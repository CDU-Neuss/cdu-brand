import Alpine from "alpinejs";
import countdown from "./js/countdown.js";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
  Alpine.data("countdown", (targetDate) => countdown(targetDate));
});

Alpine.start();
