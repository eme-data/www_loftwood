#!/bin/bash
# ============================================
# Loftwood — Script de migration du contenu
# Importe le contenu du site existant
# ============================================

set -euo pipefail

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
err()  { echo -e "${RED}[✗]${NC} $1"; exit 1; }

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

source "$SCRIPT_DIR/.env" 2>/dev/null || err "Fichier .env introuvable. Lancez d'abord setup.sh"

echo ""
echo "  ╔═══════════════════════════════════════════╗"
echo "  ║   Loftwood — Migration du contenu         ║"
echo "  ╚═══════════════════════════════════════════╝"
echo ""

# ============================================
# Vérification des prérequis
# ============================================

# Vérifier que les containers tournent
if ! docker compose ps --status running | grep -q wordpress; then
    err "Les containers ne tournent pas. Lancez: docker compose up -d"
fi

# ============================================
# Choix du mode de migration
# ============================================

echo "  Méthode de migration :"
echo ""
echo "  1) Dump SQL (recommandé — importe tout : pages, posts, ACF, menus)"
echo "  2) Export XML WordPress (partiel — pas les données ACF)"
echo "  3) Uploads uniquement (médias seulement)"
echo ""
read -rp "  Choix [1/2/3] : " MIGRATION_MODE
MIGRATION_MODE=${MIGRATION_MODE:-1}

# ============================================
# 1. Migration des médias (uploads)
# ============================================

migrate_uploads() {
    local UPLOADS_SRC="$SCRIPT_DIR/public_html/wp-content/uploads"

    if [[ ! -d "$UPLOADS_SRC" ]]; then
        read -rp "  Chemin vers le dossier uploads du site existant : " UPLOADS_SRC
    fi

    if [[ ! -d "$UPLOADS_SRC" ]]; then
        warn "Dossier uploads introuvable : $UPLOADS_SRC"
        return 1
    fi

    log "Copie des médias vers le container WordPress..."

    # Copier les uploads dans le volume WordPress
    # On exclut les dossiers de cache/backup
    docker compose cp "$UPLOADS_SRC/." wordpress:/var/www/html/wp-content/uploads/

    # Corriger les permissions
    docker compose exec -T -u root wordpress bash -c \
        "chown -R www-data:www-data /var/www/html/wp-content/uploads && chmod -R 755 /var/www/html/wp-content/uploads"

    log "Médias copiés avec succès."
}

# ============================================
# 2. Migration via dump SQL
# ============================================

migrate_sql() {
    echo ""
    echo "  Le dump SQL doit être un fichier .sql ou .sql.gz"
    echo "  Exportez-le depuis phpMyAdmin ou Hostinger panel."
    echo ""
    read -rp "  Chemin vers le dump SQL : " SQL_DUMP

    if [[ ! -f "$SQL_DUMP" ]]; then
        err "Fichier introuvable : $SQL_DUMP"
    fi

    # Détecter le préfixe de table de l'ancien site
    OLD_PREFIX="wp_"
    read -rp "  Préfixe des tables de l'ancien site [$OLD_PREFIX] : " INPUT_PREFIX
    OLD_PREFIX=${INPUT_PREFIX:-$OLD_PREFIX}

    # Détecter l'ancien domaine
    read -rp "  URL de l'ancien site (ex: https://www.loftwood.fr) : " OLD_URL
    OLD_URL=${OLD_URL:-https://www.loftwood.fr}

    log "Import du dump SQL..."

    # Copier le dump dans le container
    if [[ "$SQL_DUMP" == *.gz ]]; then
        docker compose cp "$SQL_DUMP" db:/tmp/dump.sql.gz
        docker compose exec -T db bash -c \
            "gunzip -f /tmp/dump.sql.gz"
    else
        docker compose cp "$SQL_DUMP" db:/tmp/dump.sql
    fi

    # Vider la base actuelle et importer
    docker compose exec -T db bash -c \
        "mariadb -u root -p\${MARIADB_ROOT_PASSWORD} \${MARIADB_DATABASE} < /tmp/dump.sql"

    log "Dump SQL importé."

    # Search & Replace des URLs
    log "Remplacement des URLs..."
    docker compose exec -T -u root wpcli wp search-replace \
        "$OLD_URL" \
        "$WORDPRESS_URL" \
        --all-tables \
        --allow-root \
        --precise \
        --skip-columns=guid

    # Si l'ancien site utilisait http aussi
    OLD_URL_HTTP=$(echo "$OLD_URL" | sed 's|https://|http://|')
    docker compose exec -T -u root wpcli wp search-replace \
        "$OLD_URL_HTTP" \
        "$WORDPRESS_URL" \
        --all-tables \
        --allow-root \
        --precise \
        --skip-columns=guid \
        2>/dev/null || true

    # Remplacer aussi sans www
    OLD_URL_NOWWW=$(echo "$OLD_URL" | sed 's|://www\.|://|')
    if [[ "$OLD_URL_NOWWW" != "$OLD_URL" ]]; then
        docker compose exec -T -u root wpcli wp search-replace \
            "$OLD_URL_NOWWW" \
            "$WORDPRESS_URL" \
            --all-tables \
            --allow-root \
            --precise \
            --skip-columns=guid \
            2>/dev/null || true
    fi

    log "URLs mises à jour."

    # Mettre à jour le mot de passe admin
    log "Création/mise à jour du compte admin..."
    docker compose exec -T -u root wpcli wp user create \
        "$WORDPRESS_ADMIN_USER" \
        "$WORDPRESS_ADMIN_EMAIL" \
        --user_pass="$WORDPRESS_ADMIN_PASSWORD" \
        --role=administrator \
        --allow-root \
        2>/dev/null || \
    docker compose exec -T -u root wpcli wp user update \
        "$WORDPRESS_ADMIN_USER" \
        --user_pass="$WORDPRESS_ADMIN_PASSWORD" \
        --allow-root \
        2>/dev/null || true

    log "Compte admin configuré."
}

# ============================================
# 3. Migration via XML
# ============================================

migrate_xml() {
    echo ""
    echo "  Exportez le XML depuis l'admin WP existant :"
    echo "  Outils > Exporter > Tout le contenu > Télécharger"
    echo ""
    read -rp "  Chemin vers le fichier XML : " XML_FILE

    if [[ ! -f "$XML_FILE" ]]; then
        err "Fichier introuvable : $XML_FILE"
    fi

    # Installer le plugin d'import
    log "Installation de l'importeur WordPress..."
    docker compose exec -T -u root wpcli wp plugin install wordpress-importer --activate --allow-root

    # Copier le XML dans le container
    docker compose cp "$XML_FILE" wpcli:/tmp/export.xml

    # Importer
    log "Import du contenu XML..."
    docker compose exec -T -u root wpcli wp import /tmp/export.xml \
        --authors=create \
        --allow-root

    log "Contenu XML importé."
}

# ============================================
# 4. Post-migration
# ============================================

post_migration() {
    log "Opérations post-migration..."

    # Activer le thème Loftwood
    docker compose exec -T -u root wpcli wp theme activate loftwood --allow-root

    # Régénérer les thumbnails
    log "Régénération des miniatures (peut prendre du temps)..."
    docker compose exec -T -u root wpcli wp media regenerate --yes --allow-root 2>/dev/null || warn "Régénération partielle"

    # Flush les permaliens
    docker compose exec -T -u root wpcli wp rewrite structure '/%postname%/' --allow-root
    docker compose exec -T -u root wpcli wp rewrite flush --allow-root

    # Flush le cache
    docker compose exec -T -u root wpcli wp cache flush --allow-root 2>/dev/null || true

    # Vérifier les plugins actifs
    log "Plugins actifs :"
    docker compose exec -T -u root wpcli wp plugin list --status=active --allow-root

    # Résumé
    echo ""
    log "Statistiques du contenu importé :"
    echo -n "  Pages :       "; docker compose exec -T -u root wpcli wp post list --post_type=page --format=count --allow-root
    echo -n "  Articles :    "; docker compose exec -T -u root wpcli wp post list --post_type=post --format=count --allow-root
    echo -n "  Programmes :  "; docker compose exec -T -u root wpcli wp post list --post_type=programmes --format=count --allow-root 2>/dev/null || echo "0"
    echo -n "  Médias :      "; docker compose exec -T -u root wpcli wp post list --post_type=attachment --format=count --allow-root

    echo ""
    echo "  ╔═══════════════════════════════════════════════╗"
    echo "  ║   Migration terminée !                        ║"
    echo "  ║                                               ║"
    echo "  ║   Site :  $WORDPRESS_URL"
    echo "  ║   Admin : $WORDPRESS_URL/wp-admin"
    echo "  ║                                               ║"
    echo "  ╚═══════════════════════════════════════════════╝"
    echo ""
    echo "  Prochaines étapes :"
    echo "  1. Installer ACF Pro dans l'admin"
    echo "  2. Vérifier les menus (Apparence > Menus)"
    echo "  3. Vérifier la page d'accueil (Réglages > Lecture)"
    echo "  4. Tester les pages et programmes"
    echo ""
}

# ============================================
# Exécution
# ============================================

case "$MIGRATION_MODE" in
    1)
        log "Mode : Migration SQL complète"
        migrate_uploads
        migrate_sql
        post_migration
        ;;
    2)
        log "Mode : Import XML"
        migrate_uploads
        migrate_xml
        post_migration
        ;;
    3)
        log "Mode : Uploads uniquement"
        migrate_uploads
        log "Médias importés. Pensez à lancer la régénération :"
        echo "  docker compose exec -u root wpcli wp media regenerate --yes --allow-root"
        ;;
    *)
        err "Choix invalide"
        ;;
esac
