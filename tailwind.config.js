/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php", // Scan all PHP files in your theme
    "./src/**/*.{html,js}", // Scan all HTML and JS files in the src folder
  ],
  theme: {
    extend: {
      colors: {
        "app-gray": "#767677",
        "custom-blue": "#007BFF",
      },
    },
  },
  plugins: [],
};
