// <!-- JavaScript for icons interaction -->

    let userBox = document.querySelector('.account-box');
    let navbar = document.querySelector('.navbar');

    document.querySelector('#user-btn').onclick = () => {
        userBox.classList.toggle('active');
        navbar.classList.remove('active');
    }

    document.querySelector('#menu-btn').onclick = () => {
        navbar.classList.toggle('active');
        userBox.classList.remove('active');
    }

    window.onscroll = () => {
        userBox.classList.remove('active');
        navbar.classList.remove('active');
    }
    

