<?php
/**
 * Testimonials Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Testimonials Custom Post Type
 */
function inviro_register_testimonials() {
    $labels = array(
        'name'               => __('Testimoni', 'inviro'),
        'singular_name'      => __('Testimoni', 'inviro'),
        'menu_name'          => __('Testimoni', 'inviro'),
        'add_new'            => __('Tambah Testimoni', 'inviro'),
        'add_new_item'       => __('Tambah Testimoni Baru', 'inviro'),
        'edit_item'          => __('Edit Testimoni', 'inviro'),
        'new_item'           => __('Testimoni Baru', 'inviro'),
        'view_item'          => __('Lihat Testimoni', 'inviro'),
        'search_items'       => __('Cari Testimoni', 'inviro'),
        'not_found'          => __('Testimoni tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada testimoni di trash', 'inviro')
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
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-format-quote',
        'supports'            => array('title', 'thumbnail'),
        'show_in_rest'        => true
    );
    
    register_post_type('testimoni', $args);
}
add_action('init', 'inviro_register_testimonials');

