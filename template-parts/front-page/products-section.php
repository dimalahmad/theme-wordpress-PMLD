<?php
/**
 * Template part for displaying products section
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$products_count = get_theme_mod('inviro_products_count', 8);
$products_count = max(1, min(12, absint($products_count)));

// Get selected featured products
$selected_product_ids = array();
for ($i = 1; $i <= 12; $i++) {
    $product_id = get_theme_mod('inviro_featured_product_' . $i);
    if ($product_id) {
        $selected_product_ids[] = absint($product_id);
    }
}

// Setup query
if (!empty($selected_product_ids)) {
    $selected_product_ids = array_slice($selected_product_ids, 0, $products_count);
    $args = array(
        'post_type' => 'produk',
        'post__in' => $selected_product_ids,
        'orderby' => 'post__in',
        'posts_per_page' => $products_count,
        'post_status' => 'publish'
    );
} else {
    $args = array(
        'post_type' => 'produk',
        'posts_per_page' => $products_count,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    );
}

$products_query = new WP_Query($args);
?>

<section id="products" class="products-section" itemscope itemtype="https://schema.org/ItemList">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_products_title', 'Rekomendasi Produk Inviro')); ?></h2>
            <?php 
            $products_subtitle = get_theme_mod('inviro_products_subtitle', '');
            if ($products_subtitle) : ?>
                <p class="section-subtitle"><?php echo esc_html($products_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="products-grid">
            <?php if ($products_query->have_posts()) : ?>
                <?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
                    <?php
                    $product_id = get_the_ID();
                    $price = get_post_meta($product_id, '_product_price', true);
                    $price_original = get_post_meta($product_id, '_product_original_price', true);
                    $description = get_post_meta($product_id, '_product_description', true);
                    $buy_url = get_post_meta($product_id, '_product_buy_url', true);
                    
                    // Debug: Pastikan harga selalu ada (setidaknya "Hubungi Kami")
                    // Uncomment untuk debugging:
                    // error_log("Product ID: $product_id, Price: $price, Original: $price_original");
                    
                    if (empty($buy_url)) {
                        $buy_url = get_theme_mod('inviro_whatsapp') ? 'https://wa.me/' . get_theme_mod('inviro_whatsapp') : '#';
                    }
                    
                    $product_excerpt = get_the_excerpt($product_id);
                    
                    // Get image - use multiple fallback methods
                    $image_url = '';
                    $thumbnail_id = get_post_thumbnail_id($product_id);
                    
                    if ($thumbnail_id) {
                        // Method 1: Use helper function
                        $image_url = inviro_get_product_image_url($product_id, 'medium');
                        
                        // Method 2: Direct get_the_post_thumbnail_url if helper fails
                        if (empty($image_url)) {
                            $image_url = get_the_post_thumbnail_url($product_id, 'medium');
                            if (empty($image_url)) {
                                $image_url = get_the_post_thumbnail_url($product_id, 'large');
                            }
                            if (empty($image_url)) {
                                $image_url = get_the_post_thumbnail_url($product_id, 'full');
                            }
                        }
                        
                        // Method 3: wp_get_attachment_image_src
                        if (empty($image_url)) {
                            $image_data = wp_get_attachment_image_src($thumbnail_id, 'medium');
                            if ($image_data && !empty($image_data[0])) {
                                $image_url = $image_data[0];
                            } else {
                                $image_data = wp_get_attachment_image_src($thumbnail_id, 'large');
                                if ($image_data && !empty($image_data[0])) {
                                    $image_url = $image_data[0];
                                }
                            }
                        }
                        
                        // Normalize URL if needed
                        if ($image_url && strpos($image_url, 'wp-content/uploads') !== false) {
                            $image_url = inviro_normalize_image_url($image_url);
                        }
                    }
                    ?>
                    <article class="product-card" data-product-id="<?php echo esc_attr($product_id); ?>" itemscope itemtype="https://schema.org/Product">
                        <div class="product-image">
                            <?php if (!empty($image_url)) : ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title($product_id)); ?>" loading="lazy" itemprop="image">
                            <?php elseif ($thumbnail_id) : ?>
                                <?php 
                                // Last resort: use wp_get_attachment_image
                                echo wp_get_attachment_image($thumbnail_id, 'medium', false, array(
                                    'class' => '',
                                    'loading' => 'lazy',
                                    'itemprop' => 'image',
                                    'alt' => esc_attr(get_the_title($product_id))
                                ));
                                ?>
                            <?php else : ?>
                                <div class="product-placeholder-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <button class="product-like" data-product-id="<?php echo esc_attr($product_id); ?>" aria-label="Like product">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="product-info">
                            <div class="product-top-content">
                                <h3 class="product-title" itemprop="name"><?php echo esc_html(get_the_title($product_id)); ?></h3>
                                
                                <?php if ($description) : ?>
                                    <p class="product-description"><?php echo esc_html(wp_trim_words($description, 12)); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-bottom-content">
                                <div class="product-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <?php if ($price && $price_original) : ?>
                                        <span class="product-price-original"><?php echo esc_html($price_original); ?></span>
                                        <span class="product-price-promo" itemprop="price"><?php echo esc_html($price); ?></span>
                                    <?php elseif ($price) : ?>
                                        <span class="product-current-price" itemprop="price"><?php echo esc_html($price); ?></span>
                                    <?php else : ?>
                                        <span class="product-current-price">Hubungi Kami</span>
                                    <?php endif; ?>
                                </div>
                                
                                <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="btn btn-product"><?php esc_html_e('Beli', 'inviro'); ?></a>
                            </div>
                        </div>
                        
                        <meta itemprop="description" content="<?php echo esc_attr(wp_strip_all_tags($description ? $description : $product_excerpt)); ?>">
                    </article>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="no-products"><?php esc_html_e('Belum ada produk yang tersedia.', 'inviro'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

