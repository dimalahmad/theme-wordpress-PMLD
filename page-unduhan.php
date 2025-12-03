<?php
/**
 * Template Name: Unduhan
 */

// Ensure this template is used for unduhan page
global $wp_query;
if (empty($wp_query->posts) || $wp_query->is_404) {
    $unduhan_page = get_page_by_path('unduhan');
    if (!$unduhan_page) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-unduhan.php',
            'number' => 1
        ));
        if (!empty($pages)) {
            $unduhan_page = $pages[0];
        }
    }
    if ($unduhan_page) {
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->is_404 = false;
        $wp_query->is_archive = false;
        $wp_query->is_post_type_archive = false;
        $wp_query->queried_object = $unduhan_page;
        $wp_query->queried_object_id = $unduhan_page->ID;
        $wp_query->posts = array($unduhan_page);
        $wp_query->post_count = 1;
        $wp_query->found_posts = 1;
        $wp_query->max_num_pages = 1;
    }
}

get_header();
?>

<div class="unduhan-page">
    <!-- Hero Section -->
    <section class="unduhan-hero">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo esc_html(get_theme_mod('inviro_unduhan_hero_title', 'Pusat Unduhan')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('inviro_unduhan_hero_subtitle', 'Download katalog produk, brosur, dan dokumen pendukung lainnya untuk kebutuhan bisnis depot air minum Anda')); ?></p>
            </div>
        </div>
    </section>

    <!-- Search & Filter -->
    <section class="unduhan-filter">
        <div class="container">
            <div class="filter-bar">
                <input type="text" id="unduhan-search" placeholder="<?php echo esc_attr(get_theme_mod('inviro_unduhan_search_placeholder', 'Cari file yang Anda butuhkan...')); ?>" />
                <div class="filter-dropdowns">
                    <select id="sort-by">
                        <option value="latest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="name">Nama A-Z</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Unduhan Grid - Menampilkan file dari post type unduhan -->
    <section class="unduhan-grid-section">
        <div class="container">
            <div class="unduhan-grid">
                <?php
                $downloads = new WP_Query(array(
                    'post_type' => 'unduhan',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($downloads->have_posts()) :
                    while ($downloads->have_posts()) : $downloads->the_post();
                        $post_id = get_the_ID();
                        
                        // Get unduhan data
                        $file_url = get_post_meta($post_id, '_unduhan_file_url', true);
                        $download_count = get_post_meta($post_id, '_unduhan_download_count', true);
                        
                        if (empty($download_count)) {
                            $download_count = 0;
                        }
                        
                        // Generate download URL with nonce for tracking
                        $download_nonce = wp_create_nonce('download_unduhan_' . $post_id);
                        $download_url = add_query_arg(array(
                            'download_unduhan' => '1',
                            'post_id' => $post_id,
                            'nonce' => $download_nonce
                        ), home_url('/'));
                        
                        // Get image - use featured image
                        $unduhan_image_url = '';
                        $thumbnail_id = get_post_thumbnail_id($post_id);
                        
                        if ($thumbnail_id) {
                            $unduhan_image_url = get_the_post_thumbnail_url($post_id, 'medium');
                            if (empty($unduhan_image_url)) {
                                $unduhan_image_url = get_the_post_thumbnail_url($post_id, 'large');
                            }
                            if (empty($unduhan_image_url)) {
                                $unduhan_image_url = get_the_post_thumbnail_url($post_id, 'full');
                            }
                            
                            // Last resort: wp_get_attachment_image_src
                            if (empty($unduhan_image_url)) {
                                $image_data = wp_get_attachment_image_src($thumbnail_id, 'medium');
                                if ($image_data && !empty($image_data[0])) {
                                    $unduhan_image_url = $image_data[0];
                                }
                            }
                        }
                    ?>
                        <div class="unduhan-card" data-name="<?php echo esc_attr(get_the_title()); ?>" data-date="<?php echo esc_attr(get_the_date('Y-m-d')); ?>" data-file-url="<?php echo esc_attr($file_url); ?>">
                            <div class="unduhan-image" style="height: 240px; min-height: 240px; max-height: 240px; overflow: hidden;">
                                <?php if (!empty($unduhan_image_url)) : ?>
                                    <img src="<?php echo esc_url($unduhan_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width: 100%; height: 240px; object-fit: cover; object-position: center; display: block;">
                                <?php else : ?>
                                    <div class="unduhan-image-placeholder" style="display: flex; align-items: center; justify-content: center; height: 240px; background: #f5f5f5;">
                                        <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="unduhan-content">
                                <h3><?php the_title(); ?></h3>
                                
                                <?php if (get_the_excerpt()) : ?>
                                    <p class="unduhan-desc"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                                <?php endif; ?>
                                
                                <div class="unduhan-meta">
                                    <div class="unduhan-file-info">
                                        <span class="unduhan-download-count"><?php echo esc_html($download_count); ?> download</span>
                                    </div>
                                </div>
                                
                                <div class="unduhan-actions">
                                    <?php if ($file_url) : ?>
                                        <a href="<?php echo esc_url($download_url); ?>" class="btn-order unduhan-download-btn" data-post-id="<?php echo esc_attr($post_id); ?>" data-file-url="<?php echo esc_attr($file_url); ?>">
                                            Unduh File
                                        </a>
                                    <?php else : ?>
                                        <a href="#" class="btn-order" onclick="return false;" style="opacity: 0.5; cursor: not-allowed;">
                                            File Tidak Tersedia
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="no-results">
                        <p>Belum ada file. Silakan tambahkan di <strong>Unduhan</strong> > <strong>Tambah Unduhan</strong></p>
                    </div>
                    <?php
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="unduhan-cta">
        <div class="container">
            <div class="cta-content">
                <h2><?php echo esc_html(get_theme_mod('inviro_unduhan_cta_title', 'Butuh Konsultasi Spesialis?')); ?></h2>
                <p><?php echo esc_html(get_theme_mod('inviro_unduhan_cta_subtitle', 'Tim ahli kami siap membantu Anda menemukan solusi terbaik untuk kebutuhan air minum Anda')); ?></p>
                <?php $wa_number_cta = get_theme_mod('inviro_whatsapp', '6281234567890'); ?>
                <a href="https://wa.me/<?php echo esc_attr($wa_number_cta); ?>" class="btn-whatsapp" target="_blank">
                    <?php echo esc_html(get_theme_mod('inviro_unduhan_cta_button', 'Chat WhatsApp')); ?>
                </a>
            </div>
        </div>
    </section>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('unduhan-search');
    const sortSelect = document.getElementById('sort-by');
    const cards = document.querySelectorAll('.unduhan-card');
    const grid = document.querySelector('.unduhan-grid');
    
    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;
        
        cards.forEach((card, index) => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc = card.querySelector('.unduhan-desc');
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
                noResultsDiv.innerHTML = '<p>Tidak ada file yang ditemukan' + (searchTerm ? ' untuk "<strong>' + searchTerm + '</strong>"' : '') + '</p>';
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
                    case 'oldest':
                        return new Date(a.dataset.date || 0) - new Date(b.dataset.date || 0);
                    case 'latest':
                        return new Date(b.dataset.date || 0) - new Date(a.dataset.date || 0);
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
