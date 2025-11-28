<?php
/**
 * SparePart Reviews Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Spare Part Reviews Custom Post Type
 */
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

