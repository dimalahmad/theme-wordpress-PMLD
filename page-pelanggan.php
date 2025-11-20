<?php
/**
 * Template Name: Pelanggan
 * 
 * @package INVIRO
 */

get_header();
?>

<main id="primary" class="site-main pelanggan-page">
    
    <!-- Hero Section -->
    <section class="pelanggan-hero">
        <div class="container">
            <div class="pelanggan-hero-content">
                <h1><?php echo esc_html(get_theme_mod('inviro_pelanggan_hero_title', 'Pengguna Produk INVIRO di Indonesia')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('inviro_pelanggan_hero_subtitle', 'Dipercaya oleh ratusan perusahaan terkemuka di Indonesia')); ?></p>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="pelanggan-filter">
        <div class="container">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">Semua</button>
                <button class="filter-tab" data-filter="sumatra">Sumatra</button>
                <button class="filter-tab" data-filter="jawa">Jawa</button>
                <button class="filter-tab" data-filter="kalimantan">Kalimantan</button>
                <button class="filter-tab" data-filter="sulawesi">Sulawesi</button>
                <button class="filter-tab" data-filter="maluku">Maluku</button>
                <button class="filter-tab" data-filter="nusa-tenggara">Nusa Tenggara</button>
                <button class="filter-tab" data-filter="papua">Papua</button>
            </div>
        </div>
    </section>

    <!-- Customers Grid -->
    <section class="pelanggan-grid-section">
        <div class="container">
            <div class="pelanggan-grid">
                <?php
                // Get all customer projects
                $projects = new WP_Query(array(
                    'post_type' => 'proyek_pelanggan',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($projects->have_posts()) :
                    while ($projects->have_posts()) : $projects->the_post();
                        $client_name = get_post_meta(get_the_ID(), '_proyek_client_name', true);
                        $proyek_date = get_post_meta(get_the_ID(), '_proyek_date', true);
                        $excerpt = get_post_meta(get_the_ID(), '_proyek_excerpt', true);
                        
                        // Get region taxonomy
                        $regions = get_the_terms(get_the_ID(), 'region');
                        $region_slug = ($regions && !is_wp_error($regions)) ? $regions[0]->slug : 'all';
                        
                        // Format date
                        $formatted_date = '';
                        if ($proyek_date) {
                            $formatted_date = date('d/m/Y', strtotime($proyek_date));
                        }
                ?>
                <div class="pelanggan-card" data-region="<?php echo esc_attr($region_slug); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="pelanggan-image">
                            <?php the_post_thumbnail('inviro-branch'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="pelanggan-content">
                        <h3><?php the_title(); ?></h3>
                        <?php if ($client_name) : ?>
                            <div class="pelanggan-meta">
                                <span class="pelanggan-icon">�</span>
                                <span><?php echo esc_html($client_name); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($formatted_date) : ?>
                            <div class="pelanggan-meta">
                                <span class="pelanggan-icon">�</span>
                                <span><?php echo esc_html($formatted_date); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="pelanggan-excerpt">
                            <?php 
                            // Use custom excerpt if available, otherwise fallback to the_excerpt
                            if (!empty($excerpt)) {
                                echo esc_html($excerpt);
                            } else {
                                the_excerpt();
                            }
                            ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="pelanggan-link">Baca Selengkapnya »</a>
                    </div>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback to dummy data
                    $dummy_pelanggan = function_exists('inviro_get_dummy_pelanggan') ? inviro_get_dummy_pelanggan() : array();
                    if (!empty($dummy_pelanggan)) :
                        foreach ($dummy_pelanggan as $proyek) :
                            $formatted_date = date('d/m/Y', strtotime($proyek['date']));
                            ?>
                            <div class="pelanggan-card" data-region="<?php echo esc_attr($proyek['region']); ?>">
                                <div class="pelanggan-image">
                                    <img src="<?php echo esc_url($proyek['image']); ?>" alt="<?php echo esc_attr($proyek['title']); ?>" loading="lazy">
                                </div>
                                <div class="pelanggan-content">
                                    <h3><?php echo esc_html($proyek['title']); ?></h3>
                                    <?php if (!empty($proyek['client_name'])) : ?>
                                        <div class="pelanggan-meta">
                                            <span class="pelanggan-icon">👤</span>
                                            <span><?php echo esc_html($proyek['client_name']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="pelanggan-meta">
                                        <span class="pelanggan-icon">📅</span>
                                        <span><?php echo esc_html($formatted_date); ?></span>
                                    </div>
                                    <div class="pelanggan-excerpt">
                                        <?php echo esc_html($proyek['excerpt']); ?>
                                    </div>
                                    <a href="#" class="pelanggan-link">Baca Selengkapnya »</a>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    else :
                        ?>
                        <div class="no-results">
                            <p>Belum ada proyek pelanggan. Silakan tambahkan di <strong>Proyek Pelanggan</strong> > <strong>Tambah Proyek</strong></p>
                        </div>
                        <?php
                    endif;
                endif; ?>
            </div>
        </div>
    </section>

    <!-- Company Logos Section -->
    <section class="company-logos-section">
        <div class="container">
            <h2><?php echo esc_html(get_theme_mod('inviro_pelanggan_logos_title', 'Corporate Protofolio Project by INVIRO')); ?></h2>
            <p class="logos-subtitle"><?php echo esc_html(get_theme_mod('inviro_pelanggan_logos_subtitle', 'Dipercaya oleh perusahaan terkemuka')); ?></p>
            
            <div class="logos-grid">
                <?php for ($i = 1; $i <= 12; $i++) : 
                    $logo_image = get_theme_mod("inviro_pelanggan_logo_{$i}_image");
                    $logo_name = get_theme_mod("inviro_pelanggan_logo_{$i}_name");
                    if ($logo_image) :
                ?>
                <div class="logo-card">
                    <img src="<?php echo esc_url($logo_image); ?>" alt="<?php echo esc_attr($logo_name ? $logo_name : 'Company Logo'); ?>">
                    <?php if ($logo_name) : ?>
                        <p class="logo-name"><?php echo esc_html($logo_name); ?></p>
                    <?php endif; ?>
                </div>
                <?php 
                    endif;
                endfor; 
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="pelanggan-cta">
        <div class="container">
            <div class="cta-content">
                <h2><?php echo esc_html(get_theme_mod('inviro_pelanggan_cta_title', 'Bergabunglah dengan Pelanggan Kami')); ?></h2>
                <p><?php echo esc_html(get_theme_mod('inviro_pelanggan_cta_subtitle', 'Dapatkan solusi terbaik untuk bisnis depot air minum Anda')); ?></p>
                <a href="<?php echo esc_url(get_theme_mod('inviro_pelanggan_cta_link', '#kontak')); ?>" class="btn btn-primary">
                    <?php echo esc_html(get_theme_mod('inviro_pelanggan_cta_button', 'Hubungi Kami Sekarang')); ?>
                </a>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
