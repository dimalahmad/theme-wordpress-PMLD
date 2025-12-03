<?php
/**
 * Product Reviews Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Product Reviews Custom Post Type
 */
function inviro_register_product_reviews() {
    $labels = array(
        'name'               => __('Ulasan Produk', 'inviro'),
        'singular_name'      => __('Ulasan', 'inviro'),
        'menu_name'          => __('Ulasan Produk', 'inviro'),
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
        // Tampilkan sebagai submenu di bawah CPT Produk
        'show_in_menu'        => 'edit.php?post_type=produk',
        'query_var'           => true,
        'rewrite'             => false,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'supports'            => array('title', 'editor'),
        'show_in_rest'        => false,
    );
    
    register_post_type('product_review', $args);
}
add_action('init', 'inviro_register_product_reviews');



