document.addEventListener('DOMContentLoaded', () => {
    const projectCards = document.querySelectorAll('.project-card');

    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Elem a látható tartományba került
                entry.target.style.animation = `fadeInUp 0.5s ease-out forwards`;
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    projectCards.forEach(card => {
        observer.observe(card);
    });
});