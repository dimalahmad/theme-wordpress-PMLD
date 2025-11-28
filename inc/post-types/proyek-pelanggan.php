<?php
/**
 * Proyek Pelanggan Custom Post Type
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Proyek Pelanggan Custom Post Type
 */
function inviro_register_proyek_pelanggan() {
    $labels = array(
        'name'               => __('Proyek Pelanggan', 'inviro'),
        'singular_name'      => __('Proyek', 'inviro'),
        'menu_name'          => __('Proyek Pelanggan', 'inviro'),
        'add_new'            => __('Tambah Proyek', 'inviro'),
        'add_new_item'       => __('Tambah Proyek Baru', 'inviro'),
        'edit_item'          => __('Edit Proyek', 'inviro'),
        'new_item'           => __('Proyek Baru', 'inviro'),
        'view_item'          => __('Lihat Proyek', 'inviro'),
        'search_items'       => __('Cari Proyek', 'inviro'),
        'not_found'          => __('Proyek tidak ditemukan', 'inviro'),
        'not_found_in_trash' => __('Tidak ada proyek di trash', 'inviro')
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'proyek-pelanggan'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 8,
        'menu_icon'           => 'dashicons-businessperson',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
        'taxonomies'          => array('region')
    );
    
    register_post_type('proyek_pelanggan', $args);
}
add_action('init', 'inviro_register_proyek_pelanggan');

/**
 * Register Region Taxonomy for Projects
 */
function inviro_register_region_taxonomy() {
    $labels = array(
        'name'              => __('Daerah', 'inviro'),
        'singular_name'     => __('Daerah', 'inviro'),
        'search_items'      => __('Cari Daerah', 'inviro'),
        'all_items'         => __('Semua Daerah', 'inviro'),
        'parent_item'       => __('Parent Daerah', 'inviro'),
        'parent_item_colon' => __('Parent Daerah:', 'inviro'),
        'edit_item'         => __('Edit Daerah', 'inviro'),
        'update_item'       => __('Update Daerah', 'inviro'),
        'add_new_item'      => __('Tambah Daerah Baru', 'inviro'),
        'new_item_name'     => __('Nama Daerah Baru', 'inviro'),
        'menu_name'         => __('Daerah', 'inviro'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'daerah'),
        'show_in_rest'      => true,
    );

    register_taxonomy('region', array('proyek_pelanggan'), $args);
    
    // Auto-create default regions
    if (!term_exists('Sumatra', 'region')) {
        wp_insert_term('Sumatra', 'region', array('slug' => 'sumatra'));
    }
    if (!term_exists('Jawa', 'region')) {
        wp_insert_term('Jawa', 'region', array('slug' => 'jawa'));
    }
    if (!term_exists('Kalimantan', 'region')) {
        wp_insert_term('Kalimantan', 'region', array('slug' => 'kalimantan'));
    }
    if (!term_exists('Maluku', 'region')) {
        wp_insert_term('Maluku', 'region', array('slug' => 'maluku'));
    }
    if (!term_exists('Nusa Tenggara', 'region')) {
        wp_insert_term('Nusa Tenggara', 'region', array('slug' => 'nusa-tenggara'));
    }
    if (!term_exists('Papua', 'region')) {
        wp_insert_term('Papua', 'region', array('slug' => 'papua'));
    }
    if (!term_exists('Sulawesi', 'region')) {
        wp_insert_term('Sulawesi', 'region', array('slug' => 'sulawesi'));
    }
}
add_action('init', 'inviro_register_region_taxonomy');

