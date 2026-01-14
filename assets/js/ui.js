document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-sm');
                navbar.style.padding = '0.5rem 0';
            } else {
                navbar.classList.remove('shadow-sm');
                navbar.style.padding = '1rem 0';
            }
        });
    }

    // Add 3D tilt effect to cards (Simple version)
    const cards = document.querySelectorAll('.event-card, .hover-3d');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        });
    });

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card, .event-card, .animate-card').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
});
document.addEventListener('DOMContentLoaded', () => {
    let currentStep = 0;
    const steps = document.querySelectorAll('.step');
    const submitBtn = document.getElementById('submitBtn');

    if (!steps.length) return; // safety check

    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.toggle('d-none', i !== index);
            step.classList.toggle('active', i === index);
        });

        submitBtn?.classList.toggle(
            'd-none',
            index !== steps.length - 1
        );
    }

    window.nextStep = function () {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
            fillPreview();
        }
    };

    window.prevStep = function () {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    };

    function fillPreview() {
        const title = document.querySelector('[name="title"]')?.value;
        const date = document.querySelector('[name="event_date"]')?.value;
        const location = document.querySelector('[name="location"]')?.value;

        if (document.getElementById('previewTitle'))
            document.getElementById('previewTitle').innerText = title || '—';

        if (document.getElementById('previewDate'))
            document.getElementById('previewDate').innerText = date || '—';

        if (document.getElementById('previewLocation'))
            document.getElementById('previewLocation').innerText =
                location || 'Online Event';
    }

    showStep(currentStep);
});
