// add class navbarDark on navbar scroll
const header = document.querySelector('.navbar');

window.onscroll = function() {
    var top = window.scrollY;
    if(top >=20) {
        header.classList.add('navbarDark');
        header.classList.add('navbar-dark');
    }
    else {
        header.classList.remove('navbarDark');
        header.classList.remove('navbar-dark');
        header.classList.add('navbar-light');
    }
}

