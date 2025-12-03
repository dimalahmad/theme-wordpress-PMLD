<?php
/**
 * Theme Setup Functions
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function inviro_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    ));
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Menu Utama', 'inviro'),
        'footer'  => __('Menu Footer', 'inviro')
    ));
    
    // Set image sizes with responsive breakpoints
    set_post_thumbnail_size(1200, 630, true);
    
    // Product images - responsive sizes
    add_image_size('inviro-product', 400, 400, true);
    add_image_size('inviro-product-small', 300, 300, true);
    add_image_size('inviro-product-large', 600, 600, true);
    
    // Testimonial avatars
    add_image_size('inviro-testimonial', 100, 100, true);
    
    // Branch images
    add_image_size('inviro-branch', 300, 200, true);
    
    // Sertifikat images
    add_image_size('inviro-sertifikat', 400, 300, true);
    add_image_size('inviro-branch-large', 600, 400, true);
    
    // Hero images
    add_image_size('inviro-hero', 1920, 800, true);
    add_image_size('inviro-hero-mobile', 768, 600, true);
    add_image_size('inviro-hero-tablet', 1024, 700, true);
    
    // Hero articles grid sizes
    add_image_size('inviro-hero-large', 800, 600, true);      // For the large card
    add_image_size('inviro-hero-medium', 400, 300, true);     // For medium cards
    add_image_size('inviro-hero-small', 300, 200, true);      // For small cards
}
add_action('after_setup_theme', 'inviro_theme_setup');

/**
 * Admin Notice for Regenerating Thumbnails
 * Show notice to regenerate thumbnails after adding new image sizes
 */
function inviro_admin_notice_regenerate_thumbnails() {
    // Check if we've shown this notice before
    $notice_shown = get_option('inviro_thumbnail_notice_shown');
    
    // Also check if user has dismissed the notice
    $notice_dismissed = get_option('inviro_thumbnail_notice_dismissed');
    
    if (!$notice_shown && !$notice_dismissed && current_user_can('manage_options')) {
        ?>
        <div class="notice notice-warning is-dismissible" id="inviro-regenerate-thumbnails-notice">
            <p><strong>INVIRO Theme:</strong> Ukuran gambar baru telah ditambahkan untuk Hero Section.</p>
            <p>Untuk memastikan gambar proyek pelanggan tampil dengan baik di Hero Section, silakan:</p>
            <ol>
                <li>Install plugin "Regenerate Thumbnails" jika belum ada</li>
                <li>Pergi ke <strong>Tools → Regenerate Thumbnails</strong></li>
                <li>Klik "Regenerate All Thumbnails"</li>
            </ol>
            <p>Atau Anda bisa upload ulang featured image untuk proyek pelanggan yang ingin ditampilkan di Hero Section.</p>
            <p><a href="#" class="button button-primary" id="inviro-dismiss-thumbnail-notice">Saya Mengerti</a></p>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $('#inviro-dismiss-thumbnail-notice, #inviro-regenerate-thumbnails-notice .notice-dismiss').on('click', function(e) {
                if ($(this).attr('id') === 'inviro-dismiss-thumbnail-notice') {
                    e.preventDefault();
                }
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'inviro_dismiss_thumbnail_notice',
                        nonce: '<?php echo wp_create_nonce("inviro_dismiss_notice"); ?>'
                    }
                });
                $('#inviro-regenerate-thumbnails-notice').fadeOut();
            });
        });
        </script>
        <?php
        // Mark that we've shown the notice
        update_option('inviro_thumbnail_notice_shown', true);
    }
}
add_action('admin_notices', 'inviro_admin_notice_regenerate_thumbnails');

/**
 * AJAX handler to dismiss the thumbnail notice
 */
function inviro_dismiss_thumbnail_notice() {
    if (!wp_verify_nonce($_POST['nonce'], 'inviro_dismiss_notice')) {
        wp_die('Security check failed');
    }
    
    update_option('inviro_thumbnail_notice_dismissed', true);
    wp_die();
}
add_action('wp_ajax_inviro_dismiss_thumbnail_notice', 'inviro_dismiss_thumbnail_notice');

/**
 * Custom Navbar Menu dari Customizer
 * Menampilkan menu berdasarkan settings di WordPress Customizer
 */
function inviro_custom_navbar_menu() {
    $menu_items = array();
    
    // Loop melalui semua menu items (maksimal 10)
    for ($i = 1; $i <= 10; $i++) {
        $enabled = get_theme_mod('inviro_navbar_item_' . $i . '_enabled', ($i <= 7) ? true : false);
        $text = get_theme_mod('inviro_navbar_item_' . $i . '_text', '');
        $url = get_theme_mod('inviro_navbar_item_' . $i . '_url', '');
        
        if ($enabled && !empty($text) && !empty($url)) {
            $menu_items[] = array(
                'text' => $text,
                'url'  => $url,
            );
        }
    }
    
    if (!empty($menu_items)) {
        ?>
        <ul class="nav-menu" id="primary-menu" role="menubar">
            <?php foreach ($menu_items as $item) : 
                $current_class = '';
                $request_uri = $_SERVER['REQUEST_URI'];
                $parsed_uri = parse_url($request_uri);
                $current_path = isset($parsed_uri['path']) ? trim($parsed_uri['path'], '/') : '';
                $item_url = esc_url($item['url']);
                $parsed_item = parse_url($item_url);
                $item_path = isset($parsed_item['path']) ? trim($parsed_item['path'], '/') : '';
                
                // Remove WordPress subdirectory from paths if exists
                $home_path = trim(parse_url(home_url('/'), PHP_URL_PATH), '/');
                if ($home_path && strpos($current_path, $home_path) === 0) {
                    $current_path = trim(substr($current_path, strlen($home_path)), '/');
                }
                if ($home_path && $item_path && strpos($item_path, $home_path) === 0) {
                    $item_path = trim(substr($item_path, strlen($home_path)), '/');
                }
                
                // Check if current page matches menu URL
                if (home_url('/') === $item_url && is_front_page()) {
                    $current_class = 'current-menu-item';
                } elseif ($item_path && ($current_path === $item_path || strpos($current_path, $item_path . '/') === 0)) {
                    $current_class = 'current-menu-item';
                } elseif (($item_path === 'paket-usaha' || strpos($item_path, 'paket-usaha') !== false) && (is_page('paket-usaha') || is_post_type_archive('paket_usaha') || is_singular('paket_usaha') || (isset($_GET['dummy_id']) && strpos($current_path, 'paket-usaha') !== false))) {
                    $current_class = 'current-menu-item';
                } elseif (($item_path === 'spareparts' || strpos($item_path, 'spareparts') !== false) && (is_page('spareparts') || is_page('spare-parts') || is_post_type_archive('spareparts') || is_singular('spareparts') || (isset($_GET['dummy_id']) && strpos($current_path, 'spareparts') !== false))) {
                    $current_class = 'current-menu-item';
                }
            ?>
            <li<?php echo !empty($current_class) ? ' class="' . esc_attr($current_class) . '"' : ''; ?>>
                <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['text']); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php
    } else {
        // Fallback jika tidak ada menu items yang diaktifkan
        inviro_default_menu();
    }
}

/**
 * Default menu fallback
 * Menu default yang ditampilkan jika tidak ada custom menu atau WordPress menu
 */
function inviro_default_menu() {
    ?>
    <ul class="nav-menu" id="primary-menu" role="menubar">
        <li<?php echo is_front_page() ? ' class="current-menu-item"' : ''; ?>>
            <a href="<?php echo esc_url(home_url('/')); ?>">Beranda</a>
        </li>
        <li<?php echo is_page('profil') ? ' class="current-menu-item"' : ''; ?>>
            <a href="<?php echo esc_url(home_url('/profil')); ?>">Profil</a>
        </li>
        <li<?php 
            $request_uri = $_SERVER['REQUEST_URI'];
            $parsed_uri = parse_url($request_uri);
            $current_path = isset($parsed_uri['path']) ? trim($parsed_uri['path'], '/') : '';
            $home_path = trim(parse_url(home_url('/'), PHP_URL_PATH), '/');
            if ($home_path && strpos($current_path, $home_path) === 0) {
                $current_path = trim(substr($current_path, strlen($home_path)), '/');
            }
            $is_paket_usaha = is_page('paket-usaha') || is_post_type_archive('paket_usaha') || is_singular('paket_usaha') || 
                              (isset($_GET['dummy_id']) && strpos($current_path, 'paket-usaha') !== false) ||
                              ($current_path === 'paket-usaha' || strpos($current_path, 'paket-usaha/') === 0);
            echo $is_paket_usaha ? ' class="current-menu-item"' : ''; 
        ?>>
            <a href="<?php echo esc_url(home_url('/paket-usaha')); ?>">Paket Usaha</a>
        </li>
        <li<?php echo is_page('pelanggan') ? ' class="current-menu-item"' : ''; ?>>
            <a href="<?php echo esc_url(home_url('/pelanggan')); ?>">Pelanggan</a>
        </li>
        <li<?php 
            $is_spareparts = is_page('spareparts') || is_page('spare-parts') || is_post_type_archive('spareparts') || is_singular('spareparts') || 
                             (isset($_GET['dummy_id']) && strpos($current_path, 'spareparts') !== false) ||
                             ($current_path === 'spareparts' || strpos($current_path, 'spareparts/') === 0);
            echo $is_spareparts ? ' class="current-menu-item"' : ''; 
        ?>>
            <a href="<?php echo esc_url(home_url('/spareparts')); ?>">Spare Parts</a>
        </li>
        <li<?php echo ((is_single() && get_post_type() == 'post') || is_category() || is_tag() || is_date()) ? ' class="current-menu-item"' : ''; ?>>
            <a href="<?php echo esc_url(home_url('/artikel')); ?>">Artikel</a>
        </li>
        <li<?php echo is_page('unduhan') ? ' class="current-menu-item"' : ''; ?>>
            <a href="<?php echo esc_url(home_url('/unduhan')); ?>">Unduhan</a>
        </li>
    </ul>
    <?php
}

// Note: Image lazy loading and responsive image functions (inviro_add_lazy_loading, inviro_responsive_image_sizes) are in inc/hooks/hooks.php


