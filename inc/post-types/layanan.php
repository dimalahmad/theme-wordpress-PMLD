<?php
/**
 * Layanan Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Layanan Custom Post Type
 */
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

// Note: Dummy data creation function (inviro_create_dummy_layanan) is in inc/hooks/hooks.php

