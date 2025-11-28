<?php
/**
 * Layanan Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Meta Box for Layanan External URL
 */
function inviro_layanan_meta_boxes() {
    add_meta_box(
        'inviro_layanan_url',
        __('Link Eksternal', 'inviro'),
        'inviro_layanan_url_callback',
        'layanan',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_layanan_meta_boxes');

/**
 * Meta Box Callback for Layanan URL
 */
function inviro_layanan_url_callback($post) {
    wp_nonce_field('inviro_layanan_url_nonce', 'inviro_layanan_url_nonce');
    $external_url = get_post_meta($post->ID, '_layanan_external_url', true);
    ?>
    <p>
        <label for="layanan_external_url"><?php _e('URL Eksternal (Link ke domain lain):', 'inviro'); ?></label><br>
        <input type="url" name="layanan_external_url" id="layanan_external_url" value="<?php echo esc_attr($external_url); ?>" style="width: 100%; padding: 8px;" placeholder="https://example.com/layanan">
        <br><small><?php _e('Masukkan URL lengkap termasuk http:// atau https://', 'inviro'); ?></small>
    </p>
    <?php
}

/**
 * Save Layanan URL Meta
 */
function inviro_save_layanan_url($post_id) {
    if (!isset($_POST['inviro_layanan_url_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['inviro_layanan_url_nonce'], 'inviro_layanan_url_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['layanan_external_url'])) {
        update_post_meta($post_id, '_layanan_external_url', esc_url_raw($_POST['layanan_external_url']));
    }
}
add_action('save_post_layanan', 'inviro_save_layanan_url');


