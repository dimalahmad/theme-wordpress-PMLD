<?php
/**
 * The front page template file
 *
 * @package INVIRO
 */

get_header();
?>

<main id="main" class="site-main">
    
    <!-- Hero Section -->
    <section class="hero-section" itemscope itemtype="https://schema.org/ItemList">
        <div class="container">
            <div class="hero-articles-grid">
                <?php
                // Get 4 latest proyek pelanggan
                $proyek_query = new WP_Query(array(
                    'post_type' => 'proyek_pelanggan',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'publish'
                ));
                
                if ($proyek_query->have_posts()) :
                    $posts = array();
                    while ($proyek_query->have_posts()) : $proyek_query->the_post();
                        $posts[] = array(
                            'id' => get_the_ID(),
                            'title' => get_the_title(),
                            'permalink' => get_permalink(),
                            'thumbnail' => has_post_thumbnail() ? get_post_thumbnail_id() : null,
                            'client_name' => get_post_meta(get_the_ID(), '_proyek_client_name', true),
                            'proyek_date' => get_post_meta(get_the_ID(), '_proyek_date', true),
                            'regions' => get_the_terms(get_the_ID(), 'region'),
                        );
                    endwhile;
                    wp_reset_postdata();
                    
                    // Display first post as large card
                    if (!empty($posts[0])) :
                        $post = $posts[0];
                        $region_name = '';
                        if ($post['regions'] && !is_wp_error($post['regions']) && !empty($post['regions'])) {
                            $region_name = $post['regions'][0]->name;
                        }
                        $formatted_date = '';
                        if ($post['proyek_date']) {
                            $formatted_date = date('M d', strtotime($post['proyek_date']));
                        }
                        ?>
                        <article class="hero-article-card hero-article-large" itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
                            <a href="<?php echo esc_url($post['permalink']); ?>" class="hero-article-link">
                                <?php if ($post['thumbnail']) : ?>
                                    <div class="hero-article-image">
                                        <?php echo wp_get_attachment_image($post['thumbnail'], 'large', false, array('loading' => 'eager', 'itemprop' => 'image')); ?>
                                        <div class="hero-article-overlay"></div>
                                    </div>
                                <?php else : ?>
                                    <div class="hero-article-image hero-article-placeholder">
                                        <div class="hero-article-overlay"></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($region_name) : ?>
                                    <span class="hero-article-category" itemprop="articleSection"><?php echo esc_html($region_name); ?></span>
                                <?php endif; ?>
                                
                                <div class="hero-article-content">
                                    <h2 class="hero-article-title" itemprop="headline"><?php echo esc_html($post['title']); ?></h2>
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
                    endif;
                    
                    // Right side: nested grid with 3 cards
                    if (count($posts) > 1) :
                        ?>
                        <div class="hero-articles-grid-right">
                            <?php
                            // Card 2: Top (full width)
                            if (!empty($posts[1])) :
                                $post = $posts[1];
                                $region_name = '';
                                if ($post['regions'] && !is_wp_error($post['regions']) && !empty($post['regions'])) {
                                    $region_name = $post['regions'][0]->name;
                                }
                                ?>
                                <article class="hero-article-card hero-article-small-top" itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
                                    <a href="<?php echo esc_url($post['permalink']); ?>" class="hero-article-link">
                                        <?php if ($post['thumbnail']) : ?>
                                            <div class="hero-article-image">
                                                <?php echo wp_get_attachment_image($post['thumbnail'], 'medium', false, array('loading' => 'lazy', 'itemprop' => 'image')); ?>
                                                <div class="hero-article-overlay"></div>
                                            </div>
                                        <?php else : ?>
                                            <div class="hero-article-image hero-article-placeholder">
                                                <div class="hero-article-overlay"></div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($region_name) : ?>
                                            <span class="hero-article-category hero-article-category-top" itemprop="articleSection"><?php echo esc_html($region_name); ?></span>
                                        <?php endif; ?>
                                        
                                        <div class="hero-article-content">
                                            <h3 class="hero-article-title" itemprop="headline"><?php echo esc_html($post['title']); ?></h3>
                                        </div>
                                    </a>
                                </article>
                                <?php
                            endif;
                            
                            // Card 3 & 4: Bottom (side by side)
                            for ($i = 2; $i <= 3; $i++) :
                                if (!empty($posts[$i])) :
                                    $post = $posts[$i];
                                    $region_name = '';
                                    if ($post['regions'] && !is_wp_error($post['regions']) && !empty($post['regions'])) {
                                        $region_name = $post['regions'][0]->name;
                                    }
                                    $card_class = ($i === 2) ? 'hero-article-small-bottom-left' : 'hero-article-small-bottom-right';
                                    ?>
                                    <article class="hero-article-card <?php echo esc_attr($card_class); ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
                                        <a href="<?php echo esc_url($post['permalink']); ?>" class="hero-article-link">
                                            <?php if ($post['thumbnail']) : ?>
                                                <div class="hero-article-image">
                                                    <?php echo wp_get_attachment_image($post['thumbnail'], 'medium', false, array('loading' => 'lazy', 'itemprop' => 'image')); ?>
                                                    <div class="hero-article-overlay"></div>
                                                </div>
                                            <?php else : ?>
                                                <div class="hero-article-image hero-article-placeholder">
                                                    <div class="hero-article-overlay"></div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($region_name) : ?>
                                                <span class="hero-article-category hero-article-category-bottom" itemprop="articleSection"><?php echo esc_html($region_name); ?></span>
                                            <?php endif; ?>
                                            
                                            <div class="hero-article-content">
                                                <h3 class="hero-article-title" itemprop="headline"><?php echo esc_html($post['title']); ?></h3>
                                            </div>
                                        </a>
                                    </article>
                                    <?php
                                endif;
                            endfor;
                            ?>
                        </div>
                        <?php
                    endif;
                else :
                    // Fallback to dummy data
                    $dummy_pelanggan = function_exists('inviro_get_dummy_pelanggan') ? inviro_get_dummy_pelanggan() : array();
                    if (!empty($dummy_pelanggan)) :
                        // Limit to 4 items (1 large + 3 small)
                        $dummy_pelanggan = array_slice($dummy_pelanggan, 0, 4);
                        
                        // Use same demo image for all cards (water refilling station)
                        $demo_image = 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&h=800&fit=crop&q=80';
                        
                        // Display first item as large card
                        if (!empty($dummy_pelanggan[0])) :
                            $proyek = $dummy_pelanggan[0];
                            $formatted_date = '';
                            if (!empty($proyek['date'])) {
                                $formatted_date = date('M d', strtotime($proyek['date']));
                            }
                            $region_name = !empty($proyek['region']) ? ucfirst($proyek['region']) : '';
                            ?>
                            <article class="hero-article-card hero-article-large" itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
                                <a href="#" class="hero-article-link">
                                    <div class="hero-article-image">
                                        <img src="<?php echo esc_url($demo_image); ?>" alt="<?php echo esc_attr($proyek['title']); ?>" loading="eager" itemprop="image">
                                        <div class="hero-article-overlay"></div>
                                    </div>
                                    
                                    <?php if ($region_name) : ?>
                                        <span class="hero-article-category" itemprop="articleSection"><?php echo esc_html($region_name); ?></span>
                                    <?php endif; ?>
                                    
                                    <div class="hero-article-content">
                                        <h2 class="hero-article-title" itemprop="headline"><?php echo esc_html($proyek['title']); ?></h2>
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
                        endif;
                        
                        // Right side: nested grid with 3 cards
                        if (count($dummy_pelanggan) > 1) :
                            ?>
                            <div class="hero-articles-grid-right">
                                <?php
                                // Card 2: Top (full width)
                                if (!empty($dummy_pelanggan[1])) :
                                    $proyek = $dummy_pelanggan[1];
                                    $region_name = !empty($proyek['region']) ? ucfirst($proyek['region']) : '';
                                    ?>
                                    <article class="hero-article-card hero-article-small-top" itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
                                        <a href="#" class="hero-article-link">
                                            <div class="hero-article-image">
                                                <img src="<?php echo esc_url($demo_image); ?>" alt="<?php echo esc_attr($proyek['title']); ?>" loading="lazy" itemprop="image">
                                                <div class="hero-article-overlay"></div>
                                            </div>
                                            
                                            <?php if ($region_name) : ?>
                                                <span class="hero-article-category hero-article-category-top" itemprop="articleSection"><?php echo esc_html($region_name); ?></span>
                                            <?php endif; ?>
                                            
                                            <div class="hero-article-content">
                                                <h3 class="hero-article-title" itemprop="headline"><?php echo esc_html($proyek['title']); ?></h3>
                                            </div>
                                        </a>
                                    </article>
                                    <?php
                                endif;
                                
                                // Card 3 & 4: Bottom (side by side)
                                for ($i = 2; $i <= 3; $i++) :
                                    if (!empty($dummy_pelanggan[$i])) :
                                        $proyek = $dummy_pelanggan[$i];
                                        $region_name = !empty($proyek['region']) ? ucfirst($proyek['region']) : '';
                                        $card_class = ($i === 2) ? 'hero-article-small-bottom-left' : 'hero-article-small-bottom-right';
                                        ?>
                                        <article class="hero-article-card <?php echo esc_attr($card_class); ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
                                            <a href="#" class="hero-article-link">
                                                <div class="hero-article-image">
                                                    <img src="<?php echo esc_url($demo_image); ?>" alt="<?php echo esc_attr($proyek['title']); ?>" loading="lazy" itemprop="image">
                                                    <div class="hero-article-overlay"></div>
                                                </div>
                                                
                                                <?php if ($region_name) : ?>
                                                    <span class="hero-article-category hero-article-category-bottom" itemprop="articleSection"><?php echo esc_html($region_name); ?></span>
                                                <?php endif; ?>
                                                
                                                <div class="hero-article-content">
                                                    <h3 class="hero-article-title" itemprop="headline"><?php echo esc_html($proyek['title']); ?></h3>
                                                </div>
                                            </a>
                                        </article>
                                        <?php
                                    endif;
                                endfor;
                                ?>
                            </div>
            <?php
                        endif;
                    else :
                        // Ultimate fallback jika tidak ada dummy data
            ?>
                        <div class="hero-article-card hero-article-large">
                            <div class="hero-article-image hero-article-placeholder">
                                <div class="hero-article-overlay"></div>
                        </div>
                            <div class="hero-article-content">
                                <span class="hero-article-category">Proyek</span>
                                <h2 class="hero-article-title">Belum ada proyek pelanggan</h2>
                                <p>Tambah proyek pelanggan di WordPress Admin untuk menampilkan di sini.</p>
                    </div>
                </div>
                        <?php
                    endif;
                endif;
                ?>
            </div>
        </div>
    
    <!-- Statistics Section -->
        <div class="stats-section">
        <div class="container">
                <div class="stats-grid" itemscope itemtype="https://schema.org/ItemList">
                    <div class="stat-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <meta itemprop="position" content="1">
                        <div class="stat-number" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_stat_1_number', '105+')); ?></div>
                        <div class="stat-label"><?php echo esc_html(get_theme_mod('inviro_stat_1_label', 'Corporate Portofolio by INVIRO')); ?></div>
                    </div>
                    <div class="stat-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <meta itemprop="position" content="2">
                        <div class="stat-number" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_stat_2_number', '30+')); ?></div>
                        <div class="stat-label"><?php echo esc_html(get_theme_mod('inviro_stat_2_label', 'Pengguna produk di Indonesia')); ?></div>
                </div>
                    <div class="stat-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <meta itemprop="position" content="3">
                        <div class="stat-number" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_stat_3_number', '+95%')); ?></div>
                        <div class="stat-label"><?php echo esc_html(get_theme_mod('inviro_stat_3_label', 'Kepuasan Pelanggan')); ?></div>
                </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="about-section" itemscope itemtype="https://schema.org/AboutPage">
        <div class="container">
            <div class="about-main-title">
                <?php
                // Use custom logo from WordPress (same as footer)
                if (has_custom_logo()) {
                    // Get custom logo ID and URL
                    $custom_logo_id = get_theme_mod('custom_logo');
                    if ($custom_logo_id) {
                        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                        $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                        if (empty($logo_alt)) {
                            $logo_alt = get_bloginfo('name');
                        }
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="custom-logo" />
                        </a>
                        <?php
                    } else {
                        the_custom_logo();
                    }
                } else {
                    // Fallback: text logo
                    ?>
                    <h2><?php echo esc_html(get_theme_mod('inviro_about_title', 'INVIRO')); ?></h2>
                    <?php
                }
                ?>
            </div>
            <div class="about-content">
                <?php
                // Get number of branches to display from customizer
                $branch_count = get_theme_mod('inviro_branch_count', 4);
                $branch_count = max(1, min(8, absint($branch_count))); // Ensure between 1-8
                ?>
                <div class="about-branches branch-count-<?php echo esc_attr($branch_count); ?>">
                    <?php
                    
                    // Get selected branches from customizer
                    $displayed_branches = array();
                    for ($i = 1; $i <= 8; $i++) {
                        $branch_id = get_theme_mod('inviro_branch_' . $i);
                        if ($branch_id) {
                            $displayed_branches[] = $branch_id;
                        }
                    }
                    
                    // Limit to the number of branches specified
                    if (count($displayed_branches) > $branch_count) {
                        $displayed_branches = array_slice($displayed_branches, 0, $branch_count);
                    }
                    
                    // Display selected branches
                    if (!empty($displayed_branches)) :
                        $branches_query = new WP_Query(array(
                            'post_type' => 'cabang',
                            'post__in' => $displayed_branches,
                            'orderby' => 'post__in',
                            'posts_per_page' => $branch_count
                        ));
                        
                        if ($branches_query->have_posts()) :
                            while ($branches_query->have_posts()) : $branches_query->the_post();
                                $location = get_post_meta(get_the_ID(), '_branch_location', true);
                                ?>
                                <div class="branch-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="branch-image">
                                            <?php the_post_thumbnail('inviro-branch'); ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="branch-name"><?php the_title(); ?></h3>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                    else :
                        // Fallback: display latest branches if none selected
                        $branches_query = new WP_Query(array(
                            'post_type' => 'cabang',
                            'posts_per_page' => $branch_count,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($branches_query->have_posts()) :
                            while ($branches_query->have_posts()) : $branches_query->the_post();
                                $location = get_post_meta(get_the_ID(), '_branch_location', true);
                                ?>
                                <div class="branch-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="branch-image">
                                            <?php the_post_thumbnail('inviro-branch'); ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="branch-name"><?php the_title(); ?></h3>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            // Fallback to dummy data
                            $dummy_branches = function_exists('inviro_get_dummy_branches') ? inviro_get_dummy_branches() : array();
                            if (!empty($dummy_branches)) :
                                $dummy_branches = array_slice($dummy_branches, 0, 4);
                                foreach ($dummy_branches as $branch) :
                                    ?>
                                    <div class="branch-card">
                                        <div class="branch-image">
                                            <img src="<?php echo esc_url($branch['image']); ?>" alt="<?php echo esc_attr($branch['name']); ?>" loading="lazy">
                                        </div>
                                        <h3 class="branch-name"><?php echo esc_html($branch['name']); ?></h3>
                                    </div>
                                    <?php
                                endforeach;
                            endif;
                        endif;
                    endif;
                    ?>
                </div>
                
                <div class="about-text">
                    <div class="about-description" itemprop="description">
                        <?php echo wp_kses_post(get_theme_mod('inviro_about_description', 'INVIRO menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air. INVIRO adalah divisi usaha dari CV. INDO SOLUTION yang memiliki kantor di Jakarta, Surabaya, Bandung, Semarang, dan kantor pusat di Jogjakarta. Kami bergerak dalam bidang Water Treatment, Water Purifier, Water Equipment, Water Purification Systems, dan usaha-usaha terkait lainnya. Kami melayani kebutuhan individu/rumah tangga, rumah sakit, pabrik, kantor, asrama, hotel, penginapan, restoran, sekolah, dan sektor komersial lainnya.')); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Products Section -->
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
                <?php
                // Get 8 best selling products
                // Priority: Featured products from customizer > Products with sales count > Latest products
                
                // Check for featured products from customizer (highest priority)
                $featured_products = array();
                for ($i = 1; $i <= 8; $i++) {
                    $product_id = get_theme_mod('inviro_featured_product_' . $i);
                    if ($product_id) {
                        $featured_products[] = $product_id;
                    }
                }
                
                // If there are featured products, use them (these are considered best sellers)
                if (!empty($featured_products)) {
                    $args = array(
                        'post_type' => 'produk',
                        'posts_per_page' => 8,
                        'post__in' => array_slice($featured_products, 0, 8),
                        'orderby' => 'post__in'
                    );
                } else {
                    // Try to get products with sales count meta (best sellers)
                    $args = array(
                        'post_type' => 'produk',
                        'posts_per_page' => 8,
                        'meta_key' => '_product_sales_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC'
                    );
                    
                    // Check if we have products with sales count
                    $test_query = new WP_Query($args);
                    wp_reset_postdata();
                    
                    if ($test_query->found_posts < 8) {
                        // If less than 8 products have sales count, get latest products to fill
                        $args = array(
                            'post_type' => 'produk',
                            'posts_per_page' => 8,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        );
                    }
                }
                
                $products = new WP_Query($args);
                
                if ($products->have_posts()) :
                    while ($products->have_posts()) : $products->the_post();
                        $price = get_post_meta(get_the_ID(), '_product_price', true);
                        $original_price = get_post_meta(get_the_ID(), '_product_original_price', true);
                        $buy_url = get_post_meta(get_the_ID(), '_product_buy_url', true);
                        
                        // Fallback URL jika tidak diisi
                        if (empty($buy_url)) {
                            $buy_url = get_theme_mod('inviro_whatsapp') ? 'https://wa.me/' . get_theme_mod('inviro_whatsapp') : '#';
                        }
                        ?>
                        <article class="product-card" data-product-id="<?php echo esc_attr(get_the_ID()); ?>" itemscope itemtype="https://schema.org/Product">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="product-image">
                                    <?php the_post_thumbnail('inviro-product'); ?>
                                    <button class="product-like" data-product-id="<?php echo esc_attr(get_the_ID()); ?>" aria-label="Like product">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-info">
                                <h3 class="product-title" itemprop="name"><?php the_title(); ?></h3>
                                
                                <div class="product-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <?php if ($original_price) : ?>
                                        <span class="product-original-price"><?php echo esc_html($original_price); ?></span>
                                    <?php endif; ?>
                                    <?php if ($price) : ?>
                                        <span class="product-current-price" itemprop="price"><?php echo esc_html($price); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <a href="<?php echo esc_url($buy_url); ?>" class="btn btn-product" target="_blank" rel="noopener"><?php esc_html_e('Beli', 'inviro'); ?></a>
                            </div>
                            
                            <meta itemprop="description" content="<?php echo esc_attr(wp_strip_all_tags(get_the_excerpt())); ?>">
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback to dummy data
                    $dummy_products = function_exists('inviro_get_dummy_products') ? inviro_get_dummy_products() : array();
                    if (!empty($dummy_products)) :
                        // Limit to 8 products
                        $dummy_products = array_slice($dummy_products, 0, 8);
                        foreach ($dummy_products as $product) :
                            $buy_url = get_theme_mod('inviro_whatsapp') ? 'https://wa.me/' . get_theme_mod('inviro_whatsapp') : '#';
                            ?>
                            <article class="product-card" data-product-id="<?php echo esc_attr($product['id']); ?>" itemscope itemtype="https://schema.org/Product">
                                <div class="product-image">
                                    <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['title']); ?>" loading="lazy">
                                    <button class="product-like" data-product-id="<?php echo esc_attr($product['id']); ?>" aria-label="Like product">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title" itemprop="name"><?php echo esc_html($product['title']); ?></h3>
                                    <div class="product-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                        <?php if (!empty($product['original_price'])) : ?>
                                            <span class="product-original-price"><?php echo esc_html($product['original_price']); ?></span>
                                        <?php endif; ?>
                                        <span class="product-current-price" itemprop="price"><?php echo esc_html($product['price']); ?></span>
                                    </div>
                                    <a href="<?php echo esc_url($buy_url); ?>" class="btn btn-product" target="_blank" rel="noopener"><?php esc_html_e('Beli', 'inviro'); ?></a>
                                </div>
                                <meta itemprop="description" content="<?php echo esc_attr($product['description']); ?>">
                            </article>
                            <?php
                        endforeach;
                    else :
                        // Ultimate fallback - hardcoded products
                    for ($i = 1; $i <= 4; $i++) :
                        ?>
                        <article class="product-card">
                            <div class="product-image">
                                    <img src="https://via.placeholder.com/400x400/75C6F1/FFFFFF?text=Product+<?php echo $i; ?>" alt="Product <?php echo $i; ?>" loading="lazy">
                                <button class="product-like" aria-label="Like product">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php esc_html_e('Mesin RO 20.000 GPD Kapasitas Setara 2000 Liter/Jam', 'inviro'); ?></h3>
                                <div class="product-price">
                                    <span class="product-original-price">Rp6.000.000</span>
                                    <span class="product-current-price">Rp5.000.000</span>
                                </div>
                                <a href="#" class="btn btn-product"><?php esc_html_e('Beli', 'inviro'); ?></a>
                            </div>
                        </article>
                        <?php
                    endfor;
                    endif;
                endif;
                ?>
            </div>
            
            <div class="section-footer">
                <a href="<?php echo esc_url(get_post_type_archive_link('produk')); ?>" class="btn-view-all-products"><?php esc_html_e('Lihat semua produk', 'inviro'); ?></a>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_testimonials_title', 'Dipercaya Oleh Banyak Pelanggan')); ?></h2>
                <p class="section-subtitle" itemprop="description"><?php echo esc_html(get_theme_mod('inviro_testimonials_subtitle', '95% Pelanggan INVIRO di berbagai daerah di Indonesia merasa puas dengan pelayanan & produk INVIRO')); ?></p>
            </div>
            
            <div class="testimonials-carousel">
                <div class="testimonials-track">
                    <?php
                    // Get selected testimonials from customizer (up to 10)
                    $selected_testimonials = array();
                    for ($i = 1; $i <= 10; $i++) {
                        $testimonial_id = get_theme_mod('inviro_testimonial_' . $i);
                        if ($testimonial_id) {
                            $selected_testimonials[] = $testimonial_id;
                        }
                    }
                    
                    if (!empty($selected_testimonials)) :
                        $testimonials = new WP_Query(array(
                            'post_type' => 'testimoni',
                            'post__in' => $selected_testimonials,
                            'orderby' => 'post__in',
                            'posts_per_page' => -1
                        ));
                        
                        if ($testimonials->have_posts()) :
                            while ($testimonials->have_posts()) : $testimonials->the_post();
                                $customer_name = get_post_meta(get_the_ID(), '_testimonial_customer_name', true);
                                $rating = get_post_meta(get_the_ID(), '_testimonial_rating', true);
                                $message = get_post_meta(get_the_ID(), '_testimonial_message', true);
                                $date = get_post_meta(get_the_ID(), '_testimonial_date', true);
                                
                                if (!$customer_name) $customer_name = get_the_title();
                                if (!$rating) $rating = 5;
                                if (!$message) $message = get_the_content();
                                if (!$date) $date = get_the_date('d / m / Y');
                                ?>
                                <div class="testimonial-card" itemscope itemtype="https://schema.org/Review">
                                    <div class="testimonial-header">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="testimonial-avatar">
                                                <?php the_post_thumbnail('inviro-testimonial'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="testimonial-meta">
                                            <h4 class="testimonial-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                <span itemprop="name"><?php echo esc_html($customer_name); ?></span>
                                            </h4>
                                            <div class="testimonial-date"><?php echo esc_html($date); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="testimonial-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="<?php echo esc_attr($rating); ?>">
                                        <meta itemprop="bestRating" content="5">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<span class="star filled">★</span>';
                                            } else {
                                                echo '<span class="star">★</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    
                                    <div class="testimonial-content" itemprop="reviewBody">
                                        <?php echo wpautop(esc_html($message)); ?>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                    else :
                        // Fallback to dummy data
                        $dummy_testimonials = function_exists('inviro_get_dummy_testimonials') ? inviro_get_dummy_testimonials() : array();
                        if (!empty($dummy_testimonials)) :
                            foreach ($dummy_testimonials as $testimonial) :
                                ?>
                                <div class="testimonial-card" itemscope itemtype="https://schema.org/Review">
                                    <div class="testimonial-header">
                                        <div class="testimonial-avatar">
                                            <img src="<?php echo esc_url($testimonial['avatar']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" loading="lazy">
                                        </div>
                                        <div class="testimonial-meta">
                                            <h4 class="testimonial-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                <span itemprop="name"><?php echo esc_html($testimonial['name']); ?></span>
                                            </h4>
                                            <div class="testimonial-date"><?php echo esc_html($testimonial['date']); ?></div>
                                        </div>
                                    </div>
                                    <div class="testimonial-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="<?php echo esc_attr($testimonial['rating']); ?>">
                                        <meta itemprop="bestRating" content="5">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $testimonial['rating']) {
                                                echo '<span class="star filled">★</span>';
                                            } else {
                                                echo '<span class="star">★</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="testimonial-content" itemprop="reviewBody">
                                        <?php echo wpautop(esc_html($testimonial['message'])); ?>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        else :
                            // Ultimate fallback - hardcoded testimonials
                        $default_testimonials = array(
                                array('name' => 'Robert B.', 'rating' => 5, 'date' => '1/1/2024', 'content' => 'Wow... I am so happy to see this business is turned out to be more than my expectations and as for the service, I am so pleased. Thank you so much!', 'avatar' => 'https://via.placeholder.com/100x100/75C6F1/FFFFFF?text=RB'),
                                array('name' => 'Diana M.', 'rating' => 5, 'date' => '1/1/2024', 'content' => 'Wow... I am very happy to use this service. It turned out to be more than my expectations and as for the service, I am so pleased. Thank you so much!', 'avatar' => 'https://via.placeholder.com/100x100/FF8B25/FFFFFF?text=DM'),
                                array('name' => 'Syahrani P.', 'rating' => 5, 'date' => '1/10/2024', 'content' => 'Wow... I am very happy to use this Service. It turned out to be more than my expectations and so far there have been no problems. Inviro always the best.', 'avatar' => 'https://via.placeholder.com/100x100/4FB3E8/FFFFFF?text=SP')
                        );
                        
                        foreach ($default_testimonials as $testimonial) :
                            ?>
                            <div class="testimonial-card">
                                <div class="testimonial-header">
                                    <div class="testimonial-avatar">
                                            <img src="<?php echo esc_url($testimonial['avatar']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" loading="lazy">
                                    </div>
                                    <div class="testimonial-meta">
                                        <h4 class="testimonial-name"><?php echo esc_html($testimonial['name']); ?></h4>
                                        <div class="testimonial-date"><?php echo esc_html($testimonial['date']); ?></div>
                                    </div>
                                </div>
                                <div class="testimonial-rating">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $testimonial['rating']) {
                                            echo '<span class="star filled">★</span>';
                                        } else {
                                            echo '<span class="star">★</span>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="testimonial-content">
                                    <p><?php echo esc_html($testimonial['content']); ?></p>
                                </div>
                            </div>
                            <?php
                        endforeach;
                        endif;
                    endif;
                    ?>
                </div>
                
                <div class="testimonials-controls">
                    <button class="testimonial-prev" aria-label="Previous testimonial">‹</button>
                    <button class="testimonial-next" aria-label="Next testimonial">›</button>
                </div>
                
                <div class="testimonials-indicators"></div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="contact-section" itemscope itemtype="https://schema.org/ContactPage">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_contact_title', 'Hubungi Kami untuk Layanan Terbaik')); ?></h2>
                <p class="section-subtitle" itemprop="description"><?php echo esc_html(get_theme_mod('inviro_contact_description', 'Untuk informasi lebih lanjut mengenai produk dan layanan kami, jangan ragu untuk menghubungi kami')); ?></p>
            </div>
            
            <div class="contact-content">
                <div class="contact-map">
                    <?php
                    $map_url = get_theme_mod('inviro_contact_map_url', '');
                    if ($map_url) {
                        // Simply use iframe with the URL directly - let browser handle it
                        // Google Maps will auto-convert most URL formats when used in iframe
                        echo '<iframe src="' . esc_url($map_url) . '" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
                    } else {
                        ?>
                        <div class="map-placeholder" itemscope itemtype="https://schema.org/LocalBusiness">
                            <div class="map-placeholder-content">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <p><?php esc_html_e('Peta akan ditampilkan di sini', 'inviro'); ?></p>
                                <p class="small"><?php esc_html_e('Tambahkan URL embed Google Maps di Customizer', 'inviro'); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
                <div class="contact-features">
                    <?php for ($i = 1; $i <= 3; $i++) : 
                        $icon = get_theme_mod('inviro_contact_feature_' . $i . '_icon', $i == 1 ? 'phone' : ($i == 2 ? 'tag' : 'map-pin'));
                        $title = get_theme_mod('inviro_contact_feature_' . $i . '_title', $i == 1 ? 'Customer Support' : ($i == 2 ? 'Harga & Kualitas Terjamin' : 'Banyak Lokasi'));
                        $description = get_theme_mod('inviro_contact_feature_' . $i . '_description', $i == 1 ? 'Tim support kami siap membantu Anda 24/7' : ($i == 2 ? 'Harga terbaik dengan kualitas premium' : 'Hadir di berbagai kota di Indonesia'));
                        $color = get_theme_mod('inviro_contact_feature_' . $i . '_color', $i == 1 ? '#28a745' : ($i == 2 ? '#ff8c00' : '#dc3545'));
                        ?>
                        <div class="feature-item">
                            <div class="feature-icon" style="background-color: <?php echo esc_attr($color); ?>;">
                                <?php echo inviro_get_feature_icon($icon); ?>
                            </div>
                            <div class="feature-content">
                                <h3><?php echo esc_html($title); ?></h3>
                                <p><?php echo esc_html($description); ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="contact-form-wrapper">
                <h3 class="form-title"><?php esc_html_e('Silakan tinggalkan saran atau tanggapan Anda!', 'inviro'); ?></h3>
                <form id="inviro-contact-form" class="contact-form" method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-name"><?php esc_html_e('Nama', 'inviro'); ?> <span class="required">*</span></label>
                            <input type="text" id="contact-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email"><?php esc_html_e('Email', 'inviro'); ?> <span class="required">*</span></label>
                            <input type="email" id="contact-email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-subject"><?php esc_html_e('Subjek', 'inviro'); ?></label>
                            <input type="text" id="contact-subject" name="subject">
                        </div>
                        <div class="form-group">
                            <label for="contact-phone"><?php esc_html_e('Nomor Ponsel', 'inviro'); ?></label>
                            <input type="tel" id="contact-phone" name="phone">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact-message"><?php esc_html_e('Pesan', 'inviro'); ?> <span class="required">*</span></label>
                        <textarea id="contact-message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-submit">
                        <button type="submit" class="btn btn-primary"><?php esc_html_e('Kirim Pesan', 'inviro'); ?></button>
                    </div>
                    
                    <div class="form-message"></div>
                </form>
            </div>
        </div>
    </section>
    
</main>

<?php
get_footer();

