/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./templates/**/*.tpl"
  ],
  safelist: [
    'overflow-hidden',
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

