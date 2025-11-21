<?php
/**
 * INVIRO WP Theme Functions
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load Dummy Data Helper (Development Only)
 * Hapus setelah development selesai!
 */
if (file_exists(get_template_directory() . '/dummy-data/helper.php')) {
    require_once get_template_directory() . '/dummy-data/helper.php';
}

/**
 * Get SVG Icon for Contact Features
 */
function inviro_get_feature_icon($icon_name) {
    $icons = array(
        'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>',
        'tag' => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line>',
        'map-pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>',
        'mail' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>',
        'clock' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
        'award' => '<circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>',
        'check-circle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>',
    );
    
    $icon_path = isset($icons[$icon_name]) ? $icons[$icon_name] : $icons['phone'];
    
    return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $icon_path . '</svg>';
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
    } elseif (is_page('profil')) {
        wp_enqueue_style('inviro-profil', get_template_directory_uri() . '/assets/css/profil.css', array('inviro-base'), $theme_version);
        wp_enqueue_style('inviro-front-page', get_template_directory_uri() . '/assets/css/front-page.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    } elseif (is_page('pelanggan')) {
        wp_enqueue_style('inviro-pelanggan', get_template_directory_uri() . '/assets/css/pelanggan.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
        wp_enqueue_script('inviro-pelanggan-filter', get_template_directory_uri() . '/assets/js/pelanggan-filter.js', array('jquery'), $theme_version, true);
    } elseif (is_page('paket-usaha') || is_page_template('page-paket-usaha.php') || is_post_type_archive('paket_usaha')) {
        wp_enqueue_style('inviro-paket-usaha', get_template_directory_uri() . '/assets/css/paket-usaha.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    } elseif (is_page('spareparts') || is_page_template('page-spareparts.php')) {
        wp_enqueue_style('inviro-spareparts', get_template_directory_uri() . '/assets/css/spareparts.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    } elseif (is_singular('spareparts')) {
        wp_enqueue_style('inviro-sparepart-detail', get_template_directory_uri() . '/assets/css/sparepart-detail.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
    } elseif (is_singular('paket_usaha')) {
        wp_enqueue_style('inviro-paket-detail', get_template_directory_uri() . '/assets/css/sparepart-detail.css', array('inviro-base', 'inviro-components-cards'), $theme_version);
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

/**
 * Theme setup
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
}
add_action('after_setup_theme', 'inviro_theme_setup');

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
                $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $item_url = esc_url($item['url']);
                
                // Check if current page matches menu URL
                if (home_url('/') === $item_url && is_front_page()) {
                    $current_class = 'current-menu-item';
                } elseif (strpos($current_url, $item_url) !== false && $item_url !== home_url('/')) {
                    $current_class = 'current-menu-item';
                } elseif (strpos($item_url, '/paket-usaha') !== false && (is_page('paket-usaha') || is_post_type_archive('paket_usaha') || is_singular('paket_usaha') || (isset($_GET['dummy_id']) && strpos($current_url, '/paket-usaha') !== false))) {
                    $current_class = 'current-menu-item';
                } elseif (strpos($item_url, '/spareparts') !== false && (is_page('spareparts') || is_page('spare-parts') || is_post_type_archive('spareparts') || is_singular('spareparts') || (isset($_GET['dummy_id']) && strpos($current_url, '/spareparts') !== false))) {
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
        <li<?php echo (is_page('paket-usaha') || is_post_type_archive('paket_usaha') || is_singular('paket_usaha') || (isset($_GET['dummy_id']) && strpos($_SERVER['REQUEST_URI'], '/paket-usaha') !== false)) ? ' class="current-menu-item"' : ''; ?>>
            <a href="<?php echo esc_url(home_url('/paket-usaha')); ?>">Paket Usaha</a>
        </li>
        <li<?php echo is_page('pelanggan') ? ' class="current-menu-item"' : ''; ?>>
            <a href="<?php echo esc_url(home_url('/pelanggan')); ?>">Pelanggan</a>
        </li>
        <li<?php echo (is_page('spareparts') || is_page('spare-parts') || is_post_type_archive('spareparts') || is_singular('spareparts') || (isset($_GET['dummy_id']) && strpos($_SERVER['REQUEST_URI'], '/spareparts') !== false)) ? ' class="current-menu-item"' : ''; ?>>
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

/**
 * Add lazy loading to images
 */
function inviro_add_lazy_loading($attr, $attachment, $size) {
    // Skip lazy loading for above-the-fold images
    if (is_front_page() && has_post_thumbnail() && $attachment->ID === get_post_thumbnail_id()) {
        return $attr;
    }
    
    // Add loading="lazy" attribute
    $attr['loading'] = 'lazy';
    
    // Add decoding="async" for better performance
    $attr['decoding'] = 'async';
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'inviro_add_lazy_loading', 10, 3);

/**
 * Add responsive image srcset
 */
function inviro_responsive_image_sizes($sizes, $size) {
    if (is_singular()) {
        return '(max-width: 768px) 100vw, (max-width: 1024px) 80vw, 1200px';
    }
    return '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw';
}
add_filter('wp_calculate_image_sizes', 'inviro_responsive_image_sizes', 10, 2);

/**
 * Register Custom Post Types
 */

// Products Custom Post Type
function inviro_register_products() {
    $labels = array(
        'name'               => __('Produk', 'inviro'),
        'singular_name'      => __('Produk', 'inviro'),
        'menu_name'          => __('Produk', 'inviro'),
        'add_new'            => __('Tambah Produk', 'inviro'),
        'add_new_item'       => __('Tambah Produk Baru', 'inviro'),
        'edit_item'          => __('Edit Produk', 'inviro'),
        'new_item'           => __('Produk Baru', 'inviro'),
        'view_item'          => __('Lihat Produk', 'inviro'),
        'search_items'       => __('Cari Produk', 'inviro'),
        'not_found'          => __('Produk tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada produk di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => false,  // Tidak ada single page
        'publicly_queryable'  => true,   // Archive bisa diakses
        'show_ui'             => true,   // Tampil di admin
        'show_in_menu'        => true,
        'query_var'           => true,
        'has_archive'         => 'produk',  // URL archive: /produk/
        'rewrite'             => array('slug' => 'produk', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-cart',
        'supports'            => array('title', 'thumbnail'),  // Hanya title dan thumbnail, tanpa editor
        'show_in_rest'        => false  // Nonaktifkan Gutenberg editor
    );
    
    register_post_type('produk', $args);
}
add_action('init', 'inviro_register_products');

// Paket Usaha Custom Post Type
function inviro_register_paket_usaha() {
    $labels = array(
        'name'               => __('Paket Usaha', 'inviro'),
        'singular_name'      => __('Paket Usaha', 'inviro'),
        'menu_name'          => __('Paket Usaha', 'inviro'),
        'add_new'            => __('Tambah Paket', 'inviro'),
        'add_new_item'       => __('Tambah Paket Baru', 'inviro'),
        'edit_item'          => __('Edit Paket', 'inviro'),
        'new_item'           => __('Paket Baru', 'inviro'),
        'view_item'          => __('Lihat Paket', 'inviro'),
        'search_items'       => __('Cari Paket', 'inviro'),
        'not_found'          => __('Paket tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada paket di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,   // Ada single page
        'publicly_queryable'  => true,   // Archive bisa diakses
        'show_ui'             => true,   // Tampil di admin
        'show_in_menu'        => true,
        'query_var'           => true,
        'has_archive'         => 'paket-usaha',  // URL archive: /paket-usaha/
        'rewrite'             => array('slug' => 'paket-usaha', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-portfolio',
        'supports'            => array('title', 'editor', 'thumbnail'),  // Title, editor, dan thumbnail
        'show_in_rest'        => false  // Nonaktifkan Gutenberg editor
    );
    
    register_post_type('paket_usaha', $args);
    
    // Register Taxonomy for Paket Usaha Category
    $taxonomy_labels = array(
        'name'              => __('Kategori Paket Usaha', 'inviro'),
        'singular_name'     => __('Kategori Paket Usaha', 'inviro'),
        'search_items'      => __('Cari Kategori', 'inviro'),
        'all_items'         => __('Semua Kategori', 'inviro'),
        'parent_item'       => __('Kategori Induk', 'inviro'),
        'parent_item_colon' => __('Kategori Induk:', 'inviro'),
        'edit_item'         => __('Edit Kategori', 'inviro'),
        'update_item'       => __('Update Kategori', 'inviro'),
        'add_new_item'      => __('Tambah Kategori Baru', 'inviro'),
        'new_item_name'     => __('Nama Kategori Baru', 'inviro'),
        'menu_name'         => __('Kategori', 'inviro'),
    );
    
    register_taxonomy('paket_usaha_category', array('paket_usaha'), array(
        'hierarchical'      => true,
        'labels'            => $taxonomy_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'kategori-paket-usaha'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'inviro_register_paket_usaha');

// Testimonials Custom Post Type (No Single Page)
function inviro_register_testimonials() {
    $labels = array(
        'name'               => __('Testimoni', 'inviro'),
        'singular_name'      => __('Testimoni', 'inviro'),
        'menu_name'          => __('Testimoni', 'inviro'),
        'add_new'            => __('Tambah Testimoni', 'inviro'),
        'add_new_item'       => __('Tambah Testimoni Baru', 'inviro'),
        'edit_item'          => __('Edit Testimoni', 'inviro'),
        'new_item'           => __('Testimoni Baru', 'inviro'),
        'view_item'          => __('Lihat Testimoni', 'inviro'),
        'search_items'       => __('Cari Testimoni', 'inviro'),
        'not_found'          => __('Testimoni tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada testimoni di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => false,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-format-quote',
        'supports'            => array('title', 'thumbnail'),
        'show_in_rest'        => true
    );
    
    register_post_type('testimoni', $args);
}
add_action('init', 'inviro_register_testimonials');

// Cabang Custom Post Type (No Single Page)
function inviro_register_branches() {
    $labels = array(
        'name'               => __('Cabang', 'inviro'),
        'singular_name'      => __('Cabang', 'inviro'),
        'menu_name'          => __('Cabang', 'inviro'),
        'add_new'            => __('Tambah Cabang', 'inviro'),
        'add_new_item'       => __('Tambah Cabang Baru', 'inviro'),
        'edit_item'          => __('Edit Cabang', 'inviro'),
        'new_item'           => __('Cabang Baru', 'inviro'),
        'view_item'          => __('Lihat Cabang', 'inviro'),
        'search_items'       => __('Cari Cabang', 'inviro'),
        'not_found'          => __('Cabang tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada cabang di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => false,  // Tidak ada single page
        'publicly_queryable'  => false,  // Tidak bisa diakses dari depan
        'show_ui'             => true,   // Tampil di admin
        'show_in_menu'        => true,
        'query_var'           => false,
        'capability_type'     => 'post',
        'has_archive'         => false,  // Tidak ada archive
        'hierarchical'        => false,
        'menu_position'       => 7,
        'menu_icon'           => 'dashicons-location',
        'supports'            => array('title', 'thumbnail'),
        'show_in_rest'        => true,
    );
    
    register_post_type('cabang', $args);
}
add_action('init', 'inviro_register_branches');

/**
 * Add Meta Box for Branch Location
 */
function inviro_branch_meta_boxes() {
    add_meta_box(
        'inviro_branch_location',
        __('Lokasi Cabang', 'inviro'),
        'inviro_branch_location_callback',
        'cabang',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_branch_meta_boxes');

/**
 * Meta Box Callback
 */
function inviro_branch_location_callback($post) {
    wp_nonce_field('inviro_branch_location_nonce', 'inviro_branch_location_nonce');
    $location = get_post_meta($post->ID, '_branch_location', true);
    ?>
    <p>
        <label for="branch_location"><?php _e('Alamat/Lokasi Cabang:', 'inviro'); ?></label><br>
        <textarea name="branch_location" id="branch_location" rows="3" style="width: 100%;"><?php echo esc_textarea($location); ?></textarea>
    </p>
    <?php
}

/**
 * Save Branch Location Meta
 */
function inviro_save_branch_location($post_id) {
    if (!isset($_POST['inviro_branch_location_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['inviro_branch_location_nonce'], 'inviro_branch_location_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['branch_location'])) {
        update_post_meta($post_id, '_branch_location', sanitize_textarea_field($_POST['branch_location']));
    }
}
add_action('save_post_cabang', 'inviro_save_branch_location');

// Layanan Custom Post Type
function inviro_register_layanan() {
    $labels = array(
        'name'               => __('Layanan', 'inviro'),
        'singular_name'      => __('Layanan', 'inviro'),
        'menu_name'          => __('Layanan', 'inviro'),
        'add_new'            => __('Tambah Layanan', 'inviro'),
        'add_new_item'       => __('Tambah Layanan Baru', 'inviro'),
        'edit_item'          => __('Edit Layanan', 'inviro'),
        'new_item'           => __('Layanan Baru', 'inviro'),
        'view_item'          => __('Lihat Layanan', 'inviro'),
        'search_items'       => __('Cari Layanan', 'inviro'),
        'not_found'          => __('Layanan tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada layanan di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => false,  // Tidak ada single page
        'publicly_queryable'  => false,  // Tidak bisa diakses dari depan
        'show_ui'             => true,   // Tampil di admin
        'show_in_menu'        => true,
        'query_var'           => false,
        'capability_type'     => 'post',
        'has_archive'         => false,  // Tidak ada archive
        'hierarchical'        => false,
        'menu_position'       => 8,
        'menu_icon'           => 'dashicons-admin-tools',
        'supports'            => array('title', 'thumbnail'),
        'show_in_rest'        => true,
    );
    
    register_post_type('layanan', $args);
}
add_action('init', 'inviro_register_layanan');

/**
 * Add Meta Box for Layanan External URL
 */
function inviro_layanan_meta_boxes() {
    add_meta_box(
        'inviro_layanan_url',
        __('Link Eksternal', 'inviro'),
        'inviro_layanan_url_callback',
        'layanan',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_layanan_meta_boxes');

/**
 * Meta Box Callback for Layanan URL
 */
function inviro_layanan_url_callback($post) {
    wp_nonce_field('inviro_layanan_url_nonce', 'inviro_layanan_url_nonce');
    $external_url = get_post_meta($post->ID, '_layanan_external_url', true);
    ?>
    <p>
        <label for="layanan_external_url"><?php _e('URL Eksternal (Link ke domain lain):', 'inviro'); ?></label><br>
        <input type="url" name="layanan_external_url" id="layanan_external_url" value="<?php echo esc_attr($external_url); ?>" style="width: 100%; padding: 8px;" placeholder="https://example.com/layanan">
        <br><small><?php _e('Masukkan URL lengkap termasuk http:// atau https://', 'inviro'); ?></small>
    </p>
    <?php
}

/**
 * Save Layanan URL Meta
 */
function inviro_save_layanan_url($post_id) {
    if (!isset($_POST['inviro_layanan_url_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['inviro_layanan_url_nonce'], 'inviro_layanan_url_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['layanan_external_url'])) {
        update_post_meta($post_id, '_layanan_external_url', esc_url_raw($_POST['layanan_external_url']));
    }
}
add_action('save_post_layanan', 'inviro_save_layanan_url');

/**
 * Create Dummy Layanan Data
 */
function inviro_create_dummy_layanan() {
    // Check if dummy data already exists
    $existing_layanan = get_posts(array(
        'post_type' => 'layanan',
        'posts_per_page' => 1,
        'post_status' => 'any'
    ));
    
    if (!empty($existing_layanan)) {
        return; // Dummy data already exists
    }
    
    $dummy_layanan = array(
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
    
    foreach ($dummy_layanan as $layanan) {
        $post_id = wp_insert_post(array(
            'post_title'    => $layanan['title'],
            'post_status'   => 'publish',
            'post_type'     => 'layanan',
        ));
        
        if ($post_id) {
            update_post_meta($post_id, '_layanan_external_url', $layanan['url']);
        }
    }
}
add_action('admin_init', 'inviro_create_dummy_layanan');

// Proyek Pelanggan Custom Post Type
function inviro_register_proyek_pelanggan() {
    $labels = array(
        'name'               => __('Proyek Pelanggan', 'inviro'),
        'singular_name'      => __('Proyek', 'inviro'),
        'menu_name'          => __('Proyek Pelanggan', 'inviro'),
        'add_new'            => __('Tambah Proyek', 'inviro'),
        'add_new_item'       => __('Tambah Proyek Baru', 'inviro'),
        'edit_item'          => __('Edit Proyek', 'inviro'),
        'new_item'           => __('Proyek Baru', 'inviro'),
        'view_item'          => __('Lihat Proyek', 'inviro'),
        'search_items'       => __('Cari Proyek', 'inviro'),
        'not_found'          => __('Proyek tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada proyek di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'proyek-pelanggan'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 8,
        'menu_icon'           => 'dashicons-businessperson',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
        'taxonomies'          => array('region')
    );
    
    register_post_type('proyek_pelanggan', $args);
}
add_action('init', 'inviro_register_proyek_pelanggan');

/**
 * Register Region Taxonomy for Projects
 */
function inviro_register_region_taxonomy() {
    $labels = array(
        'name'              => __('Daerah', 'inviro'),
        'singular_name'     => __('Daerah', 'inviro'),
        'search_items'      => __('Cari Daerah', 'inviro'),
        'all_items'         => __('Semua Daerah', 'inviro'),
        'parent_item'       => __('Parent Daerah', 'inviro'),
        'parent_item_colon' => __('Parent Daerah:', 'inviro'),
        'edit_item'         => __('Edit Daerah', 'inviro'),
        'update_item'       => __('Update Daerah', 'inviro'),
        'add_new_item'      => __('Tambah Daerah Baru', 'inviro'),
        'new_item_name'     => __('Nama Daerah Baru', 'inviro'),
        'menu_name'         => __('Daerah', 'inviro'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'daerah'),
        'show_in_rest'      => true,
    );

    register_taxonomy('region', array('proyek_pelanggan'), $args);
    
    // Auto-create default regions
    if (!term_exists('Sumatra', 'region')) {
        wp_insert_term('Sumatra', 'region', array('slug' => 'sumatra'));
    }
    if (!term_exists('Jawa', 'region')) {
        wp_insert_term('Jawa', 'region', array('slug' => 'jawa'));
    }
    if (!term_exists('Kalimantan', 'region')) {
        wp_insert_term('Kalimantan', 'region', array('slug' => 'kalimantan'));
    }
    if (!term_exists('Maluku', 'region')) {
        wp_insert_term('Maluku', 'region', array('slug' => 'maluku'));
    }
    if (!term_exists('Nusa Tenggara', 'region')) {
        wp_insert_term('Nusa Tenggara', 'region', array('slug' => 'nusa-tenggara'));
    }
    if (!term_exists('Papua', 'region')) {
        wp_insert_term('Papua', 'region', array('slug' => 'papua'));
    }
    if (!term_exists('Sulawesi', 'region')) {
        wp_insert_term('Sulawesi', 'region', array('slug' => 'sulawesi'));
    }
}
add_action('init', 'inviro_register_region_taxonomy');

// SpareParts Custom Post Type
function inviro_register_spareparts() {
    $labels = array(
        'name'               => __('Spare Parts', 'inviro'),
        'singular_name'      => __('Spare Part', 'inviro'),
        'menu_name'          => __('Spare Parts', 'inviro'),
        'add_new'            => __('Tambah Spare Part', 'inviro'),
        'add_new_item'       => __('Tambah Spare Part Baru', 'inviro'),
        'edit_item'          => __('Edit Spare Part', 'inviro'),
        'new_item'           => __('Spare Part Baru', 'inviro'),
        'view_item'          => __('Lihat Spare Part', 'inviro'),
        'search_items'       => __('Cari Spare Part', 'inviro'),
        'not_found'          => __('Spare Part tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada spare part di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'spareparts'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 9,
        'menu_icon'           => 'dashicons-admin-tools',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
    );
    
    register_post_type('spareparts', $args);
    
    // Register taxonomy for spare parts categories
    $taxonomy_labels = array(
        'name'              => __('Kategori Spare Parts', 'inviro'),
        'singular_name'     => __('Kategori', 'inviro'),
        'search_items'      => __('Cari Kategori', 'inviro'),
        'all_items'         => __('Semua Kategori', 'inviro'),
        'parent_item'       => __('Kategori Induk', 'inviro'),
        'parent_item_colon' => __('Kategori Induk:', 'inviro'),
        'edit_item'         => __('Edit Kategori', 'inviro'),
        'update_item'       => __('Update Kategori', 'inviro'),
        'add_new_item'      => __('Tambah Kategori Baru', 'inviro'),
        'new_item_name'     => __('Nama Kategori Baru', 'inviro'),
        'menu_name'         => __('Kategori', 'inviro'),
    );
    
    register_taxonomy('sparepart_category', array('spareparts'), array(
        'hierarchical'      => true,
        'labels'            => $taxonomy_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'kategori-sparepart'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'inviro_register_spareparts');

// Register Spare Part Reviews Custom Post Type
function inviro_register_sparepart_reviews() {
    $labels = array(
        'name'               => __('Ulasan Spare Parts', 'inviro'),
        'singular_name'      => __('Ulasan', 'inviro'),
        'menu_name'          => __('Ulasan Spare Parts', 'inviro'),
        'add_new'            => __('Tambah Ulasan', 'inviro'),
        'add_new_item'       => __('Tambah Ulasan Baru', 'inviro'),
        'edit_item'          => __('Edit Ulasan', 'inviro'),
        'new_item'           => __('Ulasan Baru', 'inviro'),
        'view_item'          => __('Lihat Ulasan', 'inviro'),
        'search_items'       => __('Cari Ulasan', 'inviro'),
        'not_found'          => __('Ulasan tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada ulasan di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => 'edit.php?post_type=spareparts', // Submenu di bawah Spare Parts
        'query_var'           => true,
        'rewrite'             => false,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'supports'            => array('title', 'editor'),
        'show_in_rest'        => false,
    );
    
    register_post_type('sparepart_review', $args);
}
add_action('init', 'inviro_register_sparepart_reviews');

// Add meta boxes for reviews
function inviro_add_review_meta_boxes() {
    add_meta_box(
        'review_info',
        'Informasi Ulasan',
        'inviro_review_info_callback',
        'sparepart_review',
        'side',
        'default'
    );
    
    add_meta_box(
        'review_status',
        'Status Ulasan',
        'inviro_review_status_callback',
        'sparepart_review',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_review_meta_boxes');

function inviro_review_info_callback($post) {
    $sparepart_id = get_post_meta($post->ID, '_review_sparepart_id', true);
    $reviewer_name = get_post_meta($post->ID, '_reviewer_name', true);
    $reviewer_email = get_post_meta($post->ID, '_reviewer_email', true);
    $rating = get_post_meta($post->ID, '_review_rating', true);
    ?>
    <div style="padding: 10px 0;">
        <p><strong>Nama:</strong><br><?php echo esc_html($reviewer_name); ?></p>
        <p><strong>Email:</strong><br><?php echo esc_html($reviewer_email); ?></p>
        <p><strong>Rating:</strong><br>
            <?php 
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $rating ? '★' : '☆';
            }
            ?> (<?php echo esc_html($rating); ?>/5)
        </p>
        <p><strong>Produk:</strong><br>
            <?php 
            $product_type = get_post_meta($post->ID, '_review_product_type', true);
            $product_type = $product_type ? $product_type : 'spareparts';
            if ($sparepart_id) : 
                if ($product_type == 'paket_usaha') {
                    $paket = get_post($sparepart_id);
                    if ($paket) : ?>
                        <a href="<?php echo get_edit_post_link($sparepart_id); ?>" target="_blank">
                            <?php echo esc_html($paket->post_title); ?>
                        </a>
                    <?php else : ?>
                        <?php echo esc_html($sparepart_id); ?>
                    <?php endif;
                } else {
                    $sparepart = get_post($sparepart_id);
                    if ($sparepart) : ?>
                        <a href="<?php echo get_edit_post_link($sparepart_id); ?>" target="_blank">
                            <?php echo esc_html($sparepart->post_title); ?>
                        </a>
                    <?php else : ?>
                        <?php echo esc_html($sparepart_id); ?>
                    <?php endif;
                }
            endif; ?>
        </p>
    </div>
    <?php
}

function inviro_review_status_callback($post) {
    $status = get_post_meta($post->ID, '_review_status', true);
    if (!$status) $status = 'pending';
    ?>
    <div style="padding: 10px 0;">
        <select name="review_status" style="width: 100%; padding: 10px; font-size: 14px;">
            <option value="approved" <?php selected($status, 'approved'); ?>>Disetujui (Tampilkan)</option>
            <option value="pending" <?php selected($status, 'pending'); ?>>Menunggu Persetujuan</option>
            <option value="rejected" <?php selected($status, 'rejected'); ?>>Ditolak (Tidak Tampilkan)</option>
        </select>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Pilih status ulasan. Hanya ulasan yang disetujui yang akan ditampilkan di halaman produk.
        </p>
    </div>
    <?php
}

function inviro_save_review_meta($post_id) {
    if (get_post_type($post_id) !== 'sparepart_review') {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['review_status'])) {
        update_post_meta($post_id, '_review_status', sanitize_text_field($_POST['review_status']));
    }
}
add_action('save_post_sparepart_review', 'inviro_save_review_meta');

// Handle review form submission
function inviro_handle_review_submission() {
    if (!isset($_POST['review_nonce']) || !wp_verify_nonce($_POST['review_nonce'], 'submit_review')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        return;
    }
    
    $sparepart_id_raw = $_POST['sparepart_id'];
    $is_dummy = isset($_POST['is_dummy']) && $_POST['is_dummy'] == '1';
    $sparepart_id = $is_dummy ? $sparepart_id_raw : intval($sparepart_id_raw);
    $reviewer_name = sanitize_text_field($_POST['reviewer_name']);
    $reviewer_email = sanitize_email($_POST['reviewer_email']);
    $rating = intval($_POST['rating']);
    $review_content = sanitize_textarea_field($_POST['review_content']);
    
    if (empty($reviewer_name) || empty($reviewer_email) || empty($review_content) || $rating < 1 || $rating > 5) {
        wp_send_json_error(array('message' => 'Semua field harus diisi dengan benar'));
        return;
    }
    
    $review_id = wp_insert_post(array(
        'post_type'    => 'sparepart_review',
        'post_title'   => 'Ulasan dari ' . $reviewer_name,
        'post_content' => $review_content,
        'post_status'  => 'publish',
    ));
    
    if ($review_id) {
        update_post_meta($review_id, '_review_sparepart_id', $sparepart_id);
        update_post_meta($review_id, '_review_is_dummy', $is_dummy ? '1' : '0');
        update_post_meta($review_id, '_review_product_type', 'spareparts');
        update_post_meta($review_id, '_reviewer_name', $reviewer_name);
        update_post_meta($review_id, '_reviewer_email', $reviewer_email);
        update_post_meta($review_id, '_review_rating', $rating);
        update_post_meta($review_id, '_review_status', 'pending');
        
        wp_send_json_success(array('message' => 'Ulasan Anda telah dikirim dan menunggu persetujuan admin.'));
    } else {
        wp_send_json_error(array('message' => 'Gagal mengirim ulasan. Silakan coba lagi.'));
    }
}
add_action('wp_ajax_submit_sparepart_review', 'inviro_handle_review_submission');
add_action('wp_ajax_nopriv_submit_sparepart_review', 'inviro_handle_review_submission');

// Handle paket usaha review form submission
function inviro_handle_paket_review_submission() {
    if (!isset($_POST['review_nonce']) || !wp_verify_nonce($_POST['review_nonce'], 'submit_review')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        return;
    }
    
    $paket_id_raw = $_POST['paket_id'];
    $is_dummy = isset($_POST['is_dummy']) && $_POST['is_dummy'] == '1';
    $paket_id = $is_dummy ? $paket_id_raw : intval($paket_id_raw);
    $reviewer_name = sanitize_text_field($_POST['reviewer_name']);
    $reviewer_email = sanitize_email($_POST['reviewer_email']);
    $rating = intval($_POST['rating']);
    $review_content = sanitize_textarea_field($_POST['review_content']);
    
    if (empty($reviewer_name) || empty($reviewer_email) || empty($review_content) || $rating < 1 || $rating > 5) {
        wp_send_json_error(array('message' => 'Semua field harus diisi dengan benar'));
        return;
    }
    
    $review_id = wp_insert_post(array(
        'post_type'    => 'paket_usaha_review',
        'post_title'   => 'Ulasan dari ' . $reviewer_name,
        'post_content' => $review_content,
        'post_status'  => 'publish',
    ));
    
    if ($review_id) {
        update_post_meta($review_id, '_review_sparepart_id', $paket_id);
        update_post_meta($review_id, '_review_is_dummy', $is_dummy ? '1' : '0');
        update_post_meta($review_id, '_review_product_type', 'paket_usaha');
        update_post_meta($review_id, '_reviewer_name', $reviewer_name);
        update_post_meta($review_id, '_reviewer_email', $reviewer_email);
        update_post_meta($review_id, '_review_rating', $rating);
        update_post_meta($review_id, '_review_status', 'pending');
        
        wp_send_json_success(array('message' => 'Ulasan Anda telah dikirim dan menunggu persetujuan admin.'));
    } else {
        wp_send_json_error(array('message' => 'Gagal mengirim ulasan. Silakan coba lagi.'));
    }
}
add_action('wp_ajax_submit_paket_review', 'inviro_handle_paket_review_submission');
add_action('wp_ajax_nopriv_submit_paket_review', 'inviro_handle_paket_review_submission');

// Register Paket Usaha Reviews Custom Post Type
function inviro_register_paket_usaha_reviews() {
    $labels = array(
        'name'               => __('Ulasan Paket Usaha', 'inviro'),
        'singular_name'      => __('Ulasan', 'inviro'),
        'menu_name'          => __('Ulasan Paket Usaha', 'inviro'),
        'add_new'            => __('Tambah Ulasan', 'inviro'),
        'add_new_item'       => __('Tambah Ulasan Baru', 'inviro'),
        'edit_item'          => __('Edit Ulasan', 'inviro'),
        'new_item'           => __('Ulasan Baru', 'inviro'),
        'view_item'          => __('Lihat Ulasan', 'inviro'),
        'search_items'       => __('Cari Ulasan', 'inviro'),
        'not_found'          => __('Ulasan tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada ulasan di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => 'edit.php?post_type=paket_usaha', // Submenu di bawah Paket Usaha
        'query_var'           => true,
        'rewrite'             => false,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'supports'            => array('title', 'editor'),
        'show_in_rest'        => false,
    );
    
    register_post_type('paket_usaha_review', $args);
}
add_action('init', 'inviro_register_paket_usaha_reviews');

// Add meta boxes for paket usaha reviews
function inviro_add_paket_review_meta_boxes() {
    add_meta_box(
        'paket_review_info',
        'Informasi Ulasan',
        'inviro_paket_review_info_callback',
        'paket_usaha_review',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_review_status',
        'Status Ulasan',
        'inviro_paket_review_status_callback',
        'paket_usaha_review',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_paket_review_meta_boxes');

function inviro_paket_review_info_callback($post) {
    $paket_id = get_post_meta($post->ID, '_review_sparepart_id', true);
    $reviewer_name = get_post_meta($post->ID, '_reviewer_name', true);
    $reviewer_email = get_post_meta($post->ID, '_reviewer_email', true);
    $rating = get_post_meta($post->ID, '_review_rating', true);
    ?>
    <div style="padding: 10px 0;">
        <p><strong>Nama:</strong><br><?php echo esc_html($reviewer_name); ?></p>
        <p><strong>Email:</strong><br><?php echo esc_html($reviewer_email); ?></p>
        <p><strong>Rating:</strong><br>
            <?php 
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $rating ? '★' : '☆';
            }
            ?> (<?php echo esc_html($rating); ?>/5)
        </p>
        <p><strong>Paket:</strong><br>
            <?php 
            if ($paket_id) : 
                // Check if it's dummy data
                $is_dummy = get_post_meta($post->ID, '_review_is_dummy', true);
                if ($is_dummy == '1') {
                    echo esc_html($paket_id);
                } else {
                    $paket = get_post($paket_id);
                    if ($paket) : ?>
                        <a href="<?php echo get_edit_post_link($paket_id); ?>" target="_blank">
                            <?php echo esc_html($paket->post_title); ?>
                        </a>
                    <?php else : ?>
                        <?php echo esc_html($paket_id); ?>
                    <?php endif;
                }
            endif; ?>
        </p>
    </div>
    <?php
}

function inviro_paket_review_status_callback($post) {
    $status = get_post_meta($post->ID, '_review_status', true);
    if (!$status) $status = 'pending';
    ?>
    <div style="padding: 10px 0;">
        <select name="review_status" style="width: 100%; padding: 10px; font-size: 14px;">
            <option value="approved" <?php selected($status, 'approved'); ?>>Disetujui (Tampilkan)</option>
            <option value="pending" <?php selected($status, 'pending'); ?>>Menunggu Persetujuan</option>
            <option value="rejected" <?php selected($status, 'rejected'); ?>>Ditolak (Tidak Tampilkan)</option>
        </select>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Pilih status ulasan. Hanya ulasan yang disetujui yang akan ditampilkan di halaman produk.
        </p>
    </div>
    <?php
}

function inviro_save_paket_review_meta($post_id) {
    if (get_post_type($post_id) !== 'paket_usaha_review') {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['review_status'])) {
        update_post_meta($post_id, '_review_status', sanitize_text_field($_POST['review_status']));
    }
}
add_action('save_post_paket_usaha_review', 'inviro_save_paket_review_meta');

// Artikel Custom Post Type
function inviro_register_artikel() {
    $labels = array(
        'name'               => __('Artikel', 'inviro'),
        'singular_name'      => __('Artikel', 'inviro'),
        'menu_name'          => __('Artikel', 'inviro'),
        'add_new'            => __('Tambah Artikel', 'inviro'),
        'add_new_item'       => __('Tambah Artikel Baru', 'inviro'),
        'edit_item'          => __('Edit Artikel', 'inviro'),
        'new_item'           => __('Artikel Baru', 'inviro'),
        'view_item'          => __('Lihat Artikel', 'inviro'),
        'search_items'       => __('Cari Artikel', 'inviro'),
        'not_found'          => __('Artikel tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada artikel di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'artikel'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 10,
        'menu_icon'           => 'dashicons-welcome-write-blog',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'show_in_rest'        => true,
    );
    
    register_post_type('artikel', $args);
}
add_action('init', 'inviro_register_artikel');

// Unduhan (Downloads) Custom Post Type
function inviro_register_unduhan() {
    $labels = array(
        'name'               => __('Unduhan', 'inviro'),
        'singular_name'      => __('Unduhan', 'inviro'),
        'menu_name'          => __('Unduhan', 'inviro'),
        'add_new'            => __('Tambah Unduhan', 'inviro'),
        'add_new_item'       => __('Tambah Unduhan Baru', 'inviro'),
        'edit_item'          => __('Edit Unduhan', 'inviro'),
        'new_item'           => __('Unduhan Baru', 'inviro'),
        'view_item'          => __('Lihat Unduhan', 'inviro'),
        'search_items'       => __('Cari Unduhan', 'inviro'),
        'not_found'          => __('Unduhan tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada unduhan di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'unduhan'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 11,
        'menu_icon'           => 'dashicons-download',
        'supports'            => array('title', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
    );
    
    register_post_type('unduhan', $args);
}
add_action('init', 'inviro_register_unduhan');

/**
 * Add custom meta boxes for Products
 */
function inviro_add_product_meta_boxes() {
    add_meta_box(
        'inviro_product_description',
        __('Deskripsi Produk', 'inviro'),
        'inviro_product_description_callback',
        'produk',
        'normal',
        'high'
    );
    
    add_meta_box(
        'inviro_product_price',
        __('Harga Produk', 'inviro'),
        'inviro_product_price_callback',
        'produk',
        'normal',
        'high'
    );
    
    add_meta_box(
        'inviro_product_buy_url',
        __('URL Tombol Beli', 'inviro'),
        'inviro_product_buy_url_callback',
        'produk',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_product_meta_boxes');

/**
 * Product Description Meta Box Callback
 */
function inviro_product_description_callback($post) {
    wp_nonce_field('inviro_product_meta_nonce', 'inviro_product_meta_nonce');
    $description = get_post_meta($post->ID, '_product_description', true);
    ?>
    <p>
        <label for="product_description"><?php _e('Deskripsi Produk:', 'inviro'); ?></label><br>
        <textarea name="product_description" id="product_description" rows="5" style="width: 100%; padding: 8px;" placeholder="Mesin RO 20.000 GPD dengan kapasitas setara 2000 liter/jam..."><?php echo esc_textarea($description); ?></textarea>
    </p>
    <p class="description">
        <?php _e('Tulis deskripsi lengkap tentang produk ini.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Product Price Meta Box Callback
 */
function inviro_product_price_callback($post) {
    $price = get_post_meta($post->ID, '_product_price', true);
    $original_price = get_post_meta($post->ID, '_product_original_price', true);
    ?>
    <p>
        <label for="product_price"><?php _e('Harga Jual:', 'inviro'); ?></label><br>
        <input type="text" name="product_price" id="product_price" value="<?php echo esc_attr($price); ?>" placeholder="Rp. 5.000.000" style="width: 100%; padding: 8px;" />
    </p>
    <p>
        <label for="product_original_price"><?php _e('Harga Asli (Opsional - untuk coret harga):', 'inviro'); ?></label><br>
        <input type="text" name="product_original_price" id="product_original_price" value="<?php echo esc_attr($original_price); ?>" placeholder="Rp. 6.000.000" style="width: 100%; padding: 8px;" />
    </p>
    <p class="description">
        <?php _e('Harga asli akan ditampilkan dengan coretan jika diisi. Biarkan kosong jika tidak ada diskon.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Product Buy URL Meta Box Callback
 */
function inviro_product_buy_url_callback($post) {
    $buy_url = get_post_meta($post->ID, '_product_buy_url', true);
    ?>
    <p>
        <label for="product_buy_url"><?php _e('URL untuk tombol beli:', 'inviro'); ?></label><br>
        <input type="url" name="product_buy_url" id="product_buy_url" value="<?php echo esc_attr($buy_url); ?>" placeholder="https://wa.me/621234567890" style="width: 100%; padding: 8px;" />
    </p>
    <p class="description">
        <?php _e('Contoh: https://wa.me/621234567890 atau https://tokopedia.com/inviro/produk', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Save Product Meta
 */
function inviro_save_product_meta($post_id) {
    // Check post type
    if (get_post_type($post_id) !== 'produk') {
        return;
    }
    
    if (!isset($_POST['inviro_product_meta_nonce']) || !wp_verify_nonce($_POST['inviro_product_meta_nonce'], 'inviro_product_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['product_description'])) {
        update_post_meta($post_id, '_product_description', sanitize_textarea_field($_POST['product_description']));
    }
    
    if (isset($_POST['product_price'])) {
        update_post_meta($post_id, '_product_price', sanitize_text_field($_POST['product_price']));
    }
    
    if (isset($_POST['product_original_price'])) {
        update_post_meta($post_id, '_product_original_price', sanitize_text_field($_POST['product_original_price']));
    }
    
    if (isset($_POST['product_buy_url'])) {
        update_post_meta($post_id, '_product_buy_url', esc_url_raw($_POST['product_buy_url']));
    }
}
add_action('save_post_produk', 'inviro_save_product_meta');

/**
 * Add custom meta boxes for Paket Usaha
 */
function inviro_add_paket_usaha_meta_boxes() {
    add_meta_box(
        'inviro_paket_description',
        __('Deskripsi Paket', 'inviro'),
        'inviro_paket_description_callback',
        'paket_usaha',
        'normal',
        'high'
    );
    
    add_meta_box(
        'paket_price',
        '💰 Harga Paket',
        'inviro_paket_price_callback',
        'paket_usaha',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_sku',
        '🏷️ Kode SKU',
        'inviro_paket_sku_callback',
        'paket_usaha',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_promo',
        '🎯 Promo',
        'inviro_paket_promo_callback',
        'paket_usaha',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_original_price',
        '💰 Harga Asli (untuk Promo)',
        'inviro_paket_original_price_callback',
        'paket_usaha',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_gallery',
        '🖼️ Gallery Images',
        'inviro_paket_gallery_callback',
        'paket_usaha',
        'normal',
        'high'
    );
    
    add_meta_box(
        'paket_specifications',
        '⚙️ Spesifikasi',
        'inviro_paket_specifications_callback',
        'paket_usaha',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_paket_usaha_meta_boxes');

/**
 * Paket Usaha Description Meta Box Callback
 */
function inviro_paket_description_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $description = get_post_meta($post->ID, '_paket_description', true);
    ?>
    <p>
        <label for="paket_description"><?php _e('Deskripsi Paket:', 'inviro'); ?></label><br>
        <textarea name="paket_description" id="paket_description" rows="5" style="width: 100%; padding: 8px;" placeholder="DAMIU Paket A dengan kapasitas..."><?php echo esc_textarea($description); ?></textarea>
    </p>
    <p class="description">
        <?php _e('Tulis deskripsi lengkap tentang paket usaha ini.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Paket Usaha Price Meta Box Callback
 */
function inviro_paket_price_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $price = get_post_meta($post->ID, '_paket_price', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="paket_price" name="paket_price" 
               value="<?php echo esc_attr($price); ?>" 
               placeholder="5000000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Harga dalam Rupiah (tanpa titik/koma)
        </p>
    </div>
    <?php
}

function inviro_paket_sku_callback($post) {
    $sku = get_post_meta($post->ID, '_paket_sku', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="text" id="paket_sku" name="paket_sku" 
               value="<?php echo esc_attr($sku); ?>" 
               placeholder="PKT-001" 
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Kode unik untuk paket usaha
        </p>
    </div>
    <?php
}

function inviro_paket_promo_callback($post) {
    $promo = get_post_meta($post->ID, '_paket_promo', true);
    ?>
    <div style="padding: 10px 0;">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <input type="checkbox" id="paket_promo" name="paket_promo" value="1" 
                   <?php checked($promo, '1'); ?>
                   style="width: 20px; height: 20px; cursor: pointer;">
            <span style="font-size: 14px; font-weight: 500;">Tandai sebagai Promo</span>
        </label>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Centang jika paket ini sedang dalam promo
        </p>
    </div>
    <?php
}

function inviro_paket_original_price_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $original_price = get_post_meta($post->ID, '_paket_original_price', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="paket_original_price" name="paket_original_price" 
               value="<?php echo esc_attr($original_price); ?>" 
               placeholder="6000000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Harga sebelum promo (akan dicoret). Kosongkan jika tidak ada promo.
        </p>
    </div>
    <?php
}

/**
 * Save Paket Usaha Meta
 */
function inviro_save_paket_usaha_meta($post_id) {
    // Check post type
    if (get_post_type($post_id) !== 'paket_usaha') {
        return;
    }
    
    if (!isset($_POST['inviro_paket_meta_nonce']) || !wp_verify_nonce($_POST['inviro_paket_meta_nonce'], 'inviro_paket_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['paket_description'])) {
        update_post_meta($post_id, '_paket_description', sanitize_textarea_field($_POST['paket_description']));
    }
    
    if (isset($_POST['paket_price'])) {
        update_post_meta($post_id, '_paket_price', absint($_POST['paket_price']));
    }
    
    if (isset($_POST['paket_sku'])) {
        update_post_meta($post_id, '_paket_sku', sanitize_text_field($_POST['paket_sku']));
    }
    
    if (isset($_POST['paket_promo'])) {
        update_post_meta($post_id, '_paket_promo', '1');
    } else {
        update_post_meta($post_id, '_paket_promo', '0');
    }
    
    if (isset($_POST['paket_original_price'])) {
        update_post_meta($post_id, '_paket_original_price', absint($_POST['paket_original_price']));
    }
    
    if (isset($_POST['paket_gallery'])) {
        update_post_meta($post_id, '_paket_gallery', sanitize_text_field($_POST['paket_gallery']));
    }
    
    if (isset($_POST['spec_label']) && isset($_POST['spec_value'])) {
        $specs = array();
        foreach ($_POST['spec_label'] as $index => $label) {
            if (!empty($label) && !empty($_POST['spec_value'][$index])) {
                $specs[] = array(
                    'label' => sanitize_text_field($label),
                    'value' => sanitize_text_field($_POST['spec_value'][$index])
                );
            }
        }
        update_post_meta($post_id, '_paket_specifications', json_encode($specs));
    }
}
add_action('save_post_paket_usaha', 'inviro_save_paket_usaha_meta');

function inviro_paket_gallery_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $gallery_ids = get_post_meta($post->ID, '_paket_gallery', true);
    $gallery_ids = $gallery_ids ? explode(',', $gallery_ids) : array();
    ?>
    <div style="padding: 10px 0;">
        <input type="hidden" id="paket_gallery" name="paket_gallery" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>">
        <div id="gallery-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
            <?php foreach ($gallery_ids as $img_id) : 
                if ($img_id) :
                    $img_url = wp_get_attachment_image_url($img_id, 'thumbnail');
            ?>
                <div class="gallery-item" data-id="<?php echo esc_attr($img_id); ?>" style="position: relative; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
                    <img src="<?php echo esc_url($img_url); ?>" style="width: 100%; height: 150px; object-fit: cover; display: block;">
                    <button type="button" class="remove-gallery-item" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>
                </div>
            <?php 
                endif;
            endforeach; ?>
        </div>
        <button type="button" id="add-gallery-images" class="button" style="margin-top: 15px;">Tambah Gambar ke Gallery</button>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Tambahkan beberapa gambar untuk ditampilkan di halaman detail paket
        </p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var galleryFrame;
        $('#add-gallery-images').on('click', function(e) {
            e.preventDefault();
            if (galleryFrame) {
                galleryFrame.open();
                return;
            }
            galleryFrame = wp.media({
                title: 'Pilih Gambar Gallery',
                button: { text: 'Gunakan Gambar' },
                multiple: true
            });
            galleryFrame.on('select', function() {
                var selection = galleryFrame.state().get('selection');
                var galleryIds = $('#paket_gallery').val().split(',').filter(Boolean);
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    if (galleryIds.indexOf(attachment.id.toString()) === -1) {
                        galleryIds.push(attachment.id);
                        $('#gallery-preview').append(
                            '<div class="gallery-item" data-id="' + attachment.id + '" style="position: relative; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">' +
                            '<img src="' + attachment.sizes.thumbnail.url + '" style="width: 100%; height: 150px; object-fit: cover; display: block;">' +
                            '<button type="button" class="remove-gallery-item" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>' +
                            '</div>'
                        );
                    }
                });
                $('#paket_gallery').val(galleryIds.join(','));
            });
            galleryFrame.open();
        });
        $(document).on('click', '.remove-gallery-item', function() {
            var item = $(this).closest('.gallery-item');
            var imgId = item.data('id').toString();
            var galleryIds = $('#paket_gallery').val().split(',').filter(Boolean);
            galleryIds = galleryIds.filter(function(id) { return id !== imgId; });
            $('#paket_gallery').val(galleryIds.join(','));
            item.remove();
        });
    });
    </script>
    <?php
}

function inviro_paket_specifications_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $specs = get_post_meta($post->ID, '_paket_specifications', true);
    $specs = $specs ? json_decode($specs, true) : array();
    if (empty($specs)) {
        $specs = array(array('label' => '', 'value' => ''));
    }
    ?>
    <div style="padding: 10px 0;">
        <div id="specifications-list">
            <?php foreach ($specs as $index => $spec) : ?>
                <div class="spec-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                    <input type="text" name="spec_label[]" placeholder="Label (contoh: Kapasitas)" 
                           value="<?php echo esc_attr($spec['label']); ?>"
                           style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    <input type="text" name="spec_value[]" placeholder="Value (contoh: 10.000 GPD)" 
                           value="<?php echo esc_attr($spec['value']); ?>"
                           style="flex: 2; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    <button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-spec" class="button" style="margin-top: 10px;">Tambah Spesifikasi</button>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Tambahkan spesifikasi paket (contoh: Kapasitas, Komponen, Garansi, dll)
        </p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#add-spec').on('click', function() {
            $('#specifications-list').append(
                '<div class="spec-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">' +
                '<input type="text" name="spec_label[]" placeholder="Label (contoh: Kapasitas)" style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">' +
                '<input type="text" name="spec_value[]" placeholder="Value (contoh: 10.000 GPD)" style="flex: 2; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">' +
                '<button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>' +
                '</div>'
            );
        });
        $(document).on('click', '.remove-spec', function() {
            $(this).closest('.spec-row').remove();
        });
    });
    </script>
    <?php
}

/**
 * Add custom meta boxes for Testimonials
 */
function inviro_add_testimonial_meta_boxes() {
    add_meta_box(
        'inviro_testimonial_info',
        __('Informasi Testimoni', 'inviro'),
        'inviro_testimonial_info_callback',
        'testimoni',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_testimonial_meta_boxes');

/**
 * Testimonial Info Meta Box Callback
 */
function inviro_testimonial_info_callback($post) {
    wp_nonce_field('inviro_testimonial_meta_nonce', 'inviro_testimonial_meta_nonce');
    
    $customer_name = get_post_meta($post->ID, '_testimonial_customer_name', true);
    $rating = get_post_meta($post->ID, '_testimonial_rating', true);
    $message = get_post_meta($post->ID, '_testimonial_message', true);
    $date = get_post_meta($post->ID, '_testimonial_date', true);
    
    if (!$date) {
        $date = date('d / m / Y');
    }
    ?>
    <p>
        <label for="testimonial_customer_name"><?php _e('Nama Pelanggan:', 'inviro'); ?></label><br>
        <input type="text" name="testimonial_customer_name" id="testimonial_customer_name" value="<?php echo esc_attr($customer_name); ?>" placeholder="Robert R." style="width: 100%; padding: 8px;" required />
    </p>
    
    <p>
        <label for="testimonial_rating"><?php _e('Rating (Bintang):', 'inviro'); ?></label><br>
        <select name="testimonial_rating" id="testimonial_rating" style="width: 100%; padding: 8px;">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>><?php echo $i; ?> Bintang</option>
            <?php endfor; ?>
        </select>
    </p>
    
    <p>
        <label for="testimonial_message"><?php _e('Pesan Testimoni:', 'inviro'); ?></label><br>
        <textarea name="testimonial_message" id="testimonial_message" rows="5" style="width: 100%; padding: 8px;" placeholder="Wow... I am very happy to use this Service, it turned out to be more than my expectations Inviro always the best."><?php echo esc_textarea($message); ?></textarea>
    </p>
    
    <p>
        <label for="testimonial_date"><?php _e('Tanggal Testimoni:', 'inviro'); ?></label><br>
        <input type="text" name="testimonial_date" id="testimonial_date" value="<?php echo esc_attr($date); ?>" placeholder="1 / 10 / 2025" style="width: 100%; padding: 8px;" />
    </p>
    
    <p class="description">
        <?php _e('Foto profil pelanggan dapat diatur di bagian "Featured Image" di sebelah kanan.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Save Testimonial Meta
 */
function inviro_save_testimonial_meta($post_id) {
    // Check post type
    if (get_post_type($post_id) !== 'testimoni') {
        return;
    }
    
    if (!isset($_POST['inviro_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['inviro_testimonial_meta_nonce'], 'inviro_testimonial_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['testimonial_customer_name'])) {
        update_post_meta($post_id, '_testimonial_customer_name', sanitize_text_field($_POST['testimonial_customer_name']));
    }
    
    if (isset($_POST['testimonial_rating'])) {
        update_post_meta($post_id, '_testimonial_rating', intval($_POST['testimonial_rating']));
    }
    
    if (isset($_POST['testimonial_message'])) {
        update_post_meta($post_id, '_testimonial_message', sanitize_textarea_field($_POST['testimonial_message']));
    }
    
    if (isset($_POST['testimonial_date'])) {
        update_post_meta($post_id, '_testimonial_date', sanitize_text_field($_POST['testimonial_date']));
    }
}
add_action('save_post_testimoni', 'inviro_save_testimonial_meta');

/**
 * Add custom meta boxes for Branches
 */
function inviro_add_branch_meta_boxes() {
    add_meta_box(
        'branch_address',
        __('Alamat Lengkap', 'inviro'),
        'inviro_branch_address_callback',
        'cabang',
        'normal',
        'high'
    );
    
    add_meta_box(
        'branch_phone',
        __('Nomor Telepon', 'inviro'),
        'inviro_branch_phone_callback',
        'cabang',
        'normal',
        'high'
    );
    
    add_meta_box(
        'branch_map',
        __('URL Google Maps', 'inviro'),
        'inviro_branch_map_callback',
        'cabang',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_branch_meta_boxes');

function inviro_branch_address_callback($post) {
    wp_nonce_field('inviro_branch_meta', 'inviro_branch_meta_nonce');
    $address = get_post_meta($post->ID, '_branch_address', true);
    echo '<textarea name="branch_address" style="width: 100%; padding: 8px; min-height: 100px;">' . esc_textarea($address) . '</textarea>';
}

function inviro_branch_phone_callback($post) {
    $phone = get_post_meta($post->ID, '_branch_phone', true);
    echo '<input type="text" name="branch_phone" value="' . esc_attr($phone) . '" placeholder="+62 123 456 7890" style="width: 100%; padding: 8px;" />';
}

function inviro_branch_map_callback($post) {
    $map = get_post_meta($post->ID, '_branch_map', true);
    echo '<input type="url" name="branch_map" value="' . esc_url($map) . '" placeholder="https://maps.google.com/..." style="width: 100%; padding: 8px;" />';
}

function inviro_save_branch_meta($post_id) {
    // Check post type
    if (get_post_type($post_id) !== 'cabang') {
        return;
    }
    
    if (!isset($_POST['inviro_branch_meta_nonce']) || !wp_verify_nonce($_POST['inviro_branch_meta_nonce'], 'inviro_branch_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['branch_address'])) {
        update_post_meta($post_id, '_branch_address', sanitize_textarea_field($_POST['branch_address']));
    }
    
    if (isset($_POST['branch_phone'])) {
        update_post_meta($post_id, '_branch_phone', sanitize_text_field($_POST['branch_phone']));
    }
    
    if (isset($_POST['branch_map'])) {
        update_post_meta($post_id, '_branch_map', esc_url_raw($_POST['branch_map']));
    }
}
add_action('save_post_cabang', 'inviro_save_branch_meta');

/**
 * Add meta boxes for Proyek Pelanggan
 */
function inviro_add_proyek_meta_boxes() {
    // Remove default editor (we'll add custom one)
    remove_post_type_support('proyek_pelanggan', 'editor');
    
    // Panduan Lengkap
    add_meta_box(
        'proyek_panduan',
        '📋 Panduan Mengisi Proyek',
        'inviro_proyek_panduan_callback',
        'proyek_pelanggan',
        'side',
        'high'
    );
    
    // Nama Klien
    add_meta_box(
        'proyek_client_name',
        '👤 Nama Klien',
        'inviro_proyek_client_name_callback',
        'proyek_pelanggan',
        'side',
        'default'
    );
    
    // Tanggal Proyek
    add_meta_box(
        'proyek_date',
        '📅 Tanggal Proyek',
        'inviro_proyek_date_callback',
        'proyek_pelanggan',
        'side',
        'default'
    );
    
    // Deskripsi Lengkap (Custom Editor)
    add_meta_box(
        'proyek_description',
        '📝 Deskripsi Proyek Lengkap',
        'inviro_proyek_description_callback',
        'proyek_pelanggan',
        'normal',
        'high'
    );
    
    // Ringkasan Singkat
    add_meta_box(
        'proyek_excerpt',
        '✍️ Ringkasan Singkat (untuk Card)',
        'inviro_proyek_excerpt_callback',
        'proyek_pelanggan',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_proyek_meta_boxes');

function inviro_proyek_panduan_callback($post) {
    ?>
    <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; border-left: 4px solid #2196F3;">
        <h4 style="margin-top: 0; color: #1976D2;">✅ Checklist:</h4>
        <ol style="margin: 0; padding-left: 20px; line-height: 1.8;">
            <li><strong>Judul:</strong> Nama proyek lengkap</li>
            <li><strong>Featured Image:</strong> Upload foto (sidebar kanan) ⚠️ WAJIB</li>
            <li><strong>Daerah:</strong> Pilih region (sidebar kanan) ⚠️ WAJIB</li>
            <li><strong>Deskripsi:</strong> Detail proyek (di bawah)</li>
            <li><strong>Ringkasan:</strong> 1-2 kalimat (di bawah)</li>
            <li><strong>Nama Klien:</strong> PIC/pemilik (di bawah)</li>
            <li><strong>Tanggal:</strong> Kapan selesai (di bawah)</li>
        </ol>
        <p style="margin-bottom: 0; margin-top: 10px; font-size: 13px; color: #666;">
            💡 <strong>Tip:</strong> Foto berkualitas tinggi akan meningkatkan kredibilitas!
        </p>
    </div>
    <?php
}

function inviro_proyek_client_name_callback($post) {
    wp_nonce_field('inviro_proyek_meta', 'inviro_proyek_meta_nonce');
    $client_name = get_post_meta($post->ID, '_proyek_client_name', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="text" id="proyek_client_name" name="proyek_client_name" 
               value="<?php echo esc_attr($client_name); ?>" 
               placeholder="Contoh: Oleh Agung INVIRO" 
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Nama pemilik/klien proyek. Contoh: "Oleh Agung INVIRO", "Ibu Ruth", dll.
        </p>
    </div>
    <?php
}

function inviro_proyek_date_callback($post) {
    $proyek_date = get_post_meta($post->ID, '_proyek_date', true);
    if (empty($proyek_date)) {
        $proyek_date = date('Y-m-d');
    }
    ?>
    <div style="padding: 10px 0;">
        <input type="date" id="proyek_date" name="proyek_date" 
               value="<?php echo esc_attr($proyek_date); ?>" 
               max="<?php echo date('Y-m-d'); ?>"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Tanggal pemasangan atau selesai proyek. Max: hari ini.
        </p>
    </div>
    <?php
}

function inviro_proyek_description_callback($post) {
    $description = get_post_meta($post->ID, '_proyek_description', true);
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 10px; color: #666; font-size: 13px;">
            📝 Tulis deskripsi lengkap proyek: lokasi detail, spesifikasi produk yang digunakan, proses pemasangan, dll. (Min. 50 karakter)
        </p>
        <textarea id="proyek_description" name="proyek_description" rows="10" 
                  style="width: 100%; padding: 12px; font-size: 14px; line-height: 1.6; border: 2px solid #ddd; border-radius: 6px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;"
                  placeholder="Contoh:&#10;&#10;Pemasangan Depot Air Minum Isi Ulang di Giwangan, Umbulharjo, Yogyakarta.&#10;&#10;Lokasi: Jl. Giwangan No. 123, Umbulharjo, Yogyakarta&#10;Nama Klien: Bapak Agung&#10;Produk yang digunakan: DAMIU Paket A&#10;&#10;Spesifikasi:&#10;- Filter Air 5 tahap&#10;- Pompa elektrik otomatis&#10;- Tangki penampungan 1000L&#10;&#10;Proses pemasangan berjalan lancar dalam 2 hari."><?php echo esc_textarea($description); ?></textarea>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            💡 Semakin detail, semakin profesional dan kredibel!
        </p>
    </div>
    <?php
}

function inviro_proyek_excerpt_callback($post) {
    $excerpt = get_post_meta($post->ID, '_proyek_excerpt', true);
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 10px; color: #666; font-size: 13px;">
            ✍️ Tulis ringkasan singkat 1-2 kalimat untuk tampilan card di Halaman Pelanggan (Max. 150 karakter)
        </p>
        <textarea id="proyek_excerpt" name="proyek_excerpt" rows="3" 
                  maxlength="150"
                  style="width: 100%; padding: 12px; font-size: 14px; line-height: 1.6; border: 2px solid #ddd; border-radius: 6px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;"
                  placeholder="Contoh: Pemasangan Depot Air Minum di Giwangan, Yogyakarta. Nama Konsumen: Bapak Agung."><?php echo esc_textarea($excerpt); ?></textarea>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            <span id="excerpt-counter">0</span>/150 karakter
        </p>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('proyek_excerpt');
        const counter = document.getElementById('excerpt-counter');
        
        function updateCounter() {
            counter.textContent = textarea.value.length;
            if (textarea.value.length >= 150) {
                counter.style.color = '#d32f2f';
                counter.style.fontWeight = 'bold';
            } else {
                counter.style.color = '#666';
                counter.style.fontWeight = 'normal';
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
    </script>
    <?php
}

function inviro_save_proyek_meta($post_id) {
    if (get_post_type($post_id) !== 'proyek_pelanggan') {
        return;
    }
    
    if (!isset($_POST['inviro_proyek_meta_nonce']) || !wp_verify_nonce($_POST['inviro_proyek_meta_nonce'], 'inviro_proyek_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['proyek_client_name'])) {
        update_post_meta($post_id, '_proyek_client_name', sanitize_text_field($_POST['proyek_client_name']));
    }
    
    if (isset($_POST['proyek_date'])) {
        update_post_meta($post_id, '_proyek_date', sanitize_text_field($_POST['proyek_date']));
    }
    
    if (isset($_POST['proyek_description'])) {
        update_post_meta($post_id, '_proyek_description', wp_kses_post($_POST['proyek_description']));
    }
    
    if (isset($_POST['proyek_excerpt'])) {
        $excerpt = sanitize_textarea_field($_POST['proyek_excerpt']);
        // Limit to 150 characters
        if (strlen($excerpt) > 150) {
            $excerpt = substr($excerpt, 0, 150);
        }
        update_post_meta($post_id, '_proyek_excerpt', $excerpt);
    }
}
add_action('save_post_proyek_pelanggan', 'inviro_save_proyek_meta');

/**
 * Add meta boxes for Spare Parts
 */
function inviro_add_sparepart_meta_boxes() {
    add_meta_box(
        'sparepart_price',
        '💰 Harga Spare Part',
        'inviro_sparepart_price_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_stock',
        '📦 Stok',
        'inviro_sparepart_stock_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_sku',
        '🏷️ Kode SKU',
        'inviro_sparepart_sku_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_promo',
        '🎯 Promo',
        'inviro_sparepart_promo_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_original_price',
        '💰 Harga Asli (untuk Promo)',
        'inviro_sparepart_original_price_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_gallery',
        '🖼️ Gallery Images',
        'inviro_sparepart_gallery_callback',
        'spareparts',
        'normal',
        'high'
    );
    
    add_meta_box(
        'sparepart_specifications',
        '⚙️ Spesifikasi',
        'inviro_sparepart_specifications_callback',
        'spareparts',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_sparepart_meta_boxes');

function inviro_sparepart_price_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $price = get_post_meta($post->ID, '_sparepart_price', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="sparepart_price" name="sparepart_price" 
               value="<?php echo esc_attr($price); ?>" 
               placeholder="150000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Harga dalam Rupiah (tanpa titik/koma)
        </p>
    </div>
    <?php
}

function inviro_sparepart_stock_callback($post) {
    $stock = get_post_meta($post->ID, '_sparepart_stock', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="sparepart_stock" name="sparepart_stock" 
               value="<?php echo esc_attr($stock); ?>" 
               placeholder="10" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Jumlah stok tersedia
        </p>
    </div>
    <?php
}

function inviro_sparepart_sku_callback($post) {
    $sku = get_post_meta($post->ID, '_sparepart_sku', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="text" id="sparepart_sku" name="sparepart_sku" 
               value="<?php echo esc_attr($sku); ?>" 
               placeholder="SP-001" 
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Kode unik untuk spare part
        </p>
    </div>
    <?php
}

function inviro_sparepart_promo_callback($post) {
    $promo = get_post_meta($post->ID, '_sparepart_promo', true);
    ?>
    <div style="padding: 10px 0;">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <input type="checkbox" id="sparepart_promo" name="sparepart_promo" value="1" 
                   <?php checked($promo, '1'); ?>
                   style="width: 20px; height: 20px; cursor: pointer;">
            <span style="font-size: 14px; font-weight: 500;">Tandai sebagai Promo</span>
        </label>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Centang jika spare part ini sedang dalam promo
        </p>
    </div>
    <?php
}

function inviro_sparepart_original_price_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $original_price = get_post_meta($post->ID, '_sparepart_original_price', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="sparepart_original_price" name="sparepart_original_price" 
               value="<?php echo esc_attr($original_price); ?>" 
               placeholder="200000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Harga sebelum promo (akan dicoret). Kosongkan jika tidak ada promo.
        </p>
    </div>
    <?php
}

function inviro_sparepart_gallery_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $gallery_ids = get_post_meta($post->ID, '_sparepart_gallery', true);
    $gallery_ids = $gallery_ids ? explode(',', $gallery_ids) : array();
    ?>
    <div style="padding: 10px 0;">
        <input type="hidden" id="sparepart_gallery" name="sparepart_gallery" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>">
        <div id="gallery-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
            <?php foreach ($gallery_ids as $img_id) : 
                if ($img_id) :
                    $img_url = wp_get_attachment_image_url($img_id, 'thumbnail');
            ?>
                <div class="gallery-item" data-id="<?php echo esc_attr($img_id); ?>" style="position: relative; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
                    <img src="<?php echo esc_url($img_url); ?>" style="width: 100%; height: 150px; object-fit: cover; display: block;">
                    <button type="button" class="remove-gallery-item" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>
                </div>
            <?php 
                endif;
            endforeach; ?>
        </div>
        <button type="button" id="add-gallery-images" class="button" style="margin-top: 15px;">Tambah Gambar ke Gallery</button>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Tambahkan beberapa gambar untuk ditampilkan di halaman detail product
        </p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var galleryFrame;
        $('#add-gallery-images').on('click', function(e) {
            e.preventDefault();
            if (galleryFrame) {
                galleryFrame.open();
                return;
            }
            galleryFrame = wp.media({
                title: 'Pilih Gambar Gallery',
                button: { text: 'Gunakan Gambar' },
                multiple: true
            });
            galleryFrame.on('select', function() {
                var selection = galleryFrame.state().get('selection');
                var galleryIds = $('#sparepart_gallery').val().split(',').filter(Boolean);
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    if (galleryIds.indexOf(attachment.id.toString()) === -1) {
                        galleryIds.push(attachment.id);
                        $('#gallery-preview').append(
                            '<div class="gallery-item" data-id="' + attachment.id + '" style="position: relative; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">' +
                            '<img src="' + attachment.sizes.thumbnail.url + '" style="width: 100%; height: 150px; object-fit: cover; display: block;">' +
                            '<button type="button" class="remove-gallery-item" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>' +
                            '</div>'
                        );
                    }
                });
                $('#sparepart_gallery').val(galleryIds.join(','));
            });
            galleryFrame.open();
        });
        $(document).on('click', '.remove-gallery-item', function() {
            var item = $(this).closest('.gallery-item');
            var imgId = item.data('id').toString();
            var galleryIds = $('#sparepart_gallery').val().split(',').filter(Boolean);
            galleryIds = galleryIds.filter(function(id) { return id !== imgId; });
            $('#sparepart_gallery').val(galleryIds.join(','));
            item.remove();
        });
    });
    </script>
    <?php
}

function inviro_sparepart_specifications_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $specs = get_post_meta($post->ID, '_sparepart_specifications', true);
    $specs = $specs ? json_decode($specs, true) : array();
    if (empty($specs)) {
        $specs = array(array('label' => '', 'value' => ''));
    }
    ?>
    <div style="padding: 10px 0;">
        <div id="specifications-list">
            <?php foreach ($specs as $index => $spec) : ?>
                <div class="spec-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                    <input type="text" name="spec_label[]" placeholder="Label (contoh: Material)" 
                           value="<?php echo esc_attr($spec['label']); ?>"
                           style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    <input type="text" name="spec_value[]" placeholder="Value (contoh: Stainless Steel)" 
                           value="<?php echo esc_attr($spec['value']); ?>"
                           style="flex: 2; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    <button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-spec" class="button" style="margin-top: 10px;">Tambah Spesifikasi</button>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Tambahkan spesifikasi produk (contoh: Material, Ukuran, Berat, dll)
        </p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#add-spec').on('click', function() {
            $('#specifications-list').append(
                '<div class="spec-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">' +
                '<input type="text" name="spec_label[]" placeholder="Label" style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">' +
                '<input type="text" name="spec_value[]" placeholder="Value" style="flex: 2; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">' +
                '<button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>' +
                '</div>'
            );
        });
        $(document).on('click', '.remove-spec', function() {
            if ($('.spec-row').length > 1) {
                $(this).closest('.spec-row').remove();
            } else {
                alert('Minimal harus ada 1 spesifikasi');
            }
        });
    });
    </script>
    <?php
}

function inviro_save_sparepart_meta($post_id) {
    if (get_post_type($post_id) !== 'spareparts') {
        return;
    }
    
    if (!isset($_POST['inviro_sparepart_meta_nonce']) || !wp_verify_nonce($_POST['inviro_sparepart_meta_nonce'], 'inviro_sparepart_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['sparepart_price'])) {
        update_post_meta($post_id, '_sparepart_price', absint($_POST['sparepart_price']));
    }
    
    if (isset($_POST['sparepart_stock'])) {
        update_post_meta($post_id, '_sparepart_stock', absint($_POST['sparepart_stock']));
    }
    
    if (isset($_POST['sparepart_sku'])) {
        update_post_meta($post_id, '_sparepart_sku', sanitize_text_field($_POST['sparepart_sku']));
    }
    
    if (isset($_POST['sparepart_promo'])) {
        update_post_meta($post_id, '_sparepart_promo', '1');
    } else {
        update_post_meta($post_id, '_sparepart_promo', '0');
    }
    
    if (isset($_POST['sparepart_original_price'])) {
        update_post_meta($post_id, '_sparepart_original_price', absint($_POST['sparepart_original_price']));
    }
    
    if (isset($_POST['sparepart_gallery'])) {
        update_post_meta($post_id, '_sparepart_gallery', sanitize_text_field($_POST['sparepart_gallery']));
    }
    
    if (isset($_POST['spec_label']) && isset($_POST['spec_value'])) {
        $specs = array();
        foreach ($_POST['spec_label'] as $index => $label) {
            if (!empty($label) && !empty($_POST['spec_value'][$index])) {
                $specs[] = array(
                    'label' => sanitize_text_field($label),
                    'value' => sanitize_text_field($_POST['spec_value'][$index])
                );
            }
        }
        update_post_meta($post_id, '_sparepart_specifications', json_encode($specs));
    }
}
add_action('save_post_spareparts', 'inviro_save_sparepart_meta');

/**
 * Add meta boxes for Unduhan
 */
function inviro_add_unduhan_meta_boxes() {
    add_meta_box(
        'unduhan_file',
        '📁 File Download',
        'inviro_unduhan_file_callback',
        'unduhan',
        'normal',
        'high'
    );
    
    add_meta_box(
        'unduhan_info',
        'ℹ️ Info File',
        'inviro_unduhan_info_callback',
        'unduhan',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_unduhan_meta_boxes');

function inviro_unduhan_file_callback($post) {
    wp_nonce_field('inviro_unduhan_meta', 'inviro_unduhan_meta_nonce');
    $file_url = get_post_meta($post->ID, '_unduhan_file_url', true);
    ?>
    <div style="padding: 15px 0;">
        <p style="margin-bottom: 10px; color: #666;">
            📤 Upload file PDF, DOC, ZIP, atau file lainnya yang akan didownload user.
        </p>
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="unduhan_file_url" name="unduhan_file_url" 
                   value="<?php echo esc_url($file_url); ?>" 
                   placeholder="https://..." 
                   readonly
                   style="flex: 1; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
            <button type="button" class="button button-primary" id="upload_file_button">
                📂 Pilih File
            </button>
            <?php if ($file_url) : ?>
            <button type="button" class="button" id="remove_file_button">
                ✕ Hapus
            </button>
            <?php endif; ?>
        </div>
        <?php if ($file_url) : ?>
        <p style="margin-top: 10px; color: #0073aa;">
            ✅ File terpilih: <a href="<?php echo esc_url($file_url); ?>" target="_blank">Lihat File</a>
        </p>
        <?php endif; ?>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var file_frame;
        
        $('#upload_file_button').on('click', function(e) {
            e.preventDefault();
            
            if (file_frame) {
                file_frame.open();
                return;
            }
            
            file_frame = wp.media({
                title: 'Pilih File untuk Diunduh',
                button: {
                    text: 'Gunakan File Ini'
                },
                multiple: false
            });
            
            file_frame.on('select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                $('#unduhan_file_url').val(attachment.url);
                location.reload();
            });
            
            file_frame.open();
        });
        
        $('#remove_file_button').on('click', function(e) {
            e.preventDefault();
            $('#unduhan_file_url').val('');
            $(this).remove();
            $('.button-primary').after('<p style="color: #d32f2f;">File dihapus, klik Update untuk menyimpan</p>');
        });
    });
    </script>
    <?php
}

function inviro_unduhan_info_callback($post) {
    $file_size = get_post_meta($post->ID, '_unduhan_file_size', true);
    $file_type = get_post_meta($post->ID, '_unduhan_file_type', true);
    $download_count = get_post_meta($post->ID, '_unduhan_download_count', true);
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 10px;"><strong>Ukuran File:</strong></p>
        <input type="text" id="unduhan_file_size" name="unduhan_file_size" 
               value="<?php echo esc_attr($file_size); ?>" 
               placeholder="5 MB" 
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
        
        <p style="margin-bottom: 10px;"><strong>Tipe File:</strong></p>
        <input type="text" id="unduhan_file_type" name="unduhan_file_type" 
               value="<?php echo esc_attr($file_type); ?>" 
               placeholder="PDF" 
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
        
        <p style="margin-bottom: 10px;"><strong>Jumlah Download:</strong></p>
        <input type="number" id="unduhan_download_count" name="unduhan_download_count" 
               value="<?php echo esc_attr($download_count ? $download_count : 0); ?>" 
               readonly
               style="width: 100%; padding: 8px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 4px;">
        <p class="description" style="margin-top: 5px;">Auto-increment saat di-download</p>
    </div>
    <?php
}

function inviro_save_unduhan_meta($post_id) {
    if (get_post_type($post_id) !== 'unduhan') {
        return;
    }
    
    if (!isset($_POST['inviro_unduhan_meta_nonce']) || !wp_verify_nonce($_POST['inviro_unduhan_meta_nonce'], 'inviro_unduhan_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['unduhan_file_url'])) {
        update_post_meta($post_id, '_unduhan_file_url', esc_url_raw($_POST['unduhan_file_url']));
    }
    
    if (isset($_POST['unduhan_file_size'])) {
        update_post_meta($post_id, '_unduhan_file_size', sanitize_text_field($_POST['unduhan_file_size']));
    }
    
    if (isset($_POST['unduhan_file_type'])) {
        update_post_meta($post_id, '_unduhan_file_type', sanitize_text_field($_POST['unduhan_file_type']));
    }
}
add_action('save_post_unduhan', 'inviro_save_unduhan_meta');

/**
 * AJAX handler untuk tracking download
 */
function inviro_track_download() {
    if (!isset($_POST['post_id'])) {
        wp_send_json_error('Invalid request');
        return;
    }
    
    $post_id = intval($_POST['post_id']);
    
    // Verify it's an unduhan post type
    if (get_post_type($post_id) !== 'unduhan') {
        wp_send_json_error('Invalid post type');
        return;
    }
    
    // Get current count
    $current_count = get_post_meta($post_id, '_unduhan_download_count', true);
    $current_count = $current_count ? intval($current_count) : 0;
    
    // Increment
    $new_count = $current_count + 1;
    update_post_meta($post_id, '_unduhan_download_count', $new_count);
    
    wp_send_json_success(array(
        'new_count' => $new_count,
        'message' => 'Download tracked successfully'
    ));
}
add_action('wp_ajax_track_download', 'inviro_track_download');
add_action('wp_ajax_nopriv_track_download', 'inviro_track_download');

/**
 * Admin notices untuk membantu user
 */
function inviro_admin_notices() {
    $screen = get_current_screen();
    
    // Notice untuk halaman edit produk
    if ($screen->post_type === 'produk' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        ?>
        <div class="notice notice-info">
            <p><strong>💡 Tip:</strong> Jangan lupa upload <strong>Featured Image</strong> untuk gambar produk (lihat sidebar kanan) dan isi <strong>Harga Produk</strong> di bawah.</p>
        </div>
        <?php
    }
    
    // Notice untuk halaman edit testimoni
    if ($screen->post_type === 'testimoni' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        ?>
        <div class="notice notice-info">
            <p><strong>💡 Tip:</strong> Upload <strong>Featured Image</strong> untuk foto pelanggan dan pilih <strong>Rating</strong> bintang.</p>
        </div>
        <?php
    }
    
    // Notice untuk halaman edit cabang
    if ($screen->post_type === 'cabang' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        ?>
        <div class="notice notice-info">
            <p><strong>💡 Tip:</strong> Upload <strong>Featured Image</strong> untuk foto lokasi cabang dan isi alamat lengkap.</p>
        </div>
        <?php
    }
    
    // Notice untuk halaman edit proyek pelanggan
    if ($screen->post_type === 'proyek_pelanggan' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        ?>
        <div class="notice notice-info">
            <p><strong>💡 Tip:</strong> Upload <strong>Featured Image</strong> untuk foto proyek, pilih <strong>Daerah</strong> (Jawa, Sumatra, dll), dan isi <strong>Nama Klien</strong> + <strong>Tanggal Proyek</strong>.</p>
        </div>
        <?php
    }
    
    // Notice untuk halaman edit spareparts
    if ($screen->post_type === 'spareparts' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        ?>
        <div class="notice notice-info">
            <p><strong>💡 Tip:</strong> Upload <strong>Featured Image</strong> untuk foto spare part, isi <strong>Harga</strong>, <strong>Stok</strong>, dan <strong>SKU</strong> di bawah.</p>
        </div>
        <?php
    }
    
    // Notice untuk halaman edit artikel
    if ($screen->post_type === 'artikel' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        ?>
        <div class="notice notice-info">
            <p><strong>💡 Tip:</strong> Upload <strong>Featured Image</strong> untuk gambar artikel dan tulis konten yang menarik untuk dibaca.</p>
        </div>
        <?php
    }
    
    // Notice untuk halaman edit unduhan
    if ($screen->post_type === 'unduhan' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        ?>
        <div class="notice notice-info">
            <p><strong>💡 Tip:</strong> Upload <strong>Featured Image</strong> (thumbnail), klik <strong>Pilih File</strong> di bawah untuk upload dokumen, dan isi <strong>Ukuran File</strong> dan <strong>Tipe File</strong>.</p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'inviro_admin_notices');

/**
 * Handle Proyek Pelanggan Form Submission
 */
function inviro_handle_proyek_submission() {
    // Check if form was submitted
    if (!isset($_POST['submit_proyek_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['submit_proyek_nonce'], 'submit_proyek_action')) {
        wp_redirect(add_query_arg('error', 'invalid_nonce', wp_get_referer()));
        exit;
    }
    
    // Validate required fields
    if (empty($_POST['proyek_title']) || empty($_POST['proyek_description']) || 
        empty($_POST['proyek_excerpt']) || empty($_POST['proyek_client']) || 
        empty($_POST['proyek_date']) || empty($_POST['proyek_region'])) {
        wp_redirect(add_query_arg('error', 'missing_fields', wp_get_referer()));
        exit;
    }
    
    // Check if image was uploaded
    if (empty($_FILES['proyek_image']['name'])) {
        wp_redirect(add_query_arg('error', 'missing_image', wp_get_referer()));
        exit;
    }
    
    // Sanitize inputs
    $title = sanitize_text_field($_POST['proyek_title']);
    $description = wp_kses_post($_POST['proyek_description']);
    $excerpt = sanitize_textarea_field($_POST['proyek_excerpt']);
    $client_name = sanitize_text_field($_POST['proyek_client']);
    $proyek_date = sanitize_text_field($_POST['proyek_date']);
    $region_id = intval($_POST['proyek_region']);
    
    // Create the post
    $post_data = array(
        'post_title'    => $title,
        'post_content'  => $description,
        'post_excerpt'  => $excerpt,
        'post_status'   => 'publish',
        'post_type'     => 'proyek_pelanggan',
        'post_author'   => get_current_user_id()
    );
    
    $post_id = wp_insert_post($post_data);
    
    if (is_wp_error($post_id)) {
        wp_redirect(add_query_arg('error', 'post_creation_failed', wp_get_referer()));
        exit;
    }
    
    // Set taxonomy
    wp_set_post_terms($post_id, array($region_id), 'region');
    
    // Save meta fields
    update_post_meta($post_id, '_proyek_client_name', $client_name);
    update_post_meta($post_id, '_proyek_date', $proyek_date);
    
    // Handle image upload
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    $attachment_id = media_handle_upload('proyek_image', $post_id);
    
    if (is_wp_error($attachment_id)) {
        // Delete the post if image upload failed
        wp_delete_post($post_id, true);
        wp_redirect(add_query_arg('error', 'image_upload_failed', wp_get_referer()));
        exit;
    }
    
    // Set as featured image
    set_post_thumbnail($post_id, $attachment_id);
    
    // Redirect to success page
    wp_redirect(add_query_arg('success', '1', wp_get_referer()));
    exit;
}
add_action('template_redirect', 'inviro_handle_proyek_submission');

/**
 * Handle dummy sparepart detail page
 * Ensure page-spareparts.php template is used when dummy_id is present
 */
function inviro_handle_dummy_sparepart_detail() {
    // Check if dummy_id is present in query string
    if (isset($_GET['dummy_id']) && intval($_GET['dummy_id']) > 0) {
        // Check if we're on spareparts page or trying to access dummy detail
        $dummy_id = intval($_GET['dummy_id']);
        
        // Try to find spareparts page
        $spareparts_page = get_page_by_path('spareparts');
        if (!$spareparts_page) {
            // Try alternative slug
            $spareparts_page = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-spareparts.php',
                'number' => 1
            ));
            if (!empty($spareparts_page)) {
                $spareparts_page = $spareparts_page[0];
            }
        }
        
        // If page exists, set up query to use that page
        if ($spareparts_page) {
            global $wp_query;
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
            $wp_query->is_404 = false;
            $wp_query->queried_object = $spareparts_page;
            $wp_query->queried_object_id = $spareparts_page->ID;
            $wp_query->posts = array($spareparts_page);
            $wp_query->post_count = 1;
            $wp_query->found_posts = 1;
            $wp_query->max_num_pages = 1;
        }
    }
}
add_action('template_redirect', 'inviro_handle_dummy_sparepart_detail', 1);

/**
 * Handle dummy paket usaha detail page
 * Ensure page-paket-usaha.php template is used when dummy_id is present
 */
function inviro_handle_dummy_paket_usaha_detail() {
    // Check if dummy_id is present in query string
    if (isset($_GET['dummy_id']) && intval($_GET['dummy_id']) > 0) {
        // Check if we're on paket-usaha page or trying to access dummy detail
        $request_uri = $_SERVER['REQUEST_URI'];
        if (strpos($request_uri, '/paket-usaha') !== false) {
            $dummy_id = intval($_GET['dummy_id']);
            
            // Try to find paket-usaha page
            $paket_usaha_page = get_page_by_path('paket-usaha');
            if (!$paket_usaha_page) {
                // Try alternative slug
                $paket_usaha_page = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-paket-usaha.php',
                    'number' => 1
                ));
                if (!empty($paket_usaha_page)) {
                    $paket_usaha_page = $paket_usaha_page[0];
                }
            }
            
            // If page exists, set up query to use that page
            if ($paket_usaha_page) {
                global $wp_query;
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->is_404 = false;
                $wp_query->is_archive = false;
                $wp_query->is_post_type_archive = false;
                $wp_query->queried_object = $paket_usaha_page;
                $wp_query->queried_object_id = $paket_usaha_page->ID;
                $wp_query->posts = array($paket_usaha_page);
                $wp_query->post_count = 1;
                $wp_query->found_posts = 1;
                $wp_query->max_num_pages = 1;
            }
        }
    }
}
add_action('template_redirect', 'inviro_handle_dummy_paket_usaha_detail', 1);

/**
 * Force template for dummy sparepart detail
 */
function inviro_force_spareparts_template($template) {
    // Check if dummy_id is present
    if (isset($_GET['dummy_id']) && intval($_GET['dummy_id']) > 0) {
        // Check if current request might be for spareparts page
        $request_uri = $_SERVER['REQUEST_URI'];
        if (strpos($request_uri, '/spareparts') !== false || (is_404() && strpos($request_uri, '/spareparts') !== false)) {
            // Try to find spareparts page
            $spareparts_page = get_page_by_path('spareparts');
            if (!$spareparts_page) {
                $pages = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-spareparts.php',
                    'number' => 1
                ));
                if (!empty($pages)) {
                    $spareparts_page = $pages[0];
                }
            }
            
            // If spareparts page exists, use its template
            if ($spareparts_page) {
                $page_template = get_page_template_slug($spareparts_page->ID);
                if ($page_template == 'page-spareparts.php' || empty($page_template)) {
                    $template_path = locate_template('page-spareparts.php');
                    if ($template_path) {
                        return $template_path;
                    }
                }
            }
        }
        
        // Check if current request might be for paket-usaha page
        if (strpos($request_uri, '/paket-usaha') !== false || (is_404() && strpos($request_uri, '/paket-usaha') !== false)) {
            // Try to find paket-usaha page
            $paket_usaha_page = get_page_by_path('paket-usaha');
            if (!$paket_usaha_page) {
                $pages = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-paket-usaha.php',
                    'number' => 1
                ));
                if (!empty($pages)) {
                    $paket_usaha_page = $pages[0];
                }
            }
            
            // If paket-usaha page exists, use its template and set query vars
            if ($paket_usaha_page) {
                global $wp_query;
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->is_404 = false;
                $wp_query->is_archive = false;
                $wp_query->is_post_type_archive = false;
                $wp_query->queried_object = $paket_usaha_page;
                $wp_query->queried_object_id = $paket_usaha_page->ID;
                
                $page_template = get_page_template_slug($paket_usaha_page->ID);
                if ($page_template == 'page-paket-usaha.php' || empty($page_template)) {
                    $template_path = locate_template('page-paket-usaha.php');
                    if ($template_path) {
                        return $template_path;
                    }
                }
            }
        }
    }
    return $template;
}
add_filter('template_include', 'inviro_force_spareparts_template', 99);

/**
 * Contact Form Handler
 */
function inviro_handle_contact_form() {
    check_ajax_referer('inviro_nonce', 'nonce');
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);
    
    $to = get_option('admin_email');
    $email_subject = 'Pesan Baru dari ' . $name . ' - ' . $subject;
    $email_message = "Nama: $name\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Telepon: $phone\n";
    $email_message .= "Subjek: $subject\n\n";
    $email_message .= "Pesan:\n$message";
    
    $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $name . ' <' . $email . '>');
    
    $sent = wp_mail($to, $email_subject, $email_message, $headers);
    
    if ($sent) {
        wp_send_json_success(array('message' => 'Pesan berhasil dikirim!'));
    } else {
        wp_send_json_error(array('message' => 'Gagal mengirim pesan. Silakan coba lagi.'));
    }
}
add_action('wp_ajax_inviro_contact_form', 'inviro_handle_contact_form');
add_action('wp_ajax_nopriv_inviro_contact_form', 'inviro_handle_contact_form');

/**
 * Customizer Settings
 */
function inviro_customize_register($wp_customize) {
    // Site Identity - Color Settings
    $wp_customize->add_setting('inviro_primary_color', array(
        'default'           => '#2F80ED',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_primary_color', array(
        'label'       => __('Warna Utama (Primary Color)', 'inviro'),
        'description' => __('Warna utama yang digunakan di navbar, hero section, dan elemen utama lainnya. Contoh: biru (#2F80ED), merah (#E91C2E), kuning (#FFC107)', 'inviro'),
        'section'     => 'title_tagline',
        'priority'    => 30,
    )));
    
    $wp_customize->add_setting('inviro_primary_color_light', array(
        'default'           => '#75C6F1',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_primary_color_light', array(
        'label'       => __('Warna Utama Terang (Primary Light)', 'inviro'),
        'description' => __('Warna terang untuk gradient dan efek hover. Biasanya lebih terang dari warna utama.', 'inviro'),
        'section'     => 'title_tagline',
        'priority'    => 31,
    )));
    
    $wp_customize->add_setting('inviro_primary_color_medium', array(
        'default'           => '#4FB3E8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_primary_color_medium', array(
        'label'       => __('Warna Utama Medium (Primary Medium)', 'inviro'),
        'description' => __('Warna medium untuk gradient. Biasanya di antara warna utama dan warna terang.', 'inviro'),
        'section'     => 'title_tagline',
        'priority'    => 32,
    )));
    
    // Header Section
    $wp_customize->add_section('inviro_header', array(
        'title'    => __('Header', 'inviro'),
        'priority' => 20,
    ));
    
    // Header Logo (Alternatif jika tidak menggunakan Custom Logo)
    $wp_customize->add_setting('inviro_header_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'inviro_header_logo', array(
        'label'       => __('Logo Header', 'inviro'),
        'description' => __('Upload logo untuk header. Prioritas: 1) Custom Logo (Site Identity), 2) Logo ini, 3) Default logo file, 4) Text.', 'inviro'),
        'section'     => 'inviro_header',
        'priority'    => 10,
    )));
    
    // Toggle untuk menggunakan default logo
    $wp_customize->add_setting('inviro_use_default_logo', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('inviro_use_default_logo', array(
        'label'       => __('Gunakan Default Logo', 'inviro'),
        'description' => __('Centang untuk menggunakan logo default dari file (assets/images/inviro-logo.png). Pastikan file logo sudah ada di folder tersebut.', 'inviro'),
        'section'     => 'inviro_header',
        'type'        => 'checkbox',
        'priority'    => 11,
    ));
    
    // Instagram URL untuk Header
    $wp_customize->add_setting('inviro_instagram', array(
        'default'           => 'https://instagram.com/inviro',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('inviro_instagram', array(
        'label'    => __('Instagram URL (Header)', 'inviro'),
        'section'  => 'inviro_header',
        'type'     => 'url',
    ));
    
    // Custom Navbar Menu Settings
    $wp_customize->add_setting('inviro_use_custom_navbar', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('inviro_use_custom_navbar', array(
        'label'       => __('Gunakan Menu Custom Navbar', 'inviro'),
        'description' => __('Aktifkan untuk menggunakan menu custom dari Customizer. Jika tidak aktif, akan menggunakan WordPress Menu.', 'inviro'),
        'section'     => 'inviro_header',
        'type'        => 'checkbox',
        'priority'    => 20,
    ));
    
    // Menu Items (maksimal 10 items)
    for ($i = 1; $i <= 10; $i++) {
        // Enable/Disable Menu Item
        $wp_customize->add_setting('inviro_navbar_item_' . $i . '_enabled', array(
            'default'           => ($i <= 7) ? true : false, // Default enable untuk 7 item pertama
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        $wp_customize->add_control('inviro_navbar_item_' . $i . '_enabled', array(
            'label'    => sprintf(__('Menu Item %d - Aktifkan', 'inviro'), $i),
            'section'  => 'inviro_header',
            'type'     => 'checkbox',
            'priority' => 21 + ($i * 3),
        ));
        
        // Menu Item Text
        $default_texts = array(
            1 => 'Beranda',
            2 => 'Profil',
            3 => 'Paket Usaha',
            4 => 'Pelanggan',
            5 => 'Spare Parts',
            6 => 'Artikel',
            7 => 'Unduhan',
        );
        $wp_customize->add_setting('inviro_navbar_item_' . $i . '_text', array(
            'default'           => isset($default_texts[$i]) ? $default_texts[$i] : '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('inviro_navbar_item_' . $i . '_text', array(
            'label'    => sprintf(__('Menu Item %d - Text', 'inviro'), $i),
            'section'  => 'inviro_header',
            'type'     => 'text',
            'priority' => 22 + ($i * 3),
        ));
        
        // Menu Item URL
        $default_urls = array(
            1 => home_url('/'),
            2 => home_url('/profil'),
            3 => home_url('/paket-usaha'),
            4 => home_url('/pelanggan'),
            5 => home_url('/spare-parts'),
            6 => home_url('/artikel'),
            7 => home_url('/unduhan'),
        );
        $wp_customize->add_setting('inviro_navbar_item_' . $i . '_url', array(
            'default'           => isset($default_urls[$i]) ? $default_urls[$i] : '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('inviro_navbar_item_' . $i . '_url', array(
            'label'    => sprintf(__('Menu Item %d - URL', 'inviro'), $i),
            'section'  => 'inviro_header',
            'type'     => 'url',
            'priority' => 23 + ($i * 3),
        ));
    }
    
    // About Section
    $wp_customize->add_section('inviro_about', array(
        'title'    => __('About Section', 'inviro'),
        'priority' => 26,
    ));
    
    $wp_customize->add_setting('inviro_about_title', array(
        'default'           => 'INVIRO',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_about_title', array(
        'label'    => __('Judul About', 'inviro'),
        'section'  => 'inviro_about',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_about_description', array(
        'default'           => 'INVIRO adalah perusahaan terkemuka dalam penyediaan mesin dan peralatan depot air minum berkualitas tinggi.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('inviro_about_description', array(
        'label'    => __('Deskripsi About', 'inviro'),
        'section'  => 'inviro_about',
        'type'     => 'textarea',
    ));
    
    // Number of branches to display
    $wp_customize->add_setting('inviro_branch_count', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('inviro_branch_count', array(
        'label'       => __('Jumlah Cabang yang Ditampilkan', 'inviro'),
        'description' => __('Pilih jumlah cabang yang akan ditampilkan di About Section (1-8 cabang)', 'inviro'),
        'section'     => 'inviro_about',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 8,
            'step' => 1,
        ),
        'priority'    => 15,
    ));
    
    // Branches Selection in About Section
    // Get all branches
    $branches_query = new WP_Query(array(
        'post_type' => 'cabang',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $branch_choices = array('' => __('-- Pilih Cabang --', 'inviro'));
    if ($branches_query->have_posts()) {
        while ($branches_query->have_posts()) {
            $branches_query->the_post();
            $branch_choices[get_the_ID()] = get_the_title();
        }
        wp_reset_postdata();
    }
    
    // Select branches to display (up to 8 slots)
    for ($i = 1; $i <= 8; $i++) {
        $wp_customize->add_setting('inviro_branch_' . $i, array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('inviro_branch_' . $i, array(
            'label'       => sprintf(__('Cabang Slot %d', 'inviro'), $i),
            'description' => __('Pilih cabang yang akan ditampilkan. Kosongkan jika tidak digunakan.', 'inviro'),
            'section'     => 'inviro_about',
            'type'        => 'select',
            'choices'     => $branch_choices,
            'priority'    => 20 + $i,
        ));
    }
    
    // Products Section
    $wp_customize->add_section('inviro_products', array(
        'title'    => __('Products Section', 'inviro'),
        'priority' => 27,
    ));
    
    $wp_customize->add_setting('inviro_products_title', array(
        'default'           => 'Rekomendasi Produk Inviro',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_products_title', array(
        'label'    => __('Judul Produk', 'inviro'),
        'section'  => 'inviro_products',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_products_subtitle', array(
        'default'           => 'Pilihan terbaik untuk usaha depot air minum Anda',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_products_subtitle', array(
        'label'    => __('Subtitle Produk', 'inviro'),
        'section'  => 'inviro_products',
        'type'     => 'text',
    ));
    
    // Products Count and Selection
    $wp_customize->add_setting('inviro_products_count', array(
        'default'           => '8',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('inviro_products_count', array(
        'label'       => __('Jumlah Produk yang Ditampilkan', 'inviro'),
        'description' => __('Masukkan jumlah produk yang akan ditampilkan di halaman depan', 'inviro'),
        'section'     => 'inviro_products',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 20,
            'step' => 1,
        ),
    ));
    
    // Get all product posts
    $products_query = new WP_Query(array(
        'post_type' => 'produk',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $product_choices = array('' => __('-- Pilih Produk --', 'inviro'));
    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product_choices[get_the_ID()] = get_the_title();
        }
        wp_reset_postdata();
    }
    
    // Featured products - manual selection
    for ($i = 1; $i <= 8; $i++) {
        $wp_customize->add_setting('inviro_featured_product_' . $i, array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('inviro_featured_product_' . $i, array(
            'label'       => sprintf(__('Produk Unggulan %d', 'inviro'), $i),
            'description' => __('Pilih produk yang akan ditampilkan. Kosongkan untuk otomatis', 'inviro'),
            'section'     => 'inviro_products',
            'type'        => 'select',
            'choices'     => $product_choices,
        ));
    }
    
    // Testimonials Section
    $wp_customize->add_section('inviro_testimonials', array(
        'title'    => __('Testimonials Section', 'inviro'),
        'priority' => 28,
    ));
    
    $wp_customize->add_setting('inviro_testimonials_title', array(
        'default'           => 'Dipercaya Oleh Banyak Pelanggan',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_testimonials_title', array(
        'label'    => __('Judul Testimoni', 'inviro'),
        'section'  => 'inviro_testimonials',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_testimonials_subtitle', array(
        'default'           => '95% Pelanggan INVIRO di berbagai daerah di Indonesia merasa puas dengan pelayanan & produk INVIRO',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('inviro_testimonials_subtitle', array(
        'label'    => __('Subtitle Testimoni', 'inviro'),
        'section'  => 'inviro_testimonials',
        'type'     => 'textarea',
    ));
    
    // Get all testimonials for selection
    $testimonials = get_posts(array(
        'post_type'      => 'testimoni',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC'
    ));
    
    $testimonial_choices = array('' => __('-- Pilih Testimoni --', 'inviro'));
    foreach ($testimonials as $testimonial) {
        $testimonial_choices[$testimonial->ID] = $testimonial->post_title;
    }
    
    // Add 10 testimonial selection dropdowns (akan tampil 3 per slide di carousel)
    for ($i = 1; $i <= 10; $i++) {
        $wp_customize->add_setting('inviro_testimonial_' . $i, array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('inviro_testimonial_' . $i, array(
            'label'    => sprintf(__('Testimoni %d', 'inviro'), $i),
            'section'  => 'inviro_testimonials',
            'type'     => 'select',
            'choices'  => $testimonial_choices,
        ));
    }
    
    // Contact Section
    $wp_customize->add_section('inviro_contact', array(
        'title'    => __('Contact Section', 'inviro'),
        'priority' => 29,
    ));
    
    // Judul & Deskripsi
    $wp_customize->add_setting('inviro_contact_title', array(
        'default'           => 'Hubungi Kami untuk Layanan Terbaik',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_contact_title', array(
        'label'    => __('Judul Section', 'inviro'),
        'section'  => 'inviro_contact',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_contact_description', array(
        'default'           => 'Untuk informasi lebih lanjut mengenai produk dan layanan kami, jangan ragu untuk menghubungi kami',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('inviro_contact_description', array(
        'label'    => __('Deskripsi (Opsional)', 'inviro'),
        'section'  => 'inviro_contact',
        'type'     => 'textarea',
    ));
    
    // Google Maps
    $wp_customize->add_setting('inviro_contact_map_url', array(
        'default'           => '',
        'sanitize_callback' => function($value) {
            // Allow iframe tags and extract URL if needed
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
            return wp_kses($value, $allowed_html);
        },
    ));
    
    $wp_customize->add_control('inviro_contact_map_url', array(
        'label'       => __('URL atau Embed Code Google Maps', 'inviro'),
        'description' => __('Masuk ke Google Maps → Pilih lokasi → Klik "Share" → Tab "Embed a map" → Copy URL dari src="..." atau full iframe code → Paste di sini', 'inviro'),
        'section'     => 'inviro_contact',
        'type'        => 'textarea',
    ));
    
    // Feature Items (3 items with icon, title, description)
    for ($i = 1; $i <= 3; $i++) {
        // Icon choice
        $wp_customize->add_setting('inviro_contact_feature_' . $i . '_icon', array(
            'default'           => $i == 1 ? 'phone' : ($i == 2 ? 'tag' : 'map-pin'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('inviro_contact_feature_' . $i . '_icon', array(
            'label'    => sprintf(__('Feature %d - Icon', 'inviro'), $i),
            'description' => __('Pilih: phone, tag, map-pin, mail, clock, shield, award, check-circle', 'inviro'),
            'section'  => 'inviro_contact',
            'type'     => 'select',
            'choices'  => array(
                'phone'        => __('Phone (Telepon)', 'inviro'),
                'tag'          => __('Tag (Harga)', 'inviro'),
                'map-pin'      => __('Map Pin (Lokasi)', 'inviro'),
                'mail'         => __('Mail (Email)', 'inviro'),
                'clock'        => __('Clock (Waktu)', 'inviro'),
                'shield'       => __('Shield (Keamanan)', 'inviro'),
                'award'        => __('Award (Penghargaan)', 'inviro'),
                'check-circle' => __('Check Circle (Verifikasi)', 'inviro'),
            ),
        ));
        
        // Title
        $wp_customize->add_setting('inviro_contact_feature_' . $i . '_title', array(
            'default'           => $i == 1 ? 'Customer Support' : ($i == 2 ? 'Harga & Kualitas Terjamin' : 'Banyak Lokasi'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('inviro_contact_feature_' . $i . '_title', array(
            'label'    => sprintf(__('Feature %d - Judul', 'inviro'), $i),
            'section'  => 'inviro_contact',
            'type'     => 'text',
        ));
        
        // Description
        $wp_customize->add_setting('inviro_contact_feature_' . $i . '_description', array(
            'default'           => $i == 1 ? 'Tim support kami siap membantu Anda 24/7' : ($i == 2 ? 'Harga terbaik dengan kualitas premium' : 'Hadir di berbagai kota di Indonesia'),
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        
        $wp_customize->add_control('inviro_contact_feature_' . $i . '_description', array(
            'label'    => sprintf(__('Feature %d - Deskripsi', 'inviro'), $i),
            'section'  => 'inviro_contact',
            'type'     => 'textarea',
        ));
        
        // Color for icon circle
        $wp_customize->add_setting('inviro_contact_feature_' . $i . '_color', array(
            'default'           => $i == 1 ? '#28a745' : ($i == 2 ? '#ff8c00' : '#dc3545'),
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_contact_feature_' . $i . '_color', array(
            'label'    => sprintf(__('Feature %d - Warna Icon', 'inviro'), $i),
            'section'  => 'inviro_contact',
        )));
    }
    
    // WhatsApp
    $wp_customize->add_setting('inviro_whatsapp', array(
        'default'           => '621234567890',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_whatsapp', array(
        'label'       => __('Nomor WhatsApp', 'inviro'),
        'description' => __('Format: 621234567890 (tanpa + atau spasi)', 'inviro'),
        'section'     => 'inviro_contact',
        'type'        => 'text',
    ));
    
    // Hero Section (Statistics)
    $wp_customize->add_section('inviro_stats', array(
        'title'    => __('Hero Section', 'inviro'),
        'description' => __('Kustomisasi statistik yang ditampilkan di bagian hero homepage. Anda dapat mengubah angka dan label untuk setiap statistik.', 'inviro'),
        'priority' => 25,
        'panel'    => '', // Bisa ditambahkan ke panel jika diperlukan
    ));
    
    // Statistik 1
    $wp_customize->add_setting('inviro_stat_1_number', array(
        'default'           => '105+',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('inviro_stat_1_number', array(
        'label'       => __('Statistik 1 - Angka', 'inviro'),
        'description' => __('Masukkan angka atau teks untuk statistik pertama (contoh: 105+, 500, 1000+)', 'inviro'),
        'section'     => 'inviro_stats',
        'type'        => 'text',
        'priority'    => 10,
    ));
    
    $wp_customize->add_setting('inviro_stat_1_label', array(
        'default'           => 'Corporate Portofolio by INVIRO',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('inviro_stat_1_label', array(
        'label'       => __('Statistik 1 - Label', 'inviro'),
        'description' => __('Masukkan label atau deskripsi untuk statistik pertama', 'inviro'),
        'section'     => 'inviro_stats',
        'type'        => 'text',
        'priority'    => 11,
    ));
    
    // Statistik 2
    $wp_customize->add_setting('inviro_stat_2_number', array(
        'default'           => '30+',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('inviro_stat_2_number', array(
        'label'       => __('Statistik 2 - Angka', 'inviro'),
        'description' => __('Masukkan angka atau teks untuk statistik kedua (contoh: 30+, 50, 100+)', 'inviro'),
        'section'     => 'inviro_stats',
        'type'        => 'text',
        'priority'    => 20,
    ));
    
    $wp_customize->add_setting('inviro_stat_2_label', array(
        'default'           => 'Pengguna produk di Indonesia',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('inviro_stat_2_label', array(
        'label'       => __('Statistik 2 - Label', 'inviro'),
        'description' => __('Masukkan label atau deskripsi untuk statistik kedua', 'inviro'),
        'section'     => 'inviro_stats',
        'type'        => 'text',
        'priority'    => 21,
    ));
    
    // Statistik 3
    $wp_customize->add_setting('inviro_stat_3_number', array(
        'default'           => '+95%',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('inviro_stat_3_number', array(
        'label'       => __('Statistik 3 - Angka', 'inviro'),
        'description' => __('Masukkan angka atau teks untuk statistik ketiga (contoh: +95%, 98%, 100%)', 'inviro'),
        'section'     => 'inviro_stats',
        'type'        => 'text',
        'priority'    => 30,
    ));
    
    $wp_customize->add_setting('inviro_stat_3_label', array(
        'default'           => 'Kepuasan Pelanggan',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('inviro_stat_3_label', array(
        'label'       => __('Statistik 3 - Label', 'inviro'),
        'description' => __('Masukkan label atau deskripsi untuk statistik ketiga', 'inviro'),
        'section'     => 'inviro_stats',
        'type'        => 'text',
        'priority'    => 31,
    ));
    
    // Footer Section
    $wp_customize->add_section('inviro_footer', array(
        'title'    => __('Footer', 'inviro'),
        'priority' => 33,
    ));

    // Footer - Icon next to text logotype (when no custom logo)
    $wp_customize->add_setting('inviro_footer_logo_icon', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'inviro_footer_logo_icon', array(
        'label'   => __('Gambar Ikon Logo (Footer)', 'inviro'),
        'section' => 'inviro_footer',
    )));
    
    $wp_customize->add_setting('inviro_footer_description', array(
        'default'           => 'Solusi terpercaya untuk usaha depot air minum Anda. Mesin berkualitas, harga terjangkau, dukungan penuh.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('inviro_footer_description', array(
        'label'    => __('Deskripsi Footer', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'textarea',
    ));
    
    // Footer Gradient Direction
    $wp_customize->add_setting('inviro_footer_gradient_direction', array(
        'default'           => '90deg',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_gradient_direction', array(
        'label'    => __('Arah Gradasi', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'select',
        'choices'  => array(
            '0deg'   => __('Atas ke Bawah ↓', 'inviro'),
            '90deg'  => __('Kiri ke Kanan →', 'inviro'),
            '180deg' => __('Bawah ke Atas ↑', 'inviro'),
            '270deg' => __('Kanan ke Kiri ←', 'inviro'),
            '45deg'  => __('Diagonal ↗', 'inviro'),
            '135deg' => __('Diagonal ↘', 'inviro'),
        ),
    ));
    
    // Footer Gradient Color 1
    $wp_customize->add_setting('inviro_footer_gradient_color1', array(
        'default'           => '#FF8C42',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_footer_gradient_color1', array(
        'label'    => __('Warna 1', 'inviro'),
        'section'  => 'inviro_footer',
    )));
    
    $wp_customize->add_setting('inviro_footer_gradient_stop1', array(
        'default'           => '0',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('inviro_footer_gradient_stop1', array(
        'label'       => __('Posisi Warna 1 (%)', 'inviro'),
        'section'     => 'inviro_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ),
    ));
    
    // Footer Gradient Color 2
    $wp_customize->add_setting('inviro_footer_gradient_color2', array(
        'default'           => '#FF6B35',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_footer_gradient_color2', array(
        'label'    => __('Warna 2', 'inviro'),
        'section'  => 'inviro_footer',
    )));
    
    $wp_customize->add_setting('inviro_footer_gradient_stop2', array(
        'default'           => '25',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('inviro_footer_gradient_stop2', array(
        'label'       => __('Posisi Warna 2 (%)', 'inviro'),
        'section'     => 'inviro_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ),
    ));
    
    // Footer Gradient Color 3
    $wp_customize->add_setting('inviro_footer_gradient_color3', array(
        'default'           => '#4ECDC4',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_footer_gradient_color3', array(
        'label'    => __('Warna 3', 'inviro'),
        'section'  => 'inviro_footer',
    )));
    
    $wp_customize->add_setting('inviro_footer_gradient_stop3', array(
        'default'           => '75',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('inviro_footer_gradient_stop3', array(
        'label'       => __('Posisi Warna 3 (%)', 'inviro'),
        'section'     => 'inviro_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ),
    ));
    
    // Footer Gradient Color 4
    $wp_customize->add_setting('inviro_footer_gradient_color4', array(
        'default'           => '#45B7D1',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_footer_gradient_color4', array(
        'label'    => __('Warna 4', 'inviro'),
        'section'  => 'inviro_footer',
    )));
    
    $wp_customize->add_setting('inviro_footer_gradient_stop4', array(
        'default'           => '100',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('inviro_footer_gradient_stop4', array(
        'label'       => __('Posisi Warna 4 (%)', 'inviro'),
        'section'     => 'inviro_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ),
    ));
    
    $wp_customize->add_setting('inviro_footer_facebook', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_facebook', array(
        'label'    => __('Facebook URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_instagram', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_instagram', array(
        'label'    => __('Instagram URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_twitter', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_twitter', array(
        'label'    => __('Twitter URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_youtube', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_youtube', array(
        'label'    => __('YouTube URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    // Footer Colors Section
    $wp_customize->add_setting('inviro_footer_left_bg', array(
        'default'           => '#FF7A00',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_footer_left_bg', array(
        'label'    => __('Warna Background Kiri (Orange)', 'inviro'),
        'section'  => 'inviro_footer',
    )));
    
    $wp_customize->add_setting('inviro_footer_left_bg_end', array(
        'default'           => '#FF8C42',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_footer_left_bg_end', array(
        'label'    => __('Warna Background Kiri End (Orange Gradient)', 'inviro'),
        'section'  => 'inviro_footer',
    )));
    
    $wp_customize->add_setting('inviro_footer_right_bg', array(
        'default'           => '#E3F2FD',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_footer_right_bg', array(
        'label'    => __('Warna Background Kanan (Light Blue)', 'inviro'),
        'section'  => 'inviro_footer',
    )));
    
    $wp_customize->add_setting('inviro_footer_right_bg_end', array(
        'default'           => '#BBDEFB',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'inviro_footer_right_bg_end', array(
        'label'    => __('Warna Background Kanan End (Light Blue Gradient)', 'inviro'),
        'section'  => 'inviro_footer',
    )));
    
    // Footer Menu Links - About Section
    $wp_customize->add_setting('inviro_footer_about_title', array(
        'default'           => 'About',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_about_title', array(
        'label'    => __('Judul Menu About', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_about_link1_text', array(
        'default'           => 'How it works',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_about_link1_text', array(
        'label'    => __('About Link 1 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_about_link1_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_about_link1_url', array(
        'label'    => __('About Link 1 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_about_link2_text', array(
        'default'           => 'Featured',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_about_link2_text', array(
        'label'    => __('About Link 2 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_about_link2_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_about_link2_url', array(
        'label'    => __('About Link 2 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_about_link3_text', array(
        'default'           => 'Partnership',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_about_link3_text', array(
        'label'    => __('About Link 3 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_about_link3_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_about_link3_url', array(
        'label'    => __('About Link 3 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_about_link4_text', array(
        'default'           => 'Business Relation',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_about_link4_text', array(
        'label'    => __('About Link 4 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_about_link4_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_about_link4_url', array(
        'label'    => __('About Link 4 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    // Footer Menu Links - Community Section
    $wp_customize->add_setting('inviro_footer_community_title', array(
        'default'           => 'Community',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_community_title', array(
        'label'    => __('Judul Menu Community', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_community_link1_text', array(
        'default'           => 'Events',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_community_link1_text', array(
        'label'    => __('Community Link 1 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_community_link1_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_community_link1_url', array(
        'label'    => __('Community Link 1 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_community_link2_text', array(
        'default'           => 'Blog',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_community_link2_text', array(
        'label'    => __('Community Link 2 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_community_link2_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_community_link2_url', array(
        'label'    => __('Community Link 2 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_community_link3_text', array(
        'default'           => 'Podcast',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_community_link3_text', array(
        'label'    => __('Community Link 3 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_community_link3_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_community_link3_url', array(
        'label'    => __('Community Link 3 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_community_link4_text', array(
        'default'           => 'Invite a friend',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_community_link4_text', array(
        'label'    => __('Community Link 4 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_community_link4_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_community_link4_url', array(
        'label'    => __('Community Link 4 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    // Footer Menu Links - Socials Section
    $wp_customize->add_setting('inviro_footer_socials_title', array(
        'default'           => 'Socials',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_socials_title', array(
        'label'    => __('Judul Menu Socials', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_socials_link1_text', array(
        'default'           => 'Discord',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_socials_link1_text', array(
        'label'    => __('Socials Link 1 - Text', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('inviro_footer_socials_link1_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_socials_link1_url', array(
        'label'    => __('Socials Link 1 - URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    // Footer Bottom - Copyright & Legal Links
    $wp_customize->add_setting('inviro_footer_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('inviro_footer_copyright', array(
        'label'    => __('Copyright Text (kosongkan untuk default)', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'text',
        'description' => __('Default: ©{tahun} {nama_situs}. All rights reserved', 'inviro'),
    ));
    
    $wp_customize->add_setting('inviro_footer_privacy_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_privacy_url', array(
        'label'    => __('Privacy & Policy URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('inviro_footer_terms_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('inviro_footer_terms_url', array(
        'label'    => __('Terms & Condition URL', 'inviro'),
        'section'  => 'inviro_footer',
        'type'     => 'url',
    ));
}
add_action('customize_register', 'inviro_customize_register');

/**
 * Output Custom CSS Variables untuk warna dari Customizer
 */
function inviro_output_custom_colors() {
    $primary_color = get_theme_mod('inviro_primary_color', '#2F80ED');
    $primary_light = get_theme_mod('inviro_primary_color_light', '#75C6F1');
    $primary_medium = get_theme_mod('inviro_primary_color_medium', '#4FB3E8');
    
    ?>
    <style id="inviro-custom-colors">
        :root {
            --inviro-primary: <?php echo esc_attr($primary_color); ?>;
            --inviro-primary-light: <?php echo esc_attr($primary_light); ?>;
            --inviro-primary-medium: <?php echo esc_attr($primary_medium); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'inviro_output_custom_colors', 5);

/**
 * Enqueue Customizer Preview Script untuk Live Preview
 */
function inviro_customize_preview_js() {
    wp_enqueue_script(
        'inviro-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        array('customize-preview', 'jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_preview_init', 'inviro_customize_preview_js');

/**
 * Add Schema.org JSON-LD for SEO
 */
function inviro_add_schema() {
    if (is_front_page()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'logo' => get_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
            'description' => get_bloginfo('description'),
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
    }
}
add_action('wp_head', 'inviro_add_schema');

/**
 * Profil Page Customizer Settings
 */
function inviro_profil_customize_register($wp_customize) {
    // Profil Section
    $wp_customize->add_section('inviro_profil', array(
        'title' => __('Halaman Profil', 'inviro'),
        'priority' => 35,
        'description' => __('Kustomisasi konten halaman Profil. Hero section menggunakan data dari About Section di homepage.', 'inviro'),
    ));

    // ============================================
    // Sejarah / CV Section Settings
    // ============================================
    $wp_customize->add_setting('inviro_profil_history_title', array(
        'default' => 'Sejarah Perusahaan',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_history_title', array(
        'label' => __('Sejarah - Judul', 'inviro'),
        'description' => __('Judul untuk section Sejarah/CV Perusahaan', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_history_content', array(
        'default' => 'CV. INDO SOLUTION merupakan sebuah badan usaha komanditer yang didirikan pada Bulan Juli Tahun 2009 yang bergerak dibidang perdagangan umum/general trading, dalam proses perkembagannya CV. INDO SOLUTION juga ekspansi divisi bisnis dengan menghadirkan solusi bisnis dalam bidang pengolahan air (water treatment).

Divisi water treatment/water purifier CV. INDO SOLUTION yang bernama INVIRO™ [Water Solution] menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('inviro_profil_history_content', array(
        'label' => __('Sejarah - Konten CV', 'inviro'),
        'description' => __('Konten CV perusahaan yang akan ditampilkan di section Sejarah', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('inviro_profil_history_subtitle', array(
        'default' => 'Profil CV. INDO SOLUTION',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_history_subtitle', array(
        'label' => __('Sejarah - Subtitle', 'inviro'),
        'description' => __('Subtitle yang ditampilkan di bawah judul Sejarah', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    // CV Highlights (3 kotak)
    $wp_customize->add_setting('inviro_profil_highlight_1_title', array(
        'default' => 'Didirikan',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_highlight_1_title', array(
        'label' => __('Highlight 1 - Judul', 'inviro'),
        'description' => __('Judul untuk kotak highlight pertama (contoh: Didirikan)', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_highlight_1_value', array(
        'default' => 'Juli 2009',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_highlight_1_value', array(
        'label' => __('Highlight 1 - Nilai', 'inviro'),
        'description' => __('Nilai untuk kotak highlight pertama', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_highlight_2_title', array(
        'default' => 'Bidang Usaha',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_highlight_2_title', array(
        'label' => __('Highlight 2 - Judul', 'inviro'),
        'description' => __('Judul untuk kotak highlight kedua (contoh: Bidang Usaha)', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_highlight_2_value', array(
        'default' => 'Perdagangan Umum & Water Treatment',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_highlight_2_value', array(
        'label' => __('Highlight 2 - Nilai', 'inviro'),
        'description' => __('Nilai untuk kotak highlight kedua', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_highlight_3_title', array(
        'default' => 'Divisi',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_highlight_3_title', array(
        'label' => __('Highlight 3 - Judul', 'inviro'),
        'description' => __('Judul untuk kotak highlight ketiga (contoh: Divisi)', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_highlight_3_value', array(
        'default' => 'INVIRO™ [Water Solution]',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_highlight_3_value', array(
        'label' => __('Highlight 3 - Nilai', 'inviro'),
        'description' => __('Nilai untuk kotak highlight ketiga', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    // ============================================
    // Layanan Section Settings
    // ============================================
    $wp_customize->add_setting('inviro_profil_layanan_title', array(
        'default' => 'Layanan',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_layanan_title', array(
        'label' => __('Layanan - Judul', 'inviro'),
        'description' => __('Judul untuk section Layanan', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));
    $wp_customize->add_setting('inviro_profil_layanan_description', array(
        'default' => 'Divisi water treatment/water purifier CV. INDO SOLUTION yang bernama INVIRO™ [Water Solution] menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air, kami menyediakan jasa dan produk:',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('inviro_profil_layanan_description', array(
        'label' => __('Layanan - Deskripsi', 'inviro'),
        'description' => __('Deskripsi yang ditampilkan di atas grid layanan. Untuk menambah/mengubah layanan, gunakan menu "Layanan" di sidebar WordPress.', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'textarea',
    ));

    // ============================================
    // Video & Legalitas Section Settings
    // ============================================
    $wp_customize->add_setting('inviro_profil_youtube_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('inviro_profil_youtube_url', array(
        'label' => __('Video YouTube - URL', 'inviro'),
        'description' => __('Masukkan URL lengkap video YouTube (contoh: https://www.youtube.com/watch?v=VIDEO_ID atau https://youtu.be/VIDEO_ID)', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'url',
    ));

    // Video & Legalitas Section Headers
    $wp_customize->add_setting('inviro_profil_video_legalitas_title', array(
        'default' => 'Video Proses Bisnis & Legalitas',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_video_legalitas_title', array(
        'label' => __('Video & Legalitas - Judul Utama', 'inviro'),
        'description' => __('Judul utama untuk section Video & Legalitas', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_video_legalitas_subtitle', array(
        'default' => 'Tonton proses bisnis kami dan lihat legalitas perusahaan',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_video_legalitas_subtitle', array(
        'label' => __('Video & Legalitas - Subtitle', 'inviro'),
        'description' => __('Subtitle untuk section Video & Legalitas', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_video_title', array(
        'default' => 'Video Proses Bisnis INVIRO',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_video_title', array(
        'label' => __('Video - Judul', 'inviro'),
        'description' => __('Judul untuk section video', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_legalitas_title', array(
        'default' => 'Data Terkait Legalitas',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_legalitas_title', array(
        'label' => __('Legalitas - Judul', 'inviro'),
        'description' => __('Judul untuk section data legalitas', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_legalitas_intro', array(
        'default' => 'Adapun Legalitas Perusahaan kami adalah sebagai berikut:',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_legalitas_intro', array(
        'label' => __('Legalitas - Teks Pengantar', 'inviro'),
        'description' => __('Teks pengantar sebelum daftar data legalitas', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    // Legalitas Data Fields
    $legalitas_fields = array(
        'alamat' => array('label' => 'Alamat Perusahaan', 'default' => 'Jl. Parangtritis Km. 4,5 Yogyakarta'),
        'bidang_usaha' => array('label' => 'Bidang Usaha', 'default' => 'Peralatan Filter Air'),
        'telepon' => array('label' => 'No. Telepon', 'default' => '0274–385 322'),
        'akta_pendirian' => array('label' => 'Akta Pendirian', 'default' => 'No. 01 Tanggal 27 Juli 2009 Notaris Dewi Lestari, S.H'),
        'akta_perubahan' => array('label' => 'Akta Perubahan', 'default' => '115/CV/III/2018/KUM.01.01.PHBH'),
        'pengesahan' => array('label' => 'Pengesahan', 'default' => 'No. 162/CV/VIII/2009 Kum. 01.01/Pengadilan Negeri Bantul'),
        'ho' => array('label' => 'HO', 'default' => '12/Pem/Pgh/2018'),
        'siup' => array('label' => 'SIUP', 'default' => '1050DPMPT/007/III/2018'),
        'tdp' => array('label' => 'TDP', 'default' => '1051/DPMPT/099/III/2018'),
        'npwp' => array('label' => 'NPWP', 'default' => '21.111.248.7-543.000'),
        'pkp' => array('label' => 'PKP', 'default' => 'S-144PKP/WPJ.23/KP.0503/2018'),
        'email' => array('label' => 'Email', 'default' => 'inviro.co.id[at]gmail.com'),
    );

    foreach ($legalitas_fields as $key => $field) {
        $wp_customize->add_setting("inviro_profil_legalitas_{$key}", array(
            'default' => $field['default'],
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("inviro_profil_legalitas_{$key}", array(
            'label' => sprintf(__('Legalitas - %s', 'inviro'), $field['label']),
            'description' => sprintf(__('Nilai untuk %s', 'inviro'), $field['label']),
            'section' => 'inviro_profil',
            'type' => 'text',
        ));
    }

    $wp_customize->add_setting('inviro_profil_sertifikat_title', array(
        'default' => 'Sertifikat',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_sertifikat_title', array(
        'label' => __('Sertifikat - Judul', 'inviro'),
        'description' => __('Judul untuk section sertifikat', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    // Sertifikat Settings (hanya 1)
    $wp_customize->add_setting('inviro_profil_cert_1_title', array(
        'default' => 'Legalitas INVIRO',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_cert_1_title', array(
        'label' => __('Sertifikat - Judul', 'inviro'),
        'description' => __('Judul untuk sertifikat legalitas (opsional, akan digunakan sebagai alt text)', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_cert_1_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'inviro_profil_cert_1_image', array(
        'label' => __('Sertifikat - Gambar', 'inviro'),
        'description' => __('Upload gambar sertifikat legalitas perusahaan. Gambar akan ditampilkan full width di section Legalitas.', 'inviro'),
        'section' => 'inviro_profil',
    )));

    // ============================================
    // CTA Section Settings
    // ============================================
    $wp_customize->add_setting('inviro_profil_cta_title', array(
        'default' => 'Mari Bergabung Bersama Kami',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_cta_title', array(
        'label' => __('CTA - Judul', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_cta_subtitle', array(
        'default' => 'Hubungi kami untuk solusi pengolahan air terbaik',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_cta_subtitle', array(
        'label' => __('CTA - Subtitle', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_cta_button', array(
        'default' => 'Hubungi Kami',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_profil_cta_button', array(
        'label' => __('CTA - Teks Button', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_profil_cta_link', array(
        'default' => '#contact',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('inviro_profil_cta_link', array(
        'label' => __('CTA - Link', 'inviro'),
        'description' => __('Link tujuan saat button diklik (contoh: #contact untuk scroll ke section contact)', 'inviro'),
        'section' => 'inviro_profil',
        'type' => 'url',
    ));
}
add_action('customize_register', 'inviro_profil_customize_register');

/**
 * Izinkan front-page template untuk halaman biasa
 * Sehingga user bisa membuat halaman dengan desain beranda
 */
function inviro_add_frontpage_template_to_pages($templates) {
    $templates['front-page.php'] = 'Desain Beranda';
    return $templates;
}
add_filter('theme_page_templates', 'inviro_add_frontpage_template_to_pages');

/**
 * Load front-page CSS untuk halaman yang menggunakan front-page template
 */
function inviro_load_frontpage_css_for_custom_pages() {
    if (is_page() && get_page_template_slug() === 'front-page.php') {
        wp_enqueue_style('inviro-front-page', get_template_directory_uri() . '/assets/css/front-page.css', array('inviro-base'), '1.0.0');
    }
}
add_action('wp_enqueue_scripts', 'inviro_load_frontpage_css_for_custom_pages', 11);

/**
 * Pelanggan Page Customizer Settings
 */
function inviro_pelanggan_customize_register($wp_customize) {
    // Pelanggan Section
    $wp_customize->add_section('inviro_pelanggan', array(
        'title' => __('Halaman Pelanggan', 'inviro'),
        'priority' => 36,
    ));

    // Hero Settings
    $wp_customize->add_setting('inviro_pelanggan_hero_title', array(
        'default' => 'Pengguna Produk INVIRO di Indonesia',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_hero_title', array(
        'label' => __('Hero - Judul', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_pelanggan_hero_subtitle', array(
        'default' => 'Dipercaya oleh ratusan perusahaan terkemuka di Indonesia',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_hero_subtitle', array(
        'label' => __('Hero - Subtitle', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'text',
    ));

    // Company Logos
    $wp_customize->add_setting('inviro_pelanggan_logos_title', array(
        'default' => 'Corporate Protofolio Project by INVIRO',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_logos_title', array(
        'label' => __('Logos - Judul Utama', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_pelanggan_logos_subtitle', array(
        'default' => 'Dipercaya oleh perusahaan terkemuka',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_logos_subtitle', array(
        'label' => __('Logos - Subtitle', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'text',
    ));

    // 12 Company Logos
    for ($i = 1; $i <= 12; $i++) {
        $wp_customize->add_setting("inviro_pelanggan_logo_{$i}_name", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("inviro_pelanggan_logo_{$i}_name", array(
            'label' => sprintf(__('Logo %d - Nama Perusahaan', 'inviro'), $i),
            'section' => 'inviro_pelanggan',
            'type' => 'text',
        ));

        $wp_customize->add_setting("inviro_pelanggan_logo_{$i}_image", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "inviro_pelanggan_logo_{$i}_image", array(
            'label' => sprintf(__('Logo %d - Gambar', 'inviro'), $i),
            'section' => 'inviro_pelanggan',
        )));
    }

    // CTA Settings
    $wp_customize->add_setting('inviro_pelanggan_cta_title', array(
        'default' => 'Bergabunglah dengan Pelanggan Kami',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_cta_title', array(
        'label' => __('CTA - Judul', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_pelanggan_cta_subtitle', array(
        'default' => 'Dapatkan solusi terbaik untuk bisnis depot air minum Anda',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_cta_subtitle', array(
        'label' => __('CTA - Subtitle', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_pelanggan_cta_button', array(
        'default' => 'Hubungi Kami Sekarang',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_cta_button', array(
        'label' => __('CTA - Teks Button', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_pelanggan_cta_link', array(
        'default' => '#kontak',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('inviro_pelanggan_cta_link', array(
        'label' => __('CTA - Link', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'url',
    ));
}
add_action('customize_register', 'inviro_pelanggan_customize_register');

/**
 * Paket Usaha Page Customizer Settings
 */
function inviro_paket_customize_register($wp_customize) {
    // Paket Usaha Section
    $wp_customize->add_section('inviro_paket', array(
        'title' => __('Halaman Paket Usaha', 'inviro'),
        'priority' => 37,
    ));

    // Hero Settings
    $wp_customize->add_setting('inviro_paket_hero_title', array(
        'default' => 'Paket Usaha',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_hero_title', array(
        'label' => __('Hero - Judul', 'inviro'),
        'section' => 'inviro_paket',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_paket_hero_subtitle', array(
        'default' => 'INVIRO menyediakan paket mulai dari Depot Air Minum Isi Ulang (DAMIU), mesin RO, Water Treatment Plant, Produk AMDK, dan lain-lain.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('inviro_paket_hero_subtitle', array(
        'label' => __('Hero - Subtitle', 'inviro'),
        'section' => 'inviro_paket',
        'type' => 'textarea',
    ));

    // Features Settings
    $wp_customize->add_setting('inviro_paket_features_title', array(
        'default' => 'Keunggulan Paket Usaha INVIRO',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_features_title', array(
        'label' => __('Features - Judul Utama', 'inviro'),
        'section' => 'inviro_paket',
        'type' => 'text',
    ));

    // 4 Features
    for ($i = 1; $i <= 4; $i++) {
        $wp_customize->add_setting("inviro_paket_feature_{$i}_icon", array(
            'default' => '✓',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("inviro_paket_feature_{$i}_icon", array(
            'label' => sprintf(__('Feature %d - Icon (emoji/text)', 'inviro'), $i),
            'section' => 'inviro_paket',
            'type' => 'text',
        ));

        $wp_customize->add_setting("inviro_paket_feature_{$i}_title", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("inviro_paket_feature_{$i}_title", array(
            'label' => sprintf(__('Feature %d - Judul', 'inviro'), $i),
            'section' => 'inviro_paket',
            'type' => 'text',
        ));

        $wp_customize->add_setting("inviro_paket_feature_{$i}_desc", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("inviro_paket_feature_{$i}_desc", array(
            'label' => sprintf(__('Feature %d - Deskripsi', 'inviro'), $i),
            'section' => 'inviro_paket',
            'type' => 'textarea',
        ));
    }

    // CTA Settings
    $wp_customize->add_setting('inviro_paket_cta_title', array(
        'default' => 'Siap Memulai Bisnis Depot Air Minum?',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_cta_title', array(
        'label' => __('CTA - Judul', 'inviro'),
        'section' => 'inviro_paket',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_paket_cta_subtitle', array(
        'default' => 'Konsultasikan kebutuhan bisnis Anda dengan tim ahli kami',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_cta_subtitle', array(
        'label' => __('CTA - Subtitle', 'inviro'),
        'section' => 'inviro_paket',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_paket_cta_button', array(
        'default' => 'Konsultasi Gratis',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_cta_button', array(
        'label' => __('CTA - Teks Button', 'inviro'),
        'section' => 'inviro_paket',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_paket_cta_link', array(
        'default' => '#kontak',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('inviro_paket_cta_link', array(
        'label' => __('CTA - Link', 'inviro'),
        'section' => 'inviro_paket',
        'type' => 'url',
    ));
}
add_action('customize_register', 'inviro_paket_customize_register');

/**
 * Spare Parts Page Customizer Settings
 */
function inviro_spareparts_customize_register($wp_customize) {
    // Spare Parts Section
    $wp_customize->add_section('inviro_spareparts', array(
        'title' => __('Halaman Spare Parts', 'inviro'),
        'priority' => 38,
        'description' => __('Kustomisasi konten halaman Spare Parts', 'inviro'),
    ));

    // Hero Settings
    $wp_customize->add_setting('inviro_spareparts_hero_title', array(
        'default' => 'Spare Parts Premium',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_spareparts_hero_title', array(
        'label' => __('Hero - Judul', 'inviro'),
        'description' => __('Judul utama di hero section', 'inviro'),
        'section' => 'inviro_spareparts',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_spareparts_hero_subtitle', array(
        'default' => 'Solusi lengkap spare parts berkualitas tinggi untuk mesin pengolahan air Anda. Dapatkan performa optimal dengan komponen asli dan terpercaya.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('inviro_spareparts_hero_subtitle', array(
        'label' => __('Hero - Subtitle', 'inviro'),
        'description' => __('Deskripsi di hero section', 'inviro'),
        'section' => 'inviro_spareparts',
        'type' => 'textarea',
    ));

    // Search Placeholder
    $wp_customize->add_setting('inviro_spareparts_search_placeholder', array(
        'default' => 'Cari spare part yang Anda butuhkan...',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_spareparts_search_placeholder', array(
        'label' => __('Search - Placeholder Text', 'inviro'),
        'description' => __('Teks placeholder untuk search input', 'inviro'),
        'section' => 'inviro_spareparts',
        'type' => 'text',
    ));

    // CTA Settings
    $wp_customize->add_setting('inviro_spareparts_cta_title', array(
        'default' => 'Butuh Konsultasi Spesialis?',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_spareparts_cta_title', array(
        'label' => __('CTA - Judul', 'inviro'),
        'description' => __('Judul untuk section CTA di bawah', 'inviro'),
        'section' => 'inviro_spareparts',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_spareparts_cta_subtitle', array(
        'default' => 'Tim ahli kami siap membantu Anda menemukan spare part yang tepat untuk kebutuhan mesin pengolahan air Anda',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('inviro_spareparts_cta_subtitle', array(
        'label' => __('CTA - Subtitle', 'inviro'),
        'description' => __('Deskripsi untuk section CTA', 'inviro'),
        'section' => 'inviro_spareparts',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('inviro_spareparts_cta_button', array(
        'default' => 'Chat WhatsApp',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_spareparts_cta_button', array(
        'label' => __('CTA - Teks Button', 'inviro'),
        'description' => __('Teks untuk tombol CTA', 'inviro'),
        'section' => 'inviro_spareparts',
        'type' => 'text',
    ));
}
add_action('customize_register', 'inviro_spareparts_customize_register');

/**
 * Paket Usaha Customizer Settings
 */
function inviro_paket_usaha_customize_register($wp_customize) {
    // Paket Usaha Section
    $wp_customize->add_section('inviro_paket_usaha', array(
        'title' => __('Halaman Paket Usaha', 'inviro'),
        'priority' => 35,
    ));

    // Hero Settings
    $wp_customize->add_setting('inviro_paket_usaha_hero_title', array(
        'default' => 'Paket Usaha Premium',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_usaha_hero_title', array(
        'label' => __('Hero - Judul', 'inviro'),
        'section' => 'inviro_paket_usaha',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_paket_usaha_hero_subtitle', array(
        'default' => 'Solusi lengkap paket usaha berkualitas tinggi untuk bisnis depot air minum Anda. Dapatkan paket terbaik dengan komponen lengkap dan terpercaya.',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_usaha_hero_subtitle', array(
        'label' => __('Hero - Subtitle', 'inviro'),
        'section' => 'inviro_paket_usaha',
        'type' => 'textarea',
    ));

    // Search Placeholder
    $wp_customize->add_setting('inviro_paket_usaha_search_placeholder', array(
        'default' => 'Cari paket usaha yang Anda butuhkan...',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_usaha_search_placeholder', array(
        'label' => __('Search - Placeholder', 'inviro'),
        'section' => 'inviro_paket_usaha',
        'type' => 'text',
    ));

    // CTA Settings
    $wp_customize->add_setting('inviro_paket_usaha_cta_title', array(
        'default' => 'Butuh Konsultasi Spesialis?',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_usaha_cta_title', array(
        'label' => __('CTA - Judul', 'inviro'),
        'section' => 'inviro_paket_usaha',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_paket_usaha_cta_subtitle', array(
        'default' => 'Tim ahli kami siap membantu Anda menemukan paket usaha yang tepat untuk kebutuhan bisnis depot air minum Anda',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_usaha_cta_subtitle', array(
        'label' => __('CTA - Subtitle', 'inviro'),
        'section' => 'inviro_paket_usaha',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('inviro_paket_usaha_cta_button', array(
        'default' => 'Chat WhatsApp',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_paket_usaha_cta_button', array(
        'label' => __('CTA - Teks Button', 'inviro'),
        'section' => 'inviro_paket_usaha',
        'type' => 'text',
    ));
}
add_action('customize_register', 'inviro_paket_usaha_customize_register');

add_action('wp_enqueue_scripts', 'inviro_load_frontpage_css_for_custom_pages', 11);


