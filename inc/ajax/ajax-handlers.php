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
        'post_status'  => 'pending',
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
        
        wp_send_json_success(array('message' => 'Ulasan Anda telah dikirim '));
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
/**
 * Handle contact form submission
 * Simple approach: Save to database like reviews, no email required
 */
function inviro_handle_contact_form() {
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'submit_contact')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        return;
    }
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
    $message = sanitize_textarea_field($_POST['message']);
    
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Semua field wajib harus diisi dengan benar'));
        return;
    }
    
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Format email tidak valid'));
        return;
    }
    
    // Save to database as post (like reviews)
    $post_title = !empty($subject) 
        ? sprintf('Pesan dari %s: %s', $name, $subject)
        : sprintf('Pesan dari %s', $name);
    
    // Prepare content with all information
    $post_content = $message;
    if (!empty($phone)) {
        $post_content = "Telepon: $phone\n\n" . $post_content;
    }
    
    $submission_id = wp_insert_post(array(
        'post_type'    => 'contact_submission',
        'post_title'   => $post_title,
        'post_content' => $post_content,
        'post_status'  => 'publish', // Auto-publish, admin can see in dashboard
    ));
    
    if ($submission_id) {
        // Save meta data
        update_post_meta($submission_id, '_contact_name', $name);
        update_post_meta($submission_id, '_contact_email', $email);
        if (!empty($phone)) {
            update_post_meta($submission_id, '_contact_phone', $phone);
        }
        if (!empty($subject)) {
            update_post_meta($submission_id, '_contact_subject', $subject);
        }
        
        // Try to send email (optional, won't fail if email fails)
        $admin_email = get_option('admin_email');
        if ($admin_email) {
            $email_subject = !empty($subject) 
                ? sprintf('[%s] Pesan Baru dari %s: %s', get_bloginfo('name'), $name, $subject)
                : sprintf('[%s] Pesan Baru dari %s', get_bloginfo('name'), $name);
            
            $email_message = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
            $email_message .= '<h2 style="color: #2F80ED;">Pesan Baru dari Form Kontak</h2>';
            $email_message .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
            $email_message .= '<tr><td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; width: 150px;">Nama:</td><td style="padding: 8px; border-bottom: 1px solid #eee;">' . esc_html($name) . '</td></tr>';
            $email_message .= '<tr><td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Email:</td><td style="padding: 8px; border-bottom: 1px solid #eee;"><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></td></tr>';
            
            if (!empty($phone)) {
                $email_message .= '<tr><td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Telepon:</td><td style="padding: 8px; border-bottom: 1px solid #eee;">' . esc_html($phone) . '</td></tr>';
            }
            
            if (!empty($subject)) {
                $email_message .= '<tr><td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Subjek:</td><td style="padding: 8px; border-bottom: 1px solid #eee;">' . esc_html($subject) . '</td></tr>';
            }
            
            $email_message .= '</table>';
            $email_message .= '<div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #2F80ED;">';
            $email_message .= '<h3 style="margin-top: 0; color: #2F80ED;">Pesan:</h3>';
            $email_message .= '<p style="white-space: pre-wrap;">' . nl2br(esc_html($message)) . '</p>';
            $email_message .= '</div>';
            $email_message .= '<p style="margin-top: 30px; font-size: 12px; color: #999;">Pesan ini juga tersimpan di WordPress Admin → Pesan Kontak</p>';
            $email_message .= '</body></html>';
            
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
                'Reply-To: ' . $name . ' <' . $email . '>'
            );
            
            // Try to send email (don't fail if it doesn't work)
            wp_mail($admin_email, $email_subject, $email_message, $headers);
        }
        
        wp_send_json_success(array('message' => 'Pesan berhasil dikirim! Kami akan segera menghubungi Anda.'));
    } else {
        wp_send_json_error(array('message' => 'Gagal mengirim pesan. Silakan coba lagi.'));
    }
}
add_action('wp_ajax_submit_contact_form', 'inviro_handle_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'inviro_handle_contact_form');


