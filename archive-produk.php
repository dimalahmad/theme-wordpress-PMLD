<?php
/**
 * The template for displaying product archive
 *
 * @package INVIRO
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Semua Produk', 'inviro'); ?></h1>
            <?php if (get_the_archive_description()) : ?>
                <div class="archive-description">
                    <?php the_archive_description(); ?>
                </div>
            <?php endif; ?>
        </header>
        
        <div class="products-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    $price = get_post_meta(get_the_ID(), '_product_price', true);
                    $original_price = get_post_meta(get_the_ID(), '_product_original_price', true);
                    $buy_url = get_post_meta(get_the_ID(), '_product_buy_url', true);
                    $description = get_post_meta(get_the_ID(), '_product_description', true);
                    
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
                            
                            <?php if ($description) : ?>
                                <div class="product-description">
                                    <?php echo wp_trim_words($description, 20); ?>
                                </div>
                            <?php endif; ?>
                            
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
                        
                        <meta itemprop="description" content="<?php echo esc_attr(wp_strip_all_tags($description)); ?>">
                    </article>
                    <?php
                endwhile;
                
                // Pagination
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('&laquo; Previous', 'inviro'),
                    'next_text' => __('Next &raquo;', 'inviro'),
                ));
            else :
                // Fallback to dummy data
                $dummy_products = function_exists('inviro_get_dummy_products') ? inviro_get_dummy_products() : array();
                if (!empty($dummy_products)) :
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
                                
                                <?php if (!empty($product['description'])) : ?>
                                    <div class="product-description">
                                        <?php echo wp_trim_words($product['description'], 20); ?>
                                    </div>
                                <?php endif; ?>
                                
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
                    ?>
                    <p><?php esc_html_e('Tidak ada produk yang ditemukan.', 'inviro'); ?></p>
                    <?php
                endif;
            endif;
            ?>
        </div>
    </div>
</main>

<?php
get_footer();

