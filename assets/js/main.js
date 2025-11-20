/**
 * INVIRO WP Theme - Main JavaScript
 */

(function($) {
    'use strict';

    // Document Ready
    $(document).ready(function() {
        initMobileMenu();
        initSearchOverlay();
        initHeroSlider();
        initTestimonialsCarousel();
        initContactForm();
        initSmoothScroll();
        initLazyLoad();
        initStickyHeader();
    });

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const menuToggle = $('.menu-toggle');
        const navigation = $('.main-navigation');
        const navMenu = $('.nav-menu');
        const menuLinks = $('.nav-menu a');

        menuToggle.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isExpanded = navMenu.hasClass('active');
            
            // Pastikan menu ada sebelum toggle
            if (navMenu.length === 0) {
                console.warn('Menu tidak ditemukan!');
                return;
            }
            
            navMenu.toggleClass('active');
            $(this).attr('aria-expanded', !isExpanded);
            
            // Debug: cek apakah menu items ada
            const menuItems = navMenu.find('li');
            if (menuItems.length === 0) {
                console.warn('Menu items tidak ditemukan!');
            } else {
                console.log('Menu items ditemukan:', menuItems.length);
            }
            
            // Prevent body scroll when menu is open and add overlay
            if (!isExpanded) {
                $('body').addClass('menu-open').css('overflow', 'hidden');
            } else {
                $('body').removeClass('menu-open').css('overflow', '');
            }
        });

        // Close menu when clicking on a link
        menuLinks.on('click', function() {
            if ($(window).width() <= 768) {
                navMenu.removeClass('active');
                menuToggle.attr('aria-expanded', 'false');
                $('body').removeClass('menu-open').css('overflow', '');
            }
        });

        // Close menu when clicking outside or on overlay
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation, .menu-toggle, .nav-menu').length) {
                navMenu.removeClass('active');
                menuToggle.attr('aria-expanded', 'false');
                $('body').removeClass('menu-open').css('overflow', '');
            }
        });

        // Close menu on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.hasClass('active')) {
                navMenu.removeClass('active');
                menuToggle.attr('aria-expanded', 'false');
                $('body').removeClass('menu-open').css('overflow', '');
                menuToggle.focus();
            }
        });

        // Handle window resize
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                navMenu.removeClass('active');
                menuToggle.attr('aria-expanded', 'false');
                $('body').css('overflow', '');
            }
        });
    }

    /**
     * Search Overlay
     */
    function initSearchOverlay() {
        const searchToggle = $('.search-toggle');
        const searchOverlay = $('.search-overlay');
        const searchClose = $('.search-close');
        const searchField = $('.search-field');

        searchToggle.on('click', function() {
            searchOverlay.addClass('active');
            searchToggle.attr('aria-expanded', 'true');
            setTimeout(function() {
            searchField.focus();
            }, 100);
            $('body').css('overflow', 'hidden');
        });

        searchClose.on('click', function() {
            closeSearchOverlay();
        });

        searchOverlay.on('click', function(e) {
            if ($(e.target).is(searchOverlay)) {
                closeSearchOverlay();
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.hasClass('active')) {
                closeSearchOverlay();
            }
        });

        function closeSearchOverlay() {
            searchOverlay.removeClass('active');
            searchToggle.attr('aria-expanded', 'false');
            $('body').css('overflow', '');
        }
    }

    /**
     * Hero Slider
     */
    function initHeroSlider() {
        const slides = $('.hero-slide');
        const prevBtn = $('.hero-prev');
        const nextBtn = $('.hero-next');
        let currentSlide = 0;
        let slideInterval;

        if (slides.length === 0) return;

        // Create indicators
        const indicatorsContainer = $('.hero-indicators');
        slides.each(function(index) {
            const indicator = $('<div class="hero-indicator"></div>');
            if (index === 0) indicator.addClass('active');
            indicator.on('click', function() {
                goToSlide(index);
            });
            indicatorsContainer.append(indicator);
        });

        function showSlide(index) {
            slides.removeClass('active');
            slides.eq(index).addClass('active');
            $('.hero-indicator').removeClass('active');
            $('.hero-indicator').eq(index).addClass('active');
            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
        }

        function goToSlide(index) {
            showSlide(index);
            resetInterval();
        }

        function startInterval() {
            slideInterval = setInterval(nextSlide, 5000);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }

        nextBtn.on('click', function() {
            nextSlide();
            resetInterval();
        });

        prevBtn.on('click', function() {
            prevSlide();
            resetInterval();
        });

        // Auto-play slider
        if (slides.length > 1) {
            startInterval();
        }

        // Pause on hover
        $('.hero-section').on('mouseenter', function() {
            clearInterval(slideInterval);
        }).on('mouseleave', function() {
            startInterval();
        });
    }

    /**
     * Testimonials Carousel
     */
    function initTestimonialsCarousel() {
        const track = $('.testimonials-track');
        const cards = $('.testimonial-card');
        const prevBtn = $('.testimonial-prev');
        const nextBtn = $('.testimonial-next');
        let currentIndex = 0;
        const visibleCards = 3; // Always show 3 cards

        if (cards.length === 0) return;

        // Total slides based on moving 1 card at a time
        const maxIndex = cards.length - visibleCards;

        // Create indicators based on total cards
        function createIndicators() {
            const indicatorsContainer = $('.testimonials-indicators');
            indicatorsContainer.empty();
            
            // Create indicators for each possible position
            const totalIndicators = Math.min(cards.length, maxIndex + 1);
            
            for (let i = 0; i <= maxIndex; i++) {
                const indicator = $('<button class="testimonial-indicator"></button>');
                if (i === 0) indicator.addClass('active');
                indicator.on('click', function() {
                    goToSlide(i);
                });
                indicatorsContainer.append(indicator);
            }
        }

        // Update carousel position with smooth transition
        function updateCarousel(animate = true) {
            if (animate) {
                track.css('transition', 'transform 0.5s ease-in-out');
            } else {
                track.css('transition', 'none');
            }
            
            // Calculate percentage to move (each card is 33.333% width)
            const movePercentage = -(currentIndex * (100 / 3));
            track.css('transform', `translateX(${movePercentage}%)`);
            
            updateIndicators();
            updateButtons();
        }

        // Update indicators
        function updateIndicators() {
            $('.testimonial-indicator').removeClass('active');
            $('.testimonial-indicator').eq(currentIndex).addClass('active');
        }

        // Update button states
        function updateButtons() {
            if (currentIndex === 0) {
                prevBtn.css('opacity', '0.5').prop('disabled', true);
            } else {
                prevBtn.css('opacity', '1').prop('disabled', false);
            }
            
            if (currentIndex >= maxIndex) {
                nextBtn.css('opacity', '0.5').prop('disabled', true);
            } else {
                nextBtn.css('opacity', '1').prop('disabled', false);
            }
        }

        // Go to specific slide
        function goToSlide(index) {
            if (index >= 0 && index <= maxIndex) {
                currentIndex = index;
                updateCarousel();
            }
        }

        // Next slide (move 1 card)
        function nextSlide() {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        }

        // Previous slide (move 1 card)
        function prevSlide() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        }

        // Event listeners
        prevBtn.on('click', prevSlide);
        nextBtn.on('click', nextSlide);

        // Initialize
        if (cards.length > visibleCards) {
            createIndicators();
            updateCarousel(false);
        } else {
            // If 3 or less cards, hide controls
            $('.testimonials-controls, .testimonials-indicators').hide();
        }

        // Handle window resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                updateCarousel(false);
            }, 250);
        });
    }

    /**
     * Contact Form Handler
     */
    function initContactForm() {
        const form = $('#inviro-contact-form');
        const messageDiv = $('.form-message');

        form.on('submit', function(e) {
            e.preventDefault();

            const formData = {
                action: 'inviro_contact_form',
                nonce: inviroAjax.nonce,
                name: $('#contact-name').val(),
                email: $('#contact-email').val(),
                phone: $('#contact-phone').val(),
                subject: $('#contact-subject').val(),
                message: $('#contact-message').val()
            };

            // Validate
            if (!formData.name || !formData.email || !formData.message) {
                showMessage('error', 'Mohon lengkapi semua field yang wajib diisi.');
                return;
            }

            // Show loading state
            const submitBtn = form.find('button[type="submit"]');
            const originalText = submitBtn.text();
            submitBtn.prop('disabled', true).text('Mengirim...');

            // Send AJAX request
            $.ajax({
                url: inviroAjax.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showMessage('success', response.data.message || 'Pesan berhasil dikirim!');
                        form[0].reset();
                    } else {
                        showMessage('error', response.data.message || 'Gagal mengirim pesan. Silakan coba lagi.');
                    }
                },
                error: function() {
                    showMessage('error', 'Terjadi kesalahan. Silakan coba lagi.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });

        function showMessage(type, message) {
            messageDiv.removeClass('success error')
                      .addClass(type)
                      .text(message)
                      .fadeIn();

            setTimeout(function() {
                messageDiv.fadeOut();
            }, 5000);
        }
    }

    /**
     * Smooth Scroll
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 800);
            }
        });
    }

    /**
     * Lazy Load Images
     */
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });

            $('img[data-src]').each(function() {
                imageObserver.observe(this);
            });
        }
    }

    /**
     * Sticky Header on Scroll - Pill Shape Transformation
     */
    function initStickyHeader() {
        const header = $('.site-header');
        
    $(window).on('scroll', function() {
            if ($(window).scrollTop() > 50) {
            header.addClass('scrolled');
        } else {
            header.removeClass('scrolled');
        }
    });
        
        // Check on load
        if ($(window).scrollTop() > 50) {
            header.addClass('scrolled');
        }
    }

    /**
     * Product Like Button with LocalStorage
     */
    function initProductLikes() {
        // Load liked products from localStorage
        const likedProducts = JSON.parse(localStorage.getItem('likedProducts') || '[]');
        
        // Apply liked state to products
        likedProducts.forEach(function(productId) {
            $('[data-product-id="' + productId + '"]').addClass('liked');
        });
        
        // Handle like button click
        $('.product-like').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = $(this);
            const productCard = button.closest('.product-card');
            const productId = productCard.attr('data-product-id') || button.attr('data-product-id');
            
            // Toggle liked state
            button.toggleClass('liked');
            
            // Update localStorage
            let likedProducts = JSON.parse(localStorage.getItem('likedProducts') || '[]');
            
            if (button.hasClass('liked')) {
                // Add to liked
                if (!likedProducts.includes(productId)) {
                    likedProducts.push(productId);
                }
                // Add animation
                button.animate({
                    fontSize: '1.2em'
                }, 100).animate({
                    fontSize: '1em'
                }, 100);
            } else {
                // Remove from liked
                likedProducts = likedProducts.filter(id => id !== productId);
            }
            
            localStorage.setItem('likedProducts', JSON.stringify(likedProducts));
        });
    }
    
    // Initialize product likes
    initProductLikes();

    /**
     * Animate on Scroll
     */
    function initScrollAnimation() {
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        animationObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            $('.product-card, .testimonial-card, .branch-card, .feature-item').each(function() {
                animationObserver.observe(this);
            });
        }
    }

    // Initialize scroll animations
    initScrollAnimation();

})(jQuery);

