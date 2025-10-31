"use strict";

window.addEventListener("load", () => {
  const form = document.getElementById("form");
  if (!form) return;

  form.addEventListener("submit", event => {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.classList.add("was-validated");
  });
});

// Prévisualisation de photo
function actuPhoto() {
  const image = document.getElementById("image");
  if (!image.files.length) return;

  const reader = new FileReader();
  reader.onloadend = event => {
    document.getElementById("photo").src = event.target.result;
  };
  reader.readAsDataURL(image.files[0]);
}