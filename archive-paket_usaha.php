<?php
/**
 * The template for displaying paket usaha archive
 *
 * @package INVIRO
 */

// Ensure this template is used for paket-usaha page
// Fix query if it's empty or 404
global $wp_query;
if (empty($wp_query->posts) || $wp_query->is_404) {
    // Try to find paket-usaha page
    $paket_usaha_page = get_page_by_path('paket-usaha');
    if (!$paket_usaha_page) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-paket-usaha.php',
            'number' => 1
        ));
        if (!empty($pages)) {
            $paket_usaha_page = $pages[0];
        }
    }
    if ($paket_usaha_page) {
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->is_404 = false;
        $wp_query->is_archive = false;
        $wp_query->is_post_type_archive = false;
        $wp_query->queried_object = $paket_usaha_page;
        $wp_query->queried_object_id = $paket_usaha_page->ID;
        $wp_query->posts = array($paket_usaha_page);
        $wp_query->post_count = 1;
        $wp_query->found_posts = 1;
        $wp_query->max_num_pages = 1;
    }
}

get_header();
?>

<div class="paket-usaha-page">
    <!-- Hero Section -->
    <section class="paket-usaha-hero">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo esc_html(get_theme_mod('inviro_paket_usaha_hero_title', 'Paket Usaha Premium')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('inviro_paket_usaha_hero_subtitle', 'Solusi lengkap paket usaha berkualitas tinggi untuk bisnis depot air minum Anda. Dapatkan paket terbaik dengan komponen lengkap dan terpercaya.')); ?></p>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="paket-usaha-filters">
        <div class="container">
            <div class="filters-wrapper">
                <div class="search-box">
                    <input type="text" id="paket-search" placeholder="Cari paket usaha yang Anda butuhkan..." value="">
                </div>
                <div class="filter-dropdowns">
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'paket_usaha_category',
                        'hide_empty' => false,
                    ));
                    ?>
                    <select id="category-filter">
                        <option value="">Semua Kategori</option>
                        <?php if ($categories && !is_wp_error($categories)) : ?>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <select id="sort-filter">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="price-low">Harga: Rendah ke Tinggi</option>
                        <option value="price-high">Harga: Tinggi ke Rendah</option>
                        <option value="name-asc">Nama: A-Z</option>
                        <option value="name-desc">Nama: Z-A</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Paket Usaha Grid -->
    <section class="paket-usaha-grid-section">
        <div class="container">
            <div class="paket-usaha-grid">
                <?php
                $pakets = new WP_Query(array(
                    'post_type' => 'paket_usaha',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($pakets->have_posts()) :
                    while ($pakets->have_posts()) : $pakets->the_post();
                        $post_id = get_the_ID();
                        $price = get_post_meta($post_id, '_paket_price', true);
                        $original_price = get_post_meta($post_id, '_paket_original_price', true);
                        $sku = get_post_meta($post_id, '_paket_sku', true);
                        $promo = get_post_meta($post_id, '_paket_promo', true);
                        $categories = get_the_terms($post_id, 'paket_usaha_category');
                        $category_slugs = '';
                        if ($categories && !is_wp_error($categories)) {
                            $category_slugs = implode(' ', array_map(function($cat) { return $cat->slug; }, $categories));
                        }
                        
                        // Tentukan status promo - cek dari meta field atau original_price
                        $is_promo = false;
                        if ($promo == '1' || $promo === 1 || $promo === '1') {
                            $is_promo = true;
                        } elseif ($original_price && $original_price > 0 && $price && $original_price > $price) {
                            // Jika ada original_price yang lebih besar dari price, berarti promo
                            $is_promo = true;
                        } elseif ($categories && !is_wp_error($categories)) {
                            // Cek apakah ada kategori "promo"
                            foreach ($categories as $cat) {
                                if ($cat->slug == 'promo') {
                                    $is_promo = true;
                                    break;
                                }
                            }
                        }
                        
                        // Get image - SAMA PERSIS DENGAN DUMMY DATA
                        // Priority 1: Featured Image
                        $paket_image = '';
                        $post_id = get_the_ID();
                        
                        // Cek Featured Image terlebih dahulu
                        if (has_post_thumbnail($post_id)) {
                            $paket_image = get_the_post_thumbnail_url($post_id, 'medium');
                            if (empty($paket_image)) {
                                $paket_image = get_the_post_thumbnail_url($post_id, 'large');
                            }
                            if (empty($paket_image)) {
                                $paket_image = get_the_post_thumbnail_url($post_id, 'full');
                            }
                        } 
                        
                        // Priority 2: First image from gallery
                        if (empty($paket_image)) {
                            $gallery_ids = get_post_meta($post_id, '_paket_gallery', true);
                            if (!empty($gallery_ids)) {
                                // Handle jika string atau array
                                if (is_string($gallery_ids)) {
                                    $gallery_ids = explode(',', $gallery_ids);
                                }
                                
                                // Filter dan trim
                                $gallery_ids = array_filter(array_map('trim', $gallery_ids));
                                
                                if (!empty($gallery_ids)) {
                                    $gallery_ids = array_values($gallery_ids);
                                    $first_gallery_id = trim($gallery_ids[0]);
                                    
                                    // Pastikan numeric
                                    if (is_numeric($first_gallery_id)) {
                                        $first_gallery_id = intval($first_gallery_id);
                                        
                                        // Coba berbagai ukuran
                                        $paket_image = wp_get_attachment_image_url($first_gallery_id, 'medium');
                                        if (empty($paket_image)) {
                                            $paket_image = wp_get_attachment_image_url($first_gallery_id, 'large');
                                        }
                                        if (empty($paket_image)) {
                                            $paket_image = wp_get_attachment_image_url($first_gallery_id, 'full');
                                        }
                                        if (empty($paket_image)) {
                                            // Fallback: ambil URL langsung dari attachment
                                            $paket_image = wp_get_attachment_url($first_gallery_id);
                                        }
                                    }
                                }
                            }
                        }
                    ?>
                    <div class="paket-card" data-price="<?php echo esc_attr($price); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>" data-category="<?php echo esc_attr($category_slugs); ?>">
                        <div class="paket-image" style="height: 240px; min-height: 240px; max-height: 240px; overflow: hidden;">
                            <?php if (!empty($paket_image)) : ?>
                                <img src="<?php echo esc_url($paket_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width: 100%; height: 240px; min-height: 240px; max-height: 240px; object-fit: cover; object-position: center; display: block; margin: 0; padding: 0;">
                            <?php else : ?>
                                <!-- Placeholder jika tidak ada gambar -->
                                <div class="paket-image-placeholder" style="display: flex; align-items: center; justify-content: center; height: 240px;">
                                    <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <?php if ($is_promo) : ?>
                                <span class="stock-badge promo-badge">Promo</span>
                            <?php else : ?>
                                <span class="stock-badge in-stock">Tersedia</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="paket-content">
                            <?php if ($sku) : ?>
                                <span class="paket-sku"><?php echo esc_html($sku); ?></span>
                            <?php endif; ?>
                            
                            <h3><?php the_title(); ?></h3>
                            
                            <?php 
                            $description = get_post_meta(get_the_ID(), '_paket_description', true);
                            if ($description) : ?>
                                <p class="paket-desc"><?php echo esc_html(wp_trim_words($description, 15)); ?></p>
                            <?php endif; ?>
                            
                            <div class="paket-meta">
                                <?php if ($price) : ?>
                                    <?php 
                                    // Jika ada original_price dan lebih besar dari price, tampilkan keduanya
                                    if ($original_price && $original_price > 0 && $original_price > $price) : ?>
                                        <div class="paket-price-wrapper">
                                            <span class="paket-price-original">Rp <?php echo number_format($original_price, 0, ',', '.'); ?></span>
                                            <span class="paket-price paket-price-promo">Rp <?php echo number_format($price, 0, ',', '.'); ?></span>
                                        </div>
                                    <?php else : ?>
                                        <span class="paket-price">
                                            Rp <?php echo number_format($price, 0, ',', '.'); ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="paket-actions">
                                <a href="<?php echo esc_url(get_permalink()); ?>" 
                                   class="btn-order">
                                    Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="paket-usaha-cta">
        <div class="container">
            <div class="cta-content">
                <h2><?php echo esc_html(get_theme_mod('inviro_paket_usaha_cta_title', 'Butuh Konsultasi Spesialis?')); ?></h2>
                <p><?php echo esc_html(get_theme_mod('inviro_paket_usaha_cta_subtitle', 'Tim ahli kami siap membantu Anda menemukan paket usaha yang tepat untuk kebutuhan bisnis depot air minum Anda')); ?></p>
                <?php $wa_number_cta = get_theme_mod('inviro_whatsapp', '6281234567890'); ?>
                <a href="https://wa.me/<?php echo esc_attr($wa_number_cta); ?>" class="btn-whatsapp" target="_blank">
                    <?php echo esc_html(get_theme_mod('inviro_paket_usaha_cta_button', 'Chat WhatsApp')); ?>
                </a>
            </div>
        </div>
    </section>
</div>

<script>
jQuery(document).ready(function($) {
    // Search functionality
    $('#paket-search').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.paket-card').each(function() {
            var cardName = $(this).data('name').toLowerCase();
            if (cardName.indexOf(searchTerm) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Category filter
    $('#category-filter').on('change', function() {
        var selectedCategory = $(this).val();
        $('.paket-card').each(function() {
            var cardCategories = $(this).data('category');
            if (selectedCategory === '' || cardCategories.indexOf(selectedCategory) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Sort functionality
    $('#sort-filter').on('change', function() {
        var sortValue = $(this).val();
        var $grid = $('.paket-usaha-grid');
        var $cards = $('.paket-card').toArray();
        
        $cards.sort(function(a, b) {
            var $a = $(a);
            var $b = $(b);
            
            switch(sortValue) {
                case 'newest':
                    return 0; // Already sorted by date DESC
                case 'oldest':
                    return 0; // Would need to reverse
                case 'price-low':
                    return parseFloat($a.data('price') || 0) - parseFloat($b.data('price') || 0);
                case 'price-high':
                    return parseFloat($b.data('price') || 0) - parseFloat($a.data('price') || 0);
                case 'name-asc':
                    return $a.data('name').localeCompare($b.data('name'));
                case 'name-desc':
                    return $b.data('name').localeCompare($a.data('name'));
                default:
                    return 0;
            }
        });
        
        $grid.empty().append($cards);
    });
});
</script>

<?php
get_footer();
?>
