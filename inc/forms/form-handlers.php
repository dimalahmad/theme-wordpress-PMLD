<?php
/**
 * Form Handlers
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Proyek Pelanggan Form Submission
 */
function inviro_handle_proyek_submission() {
    // Check if form was submitted
    if (!isset($_POST['submit_proyek_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['submit_proyek_nonce'], 'submit_proyek_action')) {
        wp_redirect(add_query_arg('error', 'invalid_nonce', wp_get_referer()));
        exit;
    }
    
    // Validate required fields
    if (empty($_POST['proyek_title']) || empty($_POST['proyek_description']) || 
        empty($_POST['proyek_excerpt']) || empty($_POST['proyek_client']) || 
        empty($_POST['proyek_date']) || empty($_POST['proyek_region'])) {
        wp_redirect(add_query_arg('error', 'missing_fields', wp_get_referer()));
        exit;
    }
    
    // Check if image was uploaded
    if (empty($_FILES['proyek_image']['name'])) {
        wp_redirect(add_query_arg('error', 'missing_image', wp_get_referer()));
        exit;
    }
    
    // Sanitize inputs
    $title = sanitize_text_field($_POST['proyek_title']);
    $description = wp_kses_post($_POST['proyek_description']);
    $excerpt = sanitize_textarea_field($_POST['proyek_excerpt']);
    $client_name = sanitize_text_field($_POST['proyek_client']);
    $proyek_date = sanitize_text_field($_POST['proyek_date']);
    $region_id = intval($_POST['proyek_region']);
    
    // Create the post
    $post_data = array(
        'post_title'    => $title,
        'post_content'  => $description,
        'post_excerpt'  => $excerpt,
        'post_status'   => 'publish',
        'post_type'     => 'proyek_pelanggan',
        'post_author'   => get_current_user_id()
    );
    
    $post_id = wp_insert_post($post_data);
    
    if (is_wp_error($post_id)) {
        wp_redirect(add_query_arg('error', 'post_creation_failed', wp_get_referer()));
        exit;
    }
    
    // Set taxonomy
    wp_set_post_terms($post_id, array($region_id), 'region');
    
    // Save meta fields
    update_post_meta($post_id, '_proyek_client_name', $client_name);
    update_post_meta($post_id, '_proyek_date', $proyek_date);
    
    // Handle image upload
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    $attachment_id = media_handle_upload('proyek_image', $post_id);
    
    if (is_wp_error($attachment_id)) {
        // Delete the post if image upload failed
        wp_delete_post($post_id, true);
        wp_redirect(add_query_arg('error', 'image_upload_failed', wp_get_referer()));
        exit;
    }
    
    // Set as featured image
    set_post_thumbnail($post_id, $attachment_id);
    
    // Redirect to success page
    wp_redirect(add_query_arg('success', '1', wp_get_referer()));
    exit;
}
add_action('template_redirect', 'inviro_handle_proyek_submission');


