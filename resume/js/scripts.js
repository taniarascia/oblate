var html = document.querySelector("html");
var toggleOpen = document.getElementById('toggle-open');
var toggleClose = document.getElementById('toggle-close');
var navMenu = document.getElementsByClassName('nav-menu')[0];
//var card = document.querySelectorAll('.card');

html.addEventListener('click', function () {
  navMenu.classList.remove('open');
});

toggleOpen.addEventListener('click', function (e) {
  e.stopPropagation();
  navMenu.classList.toggle('open');
});

toggleClose.addEventListener('click', function (e) {
  e.stopPropagation();
  navMenu.classList.toggle('open');
});

// card.forEach(c => {
//   c.addEventListener('click', function () {
//   });
// });