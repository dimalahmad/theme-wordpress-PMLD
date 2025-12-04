<?php
/**
 * Single Spare Part Template
 * Modern & Futuristic Design dengan Tab (Deskripsi, Spesifikasi, Ulasan)
 */

get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID();
    
    // Get sparepart data
    $price_raw = get_post_meta($post_id, '_sparepart_price', true);
    $original_price_raw = get_post_meta($post_id, '_sparepart_original_price', true);
    $promo = get_post_meta($post_id, '_sparepart_promo', true);
    $specifications = get_post_meta($post_id, '_sparepart_specifications', true);
    $specifications = $specifications ? json_decode($specifications, true) : array();
    
    // Bersihkan harga dari format "Rp", titik, koma, dan spasi
    $clean_price = function($value) {
        if (empty($value)) return 0;
        if (is_numeric($value)) {
            return absint($value);
        }
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        return !empty($cleaned) ? absint($cleaned) : 0;
    };
    
    $price_promo = $clean_price($price_raw);
    $original_price = $clean_price($original_price_raw);
    
    // Jika harga promo tidak ada atau 0, gunakan harga asli
    // Harga harus selalu tampil (minimal harga asli)
    $price = ($price_promo > 0) ? $price_promo : $original_price;
    
    // Tentukan apakah ada promo
    $is_promo = $price_promo > 0 && $original_price > 0 && $price_promo != $original_price && $price_promo < $original_price;
    
    // Get main image
    $main_image = has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'large') : '';
    
    // Get reviews
    $reviews = array();
    $avg_rating = 0;
    
    // Query reviews - show all published reviews
    // If post_status = 'publish', it means admin approved it
    $reviews_query = new WP_Query(array(
        'post_type' => 'sparepart_review',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_review_sparepart_id',
                'value' => $post_id,
                'compare' => '='
            ),
            array(
                'key' => '_review_is_dummy',
                'value' => '0',
                'compare' => '='
            ),
            array(
                'key' => '_review_product_type',
                'value' => 'spareparts',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if ($reviews_query->have_posts()) {
        while ($reviews_query->have_posts()) {
            $reviews_query->the_post();
            $review_id = get_the_ID();
            $review_status = get_post_meta($review_id, '_review_status', true);
            
            // Skip only if explicitly rejected
            // If post_status = 'publish', it means admin approved it, so show it
            if ($review_status !== 'rejected') {
                $review_date = get_post_meta($review_id, '_review_date', true);
                if (!$review_date) {
                    $review_date = get_the_date('d/m/Y', $review_id);
                } else {
                    $review_date = date('d/m/Y', strtotime($review_date));
                }
                
                $reviewer_name = get_post_meta($review_id, '_reviewer_name', true);
                $review_rating = get_post_meta($review_id, '_review_rating', true);
                $review_content = get_the_content();
                
                // Only add if we have required data
                if (!empty($reviewer_name) && !empty($review_rating)) {
                    $reviews[] = array(
                        'id' => $review_id,
                        'name' => $reviewer_name,
                        'rating' => $review_rating,
                        'content' => $review_content,
                        'date' => $review_date
                    );
                }
            }
        }
        wp_reset_postdata();
    }
    
    // Calculate average rating
    if (!empty($reviews)) {
        $total_rating = 0;
        foreach ($reviews as $review) {
            $total_rating += intval($review['rating']);
        }
        $avg_rating = round($total_rating / count($reviews), 1);
    }
?>

<div class="sparepart-detail-page">
    <!-- Hero Image Section -->
    <section class="sparepart-detail-hero">
        <div class="container">
            <div class="hero-content-wrapper">
                <!-- Title at top (full width) -->
                <h1 class="product-title"><?php echo esc_html(get_the_title()); ?></h1>
                
                <!-- Image and Info Section (side by side) -->
                <div class="hero-image-info-wrapper">
                    <!-- Image on left -->
                    <div class="hero-image-wrapper">
                        <div class="hero-image-container">
                            <?php if ($main_image) : ?>
                                <img src="<?php echo esc_url($main_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="hero-image" style="width: 100% !important; height: 100% !important; object-fit: cover !important; display: block !important; visibility: visible !important; opacity: 1 !important;">
                            <?php else : ?>
                                <div class="hero-image-placeholder">
                                    <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: rgba(47, 128, 237, 0.3);">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Price and Button on right -->
                    <div class="hero-info-section">
                        <?php if ($original_price > 0) : ?>
                            <div class="price-card">
                                <div class="price-label">Harga</div>
                                <div class="price-amount-wrapper">
                                    <?php if ($is_promo && $price_promo > 0) : ?>
                                        <div class="price-original">Rp <?php echo number_format($original_price, 0, ',', '.'); ?></div>
                                        <div class="price-current">Rp <?php echo number_format($price_promo, 0, ',', '.'); ?></div>
                                    <?php else : ?>
                                        <div class="price-current">Rp <?php echo number_format($original_price, 0, ',', '.'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($promo == '1' && $is_promo) : ?>
                                    <div class="price-badge">Promo</div>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="price-card">
                                <div class="price-label">Harga</div>
                                <div class="price-amount-wrapper">
                                    <div class="price-current">Hubungi Kami</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="contact-person-card">
                            <?php 
                            // Get spareparts-specific contact info, fallback to general contact
                            $wa_number = get_theme_mod('inviro_spareparts_whatsapp', '');
                            if (empty($wa_number)) {
                                $wa_number = get_theme_mod('inviro_whatsapp', '6281234567890');
                            }
                            
                            // Get button text from customizer
                            $buy_button_text = get_theme_mod('inviro_spareparts_buy_button', 'Beli atau Tanya Lebih Lanjut');
                            if (empty($buy_button_text)) {
                                $buy_button_text = 'Beli atau Tanya Lebih Lanjut';
                            }
                            
                            // WhatsApp
                            $wa_clean = preg_replace('/[^0-9]/', '', $wa_number);
                            if (empty($wa_clean)) {
                                $wa_clean = '6281234567890'; // Fallback
                            }
                            $wa_link = 'https://wa.me/' . $wa_clean;
                            ?>
                            <a href="<?php echo esc_url($wa_link); ?>" target="_blank" class="btn-buy-sparepart">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <span><?php echo esc_html($buy_button_text); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Section -->
    <section class="sparepart-detail-tabs-section">
        <div class="container">
            <div class="tabs-wrapper">
                <div class="tabs-nav">
                    <button class="tab-button active" data-tab="deskripsi">Deskripsi</button>
                    <button class="tab-button" data-tab="spesifikasi">Spesifikasi</button>
                    <button class="tab-button" data-tab="ulasan">Ulasan</button>
                </div>
                
                <div class="tabs-content">
                    <!-- Deskripsi Tab -->
                    <div class="tab-pane active" id="tab-deskripsi">
                        <div class="tab-content-inner">
                            <?php if (get_the_content()) : ?>
                                <?php the_content(); ?>
                            <?php else : ?>
                                <p>Deskripsi produk belum tersedia.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Spesifikasi Tab -->
                    <div class="tab-pane" id="tab-spesifikasi">
                        <div class="tab-content-inner">
                            <?php if (!empty($specifications)) : ?>
                                <table class="specifications-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Komponen, Spesifikasi Teknis & Fungsi</th>
                                            <th>Jml</th>
                                            <th>Sat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($specifications as $index => $spec) : 
                                            if (!empty($spec['component'])) :
                                        ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td><?php echo esc_html($spec['component']); ?></td>
                                                <td><?php echo esc_html($spec['quantity']); ?></td>
                                                <td><?php echo esc_html($spec['unit']); ?></td>
                                            </tr>
                                        <?php 
                                            endif;
                                        endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>Spesifikasi produk belum tersedia.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Ulasan Tab -->
                    <div class="tab-pane" id="tab-ulasan">
                        <div class="tab-content-inner">
                            <?php if (!empty($reviews)) : ?>
                                <div class="reviews-carousel testimonials-carousel">
                                    <div class="reviews-list testimonials-track">
                                        <?php foreach ($reviews as $review) : ?>
                                            <div class="testimonial-card review-item">
                                                <div class="testimonial-header">
                                                    <div class="testimonial-avatar">
                                                        <img src="" alt="" style="display: none;">
                                                    </div>
                                                    <div class="testimonial-meta">
                                                        <h4 class="testimonial-name"><?php echo esc_html($review['name']); ?></h4>
                                                        <?php if ($review['date']) : ?>
                                                            <div class="testimonial-date"><?php echo esc_html($review['date']); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="testimonial-rating">
                                                    <?php
                                                    $rating = intval($review['rating']);
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        echo $i <= $rating ? '<span class="star filled">★</span>' : '<span class="star">★</span>';
                                                    }
                                                    ?>
                                                </div>
                                                
                                                <div class="testimonial-content">
                                                    <?php echo wpautop(esc_html($review['content'])); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <?php if (count($reviews) > 3) : ?>
                                        <div class="testimonials-controls reviews-controls">
                                            <button class="testimonial-prev review-prev" aria-label="Previous review">‹</button>
                                            <button class="testimonial-next review-next" aria-label="Next review">›</button>
                                        </div>
                                        
                                        <div class="testimonials-indicators reviews-indicators"></div>
                                    <?php endif; ?>
                                </div>
                            <?php else : ?>
                                <div class="no-reviews">
                                    <p>Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Review Form -->
                            <div class="review-form-wrapper">
                                <h3>Tulis Ulasan</h3>
                                <form id="sparepart-review-form" class="review-form">
                                    <?php wp_nonce_field('submit_review', 'review_nonce'); ?>
                                    <input type="hidden" name="sparepart_id" value="<?php echo $post_id; ?>">
                                    <input type="hidden" name="is_dummy" value="0">
                                    <input type="hidden" name="product_type" value="spareparts">
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="reviewer_name">Nama *</label>
                                            <input type="text" id="reviewer_name" name="reviewer_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="reviewer_email">Email *</label>
                                            <input type="email" id="reviewer_email" name="reviewer_email" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="rating">Rating *</label>
                                        <select id="rating" name="rating" required class="rating-select">
                                            <option value="">Pilih Rating</option>
                                            <option value="5">5 Bintang (Sangat Baik)</option>
                                            <option value="4">4 Bintang (Baik)</option>
                                            <option value="3">3 Bintang (Cukup)</option>
                                            <option value="2">2 Bintang (Kurang)</option>
                                            <option value="1">1 Bintang (Sangat Kurang)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="review_content">Pesan *</label>
                                        <textarea id="review_content" name="review_content" rows="5" required placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn-submit-review">Kirim Ulasan</button>
                                    <div class="form-message"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.tab-button').on('click', function() {
        var tabId = $(this).data('tab');
        
        // Update buttons
        $('.tab-button').removeClass('active');
        $(this).addClass('active');
        
        // Update panes
        $('.tab-pane').removeClass('active');
        $('#tab-' + tabId).addClass('active');
    });
    
    
    // Review form submission
    $('#sparepart-review-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var messageDiv = form.find('.form-message');
        
        submitBtn.prop('disabled', true).text('Mengirim...');
        messageDiv.removeClass('success error').html('');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: form.serialize() + '&action=submit_sparepart_review',
            success: function(response) {
                if (response.success) {
                    messageDiv.addClass('success').html(response.data.message);
                    form[0].reset();
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    messageDiv.addClass('error').html(response.data.message);
                    submitBtn.prop('disabled', false).text('Kirim Ulasan');
                }
            },
            error: function() {
                messageDiv.addClass('error').html('Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.prop('disabled', false).text('Kirim Ulasan');
            }
        });
    });
    
    // Rating stars hover effect
    $('.rating-input .star-label').on('mouseenter', function() {
        var rating = $(this).prev('input').val();
        $('.rating-input .star-label').each(function(index) {
            if (index < rating) {
                $(this).addClass('hover');
            } else {
                $(this).removeClass('hover');
            }
        });
    });
    
    $('.rating-input').on('mouseleave', function() {
        $('.rating-input .star-label').removeClass('hover');
        var checked = $('.rating-input input:checked').val();
        if (checked) {
            $('.rating-input .star-label').each(function(index) {
                if (index < checked) {
                    $(this).addClass('hover');
                }
            });
        }
    });
    
    $('.rating-input input:radio').on('change', function() {
        var rating = $(this).val();
        $('.rating-input .star-label').removeClass('hover');
        $('.rating-input .star-label').each(function(index) {
            if (index < rating) {
                $(this).addClass('hover');
            }
        });
    });
    
    // Reviews Carousel
    function initReviewsCarousel() {
        const track = $('.reviews-carousel .testimonials-track');
        const cards = $('.reviews-carousel .testimonial-card');
        const prevBtn = $('.review-prev');
        const nextBtn = $('.review-next');
        let currentIndex = 0;
        
        if (cards.length === 0 || cards.length <= 3) {
            $('.reviews-controls, .reviews-indicators').hide();
            return;
        }
        
        // Always show 3 cards on desktop
        const visibleCards = 3;
        const maxIndex = Math.max(0, cards.length - visibleCards);
        
        // Create indicators
        function createIndicators() {
            const indicatorsContainer = $('.reviews-indicators');
            indicatorsContainer.empty();
            
            for (let i = 0; i <= maxIndex; i++) {
                const indicator = $('<button class="testimonial-indicator"></button>');
                if (i === 0) indicator.addClass('active');
                indicator.on('click', function() {
                    goToSlide(i);
                });
                indicatorsContainer.append(indicator);
            }
        }
        
        // Update carousel position
        function updateCarousel(animate = true) {
            if (animate) {
                track.css('transition', 'transform 0.5s ease-in-out');
            } else {
                track.css('transition', 'none');
            }
            
            const cardWidth = 100 / visibleCards;
            const movePercentage = -(currentIndex * cardWidth);
            track.css('transform', `translateX(${movePercentage}%)`);
            
            updateIndicators();
            updateButtons();
        }
        
        // Update indicators
        function updateIndicators() {
            $('.reviews-indicators .testimonial-indicator').removeClass('active');
            $('.reviews-indicators .testimonial-indicator').eq(currentIndex).addClass('active');
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
        
        // Next slide
        function nextSlide() {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        }
        
        // Previous slide
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
        if (cards.length > 3) {
            $('.reviews-controls, .reviews-indicators').show();
            createIndicators();
            updateCarousel(false);
        } else {
            $('.reviews-controls, .reviews-indicators').hide();
        }
    }
    
    // Initialize reviews carousel
    initReviewsCarousel();
});
</script>

<?php
endwhile;
get_footer();
?>


