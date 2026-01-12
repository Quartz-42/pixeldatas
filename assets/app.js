import "./bootstrap.js";
import "./styles/app.css";
import { Chart } from "chart.js";
import ChartDataLabels from "chartjs-plugin-datalabels";

Chart.register(ChartDataLabels);

import {
  shouldPerformTransition,
  performTransition,
} from "turbo-view-transitions";

// BOUTON BACK TO TOP
document.addEventListener("DOMContentLoaded", function () {
  let toTopButton = document.getElementById("to-top-button");

  function toggleToTopButton() {
    if (
      document.body.scrollTop > 200 ||
      document.documentElement.scrollTop > 200
    ) {
      toTopButton.classList.remove("hidden");
    } else {
      toTopButton.classList.add("hidden");
    }
  }

  if (toTopButton) {
    window.onscroll = toggleToTopButton;

    window.goToTop = function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    };
  }
});

document.addEventListener("turbo:before-render", (event) => {
  if (shouldPerformTransition()) {
    event.preventDefault();
    performTransition(document.body, event.detail.newBody, async () => {
      await event.detail.resume();
    });
  }
});
document.addEventListener("turbo:load", () => {
  // View Transitions don't play nicely with Turbo cache
  if (shouldPerformTransition()) Turbo.cache.exemptPageFromCache();
});
document.addEventListener("turbo:before-frame-render", (event) => {
  if (
    shouldPerformTransition() &&
    !event.target.hasAttribute("data-skip-transition")
  ) {
    event.preventDefault();
    performTransition(event.target, event.detail.newFrame, async () => {
      await event.detail.resume();
    });
  }
});

// TOGGLE MENU MOBILE
document.addEventListener("click", function (event) {
  if (event.target.closest("#menu-toggle")) {
    const mobileMenu = document.getElementById("mobile-menu");
    if (mobileMenu) {
      mobileMenu.classList.toggle("hidden");
    }
  }
});
