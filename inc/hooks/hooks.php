<?php
/**
 * Hooks and Filters
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

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
 * Post Views Counter for Paket Usaha
 */
function inviro_set_paket_views($post_id) {
    if (get_post_type($post_id) !== 'paket_usaha') {
        return;
    }
    
    $count_key = '_paket_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

function inviro_get_paket_views($post_id) {
    $count_key = '_paket_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
        return 0;
    }
    
    return $count;
}

function inviro_track_paket_views() {
    if (is_singular('paket_usaha') && !is_admin()) {
        global $post;
        if ($post && get_post_type($post->ID) === 'paket_usaha') {
            inviro_set_paket_views($post->ID);
        }
    }
}
add_action('wp_head', 'inviro_track_paket_views');

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
 * Fix Artikel Detail Image Sizing
 * Memastikan semua gambar di detail artikel memiliki ukuran yang sama
 */
function inviro_fix_artikel_image_sizing($content) {
    // Hanya jalankan di halaman detail artikel dan proyek pelanggan
    if ((!is_singular('artikel') && !is_singular('proyek_pelanggan')) || is_admin()) {
        return $content;
    }

    // Deteksi ukuran layar (default desktop)
    $is_mobile = wp_is_mobile();
    $is_tablet = false;
    
    if ($is_mobile) {
        // Cek apakah benar-benar mobile atau tablet
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (preg_match('/tablet|ipad|playbook|silk/i', $user_agent)) {
            $is_tablet = true;
            $is_mobile = false;
        }
    }

    // Tentukan tinggi berdasarkan ukuran layar - sama dengan spareparts detail
    $height = '655px'; // Desktop
    if ($is_mobile || $is_tablet) {
        $height = '400px'; // Tablet & Mobile
    }

    // Modifikasi semua tag img di dalam artikel-content-full
    $content = preg_replace_callback(
        '/<img([^>]*?)>/i',
        function($matches) use ($height) {
            $img_tag = $matches[0];
            $img_attrs = $matches[1];
            
            // Hapus style yang sudah ada (jika ada)
            $img_attrs = preg_replace('/style\s*=\s*["\'][^"\']*["\']/i', '', $img_attrs);
            
            // Tambahkan inline styles untuk memastikan ukuran
            $new_style = sprintf(
                'style="width: 100%% !important; height: %s !important; max-width: 100%% !important; max-height: %s !important; min-height: %s !important; object-fit: cover !important; object-position: center !important; display: block !important; visibility: visible !important; opacity: 1 !important; border-radius: 12px; margin: 0 !important; padding: 0 !important; position: relative; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);"',
                $height,
                $height,
                $height
            );
            
            return '<img' . $img_attrs . ' ' . $new_style . '>';
        },
        $content
    );

    // Modifikasi wrapper figure dan wp-block-image
    $content = preg_replace_callback(
        '/<(figure|div[^>]*class=["\'][^"\']*wp-block-image[^"\']*["\'][^>]*)>/i',
        function($matches) use ($height) {
            $tag = $matches[0];
            $tag_name = $matches[1];
            
            // Hapus style yang sudah ada
            $tag = preg_replace('/style\s*=\s*["\'][^"\']*["\']/i', '', $tag);
            
            // Tambahkan inline styles untuk wrapper
            $new_style = sprintf(
                'style="margin: 30px 0; width: 100%%; height: %s !important; max-height: %s !important; min-height: %s !important; overflow: hidden; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); position: relative; display: block; line-height: 0; padding: 0;"',
                $height,
                $height,
                $height
            );
            
            // Insert style sebelum closing bracket
            $tag = preg_replace('/>$/i', ' ' . $new_style . '>', $tag);
            
            return $tag;
        },
        $content
    );

    return $content;
}
add_filter('the_content', 'inviro_fix_artikel_image_sizing', 999);

/**
 * Create dummy layanan data (Development Only)
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

/**
 * Prioritize spareparts page over archive
 */
function inviro_prioritize_spareparts_page() {
    // Skip if already a page or single post - don't interfere
    if (is_page('spareparts') || is_singular('spareparts')) {
        return;
    }
    
    // Check if we're accessing /spareparts/ URL (exact match or with query params)
    $request_uri = $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($request_uri);
    $path = isset($parsed_url['path']) ? trim($parsed_url['path'], '/') : '';
    
    // Remove WordPress subdirectory from path if exists
    $home_path = trim(parse_url(home_url('/'), PHP_URL_PATH), '/');
    if ($home_path && strpos($path, $home_path) === 0) {
        $path = trim(substr($path, strlen($home_path)), '/');
    }
    
    // Only process if we're on the exact /spareparts/ path (not single posts or other paths)
    // Also check that path doesn't contain additional segments (like /spareparts/some-slug)
    if ($path === 'spareparts' || $path === 'spareparts/') {
        global $wp_query;
        
        // Only intervene if WordPress is treating this as an archive when it should be a page
        // AND we're not already on a page
        if (($wp_query->is_post_type_archive('spareparts') || ($wp_query->is_archive && !$wp_query->is_page)) && !$wp_query->is_page) {
            // Try to find spareparts page
            $spareparts_page = get_page_by_path('spareparts');
            if (!$spareparts_page) {
                // Try alternative: find page by template
                $pages = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-spareparts.php',
                    'number' => 1
                ));
                if (!empty($pages)) {
                    $spareparts_page = $pages[0];
                }
            }
            
            // If page exists, prioritize it over archive
            if ($spareparts_page) {
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->is_404 = false;
                $wp_query->is_archive = false;
                $wp_query->is_post_type_archive = false;
                $wp_query->queried_object = $spareparts_page;
                $wp_query->queried_object_id = $spareparts_page->ID;
                $wp_query->posts = array($spareparts_page);
                $wp_query->post_count = 1;
                $wp_query->found_posts = 1;
                $wp_query->max_num_pages = 1;
            }
        }
    }
}

/**
 * Ensure spareparts page query is set correctly
 * This ensures the page template can access the page object
 */
function inviro_ensure_spareparts_page_query() {
    // Only run if we're on spareparts URL and not admin
    if (is_admin()) {
        return;
    }
    
    // Skip if already a page - don't interfere
    if (is_page('spareparts')) {
        return;
    }
    
    $request_uri = $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($request_uri);
    $path = isset($parsed_url['path']) ? trim($parsed_url['path'], '/') : '';
    
    // Remove WordPress subdirectory from path if exists
    $home_path = trim(parse_url(home_url('/'), PHP_URL_PATH), '/');
    if ($home_path && strpos($path, $home_path) === 0) {
        $path = trim(substr($path, strlen($home_path)), '/');
    }
    
    // Check if we're on /spareparts/ (exact match, not single posts)
    if ($path === 'spareparts' || $path === 'spareparts/') {
        global $wp_query;
        
        // If query is empty or 404, or if it's being treated as archive, try to find and set the page
        if (empty($wp_query->posts) || $wp_query->is_404 || ($wp_query->is_post_type_archive('spareparts') && !$wp_query->is_page)) {
            $spareparts_page = get_page_by_path('spareparts');
            if (!$spareparts_page) {
                // Try alternative: find page by template
                $pages = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-spareparts.php',
                    'number' => 1
                ));
                if (!empty($pages)) {
                    $spareparts_page = $pages[0];
                }
            }
            
            // If page exists, set up query
            if ($spareparts_page) {
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->is_404 = false;
                $wp_query->is_archive = false;
                $wp_query->is_post_type_archive = false;
                $wp_query->queried_object = $spareparts_page;
                $wp_query->queried_object_id = $spareparts_page->ID;
                $wp_query->posts = array($spareparts_page);
                $wp_query->post_count = 1;
                $wp_query->found_posts = 1;
                $wp_query->max_num_pages = 1;
            }
        }
    }
}
// Run early to ensure query vars are set before navbar is rendered
add_action('template_redirect', 'inviro_ensure_spareparts_page_query', 1);

/**
 * Handle dummy sparepart detail page
 * Ensure page-spareparts.php template is used when dummy_id is present
 */
function inviro_handle_dummy_sparepart_detail() {
    // Check if dummy_id is present in query string
    if (isset($_GET['dummy_id']) && intval($_GET['dummy_id']) > 0) {
        // Check if we're on spareparts page or trying to access dummy detail
        $request_uri = $_SERVER['REQUEST_URI'];
        $parsed_uri = parse_url($request_uri);
        $path = isset($parsed_uri['path']) ? trim($parsed_uri['path'], '/') : '';
        
        // Remove WordPress subdirectory from path if exists
        $home_path = trim(parse_url(home_url('/'), PHP_URL_PATH), '/');
        if ($home_path && strpos($path, $home_path) === 0) {
            $path = trim(substr($path, strlen($home_path)), '/');
        }
        
        if (strpos($path, 'spareparts') !== false) {
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
                $wp_query->is_archive = false;
                $wp_query->is_post_type_archive = false;
                $wp_query->queried_object = $spareparts_page;
                $wp_query->queried_object_id = $spareparts_page->ID;
                $wp_query->posts = array($spareparts_page);
                $wp_query->post_count = 1;
                $wp_query->found_posts = 1;
                $wp_query->max_num_pages = 1;
            }
        }
    }
}
// Run early to ensure query vars are set before navbar is rendered
add_action('template_redirect', 'inviro_handle_dummy_sparepart_detail', 3);

/**
 * Handle dummy paket usaha detail page
 * Ensure page-paket-usaha.php template is used when dummy_id is present
 */
function inviro_handle_dummy_paket_usaha_detail() {
    // Check if dummy_id is present in query string
    if (isset($_GET['dummy_id']) && intval($_GET['dummy_id']) > 0) {
        // Check if we're on paket-usaha page or trying to access dummy detail
        $request_uri = $_SERVER['REQUEST_URI'];
        $parsed_uri = parse_url($request_uri);
        $path = isset($parsed_uri['path']) ? trim($parsed_uri['path'], '/') : '';
        
        // Remove WordPress subdirectory from path if exists
        $home_path = trim(parse_url(home_url('/'), PHP_URL_PATH), '/');
        if ($home_path && strpos($path, $home_path) === 0) {
            $path = trim(substr($path, strlen($home_path)), '/');
        }
        
        if (strpos($path, 'paket-usaha') !== false) {
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
// Run early to ensure query vars are set before navbar is rendered
add_action('template_redirect', 'inviro_handle_dummy_paket_usaha_detail', 2);

/**
 * Force template for spareparts and paket usaha pages
 * Prioritize page template over archive template
 */
function inviro_force_spareparts_template($template) {
    $request_uri = $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($request_uri);
    $path = isset($parsed_url['path']) ? trim($parsed_url['path'], '/') : '';
    
    // Remove WordPress subdirectory from path if exists
    $home_path = trim(parse_url(home_url('/'), PHP_URL_PATH), '/');
    if ($home_path && strpos($path, $home_path) === 0) {
        $path = trim(substr($path, strlen($home_path)), '/');
    }
    
    // Check if we're on /spareparts/ (exact match, not single posts)
    if ($path === 'spareparts' || $path === 'spareparts/') {
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
                    // Set query vars if needed
                    global $wp_query;
                    if (!$wp_query->is_page || empty($wp_query->posts)) {
                        $wp_query->is_page = true;
                        $wp_query->is_singular = true;
                        $wp_query->is_404 = false;
                        $wp_query->is_archive = false;
                        $wp_query->is_post_type_archive = false;
                        $wp_query->queried_object = $spareparts_page;
                        $wp_query->queried_object_id = $spareparts_page->ID;
                        $wp_query->posts = array($spareparts_page);
                        $wp_query->post_count = 1;
                        $wp_query->found_posts = 1;
                        $wp_query->max_num_pages = 1;
                    }
                    return $template_path;
                }
            }
        }
    }
    
    // Check if we're on /paket-usaha/ (exact match, not single posts)
    // Path already processed above
    if ($path === 'paket-usaha' || $path === 'paket-usaha/') {
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
        
        // If paket-usaha page exists, use its template
        if ($paket_usaha_page) {
            $page_template = get_page_template_slug($paket_usaha_page->ID);
            if ($page_template == 'page-paket-usaha.php' || empty($page_template)) {
                $template_path = locate_template('page-paket-usaha.php');
                if ($template_path) {
                    // Set query vars if needed
                    global $wp_query;
                    if (!$wp_query->is_page || empty($wp_query->posts)) {
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
                    return $template_path;
                }
            }
        }
    }
    
    // Force single-paket-usaha.php template for single paket_usaha posts
    if (is_singular('paket_usaha')) {
        $single_template = locate_template('single-paket-usaha.php');
        if ($single_template) {
            return $single_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'inviro_force_spareparts_template', 99);

