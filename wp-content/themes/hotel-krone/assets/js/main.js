/**
 * Hotel Krone — Main JavaScript
 * Vanilla JS: Parallax, Sticky Header, Scroll Animations,
 * Stats Counter, Testimonials Slider, Gallery Lightbox, Mobile Menu
 */
(function () {
    'use strict';

    // ── Utility ──────────────────────────────────────────────────────────────
    var raf = window.requestAnimationFrame || function (cb) { setTimeout(cb, 16); };
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function $(sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }
    function on(el, ev, fn) { el && el.addEventListener(ev, fn, { passive: true }); }
    function off(el, ev, fn) { el && el.removeEventListener(ev, fn); }

    // ── 1. Sticky Header with Glassmorphism ──────────────────────────────────
    var header = $('.site-header');

    function updateHeader() {
        if (!header) return;
        if (window.scrollY > 80) {
            header.classList.remove('hk-transparent');
            header.classList.add('hk-scrolled');
        } else {
            header.classList.add('hk-transparent');
            header.classList.remove('hk-scrolled');
        }
    }

    if (header) {
        if (header.classList.contains('hk-transparent')) {
            on(window, 'scroll', updateHeader);
            updateHeader();
        }
    }

    // ── 2. Parallax Hero ─────────────────────────────────────────────────────
    var heroBg = $('.hk-hero-bg img');

    if (heroBg && !prefersReducedMotion) {
        var heroParallax = function () {
            var scrolled = window.scrollY;
            var limit    = window.innerHeight;
            if (scrolled <= limit) {
                heroBg.style.transform = 'translateY(' + (scrolled * 0.35) + 'px)';
            }
        };

        var parallaxRunning = false;
        on(window, 'scroll', function () {
            if (!parallaxRunning) {
                parallaxRunning = true;
                raf(function () {
                    heroParallax();
                    parallaxRunning = false;
                });
            }
        });
    }

    // ── 3. Scroll Animations (IntersectionObserver) ──────────────────────────
    var animatedEls = $$('.hk-animate, .hk-animate-left, .hk-animate-right');

    if (animatedEls.length && 'IntersectionObserver' in window && !prefersReducedMotion) {
        var animObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('hk-animated');
                    animObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        animatedEls.forEach(function (el) { animObserver.observe(el); });
    } else {
        // Fallback: show everything immediately
        animatedEls.forEach(function (el) { el.classList.add('hk-animated'); });
    }

    // ── 4. Stats Counter ─────────────────────────────────────────────────────
    function animateCounter(el, target, duration) {
        var start     = null;
        var startVal  = 0;
        var isFloat   = target.toString().includes('.');
        var decimals  = isFloat ? 1 : 0;

        function step(timestamp) {
            if (!start) start = timestamp;
            var progress = Math.min((timestamp - start) / duration, 1);
            var ease     = 1 - Math.pow(1 - progress, 3); // cubic ease-out
            var current  = startVal + (target - startVal) * ease;
            el.textContent = isFloat ? current.toFixed(decimals) : Math.round(current).toLocaleString();
            if (progress < 1) raf(step);
        }

        raf(step);
    }

    var statNumbers = $$('.hk-stat-number[data-target]');

    if (statNumbers.length && 'IntersectionObserver' in window && !prefersReducedMotion) {
        var statsObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var el     = entry.target;
                    var target = parseFloat(el.dataset.target);
                    animateCounter(el, target, 2000);
                    statsObserver.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        statNumbers.forEach(function (el) { statsObserver.observe(el); });
    } else {
        statNumbers.forEach(function (el) { el.textContent = el.dataset.target; });
    }

    // ── 5. Testimonials Slider ───────────────────────────────────────────────
    var sliderTrack = $('.hk-testimonials-track');
    var dotsWrap    = $('.hk-slider-dots');

    if (sliderTrack) {
        var slides     = $$('.hk-testimonial-slide', sliderTrack);
        var totalSlides= slides.length;
        var current    = 0;
        var slideWidth = 0;
        var perView    = 3;
        var autoTimer  = null;

        function getPerView() {
            if (window.innerWidth < 768) return 1;
            if (window.innerWidth < 1024) return 2;
            return 3;
        }

        function getMaxIndex() {
            return Math.max(0, totalSlides - perView);
        }

        function goToSlide(idx) {
            perView = getPerView();
            var maxIdx = getMaxIndex();
            current = Math.min(Math.max(idx, 0), maxIdx);
            var translateX = -(current * (100 / perView));
            sliderTrack.style.transform = 'translateX(' + translateX + '%)';
            updateDots();
        }

        function updateDots() {
            if (!dotsWrap) return;
            var maxIdx = getMaxIndex();
            $$('.hk-dot', dotsWrap).forEach(function (dot, i) {
                dot.classList.toggle('is-active', i === current);
            });
        }

        function buildDots() {
            if (!dotsWrap) return;
            dotsWrap.innerHTML = '';
            perView = getPerView();
            var count = getMaxIndex() + 1;
            for (var i = 0; i < count; i++) {
                var dot = document.createElement('button');
                dot.className = 'hk-dot' + (i === 0 ? ' is-active' : '');
                dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                dot.dataset.index = i;
                dot.addEventListener('click', function () {
                    goToSlide(parseInt(this.dataset.index));
                    resetAuto();
                });
                dotsWrap.appendChild(dot);
            }
        }

        function startAuto() {
            autoTimer = setInterval(function () {
                var maxIdx = getMaxIndex();
                goToSlide(current >= maxIdx ? 0 : current + 1);
            }, 5000);
        }

        function resetAuto() {
            clearInterval(autoTimer);
            startAuto();
        }

        // Touch drag support
        var touchStartX = 0;
        var touchEndX   = 0;

        sliderTrack.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        sliderTrack.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            var diff  = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                goToSlide(diff > 0 ? current + 1 : current - 1);
                resetAuto();
            }
        }, { passive: true });

        buildDots();
        startAuto();

        window.addEventListener('resize', function () {
            buildDots();
            goToSlide(current);
        });
    }

    // ── 6. Gallery Lightbox ──────────────────────────────────────────────────
    var galleryItems = $$('.hk-gallery-item');
    var lightboxImages = galleryItems.map(function (item) {
        var img = $('img', item);
        return {
            src:   img ? img.src : '',
            alt:   img ? img.alt : '',
        };
    }).filter(function (item) { return item.src; });

    var lightbox = null;
    var currentLightbox = 0;

    if (galleryItems.length) {
        // Create lightbox
        lightbox = document.createElement('div');
        lightbox.className = 'hk-lightbox';
        lightbox.innerHTML =
            '<button class="hk-lightbox-prev" aria-label="Previous">&#10094;</button>' +
            '<img src="" alt="" loading="lazy">' +
            '<button class="hk-lightbox-next" aria-label="Next">&#10095;</button>' +
            '<button class="hk-lightbox-close" aria-label="Close">&times;</button>';
        document.body.appendChild(lightbox);

        var lbImg   = $('img', lightbox);
        var lbPrev  = $('.hk-lightbox-prev', lightbox);
        var lbNext  = $('.hk-lightbox-next', lightbox);
        var lbClose = $('.hk-lightbox-close', lightbox);

        function openLightbox(idx) {
            currentLightbox = idx;
            lbImg.src = lightboxImages[idx].src;
            lbImg.alt = lightboxImages[idx].alt;
            lightbox.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        function nextLightbox() {
            currentLightbox = (currentLightbox + 1) % lightboxImages.length;
            lbImg.src = lightboxImages[currentLightbox].src;
        }

        function prevLightbox() {
            currentLightbox = (currentLightbox - 1 + lightboxImages.length) % lightboxImages.length;
            lbImg.src = lightboxImages[currentLightbox].src;
        }

        galleryItems.forEach(function (item, idx) {
            item.addEventListener('click', function () { openLightbox(idx); });
        });

        lbClose.addEventListener('click', closeLightbox);
        lbNext.addEventListener('click', nextLightbox);
        lbPrev.addEventListener('click', prevLightbox);

        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) closeLightbox();
        });

        document.addEventListener('keydown', function (e) {
            if (!lightbox.classList.contains('is-open')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextLightbox();
            if (e.key === 'ArrowLeft')  prevLightbox();
        });
    }

    // ── 7. Mobile Menu ───────────────────────────────────────────────────────
    var menuToggle = $('.hk-menu-toggle');
    var primaryNav = $('.primary-nav');

    if (menuToggle && primaryNav) {
        menuToggle.addEventListener('click', function () {
            var open = primaryNav.classList.toggle('is-open');
            menuToggle.classList.toggle('is-active', open);
            menuToggle.setAttribute('aria-expanded', open);
            document.body.style.overflow = open ? 'hidden' : '';
        });

        // Close on nav link click (mobile)
        $$('a', primaryNav).forEach(function (link) {
            link.addEventListener('click', function () {
                primaryNav.classList.remove('is-open');
                menuToggle.classList.remove('is-active');
                menuToggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            });
        });
    }

    // ── 8. Floating Booking Bar ──────────────────────────────────────────────
    var floatingBar = $('.hk-booking-bar-float');
    var heroSection = $('.hk-hero');

    if (floatingBar && heroSection) {
        var barObserver = new IntersectionObserver(function (entries) {
            floatingBar.classList.toggle('is-visible', !entries[0].isIntersecting);
        }, { threshold: 0.1 });

        barObserver.observe(heroSection);
    }

    // ── 9. Smooth Scroll for Anchor Links ────────────────────────────────────
    $$('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                var offset = (header ? header.offsetHeight : 80) + 20;
                var top    = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });

    // ── 10. Booking Bar Date Validation ──────────────────────────────────────
    $$('.hk-bb-check-in').forEach(function (input) {
        input.addEventListener('change', function () {
            var bar = this.closest('form') || document;
            var outInput = $('.hk-bb-check-out', bar);
            if (outInput && this.value) {
                outInput.min = this.value;
                if (outInput.value && outInput.value <= this.value) {
                    outInput.value = '';
                }
            }
        });
    });

    // Set today as min for check-in inputs
    var today = new Date().toISOString().split('T')[0];
    $$('.hk-bb-check-in').forEach(function (el) { el.min = today; });

    // ── 11. Booking Bar Form Redirect ─────────────────────────────────────────
    $$('.hk-bb-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var checkIn  = form.querySelector('[name="check_in"]')  && form.querySelector('[name="check_in"]').value;
            var checkOut = form.querySelector('[name="check_out"]') && form.querySelector('[name="check_out"]').value;
            var adults   = form.querySelector('[name="adults"]')   && form.querySelector('[name="adults"]').value;

            // Find booking page URL
            var bookingSection = document.getElementById('booking') || document.querySelector('.hk-cta');
            if (bookingSection) {
                var offset = (header ? header.offsetHeight : 80) + 20;
                window.scrollTo({
                    top: bookingSection.getBoundingClientRect().top + window.scrollY - offset,
                    behavior: 'smooth'
                });
                // Pre-fill booking widget if exists
                if (checkIn && window.hbPublic) {
                    var ciInput = document.getElementById('hb-check-in');
                    var coInput = document.getElementById('hb-check-out');
                    var adInput = document.getElementById('hb-adults');
                    if (ciInput) ciInput.value = checkIn;
                    if (coInput) coInput.value = checkOut;
                    if (adInput) adInput.value = adults;
                }
            }
        });
    });

})();
