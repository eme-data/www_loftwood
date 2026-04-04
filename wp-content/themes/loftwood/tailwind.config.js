/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './templates/**/*.html',
    './parts/**/*.html',
    './patterns/**/*.php',
    './src/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        bronze: '#b9a380',
        'bronze-light': '#d4c4a8',
        'bronze-dark': '#9a7f6d',
        slate: '#535D6A',
        'deep-purple': '#2E2269',
        cream: '#FBFAF8',
        'warm-white': '#F7EEE8',
      },
      fontFamily: {
        inter: ['Inter', 'system-ui', 'sans-serif'],
        montserrat: ['Montserrat', 'sans-serif'],
      },
      transitionTimingFunction: {
        'loftwood': 'cubic-bezier(0.16, 1, 0.3, 1)',
        'enter': 'cubic-bezier(0.0, 0, 0.2, 1)',
        'exit': 'cubic-bezier(0.4, 0, 1, 1)',
      },
      transitionDuration: {
        '400': '400ms',
        '600': '600ms',
      },
      keyframes: {
        'reveal-up': {
          from: { opacity: '0', transform: 'translateY(30px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        'reveal-left': {
          from: { opacity: '0', transform: 'translateX(-30px)' },
          to: { opacity: '1', transform: 'translateX(0)' },
        },
        'reveal-right': {
          from: { opacity: '0', transform: 'translateX(30px)' },
          to: { opacity: '1', transform: 'translateX(0)' },
        },
        'reveal-scale': {
          from: { opacity: '0', transform: 'scale(0.95)' },
          to: { opacity: '1', transform: 'scale(1)' },
        },
        'ken-burns': {
          from: { transform: 'scale(1)' },
          to: { transform: 'scale(1.05)' },
        },
        'pulse-bronze': {
          '0%, 100%': { boxShadow: '0 0 0 0 rgba(185, 163, 128, 0.4)' },
          '50%': { boxShadow: '0 0 0 8px rgba(185, 163, 128, 0)' },
        },
        'counter': {
          from: { '--counter-value': '0' },
          to: { '--counter-value': 'var(--counter-target)' },
        },
      },
      animation: {
        'reveal-up': 'reveal-up 0.6s var(--ease-loftwood) both',
        'reveal-left': 'reveal-left 0.6s var(--ease-loftwood) both',
        'reveal-right': 'reveal-right 0.6s var(--ease-loftwood) both',
        'reveal-scale': 'reveal-scale 0.6s var(--ease-loftwood) both',
        'ken-burns': 'ken-burns 8s ease-out both',
        'pulse-bronze': 'pulse-bronze 2s ease-in-out infinite',
      },
    },
  },
  plugins: [],
};
