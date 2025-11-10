/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'pastel-pink': {
          50: '#FFF5F7',
          100: '#FFE4EB',
          200: '#FFD1DC',
          300: '#FFB3C6',
          400: '#FF8FAB',
          500: '#FF6B95',
          600: '#FF477D',
        },
        'pastel-yellow': {
          50: '#FFFEF0',
          100: '#FFFBD1',
          200: '#FFF8A8',
          300: '#FFF275',
          400: '#FFED4E',
          500: '#FFE81F',
          600: '#FFE000',
        },
        'pastel-purple': {
          50: '#F9F5FF',
          100: '#F3E8FF',
          200: '#E9D5FF',
          300: '#D8B4FE',
          400: '#C084FC',
          500: '#A855F7',
        },
        'pastel-red': {
          50: '#FFF1F2',
          100: '#FFE4E6',
          200: '#FECDD3',
          300: '#FDA4AF',
          400: '#FB7185',
          500: '#F43F5E',
          600: '#E11D48',
        },
      },
    },
  },
  plugins: [],
}
