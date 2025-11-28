<?php
/**
 * Template Name: Spare Parts
 */

// Ensure this template is used for spareparts page
// Fix query if it's empty or 404
global $wp_query;
if (empty($wp_query->posts) || $wp_query->is_404) {
    // Try to find spareparts page
    $spareparts_page = get_page_by_path('spareparts');
    if (!$spareparts_page) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-spareparts.php',
            'number' => 1
        ));
        if (!empty($pages)) {
            $spareparts_page = $pages[0];
        }
    }
    if ($spareparts_page) {
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->is_404 = false;
        $wp_query->is_archive = false;
        $wp_query->is_post_type_archive = false;
        $wp_query->queried_object = $spareparts_page;
        $wp_query->queried_object_id = $spareparts_page->ID;
        $wp_query->posts = array($spareparts_page);
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
    $dummy_spareparts = array();
    if (function_exists('inviro_get_dummy_spareparts')) {
        $dummy_spareparts = inviro_get_dummy_spareparts();
    }
    if (empty($dummy_spareparts)) {
        $json_file = get_template_directory() . '/dummy-data/spareparts.json';
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $dummy_spareparts = json_decode($json_content, true);
        }
    }
    
    // Find dummy data by ID
    foreach ($dummy_spareparts as $item) {
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
    $stock = isset($dummy_detail_data['stock']) ? $dummy_detail_data['stock'] : 0;
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
    $review_sparepart_id = 'dummy_' . $dummy_id;
    
    $reviews_query = new WP_Query(array(
        'post_type' => 'sparepart_review',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_review_sparepart_id',
                'value' => $review_sparepart_id,
                'compare' => '='
            ),
            array(
                'key' => '_review_is_dummy',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => '_review_product_type',
                'value' => 'spareparts',
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
    include(get_template_directory() . '/single-spareparts-dummy.php');
    get_footer();
    exit;
}
?>

<div class="spareparts-page">
    <!-- Hero Section -->
    <section class="spareparts-hero">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo esc_html(get_theme_mod('inviro_spareparts_hero_title', 'Spare Parts Premium')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('inviro_spareparts_hero_subtitle', 'Solusi lengkap spare parts berkualitas tinggi untuk mesin pengolahan air Anda. Dapatkan performa optimal dengan komponen asli dan terpercaya.')); ?></p>
            </div>
        </div>
    </section>

    <!-- Search & Filter -->
    <section class="spareparts-filter">
        <div class="container">
            <div class="filter-bar">
                <input type="text" id="sparepart-search" placeholder="<?php echo esc_attr(get_theme_mod('inviro_spareparts_search_placeholder', 'Cari spare part yang Anda butuhkan...')); ?>" />
                <div class="filter-dropdowns">
                    <select id="filter-category">
                        <option value="">Semua Kategori</option>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'sparepart_category',
                            'hide_empty' => false,
                        ));
                        if (!is_wp_error($categories) && !empty($categories)) {
                            foreach ($categories as $category) {
                                echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
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

    <!-- Spare Parts Grid -->
    <section class="spareparts-grid-section">
        <div class="container">
            <div class="spareparts-grid">
                <?php
                $spareparts = new WP_Query(array(
                    'post_type' => 'spareparts',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                // Check if we have real posts
                $has_real_posts = ($spareparts->post_count > 0);
                
                if ($has_real_posts) :
                while ($spareparts->have_posts()) : $spareparts->the_post();
                    $price = get_post_meta(get_the_ID(), '_sparepart_price', true);
                    $original_price = get_post_meta(get_the_ID(), '_sparepart_original_price', true);
                    $stock = get_post_meta(get_the_ID(), '_sparepart_stock', true);
                    $sku = get_post_meta(get_the_ID(), '_sparepart_sku', true);
                    $promo = get_post_meta(get_the_ID(), '_sparepart_promo', true);
                    $categories = get_the_terms(get_the_ID(), 'sparepart_category');
                    $category_slugs = '';
                    if ($categories && !is_wp_error($categories)) {
                        $category_slugs = implode(' ', array_map(function($cat) { return $cat->slug; }, $categories));
                    }
                ?>
                <div class="sparepart-card" data-price="<?php echo esc_attr($price); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>" data-category="<?php echo esc_attr($category_slugs); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="sparepart-image">
                            <?php the_post_thumbnail('medium'); ?>
                            <?php if ($promo == '1') : ?>
                                <span class="stock-badge promo-badge">Promo</span>
                            <?php elseif ($stock && $stock > 0) : ?>
                                <span class="stock-badge in-stock">Tersedia</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="sparepart-content">
                        <?php if ($sku) : ?>
                            <span class="sparepart-sku"><?php echo esc_html($sku); ?></span>
                        <?php endif; ?>
                        
                        <h3><?php the_title(); ?></h3>
                        
                        <?php if (has_excerpt()) : ?>
                            <p class="sparepart-desc"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                        <?php endif; ?>
                        
                        <div class="sparepart-meta">
                            <?php if ($price) : ?>
                                <?php if ($promo == '1' && $original_price && $original_price > $price) : ?>
                                    <div class="sparepart-price-wrapper">
                                        <span class="sparepart-price-original">Rp <?php echo number_format($original_price, 0, ',', '.'); ?></span>
                                        <span class="sparepart-price sparepart-price-promo">Rp <?php echo number_format($price, 0, ',', '.'); ?></span>
                                    </div>
                                <?php else : ?>
                                    <span class="sparepart-price">
                                        Rp <?php echo number_format($price, 0, ',', '.'); ?>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="sparepart-actions">
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
                
                // Load dummy data if no real posts
                if (!$has_real_posts) :
                    $dummy_spareparts = array();
                    if (function_exists('inviro_get_dummy_spareparts')) {
                        $dummy_spareparts = inviro_get_dummy_spareparts();
                    }
                    // Direct fallback if helper doesn't work
                    if (empty($dummy_spareparts)) {
                        $json_file = get_template_directory() . '/dummy-data/spareparts.json';
                        if (file_exists($json_file)) {
                            $json_content = file_get_contents($json_file);
                            $dummy_spareparts = json_decode($json_content, true);
                        }
                    }
                    if (!empty($dummy_spareparts)) :
                        foreach ($dummy_spareparts as $sparepart) :
                            $dummy_category = isset($sparepart['category']) ? $sparepart['category'] : '';
                            $dummy_category_slug = $dummy_category ? sanitize_title($dummy_category) : '';
                            ?>
                            <div class="sparepart-card" data-price="<?php echo esc_attr($sparepart['price']); ?>" data-name="<?php echo esc_attr($sparepart['title']); ?>" data-category="<?php echo esc_attr($dummy_category_slug); ?>">
                                <div class="sparepart-image">
                                    <img src="<?php echo esc_url($sparepart['image']); ?>" alt="<?php echo esc_attr($sparepart['title']); ?>" loading="lazy">
                                    <?php 
                                    $dummy_promo = !empty($sparepart['promo']) ? $sparepart['promo'] : false;
                                    if ($dummy_promo) : ?>
                                        <span class="stock-badge promo-badge">Promo</span>
                                    <?php elseif (!empty($sparepart['stock']) && $sparepart['stock'] > 0) : ?>
                                        <span class="stock-badge in-stock">Tersedia</span>
                                    <?php endif; ?>
                                </div>
                                <div class="sparepart-content">
                                    <?php if (!empty($sparepart['sku'])) : ?>
                                        <span class="sparepart-sku"><?php echo esc_html($sparepart['sku']); ?></span>
                                    <?php endif; ?>
                                    <h3><?php echo esc_html($sparepart['title']); ?></h3>
                                    <?php if (!empty($sparepart['description'])) : ?>
                                        <p class="sparepart-desc"><?php echo esc_html(wp_trim_words($sparepart['description'], 15)); ?></p>
                                    <?php endif; ?>
                                    <div class="sparepart-meta">
                                        <?php 
                                        $dummy_price = $sparepart['price'];
                                        $dummy_original_price = isset($sparepart['original_price']) ? $sparepart['original_price'] : null;
                                        $dummy_promo = !empty($sparepart['promo']) ? $sparepart['promo'] : false;
                                        if ($dummy_promo && $dummy_original_price && $dummy_original_price > $dummy_price) : ?>
                                            <div class="sparepart-price-wrapper">
                                                <span class="sparepart-price-original">Rp <?php echo number_format($dummy_original_price, 0, ',', '.'); ?></span>
                                                <span class="sparepart-price sparepart-price-promo">Rp <?php echo number_format($dummy_price, 0, ',', '.'); ?></span>
                                            </div>
                                        <?php else : ?>
                                            <span class="sparepart-price">
                                                Rp <?php echo number_format($dummy_price, 0, ',', '.'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="sparepart-actions">
                                        <a href="<?php echo esc_url(home_url('/spareparts/?dummy_id=' . $sparepart['id'])); ?>" 
                                           class="btn-order">
                                            Pesan
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    else :
                        ?>
                        <div class="no-results">
                            <p>Belum ada spare part. Silakan tambahkan di <strong>Spare Parts</strong> > <strong>Tambah Spare Part</strong></p>
                        </div>
                        <?php
                    endif;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="spareparts-cta">
        <div class="container">
            <div class="cta-content">
                <h2><?php echo esc_html(get_theme_mod('inviro_spareparts_cta_title', 'Butuh Konsultasi Spesialis?')); ?></h2>
                <p><?php echo esc_html(get_theme_mod('inviro_spareparts_cta_subtitle', 'Tim ahli kami siap membantu Anda menemukan spare part yang tepat untuk kebutuhan mesin pengolahan air Anda')); ?></p>
                <?php $wa_number_cta = get_theme_mod('inviro_whatsapp', '6281234567890'); ?>
                <a href="https://wa.me/<?php echo esc_attr($wa_number_cta); ?>" class="btn-whatsapp" target="_blank">
                    <?php echo esc_html(get_theme_mod('inviro_spareparts_cta_button', 'Chat WhatsApp')); ?>
                </a>
            </div>
        </div>
    </section>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sparepart-search');
    const categorySelect = document.getElementById('filter-category');
    const sortSelect = document.getElementById('sort-by');
    const cards = document.querySelectorAll('.sparepart-card');
    const grid = document.querySelector('.spareparts-grid');
    
    // Function to filter cards
    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedCategory = categorySelect ? categorySelect.value : '';
        let visibleCount = 0;
        
        cards.forEach((card, index) => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc = card.querySelector('.sparepart-desc');
            const descText = desc ? desc.textContent.toLowerCase() : '';
            const sku = card.querySelector('.sparepart-sku')?.textContent.toLowerCase() || '';
            const cardCategory = card.dataset.category || '';
            const cardCategories = cardCategory.split(' ').filter(Boolean);
            
            // Search match
            const searchMatch = !searchTerm || 
                title.includes(searchTerm) || 
                descText.includes(searchTerm) || 
                sku.includes(searchTerm);
            
            // Category match
            const categoryMatch = !selectedCategory || cardCategories.includes(selectedCategory);
            
            const matches = searchMatch && categoryMatch;
            
            if (matches) {
                card.style.display = 'block';
                card.style.opacity = '0';
                card.style.animation = 'fadeInUp 0.4s ease forwards';
                card.style.animationDelay = (visibleCount * 0.05) + 's';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show no results message if needed
        const noResults = document.querySelector('.no-results');
        if (visibleCount === 0 && (searchTerm || selectedCategory)) {
            if (!noResults) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'no-results';
                noResultsDiv.innerHTML = '<p>Tidak ada spare part yang ditemukan' + (searchTerm ? ' untuk "<strong>' + searchTerm + '</strong>"' : '') + (selectedCategory ? ' dalam kategori yang dipilih' : '') + '</p>';
                grid.appendChild(noResultsDiv);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }
    
    // Search functionality with smooth animation
    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }
    
    // Category filter functionality
    if (categorySelect) {
        categorySelect.addEventListener('change', filterCards);
    }
    
    // Sort functionality with smooth animation
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
                    default: // latest
                        return 0;
                }
            });
            
            // Reorder with animation
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
    
    // Add loading animation on page load
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        setTimeout(() => {
            card.style.opacity = '1';
        }, index * 100);
    });
});
</script>

<?php get_footer(); ?>
