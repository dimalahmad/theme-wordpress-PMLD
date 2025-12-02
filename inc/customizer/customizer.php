<?php
/**
 * Customizer Settings
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Customizer Register Function
 */
function inviro_customize_register($wp_customize) {
    // Include custom control class
    require_once get_template_directory() . '/inc/customizer/class-multiple-select-posts-control.php';
    
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
        'description' => __('Masukkan jumlah produk yang akan ditampilkan di halaman depan (max 12)', 'inviro'),
        'section'     => 'inviro_products',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
    ));
    
    // Get all produk posts for dropdown
    $produk_posts = get_posts(array(
        'post_type' => 'produk',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'post_status' => 'publish'
    ));
    
    $product_choices = array('' => __('-- Pilih Produk --', 'inviro'));
    if (!empty($produk_posts)) {
        foreach ($produk_posts as $produk) {
            $product_choices[$produk->ID] = $produk->post_title;
        }
    }
    
    // Featured products - manual selection
    for ($i = 1; $i <= 12; $i++) {
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
        'description' => __('Masuk ke Google Maps â†’ Pilih lokasi â†’ Klik "Share" â†’ Tab "Embed a map" â†’ Copy URL dari src="..." atau full iframe code â†’ Paste di sini', 'inviro'),
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
    
    // Hero Display Mode
    $wp_customize->add_setting('inviro_hero_display_mode', array(
        'default'           => 'selected',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('inviro_hero_display_mode', array(
        'label'       => __('Mode Tampilan Proyek', 'inviro'),
        'description' => __('Pilih apakah menampilkan proyek terbaru atau proyek yang dipilih secara manual', 'inviro'),
        'section'     => 'inviro_stats',
        'type'        => 'select',
        'choices'     => array(
            'latest'   => __('Proyek Terbaru (4 proyek)', 'inviro'),
            'selected' => __('Proyek Terpilih (maksimal 4 proyek)', 'inviro'),
        ),
        'priority'    => 4,
    ));
    
    // Hero Section Projects Selection
    $wp_customize->add_setting('inviro_hero_selected_projects', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new Inviro_Multiple_Select_Posts_Control(
        $wp_customize,
        'inviro_hero_selected_projects',
        array(
            'label'       => __('Pilih Proyek untuk Hero Section', 'inviro'),
            'description' => __('Pilih maksimal 4 proyek pelanggan yang akan ditampilkan di Hero Section homepage. Proyek pertama akan ditampilkan lebih besar.', 'inviro'),
            'section'     => 'inviro_stats',
            'post_type'   => 'proyek_pelanggan',
            'max_posts'   => 4,
            'priority'    => 5,
        )
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
            '0deg'   => __('Atas ke Bawah â†“', 'inviro'),
            '90deg'  => __('Kiri ke Kanan â†’', 'inviro'),
            '180deg' => __('Bawah ke Atas â†‘', 'inviro'),
            '270deg' => __('Kanan ke Kiri â†', 'inviro'),
            '45deg'  => __('Diagonal â†—', 'inviro'),
            '135deg' => __('Diagonal â†˜', 'inviro'),
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
        'description' => __('Default: Â©{tahun} {nama_situs}. All rights reserved', 'inviro'),
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

Divisi water treatment/water purifier CV. INDO SOLUTION yang bernama INVIROâ„¢ [Water Solution] menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air',
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
        'default' => 'INVIROâ„¢ [Water Solution]',
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
        'default' => 'Divisi water treatment/water purifier CV. INDO SOLUTION yang bernama INVIROâ„¢ [Water Solution] menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air, kami menyediakan jasa dan produk:',
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
        'telepon' => array('label' => 'No. Telepon', 'default' => '0274â€“385 322'),
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

    // Search Placeholder
    $wp_customize->add_setting('inviro_pelanggan_search_placeholder', array(
        'default' => 'Cari proyek pelanggan...',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_search_placeholder', array(
        'label' => __('Search - Placeholder', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'text',
    ));

    // Region Clusters (JSON format for flexibility)
    $default_regions = json_encode(array(
        array('id' => 'sumatra', 'name' => 'Sumatra', 'slug' => 'sumatra', 'order' => 1),
        array('id' => 'jawa', 'name' => 'Jawa', 'slug' => 'jawa', 'order' => 2),
        array('id' => 'kalimantan', 'name' => 'Kalimantan', 'slug' => 'kalimantan', 'order' => 3),
        array('id' => 'sulawesi', 'name' => 'Sulawesi', 'slug' => 'sulawesi', 'order' => 4),
        array('id' => 'maluku', 'name' => 'Maluku', 'slug' => 'maluku', 'order' => 5),
        array('id' => 'nusa-tenggara', 'name' => 'Nusa Tenggara', 'slug' => 'nusa-tenggara', 'order' => 6),
        array('id' => 'papua', 'name' => 'Papua', 'slug' => 'papua', 'order' => 7),
    ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    $wp_customize->add_setting('inviro_pelanggan_region_clusters', array(
        'default' => $default_regions,
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_region_clusters', array(
        'label' => __('Cluster Wilayah (JSON)', 'inviro'),
        'description' => __('Format JSON untuk cluster wilayah. Contoh: [{"id":"sumatra","name":"Sumatra","slug":"sumatra","order":1}]', 'inviro'),
        'section' => 'inviro_pelanggan',
        'type' => 'textarea',
    ));

    // Company Logos
    $wp_customize->add_setting('inviro_pelanggan_logos_title', array(
        'default' => 'Corporate Portfolio Project by INVIRO',
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

    // Company Logos (JSON format for flexibility)
    $default_logos = json_encode(array(
        array('name' => 'PT. Perusahaan A', 'image' => 'https://via.placeholder.com/200x100/2F80ED/FFFFFF?text=Logo+1', 'order' => 1),
        array('name' => 'PT. Perusahaan B', 'image' => 'https://via.placeholder.com/200x100/4FB3E8/FFFFFF?text=Logo+2', 'order' => 2),
        array('name' => 'PT. Perusahaan C', 'image' => 'https://via.placeholder.com/200x100/75C6F1/FFFFFF?text=Logo+3', 'order' => 3),
        array('name' => 'PT. Perusahaan D', 'image' => 'https://via.placeholder.com/200x100/2F80ED/FFFFFF?text=Logo+4', 'order' => 4),
        array('name' => 'PT. Perusahaan E', 'image' => 'https://via.placeholder.com/200x100/4FB3E8/FFFFFF?text=Logo+5', 'order' => 5),
        array('name' => 'PT. Perusahaan F', 'image' => 'https://via.placeholder.com/200x100/75C6F1/FFFFFF?text=Logo+6', 'order' => 6),
    ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    $wp_customize->add_setting('inviro_pelanggan_company_logos', array(
        'default' => $default_logos,
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('inviro_pelanggan_company_logos', array(
        'label' => __('Logo Perusahaan (JSON)', 'inviro'),
        'description' => __('Format JSON untuk logo perusahaan. Contoh: [{"name":"Nama Perusahaan","image":"URL_GAMBAR","order":1}]', 'inviro'),
            'section' => 'inviro_pelanggan',
        'type' => 'textarea',
    ));

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
            'default' => 'âœ“',
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

/**
 * Customizer Settings for Artikel Page
 */
function inviro_artikel_customize_register($wp_customize) {
    // Artikel Section
    $wp_customize->add_section('inviro_artikel', array(
        'title' => __('Halaman Artikel', 'inviro'),
        'priority' => 39,
        'description' => __('Kustomisasi konten halaman Artikel', 'inviro'),
    ));

    // Hero Settings
    $wp_customize->add_setting('inviro_artikel_hero_title', array(
        'default' => 'Artikel dan Berita Terbaru',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_artikel_hero_title', array(
        'label' => __('Hero - Judul', 'inviro'),
        'description' => __('Judul utama di hero section', 'inviro'),
        'section' => 'inviro_artikel',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_artikel_hero_subtitle', array(
        'default' => 'Update terbaru dari Inviro untuk mendukung usaha dan kebutuhan air minum Anda',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('inviro_artikel_hero_subtitle', array(
        'label' => __('Hero - Subtitle', 'inviro'),
        'description' => __('Deskripsi di hero section', 'inviro'),
        'section' => 'inviro_artikel',
        'type' => 'textarea',
    ));

    // Search Placeholder
    $wp_customize->add_setting('inviro_artikel_search_placeholder', array(
        'default' => 'Cari artikel yang Anda butuhkan...',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_artikel_search_placeholder', array(
        'label' => __('Search - Placeholder', 'inviro'),
        'section' => 'inviro_artikel',
        'type' => 'text',
    ));

    // CTA Settings
    $wp_customize->add_setting('inviro_artikel_cta_title', array(
        'default' => 'Butuh Konsultasi Spesialis?',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_artikel_cta_title', array(
        'label' => __('CTA - Judul', 'inviro'),
        'section' => 'inviro_artikel',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_artikel_cta_subtitle', array(
        'default' => 'Tim ahli kami siap membantu Anda menemukan solusi terbaik untuk kebutuhan air minum Anda. Hubungi kami sekarang!',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('inviro_artikel_cta_subtitle', array(
        'label' => __('CTA - Subtitle', 'inviro'),
        'section' => 'inviro_artikel',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('inviro_artikel_cta_button', array(
        'default' => 'Hubungi via WhatsApp',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_artikel_cta_button', array(
        'label' => __('CTA - Teks Button', 'inviro'),
        'section' => 'inviro_artikel',
        'type' => 'text',
    ));

    $wp_customize->add_setting('inviro_artikel_cta_whatsapp', array(
        'default' => '6281234567890',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('inviro_artikel_cta_whatsapp', array(
        'label' => __('CTA - Nomor WhatsApp', 'inviro'),
        'description' => __('Nomor WhatsApp untuk CTA (tanpa + atau spasi, contoh: 6281234567890)', 'inviro'),
        'section' => 'inviro_artikel',
        'type' => 'text',
    ));
}
add_action('customize_register', 'inviro_artikel_customize_register');
