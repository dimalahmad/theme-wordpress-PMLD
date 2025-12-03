<?php
/**
 * Template part for displaying statistics section
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="stats-section">
    <div class="container">
        <div class="stats-grid" itemscope itemtype="https://schema.org/ItemList">
            <div class="stat-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <meta itemprop="position" content="1">
                <div class="stat-number" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_stat_1_number', '105+')); ?></div>
                <div class="stat-label"><?php echo esc_html(get_theme_mod('inviro_stat_1_label', 'Corporate Portofolio by INVIRO')); ?></div>
            </div>
            <div class="stat-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <meta itemprop="position" content="2">
                <div class="stat-number" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_stat_2_number', '30+')); ?></div>
                <div class="stat-label"><?php echo esc_html(get_theme_mod('inviro_stat_2_label', 'Pengguna produk di Indonesia')); ?></div>
            </div>
            <div class="stat-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <meta itemprop="position" content="3">
                <div class="stat-number" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_stat_3_number', '+95%')); ?></div>
                <div class="stat-label"><?php echo esc_html(get_theme_mod('inviro_stat_3_label', 'Kepuasan Pelanggan')); ?></div>
            </div>
        </div>
    </div>
</div>

