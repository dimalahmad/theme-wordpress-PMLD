<?php
/**
 * Paket Usaha Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Paket Usaha
 */
function inviro_add_paket_usaha_meta_boxes() {
    add_meta_box(
        'paket_usaha_price',
        '💰 Harga',
        'inviro_paket_usaha_price_callback',
        'paket_usaha',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_paket_usaha_meta_boxes');

function inviro_paket_usaha_price_callback($post) {
    wp_nonce_field('inviro_paket_usaha_meta', 'inviro_paket_usaha_meta_nonce');
    $price_raw = get_post_meta($post->ID, '_paket_price', true);
    $original_price_raw = get_post_meta($post->ID, '_paket_original_price', true);
    $promo = get_post_meta($post->ID, '_paket_promo', true);
    
    // Bersihkan harga dari format "Rp", titik, koma, dan spasi untuk ditampilkan di field
    $clean_price_for_field = function($value) {
        if (empty($value)) return '';
        if (is_numeric($value)) {
            return $value;
        }
        // Hapus "Rp", titik, koma, spasi, dan karakter non-numeric
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        return !empty($cleaned) ? $cleaned : '';
    };
    
    $price = $clean_price_for_field($price_raw);
    $original_price = $clean_price_for_field($original_price_raw);
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 10px;"><strong>Harga Asli <span style="color: #dc3545;">*</span>:</strong></p>
        <input type="number" id="paket_original_price" name="paket_original_price" 
               value="<?php echo esc_attr($original_price); ?>" 
               placeholder="20000000" 
               min="0"
               required
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
        <p class="description" style="margin-top: 8px; font-size: 13px; margin-bottom: 15px;">
            Harga asli dalam Rupiah (tanpa titik/koma) - <strong>Wajib diisi</strong>
        </p>
        
        <p style="margin-bottom: 10px;"><strong>Harga Promo (Opsional):</strong></p>
        <input type="number" id="paket_price" name="paket_price" 
               value="<?php echo esc_attr($price); ?>" 
               placeholder="15000000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
        <p class="description" style="margin-top: 8px; font-size: 13px; margin-bottom: 15px;">
            Harga promo (akan ditampilkan sebagai harga saat ini). Kosongkan jika tidak ada promo.
        </p>
        
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <input type="checkbox" id="paket_promo" name="paket_promo" value="1" 
                   <?php checked($promo, '1'); ?>
                   style="width: 20px; height: 20px; cursor: pointer;">
            <span style="font-size: 14px; font-weight: 500;">Tandai sebagai Promo</span>
        </label>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Centang jika paket usaha ini sedang dalam promo
        </p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#post').on('submit', function(e) {
            var originalPrice = $('#paket_original_price').val();
            if (!originalPrice || originalPrice.trim() === '' || parseInt(originalPrice) <= 0) {
                e.preventDefault();
                alert('Harga Asli wajib diisi!');
                $('#paket_original_price').focus();
                return false;
            }
        });
    });
    </script>
    <?php
}

function inviro_save_paket_usaha_meta($post_id) {
    if (get_post_type($post_id) !== 'paket_usaha') {
        return;
    }
    
    if (!isset($_POST['inviro_paket_usaha_meta_nonce']) || !wp_verify_nonce($_POST['inviro_paket_usaha_meta_nonce'], 'inviro_paket_usaha_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Harga asli wajib diisi
    if (isset($_POST['paket_original_price']) && !empty($_POST['paket_original_price'])) {
        update_post_meta($post_id, '_paket_original_price', absint($_POST['paket_original_price']));
    } else {
        // Jika harga asli kosong, set error
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Harga Asli wajib diisi!</p></div>';
        });
    }
    
    // Harga promo opsional - jika tidak diisi, simpan 0 (bukan harga asli)
    if (isset($_POST['paket_price']) && !empty($_POST['paket_price']) && $_POST['paket_price'] > 0) {
        update_post_meta($post_id, '_paket_price', absint($_POST['paket_price']));
    } else {
        // Jika harga promo tidak diisi, simpan 0 agar bisa dibedakan dengan harga asli
        update_post_meta($post_id, '_paket_price', 0);
    }
    
    if (isset($_POST['paket_promo'])) {
        update_post_meta($post_id, '_paket_promo', '1');
    } else {
        update_post_meta($post_id, '_paket_promo', '0');
    }
}
add_action('save_post_paket_usaha', 'inviro_save_paket_usaha_meta');

