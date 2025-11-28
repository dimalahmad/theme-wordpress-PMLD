<?php
/**
 * SpareParts Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register SpareParts Custom Post Type
 */
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

