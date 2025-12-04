<?php
/**
 * Corporate Project Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Corporate Project Custom Post Type
 */
function inviro_register_corporate_project() {
    $labels = array(
        'name'               => __('Corporate Project', 'inviro'),
        'singular_name'      => __('Corporate Project', 'inviro'),
        'menu_name'          => __('Corporate Project', 'inviro'),
        'add_new'            => __('Tambah Project', 'inviro'),
        'add_new_item'       => __('Tambah Project Baru', 'inviro'),
        'edit_item'          => __('Edit Project', 'inviro'),
        'new_item'           => __('Project Baru', 'inviro'),
        'view_item'          => __('Lihat Project', 'inviro'),
        'search_items'       => __('Cari Project', 'inviro'),
        'not_found'          => __('Project tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada project di trash', 'inviro')
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
        'menu_position'       => 13,
        'menu_icon'           => 'dashicons-building',
        'supports'            => array('title', 'thumbnail', 'page-attributes'),
        'show_in_rest'        => true
    );
    
    register_post_type('corporate_project', $args);
}
add_action('init', 'inviro_register_corporate_project');


