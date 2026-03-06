// CodeCraft — main.js

// Mobile nav toggle
const toggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');
if (toggle) toggle.addEventListener('click', () => navLinks.classList.toggle('open'));

// Animate on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
        if (e.isIntersecting) {
            e.target.style.transition = `opacity 0.5s ${i * 0.05}s ease, transform 0.5s ${i * 0.05}s ease`;
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });

document.querySelectorAll('.card, .event-item, .material-row, .post-card, .stat-block').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(16px)';
    observer.observe(el);
});
