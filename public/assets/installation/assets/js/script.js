"use strict";

// Enable tooltips
const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

// Change SVG Color
const images = document.querySelectorAll("img.svg");
images.forEach(function (img) {
  const imgID = img.getAttribute("id");
  const imgClass = img.getAttribute("class");
  const imgURL = img.getAttribute("src");

  fetch(imgURL)
    .then((response) => response.text())
    .then((data) => {
      // Get the SVG tag, ignore the rest
      const parser = new DOMParser();
      const xmlDoc = parser.parseFromString(data, "text/xml");
      const svg = xmlDoc.getElementsByTagName("svg")[0];

      // Add replaced image's ID to the new SVG
      if (typeof imgID !== "undefined") {
        svg.setAttribute("id", imgID);
      }
      // Add replaced image's classes to the new SVG
      if (typeof imgClass !== "undefined") {
        svg.setAttribute("class", imgClass + " replaced-svg");
      }

      // Remove any invalid XML tags as per http://validator.w3.org
      svg.removeAttribute("xmlns:a");

      // Check if the viewport is set, else we gonna set it if we can.
      if (
        !svg.getAttribute("viewBox") &&
        svg.getAttribute("height") &&
        svg.getAttribute("width")
      ) {
        svg.setAttribute(
          "viewBox",
          "0 0 " + svg.getAttribute("height") + " " + svg.getAttribute("width")
        );
      }

      // Replace image with new SVG
      img.parentNode.replaceChild(svg, img);
    })
    .catch((error) => console.error(error));
});

// Toggle Password
// const togglePassword = document.querySelector(".togglePassword");
// togglePassword.addEventListener("click", function (e) {
//   const password = this.previousElementSibling;
//   if (password.getAttribute("type") === "password") {
//     this.querySelector(".eye").style.display = "block";
//     this.querySelector(".eye-off").style.display = "none";
//   } else {
//     this.querySelector(".eye-off").style.display = "block";
//     this.querySelector(".eye").style.display = "none";
//   }
//   const type =
//     password.getAttribute("type") === "password" ? "text" : "password";
//   password.setAttribute("type", type);
// });

const togglePasswords = document.querySelectorAll(".togglePassword");
togglePasswords.forEach(function (togglePassword) {
  togglePassword.addEventListener("click", function (e) {
    const password = this.previousElementSibling;
    if (password.getAttribute("type") === "password") {
      this.querySelector(".eye").style.display = "block";
      this.querySelector(".eye-off").style.display = "none";
    } else {
      this.querySelector(".eye-off").style.display = "block";
      this.querySelector(".eye").style.display = "none";
    }
    const type =
      password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
  });
});
