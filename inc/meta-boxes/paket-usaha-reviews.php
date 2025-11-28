<?php
/**
 * Paket Usaha Reviews Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for paket usaha reviews
 */
function inviro_add_paket_review_meta_boxes() {
    add_meta_box(
        'paket_review_info',
        'Informasi Ulasan',
        'inviro_paket_review_info_callback',
        'paket_usaha_review',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_review_status',
        'Status Ulasan',
        'inviro_paket_review_status_callback',
        'paket_usaha_review',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_paket_review_meta_boxes');

function inviro_paket_review_info_callback($post) {
    $paket_id = get_post_meta($post->ID, '_review_sparepart_id', true);
    $reviewer_name = get_post_meta($post->ID, '_reviewer_name', true);
    $reviewer_email = get_post_meta($post->ID, '_reviewer_email', true);
    $rating = get_post_meta($post->ID, '_review_rating', true);
    ?>
    <div style="padding: 10px 0;">
        <p><strong>Nama:</strong><br><?php echo esc_html($reviewer_name); ?></p>
        <p><strong>Email:</strong><br><?php echo esc_html($reviewer_email); ?></p>
        <p><strong>Rating:</strong><br>
            <?php 
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $rating ? '★' : '☆';
            }
            ?> (<?php echo esc_html($rating); ?>/5)
        </p>
        <p><strong>Paket:</strong><br>
            <?php 
            if ($paket_id) : 
                // Check if it's dummy data
                $is_dummy = get_post_meta($post->ID, '_review_is_dummy', true);
                if ($is_dummy == '1') {
                    echo esc_html($paket_id);
                } else {
                    $paket = get_post($paket_id);
                    if ($paket) : ?>
                        <a href="<?php echo get_edit_post_link($paket_id); ?>" target="_blank">
                            <?php echo esc_html($paket->post_title); ?>
                        </a>
                    <?php else : ?>
                        <?php echo esc_html($paket_id); ?>
                    <?php endif;
                }
            endif; ?>
        </p>
    </div>
    <?php
}

function inviro_paket_review_status_callback($post) {
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

function inviro_save_paket_review_meta($post_id) {
    if (get_post_type($post_id) !== 'paket_usaha_review') {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['review_status'])) {
        update_post_meta($post_id, '_review_status', sanitize_text_field($_POST['review_status']));
    }
}
add_action('save_post_paket_usaha_review', 'inviro_save_paket_review_meta');


