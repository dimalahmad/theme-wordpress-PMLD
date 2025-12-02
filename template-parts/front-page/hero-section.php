<?php
/**
 * Template part for displaying hero section
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$posts = inviro_get_hero_projects();
$display_mode = get_theme_mod('inviro_hero_display_mode', 'selected');
$selected_projects = get_theme_mod('inviro_hero_selected_projects', '');
$show_no_projects_message = ($display_mode === 'selected' && empty($selected_projects));
?>

<section class="hero-section" itemscope itemtype="https://schema.org/ItemList">
    <div class="container">
        <div class="hero-articles-grid">
            <?php if (!empty($posts)) : ?>
                <?php if (!empty($posts[0])) : ?>
                    <?php inviro_render_hero_card($posts[0], 'large'); ?>
                <?php endif; ?>
                
                <?php if (count($posts) > 1) : ?>
                    <div class="hero-articles-grid-right">
                        <?php if (!empty($posts[1])) : ?>
                            <?php inviro_render_hero_card($posts[1], 'small-top'); ?>
                        <?php endif; ?>
                        
                        <div class="hero-articles-bottom-row">
                            <?php for ($i = 2; $i <= 3; $i++) : ?>
                                <?php if (!empty($posts[$i])) : ?>
                                    <?php inviro_render_hero_card($posts[$i], 'small'); ?>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
            <?php elseif ($show_no_projects_message) : ?>
                <div class="hero-article-card hero-article-large" style="grid-column: 1 / -1;">
                    <div class="hero-article-image hero-article-placeholder">
                        <div class="hero-article-overlay"></div>
                    </div>
                    <div class="hero-article-content">
                        <span class="hero-article-category">Info</span>
                        <h2 class="hero-article-title">Belum ada proyek yang dipilih</h2>
                        <p>Silakan pilih proyek pelanggan di <strong>Appearance → Customize → Statistik & Hero → Pilih Proyek untuk Hero Section</strong></p>
                    </div>
                </div>
            <?php else : ?>
                <?php
                // Fallback to dummy data
                $dummy_pelanggan = function_exists('inviro_get_dummy_pelanggan') ? inviro_get_dummy_pelanggan() : array();
                if (!empty($dummy_pelanggan)) :
                    $dummy_pelanggan = array_slice($dummy_pelanggan, 0, 4);
                    $demo_image = 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&h=800&fit=crop&q=80';
                    
                    if (!empty($dummy_pelanggan[0])) :
                        inviro_render_dummy_hero_card($dummy_pelanggan[0], 'large', $demo_image);
                    endif;
                    
                    if (count($dummy_pelanggan) > 1) : ?>
                        <div class="hero-articles-grid-right">
                            <?php if (!empty($dummy_pelanggan[1])) : ?>
                                <?php inviro_render_dummy_hero_card($dummy_pelanggan[1], 'small-top', $demo_image); ?>
                            <?php endif; ?>
                            
                            <div class="hero-articles-bottom-row">
                                <?php for ($i = 2; $i <= 3; $i++) : ?>
                                    <?php if (!empty($dummy_pelanggan[$i])) : ?>
                                        <?php inviro_render_dummy_hero_card($dummy_pelanggan[$i], 'small', $demo_image); ?>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif;
                else : ?>
                    <div class="hero-article-card hero-article-large">
                        <div class="hero-article-image hero-article-placeholder">
                            <div class="hero-article-overlay"></div>
                        </div>
                        <div class="hero-article-content">
                            <span class="hero-article-category">Proyek</span>
                            <h2 class="hero-article-title">Belum ada proyek pelanggan</h2>
                            <p>Tambah proyek pelanggan di WordPress Admin untuk menampilkan di sini.</p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
