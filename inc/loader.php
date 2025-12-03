<?php
/**
 * Loader File - Load all function files
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load helper functions
require_once get_template_directory() . '/inc/helpers/helpers.php';

// Load theme setup
require_once get_template_directory() . '/inc/theme-setup.php';

// Load enqueue functions
require_once get_template_directory() . '/inc/enqueue/enqueue.php';

// Load custom post types
require_once get_template_directory() . '/inc/post-types/products.php';
require_once get_template_directory() . '/inc/post-types/testimonials.php';
require_once get_template_directory() . '/inc/post-types/branches.php';
require_once get_template_directory() . '/inc/post-types/layanan.php';
require_once get_template_directory() . '/inc/post-types/proyek-pelanggan.php';
require_once get_template_directory() . '/inc/post-types/spareparts.php';
require_once get_template_directory() . '/inc/post-types/sparepart-reviews.php';
require_once get_template_directory() . '/inc/post-types/artikel.php';
require_once get_template_directory() . '/inc/post-types/unduhan.php';
require_once get_template_directory() . '/inc/post-types/contact-submissions.php';

// Load meta boxes
require_once get_template_directory() . '/inc/meta-boxes/products.php';
require_once get_template_directory() . '/inc/meta-boxes/testimonials.php';
require_once get_template_directory() . '/inc/meta-boxes/branches.php';
require_once get_template_directory() . '/inc/meta-boxes/layanan.php';
require_once get_template_directory() . '/inc/meta-boxes/proyek-pelanggan.php';
require_once get_template_directory() . '/inc/meta-boxes/spareparts.php';
require_once get_template_directory() . '/inc/meta-boxes/sparepart-reviews.php';
require_once get_template_directory() . '/inc/meta-boxes/unduhan.php';
require_once get_template_directory() . '/inc/meta-boxes/contact-submissions.php';

// Load AJAX handlers
require_once get_template_directory() . '/inc/ajax/ajax-handlers.php';

// Load form handlers
require_once get_template_directory() . '/inc/forms/form-handlers.php';

// Load customizer
require_once get_template_directory() . '/inc/customizer/customizer.php';

// Load hooks and filters
require_once get_template_directory() . '/inc/hooks/hooks.php';


