<?php
/**
 * Paket Usaha Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Paket Usaha Custom Post Type
 */
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

