<?php
/**
 * Branches Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Branches Custom Post Type
 */
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

