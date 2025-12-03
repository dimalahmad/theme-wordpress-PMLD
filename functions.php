<?php
/**
 * INVIRO WP Theme Functions
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load Dummy Data Helper (Development Only)
 * Hapus setelah development selesai!
 */
if (file_exists(get_template_directory() . '/dummy-data/helper.php')) {
    require_once get_template_directory() . '/dummy-data/helper.php';
}

/**
 * Load all function files
 * Functions are now organized in separate files for better maintainability
 */
if (file_exists(get_template_directory() . '/inc/loader.php')) {
    require_once get_template_directory() . '/inc/loader.php';
}

