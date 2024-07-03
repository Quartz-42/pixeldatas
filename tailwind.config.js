/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      width: {
        '7/10': '70%',
      },
    },
  },
  plugins: [],
}
