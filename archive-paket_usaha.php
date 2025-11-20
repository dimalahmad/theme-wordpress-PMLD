<?php
/**
 * The template for displaying paket usaha archive
 *
 * @package INVIRO
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Paket Usaha', 'inviro'); ?></h1>
            <?php if (get_the_archive_description()) : ?>
                <div class="archive-description">
                    <?php the_archive_description(); ?>
                </div>
            <?php endif; ?>
        </header>
        
        <div class="paket-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    $price = get_post_meta(get_the_ID(), '_paket_price', true);
                    $description = get_post_meta(get_the_ID(), '_paket_description', true);
                    ?>
                    <div class="paket-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="paket-image">
                                <?php the_post_thumbnail('inviro-product'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="paket-content">
                            <h3><?php the_title(); ?></h3>
                            
                            <div class="paket-excerpt">
                                <?php 
                                if ($description) {
                                    echo wp_trim_words($description, 15); 
                                } else {
                                    echo wp_trim_words(get_the_excerpt(), 15);
                                }
                                ?>
                            </div>
                            
                            <?php if ($price) : ?>
                                <div class="paket-meta">
                                    <div class="paket-price">
                                        <span class="current-price"><?php echo esc_html($price); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="paket-actions">
                                <button type="button" class="btn btn-primary">Detail</button>
                                <button type="button" class="btn btn-wishlist" data-paket-id="<?php echo get_the_ID(); ?>">
                                    <span class="wishlist-icon">♡</span>
                                </button>
                            </div>
                        </div>
                    </div>
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
                $dummy_paket = function_exists('inviro_get_dummy_paket_usaha') ? inviro_get_dummy_paket_usaha() : array();
                if (!empty($dummy_paket)) :
                    foreach ($dummy_paket as $paket) :
                        ?>
                        <div class="paket-card">
                            <div class="paket-image">
                                <img src="<?php echo esc_url($paket['image']); ?>" alt="<?php echo esc_attr($paket['title']); ?>" loading="lazy">
                            </div>
                            <div class="paket-content">
                                <h3><?php echo esc_html($paket['title']); ?></h3>
                                
                                <div class="paket-excerpt">
                                    <p><?php echo esc_html(wp_trim_words($paket['description'], 15)); ?></p>
                                </div>
                                
                                <?php if (!empty($paket['price'])) : ?>
                                    <div class="paket-meta">
                                        <div class="paket-price">
                                            <span class="current-price"><?php echo esc_html($paket['price']); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="paket-actions">
                                    <button type="button" class="btn btn-primary">Detail</button>
                                    <button type="button" class="btn btn-wishlist" data-paket-id="<?php echo esc_attr($paket['id']); ?>">
                                        <span class="wishlist-icon">♡</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach;
                else :
                    ?>
                    <p><?php esc_html_e('Belum ada paket usaha yang tersedia.', 'inviro'); ?></p>
                    <?php
                endif;
            endif;
            ?>
        </div>
    </div>
</main>

<?php
get_footer();
