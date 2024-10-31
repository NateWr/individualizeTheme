/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./templates/**/*.tpl"
  ],
  safelist: [
    'overflow-hidden',
  ],
  blocklist: [
    // Prevent clash with Bootstrap's collapse
    // component
    'collapse',
  ],
  theme: {
    extend: {
      screens: {
        '3xl': '1920px',
      }
    },
  },
  plugins: [],
}

