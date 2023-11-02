const mobileMenuBtn = document.querySelector('#mobile-menu');
const sideBar = document.querySelector('.sidebar');

if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', function () {
        sideBar.classList.toggle('mostrar');
    });
}