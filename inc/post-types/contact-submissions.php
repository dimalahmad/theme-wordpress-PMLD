<?php
/**
 * Contact Submissions Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Contact Submissions Custom Post Type
 */
function inviro_register_contact_submissions() {
    $labels = array(
        'name'               => __('Pesan Kontak', 'inviro'),
        'singular_name'      => __('Pesan', 'inviro'),
        'menu_name'          => __('Pesan Kontak', 'inviro'),
        'add_new'            => __('Tambah Pesan', 'inviro'),
        'add_new_item'       => __('Tambah Pesan Baru', 'inviro'),
        'edit_item'          => __('Lihat Pesan', 'inviro'),
        'new_item'           => __('Pesan Baru', 'inviro'),
        'view_item'          => __('Lihat Pesan', 'inviro'),
        'search_items'       => __('Cari Pesan', 'inviro'),
        'not_found'          => __('Pesan tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada pesan di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => false,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 12,
        'menu_icon'           => 'dashicons-email-alt',
        'supports'            => array('title', 'editor'),
        'show_in_rest'        => false,
    );
    
    register_post_type('contact_submission', $args);
}
add_action('init', 'inviro_register_contact_submissions');

