import flowbite from "flowbite/plugin";

export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        bluegray: 'oklch(70.7% 0.165 254.624)'
      }
    },
  },
  plugins: [
    flowbite
  ],
};
