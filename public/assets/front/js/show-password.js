
"use strict"

let createpassword = (inputId, ele) => {
    let passwordField = document.getElementById(inputId);
    if (!passwordField) {
        console.error("No element found with ID:", inputId);
        return;
    }

    passwordField.type = passwordField.type === "password" ? "text" : "password";

    let icon = ele.querySelector('i');
    if (!icon) return;

    if (icon.classList.contains("fa-eye-slash")) {
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
};
