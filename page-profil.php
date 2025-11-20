<?php
/**
 * Template Name: Profil
 * 
 * @package INVIRO
 */

get_header();
?>

<main id="primary" class="site-main profil-page">
    
    <!-- Hero Section -->
    <section class="profil-hero">
        <div class="container">
            <div class="profil-hero-content">
                <h1><?php echo esc_html(get_theme_mod('inviro_profil_hero_title', 'Tentang INVIRO')); ?></h1>
                <p><?php echo esc_html(get_theme_mod('inviro_profil_hero_subtitle', 'Pelopor Teknologi Pengolahan Air di Indonesia')); ?></p>
            </div>
        </div>
    </section>

    <!-- Sejarah Section -->
    <section class="profil-history">
        <div class="container">
            <div class="profil-grid">
                <div class="profil-content">
                    <h2><?php echo esc_html(get_theme_mod('inviro_profil_history_title', 'Sejarah Perusahaan')); ?></h2>
                    <div class="profil-text">
                        <?php echo wpautop(get_theme_mod('inviro_profil_history_content', 'INVIRO didirikan dengan visi untuk menyediakan solusi pengolahan air berkualitas tinggi untuk industri dan rumah tangga di Indonesia.')); ?>
                    </div>
                </div>
                <div class="profil-image">
                    <?php 
                    $history_image = get_theme_mod('inviro_profil_history_image');
                    if ($history_image) :
                    ?>
                        <img src="<?php echo esc_url($history_image); ?>" alt="Sejarah INVIRO">
                    <?php else : ?>
                        <div class="placeholder-image">
                            <svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="400" height="300" fill="#E5E7EB"/>
                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9CA3AF" font-size="18">Sejarah</text>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section class="profil-vision-mission">
        <div class="container">
            <div class="vm-grid">
                <div class="vm-card">
                    <div class="vm-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <h3><?php echo esc_html(get_theme_mod('inviro_profil_visi_title', 'Visi')); ?></h3>
                    <p><?php echo esc_html(get_theme_mod('inviro_profil_visi_content', 'Menjadi perusahaan terdepan dalam solusi pengolahan air yang berkelanjutan dan ramah lingkungan.')); ?></p>
                </div>
                <div class="vm-card">
                    <div class="vm-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <circle cx="12" cy="17" r="4"/>
                        </svg>
                    </div>
                    <h3><?php echo esc_html(get_theme_mod('inviro_profil_misi_title', 'Misi')); ?></h3>
                    <p><?php echo esc_html(get_theme_mod('inviro_profil_misi_content', 'Menyediakan produk dan layanan berkualitas tinggi dengan inovasi berkelanjutan untuk kesejahteraan masyarakat.')); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Nilai-nilai Section -->
    <section class="profil-values">
        <div class="container">
            <h2><?php echo esc_html(get_theme_mod('inviro_profil_values_title', 'Nilai-nilai Kami')); ?></h2>
            <div class="values-grid">
                <?php for ($i = 1; $i <= 4; $i++) : 
                    $value_title = get_theme_mod("inviro_profil_value_{$i}_title", '');
                    $value_desc = get_theme_mod("inviro_profil_value_{$i}_desc", '');
                    if ($value_title) :
                ?>
                <div class="value-card">
                    <div class="value-number"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></div>
                    <h3><?php echo esc_html($value_title); ?></h3>
                    <p><?php echo esc_html($value_desc); ?></p>
                </div>
                <?php 
                    endif;
                endfor; 
                ?>
            </div>
        </div>
    </section>

    <!-- Tim Section -->
    <section class="profil-team">
        <div class="container">
            <h2><?php echo esc_html(get_theme_mod('inviro_profil_team_title', 'Tim Kami')); ?></h2>
            <div class="team-grid">
                <?php for ($i = 1; $i <= 6; $i++) : 
                    $team_name = get_theme_mod("inviro_profil_team_{$i}_name", '');
                    $team_position = get_theme_mod("inviro_profil_team_{$i}_position", '');
                    $team_image = get_theme_mod("inviro_profil_team_{$i}_image", '');
                    if ($team_name) :
                ?>
                <div class="team-card">
                    <div class="team-image">
                        <?php if ($team_image) : ?>
                            <img src="<?php echo esc_url($team_image); ?>" alt="<?php echo esc_attr($team_name); ?>">
                        <?php else : ?>
                            <div class="team-placeholder">
                                <svg viewBox="0 0 200 200" fill="none">
                                    <circle cx="100" cy="100" r="100" fill="#E5E7EB"/>
                                    <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9CA3AF" font-size="50">👤</text>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3><?php echo esc_html($team_name); ?></h3>
                    <p><?php echo esc_html($team_position); ?></p>
                </div>
                <?php 
                    endif;
                endfor; 
                ?>
            </div>
        </div>
    </section>

    <!-- Sertifikasi Section -->
    <section class="profil-certifications">
        <div class="container">
            <h2><?php echo esc_html(get_theme_mod('inviro_profil_cert_title', 'Sertifikasi & Penghargaan')); ?></h2>
            <div class="cert-grid">
                <?php for ($i = 1; $i <= 4; $i++) : 
                    $cert_title = get_theme_mod("inviro_profil_cert_{$i}_title", '');
                    $cert_image = get_theme_mod("inviro_profil_cert_{$i}_image", '');
                    if ($cert_title) :
                ?>
                <div class="cert-card">
                    <?php if ($cert_image) : ?>
                        <img src="<?php echo esc_url($cert_image); ?>" alt="<?php echo esc_attr($cert_title); ?>">
                    <?php else : ?>
                        <div class="cert-placeholder">
                            <svg viewBox="0 0 200 250" fill="none">
                                <rect width="200" height="250" fill="#E5E7EB"/>
                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9CA3AF" font-size="40">🏆</text>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <h4><?php echo esc_html($cert_title); ?></h4>
                </div>
                <?php 
                    endif;
                endfor; 
                ?>
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
