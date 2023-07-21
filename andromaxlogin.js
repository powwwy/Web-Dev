document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.querySelector("#login");
    const createAccountForm = document.querySelector("#createAccount");
    const formTitle = document.querySelector("#formTitle");
  
    document.querySelector("#linkCreateAccount").addEventListener("click", e => {
      e.preventDefault();
      loginForm.classList.add("form--hidden");
      createAccountForm.classList.remove("form--hidden");
      formTitle.textContent = "Sign up for a fun rock experience";
    });
  
    document.querySelector("#linkLogin").addEventListener("click", e => {
      e.preventDefault();
      createAccountForm.classList.add("form--hidden");
      loginForm.classList.remove("form--hidden");
      formTitle.textContent = "Login for a fun rock experience";
    });
  });
