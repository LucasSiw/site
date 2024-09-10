const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const inicio_btn = document.querySelector("#inicio"); 
const container = document.querySelector(".container");

// Alterna o modo de inscrição e login
sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

inicio_btn.addEventListener("click", () => {
  window.location.href = 'telahome.html';
});