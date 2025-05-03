
document.addEventListener("DOMContentLoaded", () => {
    const loBox = document.querySelector('.lo-box');
    const loginLink = document.querySelector('.login-link');
    const registerLink = document.querySelector('.register-link');

    registerLink.addEventListener('click', () =>{
        loBox.classList.add('active');
    });

    loginLink.addEventListener('click', () =>{
        loBox.classList.remove('active');
    });

});