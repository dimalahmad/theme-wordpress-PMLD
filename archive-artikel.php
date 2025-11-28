<?php
/**
 * Archive Template for Artikel
 * This template is used when viewing the artikel archive page
 */

get_header();
?>

<div class="artikel-page">
    <!-- Hero Section -->
    <section class="artikel-hero">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo esc_html(get_theme_mod('inviro_artikel_hero_title', 'Artikel dan Berita Terbaru')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('inviro_artikel_hero_subtitle', 'Update terbaru dari Inviro untuk mendukung usaha dan kebutuhan air minum Anda')); ?></p>
            </div>
        </div>
    </section>

    <!-- Search & Filter -->
    <section class="artikel-filter">
        <div class="container">
            <div class="filter-bar">
                <input type="text" id="artikel-search" placeholder="<?php echo esc_attr(get_theme_mod('inviro_artikel_search_placeholder', 'Cari artikel yang Anda butuhkan...')); ?>" />
                <div class="filter-dropdowns">
                    <select id="filter-category">
                        <option value="">Semua Kategori</option>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'category',
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
                        <option value="oldest">Terlama</option>
                        <option value="name">Judul A-Z</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Artikel Grid -->
    <section class="artikel-grid-section">
        <div class="container">
            <div class="artikel-grid">
                <?php
                // Load dummy data directly
                $dummy_artikel = array();
                if (function_exists('inviro_get_dummy_artikel')) {
                    $dummy_artikel = inviro_get_dummy_artikel();
                }
                // Direct fallback if helper doesn't work
                if (empty($dummy_artikel)) {
                    $json_file = get_template_directory() . '/dummy-data/artikel.json';
                    if (file_exists($json_file)) {
                        $json_content = file_get_contents($json_file);
                        $dummy_artikel = json_decode($json_content, true);
                        if (!is_array($dummy_artikel)) {
                            $dummy_artikel = array();
                        }
                    }
                }
                
                $articles = new WP_Query(array(
                    'post_type' => 'artikel',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                // Check if we have real posts
                $has_real_posts = ($articles->post_count > 0);
                
                if ($has_real_posts) :
                    while ($articles->have_posts()) : $articles->the_post();
                        $author_name = get_the_author_meta('display_name');
                        $post_date = get_the_date('d/m/Y');
                        $categories = get_the_terms(get_the_ID(), 'category');
                        $category_slugs = '';
                        if ($categories && !is_wp_error($categories)) {
                            $category_slugs = implode(' ', array_map(function($cat) { return $cat->slug; }, $categories));
                        }
                ?>
                <div class="artikel-card" data-date="<?php echo esc_attr(get_the_date('Y-m-d')); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>" data-category="<?php echo esc_attr($category_slugs); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="artikel-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="artikel-image">
                            <a href="<?php the_permalink(); ?>">
                                <img src="https://via.placeholder.com/600x400/75C6F1/FFFFFF?text=Artikel" alt="<?php the_title(); ?>" loading="lazy">
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="artikel-content">
                        <div class="artikel-meta-top">
                            <span class="artikel-author">Oleh <?php echo esc_html($author_name); ?></span>
                            <span class="artikel-date"><?php echo esc_html($post_date); ?></span>
                        </div>
                        
                        <h3>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="artikel-excerpt">
                            <?php 
                            if (has_excerpt()) {
                                the_excerpt();
                            } else {
                                echo wp_trim_words(get_the_content(), 20);
                            }
                            ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="artikel-link">
                            Baca Selengkapnya »
                        </a>
                    </div>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                
                // Load dummy data if no real posts
                if (!$has_real_posts && !empty($dummy_artikel)) :
                        foreach ($dummy_artikel as $artikel) :
                            $post_date = isset($artikel['date']) ? date('d/m/Y', strtotime($artikel['date'])) : date('d/m/Y');
                            $author = isset($artikel['author']) ? $artikel['author'] : 'Admin INVIRO';
                            $title = isset($artikel['title']) ? $artikel['title'] : 'Artikel';
                            $excerpt = isset($artikel['excerpt']) ? $artikel['excerpt'] : '';
                            $image = isset($artikel['image']) ? $artikel['image'] : 'https://via.placeholder.com/600x400/75C6F1/FFFFFF?text=Artikel';
                            $category = isset($artikel['category']) ? $artikel['category'] : '';
                            $category_slug = $category ? sanitize_title($category) : '';
                            $date_str = isset($artikel['date']) ? date('Y-m-d', strtotime($artikel['date'])) : date('Y-m-d');
                            ?>
                            <div class="artikel-card" data-date="<?php echo esc_attr($date_str); ?>" data-name="<?php echo esc_attr($title); ?>" data-category="<?php echo esc_attr($category_slug); ?>">
                                <div class="artikel-image">
                                    <a href="#">
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                                    </a>
                                </div>
                                <div class="artikel-content">
                                    <div class="artikel-meta-top">
                                        <span class="artikel-author">Oleh <?php echo esc_html($author); ?></span>
                                        <span class="artikel-date"><?php echo esc_html($post_date); ?></span>
                                    </div>
                                    <h3>
                                        <a href="#">
                                            <?php echo esc_html($title); ?>
                                        </a>
                                    </h3>
                                    <div class="artikel-excerpt">
                                        <?php echo esc_html($excerpt); ?>
                                    </div>
                                    <a href="#" class="artikel-link">
                                        Baca Selengkapnya »
                                    </a>
                                </div>
                            </div>
                            <?php
                    endforeach;
                elseif (!$has_real_posts) :
                    ?>
                    <div class="no-results">
                        <p>Belum ada artikel. Silakan tambahkan di <strong>Artikel</strong> > <strong>Tambah Artikel</strong></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="artikel-cta">
        <div class="container">
            <h2><?php echo esc_html(get_theme_mod('inviro_artikel_cta_title', 'Butuh Konsultasi Spesialis?')); ?></h2>
            <p><?php echo esc_html(get_theme_mod('inviro_artikel_cta_subtitle', 'Tim ahli kami siap membantu Anda menemukan solusi terbaik untuk kebutuhan air minum Anda. Hubungi kami sekarang!')); ?></p>
            <a href="https://wa.me/<?php echo esc_attr(get_theme_mod('inviro_artikel_cta_whatsapp', '6281234567890')); ?>?text=<?php echo urlencode('Halo, saya tertarik dengan layanan Inviro'); ?>" 
               class="btn-whatsapp" 
               target="_blank" 
               rel="noopener noreferrer">
                <?php echo esc_html(get_theme_mod('inviro_artikel_cta_button', 'Hubungi via WhatsApp')); ?>
            </a>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('artikel-search');
    const categorySelect = document.getElementById('filter-category');
    const sortSelect = document.getElementById('sort-by');
    const cards = document.querySelectorAll('.artikel-card');
    const grid = document.querySelector('.artikel-grid');
    
    // Function to filter cards
    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedCategory = categorySelect ? categorySelect.value : '';
        let visibleCount = 0;
        
        cards.forEach((card, index) => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const excerptEl = card.querySelector('.artikel-excerpt');
            const excerpt = excerptEl ? excerptEl.textContent.toLowerCase() : '';
            const cardCategory = card.dataset.category || '';
            const cardCategories = cardCategory.split(' ').filter(Boolean);
            
            // Search match
            const searchMatch = !searchTerm || 
                title.includes(searchTerm) || 
                excerpt.includes(searchTerm);
            
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
                noResultsDiv.innerHTML = '<p>Tidak ada artikel yang ditemukan' + (searchTerm ? ' untuk "<strong>' + searchTerm + '</strong>"' : '') + (selectedCategory ? ' dalam kategori yang dipilih' : '') + '</p>';
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
                    case 'oldest':
                        return new Date(a.dataset.date || 0) - new Date(b.dataset.date || 0);
                    case 'latest':
                        return new Date(b.dataset.date || 0) - new Date(a.dataset.date || 0);
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
