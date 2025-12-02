<?php
/**
 * Spare Part Reviews Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Spare Part Reviews
 */
function inviro_add_review_meta_boxes() {
    add_meta_box(
        'review_info',
        'Informasi Ulasan',
        'inviro_review_info_callback',
        'sparepart_review',
        'side',
        'default'
    );
    
    add_meta_box(
        'review_status',
        'Status Ulasan',
        'inviro_review_status_callback',
        'sparepart_review',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_review_meta_boxes');

function inviro_review_info_callback($post) {
    wp_nonce_field('inviro_review_meta', 'inviro_review_meta_nonce');
    $sparepart_id = get_post_meta($post->ID, '_review_sparepart_id', true);
    $reviewer_name = get_post_meta($post->ID, '_reviewer_name', true);
    $reviewer_email = get_post_meta($post->ID, '_reviewer_email', true);
    $rating = get_post_meta($post->ID, '_review_rating', true);
    $review_date = get_post_meta($post->ID, '_review_date', true);
    if (!$review_date) {
        $review_date = get_the_date('Y-m-d', $post->ID);
    }
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 8px;"><strong>Nama:</strong></p>
        <input type="text" name="reviewer_name" 
               value="<?php echo esc_attr($reviewer_name); ?>" 
               placeholder="Nama Reviewer"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
        
        <p style="margin-bottom: 8px;"><strong>Email:</strong></p>
        <input type="email" name="reviewer_email" 
               value="<?php echo esc_attr($reviewer_email); ?>" 
               placeholder="email@example.com"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
        
        <p style="margin-bottom: 8px;"><strong>Tanggal:</strong></p>
        <input type="date" name="review_date" 
               value="<?php echo esc_attr($review_date); ?>" 
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
        
        <p style="margin-bottom: 8px;"><strong>Bintang (Rating 1-5):</strong></p>
        <select name="review_rating" 
                style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
            <option value="1" <?php selected($rating, '1'); ?>>1 Bintang</option>
            <option value="2" <?php selected($rating, '2'); ?>>2 Bintang</option>
            <option value="3" <?php selected($rating, '3'); ?>>3 Bintang</option>
            <option value="4" <?php selected($rating, '4'); ?>>4 Bintang</option>
            <option value="5" <?php selected($rating, '5'); ?>>5 Bintang</option>
        </select>
        
        <p style="margin-bottom: 8px;"><strong>Pesan (Ulasan):</strong></p>
        <p class="description" style="margin-top: 8px; font-size: 13px; margin-bottom: 15px;">
            Isi ulasan ada di editor konten di bawah (Post Content)
        </p>
        
        <p style="margin-bottom: 8px;"><strong>Produk (Spare Part ID):</strong></p>
        <input type="number" name="review_sparepart_id" 
               value="<?php echo esc_attr($sparepart_id); ?>" 
               placeholder="ID Spare Part"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            <?php 
            if ($sparepart_id) {
                $sparepart = get_post($sparepart_id);
                if ($sparepart) {
                    echo 'Produk: <a href="' . get_edit_post_link($sparepart_id) . '" target="_blank">' . esc_html($sparepart->post_title) . '</a>';
                } else {
                    echo 'ID tidak ditemukan';
                }
            } else {
                echo 'Masukkan ID Spare Part yang akan diulas';
            }
            ?>
        </p>
    </div>
    <?php
}

function inviro_review_status_callback($post) {
    $status = get_post_meta($post->ID, '_review_status', true);
    if (!$status) $status = 'pending';
    ?>
    <div style="padding: 10px 0;">
        <select name="review_status" style="width: 100%; padding: 10px; font-size: 14px;">
            <option value="approved" <?php selected($status, 'approved'); ?>>Disetujui (Tampilkan)</option>
            <option value="pending" <?php selected($status, 'pending'); ?>>Menunggu Persetujuan</option>
            <option value="rejected" <?php selected($status, 'rejected'); ?>>Ditolak (Tidak Tampilkan)</option>
        </select>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Pilih status ulasan. Hanya ulasan yang disetujui yang akan ditampilkan di halaman produk.
        </p>
    </div>
    <?php
}

function inviro_save_review_meta($post_id) {
    if (get_post_type($post_id) !== 'sparepart_review') {
        return;
    }
    
    if (!isset($_POST['inviro_review_meta_nonce']) || !wp_verify_nonce($_POST['inviro_review_meta_nonce'], 'inviro_review_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['reviewer_name'])) {
        update_post_meta($post_id, '_reviewer_name', sanitize_text_field($_POST['reviewer_name']));
    }
    
    if (isset($_POST['reviewer_email'])) {
        update_post_meta($post_id, '_reviewer_email', sanitize_email($_POST['reviewer_email']));
    }
    
    if (isset($_POST['review_date'])) {
        update_post_meta($post_id, '_review_date', sanitize_text_field($_POST['review_date']));
    }
    
    if (isset($_POST['review_rating'])) {
        $rating = intval($_POST['review_rating']);
        $rating = max(1, min(5, $rating)); // Ensure between 1-5
        update_post_meta($post_id, '_review_rating', $rating);
    }
    
    if (isset($_POST['review_sparepart_id'])) {
        update_post_meta($post_id, '_review_sparepart_id', absint($_POST['review_sparepart_id']));
        update_post_meta($post_id, '_review_is_dummy', '0');
        update_post_meta($post_id, '_review_product_type', 'spareparts');
    }
    
    if (isset($_POST['review_status'])) {
        update_post_meta($post_id, '_review_status', sanitize_text_field($_POST['review_status']));
    }
}
add_action('save_post_sparepart_review', 'inviro_save_review_meta');


