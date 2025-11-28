<?php
/**
 * Artikel Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Artikel Custom Post Type
 */
function inviro_register_artikel() {
    $labels = array(
        'name'               => __('Artikel', 'inviro'),
        'singular_name'      => __('Artikel', 'inviro'),
        'menu_name'          => __('Artikel', 'inviro'),
        'add_new'            => __('Tambah Artikel', 'inviro'),
        'add_new_item'       => __('Tambah Artikel Baru', 'inviro'),
        'edit_item'          => __('Edit Artikel', 'inviro'),
        'new_item'           => __('Artikel Baru', 'inviro'),
        'view_item'          => __('Lihat Artikel', 'inviro'),
        'search_items'       => __('Cari Artikel', 'inviro'),
        'not_found'          => __('Artikel tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada artikel di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'artikel'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 10,
        'menu_icon'           => 'dashicons-welcome-write-blog',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'show_in_rest'        => true,
    );
    
    register_post_type('artikel', $args);
}
add_action('init', 'inviro_register_artikel');

