<?php
/**
 * Branches Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Meta Box for Branch Location
 */
function inviro_branch_meta_boxes() {
    add_meta_box(
        'inviro_branch_location',
        __('Lokasi Cabang', 'inviro'),
        'inviro_branch_location_callback',
        'cabang',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_branch_meta_boxes');

/**
 * Meta Box Callback
 */
function inviro_branch_location_callback($post) {
    wp_nonce_field('inviro_branch_location_nonce', 'inviro_branch_location_nonce');
    $location = get_post_meta($post->ID, '_branch_location', true);
    ?>
    <p>
        <label for="branch_location"><?php _e('Alamat/Lokasi Cabang:', 'inviro'); ?></label><br>
        <textarea name="branch_location" id="branch_location" rows="3" style="width: 100%;"><?php echo esc_textarea($location); ?></textarea>
    </p>
    <?php
}

/**
 * Save Branch Location Meta
 */
function inviro_save_branch_location($post_id) {
    if (!isset($_POST['inviro_branch_location_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['inviro_branch_location_nonce'], 'inviro_branch_location_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['branch_location'])) {
        update_post_meta($post_id, '_branch_location', sanitize_textarea_field($_POST['branch_location']));
    }
}
add_action('save_post_cabang', 'inviro_save_branch_location');

/**
 * Add custom meta boxes for Branches
 */
function inviro_add_branch_meta_boxes() {
    add_meta_box(
        'branch_address',
        __('Alamat Lengkap', 'inviro'),
        'inviro_branch_address_callback',
        'cabang',
        'normal',
        'high'
    );
    
    add_meta_box(
        'branch_phone',
        __('Nomor Telepon', 'inviro'),
        'inviro_branch_phone_callback',
        'cabang',
        'normal',
        'high'
    );
    
    add_meta_box(
        'branch_map',
        __('URL Google Maps', 'inviro'),
        'inviro_branch_map_callback',
        'cabang',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_branch_meta_boxes');

function inviro_branch_address_callback($post) {
    wp_nonce_field('inviro_branch_meta', 'inviro_branch_meta_nonce');
    $address = get_post_meta($post->ID, '_branch_address', true);
    echo '<textarea name="branch_address" style="width: 100%; padding: 8px; min-height: 100px;">' . esc_textarea($address) . '</textarea>';
}

function inviro_branch_phone_callback($post) {
    $phone = get_post_meta($post->ID, '_branch_phone', true);
    echo '<input type="text" name="branch_phone" value="' . esc_attr($phone) . '" placeholder="+62 123 456 7890" style="width: 100%; padding: 8px;" />';
}

function inviro_branch_map_callback($post) {
    $map = get_post_meta($post->ID, '_branch_map', true);
    echo '<input type="url" name="branch_map" value="' . esc_url($map) . '" placeholder="https://maps.google.com/..." style="width: 100%; padding: 8px;" />';
}

function inviro_save_branch_meta($post_id) {
    // Check post type
    if (get_post_type($post_id) !== 'cabang') {
        return;
    }
    
    if (!isset($_POST['inviro_branch_meta_nonce']) || !wp_verify_nonce($_POST['inviro_branch_meta_nonce'], 'inviro_branch_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['branch_address'])) {
        update_post_meta($post_id, '_branch_address', sanitize_textarea_field($_POST['branch_address']));
    }
    
    if (isset($_POST['branch_phone'])) {
        update_post_meta($post_id, '_branch_phone', sanitize_text_field($_POST['branch_phone']));
    }
    
    if (isset($_POST['branch_map'])) {
        update_post_meta($post_id, '_branch_map', esc_url_raw($_POST['branch_map']));
    }
}
add_action('save_post_cabang', 'inviro_save_branch_meta');


