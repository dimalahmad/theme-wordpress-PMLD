<?php
/**
 * Helper Functions
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get SVG Icon for Contact Features
 */
function inviro_get_feature_icon($icon_name) {
    $icons = array(
        'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>',
        'tag' => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line>',
        'map-pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>',
        'mail' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>',
        'clock' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
        'award' => '<circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>',
        'check-circle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>',
    );
    
    $icon_path = isset($icons[$icon_name]) ? $icons[$icon_name] : $icons['phone'];
    
    return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $icon_path . '</svg>';
}

/**
 * Normalize image URL to use current site URL
 * Only normalize if URL domain doesn't match current site
 * 
 * @param string $image_url The image URL to normalize
 * @return string Normalized image URL
 */
function inviro_normalize_image_url($image_url) {
    if (empty($image_url)) {
        return '';
    }
    
    // Get current site URL
    $upload_dir = wp_upload_dir();
    $current_base_url = $upload_dir['baseurl'];
    $current_domain = parse_url($current_base_url, PHP_URL_HOST);
    
    // If URL is already absolute
    if (strpos($image_url, 'http') === 0) {
        $url_domain = parse_url($image_url, PHP_URL_HOST);
        
        // If domain matches current site, just normalize scheme
        if ($url_domain === $current_domain) {
            return set_url_scheme($image_url);
        }
        
        // If domain doesn't match and contains wp-content/uploads, replace domain
        if (strpos($image_url, 'wp-content/uploads') !== false) {
            // Extract the path after domain
            if (preg_match('#(/wp-content/uploads/.+)$#', $image_url, $matches)) {
                $image_url = $current_base_url . $matches[1];
            } elseif (preg_match('#https?://[^/]+(/wp-content/uploads/.+)#', $image_url, $matches)) {
                $image_url = $current_base_url . $matches[1];
            }
        }
        
        return set_url_scheme($image_url);
    }
    
    // If relative URL, make it absolute
    if (strpos($image_url, '/') === 0) {
        // Absolute path from root
        $image_url = home_url($image_url);
    } else {
        // Relative path
        $image_url = $current_base_url . '/' . ltrim($image_url, '/');
    }
    
    return set_url_scheme($image_url);
}

/**
 * Get product image URL - simplified method
 * Uses same method as single-spareparts.php for consistency
 * 
 * @param int $post_id Post ID
 * @param string $size Image size (medium, large, full)
 * @return string Image URL or empty string
 */
function inviro_get_product_image_url($post_id, $size = 'medium') {
    if (!$post_id || !has_post_thumbnail($post_id)) {
        return '';
    }
    
    // Use get_the_post_thumbnail_url - same as single-spareparts.php
    $image_url = get_the_post_thumbnail_url($post_id, $size);
    
    // Fallback to other sizes if requested size not available
    if (empty($image_url)) {
        if ($size === 'medium') {
            $image_url = get_the_post_thumbnail_url($post_id, 'large');
            if (empty($image_url)) {
                $image_url = get_the_post_thumbnail_url($post_id, 'full');
            }
        } elseif ($size === 'large') {
            $image_url = get_the_post_thumbnail_url($post_id, 'medium');
            if (empty($image_url)) {
                $image_url = get_the_post_thumbnail_url($post_id, 'full');
            }
        } else {
            $image_url = get_the_post_thumbnail_url($post_id, 'large');
            if (empty($image_url)) {
                $image_url = get_the_post_thumbnail_url($post_id, 'medium');
            }
        }
    }
    
    // Normalize URL only if it contains wp-content/uploads (for domain changes)
    if ($image_url && strpos($image_url, 'wp-content/uploads') !== false) {
        $image_url = inviro_normalize_image_url($image_url);
    }
    
    return $image_url ? $image_url : '';
}

/**
 * Get image URL from post ID with fallback sizes
 * 
 * @param int $post_id Post ID
 * @param string|array $size Image size
 * @return string Image URL or empty string
 */
function inviro_get_image_url($post_id, $size = 'large') {
    // For products, use simplified method
    if (get_post_type($post_id) === 'produk') {
        return inviro_get_product_image_url($post_id, $size);
    }
    
    if (!$post_id) {
        return '';
    }
    
    // First try to get thumbnail ID
    $thumb_id = get_post_thumbnail_id($post_id);
    if (!$thumb_id) {
        // Try to get from post meta
        $thumb_id = get_post_meta($post_id, '_thumbnail_id', true);
        if (!$thumb_id) {
            return '';
        }
    }
    
    // Verify attachment exists and is an image
    $attachment = get_post($thumb_id);
    if (!$attachment || $attachment->post_type !== 'attachment') {
        return '';
    }
    
    // Check if it's an image
    if (!wp_attachment_is_image($thumb_id)) {
        return '';
    }
    
    // Try requested size first
    $image_data = wp_get_attachment_image_src($thumb_id, $size);
    if ($image_data && !empty($image_data[0])) {
        $url = $image_data[0];
        // Only normalize if needed
        if (strpos($url, 'wp-content/uploads') !== false) {
            return inviro_normalize_image_url($url);
        }
        return $url;
    }
    
    // Fallback to other sizes based on requested size
    $fallback_sizes = array();
    if ($size === 'medium') {
        $fallback_sizes = array('large', 'thumbnail', 'full');
    } elseif ($size === 'large') {
        $fallback_sizes = array('medium', 'full', 'thumbnail');
    } elseif ($size === 'thumbnail') {
        $fallback_sizes = array('medium', 'large', 'full');
    } else {
        $fallback_sizes = array('medium', 'large', 'thumbnail', 'full');
    }
    
    foreach ($fallback_sizes as $fallback_size) {
        if ($fallback_size === $size) {
            continue;
        }
        $image_data = wp_get_attachment_image_src($thumb_id, $fallback_size);
        if ($image_data && !empty($image_data[0])) {
            $url = $image_data[0];
            // Only normalize if needed
            if (strpos($url, 'wp-content/uploads') !== false) {
                return inviro_normalize_image_url($url);
            }
            return $url;
        }
    }
    
    // Last resort: direct URL
    $direct_url = wp_get_attachment_url($thumb_id);
    if ($direct_url) {
        if (strpos($direct_url, 'wp-content/uploads') !== false) {
            return inviro_normalize_image_url($direct_url);
        }
        return $direct_url;
    }
    
    return '';
}

/**
 * Get logo URL with normalization
 * 
 * @param int $logo_id Logo attachment ID
 * @param string $size Image size
 * @return string Logo URL or empty string
 */
function inviro_get_logo_url($logo_id, $size = 'full') {
    if (!$logo_id) {
        return '';
    }
    
    $logo_url = wp_get_attachment_image_url($logo_id, $size);
    if ($logo_url) {
        return inviro_normalize_image_url($logo_url);
    }
    
    return '';
}

/**
 * Get hero project posts data
 * 
 * @return array Array of project post data
 */
function inviro_get_hero_projects() {
    $display_mode = get_theme_mod('inviro_hero_display_mode', 'selected');
    $selected_projects = get_theme_mod('inviro_hero_selected_projects', '');
    
    $posts = array();
    
    if ($display_mode === 'selected' && !empty($selected_projects)) {
        $selected_ids = json_decode($selected_projects, true);
        
        if (!empty($selected_ids) && is_array($selected_ids)) {
            $selected_ids = array_map('intval', array_slice($selected_ids, 0, 4));
            $selected_ids = array_filter($selected_ids);
            
            if (!empty($selected_ids)) {
                foreach ($selected_ids as $post_id) {
                    $post_obj = get_post($post_id);
                    if ($post_obj && $post_obj->post_type === 'proyek_pelanggan' && $post_obj->post_status === 'publish') {
                        $thumbnail_id = get_post_thumbnail_id($post_id);
                        if ($thumbnail_id && !wp_attachment_is_image($thumbnail_id)) {
                            $thumbnail_id = null;
                        }
                        
                        $posts[] = array(
                            'id' => $post_id,
                            'title' => get_the_title($post_id),
                            'permalink' => get_permalink($post_id),
                            'thumbnail' => $thumbnail_id,
                            'client_name' => get_post_meta($post_id, '_proyek_client_name', true),
                            'proyek_date' => get_post_meta($post_id, '_proyek_date', true),
                            'regions' => get_the_terms($post_id, 'region'),
                        );
                    }
                }
            }
        }
    } else {
        // Use latest projects
        $proyek_query = new WP_Query(array(
            'post_type' => 'proyek_pelanggan',
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        ));
        
        if ($proyek_query->have_posts()) {
            while ($proyek_query->have_posts()) : $proyek_query->the_post();
                $post_id = get_the_ID();
                $thumbnail_id = get_post_thumbnail_id($post_id);
                if ($thumbnail_id && !wp_attachment_is_image($thumbnail_id)) {
                    $thumbnail_id = null;
                }
                
                $posts[] = array(
                    'id' => $post_id,
                    'title' => get_the_title($post_id),
                    'permalink' => get_permalink($post_id),
                    'thumbnail' => $thumbnail_id,
                    'client_name' => get_post_meta($post_id, '_proyek_client_name', true),
                    'proyek_date' => get_post_meta($post_id, '_proyek_date', true),
                    'regions' => get_the_terms($post_id, 'region'),
                );
            endwhile;
            wp_reset_postdata();
        }
    }
    
    return $posts;
}

/**
 * Render hero article card
 * 
 * @param array $post Post data array
 * @param string $size Card size: 'large', 'small-top', 'small'
 */
function inviro_render_hero_card($post, $size = 'large') {
    if (empty($post)) {
        return;
    }
    
    $post_id = $post['id'];
    $region_name = '';
    // Pastikan region selalu diambil dengan benar
    if (isset($post['regions']) && $post['regions'] && !is_wp_error($post['regions'])) {
        if (is_array($post['regions']) && !empty($post['regions'])) {
            $region_name = $post['regions'][0]->name;
        } elseif (is_object($post['regions']) && isset($post['regions']->name)) {
            $region_name = $post['regions']->name;
        }
    }
    
    // Fallback: ambil region langsung dari post jika belum ada
    if (empty($region_name)) {
        $regions = get_the_terms($post_id, 'region');
        if ($regions && !is_wp_error($regions) && !empty($regions)) {
            $region_name = $regions[0]->name;
        }
    }
    
    $formatted_date = '';
    if ($post['proyek_date']) {
        $formatted_date = date('M d', strtotime($post['proyek_date']));
    }
    
    // Ambil gambar dengan metode yang sama seperti di template single yang berhasil
    // Gunakan get_the_post_thumbnail_url dengan post_id (sama seperti page-pelanggan.php)
    $image_url = '';
    
    // Method 1: Cek dulu apakah ada thumbnail dengan post_id
    if (has_post_thumbnail($post_id)) {
        // Coba ambil URL dengan berbagai size sebagai fallback (sama seperti page-pelanggan.php yang menggunakan medium_large)
        $image_url = get_the_post_thumbnail_url($post_id, 'medium_large');
        
        // Jika medium_large tidak ada, coba size lain
        if (empty($image_url)) {
            $image_url = get_the_post_thumbnail_url($post_id, 'full');
        }
        if (empty($image_url)) {
            $image_url = get_the_post_thumbnail_url($post_id, 'large');
        }
        if (empty($image_url)) {
            $image_url = get_the_post_thumbnail_url($post_id, 'medium');
        }
        if (empty($image_url)) {
            $image_url = get_the_post_thumbnail_url($post_id, 'thumbnail');
        }
        
        // Normalize URL jika perlu (untuk domain changes)
        if (!empty($image_url) && strpos($image_url, 'wp-content/uploads') !== false) {
            $image_url = inviro_normalize_image_url($image_url);
        }
    }
    
    // Method 2: Jika masih kosong, coba dengan wp_get_attachment_image_src langsung
    if (empty($image_url)) {
        $thumb_id = get_post_thumbnail_id($post_id);
        if ($thumb_id) {
            $image_data = wp_get_attachment_image_src($thumb_id, 'medium_large');
            if ($image_data && !empty($image_data[0])) {
                $image_url = $image_data[0];
                if (strpos($image_url, 'wp-content/uploads') !== false) {
                    $image_url = inviro_normalize_image_url($image_url);
                }
            } else {
                // Coba size lain
                $image_data = wp_get_attachment_image_src($thumb_id, 'full');
                if ($image_data && !empty($image_data[0])) {
                    $image_url = $image_data[0];
                    if (strpos($image_url, 'wp-content/uploads') !== false) {
                        $image_url = inviro_normalize_image_url($image_url);
                    }
                }
            }
        }
    }
    
    // Method 3: Jika masih kosong, coba dengan inviro_get_image_url sebagai fallback
    if (empty($image_url)) {
    $image_url = inviro_get_image_url($post_id, 'large');
        if (empty($image_url)) {
            $image_url = inviro_get_image_url($post_id, 'full');
        }
    }
    
    $card_class = 'hero-article-card';
    $title_tag = 'h2';
    
    switch ($size) {
        case 'small-top':
            $card_class .= ' hero-article-small-top';
            $title_tag = 'h3';
            break;
        case 'small':
            $card_class .= ' hero-article-small';
            $title_tag = 'h3';
            break;
        default:
            $card_class .= ' hero-article-large';
            break;
    }
    
    $category_class = $size === 'small' ? 'hero-article-category-small' : ($size === 'small-top' ? 'hero-article-category-top' : 'hero-article-category');
    $loading = $size === 'large' ? 'eager' : 'lazy';
    ?>
    <article class="<?php echo esc_attr($card_class); ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
        <a href="<?php echo esc_url($post['permalink']); ?>" class="hero-article-link">
            <div class="hero-article-image">
            <?php if (!empty($image_url)) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($post['title']); ?>" class="hero-image" loading="<?php echo esc_attr($loading); ?>" itemprop="image" style="display: block !important; visibility: visible !important; opacity: 1 !important; width: 100% !important; height: 100% !important; object-fit: cover !important;">
                    <div class="hero-article-overlay"></div>
            <?php else : ?>
                    <div class="hero-article-placeholder">
                    <div class="hero-article-overlay"></div>
                    <div class="placeholder-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo $size === 'large' ? '60' : ($size === 'small-top' ? '40' : '30'); ?>" height="<?php echo $size === 'large' ? '60' : ($size === 'small-top' ? '40' : '30'); ?>" viewBox="0 0 24 24" fill="white" opacity="0.3">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                    </div>
                </div>
            <?php endif; ?>
            </div>
            
            <?php if (!empty($region_name)) : ?>
                <span class="<?php echo esc_attr($category_class); ?>" itemprop="articleSection" style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; position: absolute !important; z-index: 100 !important;"><?php echo esc_html($region_name); ?></span>
            <?php endif; ?>
            
            <div class="hero-article-content">
                <<?php echo $title_tag; ?> class="hero-article-title" itemprop="headline"><?php echo esc_html($post['title']); ?></<?php echo $title_tag; ?>>
                <?php if ($post['client_name'] || $formatted_date) : ?>
                    <div class="hero-article-meta">
                        <?php if ($post['client_name']) : ?>
                            <span class="hero-article-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <span itemprop="name"><?php echo esc_html($post['client_name']); ?></span>
                            </span>
                        <?php endif; ?>
                        <?php if ($formatted_date) : ?>
                            <span class="hero-article-date" itemprop="datePublished">
                                - <?php echo esc_html($formatted_date); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>
    <?php
}

/**
 * Render dummy hero card (for fallback data)
 * 
 * @param array $proyek Dummy project data
 * @param string $size Card size: 'large', 'small-top', 'small'
 * @param string $image_url Image URL
 */
function inviro_render_dummy_hero_card($proyek, $size = 'large', $image_url = '') {
    if (empty($proyek)) {
        return;
    }
    
    $formatted_date = !empty($proyek['date']) ? date('M d', strtotime($proyek['date'])) : '';
    $region_name = !empty($proyek['region']) ? ucfirst($proyek['region']) : '';
    
    $card_class = 'hero-article-card';
    $title_tag = 'h2';
    
    switch ($size) {
        case 'small-top':
            $card_class .= ' hero-article-small-top';
            $title_tag = 'h3';
            break;
        case 'small':
            $card_class .= ' hero-article-small';
            $title_tag = 'h3';
            break;
        default:
            $card_class .= ' hero-article-large';
            break;
    }
    
    $category_class = $size === 'small' ? 'hero-article-category-small' : ($size === 'small-top' ? 'hero-article-category-top' : 'hero-article-category');
    $loading = $size === 'large' ? 'eager' : 'lazy';
    
    if (empty($image_url)) {
        $image_url = 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&h=800&fit=crop&q=80';
    }
    ?>
    <article class="<?php echo esc_attr($card_class); ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
        <a href="#" class="hero-article-link">
            <div class="hero-article-image">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($proyek['title']); ?>" loading="<?php echo esc_attr($loading); ?>" itemprop="image">
                <div class="hero-article-overlay"></div>
            </div>
            
            <?php if (!empty($region_name)) : ?>
                <span class="<?php echo esc_attr($category_class); ?>" itemprop="articleSection" style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; position: absolute !important; z-index: 100 !important;"><?php echo esc_html($region_name); ?></span>
            <?php endif; ?>
            
            <div class="hero-article-content">
                <<?php echo $title_tag; ?> class="hero-article-title" itemprop="headline"><?php echo esc_html($proyek['title']); ?></<?php echo $title_tag; ?>>
                <?php if (!empty($proyek['client_name']) || $formatted_date) : ?>
                    <div class="hero-article-meta">
                        <?php if (!empty($proyek['client_name'])) : ?>
                            <span class="hero-article-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <span itemprop="name"><?php echo esc_html($proyek['client_name']); ?></span>
                            </span>
                        <?php endif; ?>
                        <?php if ($formatted_date) : ?>
                            <span class="hero-article-date" itemprop="datePublished">
                                - <?php echo esc_html($formatted_date); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>
    <?php
}

/**
 * Render branch card
 * 
 * @param int $branch_id Branch post ID
 */
function inviro_render_branch_card($branch_id) {
    if (!$branch_id) {
        return;
    }
    
    // Ambil gambar dengan metode yang sama seperti hero card yang sudah berhasil
    $branch_title = get_the_title($branch_id);
    $branch_image_url = '';
    $branch_image_alt = $branch_title;
    
    // Method 1: Cek dulu apakah ada thumbnail dengan post_id (sama seperti hero card)
    if (has_post_thumbnail($branch_id)) {
        // Coba ambil URL dengan berbagai size sebagai fallback
        $branch_image_url = get_the_post_thumbnail_url($branch_id, 'medium_large');
        
        // Jika medium_large tidak ada, coba size lain
        if (empty($branch_image_url)) {
            $branch_image_url = get_the_post_thumbnail_url($branch_id, 'full');
        }
        if (empty($branch_image_url)) {
            $branch_image_url = get_the_post_thumbnail_url($branch_id, 'large');
        }
        if (empty($branch_image_url)) {
            $branch_image_url = get_the_post_thumbnail_url($branch_id, 'inviro-branch');
        }
        if (empty($branch_image_url)) {
            $branch_image_url = get_the_post_thumbnail_url($branch_id, 'medium');
        }
        if (empty($branch_image_url)) {
            $branch_image_url = get_the_post_thumbnail_url($branch_id, 'thumbnail');
        }
        
        // Normalize URL jika perlu (untuk domain changes)
        if (!empty($branch_image_url) && strpos($branch_image_url, 'wp-content/uploads') !== false) {
            $branch_image_url = inviro_normalize_image_url($branch_image_url);
        }
        
        // Ambil alt text dari attachment
        $thumb_id = get_post_thumbnail_id($branch_id);
        if ($thumb_id) {
            $alt_text = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
            if (!empty($alt_text)) {
                $branch_image_alt = $alt_text;
            }
        }
    }
    
    // Method 2: Jika masih kosong, coba dengan wp_get_attachment_image_src langsung
    if (empty($branch_image_url)) {
        $thumb_id = get_post_thumbnail_id($branch_id);
        if ($thumb_id) {
            $image_data = wp_get_attachment_image_src($thumb_id, 'medium_large');
            if ($image_data && !empty($image_data[0])) {
                $branch_image_url = $image_data[0];
                if (strpos($branch_image_url, 'wp-content/uploads') !== false) {
                    $branch_image_url = inviro_normalize_image_url($branch_image_url);
                }
            } else {
                // Coba size lain
                $image_data = wp_get_attachment_image_src($thumb_id, 'full');
                if ($image_data && !empty($image_data[0])) {
                    $branch_image_url = $image_data[0];
                    if (strpos($branch_image_url, 'wp-content/uploads') !== false) {
                        $branch_image_url = inviro_normalize_image_url($branch_image_url);
                    }
                }
            }
        }
    }
    
    // Method 3: Jika masih kosong, coba dengan inviro_get_image_url sebagai fallback
    if (empty($branch_image_url)) {
    $branch_image_url = inviro_get_image_url($branch_id, 'inviro-branch');
        if (empty($branch_image_url)) {
            $branch_image_url = inviro_get_image_url($branch_id, 'large');
        }
        if (empty($branch_image_url)) {
            $branch_image_url = inviro_get_image_url($branch_id, 'full');
        }
    }
    ?>
    <div class="branch-card">
        <div class="branch-image">
        <?php if (!empty($branch_image_url)) : ?>
                <img src="<?php echo esc_url($branch_image_url); ?>" alt="<?php echo esc_attr($branch_image_alt); ?>" loading="lazy" style="display: block !important; visibility: visible !important; opacity: 1 !important; width: 100% !important; height: 100% !important; object-fit: cover !important;">
            <?php endif; ?>
            </div>
        <h3 class="branch-name" style="display: block !important; visibility: visible !important; opacity: 1 !important;"><?php echo esc_html($branch_title); ?></h3>
    </div>
    <?php
}

/**
 * Render dummy branch card
 * 
 * @param array $branch Branch data array
 */
function inviro_render_dummy_branch_card($branch) {
    if (empty($branch)) {
        return;
    }
    ?>
    <div class="branch-card">
        <div class="branch-image">
            <img src="<?php echo esc_url($branch['image']); ?>" alt="<?php echo esc_attr($branch['name']); ?>" loading="lazy">
        </div>
        <h3 class="branch-name"><?php echo esc_html($branch['name']); ?></h3>
    </div>
    <?php
}

/**
 * Render testimonial card
 * 
 * @param array $testimonial_data Testimonial data array
 */
function inviro_render_testimonial_card($testimonial_data) {
    if (empty($testimonial_data)) {
        return;
    }
    
    $customer_name = !empty($testimonial_data['name']) ? $testimonial_data['name'] : (!empty($testimonial_data['customer_name']) ? $testimonial_data['customer_name'] : '');
    $rating = !empty($testimonial_data['rating']) ? intval($testimonial_data['rating']) : 5;
    $message = !empty($testimonial_data['message']) ? $testimonial_data['message'] : (!empty($testimonial_data['content']) ? $testimonial_data['content'] : '');
    $date = !empty($testimonial_data['date']) ? $testimonial_data['date'] : '';
    $avatar = !empty($testimonial_data['avatar']) ? $testimonial_data['avatar'] : (!empty($testimonial_data['image_url']) ? $testimonial_data['image_url'] : '');
    
    $has_schema = !empty($testimonial_data['has_schema']) ? $testimonial_data['has_schema'] : true;
    ?>
    <div class="testimonial-card"<?php echo $has_schema ? ' itemscope itemtype="https://schema.org/Review"' : ''; ?>>
        <div class="testimonial-header">
            <?php if (!empty($avatar)) : ?>
                <div class="testimonial-avatar">
                    <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($customer_name); ?>" loading="lazy">
                </div>
            <?php endif; ?>
            <div class="testimonial-meta">
                <h4 class="testimonial-name"<?php echo $has_schema ? ' itemprop="author" itemscope itemtype="https://schema.org/Person"' : ''; ?>>
                    <?php if ($has_schema) : ?>
                        <span itemprop="name"><?php echo esc_html($customer_name); ?></span>
                    <?php else : ?>
                        <?php echo esc_html($customer_name); ?>
                    <?php endif; ?>
                </h4>
                <?php if ($date) : ?>
                    <div class="testimonial-date"><?php echo esc_html($date); ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($has_schema) : ?>
            <div class="testimonial-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                <meta itemprop="ratingValue" content="<?php echo esc_attr($rating); ?>">
                <meta itemprop="bestRating" content="5">
        <?php else : ?>
            <div class="testimonial-rating">
        <?php endif; ?>
            <?php
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $rating ? '<span class="star filled">★</span>' : '<span class="star">★</span>';
            }
            ?>
        </div>
        
        <div class="testimonial-content"<?php echo $has_schema ? ' itemprop="reviewBody"' : ''; ?>>
            <?php echo $has_schema ? wpautop(esc_html($message)) : '<p>' . esc_html($message) . '</p>'; ?>
        </div>
    </div>
    <?php
}

/**
 * Get branches query
 * 
 * @param array $branch_ids Selected branch IDs
 * @param int $count Number of branches to show
 * @return WP_Query
 */
function inviro_get_branches_query($branch_ids = array(), $count = 4) {
    if (!empty($branch_ids)) {
        return new WP_Query(array(
            'post_type' => 'cabang',
            'post__in' => $branch_ids,
            'orderby' => 'post__in',
            'posts_per_page' => $count
        ));
    } else {
        return new WP_Query(array(
            'post_type' => 'cabang',
            'posts_per_page' => $count,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
    }
}


