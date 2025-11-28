<?php
/**
 * Products Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Products Custom Post Type
 */
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


