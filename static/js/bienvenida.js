// archivo para renderizar la imagen de la home page de bienvenida

const navbar = document.querySelector(".navbar");
const welcome = document.querySelector(".welcome");
const navbarToggle = document.querySelector("#navbarNav");

const resizeBackgroundImg = () => {
  const height = window.innerHeight - navbar.clientHeight;
  welcome.style.height = `${height}px`;
};

navbarToggle.ontransitionend = resizeBackgroundImg;
navbarToggle.ontransitionstart = resizeBackgroundImg;
window.onresize = resizeBackgroundImg;
window.onload = resizeBackgroundImg;
