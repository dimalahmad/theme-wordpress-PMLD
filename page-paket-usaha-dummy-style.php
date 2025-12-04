<?php
/**
 * Template Name: Paket Usaha (Dummy Style)
 * Menggunakan struktur tampilan dummy tapi mengambil data dari WordPress
 */

// Ensure this template is used for paket-usaha page
global $wp_query;
if (empty($wp_query->posts) || $wp_query->is_404) {
    $paket_usaha_page = get_page_by_path('paket-usaha');
    if (!$paket_usaha_page) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-paket-usaha-dummy-style.php',
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

    <!-- Search & Filter -->
    <section class="paket-usaha-filter">
        <div class="container">
            <div class="filter-bar">
                <input type="text" id="paket-search" placeholder="<?php echo esc_attr(get_theme_mod('inviro_paket_usaha_search_placeholder', 'Cari paket usaha yang Anda butuhkan...')); ?>" />
                <div class="filter-dropdowns">
                    <select id="filter-category">
                        <option value="">Semua Kategori</option>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'paket_usaha_category',
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

    <!-- Paket Usaha Grid - Menggunakan struktur dummy tapi data dari WordPress -->
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
                        
                        // Get all data from WordPress - SAMA PERSIS DENGAN STRUKTUR DUMMY
                        $price_raw = get_post_meta($post_id, '_paket_price', true);
                        $original_price_raw = get_post_meta($post_id, '_paket_original_price', true);
                        $sku = get_post_meta($post_id, '_paket_sku', true);
                        $promo = get_post_meta($post_id, '_paket_promo', true);
                        $description = get_post_meta($post_id, '_paket_description', true);
                        $categories = get_the_terms($post_id, 'paket_usaha_category');
                        $category_slugs = '';
                        if ($categories && !is_wp_error($categories)) {
                            $category_slugs = implode(' ', array_map(function($cat) { return $cat->slug; }, $categories));
                        }
                        
                        // Bersihkan harga dari format "Rp", titik, koma, dan spasi
                        $clean_price = function($value) {
                            if (empty($value)) return 0;
                            if (is_numeric($value)) {
                                return absint($value);
                            }
                            $cleaned = preg_replace('/[^0-9]/', '', $value);
                            return !empty($cleaned) ? absint($cleaned) : 0;
                        };
                        
                        $price = $clean_price($price_raw);
                        $original_price = $clean_price($original_price_raw);
                        
                        // Jika harga promo tidak ada atau 0, gunakan harga asli
                        // Harga harus selalu tampil (minimal harga asli)
                        $price_display = ($price > 0) ? $price : $original_price;
                        
                        // Tentukan status promo
                        $is_promo = false;
                        if ($promo == '1' || $promo === 1 || $promo === '1') {
                            $is_promo = true;
                        } elseif ($price > 0 && $original_price > 0 && $price < $original_price) {
                            $is_promo = true;
                        }
                        
                        // Get image - Priority: Featured Image > Gallery
                        $paket_image_url = '';
                        if (has_post_thumbnail($post_id)) {
                            $paket_image_url = get_the_post_thumbnail_url($post_id, 'medium');
                            if (empty($paket_image_url)) {
                                $paket_image_url = get_the_post_thumbnail_url($post_id, 'large');
                            }
                        }
                        
                        if (empty($paket_image_url)) {
                            $gallery_ids_raw = get_post_meta($post_id, '_paket_gallery', true);
                            if (!empty($gallery_ids_raw)) {
                                if (is_string($gallery_ids_raw)) {
                                    $gallery_ids = array_map('absint', explode(',', trim($gallery_ids_raw)));
                                } elseif (is_array($gallery_ids_raw)) {
                                    $gallery_ids = array_map('absint', $gallery_ids_raw);
                                } else {
                                    $gallery_ids = array();
                                }
                                
                                $gallery_ids = array_filter($gallery_ids, function($id) {
                                    return $id > 0;
                                });
                                
                                if (!empty($gallery_ids)) {
                                    $first_id = $gallery_ids[0];
                                    if (wp_attachment_is_image($first_id)) {
                                        $paket_image_url = wp_get_attachment_image_url($first_id, 'medium');
                                        if (empty($paket_image_url)) {
                                            $paket_image_url = wp_get_attachment_image_url($first_id, 'large');
                                        }
                                        if (empty($paket_image_url)) {
                                            $paket_image_url = wp_get_attachment_url($first_id);
                                        }
                                    }
                                }
                            }
                        }
                    ?>
                        <div class="paket-card" data-price="<?php echo esc_attr($price_display); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>" data-category="<?php echo esc_attr($category_slugs); ?>">
                            <div class="paket-image" style="height: 240px; min-height: 240px; max-height: 240px; overflow: hidden;">
                                <?php if (!empty($paket_image_url)) : ?>
                                    <img src="<?php echo esc_url($paket_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width: 100%; height: 240px; object-fit: cover; object-position: center; display: block;">
                                <?php else : ?>
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
                                
                                <?php if ($description) : ?>
                                    <p class="paket-desc"><?php echo esc_html(wp_trim_words($description, 15)); ?></p>
                                <?php elseif (get_the_excerpt()) : ?>
                                    <p class="paket-desc"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                                <?php endif; ?>
                                
                                <div class="paket-meta">
                                    <?php if ($original_price > 0) : ?>
                                        <?php if ($is_promo && $price > 0) : ?>
                                            <div class="paket-price-wrapper">
                                                <span class="paket-price-original">Rp <?php echo number_format($original_price, 0, ',', '.'); ?></span>
                                                <span class="paket-price paket-price-promo">Rp <?php echo number_format($price, 0, ',', '.'); ?></span>
                                            </div>
                                        <?php else : ?>
                                            <span class="paket-price">
                                                Rp <?php echo number_format($original_price, 0, ',', '.'); ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="paket-price">Hubungi Kami</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="paket-actions">
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="btn-order">
                                        Pesan
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
                        <p>Belum ada paket usaha. Silakan tambahkan di <strong>Paket Usaha</strong> > <strong>Tambah Paket</strong></p>
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
    const categorySelect = document.getElementById('filter-category');
    const sortSelect = document.getElementById('sort-by');
    const cards = document.querySelectorAll('.paket-card');
    const grid = document.querySelector('.paket-usaha-grid');
    
    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedCategory = categorySelect ? categorySelect.value : '';
        let visibleCount = 0;
        
        cards.forEach((card, index) => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc = card.querySelector('.paket-desc');
            const descText = desc ? desc.textContent.toLowerCase() : '';
            const sku = card.querySelector('.paket-sku')?.textContent.toLowerCase() || '';
            const cardCategory = card.dataset.category || '';
            const cardCategories = cardCategory.split(' ').filter(Boolean);
            
            const searchMatch = !searchTerm || 
                title.includes(searchTerm) || 
                descText.includes(searchTerm) || 
                sku.includes(searchTerm);
            
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
        
        const noResults = document.querySelector('.no-results');
        if (visibleCount === 0 && (searchTerm || selectedCategory)) {
            if (!noResults) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'no-results';
                noResultsDiv.innerHTML = '<p>Tidak ada paket usaha yang ditemukan' + (searchTerm ? ' untuk "<strong>' + searchTerm + '</strong>"' : '') + (selectedCategory ? ' dalam kategori yang dipilih' : '') + '</p>';
                grid.appendChild(noResultsDiv);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }
    
    if (categorySelect) {
        categorySelect.addEventListener('change', filterCards);
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


