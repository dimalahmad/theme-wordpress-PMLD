<?php
/**
 * Template Name: Profil
 * 
 * @package INVIRO
 */

get_header();
?>

<main id="primary" class="site-main profil-page">
    
    <!-- Hero Section - Same as About Section -->
    <section id="about" class="about-section" itemscope itemtype="https://schema.org/AboutPage">
        <div class="container">
            <div class="about-main-title">
                <?php
                // Use custom logo from WordPress (same as footer)
                if (has_custom_logo()) {
                    // Get custom logo ID and URL
                    $custom_logo_id = get_theme_mod('custom_logo');
                    if ($custom_logo_id) {
                        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                        $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                        if (empty($logo_alt)) {
                            $logo_alt = get_bloginfo('name');
                        }
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="custom-logo" />
                        </a>
                        <?php
                    } else {
                        the_custom_logo();
                    }
                } else {
                    // Fallback: text logo
                    ?>
                    <h2><?php echo esc_html(get_theme_mod('inviro_about_title', 'INVIRO')); ?></h2>
                    <?php
                }
                ?>
            </div>
            <div class="about-content">
                <?php
                // Get number of branches to display from customizer
                $branch_count = get_theme_mod('inviro_branch_count', 4);
                $branch_count = max(1, min(8, absint($branch_count))); // Ensure between 1-8
                ?>
                <div class="about-branches branch-count-<?php echo esc_attr($branch_count); ?>">
                    <?php
                    
                    // Get selected branches from customizer
                    $displayed_branches = array();
                    for ($i = 1; $i <= 8; $i++) {
                        $branch_id = get_theme_mod('inviro_branch_' . $i);
                        if ($branch_id) {
                            $displayed_branches[] = $branch_id;
                        }
                    }
                    
                    // Limit to the number of branches specified
                    if (count($displayed_branches) > $branch_count) {
                        $displayed_branches = array_slice($displayed_branches, 0, $branch_count);
                    }
                    
                    // Display selected branches
                    if (!empty($displayed_branches)) :
                        $branches_query = new WP_Query(array(
                            'post_type' => 'cabang',
                            'post__in' => $displayed_branches,
                            'orderby' => 'post__in',
                            'posts_per_page' => $branch_count
                        ));
                        
                        if ($branches_query->have_posts()) :
                            while ($branches_query->have_posts()) : $branches_query->the_post();
                                $location = get_post_meta(get_the_ID(), '_branch_location', true);
                                ?>
                                <div class="branch-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="branch-image">
                                            <?php the_post_thumbnail('inviro-branch'); ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="branch-name"><?php the_title(); ?></h3>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                    else :
                        // Fallback: display latest branches if none selected
                        $branches_query = new WP_Query(array(
                            'post_type' => 'cabang',
                            'posts_per_page' => $branch_count,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($branches_query->have_posts()) :
                            while ($branches_query->have_posts()) : $branches_query->the_post();
                                $location = get_post_meta(get_the_ID(), '_branch_location', true);
                                ?>
                                <div class="branch-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="branch-image">
                                            <?php the_post_thumbnail('inviro-branch'); ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="branch-name"><?php the_title(); ?></h3>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            // Fallback to dummy data
                            $dummy_branches = function_exists('inviro_get_dummy_branches') ? inviro_get_dummy_branches() : array();
                            if (!empty($dummy_branches)) :
                                $dummy_branches = array_slice($dummy_branches, 0, 4);
                                foreach ($dummy_branches as $branch) :
                                    ?>
                                    <div class="branch-card">
                                        <div class="branch-image">
                                            <img src="<?php echo esc_url($branch['image']); ?>" alt="<?php echo esc_attr($branch['name']); ?>" loading="lazy">
                                        </div>
                                        <h3 class="branch-name"><?php echo esc_html($branch['name']); ?></h3>
                                    </div>
                                    <?php
                                endforeach;
                            endif;
                        endif;
                    endif;
                    ?>
                </div>
                
                <div class="about-text">
                    <div class="about-description" itemprop="description">
                        <?php echo wp_kses_post(get_theme_mod('inviro_about_description', 'INVIRO menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air. INVIRO adalah divisi usaha dari CV. INDO SOLUTION yang memiliki kantor di Jakarta, Surabaya, Bandung, Semarang, dan kantor pusat di Jogjakarta. Kami bergerak dalam bidang Water Treatment, Water Purifier, Water Equipment, Water Purification Systems, dan usaha-usaha terkait lainnya. Kami melayani kebutuhan individu/rumah tangga, rumah sakit, pabrik, kantor, asrama, hotel, penginapan, restoran, sekolah, dan sektor komersial lainnya.')); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sejarah Section - CV Perusahaan -->
    <section class="profil-history">
        <div class="container">
            <div class="history-header">
                <h2><?php echo esc_html(get_theme_mod('inviro_profil_history_title', 'Sejarah Perusahaan')); ?></h2>
                <p class="history-subtitle"><?php echo esc_html(get_theme_mod('inviro_profil_history_subtitle', 'Profil CV. INDO SOLUTION')); ?></p>
            </div>
            <div class="cv-content">
                <div class="cv-text">
                    <?php 
                    $cv_content = get_theme_mod('inviro_profil_history_content', 'CV. INDO SOLUTION merupakan sebuah badan usaha komanditer yang didirikan pada Bulan Juli Tahun 2009 yang bergerak dibidang perdagangan umum/general trading, dalam proses perkembagannya CV. INDO SOLUTION juga ekspansi divisi bisnis dengan menghadirkan solusi bisnis dalam bidang pengolahan air (water treatment).

Divisi water treatment/water purifier CV. INDO SOLUTION yang bernama INVIRO™ [Water Solution] menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air.');
                    echo wpautop(wp_kses_post($cv_content)); 
                    ?>
                </div>
                <div class="cv-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <div class="highlight-content">
                            <h3><?php echo esc_html(get_theme_mod('inviro_profil_highlight_1_title', 'Didirikan')); ?></h3>
                            <p><?php echo esc_html(get_theme_mod('inviro_profil_highlight_1_value', 'Juli 2009')); ?></p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="highlight-content">
                            <h3><?php echo esc_html(get_theme_mod('inviro_profil_highlight_2_title', 'Bidang Usaha')); ?></h3>
                            <p><?php echo esc_html(get_theme_mod('inviro_profil_highlight_2_value', 'Perdagangan Umum & Water Treatment')); ?></p>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <div class="highlight-content">
                            <h3><?php echo esc_html(get_theme_mod('inviro_profil_highlight_3_title', 'Divisi')); ?></h3>
                            <p><?php echo esc_html(get_theme_mod('inviro_profil_highlight_3_value', 'INVIRO™ [Water Solution]')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section class="profil-layanan">
        <div class="container">
            <div class="layanan-header">
                <h2><?php echo esc_html(get_theme_mod('inviro_profil_layanan_title', 'Layanan')); ?></h2>
                <div class="layanan-logo">
                    <?php
                    if (has_custom_logo()) {
                        $custom_logo_id = get_theme_mod('custom_logo');
                        if ($custom_logo_id) {
                            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                            $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                            if (empty($logo_alt)) {
                                $logo_alt = get_bloginfo('name');
                            }
                            ?>
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="custom-logo" />
                            <?php
                        } else {
                            the_custom_logo();
                        }
                    } else {
                        ?>
                        <span class="logo-text">INVIRO™</span>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="layanan-description">
                <p><?php echo wp_kses_post(get_theme_mod('inviro_profil_layanan_description', 'Divisi water treatment/water purifier CV. INDO SOLUTION yang bernama INVIRO™ [Water Solution] menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air, kami menyediakan jasa dan produk:')); ?></p>
            </div>
            <div class="layanan-grid">
                <?php
                // Get layanan from custom post type
                $layanan_query = new WP_Query(array(
                    'post_type' => 'layanan',
                    'posts_per_page' => -1, // Get all layanan
                    'orderby' => 'menu_order',
                    'order' => 'ASC',
                    'post_status' => 'publish'
                ));
                
                if ($layanan_query->have_posts()) :
                    while ($layanan_query->have_posts()) : $layanan_query->the_post();
                        $external_url = get_post_meta(get_the_ID(), '_layanan_external_url', true);
                        $layanan_title = get_the_title();
                        
                        // If no external URL, skip or show without link
                        if (empty($external_url)) {
                            continue;
                        }
                        ?>
                        <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener noreferrer" class="layanan-card-link">
                            <div class="layanan-card">
                                <span class="layanan-name"><?php echo esc_html($layanan_title); ?></span>
                            </div>
                        </a>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback: Show default layanan if no custom post type data
                    $default_layanan = array(
                        array('title' => 'Air Minum Dalam Kemasan', 'url' => 'https://example.com/air-minum'),
                        array('title' => 'Pengolahan Limbah Air', 'url' => 'https://example.com/pengolahan-limbah'),
                        array('title' => 'Depot Air Minum Isi Ulang', 'url' => 'https://example.com/depot-air'),
                        array('title' => 'Reverse Osmosis', 'url' => 'https://example.com/reverse-osmosis'),
                        array('title' => 'Kran Air Siap Minum', 'url' => 'https://example.com/kran-air'),
                        array('title' => 'Sea Water RO', 'url' => 'https://example.com/sea-water-ro'),
                        array('title' => 'Ozone Generator', 'url' => 'https://example.com/ozone-generator'),
                        array('title' => 'Tandon Air', 'url' => 'https://example.com/tandon-air'),
                        array('title' => 'Pemanas Air', 'url' => 'https://example.com/pemanas-air'),
                        array('title' => 'Training Sanitasi DAMIU', 'url' => 'https://example.com/training-sanitasi'),
                        array('title' => 'Ultra Violet', 'url' => 'https://example.com/ultra-violet'),
                        array('title' => 'Water Treatment Plant', 'url' => 'https://example.com/water-treatment-plant')
                    );
                    
                    foreach ($default_layanan as $layanan) :
                        ?>
                        <a href="<?php echo esc_url($layanan['url']); ?>" target="_blank" rel="noopener noreferrer" class="layanan-card-link">
                            <div class="layanan-card">
                                <span class="layanan-name"><?php echo esc_html($layanan['title']); ?></span>
                            </div>
                        </a>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Video & Legalitas Section -->
    <section class="profil-video-legalitas">
        <div class="container">
            <div class="video-legalitas-header">
                <h2><?php echo esc_html(get_theme_mod('inviro_profil_video_legalitas_title', 'Video Proses Bisnis & Legalitas')); ?></h2>
                <p class="section-subtitle"><?php echo esc_html(get_theme_mod('inviro_profil_video_legalitas_subtitle', 'Tonton proses bisnis kami dan lihat legalitas perusahaan')); ?></p>
            </div>
            
            <!-- Video Section - Full Width -->
            <div class="video-section-full">
                <h3><?php echo esc_html(get_theme_mod('inviro_profil_video_title', 'Video Proses Bisnis INVIRO')); ?></h3>
                <div class="video-wrapper">
                    <?php
                    $youtube_url = get_theme_mod('inviro_profil_youtube_url', '');
                    if (!empty($youtube_url)) {
                        // Convert YouTube URL to embed format
                        $video_id = '';
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube_url, $matches)) {
                            $video_id = $matches[1];
                        }
                        
                        if ($video_id) {
                            ?>
                            <div class="video-container">
                                <iframe 
                                    src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>?rel=0&modestbranding=1" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    loading="lazy">
                                </iframe>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="video-placeholder">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                                <p>Masukkan URL YouTube yang valid</p>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="video-placeholder">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                            </svg>
                            <p>Video belum ditambahkan</p>
                            <small>Tambahkan URL YouTube di Customizer</small>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            
            <!-- Legalitas & Sertifikat Section - 2 Columns -->
            <div class="legalitas-sertifikat-content">
                <!-- Legalitas Data - Left Column -->
                <div class="legalitas-data-column">
                    <div class="legalitas-header">
                        <h3><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_title', 'Data Terkait Legalitas')); ?></h3>
                    </div>
                    <div class="legalitas-data">
                        <div class="legalitas-intro">
                            <p><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_intro', 'Adapun Legalitas Perusahaan kami adalah sebagai berikut:')); ?></p>
                        </div>
                        <div class="legalitas-list">
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Alamat Perusahaan</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_alamat', 'Jl. Parangtritis Km. 4,5 Yogyakarta')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                    </svg>
                                    <span>Bidang Usaha</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_bidang_usaha', 'Peralatan Filter Air')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    <span>No. Telepon</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_telepon', '0274–385 322')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    <span>Akta Pendirian</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_akta_pendirian', 'No. 01 Tanggal 27 Juli 2009 Notaris Dewi Lestari, S.H')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    <span>Akta Perubahan</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_akta_perubahan', '115/CV/III/2018/KUM.01.01.PHBH')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                    <span>Pengesahan</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_pengesahan', 'No. 162/CV/VIII/2009 Kum. 01.01/Pengadilan Negeri Bantul')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="9" y1="3" x2="9" y2="21"></line>
                                    </svg>
                                    <span>HO</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_ho', '12/Pem/Pgh/2018')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="9" y1="3" x2="9" y2="21"></line>
                                    </svg>
                                    <span>SIUP</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_siup', '1050DPMPT/007/III/2018')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="9" y1="3" x2="9" y2="21"></line>
                                    </svg>
                                    <span>TDP</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_tdp', '1051/DPMPT/099/III/2018')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="9" y1="3" x2="9" y2="21"></line>
                                    </svg>
                                    <span>NPWP</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_npwp', '21.111.248.7-543.000')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="9" y1="3" x2="9" y2="21"></line>
                                    </svg>
                                    <span>PKP</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_pkp', 'S-144PKP/WPJ.23/KP.0503/2018')); ?></div>
                            </div>
                            
                            <div class="legalitas-item">
                                <div class="legalitas-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <span>Email</span>
                                </div>
                                <div class="legalitas-value"><?php echo esc_html(get_theme_mod('inviro_profil_legalitas_email', 'inviro.co.id[at]gmail.com')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sertifikat Image - Right Column -->
                <div class="sertifikat-column">
                    <div class="legalitas-header">
                        <h3><?php echo esc_html(get_theme_mod('inviro_profil_sertifikat_title', 'Sertifikat')); ?></h3>
                    </div>
                    <div class="sertifikat-single">
                        <?php
                        // Get sertifikat from custom post type or customizer
                        $sertifikat_image = '';
                        $sertifikat_title = 'Legalitas INVIRO';
                        
                        $sertifikat_query = new WP_Query(array(
                            'post_type' => 'sertifikat',
                            'posts_per_page' => 1,
                            'orderby' => 'menu_order',
                            'order' => 'ASC',
                            'post_status' => 'publish'
                        ));
                        
                        if ($sertifikat_query->have_posts()) :
                            $sertifikat_query->the_post();
                            if (has_post_thumbnail()) :
                                $sertifikat_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                $sertifikat_title = get_the_title();
                            endif;
                            wp_reset_postdata();
                        else :
                            // Fallback: Get from customizer (first one)
                            $sertifikat_image = get_theme_mod("inviro_profil_cert_1_image", '');
                            $sertifikat_title = get_theme_mod("inviro_profil_cert_1_title", 'Legalitas INVIRO');
                        endif;
                        
                        if ($sertifikat_image) :
                            ?>
                            <div class="sertifikat-image-full">
                                <a href="<?php echo esc_url($sertifikat_image); ?>" data-lightbox="sertifikat" data-title="<?php echo esc_attr($sertifikat_title); ?>">
                                    <img src="<?php echo esc_url($sertifikat_image); ?>" alt="<?php echo esc_attr($sertifikat_title); ?>">
                                    <div class="sertifikat-overlay">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                        <span>Klik untuk memperbesar</span>
                                    </div>
                                </a>
                            </div>
                            <?php
                        else :
                            ?>
                            <div class="sertifikat-placeholder">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="9" y1="3" x2="9" y2="21"></line>
                                </svg>
                                <p>Gambar sertifikat belum ditambahkan</p>
                                <small>Tambahkan gambar sertifikat di Customizer</small>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_testimonials_title', 'Dipercaya Oleh Banyak Pelanggan')); ?></h2>
                <p class="section-subtitle" itemprop="description"><?php echo esc_html(get_theme_mod('inviro_testimonials_subtitle', '95% Pelanggan INVIRO di berbagai daerah di Indonesia merasa puas dengan pelayanan & produk INVIRO')); ?></p>
            </div>
            
            <div class="testimonials-carousel">
                <div class="testimonials-track">
                    <?php
                    // Get selected testimonials from customizer (up to 10)
                    $selected_testimonials = array();
                    for ($i = 1; $i <= 10; $i++) {
                        $testimonial_id = get_theme_mod('inviro_testimonial_' . $i);
                        if ($testimonial_id) {
                            $selected_testimonials[] = $testimonial_id;
                        }
                    }
                    
                    if (!empty($selected_testimonials)) :
                        $testimonials = new WP_Query(array(
                            'post_type' => 'testimoni',
                            'post__in' => $selected_testimonials,
                            'orderby' => 'post__in',
                            'posts_per_page' => -1
                        ));
                        
                        if ($testimonials->have_posts()) :
                            while ($testimonials->have_posts()) : $testimonials->the_post();
                                $customer_name = get_post_meta(get_the_ID(), '_testimonial_customer_name', true);
                                $rating = get_post_meta(get_the_ID(), '_testimonial_rating', true);
                                $message = get_post_meta(get_the_ID(), '_testimonial_message', true);
                                $date = get_post_meta(get_the_ID(), '_testimonial_date', true);
                                
                                if (!$customer_name) $customer_name = get_the_title();
                                if (!$rating) $rating = 5;
                                if (!$message) $message = get_the_content();
                                if (!$date) $date = get_the_date('d / m / Y');
                                ?>
                                <div class="testimonial-card" itemscope itemtype="https://schema.org/Review">
                                    <div class="testimonial-header">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="testimonial-avatar">
                                                <?php the_post_thumbnail('inviro-testimonial'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="testimonial-meta">
                                            <h4 class="testimonial-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                <span itemprop="name"><?php echo esc_html($customer_name); ?></span>
                                            </h4>
                                            <div class="testimonial-date"><?php echo esc_html($date); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="testimonial-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="<?php echo esc_attr($rating); ?>">
                                        <meta itemprop="bestRating" content="5">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<span class="star filled">★</span>';
                                            } else {
                                                echo '<span class="star">★</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    
                                    <div class="testimonial-content" itemprop="reviewBody">
                                        <?php echo wpautop(esc_html($message)); ?>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                    else :
                        // Fallback to dummy data
                        $dummy_testimonials = function_exists('inviro_get_dummy_testimonials') ? inviro_get_dummy_testimonials() : array();
                        if (!empty($dummy_testimonials)) :
                            foreach ($dummy_testimonials as $testimonial) :
                                ?>
                                <div class="testimonial-card" itemscope itemtype="https://schema.org/Review">
                                    <div class="testimonial-header">
                                        <div class="testimonial-avatar">
                                            <img src="<?php echo esc_url($testimonial['avatar']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" loading="lazy">
                                        </div>
                                        <div class="testimonial-meta">
                                            <h4 class="testimonial-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                <span itemprop="name"><?php echo esc_html($testimonial['name']); ?></span>
                                            </h4>
                                            <div class="testimonial-date"><?php echo esc_html($testimonial['date']); ?></div>
                                        </div>
                                    </div>
                                    <div class="testimonial-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="<?php echo esc_attr($testimonial['rating']); ?>">
                                        <meta itemprop="bestRating" content="5">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $testimonial['rating']) {
                                                echo '<span class="star filled">★</span>';
                                            } else {
                                                echo '<span class="star">★</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="testimonial-content" itemprop="reviewBody">
                                        <?php echo wpautop(esc_html($testimonial['message'])); ?>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        else :
                            // Ultimate fallback - hardcoded testimonials
                            $default_testimonials = array(
                                array('name' => 'Robert B.', 'rating' => 5, 'date' => '1/1/2024', 'content' => 'Wow... I am so happy to see this business is turned out to be more than my expectations and as for the service, I am so pleased. Thank you so much!', 'avatar' => 'https://via.placeholder.com/100x100/75C6F1/FFFFFF?text=RB'),
                                array('name' => 'Diana M.', 'rating' => 5, 'date' => '1/1/2024', 'content' => 'Wow... I am very happy to use this service. It turned out to be more than my expectations and as for the service, I am so pleased. Thank you so much!', 'avatar' => 'https://via.placeholder.com/100x100/FF8B25/FFFFFF?text=DM'),
                                array('name' => 'Syahrani P.', 'rating' => 5, 'date' => '1/10/2024', 'content' => 'Wow... I am very happy to use this Service. It turned out to be more than my expectations and so far there have been no problems. Inviro always the best.', 'avatar' => 'https://via.placeholder.com/100x100/4FB3E8/FFFFFF?text=SP')
                            );
                            
                            foreach ($default_testimonials as $testimonial) :
                                ?>
                                <div class="testimonial-card">
                                    <div class="testimonial-header">
                                        <div class="testimonial-avatar">
                                            <img src="<?php echo esc_url($testimonial['avatar']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" loading="lazy">
                                        </div>
                                        <div class="testimonial-meta">
                                            <h4 class="testimonial-name"><?php echo esc_html($testimonial['name']); ?></h4>
                                            <div class="testimonial-date"><?php echo esc_html($testimonial['date']); ?></div>
                                        </div>
                                    </div>
                                    <div class="testimonial-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $testimonial['rating']) {
                                                echo '<span class="star filled">★</span>';
                                            } else {
                                                echo '<span class="star">★</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="testimonial-content">
                                        <p><?php echo esc_html($testimonial['content']); ?></p>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        endif;
                    endif;
                    ?>
                </div>
                
                <div class="testimonials-controls">
                    <button class="testimonial-prev" aria-label="Previous testimonial">‹</button>
                    <button class="testimonial-next" aria-label="Next testimonial">›</button>
                </div>
                
                <div class="testimonials-indicators"></div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="contact-section" itemscope itemtype="https://schema.org/ContactPage">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_contact_title', 'Hubungi Kami untuk Layanan Terbaik')); ?></h2>
                <p class="section-subtitle" itemprop="description"><?php echo esc_html(get_theme_mod('inviro_contact_description', 'Untuk informasi lebih lanjut mengenai produk dan layanan kami, jangan ragu untuk menghubungi kami')); ?></p>
            </div>
            
            <div class="contact-content">
                <div class="contact-map">
                    <?php
                    $map_url = get_theme_mod('inviro_contact_map_url', '');
                    if ($map_url) {
                        // Check if it's a full iframe code or just URL
                        if (strpos($map_url, '<iframe') !== false) {
                            // If it's full iframe code, extract src or use wp_kses
                            preg_match('/src=["\']([^"\']+)["\']/', $map_url, $matches);
                            if (!empty($matches[1])) {
                                $map_url = $matches[1];
                            } else {
                                // Allow iframe with wp_kses
                                $allowed_html = array(
                                    'iframe' => array(
                                        'src' => array(),
                                        'width' => array(),
                                        'height' => array(),
                                        'style' => array(),
                                        'allowfullscreen' => array(),
                                        'loading' => array(),
                                        'referrerpolicy' => array(),
                                        'frameborder' => array(),
                                    ),
                                );
                                echo wp_kses($map_url, $allowed_html);
                                $map_url = ''; // Prevent double rendering
                            }
                        }
                        
                        if ($map_url) {
                            // Clean and sanitize URL
                            $map_url = esc_url_raw($map_url);
                            ?>
                            <iframe 
                                src="<?php echo esc_attr($map_url); ?>" 
                                width="100%" 
                                height="400" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                frameborder="0">
                            </iframe>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="map-placeholder" itemscope itemtype="https://schema.org/LocalBusiness">
                            <div class="map-placeholder-content">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <p><?php esc_html_e('Peta akan ditampilkan di sini', 'inviro'); ?></p>
                                <p class="small"><?php esc_html_e('Tambahkan URL embed Google Maps di Customizer', 'inviro'); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
                <div class="contact-features">
                    <?php for ($i = 1; $i <= 3; $i++) : 
                        $icon = get_theme_mod('inviro_contact_feature_' . $i . '_icon', $i == 1 ? 'phone' : ($i == 2 ? 'tag' : 'map-pin'));
                        $title = get_theme_mod('inviro_contact_feature_' . $i . '_title', $i == 1 ? 'Customer Support' : ($i == 2 ? 'Harga & Kualitas Terjamin' : 'Banyak Lokasi'));
                        $description = get_theme_mod('inviro_contact_feature_' . $i . '_description', $i == 1 ? 'Tim support kami siap membantu Anda 24/7' : ($i == 2 ? 'Harga terbaik dengan kualitas premium' : 'Hadir di berbagai kota di Indonesia'));
                        $color = get_theme_mod('inviro_contact_feature_' . $i . '_color', $i == 1 ? '#28a745' : ($i == 2 ? '#ff8c00' : '#dc3545'));
                        ?>
                        <div class="feature-item">
                            <div class="feature-icon" style="background-color: <?php echo esc_attr($color); ?>;">
                                <?php echo inviro_get_feature_icon($icon); ?>
                            </div>
                            <div class="feature-content">
                                <h3><?php echo esc_html($title); ?></h3>
                                <p><?php echo esc_html($description); ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="contact-form-wrapper">
                <h3 class="form-title"><?php esc_html_e('Silakan tinggalkan saran atau tanggapan Anda!', 'inviro'); ?></h3>
                <form id="inviro-contact-form" class="contact-form" method="post">
                    <?php wp_nonce_field('submit_contact', 'contact_nonce'); ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-name"><?php esc_html_e('Nama', 'inviro'); ?> <span class="required">*</span></label>
                            <input type="text" id="contact-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email"><?php esc_html_e('Email', 'inviro'); ?> <span class="required">*</span></label>
                            <input type="email" id="contact-email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-subject"><?php esc_html_e('Subjek', 'inviro'); ?></label>
                            <input type="text" id="contact-subject" name="subject">
                        </div>
                        <div class="form-group">
                            <label for="contact-phone"><?php esc_html_e('Nomor Ponsel', 'inviro'); ?></label>
                            <input type="tel" id="contact-phone" name="phone">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact-message"><?php esc_html_e('Pesan', 'inviro'); ?> <span class="required">*</span></label>
                        <textarea id="contact-message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-submit">
                        <button type="submit" class="btn btn-primary"><?php esc_html_e('Kirim Pesan', 'inviro'); ?></button>
                    </div>
                    
                    <div class="form-message"></div>
                </form>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="profil-cta">
        <div class="container">
            <div class="cta-content">
                <h2><?php echo esc_html(get_theme_mod('inviro_profil_cta_title', 'Mari Bergabung Bersama Kami')); ?></h2>
                <p><?php echo esc_html(get_theme_mod('inviro_profil_cta_subtitle', 'Hubungi kami untuk solusi pengolahan air terbaik')); ?></p>
                <a href="<?php echo esc_url(get_theme_mod('inviro_profil_cta_link', '#kontak')); ?>" class="btn btn-primary">
                    <?php echo esc_html(get_theme_mod('inviro_profil_cta_button', 'Hubungi Kami')); ?>
                </a>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();