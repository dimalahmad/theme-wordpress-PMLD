<?php
/**
 * AJAX Handlers
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX handler untuk save paket gallery
 */
function inviro_ajax_save_paket_gallery() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $gallery_ids = isset($_POST['gallery_ids']) ? $_POST['gallery_ids'] : '';
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    
    // Verify nonce
    if (!wp_verify_nonce($nonce, 'save_paket_gallery_' . $post_id)) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        return;
    }
    
    // Check post type
    if (get_post_type($post_id) !== 'paket_usaha') {
        wp_send_json_error(array('message' => 'Invalid post type'));
        return;
    }
    
    // Check capability
    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error(array('message' => 'No permission'));
        return;
    }
    
    // Process gallery IDs
    $gallery_array = array();
    if (!empty($gallery_ids) && trim($gallery_ids) !== '') {
        $gallery_array = array_map('absint', explode(',', trim($gallery_ids)));
        $gallery_array = array_filter($gallery_array, function($id) {
            return $id > 0;
        });
        $gallery_array = array_unique($gallery_array);
        $gallery_array = array_values($gallery_array);
    }
    
    // Save
    if (!empty($gallery_array)) {
        $gallery_value = implode(',', $gallery_array);
        update_post_meta($post_id, '_paket_gallery', $gallery_value);
        wp_send_json_success(array('message' => 'Gallery saved', 'ids' => $gallery_value));
    } else {
        delete_post_meta($post_id, '_paket_gallery');
        wp_send_json_success(array('message' => 'Gallery cleared'));
    }
}
add_action('wp_ajax_save_paket_gallery', 'inviro_ajax_save_paket_gallery');

/**
 * Handle review form submission (Spare Parts)
 */
function inviro_handle_review_submission() {
    if (!isset($_POST['review_nonce']) || !wp_verify_nonce($_POST['review_nonce'], 'submit_review')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        return;
    }
    
    $sparepart_id_raw = $_POST['sparepart_id'];
    $is_dummy = isset($_POST['is_dummy']) && $_POST['is_dummy'] == '1';
    $sparepart_id = $is_dummy ? $sparepart_id_raw : intval($sparepart_id_raw);
    $reviewer_name = sanitize_text_field($_POST['reviewer_name']);
    $reviewer_email = sanitize_email($_POST['reviewer_email']);
    $rating = intval($_POST['rating']);
    $review_content = sanitize_textarea_field($_POST['review_content']);
    
    if (empty($reviewer_name) || empty($reviewer_email) || empty($review_content) || $rating < 1 || $rating > 5) {
        wp_send_json_error(array('message' => 'Semua field harus diisi dengan benar'));
        return;
    }
    
    $review_id = wp_insert_post(array(
        'post_type'    => 'sparepart_review',
        'post_title'   => 'Ulasan dari ' . $reviewer_name,
        'post_content' => $review_content,
        'post_status'  => 'publish',
    ));
    
    if ($review_id) {
        update_post_meta($review_id, '_review_sparepart_id', $sparepart_id);
        update_post_meta($review_id, '_review_is_dummy', $is_dummy ? '1' : '0');
        update_post_meta($review_id, '_review_product_type', 'spareparts');
        update_post_meta($review_id, '_reviewer_name', $reviewer_name);
        update_post_meta($review_id, '_reviewer_email', $reviewer_email);
        update_post_meta($review_id, '_review_rating', $rating);
        update_post_meta($review_id, '_review_status', 'pending');
        
        wp_send_json_success(array('message' => 'Ulasan Anda telah dikirim dan menunggu persetujuan admin.'));
    } else {
        wp_send_json_error(array('message' => 'Gagal mengirim ulasan. Silakan coba lagi.'));
    }
}
add_action('wp_ajax_submit_sparepart_review', 'inviro_handle_review_submission');
add_action('wp_ajax_nopriv_submit_sparepart_review', 'inviro_handle_review_submission');

/**
 * Handle paket usaha review form submission
 */
function inviro_handle_paket_review_submission() {
    if (!isset($_POST['review_nonce']) || !wp_verify_nonce($_POST['review_nonce'], 'submit_review')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        return;
    }
    
    $paket_id_raw = $_POST['paket_id'];
    $is_dummy = isset($_POST['is_dummy']) && $_POST['is_dummy'] == '1';
    $paket_id = $is_dummy ? $paket_id_raw : intval($paket_id_raw);
    $reviewer_name = sanitize_text_field($_POST['reviewer_name']);
    $reviewer_email = sanitize_email($_POST['reviewer_email']);
    $rating = intval($_POST['rating']);
    $review_content = sanitize_textarea_field($_POST['review_content']);
    
    if (empty($reviewer_name) || empty($reviewer_email) || empty($review_content) || $rating < 1 || $rating > 5) {
        wp_send_json_error(array('message' => 'Semua field harus diisi dengan benar'));
        return;
    }
    
    $review_id = wp_insert_post(array(
        'post_type'    => 'paket_usaha_review',
        'post_title'   => 'Ulasan dari ' . $reviewer_name,
        'post_content' => $review_content,
        'post_status'  => 'publish',
    ));
    
    if ($review_id) {
        update_post_meta($review_id, '_review_sparepart_id', $paket_id);
        update_post_meta($review_id, '_review_is_dummy', $is_dummy ? '1' : '0');
        update_post_meta($review_id, '_review_product_type', 'paket_usaha');
        update_post_meta($review_id, '_reviewer_name', $reviewer_name);
        update_post_meta($review_id, '_reviewer_email', $reviewer_email);
        update_post_meta($review_id, '_review_rating', $rating);
        update_post_meta($review_id, '_review_status', 'pending');
        
        wp_send_json_success(array('message' => 'Ulasan Anda telah dikirim dan menunggu persetujuan admin.'));
    } else {
        wp_send_json_error(array('message' => 'Gagal mengirim ulasan. Silakan coba lagi.'));
    }
}
add_action('wp_ajax_submit_paket_review', 'inviro_handle_paket_review_submission');
add_action('wp_ajax_nopriv_submit_paket_review', 'inviro_handle_paket_review_submission');

/**
 * AJAX handler untuk tracking download
 */
function inviro_track_download() {
    if (!isset($_POST['post_id'])) {
        wp_send_json_error('Invalid request');
        return;
    }
    
    $post_id = intval($_POST['post_id']);
    
    // Verify it's an unduhan post type
    if (get_post_type($post_id) !== 'unduhan') {
        wp_send_json_error('Invalid post type');
        return;
    }
    
    // Get current count
    $current_count = get_post_meta($post_id, '_unduhan_download_count', true);
    $current_count = $current_count ? intval($current_count) : 0;
    
    // Increment
    $new_count = $current_count + 1;
    update_post_meta($post_id, '_unduhan_download_count', $new_count);
    
    wp_send_json_success(array(
        'new_count' => $new_count,
        'message' => 'Download tracked successfully'
    ));
}
add_action('wp_ajax_track_download', 'inviro_track_download');
add_action('wp_ajax_nopriv_track_download', 'inviro_track_download');

/**
 * Contact Form Handler
 */
function inviro_handle_contact_form() {
    check_ajax_referer('inviro_nonce', 'nonce');
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);
    
    $to = get_option('admin_email');
    $email_subject = 'Pesan Baru dari ' . $name . ' - ' . $subject;
    $email_message = "Nama: $name\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Telepon: $phone\n";
    $email_message .= "Subjek: $subject\n\n";
    $email_message .= "Pesan:\n$message";
    
    $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $name . ' <' . $email . '>');
    
    $sent = wp_mail($to, $email_subject, $email_message, $headers);
    
    if ($sent) {
        wp_send_json_success(array('message' => 'Pesan berhasil dikirim!'));
    } else {
        wp_send_json_error(array('message' => 'Gagal mengirim pesan. Silakan coba lagi.'));
    }
}
add_action('wp_ajax_inviro_contact_form', 'inviro_handle_contact_form');
add_action('wp_ajax_nopriv_inviro_contact_form', 'inviro_handle_contact_form');


