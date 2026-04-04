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
        primary: '#1a1a1a',
        secondary: '#6b7280',
        accent: '#d97706',
      },
    },
  },
  plugins: [],
};
