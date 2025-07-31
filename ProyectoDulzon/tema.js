// tema.js
window.addEventListener('DOMContentLoaded', () => {
  const tema = localStorage.getItem('tema');
  if (tema === 'oscuro') {
    document.body.classList.add('tema-oscuro');
    const navbar = document.querySelector('.navbar');
    const botones = document.querySelectorAll('button');
    if (navbar) navbar.classList.add('tema-oscuro');
    botones.forEach(b => b.classList.add('tema-oscuro'));
  }
});
