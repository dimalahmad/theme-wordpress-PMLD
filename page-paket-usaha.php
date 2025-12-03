<?php
/**
 * Template Name: Paket Usaha
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

// Check if viewing dummy detail
$dummy_id = isset($_GET['dummy_id']) ? intval($_GET['dummy_id']) : 0;
$viewing_dummy_detail = false;
$dummy_detail_data = null;

if ($dummy_id > 0) {
    // Load dummy data
    $dummy_pakets = array();
    if (function_exists('inviro_get_dummy_paket_usaha')) {
        $dummy_pakets = inviro_get_dummy_paket_usaha();
    }
    if (empty($dummy_pakets)) {
        $json_file = get_template_directory() . '/dummy-data/paket-usaha.json';
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $dummy_pakets = json_decode($json_content, true);
        }
    }
    
    // Find dummy data by ID
    foreach ($dummy_pakets as $item) {
        if (isset($item['id']) && $item['id'] == $dummy_id) {
            $dummy_detail_data = $item;
            $viewing_dummy_detail = true;
            break;
        }
    }
}

// If viewing dummy detail, redirect to single template logic
if ($viewing_dummy_detail && $dummy_detail_data) {
    // Include single template with dummy data
    $price = isset($dummy_detail_data['price']) ? $dummy_detail_data['price'] : '';
    $original_price = isset($dummy_detail_data['original_price']) ? $dummy_detail_data['original_price'] : null;
    $sku = isset($dummy_detail_data['sku']) ? $dummy_detail_data['sku'] : '';
    $promo = isset($dummy_detail_data['promo']) && $dummy_detail_data['promo'] ? '1' : '0';
    $gallery = isset($dummy_detail_data['gallery']) ? $dummy_detail_data['gallery'] : array();
    $specifications = isset($dummy_detail_data['specifications']) ? $dummy_detail_data['specifications'] : array();
    $category_name = isset($dummy_detail_data['category']) ? $dummy_detail_data['category'] : '';
    $categories = $category_name ? array((object)array('name' => $category_name)) : array();
    $title = isset($dummy_detail_data['title']) ? $dummy_detail_data['title'] : '';
    $description = isset($dummy_detail_data['description']) ? $dummy_detail_data['description'] : '';
    $main_image = isset($dummy_detail_data['image']) ? $dummy_detail_data['image'] : '';
    
    // Get reviews for dummy data
    $reviews = array();
    $avg_rating = 0;
    $review_paket_id = 'dummy_' . $dummy_id;
    
    $reviews_query = new WP_Query(array(
        'post_type' => 'paket_usaha_review',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_review_sparepart_id',
                'value' => $review_paket_id,
                'compare' => '='
            ),
            array(
                'key' => '_review_is_dummy',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => '_review_status',
                'value' => 'approved',
                'compare' => '='
            )
        )
    ));
    
    if ($reviews_query->have_posts()) {
        while ($reviews_query->have_posts()) {
            $reviews_query->the_post();
            $reviews[] = array(
                'id' => get_the_ID(),
                'name' => get_post_meta(get_the_ID(), '_reviewer_name', true),
                'email' => get_post_meta(get_the_ID(), '_reviewer_email', true),
                'rating' => get_post_meta(get_the_ID(), '_review_rating', true),
                'content' => get_the_content(),
                'date' => get_the_date('d F Y')
            );
        }
        wp_reset_postdata();
    }
    
    if (!empty($reviews)) {
        $total_rating = 0;
        foreach ($reviews as $review) {
            $total_rating += intval($review['rating']);
        }
        $avg_rating = round($total_rating / count($reviews), 1);
    }
    
    // Include single template
    include(get_template_directory() . '/single-paket-usaha-dummy.php');
    get_footer();
    exit;
}
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

    <!-- Search & Filter -->
    <section class="paket-usaha-filter">
        <div class="container">
            <div class="filter-bar">
                <input type="text" id="paket-search" placeholder="<?php echo esc_attr(get_theme_mod('inviro_paket_usaha_search_placeholder', 'Cari produk yang Anda butuhkan...')); ?>" />
                <div class="filter-dropdowns">
                    <select id="sort-by">
                        <option value="latest">Terbaru</option>
                        <option value="price-low">Harga: Rendah - Tinggi</option>
                        <option value="price-high">Harga: Tinggi - Rendah</option>
                        <option value="name">Nama A-Z</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Grid - Menampilkan produk dari post type produk -->
    <section class="paket-usaha-grid-section">
        <div class="container">
            <div class="paket-usaha-grid">
                <?php
                $products = new WP_Query(array(
                    'post_type' => 'produk',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($products->have_posts()) :
                    while ($products->have_posts()) : $products->the_post();
                        $post_id = get_the_ID();
                        
                        // Get product data from WordPress
                        $price_raw = get_post_meta($post_id, '_product_price', true);
                        $original_price_raw = get_post_meta($post_id, '_product_original_price', true);
                        $description = get_post_meta($post_id, '_product_description', true);
                        $buy_url = get_post_meta($post_id, '_product_buy_url', true);
                        
                        // Parse price - remove "Rp", spaces, dots, and commas
                        $price = 0;
                        if (!empty($price_raw)) {
                            $price_clean = preg_replace('/[^0-9]/', '', $price_raw);
                            $price = absint($price_clean);
                        }
                        
                        $original_price = 0;
                        if (!empty($original_price_raw)) {
                            $original_price_clean = preg_replace('/[^0-9]/', '', $original_price_raw);
                            $original_price = absint($original_price_clean);
                        }
                        
                        // Set buy URL - fallback to WhatsApp
                        if (empty($buy_url)) {
                            $wa_number = get_theme_mod('inviro_whatsapp', '6281234567890');
                            $buy_url = 'https://wa.me/' . $wa_number;
                        }
                        
                        // Determine promo status - check if original_price > price
                        $is_promo = false;
                        if ($original_price > 0 && $price > 0 && $original_price > $price) {
                            $is_promo = true;
                        }
                        
                        // Get image - use featured image with helper function
                        $product_image_url = '';
                        $thumbnail_id = get_post_thumbnail_id($post_id);
                        
                        if ($thumbnail_id) {
                            // Use helper function if available
                            if (function_exists('inviro_get_product_image_url')) {
                                $product_image_url = inviro_get_product_image_url($post_id, 'medium');
                            }
                            
                            // Fallback methods
                            if (empty($product_image_url)) {
                                $product_image_url = get_the_post_thumbnail_url($post_id, 'medium');
                                if (empty($product_image_url)) {
                                    $product_image_url = get_the_post_thumbnail_url($post_id, 'large');
                                }
                                if (empty($product_image_url)) {
                                    $product_image_url = get_the_post_thumbnail_url($post_id, 'full');
                                }
                            }
                            
                            // Last resort: wp_get_attachment_image_src
                            if (empty($product_image_url)) {
                                $image_data = wp_get_attachment_image_src($thumbnail_id, 'medium');
                                if ($image_data && !empty($image_data[0])) {
                                    $product_image_url = $image_data[0];
                                }
                            }
                            
                            // Normalize URL if needed
                            if ($product_image_url && function_exists('inviro_normalize_image_url')) {
                                $product_image_url = inviro_normalize_image_url($product_image_url);
                            }
                        }
                    ?>
                        <div class="paket-card" data-price="<?php echo esc_attr($price); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>">
                            <div class="paket-image" style="height: 240px; min-height: 240px; max-height: 240px; overflow: hidden;">
                                <?php if (!empty($product_image_url)) : ?>
                                    <img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width: 100%; height: 240px; object-fit: cover; object-position: center; display: block;">
                                <?php else : ?>
                                    <div class="paket-image-placeholder" style="display: flex; align-items: center; justify-content: center; height: 240px; background: #f5f5f5;">
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
                                <h3><?php the_title(); ?></h3>
                                
                                <?php if ($description) : ?>
                                    <p class="paket-desc"><?php echo esc_html(wp_trim_words($description, 15)); ?></p>
                                <?php elseif (get_the_excerpt()) : ?>
                                    <p class="paket-desc"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                                <?php endif; ?>
                                
                                <div class="paket-meta">
                                    <?php if ($price > 0) : ?>
                                        <?php if ($is_promo && $original_price > 0) : ?>
                                            <div class="paket-price-wrapper">
                                                <span class="paket-price-original">Rp <?php echo number_format($original_price, 0, ',', '.'); ?></span>
                                                <span class="paket-price paket-price-promo">Rp <?php echo number_format($price, 0, ',', '.'); ?></span>
                                            </div>
                                        <?php else : ?>
                                            <span class="paket-price">
                                                Rp <?php echo number_format($price, 0, ',', '.'); ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="paket-price">Hubungi Kami</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="paket-actions">
                                    <!-- Arahkan ke halaman detail produk, bukan langsung ke WhatsApp -->
                                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="btn-order">
                                        Beli atau Tanya Lebih Lanjut
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="no-results">
                        <p>Belum ada produk. Silakan tambahkan di <strong>Produk</strong> > <strong>Tambah Produk</strong></p>
                    </div>
                    <?php
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('paket-search');
    const sortSelect = document.getElementById('sort-by');
    const cards = document.querySelectorAll('.paket-card');
    const grid = document.querySelector('.paket-usaha-grid');
    
    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;
        
        cards.forEach((card, index) => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc = card.querySelector('.paket-desc');
            const descText = desc ? desc.textContent.toLowerCase() : '';
            
            const searchMatch = !searchTerm || 
                title.includes(searchTerm) || 
                descText.includes(searchTerm);
            
            if (searchMatch) {
                card.style.display = 'block';
                card.style.opacity = '0';
                card.style.animation = 'fadeInUp 0.4s ease forwards';
                card.style.animationDelay = (visibleCount * 0.05) + 's';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        const noResults = document.querySelector('.no-results');
        if (visibleCount === 0 && searchTerm) {
            if (!noResults) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'no-results';
                noResultsDiv.innerHTML = '<p>Tidak ada produk yang ditemukan' + (searchTerm ? ' untuk "<strong>' + searchTerm + '</strong>"' : '') + '</p>';
                grid.appendChild(noResultsDiv);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const visibleCards = Array.from(cards).filter(card => card.style.display !== 'none');
            
            visibleCards.sort((a, b) => {
                switch(sortValue) {
                    case 'price-low':
                        return parseInt(a.dataset.price || 0) - parseInt(b.dataset.price || 0);
                    case 'price-high':
                        return parseInt(b.dataset.price || 0) - parseInt(a.dataset.price || 0);
                    case 'name':
                        return (a.dataset.name || '').localeCompare(b.dataset.name || '');
                    default:
                        return 0;
                }
            });
            
            visibleCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    grid.appendChild(card);
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 30);
            });
        });
    }
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        setTimeout(() => {
            card.style.opacity = '1';
        }, index * 100);
    });
});
</script>

<?php get_footer(); ?>
