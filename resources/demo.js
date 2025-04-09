import Alpine from "alpinejs";
import { persist } from "@alpinejs/persist";
import countdown from "./js/countdown.js";
import Swiper from "swiper";
import "swiper/css";
import { Navigation, Scrollbar } from "swiper/modules";

Alpine.plugin(persist);

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
  new Swiper("#my-swiper", {
    modules: [Navigation, Scrollbar],
    slidesPerView: 1,
    spaceBetween: 40,
    direction: "horizontal",
    loop: false,
    speed: 500,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    scrollbar: {
      el: ".swiper-scrollbar",
      draggable: true,
    },

    breakpoints: {
      576: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 2,
      },
      1210: {
        slidesPerView: 3,
      },
    },
  });

  Alpine.data("countdown", (targetDate) => countdown(targetDate));

  Alpine.store("darkMode", {
    isDark: false,
    theme: Alpine.$persist(null),
    init: function () {
      const darkMode = this;
      this.checkDark();

      window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", ({ matches }) => {
        darkMode.checkDark();
      });
    },
    checkDark: function () {
      this.isDark = this.theme === "dark" || (this.theme !== "light" && this.checkSystemDark());
      return this.isDark;
    },
    checkSystemDark: function () {
      const mediaQueryList = window.matchMedia("(prefers-color-scheme: dark)");
      return mediaQueryList.matches;
    },
    setDarkTheme: function () {
      this.theme = "dark";
      this.isDark = true;
    },
    setLightTheme: function () {
      this.theme = "light";
      this.isDark = false;
    },
    switchTheme: function () {
      this.theme = this.isDark ? "light" : "dark";
      this.isDark = !this.isDark;
    },
    hasTheme: function () {
      return this.theme !== null;
    },
    clearTheme: function () {
      this.theme = null;
      this.dark = this.checkDark();
    },
  });
});

Alpine.start();
