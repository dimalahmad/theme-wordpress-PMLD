<?php
/**
 * Template part for displaying about section
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$branch_count = get_theme_mod('inviro_branch_count', 4);
$branch_count = max(1, min(8, absint($branch_count)));

// Get selected branches
$displayed_branches = array();
for ($i = 1; $i <= 8; $i++) {
    $branch_id = get_theme_mod('inviro_branch_' . $i);
    if ($branch_id) {
        $displayed_branches[] = $branch_id;
    }
}

if (count($displayed_branches) > $branch_count) {
    $displayed_branches = array_slice($displayed_branches, 0, $branch_count);
}
?>

<section id="about" class="about-section" itemscope itemtype="https://schema.org/AboutPage">
    <div class="container">
        <div class="about-main-title">
            <?php
            if (has_custom_logo()) {
                $custom_logo_id = get_theme_mod('custom_logo');
                if ($custom_logo_id) {
                    $logo_url = inviro_get_logo_url($custom_logo_id, 'full');
                    $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                    if (empty($logo_alt)) {
                        $logo_alt = get_bloginfo('name');
                    }
                    if ($logo_url) {
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="custom-logo" />
                        </a>
                        <?php
                    } else {
                        the_custom_logo();
                    }
                } else {
                    the_custom_logo();
                }
            } else {
                ?>
                <h2><?php echo esc_html(get_theme_mod('inviro_about_title', 'INVIRO')); ?></h2>
                <?php
            }
            ?>
        </div>
        
        <div class="about-content">
            <div class="about-branches branch-count-<?php echo esc_attr($branch_count); ?>">
                <?php
                $branches_query = inviro_get_branches_query($displayed_branches, $branch_count);
                
                if ($branches_query->have_posts()) :
                    while ($branches_query->have_posts()) : $branches_query->the_post();
                        inviro_render_branch_card(get_the_ID());
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback to dummy data
                    $dummy_branches = function_exists('inviro_get_dummy_branches') ? inviro_get_dummy_branches() : array();
                    if (!empty($dummy_branches)) :
                        $dummy_branches = array_slice($dummy_branches, 0, $branch_count);
                        foreach ($dummy_branches as $branch) :
                            inviro_render_dummy_branch_card($branch);
                        endforeach;
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
