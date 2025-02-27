/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php", // Scan all PHP files in your theme
    "./src/**/*.{html,js}", // Scan all HTML and JS files in the src folder
  ],
  theme: {
    extend: {
      colors: {
        "primary": "rgb(185 28 28)",
        "primary-light": "rgb(220 38 38)",
        "primary-dark": "rgb(135 18 18)",
        "secondary": "rgb(0 123 255)",
        "secondary-light": "rgb(0 153 255)",
        "secondary-dark": "rgb(0 93 195)",
        "success": "rgb(40 167 69)",
        "success-light": "rgb(40 197 69)",
        "success-dark": "rgb(40 137 69)",
        "app-gray": "#767677",
        "custom-blue": "#007BFF",
      },
    },
  },
  plugins: [],
};
