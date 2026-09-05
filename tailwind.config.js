/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        canvas: "#ffffff",
        charcoal: "#212121",
        trueBlack: "#000000",
        black: "#000000",
        sand: "#e0dacf",
        oliveChar: "#222519",
        iron: "#525252",
        stone: "#737373",
        slateBorder: "#6a6767",
        oatMilk: "#ece9e2",
        mist: "#bdbab5",
        categorySwatch: "#d1b0a4",
      },
      fontFamily: {
        display: ["'Playfair Display'", "serif"],
        sans: ["'Inter'", "sans-serif"],
      },
      fontSize: {
        caption: ["12px", { lineHeight: "1.25", letterSpacing: "0.3px" }],
        "body-sm": ["14px", { lineHeight: "1.43", letterSpacing: "0.35px" }],
        body: ["16px", { lineHeight: "1.5", letterSpacing: "0.8px" }],
        subheading: ["20px", { lineHeight: "1.4", letterSpacing: "1px" }],
        "heading-sm": ["24px", { lineHeight: "1.33", letterSpacing: "1.2px" }],
        heading: ["40px", { lineHeight: "1.2" }],
      },
      letterSpacing: {
        wide10: "0.10em",
        caption: "0.3px",
        "body-sm": "0.35px",
        body: "0.8px",
        subheading: "1px",
        "heading-sm": "1.2px",
      },
      borderRadius: {
        nav: "12px",
        card: "16px",
        input: "4px",
        pill: "9999px",
        surface: "20px",
        "surface-lg": "24px",
      },
      spacing: {
        "4": "4px",
        "8": "8px",
        "12": "12px",
        "16": "16px",
        "20": "20px",
        "24": "24px",
        "32": "32px",
        "40": "40px",
        "56": "56px",
        "64": "64px",
        "80": "80px",
        "108": "108px",
      },
      boxShadow: {
        subtle: "inset 0 0 0 1px #575757, 0 0 0 1px #000000",
      },
      maxWidth: {
        container: "1200px",
      },
    },
  },
  plugins: [],
}
