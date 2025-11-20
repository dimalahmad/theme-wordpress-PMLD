<?php
/**
 * Template Name: Paket Usaha
 * 
 * @package INVIRO
 */

get_header();
?>

<main id="primary" class="site-main paket-usaha-page">
    
    <!-- Hero Section -->
    <section class="paket-hero">
        <div class="container">
            <div class="paket-hero-content">
                <h1>Paket Usaha</h1>
                <p>INVIRO menyediakan paket mulai dari Depot Air Minum Isi Ulang (DAMIU), mesin RO, Water Treatment Plant, Produk AMDK, dan lain-lain.</p>
            </div>
        </div>
    </section>

    <!-- Blue Bar -->
    <section class="paket-filter">
        <div class="filter-wrapper">
            <h2>Paket Usaha</h2>
        </div>
    </section>

    <!-- Packages Grid -->
    <section class="paket-grid-section">
        <div class="container">
            <div class="paket-grid">
                <?php
                // Get all paket usaha
                $packages = new WP_Query(array(
                    'post_type' => 'paket_usaha',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
                
                if ($packages->have_posts()) :
                    while ($packages->have_posts()) : $packages->the_post();
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
                                echo wp_trim_words($description, 8); 
                            }
                            ?>
                        </div>
                        
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
                    wp_reset_postdata();
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
                                        <div class="paket-price">
                                            <strong><?php echo esc_html($paket['price']); ?></strong>
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
                        <div class="no-results">
                            <p>Belum ada paket usaha. Silakan tambahkan di <strong>Paket Usaha</strong> > <strong>Tambah Paket</strong></p>
                        </div>
                        <?php
                    endif;
                endif; ?>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
