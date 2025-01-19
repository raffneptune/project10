let navbar = document.querySelector('.header .flex .navbar');
let userBox = document.querySelector('.header .flex .account-box');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   userBox.classList.toggle('active'); 
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   userBox.classList.remove('active');
}