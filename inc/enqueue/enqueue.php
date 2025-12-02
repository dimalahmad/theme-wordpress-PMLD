<?php
/**
 * Enqueue Styles and Scripts
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue styles and scripts
 * Optimized for performance with proper dependencies
 */
function inviro_enqueue_files() {
    // Get theme version for cache busting
    $theme_version = wp_get_theme()->get('Version');
    
    // Enqueue Google Fonts
    wp_enqueue_style('inviro-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap', array(), null);
    
    // Base styles (always loaded) - Core design system
    wp_enqueue_style('inviro-base', get_template_directory_uri() . '/assets/css/base.css', array(), $theme_version);
    
    // Component styles (reusable components)
    wp_enqueue_style('inviro-components-cards', get_template_directory_uri() . '/assets/css/components/cards.css', array('inviro-base'), $theme_version);
    wp_enqueue_style('inviro-components-forms', get_template_directory_uri() . '/assets/css/components/forms.css', array('inviro-base'), $theme_version);
    
    // Layout styles
    wp_enqueue_style('inviro-header', get_template_directory_uri() . '/assets/css/header.css', array('inviro-base'), $theme_version);
    wp_enqueue_style('inviro-footer', get_template_directory_uri() . '/assets/css/footer.css', array('inviro-base'), $theme_version);
    
    // Animations (lightweight)
    wp_enqueue_style('inviro-animations', get_template_directory_uri() . '/assets/css/animations.css', array('inviro-base'), $theme_version);
    
    // Page specific styles (conditional loading)
    if (is_front_page()) {
        wp_enqueue_style('inviro-front-page', get_template_directory_uri() . '/assets/css/front-page.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
        wp_enqueue_style('inviro-hero-fix', get_template_directory_uri() . '/assets/css/hero-section-fix.css', array('inviro-front-page'), $theme_version);
        // Product fix disabled - using front-page.css only for consistency
    } elseif (is_page('profil')) {
        wp_enqueue_style('inviro-profil', get_template_directory_uri() . '/assets/css/profil.css', array('inviro-base'), $theme_version);
        wp_enqueue_style('inviro-front-page', get_template_directory_uri() . '/assets/css/front-page.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
        wp_enqueue_style('inviro-hero-fix', get_template_directory_uri() . '/assets/css/hero-section-fix.css', array('inviro-front-page'), $theme_version);
    } elseif (is_page('pelanggan') || is_page_template('page-pelanggan.php') || is_post_type_archive('proyek_pelanggan')) {
        wp_enqueue_style('inviro-pelanggan', get_template_directory_uri() . '/assets/css/pelanggan.css', array('inviro-base', 'inviro-components-cards'), $theme_version . '.' . filemtime(get_template_directory() . '/assets/css/pelanggan.css'));
    } elseif (is_page('paket-usaha') || is_page_template('page-paket-usaha.php') || is_post_type_archive('paket_usaha')) {
        wp_enqueue_style('inviro-paket-usaha', get_template_directory_uri() . '/assets/css/paket-usaha.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    } elseif (is_page('spareparts') || is_page_template('page-spareparts.php') || is_post_type_archive('spareparts')) {
        wp_enqueue_style('inviro-spareparts', get_template_directory_uri() . '/assets/css/spareparts.css', array('inviro-base', 'inviro-components-cards'), $theme_version . '.' . filemtime(get_template_directory() . '/assets/css/spareparts.css'));
    } elseif (is_page('artikel') || is_page_template('page-artikel.php') || is_post_type_archive('artikel')) {
        wp_enqueue_style('inviro-artikel', get_template_directory_uri() . '/assets/css/artikel.css', array('inviro-base', 'inviro-components-cards'), $theme_version . '.' . filemtime(get_template_directory() . '/assets/css/artikel.css'));
    } elseif (is_page('unduhan') || is_page_template('page-unduhan.php') || is_post_type_archive('unduhan')) {
        wp_enqueue_style('inviro-unduhan', get_template_directory_uri() . '/assets/css/unduhan.css', array('inviro-base', 'inviro-components-cards'), $theme_version . '.' . filemtime(get_template_directory() . '/assets/css/unduhan.css'));
    } elseif (is_singular('spareparts')) {
        wp_enqueue_style('inviro-sparepart-detail', get_template_directory_uri() . '/assets/css/sparepart-detail.css', array('inviro-base', 'inviro-components-cards'), $theme_version . '.' . filemtime(get_template_directory() . '/assets/css/sparepart-detail.css'));
    } elseif (is_singular('paket_usaha') || (isset($_GET['dummy_id']) && isset($_GET['post_type']) && $_GET['post_type'] === 'paket_usaha')) {
        wp_enqueue_style('inviro-sparepart-detail', get_template_directory_uri() . '/assets/css/sparepart-detail.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    } elseif (is_singular('proyek_pelanggan')) {
        wp_enqueue_style('inviro-pelanggan-article', get_template_directory_uri() . '/assets/css/pelanggan-article.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    } elseif (is_singular('artikel')) {
        wp_enqueue_style('inviro-artikel-detail', get_template_directory_uri() . '/assets/css/artikel-detail.css', array('inviro-base', 'inviro-components-cards'), $theme_version . '.' . filemtime(get_template_directory() . '/assets/css/artikel-detail.css'));
    } elseif (is_single()) {
        wp_enqueue_style('inviro-single', get_template_directory_uri() . '/assets/css/single.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    } elseif (is_archive() || is_post_type_archive()) {
        wp_enqueue_style('inviro-archive', get_template_directory_uri() . '/assets/css/archive.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    }
    
    // Enqueue main stylesheet for fallback (minimal)
    wp_enqueue_style('inviro-style', get_stylesheet_uri(), array('inviro-base'), $theme_version);
    
    // Enqueue sparepart detail CSS if viewing dummy detail
    if (isset($_GET['dummy_id']) && intval($_GET['dummy_id']) > 0) {
        wp_enqueue_style('inviro-sparepart-detail', get_template_directory_uri() . '/assets/css/sparepart-detail.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    }
    
    // Enqueue scripts (defer for performance)
    wp_enqueue_script('jquery');
    wp_enqueue_script('inviro-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), $theme_version, true);
    
    // Add defer attribute to main script
    add_filter('script_loader_tag', function($tag, $handle) {
        if ('inviro-js' === $handle) {
            return str_replace(' src', ' defer src', $tag);
        }
        return $tag;
    }, 10, 2);
    
    // Localize script for AJAX
    wp_localize_script('inviro-js', 'inviroAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('inviro_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'inviro_enqueue_files');


