<?php
/**
 * Unduhan (Downloads) Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Unduhan
 */
function inviro_add_unduhan_meta_boxes() {
    add_meta_box(
        'unduhan_file',
        '📁 File Download',
        'inviro_unduhan_file_callback',
        'unduhan',
        'normal',
        'high'
    );
    
    add_meta_box(
        'unduhan_info',
        'ℹ️ Info File',
        'inviro_unduhan_info_callback',
        'unduhan',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_unduhan_meta_boxes');

function inviro_unduhan_file_callback($post) {
    wp_nonce_field('inviro_unduhan_meta', 'inviro_unduhan_meta_nonce');
    $file_url = get_post_meta($post->ID, '_unduhan_file_url', true);
    ?>
    <div style="padding: 15px 0;">
        <p style="margin-bottom: 10px; color: #666;">
            📤 Upload file PDF, DOC, ZIP, atau file lainnya yang akan didownload user.
        </p>
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="unduhan_file_url" name="unduhan_file_url" 
                   value="<?php echo esc_url($file_url); ?>" 
                   placeholder="https://..." 
                   readonly
                   style="flex: 1; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
            <button type="button" class="button button-primary" id="upload_file_button">
                📂 Pilih File
            </button>
            <?php if ($file_url) : ?>
            <button type="button" class="button" id="remove_file_button">
                ✕ Hapus
            </button>
            <?php endif; ?>
        </div>
        <?php if ($file_url) : ?>
        <p style="margin-top: 10px; color: #0073aa;">
            ✅ File terpilih: <a href="<?php echo esc_url($file_url); ?>" target="_blank">Lihat File</a>
        </p>
        <?php endif; ?>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var file_frame;
        
        $('#upload_file_button').on('click', function(e) {
            e.preventDefault();
            
            if (file_frame) {
                file_frame.open();
                return;
            }
            
            file_frame = wp.media({
                title: 'Pilih File untuk Diunduh',
                button: {
                    text: 'Gunakan File Ini'
                },
                multiple: false
            });
            
            file_frame.on('select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                $('#unduhan_file_url').val(attachment.url);
                location.reload();
            });
            
            file_frame.open();
        });
        
        $('#remove_file_button').on('click', function(e) {
            e.preventDefault();
            $('#unduhan_file_url').val('');
            $(this).remove();
            $('.button-primary').after('<p style="color: #d32f2f;">File dihapus, klik Update untuk menyimpan</p>');
        });
    });
    </script>
    <?php
}

function inviro_unduhan_info_callback($post) {
    $file_size = get_post_meta($post->ID, '_unduhan_file_size', true);
    $file_type = get_post_meta($post->ID, '_unduhan_file_type', true);
    $download_count = get_post_meta($post->ID, '_unduhan_download_count', true);
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 10px;"><strong>Ukuran File:</strong></p>
        <input type="text" id="unduhan_file_size" name="unduhan_file_size" 
               value="<?php echo esc_attr($file_size); ?>" 
               placeholder="5 MB" 
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
        
        <p style="margin-bottom: 10px;"><strong>Tipe File:</strong></p>
        <input type="text" id="unduhan_file_type" name="unduhan_file_type" 
               value="<?php echo esc_attr($file_type); ?>" 
               placeholder="PDF" 
               style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
        
        <p style="margin-bottom: 10px;"><strong>Jumlah Download:</strong></p>
        <input type="number" id="unduhan_download_count" name="unduhan_download_count" 
               value="<?php echo esc_attr($download_count ? $download_count : 0); ?>" 
               readonly
               style="width: 100%; padding: 8px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 4px;">
        <p class="description" style="margin-top: 5px;">Auto-increment saat di-download</p>
    </div>
    <?php
}

function inviro_save_unduhan_meta($post_id) {
    if (get_post_type($post_id) !== 'unduhan') {
        return;
    }
    
    if (!isset($_POST['inviro_unduhan_meta_nonce']) || !wp_verify_nonce($_POST['inviro_unduhan_meta_nonce'], 'inviro_unduhan_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['unduhan_file_url'])) {
        update_post_meta($post_id, '_unduhan_file_url', esc_url_raw($_POST['unduhan_file_url']));
    }
    
    if (isset($_POST['unduhan_file_size'])) {
        update_post_meta($post_id, '_unduhan_file_size', sanitize_text_field($_POST['unduhan_file_size']));
    }
    
    if (isset($_POST['unduhan_file_type'])) {
        update_post_meta($post_id, '_unduhan_file_type', sanitize_text_field($_POST['unduhan_file_type']));
    }
}
add_action('save_post_unduhan', 'inviro_save_unduhan_meta');


