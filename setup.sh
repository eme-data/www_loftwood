#!/bin/bash
# ============================================
# Loftwood — Script d'installation automatique
# Ubuntu 24.04 LTS
# ============================================

set -euo pipefail

# Couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
err()  { echo -e "${RED}[✗]${NC} $1"; exit 1; }

# ============================================
# Vérifications
# ============================================

if [[ $EUID -eq 0 ]]; then
    warn "Exécution en root détectée. Poursuite de l'installation..."
    RUNNING_AS_ROOT=true
else
    RUNNING_AS_ROOT=false
fi

if ! grep -q "24.04" /etc/os-release 2>/dev/null; then
    warn "Ce script est optimisé pour Ubuntu 24.04. Votre système peut différer."
fi

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

echo ""
echo "  ╔═══════════════════════════════════════╗"
echo "  ║   Loftwood — Installation Dev Env     ║"
echo "  ║   Ubuntu 24.04                        ║"
echo "  ╚═══════════════════════════════════════╝"
echo ""

# ============================================
# 1. Mise à jour système + dépendances
# ============================================

log "Mise à jour du système..."
sudo apt-get update -qq
sudo apt-get upgrade -y -qq

log "Installation des dépendances système..."
sudo apt-get install -y -qq \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release \
    git \
    unzip \
    software-properties-common

# ============================================
# 2. Docker
# ============================================

if command -v docker &>/dev/null; then
    log "Docker déjà installé ($(docker --version))"
else
    log "Installation de Docker..."

    # Clé GPG officielle
    sudo install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    sudo chmod a+r /etc/apt/keyrings/docker.gpg

    # Dépôt Docker
    echo \
        "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
        $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
        sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

    sudo apt-get update -qq
    sudo apt-get install -y -qq docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

    # Ajouter l'utilisateur au groupe docker (inutile en root)
    if [[ "$RUNNING_AS_ROOT" == false ]]; then
        sudo usermod -aG docker "$USER"
        log "Docker installé. Groupe docker ajouté pour $USER."
        warn "Si 'docker' échoue après l'install, déconnectez-vous et reconnectez-vous (ou: newgrp docker)"
    else
        log "Docker installé (root, pas besoin du groupe docker)."
    fi
fi

# ============================================
# 3. Node.js (via NodeSource — LTS)
# ============================================

if command -v node &>/dev/null; then
    NODE_VERSION=$(node --version)
    log "Node.js déjà installé ($NODE_VERSION)"
else
    log "Installation de Node.js 22 LTS..."
    curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
    sudo apt-get install -y -qq nodejs
    log "Node.js installé ($(node --version))"
fi

# ============================================
# 4. WP-CLI
# ============================================

if command -v wp &>/dev/null; then
    log "WP-CLI déjà installé ($(wp --version))"
else
    log "Installation de WP-CLI..."
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    sudo mv wp-cli.phar /usr/local/bin/wp
    log "WP-CLI installé ($(wp --version))"
fi

# ============================================
# 5. Dépendances Node du thème
# ============================================

THEME_DIR="$SCRIPT_DIR/wp-content/themes/loftwood"

if [[ -d "$THEME_DIR" ]]; then
    log "Installation des dépendances npm du thème..."
    cd "$THEME_DIR"
    npm install --silent
    log "Build du thème..."
    npm run build
    cd "$SCRIPT_DIR"
    log "Thème compilé avec succès."
else
    err "Dossier thème introuvable: $THEME_DIR"
fi

# ============================================
# 6. Fichier .env
# ============================================

if [[ ! -f "$SCRIPT_DIR/.env" ]]; then
    log "Création du fichier .env..."
    cat > "$SCRIPT_DIR/.env" <<'ENVEOF'
# Loftwood — Configuration locale
MYSQL_ROOT_PASSWORD=loftwood_root_2026
MYSQL_DATABASE=loftwood
MYSQL_USER=loftwood
MYSQL_PASSWORD=loftwood_db_2026

WORDPRESS_DB_HOST=db
WORDPRESS_DB_USER=loftwood
WORDPRESS_DB_PASSWORD=loftwood_db_2026
WORDPRESS_DB_NAME=loftwood

# Site
WORDPRESS_URL=http://localhost:8080
WORDPRESS_TITLE=Loftwood
WORDPRESS_ADMIN_USER=admin
WORDPRESS_ADMIN_PASSWORD=admin_loftwood_2026
WORDPRESS_ADMIN_EMAIL=admin@loftwood.fr
ENVEOF
    log "Fichier .env créé. Modifiez les mots de passe si nécessaire."
else
    log "Fichier .env existant conservé."
fi

# ============================================
# 7. Mise à jour docker-compose pour .env
# ============================================

log "Mise à jour de docker-compose.yml pour utiliser .env..."
cat > "$SCRIPT_DIR/docker-compose.yml" <<'DCEOF'
services:
  wordpress:
    image: wordpress:6.7-php8.3-apache
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: ${WORDPRESS_DB_HOST}
      WORDPRESS_DB_USER: ${WORDPRESS_DB_USER}
      WORDPRESS_DB_PASSWORD: ${WORDPRESS_DB_PASSWORD}
      WORDPRESS_DB_NAME: ${WORDPRESS_DB_NAME}
      WORDPRESS_DEBUG: "true"
    volumes:
      - wordpress-data:/var/www/html
      - ./wp-content/themes/loftwood:/var/www/html/wp-content/themes/loftwood
    depends_on:
      db:
        condition: service_healthy
    restart: unless-stopped

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: ${MYSQL_DATABASE}
      MYSQL_USER: ${MYSQL_USER}
      MYSQL_PASSWORD: ${MYSQL_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
    volumes:
      - db-data:/var/lib/mysql
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 5s
      timeout: 5s
      retries: 5
    restart: unless-stopped

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      PMA_USER: ${MYSQL_USER}
      PMA_PASSWORD: ${MYSQL_PASSWORD}
    depends_on:
      - db
    restart: unless-stopped

  wpcli:
    image: wordpress:cli-php8.3
    volumes:
      - wordpress-data:/var/www/html
      - ./wp-content/themes/loftwood:/var/www/html/wp-content/themes/loftwood
    environment:
      WORDPRESS_DB_HOST: ${WORDPRESS_DB_HOST}
      WORDPRESS_DB_USER: ${WORDPRESS_DB_USER}
      WORDPRESS_DB_PASSWORD: ${WORDPRESS_DB_PASSWORD}
      WORDPRESS_DB_NAME: ${WORDPRESS_DB_NAME}
    depends_on:
      db:
        condition: service_healthy
    entrypoint: ["sh", "-c", "sleep infinity"]
    restart: unless-stopped

volumes:
  wordpress-data:
  db-data:
DCEOF

# ============================================
# 8. Script WP auto-config
# ============================================

log "Création du script de configuration WordPress..."
cat > "$SCRIPT_DIR/wp-setup.sh" <<'WPEOF'
#!/bin/bash
# Configure WordPress après le premier démarrage
set -euo pipefail

source .env

echo "[*] Attente que WordPress soit prêt..."
until curl -sf http://localhost:8080 > /dev/null 2>&1; do
    sleep 2
done
echo "[✓] WordPress accessible."

echo "[*] Correction des permissions..."
docker compose exec -T -u root wordpress bash -c "\
    mkdir -p /var/www/html/wp-content/uploads && \
    mkdir -p /var/www/html/wp-content/languages && \
    mkdir -p /var/www/html/wp-content/upgrade && \
    chown -R www-data:www-data /var/www/html/wp-content && \
    chmod -R 755 /var/www/html/wp-content"
echo "[✓] Permissions corrigées."

echo "[*] Installation de WordPress..."
docker compose exec -T -u root wpcli wp core install \
    --url="$WORDPRESS_URL" \
    --title="$WORDPRESS_TITLE" \
    --admin_user="$WORDPRESS_ADMIN_USER" \
    --admin_password="$WORDPRESS_ADMIN_PASSWORD" \
    --admin_email="$WORDPRESS_ADMIN_EMAIL" \
    --skip-email \
    --allow-root

echo "[*] Configuration de base..."
docker compose exec -T -u root wpcli wp option update blogdescription "Promotion immobilière en ossature bois" --allow-root
docker compose exec -T -u root wpcli wp option update timezone_string "Europe/Paris" --allow-root
docker compose exec -T -u root wpcli wp option update date_format "j F Y" --allow-root
docker compose exec -T -u root wpcli wp option update time_format "H:i" --allow-root

echo "[*] Installation de la langue française..."
docker compose exec -T -u root wpcli wp language core install fr_FR --allow-root
docker compose exec -T -u root wpcli wp site switch-language fr_FR --allow-root

echo "[*] Permaliens..."
docker compose exec -T -u root wpcli wp rewrite structure '/%postname%/' --allow-root
docker compose exec -T -u root wpcli wp rewrite flush --allow-root

echo "[*] Activation du thème Loftwood..."
docker compose exec -T -u root wpcli wp theme activate loftwood --allow-root

echo "[*] Suppression des thèmes par défaut..."
docker compose exec -T -u root wpcli wp theme delete twentytwentyfive --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp theme delete twentytwentyfour --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp theme delete twentytwentythree --allow-root 2>/dev/null || true

echo "[*] Installation des plugins essentiels..."
docker compose exec -T -u root wpcli wp plugin install contact-form-7 --activate --allow-root
docker compose exec -T -u root wpcli wp plugin install wordpress-seo --activate --allow-root
docker compose exec -T -u root wpcli wp plugin install svg-support --activate --allow-root

echo "[*] Suppression des plugins par défaut..."
docker compose exec -T -u root wpcli wp plugin delete hello --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp plugin delete akismet --allow-root 2>/dev/null || true

echo "[*] Création des pages de base..."
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="Accueil" --post_status=publish --allow-root
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="Nos programmes" --post_status=publish --allow-root
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="La Marque Loft Wood" --post_status=publish --allow-root
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="Pourquoi l'Ossature Bois" --post_status=publish --allow-root
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="Acheter dans le Neuf" --post_status=publish --allow-root
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="Actualités" --post_status=publish --allow-root
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="Contact" --post_status=publish --page_template="page-contact" --allow-root
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="Mentions légales" --post_status=publish --allow-root
docker compose exec -T -u root wpcli wp post create --post_type=page --post_title="Politique de confidentialité" --post_status=publish --allow-root

echo "[*] Configuration de la page d'accueil..."
FRONT_ID=$(docker compose exec -T -u root wpcli wp post list --post_type=page --name=accueil --field=ID --allow-root | tr -d '\r')
BLOG_ID=$(docker compose exec -T -u root wpcli wp post list --post_type=page --name=actualites --field=ID --allow-root | tr -d '\r')

docker compose exec -T -u root wpcli wp option update show_on_front page --allow-root
docker compose exec -T -u root wpcli wp option update page_on_front "$FRONT_ID" --allow-root
docker compose exec -T -u root wpcli wp option update page_for_posts "$BLOG_ID" --allow-root

echo "[*] Suppression du contenu par défaut..."
docker compose exec -T -u root wpcli wp post delete 1 --force --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp post delete 2 --force --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp post delete 3 --force --allow-root 2>/dev/null || true

echo "[*] Création du menu principal..."
docker compose exec -T -u root wpcli wp menu create "Menu Principal" --allow-root
docker compose exec -T -u root wpcli wp menu location assign "Menu Principal" main_menu --allow-root

PROGRAMMES_ID=$(docker compose exec -T -u root wpcli wp post list --post_type=page --name="nos-programmes" --field=ID --allow-root | tr -d '\r')
MARQUE_ID=$(docker compose exec -T -u root wpcli wp post list --post_type=page --name="la-marque-loft-wood" --field=ID --allow-root | tr -d '\r')
BOIS_ID=$(docker compose exec -T -u root wpcli wp post list --post_type=page --name="pourquoi-lossature-bois" --field=ID --allow-root | tr -d '\r')
ACHETER_ID=$(docker compose exec -T -u root wpcli wp post list --post_type=page --name="acheter-dans-le-neuf" --field=ID --allow-root | tr -d '\r')
ACTU_ID=$BLOG_ID
CONTACT_ID=$(docker compose exec -T -u root wpcli wp post list --post_type=page --name=contact --field=ID --allow-root | tr -d '\r')

docker compose exec -T -u root wpcli wp menu item add-post "Menu Principal" "$PROGRAMMES_ID" --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp menu item add-post "Menu Principal" "$MARQUE_ID" --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp menu item add-post "Menu Principal" "$BOIS_ID" --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp menu item add-post "Menu Principal" "$ACHETER_ID" --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp menu item add-post "Menu Principal" "$ACTU_ID" --allow-root 2>/dev/null || true
docker compose exec -T -u root wpcli wp menu item add-post "Menu Principal" "$CONTACT_ID" --allow-root 2>/dev/null || true

echo ""
echo "  ╔═══════════════════════════════════════════╗"
echo "  ║   WordPress configuré avec succès !       ║"
echo "  ║                                           ║"
echo "  ║   Site:       http://localhost:8080        ║"
echo "  ║   Admin:      http://localhost:8080/wp-admin"
echo "  ║   User:       $WORDPRESS_ADMIN_USER"
echo "  ║   Password:   $WORDPRESS_ADMIN_PASSWORD"
echo "  ║   phpMyAdmin: http://localhost:8081        ║"
echo "  ║                                           ║"
echo "  ║   Thème Loftwood activé.                  ║"
echo "  ╚═══════════════════════════════════════════╝"
echo ""
echo "[!] N'oubliez pas d'installer ACF Pro manuellement"
echo "    (plugin payant, non installable via WP-CLI)."
echo ""
WPEOF
chmod +x "$SCRIPT_DIR/wp-setup.sh"

# ============================================
# 9. Lancement Docker
# ============================================

log "Lancement des containers Docker..."
if [[ "$RUNNING_AS_ROOT" == true ]] || groups "$USER" | grep -q '\bdocker\b'; then
    docker compose up -d
    log "Containers démarrés."

    echo ""
    log "Lancement de la configuration WordPress..."
    bash "$SCRIPT_DIR/wp-setup.sh"
else
    warn "Vous venez d'être ajouté au groupe docker."
    warn "Exécutez ces commandes après reconnexion :"
    echo ""
    echo "  cd $SCRIPT_DIR"
    echo "  docker compose up -d"
    echo "  bash wp-setup.sh"
    echo ""
fi

# ============================================
# 10. Résumé
# ============================================

echo ""
echo "  ╔═══════════════════════════════════════════╗"
echo "  ║   Installation terminée !                 ║"
echo "  ╚═══════════════════════════════════════════╝"
echo ""
echo "  Commandes utiles :"
echo ""
echo "  Démarrer :    docker compose up -d"
echo "  Arrêter :     docker compose down"
echo "  Logs :        docker compose logs -f wordpress"
echo "  WP-CLI :      docker compose exec wpcli wp --allow-root"
echo "  Dev thème :   cd wp-content/themes/loftwood && npm run dev"
echo "  Build thème : cd wp-content/themes/loftwood && npm run build"
echo "  Reset DB :    docker compose down -v  (supprime les données !)"
echo ""
