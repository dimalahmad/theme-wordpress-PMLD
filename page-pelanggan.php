<?php
/**
 * Template Name: Pelanggan
 * 
 * @package INVIRO
 */

get_header();

// Check if this is a dummy detail page
$dummy_id = isset($_GET['dummy_id']) ? intval($_GET['dummy_id']) : 0;
if ($dummy_id > 0) {
    // Load dummy detail template
    $dummy_pelanggan = array();
    if (function_exists('inviro_get_dummy_pelanggan')) {
        $dummy_pelanggan = inviro_get_dummy_pelanggan();
    }
    if (empty($dummy_pelanggan)) {
        $json_file = get_template_directory() . '/dummy-data/pelanggan.json';
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $dummy_pelanggan = json_decode($json_content, true);
        }
    }
    
    // Find dummy data by ID
    $dummy_detail_data = null;
    foreach ($dummy_pelanggan as $item) {
        if (isset($item['id']) && $item['id'] == $dummy_id) {
            $dummy_detail_data = $item;
            break;
        }
    }
    
    if ($dummy_detail_data) {
        // Include dummy detail template
        $dummy_template = get_template_directory() . '/single-pelanggan-dummy.php';
        if (file_exists($dummy_template)) {
            include $dummy_template;
            get_footer();
            exit;
        }
    }
}
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
                <div class="container">
                    <div class="filter-bar">
                    <input type="text" id="pelanggan-search" placeholder="<?php echo esc_attr(get_theme_mod('inviro_pelanggan_search_placeholder', 'Cari proyek pelanggan...')); ?>" />
                    <div class="filter-dropdowns">
                        <?php
                        // Build region options (dipakai untuk dropdown)
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
                        
                        // Fallback ke taxonomy jika tidak ada data di customizer
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
                        ?>
                        <select id="pelanggan-region">
                            <option value="all"><?php esc_html_e('Semua Wilayah', 'inviro'); ?></option>
                            <?php foreach ($region_clusters as $cluster) :
                                $region_id = isset($cluster['id']) ? $cluster['id'] : '';
                                $region_name = isset($cluster['name']) ? $cluster['name'] : '';
                                $region_slug = isset($cluster['slug']) ? $cluster['slug'] : $region_id;
                                ?>
                                <option value="<?php echo esc_attr($region_slug); ?>"><?php echo esc_html($region_name); ?></option>
                            <?php endforeach; ?>
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

            <!-- Customers Grid -->
            <section class="pelanggan-grid-section">
                <div class="container">
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
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                // Check if we have real posts
                $has_real_posts = ($projects->post_count > 0);
                
                if ($has_real_posts) :
                    while ($projects->have_posts()) : $projects->the_post();
                        $client_name = get_post_meta(get_the_ID(), '_proyek_client_name', true);
                        $proyek_date = get_post_meta(get_the_ID(), '_proyek_date', true);
                        // Use excerpt from content (like artikel and spare parts)
                        $excerpt = get_the_excerpt();
                        if (empty($excerpt)) {
                            $content = get_the_content();
                            $excerpt = wp_trim_words(strip_tags($content), 20, '...');
                        }
                        
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
                    <?php
                    // Get image URL with robust fallback logic (same as hero and branches)
                    $post_id = get_the_ID();
                    $image_url = '';
                    
                    // Method 1: Check post thumbnail first
                    if (has_post_thumbnail($post_id)) {
                        // Try multiple sizes as fallback
                        $image_url = get_the_post_thumbnail_url($post_id, 'medium_large');
                        if (empty($image_url)) {
                            $image_url = get_the_post_thumbnail_url($post_id, 'full');
                        }
                        if (empty($image_url)) {
                            $image_url = get_the_post_thumbnail_url($post_id, 'large');
                        }
                        if (empty($image_url)) {
                            $image_url = get_the_post_thumbnail_url($post_id, 'medium');
                        }
                        if (empty($image_url)) {
                            $image_url = get_the_post_thumbnail_url($post_id, 'thumbnail');
                        }
                        
                        // Try wp_get_attachment_image_src as additional fallback
                        if (empty($image_url)) {
                            $thumbnail_id = get_post_thumbnail_id($post_id);
                            if ($thumbnail_id) {
                                $image_data = wp_get_attachment_image_src($thumbnail_id, 'medium_large');
                                if ($image_data && !empty($image_data[0])) {
                                    $image_url = $image_data[0];
                                }
                            }
                        }
                        
                        // Normalize URL if needed
                        if (!empty($image_url) && function_exists('inviro_normalize_image_url')) {
                            $image_url = inviro_normalize_image_url($image_url);
                        }
                    }
                    
                    // Method 2: Use helper function if available
                    if (empty($image_url) && function_exists('inviro_get_image_url')) {
                        $image_url = inviro_get_image_url($post_id, 'large');
                    }
                    ?>
                        <div class="pelanggan-image">
                            <a href="<?php the_permalink(); ?>">
                            <?php if (!empty($image_url)) : ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" style="display: block !important; visibility: visible !important; opacity: 1 !important; width: 100% !important; height: 100% !important; object-fit: cover !important;">
                    <?php else : ?>
                                <img src="https://via.placeholder.com/600x400/75C6F1/FFFFFF?text=Proyek" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" style="display: block !important; visibility: visible !important; opacity: 1 !important; width: 100% !important; height: 100% !important; object-fit: cover !important;">
                            <?php endif; ?>
                            </a>
                        </div>
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
                                    <img src="<?php echo esc_url($proyek['image']); ?>" alt="<?php echo esc_attr($proyek['title']); ?>" loading="lazy" style="display: block !important; visibility: visible !important; opacity: 1 !important; width: 100% !important; height: 100% !important; object-fit: cover !important;">
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
                </div>
            </section>
        </div>
    </div>

    <!-- Corporate Project Section -->
    <section class="company-logos-section">
        <div class="container">
            <h2><?php echo esc_html(get_theme_mod('inviro_pelanggan_logos_title', 'Corporate Portfolio Project by INVIRO')); ?></h2>
            <p class="logos-subtitle"><?php echo esc_html(get_theme_mod('inviro_pelanggan_logos_subtitle', 'Dipercaya oleh perusahaan terkemuka')); ?></p>
            
            <div class="logos-grid">
                <?php
                // Get corporate projects from post type
                $corporate_projects = new WP_Query(array(
                    'post_type' => 'corporate_project',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
                
                if ($corporate_projects->have_posts()) :
                    while ($corporate_projects->have_posts()) : $corporate_projects->the_post();
                        $post_id = get_the_ID();
                        $company_name = get_post_meta($post_id, '_corporate_project_company_name', true);
                        
                        // Get featured image with robust fallback
                        $logo_image = '';
                        $thumbnail_id = get_post_thumbnail_id($post_id);
                        
                        if ($thumbnail_id) {
                            // Method 1: Try multiple sizes
                            $logo_image = get_the_post_thumbnail_url($post_id, 'medium_large');
                            if (empty($logo_image)) {
                                $logo_image = get_the_post_thumbnail_url($post_id, 'full');
                            }
                            if (empty($logo_image)) {
                                $logo_image = get_the_post_thumbnail_url($post_id, 'large');
                            }
                            if (empty($logo_image)) {
                                $logo_image = get_the_post_thumbnail_url($post_id, 'medium');
                            }
                            if (empty($logo_image)) {
                                $logo_image = get_the_post_thumbnail_url($post_id, 'thumbnail');
                            }
                            
                            // Method 2: wp_get_attachment_image_src
                            if (empty($logo_image)) {
                                $image_data = wp_get_attachment_image_src($thumbnail_id, 'medium_large');
                                if ($image_data && !empty($image_data[0])) {
                                    $logo_image = $image_data[0];
                                } else {
                                    $image_data = wp_get_attachment_image_src($thumbnail_id, 'full');
                                    if ($image_data && !empty($image_data[0])) {
                                        $logo_image = $image_data[0];
                                    } else {
                                        $image_data = wp_get_attachment_image_src($thumbnail_id, 'large');
                                        if ($image_data && !empty($image_data[0])) {
                                            $logo_image = $image_data[0];
                                        }
                                    }
                                }
                            }
                            
                            // Method 3: Direct attachment URL
                            if (empty($logo_image)) {
                                $attachment_url = wp_get_attachment_url($thumbnail_id);
                                if ($attachment_url) {
                                    $logo_image = $attachment_url;
                                }
                            }
                            
                            // Normalize URL if needed
                            if ($logo_image && strpos($logo_image, 'wp-content/uploads') !== false) {
                                if (function_exists('inviro_normalize_image_url')) {
                                    $logo_image = inviro_normalize_image_url($logo_image);
                                }
                            }
                        }
                        
                        // Trim and validate logo_image
                        $logo_image = trim($logo_image);
                        $logo_image = !empty($logo_image) ? $logo_image : '';
                        
                        // Ensure URL is absolute
                        if (!empty($logo_image) && strpos($logo_image, 'http') !== 0) {
                            $logo_image = site_url($logo_image);
                        }
                        
                        if (!empty($logo_image)) :
                ?>
                <div class="logo-card">
                    <img src="<?php echo esc_url($logo_image); ?>" alt="<?php echo esc_attr($company_name ? $company_name : get_the_title()); ?>" loading="lazy">
                    <?php if ($company_name) : ?>
                        <p class="logo-name"><?php echo esc_html($company_name); ?></p>
                    <?php endif; ?>
                </div>
                <?php 
                        endif;
                    endwhile;
                    wp_reset_postdata();
                endif;
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
    const regionSelect = document.getElementById('pelanggan-region');
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
        
        // Reset all cards first
        cards.forEach((card) => {
            card.style.display = '';
            card.style.opacity = '';
            card.style.animation = '';
            card.style.animationDelay = '';
            card.style.transform = '';
            card.style.transition = '';
        });
        
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
            
            // Region match - handle empty string for "all"
            const regionMatch = !selectedRegion || 
                selectedRegion === '' ||
                selectedRegion === 'all' || 
                cardRegions.includes(selectedRegion);
            
            const matches = searchMatch && regionMatch;
            
            if (matches) {
                card.style.display = 'flex'; // Use flex for horizontal layout
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
        if (visibleCount === 0 && (searchTerm || (selectedRegion && selectedRegion !== '' && selectedRegion !== 'all'))) {
            if (!noResults) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'no-results';
                noResultsDiv.innerHTML = '<p>Tidak ada proyek pelanggan yang ditemukan' + (searchTerm ? ' untuk "<strong>' + searchTerm + '</strong>"' : '') + (selectedRegion && selectedRegion !== '' && selectedRegion !== 'all' ? ' di daerah yang dipilih' : '') + '</p>';
                grid.appendChild(noResultsDiv);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }
    
    // Region dropdown change handler
    if (regionSelect) {
        regionSelect.addEventListener('change', function() {
            const value = this.value || 'all';
            selectedRegion = (value === 'all') ? '' : value;
            filterCards();
        });
    }
    
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
