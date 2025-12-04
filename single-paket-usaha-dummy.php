<?php
/**
 * Single Paket Usaha Template for Dummy Data
 * This file is included from page-paket-usaha.php when viewing dummy detail
 */

// Variables are already set from page-paket-usaha.php
?>

<div class="sparepart-detail-page">
    <!-- Main Content -->
    <section class="sparepart-detail-main">
        <div class="container">
            <div class="detail-layout">
                <!-- Left Column - Gallery -->
                <div class="detail-gallery-section">
                    <?php if (!empty($gallery) || $main_image) : ?>
                        <div class="product-gallery">
                            <div class="gallery-main">
                                <?php 
                                $display_main_image = $main_image ? $main_image : (!empty($gallery) ? $gallery[0] : '');
                                if ($display_main_image) :
                                ?>
                                    <img id="main-gallery-image" src="<?php echo esc_url($display_main_image); ?>" alt="<?php echo esc_attr($title); ?>" loading="eager">
                                    <?php if ($promo == '1') : ?>
                                        <span class="promo-badge-large">Promo</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($gallery) && count($gallery) > 1) : ?>
                                <div class="gallery-thumbnails">
                                    <?php foreach ($gallery as $index => $img_url) : ?>
                                        <div class="gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>" data-image="<?php echo esc_url($img_url); ?>">
                                            <img src="<?php echo esc_url($img_url); ?>" alt="Gallery thumbnail">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <div class="product-gallery">
                            <div class="gallery-main">
                                <div class="no-image-placeholder">
                                    <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    <p>Tidak ada gambar</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Column - Product Info -->
                <div class="detail-info-section">
                    <div class="product-info-card">
                        <?php if ($categories && !is_wp_error($categories)) : ?>
                            <div class="detail-categories">
                                <?php foreach ($categories as $category) : ?>
                                    <span class="category-badge"><?php echo esc_html($category->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <h1 class="detail-title"><?php echo esc_html($title); ?></h1>
                        
                        <?php if ($sku) : ?>
                            <div class="detail-sku">SKU: <?php echo esc_html($sku); ?></div>
                        <?php endif; ?>
                        
                        <?php 
                        // Bersihkan harga dari format "Rp", titik, koma, dan spasi
                        $clean_price = function($value) {
                            if (empty($value)) return 0;
                            if (is_numeric($value)) {
                                return absint($value);
                            }
                            $cleaned = preg_replace('/[^0-9]/', '', (string)$value);
                            return !empty($cleaned) ? absint($cleaned) : 0;
                        };
                        
                        $price_promo = $clean_price($price);
                        $original_price_clean = isset($dummy_detail_data['original_price']) ? $clean_price($dummy_detail_data['original_price']) : 0;
                        
                        // Jika original_price tidak ada di dummy data, gunakan price sebagai original
                        if ($original_price_clean == 0 && $price_promo > 0) {
                            $original_price_clean = $price_promo;
                        }
                        
                        // Tentukan apakah ada promo
                        $is_promo = ($price_promo > 0 && $original_price_clean > 0 && $price_promo < $original_price_clean) || $promo == '1';
                        ?>
                        
                        <?php if ($original_price_clean > 0) : ?>
                            <div class="detail-price">
                                <?php if ($is_promo && $price_promo > 0) : ?>
                                    <div class="price-wrapper">
                                        <span class="price-original">Rp <?php echo number_format($original_price_clean, 0, ',', '.'); ?></span>
                                        <span class="price-amount price-promo">Rp <?php echo number_format($price_promo, 0, ',', '.'); ?></span>
                                    </div>
                                <?php else : ?>
                                    <span class="price-amount">Rp <?php echo number_format($original_price_clean, 0, ',', '.'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="detail-price">
                                <span class="price-amount">Hubungi Kami</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($promo == '1') : ?>
                            <div class="promo-banner">
                                <span>Paket ini sedang dalam promo!</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-actions">
                            <?php 
                            $wa_number = get_theme_mod('inviro_whatsapp', '6281234567890');
                            $wa_message = 'Saya%20tertarik%20dengan%20' . urlencode($title) . '%20(SKU:%20' . urlencode($sku ? $sku : 'N/A') . ')';
                            ?>
                            <a href="https://wa.me/<?php echo esc_attr($wa_number); ?>?text=<?php echo esc_attr($wa_message); ?>" 
                               class="btn-order-now" target="_blank">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Description & Specifications Section -->
    <?php if ($description || !empty($specifications)) : ?>
    <section class="sparepart-detail-content">
        <div class="container">
            <div class="content-grid">
                <?php if ($description) : ?>
                    <div class="description-section">
                        <h3>Deskripsi Paket</h3>
                        <div class="description-content">
                            <?php echo wpautop(esc_html($description)); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($specifications)) : ?>
                    <div class="specifications-section">
                        <h3>Spesifikasi</h3>
                        <div class="specifications-table">
                            <?php foreach ($specifications as $spec) : 
                                if (!empty($spec['label']) && !empty($spec['value'])) :
                            ?>
                                <div class="spec-row">
                                    <div class="spec-label"><?php echo esc_html($spec['label']); ?></div>
                                    <div class="spec-value"><?php echo esc_html($spec['value']); ?></div>
                                </div>
                            <?php 
                                endif;
                            endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Reviews Section -->
    <section class="sparepart-reviews-section">
        <div class="container">
            <div class="reviews-header">
                <h2>Ulasan Produk</h2>
                <?php if ($avg_rating > 0) : ?>
                    <div class="average-rating">
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <span class="star <?php echo $i <= round($avg_rating) ? 'filled' : ''; ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-text"><?php echo esc_html($avg_rating); ?> / 5.0 (<?php echo count($reviews); ?> ulasan)</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Review Form -->
            <div class="review-form-wrapper">
                <h3>Tulis Ulasan</h3>
                <form id="paket-review-form" class="review-form">
                    <?php wp_nonce_field('submit_review', 'review_nonce'); ?>
                    <input type="hidden" name="paket_id" value="dummy_<?php echo esc_attr($dummy_id); ?>">
                    <input type="hidden" name="is_dummy" value="1">
                    <input type="hidden" name="product_type" value="paket_usaha">
                    
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
                        <label>Rating *</label>
                        <div class="rating-input">
                            <?php for ($i = 5; $i >= 1; $i--) : ?>
                                <input type="radio" id="rating_<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                                <label for="rating_<?php echo $i; ?>" class="star-label">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="review_content">Ulasan *</label>
                        <textarea id="review_content" name="review_content" rows="5" required placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit-review">Kirim Ulasan</button>
                    <div class="form-message"></div>
                </form>
            </div>
            
            <!-- Reviews List -->
            <div class="reviews-list">
                <?php if (!empty($reviews)) : ?>
                    <?php foreach ($reviews as $review) : ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        <?php echo strtoupper(substr($review['name'], 0, 1)); ?>
                                    </div>
                                    <div class="reviewer-details">
                                        <h4><?php echo esc_html($review['name']); ?></h4>
                                        <span class="review-date"><?php echo esc_html($review['date']); ?></span>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <span class="star <?php echo $i <= intval($review['rating']) ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="review-content">
                                <?php echo wpautop(esc_html($review['content'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="no-reviews">
                        <p>Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<script>
jQuery(document).ready(function($) {
    // Gallery thumbnail click
    $('.gallery-thumb').on('click', function() {
        var imageUrl = $(this).data('image');
        $('#main-gallery-image').attr('src', imageUrl);
        $('.gallery-thumb').removeClass('active');
        $(this).addClass('active');
    });
    
    // Review form submission
    $('#paket-review-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var messageDiv = form.find('.form-message');
        
        submitBtn.prop('disabled', true).text('Mengirim...');
        messageDiv.removeClass('success error').html('');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: form.serialize() + '&action=submit_paket_review',
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
});
</script>

