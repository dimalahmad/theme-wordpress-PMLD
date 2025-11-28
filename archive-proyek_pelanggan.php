<?php
/**
 * Archive Template for Proyek Pelanggan
 * This template is used when viewing the proyek_pelanggan archive page
 */

get_header();
?>

<div class="pelanggan-page">
    <!-- Hero Section -->
    <section class="pelanggan-hero">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo esc_html(get_theme_mod('inviro_pelanggan_hero_title', 'Pengguna Produk INVIRO di Indonesia')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('inviro_pelanggan_hero_subtitle', 'Dipercaya oleh ratusan perusahaan terkemuka di Indonesia')); ?></p>
            </div>
        </div>
    </section>

    <!-- Filter & Content Layout -->
    <div class="pelanggan-content-wrapper">
        <!-- Main Content Area -->
        <div class="pelanggan-main-content">
            <!-- Search & Sort Bar with Region Slidebar -->
            <section class="pelanggan-filter">
                <div class="filter-bar">
                    <div class="region-sidebar-inline">
                        <div class="region-list">
                            <button class="region-item active" data-region="" data-filter="all">
                                <span class="region-name">Semua Wilayah</span>
                                <span class="region-count" data-count="all">0</span>
                            </button>
                            <?php
                            // Get region clusters from customizer
                            $region_clusters_json = get_theme_mod('inviro_pelanggan_region_clusters', '');
                            $region_clusters = array();
                            
                            if (!empty($region_clusters_json)) {
                                $decoded = json_decode($region_clusters_json, true);
                                if (is_array($decoded)) {
                                    $region_clusters = $decoded;
                                    // Sort by order
                                    usort($region_clusters, function($a, $b) {
                                        return ($a['order'] ?? 999) - ($b['order'] ?? 999);
                                    });
                                }
                            }
                            
                            // Fallback to taxonomy if no customizer data
                            if (empty($region_clusters)) {
                                $taxonomy_regions = get_terms(array(
                                    'taxonomy' => 'region',
                                    'hide_empty' => false,
                                ));
                                if (!is_wp_error($taxonomy_regions) && !empty($taxonomy_regions)) {
                                    foreach ($taxonomy_regions as $idx => $region) {
                                        $region_clusters[] = array(
                                            'id' => $region->slug,
                                            'name' => $region->name,
                                            'slug' => $region->slug,
                                            'order' => $idx + 1
                                        );
                                    }
                                }
                            }
                            
                            foreach ($region_clusters as $cluster) {
                                $region_id = isset($cluster['id']) ? $cluster['id'] : '';
                                $region_name = isset($cluster['name']) ? $cluster['name'] : '';
                                $region_slug = isset($cluster['slug']) ? $cluster['slug'] : $region_id;
                                ?>
                                <button class="region-item" data-region="<?php echo esc_attr($region_slug); ?>" data-filter="<?php echo esc_attr($region_slug); ?>">
                                    <span class="region-name"><?php echo esc_html($region_name); ?></span>
                                    <span class="region-count" data-count="<?php echo esc_attr($region_slug); ?>">0</span>
                                </button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <input type="text" id="pelanggan-search" placeholder="<?php echo esc_attr(get_theme_mod('inviro_pelanggan_search_placeholder', 'Cari proyek pelanggan...')); ?>" />
                    <select id="sort-by">
                        <option value="latest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="name">Judul A-Z</option>
                    </select>
                </div>
            </section>

            <!-- Customers Grid -->
            <section class="pelanggan-grid-section">
                <div class="pelanggan-grid">
                <?php
                // Load dummy data directly
                $dummy_pelanggan = array();
                if (function_exists('inviro_get_dummy_pelanggan')) {
                    $dummy_pelanggan = inviro_get_dummy_pelanggan();
                }
                // Direct fallback if helper doesn't work
                if (empty($dummy_pelanggan)) {
                    $json_file = get_template_directory() . '/dummy-data/pelanggan.json';
                    if (file_exists($json_file)) {
                        $json_content = file_get_contents($json_file);
                        $dummy_pelanggan = json_decode($json_content, true);
                        if (!is_array($dummy_pelanggan)) {
                            $dummy_pelanggan = array();
                        }
                    }
                }
                
                $projects = new WP_Query(array(
                    'post_type' => 'proyek_pelanggan',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                // Check if we have real posts
                $has_real_posts = ($projects->post_count > 0);
                
                if ($has_real_posts) :
                    while ($projects->have_posts()) : $projects->the_post();
                        $client_name = get_post_meta(get_the_ID(), '_proyek_client_name', true);
                        $proyek_date = get_post_meta(get_the_ID(), '_proyek_date', true);
                        $excerpt = get_post_meta(get_the_ID(), '_proyek_excerpt', true);
                        
                        // Get region taxonomy
                        $regions = get_the_terms(get_the_ID(), 'region');
                        $region_slug = ($regions && !is_wp_error($regions)) ? $regions[0]->slug : '';
                        $region_slugs = '';
                        if ($regions && !is_wp_error($regions)) {
                            $region_slugs = implode(' ', array_map(function($reg) { return $reg->slug; }, $regions));
                        }
                        
                        // Format date
                        $formatted_date = '';
                        $date_str = '';
                        if ($proyek_date) {
                            $formatted_date = date('d/m/Y', strtotime($proyek_date));
                            $date_str = date('Y-m-d', strtotime($proyek_date));
                        } else {
                            $formatted_date = get_the_date('d/m/Y');
                            $date_str = get_the_date('Y-m-d');
                        }
                ?>
                <div class="pelanggan-card" data-date="<?php echo esc_attr($date_str); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>" data-region="<?php echo esc_attr($region_slugs); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="pelanggan-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="pelanggan-image">
                            <a href="<?php the_permalink(); ?>">
                                <img src="https://via.placeholder.com/600x400/75C6F1/FFFFFF?text=Proyek" alt="<?php the_title(); ?>" loading="lazy">
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="pelanggan-content">
                        <div class="pelanggan-meta-top">
                            <span class="pelanggan-author">Oleh <?php echo esc_html($client_name ? $client_name : 'Admin INVIRO'); ?></span>
                            <span class="pelanggan-date"><?php echo esc_html($formatted_date); ?></span>
                        </div>
                        
                        <h3>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="pelanggan-excerpt">
                            <?php 
                            // Use custom excerpt if available, otherwise fallback to the_excerpt
                            if (!empty($excerpt)) {
                                echo esc_html($excerpt);
                            } else {
                                echo wp_trim_words(get_the_content(), 20);
                            }
                            ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="pelanggan-link">
                            Baca Selengkapnya »
                        </a>
                    </div>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                
                // Load dummy data if no real posts
                if (!$has_real_posts && !empty($dummy_pelanggan)) :
                    foreach ($dummy_pelanggan as $proyek) :
                        $formatted_date = isset($proyek['date']) ? date('d/m/Y', strtotime($proyek['date'])) : date('d/m/Y');
                        $date_str = isset($proyek['date']) ? date('Y-m-d', strtotime($proyek['date'])) : date('Y-m-d');
                        $region = isset($proyek['region']) ? $proyek['region'] : '';
                        ?>
                        <div class="pelanggan-card" data-date="<?php echo esc_attr($date_str); ?>" data-name="<?php echo esc_attr($proyek['title']); ?>" data-region="<?php echo esc_attr($region); ?>">
                            <div class="pelanggan-image">
                                <a href="<?php echo esc_url(home_url('/pelanggan/?dummy_id=' . $proyek['id'])); ?>">
                                    <img src="<?php echo esc_url($proyek['image']); ?>" alt="<?php echo esc_attr($proyek['title']); ?>" loading="lazy">
                                </a>
                            </div>
                            <div class="pelanggan-content">
                                <div class="pelanggan-meta-top">
                                    <span class="pelanggan-author">Oleh <?php echo esc_html(!empty($proyek['client_name']) ? $proyek['client_name'] : 'Admin INVIRO'); ?></span>
                                    <span class="pelanggan-date"><?php echo esc_html($formatted_date); ?></span>
                                </div>
                                <h3>
                                    <a href="<?php echo esc_url(home_url('/pelanggan/?dummy_id=' . $proyek['id'])); ?>">
                                        <?php echo esc_html($proyek['title']); ?>
                                    </a>
                                </h3>
                                <div class="pelanggan-excerpt">
                                    <?php echo esc_html($proyek['excerpt']); ?>
                                </div>
                                
                                <a href="<?php echo esc_url(home_url('/pelanggan/?dummy_id=' . $proyek['id'])); ?>" class="pelanggan-link">
                                    Baca Selengkapnya »
                                </a>
                            </div>
                        </div>
                        <?php
                    endforeach;
                elseif (!$has_real_posts) :
                    ?>
                    <div class="no-results">
                        <p>Belum ada proyek pelanggan. Silakan tambahkan di <strong>Proyek Pelanggan</strong> > <strong>Tambah Proyek</strong></p>
                    </div>
                    <?php
                endif;
                ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Company Logos Section -->
    <section class="company-logos-section">
        <div class="container">
            <h2><?php echo esc_html(get_theme_mod('inviro_pelanggan_logos_title', 'Corporate Portfolio Project by INVIRO')); ?></h2>
            <p class="logos-subtitle"><?php echo esc_html(get_theme_mod('inviro_pelanggan_logos_subtitle', 'Dipercaya oleh perusahaan terkemuka')); ?></p>
            
            <div class="logos-grid">
                <?php
                // Get company logos from customizer
                $company_logos_json = get_theme_mod('inviro_pelanggan_company_logos', '');
                $company_logos = array();
                
                if (!empty($company_logos_json)) {
                    $decoded = json_decode($company_logos_json, true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $company_logos = $decoded;
                        // Sort by order
                        usort($company_logos, function($a, $b) {
                            return ($a['order'] ?? 999) - ($b['order'] ?? 999);
                        });
                    }
                }
                
                // Fallback to old format (backward compatibility)
                if (empty($company_logos)) {
                    for ($i = 1; $i <= 12; $i++) {
                        $logo_image = get_theme_mod("inviro_pelanggan_logo_{$i}_image");
                        $logo_name = get_theme_mod("inviro_pelanggan_logo_{$i}_name");
                        if ($logo_image) {
                            $company_logos[] = array(
                                'name' => $logo_name ? $logo_name : 'Company Logo',
                                'image' => $logo_image,
                                'order' => $i
                            );
                        }
                    }
                }
                
                // Fallback to default dummy data if still empty
                if (empty($company_logos)) {
                    $default_logos = array(
                        array('name' => 'PT. Perusahaan A', 'image' => 'https://via.placeholder.com/200x100/2F80ED/FFFFFF?text=Logo+1', 'order' => 1),
                        array('name' => 'PT. Perusahaan B', 'image' => 'https://via.placeholder.com/200x100/4FB3E8/FFFFFF?text=Logo+2', 'order' => 2),
                        array('name' => 'PT. Perusahaan C', 'image' => 'https://via.placeholder.com/200x100/75C6F1/FFFFFF?text=Logo+3', 'order' => 3),
                        array('name' => 'PT. Perusahaan D', 'image' => 'https://via.placeholder.com/200x100/2F80ED/FFFFFF?text=Logo+4', 'order' => 4),
                        array('name' => 'PT. Perusahaan E', 'image' => 'https://via.placeholder.com/200x100/4FB3E8/FFFFFF?text=Logo+5', 'order' => 5),
                        array('name' => 'PT. Perusahaan F', 'image' => 'https://via.placeholder.com/200x100/75C6F1/FFFFFF?text=Logo+6', 'order' => 6),
                    );
                    $company_logos = $default_logos;
                }
                
                // Display logos
                foreach ($company_logos as $logo) {
                    $logo_name = isset($logo['name']) ? $logo['name'] : '';
                    $logo_image = isset($logo['image']) ? $logo['image'] : '';
                    
                    if (!empty($logo_image)) :
                ?>
                <div class="logo-card">
                    <img src="<?php echo esc_url($logo_image); ?>" alt="<?php echo esc_attr($logo_name ? $logo_name : 'Company Logo'); ?>" loading="lazy">
                    <?php if ($logo_name) : ?>
                        <p class="logo-name"><?php echo esc_html($logo_name); ?></p>
                    <?php endif; ?>
                </div>
                <?php 
                    endif;
                }
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="pelanggan-cta">
        <div class="container">
            <h2><?php echo esc_html(get_theme_mod('inviro_pelanggan_cta_title', 'Bergabunglah dengan Pelanggan Kami')); ?></h2>
            <p><?php echo esc_html(get_theme_mod('inviro_pelanggan_cta_subtitle', 'Dapatkan solusi terbaik untuk bisnis depot air minum Anda')); ?></p>
            <a href="<?php echo esc_url(get_theme_mod('inviro_pelanggan_cta_link', '#kontak')); ?>" class="btn-primary">
                <?php echo esc_html(get_theme_mod('inviro_pelanggan_cta_button', 'Hubungi Kami Sekarang')); ?>
            </a>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pelanggan-search');
    const sortSelect = document.getElementById('sort-by');
    const regionButtons = document.querySelectorAll('.region-item');
    const cards = document.querySelectorAll('.pelanggan-card');
    const grid = document.querySelector('.pelanggan-grid');
    let selectedRegion = '';
    
    // Function to update region counts
    function updateRegionCounts() {
        const regionCounts = {};
        
        // Count cards per region
        cards.forEach(card => {
            const cardRegions = (card.dataset.region || '').split(' ').filter(Boolean);
            if (cardRegions.length === 0) {
                regionCounts['all'] = (regionCounts['all'] || 0) + 1;
            } else {
                cardRegions.forEach(region => {
                    regionCounts[region] = (regionCounts[region] || 0) + 1;
                });
                regionCounts['all'] = (regionCounts['all'] || 0) + 1;
            }
        });
        
        // Update count displays
        document.querySelectorAll('.region-count').forEach(countEl => {
            const regionKey = countEl.dataset.count || 'all';
            const count = regionCounts[regionKey] || 0;
            countEl.textContent = count;
        });
    }
    
    // Function to filter cards
    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;
        
        cards.forEach((card, index) => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const excerptEl = card.querySelector('.pelanggan-excerpt');
            const excerpt = excerptEl ? excerptEl.textContent.toLowerCase() : '';
            const cardRegion = card.dataset.region || '';
            const cardRegions = cardRegion.split(' ').filter(Boolean);
            
            // Search match
            const searchMatch = !searchTerm || 
                title.includes(searchTerm) || 
                excerpt.includes(searchTerm);
            
            // Region match
            const regionMatch = !selectedRegion || 
                selectedRegion === 'all' || 
                cardRegions.includes(selectedRegion);
            
            const matches = searchMatch && regionMatch;
            
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
        if (visibleCount === 0 && (searchTerm || selectedRegion)) {
            if (!noResults) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'no-results';
                noResultsDiv.innerHTML = '<p>Tidak ada proyek pelanggan yang ditemukan' + (searchTerm ? ' untuk "<strong>' + searchTerm + '</strong>"' : '') + (selectedRegion && selectedRegion !== 'all' ? ' di daerah yang dipilih' : '') + '</p>';
                grid.appendChild(noResultsDiv);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }
    
    // Region button click handlers
    regionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            regionButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get filter value
            selectedRegion = this.dataset.filter || '';
            
            // Filter cards
            filterCards();
        });
    });
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }
    
    // Sort functionality
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
    
    // Initialize counts and animations
    updateRegionCounts();
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        setTimeout(() => {
            card.style.opacity = '1';
        }, index * 100);
    });
});
</script>

<?php get_footer(); ?>
