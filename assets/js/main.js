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
        initArtikelImageSizing();
        initUnduhanDownload();
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
        
        // Detect mobile vs desktop
        function getVisibleCards() {
            return window.innerWidth <= 768 ? 2 : 3;
        }
        
        // Check if desktop
        function isDesktop() {
            return window.innerWidth > 768;
        }
        
        let visibleCards = getVisibleCards();

        if (cards.length === 0) return;

        // Total slides based on moving 1 card at a time
        const maxIndex = Math.max(0, cards.length - visibleCards);

        // Create indicators based on total cards
        function createIndicators() {
            const indicatorsContainer = $('.testimonials-indicators');
            indicatorsContainer.empty();
            
            visibleCards = getVisibleCards();
            const maxIndexNew = Math.max(0, cards.length - visibleCards);
            
            // Create indicators for each possible position
            for (let i = 0; i <= maxIndexNew; i++) {
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
            visibleCards = getVisibleCards();
            const maxIndexNew = Math.max(0, cards.length - visibleCards);
            
            // Adjust currentIndex if needed
            if (currentIndex > maxIndexNew) {
                currentIndex = maxIndexNew;
            }
            
            if (animate) {
                track.css('transition', 'transform 0.5s ease-in-out');
            } else {
                track.css('transition', 'none');
            }
            
            // Calculate percentage to move based on visible cards
            const cardWidth = 100 / visibleCards;
            const movePercentage = -(currentIndex * cardWidth);
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
            visibleCards = getVisibleCards();
            const maxIndexNew = Math.max(0, cards.length - visibleCards);
            
            if (currentIndex === 0) {
                prevBtn.css('opacity', '0.5').prop('disabled', true);
            } else {
                prevBtn.css('opacity', '1').prop('disabled', false);
            }
            
            if (currentIndex >= maxIndexNew) {
                nextBtn.css('opacity', '0.5').prop('disabled', true);
            } else {
                nextBtn.css('opacity', '1').prop('disabled', false);
            }
        }

        // Go to specific slide
        function goToSlide(index) {
            visibleCards = getVisibleCards();
            const maxIndexNew = Math.max(0, cards.length - visibleCards);
            if (index >= 0 && index <= maxIndexNew) {
                currentIndex = index;
                updateCarousel();
            }
        }

        // Next slide (move 1 card)
        function nextSlide() {
            visibleCards = getVisibleCards();
            const maxIndexNew = Math.max(0, cards.length - visibleCards);
            if (currentIndex < maxIndexNew) {
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
        visibleCards = getVisibleCards();
        const maxIndexInit = Math.max(0, cards.length - visibleCards);
        
        // Only initialize carousel on desktop (width > 768px)
        if (isDesktop()) {
            if (cards.length > visibleCards) {
                $('.testimonials-controls, .testimonials-indicators').show();
                createIndicators();
                updateCarousel(false);
            } else {
                // If cards <= visible cards, hide controls
                $('.testimonials-controls, .testimonials-indicators').hide();
            }
        } else {
            // Mobile: hide controls and disable carousel
            $('.testimonials-controls, .testimonials-indicators').hide();
            track.css('transform', 'none');
        }

        // Handle window resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (isDesktop()) {
                    // Desktop: show controls and update carousel
                    if (cards.length > getVisibleCards()) {
                        $('.testimonials-controls, .testimonials-indicators').show();
                        visibleCards = getVisibleCards();
                        const maxIndexResize = Math.max(0, cards.length - visibleCards);
                        if (currentIndex > maxIndexResize) {
                            currentIndex = maxIndexResize;
                        }
                        createIndicators();
                        updateCarousel(false);
                    } else {
                        $('.testimonials-controls, .testimonials-indicators').hide();
                    }
                } else {
                    // Mobile: hide controls and reset transform
                    $('.testimonials-controls, .testimonials-indicators').hide();
                    track.css('transform', 'none');
                }
            }, 250);
        });
    }

    /**
     * Contact Form Handler
     */
    function initContactForm() {
        const form = $('#inviro-contact-form');
        if (form.length === 0) {
            return;
        }
        
        const messageDiv = form.find('.form-message');
        
        form.on('submit', function(e) {
            e.preventDefault();
            var submitBtn = form.find('button[type="submit"]');
            var originalText = submitBtn.text();
            
            submitBtn.prop('disabled', true).text('Mengirim...');
            messageDiv.removeClass('success error').html('');
            
            $.ajax({
                url: inviroAjax.ajaxurl,
                type: 'POST',
                data: form.serialize() + '&action=submit_contact_form',
                success: function(response) {
                    console.log('Contact form response:', response);
                    if (response.success) {
                        messageDiv.addClass('success').html(response.data.message);
                        form[0].reset();
                    } else {
                        messageDiv.addClass('error').html(response.data.message || 'Gagal mengirim pesan. Silakan coba lagi.');
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Contact form error:', xhr, status, error);
                    messageDiv.addClass('error').html('Terjadi kesalahan. Silakan coba lagi.');
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });
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

    /**
     * Fix Artikel Detail Image Sizing
     * Memastikan semua gambar di detail artikel memiliki ukuran yang sama
     */
    function initArtikelImageSizing() {
        // Hanya jalankan di halaman detail artikel dan proyek pelanggan
        if (!$('.artikel-single').length) {
            return;
        }

        console.log('Artikel image sizing initialized');
        console.log('Artikel single page found:', $('.artikel-single').length);

        function setImageSizes() {
            const isMobile = window.innerWidth <= 480;
            const isTablet = window.innerWidth <= 768 && window.innerWidth > 480;
            
            // Sama dengan ukuran di spareparts detail
            let targetHeight = '655px'; // Desktop
            if (isMobile || isTablet) {
                targetHeight = '400px'; // Tablet & Mobile
            }

            // Cari semua gambar dengan berbagai selector (termasuk hero image)
            const $allImages = $('.artikel-single .hero-image img, .artikel-content-full img, .artikel-content-full figure img, .artikel-content-full p img, .artikel-content-full .wp-block-image img, .artikel-content-full * img');
            
            console.log('Setting image sizes to:', targetHeight);
            console.log('Total images found:', $allImages.length);
            console.log('Hero images:', $('.artikel-single .hero-image img').length);
            console.log('Content images:', $('.artikel-content-full img').length);

            if ($allImages.length === 0) {
                console.warn('No images found! Checking content...');
                console.log('Content preview:', $('.artikel-content-full').html().substring(0, 500));
                
                // Coba lagi setelah delay
                setTimeout(function() {
                    const $retryImages = $('.artikel-content-full img');
                    console.log('Retry: Images found:', $retryImages.length);
                    if ($retryImages.length > 0) {
                        setImageSizes();
                    }
                }, 500);
                return;
            }

            // Set ukuran untuk semua gambar di dalam artikel-content-full
            $allImages.each(function(index) {
                const $img = $(this);
                const imgSrc = $img.attr('src') || 'no-src';
                
                // Set inline styles untuk memastikan ukuran dengan !important
                const newStyle = 
                    'width: 100% !important; ' +
                    'height: ' + targetHeight + ' !important; ' +
                    'max-width: 100% !important; ' +
                    'max-height: ' + targetHeight + ' !important; ' +
                    'min-height: ' + targetHeight + ' !important; ' +
                    'object-fit: cover !important; ' +
                    'object-position: center !important; ' +
                    'display: block !important; ' +
                    'visibility: visible !important; ' +
                    'opacity: 1 !important; ' +
                    'border-radius: 12px; ' +
                    'margin: 0 !important; ' +
                    'padding: 0 !important; ' +
                    'position: relative; ' +
                    'box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);';
                
                $img.attr('style', newStyle);
                $img.css({
                    'width': '100%',
                    'height': targetHeight,
                    'max-width': '100%',
                    'max-height': targetHeight,
                    'min-height': targetHeight,
                    'object-fit': 'cover',
                    'object-position': 'center'
                });

                console.log('Image ' + index + ' styled:', imgSrc.substring(0, 50) + '...');

                // Set ukuran untuk wrapper (figure, wp-block-image, hero-image)
                const $wrapper = $img.closest('figure, .wp-block-image, .hero-image, p');
                if ($wrapper.length && !$wrapper.hasClass('artikel-content-full')) {
                    // Khusus untuk hero-image, set height juga
                    if ($wrapper.hasClass('hero-image')) {
                        $wrapper.css({
                            'height': targetHeight,
                            'max-height': targetHeight,
                            'min-height': targetHeight
                        });
                    }
                    const wrapperStyle = 
                        'margin: 30px 0; ' +
                        'width: 100%; ' +
                        'height: ' + targetHeight + ' !important; ' +
                        'max-height: ' + targetHeight + ' !important; ' +
                        'min-height: ' + targetHeight + ' !important; ' +
                        'overflow: hidden; ' +
                        'border-radius: 12px; ' +
                        'box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); ' +
                        'position: relative; ' +
                        'display: block; ' +
                        'line-height: 0; ' +
                        'padding: 0;';
                    
                    $wrapper.attr('style', wrapperStyle);
                }
            });
        }

        // Jalankan dengan delay untuk memastikan DOM sudah ready
        setTimeout(function() {
            setImageSizes();
        }, 100);

        // Jalankan saat halaman dimuat
        $(document).ready(function() {
            setTimeout(setImageSizes, 200);
        });

        // Jalankan lagi setelah semua gambar dimuat
        $(window).on('load', function() {
            console.log('Window loaded, setting image sizes again');
            setTimeout(setImageSizes, 300);
        });

        // Jalankan saat window di-resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                console.log('Window resized, updating image sizes');
                setImageSizes();
            }, 250);
        });

        // Jalankan saat gambar baru dimuat (lazy load) - lebih agresif
        if ('MutationObserver' in window) {
            const observer = new MutationObserver(function(mutations) {
                let shouldUpdate = false;
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                if (node.tagName === 'IMG' || node.querySelector && node.querySelector('img')) {
                                    shouldUpdate = true;
                                }
                            }
                        });
                    }
                });
                if (shouldUpdate) {
                    console.log('New images detected, updating image sizes');
                    setTimeout(setImageSizes, 100);
                }
            });

            const contentArea = document.querySelector('.artikel-content-full');
            if (contentArea) {
                observer.observe(contentArea, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['src', 'srcset']
                });
            }
        }

        // Fallback: Check setiap detik untuk gambar baru (untuk lazy load)
        let checkInterval = setInterval(function() {
            const $images = $('.artikel-content-full img');
            $images.each(function() {
                const $img = $(this);
                const currentHeight = $img.css('height');
                const isMobile = window.innerWidth <= 480;
                const isTablet = window.innerWidth <= 768 && window.innerWidth > 480;
                const targetHeight = isMobile ? '250px' : (isTablet ? '300px' : '400px');
                
                if (currentHeight !== targetHeight && currentHeight !== 'auto') {
                    console.log('Image height mismatch detected, fixing...');
                    setImageSizes();
                    clearInterval(checkInterval);
                }
            });
        }, 1000);

        // Stop checking after 10 seconds
        setTimeout(function() {
            clearInterval(checkInterval);
        }, 10000);
    }

    /**
     * Unduhan Download Handler - Track download count
     * Note: Actual download is handled by PHP handler
     */
    function initUnduhanDownload() {
        $('.unduhan-download-btn').on('click', function(e) {
            const $btn = $(this);
            const postId = $btn.attr('data-post-id');
            
            if (!postId) {
                return;
            }
            
            // Track download via AJAX (non-blocking)
            $.ajax({
                url: inviroAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'track_download',
                    post_id: postId,
                    nonce: inviroAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update download count in UI
                        const $countEl = $btn.closest('.unduhan-card').find('.unduhan-download-count');
                        if ($countEl.length) {
                            const newCount = response.data.new_count;
                            $countEl.text(newCount + ' download');
                        }
                    }
                },
                error: function() {
                    console.error('Failed to track download');
                }
            });
            
            // Let the link proceed normally - PHP handler will force download
        });
    }

})(jQuery);

