<?php
/**
 * Corporate Project Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom meta boxes for Corporate Project
 */
function inviro_add_corporate_project_meta_boxes() {
    add_meta_box(
        'inviro_corporate_project_info',
        __('Informasi Corporate Project', 'inviro'),
        'inviro_corporate_project_info_callback',
        'corporate_project',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_corporate_project_meta_boxes');

/**
 * Corporate Project Info Meta Box Callback
 */
function inviro_corporate_project_info_callback($post) {
    wp_nonce_field('inviro_corporate_project_meta_nonce', 'inviro_corporate_project_meta_nonce');
    
    $company_name = get_post_meta($post->ID, '_corporate_project_company_name', true);
    
    ?>
    <p>
        <label for="corporate_project_company_name"><strong><?php _e('Nama Perusahaan:', 'inviro'); ?></strong></label><br>
        <input type="text" name="corporate_project_company_name" id="corporate_project_company_name" value="<?php echo esc_attr($company_name); ?>" placeholder="PT. Perusahaan A" style="width: 100%; padding: 8px; margin-top: 5px;" required />
    </p>
    
    <p class="description">
        <?php _e('Gambar logo perusahaan dapat diatur di bagian "Featured Image" di sebelah kanan.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Save Corporate Project Meta
 */
function inviro_save_corporate_project_meta($post_id) {
    // Check post type
    if (get_post_type($post_id) !== 'corporate_project') {
        return;
    }
    
    if (!isset($_POST['inviro_corporate_project_meta_nonce']) || !wp_verify_nonce($_POST['inviro_corporate_project_meta_nonce'], 'inviro_corporate_project_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['corporate_project_company_name'])) {
        update_post_meta($post_id, '_corporate_project_company_name', sanitize_text_field($_POST['corporate_project_company_name']));
    }
}
add_action('save_post_corporate_project', 'inviro_save_corporate_project_meta');


