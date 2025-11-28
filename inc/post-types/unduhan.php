<?php
/**
 * Unduhan Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Unduhan Custom Post Type
 */
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

