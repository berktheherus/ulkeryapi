document.addEventListener('DOMContentLoaded', () => {
    // --- DYNAMIC HEADER ---
    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // --- HERO CAROUSEL ---
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('.indicator');
    const prevButton = document.querySelector('.carousel-arrow-left');
    const nextButton = document.querySelector('.carousel-arrow-right');
    let currentSlideIndex = 0;
    let slideInterval;

    if (slides.length > 0) {
        function showSlide(index) {
            const lastActiveSlide = document.querySelector('.slide.active');
            if (lastActiveSlide) {
                lastActiveSlide.classList.remove('active');
            }
            
            slides.forEach(slide => slide.classList.remove('prev'));
            if (lastActiveSlide) {
                lastActiveSlide.classList.add('prev');
            }

            slides[index].classList.add('active');

            indicators.forEach(ind => ind.classList.remove('active'));
            indicators[index].classList.add('active');

            currentSlideIndex = index;
        }

        function nextSlide() {
            const nextIndex = (currentSlideIndex + 1) % slides.length;
            showSlide(nextIndex);
        }

        function previousSlide() {
            const prevIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
            showSlide(prevIndex);
        }

        function goToSlide(index) {
            showSlide(index);
            resetAutoSlide();
        }

        function startAutoSlide() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 7000); // Change slide every 7 seconds
        }

        function resetAutoSlide() {
            startAutoSlide();
        }

        // Event Listeners for Carousel
        if (nextButton) nextButton.addEventListener('click', () => { previousSlide(); resetAutoSlide(); });
        if (prevButton) prevButton.addEventListener('click', () => { nextSlide(); resetAutoSlide(); });

        indicators.forEach(indicator => {
            indicator.addEventListener('click', (e) => {
                const slideIndex = parseInt(e.target.getAttribute('data-slide-to'));
                goToSlide(slideIndex);
            });
        });

        document.querySelector('.hero-carousel').addEventListener('mouseenter', () => clearInterval(slideInterval));
        document.querySelector('.hero-carousel').addEventListener('mouseleave', startAutoSlide);

        // Initialize Carousel
        showSlide(0);
        startAutoSlide();
    }

    // --- SMOOTH SCROLLING FOR ANCHOR LINKS ---
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const headerOffset = document.querySelector('.header').offsetHeight;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // --- SCROLL-BASED ANIMATIONS (Intersection Observer) ---
    const scrollElements = document.querySelectorAll('.animate-on-scroll');
    const elementObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    scrollElements.forEach(el => {
        elementObserver.observe(el);
    });

    // --- STATS COUNTER ANIMATION ---
    const statsSection = document.querySelector('#stats');
    const counters = document.querySelectorAll('.counter');
    let hasCounterAnimated = false;

    const animateCounters = () => {
        if (hasCounterAnimated) return;
        hasCounterAnimated = true;

        counters.forEach(counter => {
            counter.innerText = '0';
            const target = +counter.getAttribute('data-target');
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps

            let current = 0;
            const updateCount = () => {
                current += increment;
                if (current < target) {
                    counter.innerText = Math.ceil(current);
                    requestAnimationFrame(updateCount);
                } else {
                    counter.innerText = target;
                }
            };
            requestAnimationFrame(updateCount);
        });
    };

    if (statsSection) {
        const statsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        statsObserver.observe(statsSection);
    }

    // --- DETAILED INFO BUTTON ---
    const detailedInfoBtn = document.getElementById('detailed-info-btn');
    if (detailedInfoBtn) {
        detailedInfoBtn.addEventListener('click', () => {
            const contactSection = document.querySelector('#contact'); // Assuming you have a contact section with this ID
            if (contactSection) {
                contactSection.scrollIntoView({ behavior: 'smooth' });
            } else {
                // Fallback if contact section doesn't exist
                alert('İletişim bölümü yakında eklenecektir.');
            }
        });
    }

    // --- MOBILE HAMBURGER MENU ---
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }
});
