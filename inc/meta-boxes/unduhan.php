<?php
/**
 * Unduhan (Downloads) Meta Boxes
 * Clean version without instructions and emojis
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
        'File Download',
        'inviro_unduhan_file_callback',
        'unduhan',
        'normal',
        'high'
    );
    
    add_meta_box(
        'unduhan_stats',
        'Statistik',
        'inviro_unduhan_stats_callback',
        'unduhan',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_unduhan_meta_boxes');

function inviro_unduhan_file_callback($post) {
    wp_nonce_field('inviro_unduhan_meta', 'inviro_unduhan_meta_nonce');
    $file_url = get_post_meta($post->ID, '_unduhan_file_url', true);
    $file_id = get_post_meta($post->ID, '_unduhan_file_id', true);
    ?>
    <div style="padding: 15px 0;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="unduhan_file_url" name="unduhan_file_url" 
                   value="<?php echo esc_url($file_url); ?>" 
                   placeholder="URL file akan muncul di sini" 
                   readonly
                   style="flex: 1; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
            <input type="hidden" id="unduhan_file_id" name="unduhan_file_id" value="<?php echo esc_attr($file_id); ?>">
            <button type="button" class="button button-primary" id="upload_file_button">
                Pilih File
            </button>
            <?php if ($file_url) : ?>
            <button type="button" class="button" id="remove_file_button">
                Hapus
            </button>
            <?php endif; ?>
        </div>
        <?php if ($file_url) : ?>
        <p style="margin-top: 10px; color: #0073aa;">
            File terpilih: <a href="<?php echo esc_url($file_url); ?>" target="_blank">Lihat File</a>
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
                $('#unduhan_file_id').val(attachment.id);
            });
            
            file_frame.open();
        });
        
        $('#remove_file_button').on('click', function(e) {
            e.preventDefault();
            if (confirm('Hapus file ini?')) {
            $('#unduhan_file_url').val('');
                $('#unduhan_file_id').val('');
            $(this).remove();
            }
        });
    });
    </script>
    <?php
}

function inviro_unduhan_stats_callback($post) {
    $download_count = get_post_meta($post->ID, '_unduhan_download_count', true);
    if (empty($download_count)) {
        $download_count = 0;
    }
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 10px;"><strong>Jumlah Download:</strong></p>
        <input type="number" id="unduhan_download_count" name="unduhan_download_count" 
               value="<?php echo esc_attr($download_count); ?>" 
               readonly
               style="width: 100%; padding: 8px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 4px;">
        <p class="description" style="margin-top: 5px;">Auto-increment saat file diunduh</p>
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
    
    if (isset($_POST['unduhan_file_id'])) {
        update_post_meta($post_id, '_unduhan_file_id', absint($_POST['unduhan_file_id']));
    }
    
    // Don't update download count on save - it's auto-incremented on download
}
add_action('save_post_unduhan', 'inviro_save_unduhan_meta');

/**
 * Handle download and increment count
 */
function inviro_handle_download() {
    if (!isset($_GET['download_unduhan']) || !isset($_GET['post_id'])) {
        return;
    }
    
    $post_id = absint($_GET['post_id']);
    $nonce = isset($_GET['nonce']) ? sanitize_text_field($_GET['nonce']) : '';
    
    // Verify nonce
    if (!wp_verify_nonce($nonce, 'download_unduhan_' . $post_id)) {
        wp_die('Invalid request');
    }
    
    // Check if post exists and is unduhan type
    if (get_post_type($post_id) !== 'unduhan') {
        wp_die('Invalid post type');
    }
    
    // Get file URL and ID
    $file_url = get_post_meta($post_id, '_unduhan_file_url', true);
    $file_id = get_post_meta($post_id, '_unduhan_file_id', true);
    
    if (empty($file_url)) {
        wp_die('File tidak tersedia');
    }
    
    // Increment download count
    $download_count = get_post_meta($post_id, '_unduhan_download_count', true);
    $download_count = empty($download_count) ? 0 : absint($download_count);
    $download_count++;
    update_post_meta($post_id, '_unduhan_download_count', $download_count);
    
    // Get post title
    $post_title = get_post($post_id)->post_title;
    
    // Get file path if we have attachment ID
    $file_path = '';
    if ($file_id) {
        $file_path = get_attached_file($file_id);
    }
    
    // Clean output buffer
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // If we have file path, serve it directly with proper headers
    if ($file_path && file_exists($file_path)) {
        // Get file name
        $file_name = basename($file_path);
        $file_name = sanitize_file_name($post_title) . '_' . $file_name;
        
        // Get file mime type
        $file_mime = wp_check_filetype($file_path);
        $mime_type = $file_mime['type'] ? $file_mime['type'] : 'application/octet-stream';
        
        // Set headers for download
        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . sanitize_file_name($file_name) . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        
        // Read and output file
        readfile($file_path);
        exit;
    } else {
        // Fallback: For external URLs, proxy download through PHP
        // Get file extension from URL for filename
        $file_ext = pathinfo(parse_url($file_url, PHP_URL_PATH), PATHINFO_EXTENSION);
        $file_name = sanitize_file_name($post_title) . ($file_ext ? '.' . $file_ext : '');
        
        // Check if file is from same domain (can use direct download)
        $site_url = site_url();
        $file_domain = parse_url($file_url, PHP_URL_HOST);
        $site_domain = parse_url($site_url, PHP_URL_HOST);
        
        if ($file_domain === $site_domain) {
            // Same domain - try to get file path
            $upload_dir = wp_upload_dir();
            $file_path_in_url = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $file_url);
            
            if (file_exists($file_path_in_url)) {
                $file_mime = wp_check_filetype($file_path_in_url);
                $mime_type = $file_mime['type'] ? $file_mime['type'] : 'application/octet-stream';
                
                header('Content-Type: ' . $mime_type);
                header('Content-Disposition: attachment; filename="' . sanitize_file_name($file_name) . '"');
                header('Content-Length: ' . filesize($file_path_in_url));
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Expires: 0');
                
                readfile($file_path_in_url);
                exit;
            }
        }
        
        // External URL - proxy download through PHP
        // Get file extension for mime type
        $file_mime = wp_check_filetype($file_name);
        $mime_type = $file_mime['type'] ? $file_mime['type'] : 'application/octet-stream';
        
        // Set headers for download
        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . sanitize_file_name($file_name) . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        
        // Stream file from URL
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => 'User-Agent: WordPress/' . get_bloginfo('version'),
                'timeout' => 30
            )
        ));
        
        // Try to get file size from headers (optional)
        $headers = @get_headers($file_url, 1);
        if ($headers && isset($headers['Content-Length'])) {
            $content_length = is_array($headers['Content-Length']) ? end($headers['Content-Length']) : $headers['Content-Length'];
            header('Content-Length: ' . $content_length);
        }
        
        // Stream file
        $file_handle = @fopen($file_url, 'rb', false, $context);
        if ($file_handle) {
            while (!feof($file_handle)) {
                $chunk = fread($file_handle, 8192); // Read in 8KB chunks
                if ($chunk === false) {
                    break;
                }
                echo $chunk;
                flush();
            }
            fclose($file_handle);
        } else {
            // Fallback: redirect to file URL
            wp_redirect($file_url);
        }
        exit;
    }
}
add_action('template_redirect', 'inviro_handle_download');
