<?php
/**
 * Proyek Pelanggan Meta Boxes
 * Clean & Efficient - All fields required
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Proyek Pelanggan
 */
function inviro_add_proyek_meta_boxes() {
    // Keep default editor (deskripsi menggunakan editor seperti artikel dan spare parts)
    // Don't remove editor support
    
    // Nama Klien - Required
    add_meta_box(
        'proyek_client_name',
        '👤 Nama Klien <span style="color: #d32f2f;">*</span>',
        'inviro_proyek_client_name_callback',
        'proyek_pelanggan',
        'side',
        'high'
    );
    
    // Tanggal Proyek - Required
    add_meta_box(
        'proyek_date',
        '📅 Tanggal Proyek <span style="color: #d32f2f;">*</span>',
        'inviro_proyek_date_callback',
        'proyek_pelanggan',
        'side',
        'high'
    );
    
    // Region notice (will be validated)
    add_action('admin_notices', 'inviro_proyek_region_notice');
}
add_action('add_meta_boxes', 'inviro_add_proyek_meta_boxes');

function inviro_proyek_client_name_callback($post) {
    wp_nonce_field('inviro_proyek_meta', 'inviro_proyek_meta_nonce');
    $client_name = get_post_meta($post->ID, '_proyek_client_name', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="text" id="proyek_client_name" name="proyek_client_name" 
               value="<?php echo esc_attr($client_name); ?>" 
               placeholder="Contoh: Bapak Agung" 
               required
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px; color: #d32f2f;">
            <strong>Wajib diisi.</strong> Nama pemilik/klien proyek.
        </p>
    </div>
    <?php
}

function inviro_proyek_date_callback($post) {
    $proyek_date = get_post_meta($post->ID, '_proyek_date', true);
    if (empty($proyek_date)) {
        $proyek_date = date('Y-m-d');
    }
    ?>
    <div style="padding: 10px 0;">
        <input type="date" id="proyek_date" name="proyek_date" 
               value="<?php echo esc_attr($proyek_date); ?>" 
               max="<?php echo date('Y-m-d'); ?>"
               required
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px; color: #d32f2f;">
            <strong>Wajib diisi.</strong> Tanggal pemasangan atau selesai proyek.
        </p>
    </div>
    <?php
}

/**
 * Show admin notice if region is not selected
 */
function inviro_proyek_region_notice() {
    global $post, $pagenow;
    
    // Only show on proyek_pelanggan edit screen
    if ($pagenow === 'post.php' && isset($post) && $post->post_type === 'proyek_pelanggan') {
        $regions = get_the_terms($post->ID, 'region');
        if (!$regions || is_wp_error($regions) || empty($regions)) {
            ?>
            <div class="notice notice-error">
                <p><strong>⚠️ Perhatian:</strong> Proyek ini belum memiliki <strong>Daerah</strong>!</p>
                <p>Silakan pilih Daerah di panel "Daerah" di sidebar kanan. Field ini <strong>WAJIB</strong> diisi.</p>
            </div>
            <?php
        }
    }
    
    // Show on new proyek screen
    if ($pagenow === 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'proyek_pelanggan') {
        ?>
        <div class="notice notice-info">
            <p><strong>💡 Informasi:</strong> Pastikan untuk mengisi semua field yang wajib:</p>
            <ul style="margin: 5px 0; padding-left: 20px;">
                <li><strong>Judul</strong> - Wajib</li>
                <li><strong>Deskripsi</strong> (Editor di bawah judul) - Wajib</li>
                <li><strong>Featured Image</strong> - Wajib</li>
                <li><strong>Daerah</strong> (Panel di sidebar) - Wajib</li>
                <li><strong>Nama Klien</strong> - Wajib</li>
                <li><strong>Tanggal Proyek</strong> - Wajib</li>
            </ul>
        </div>
        <?php
    }
}

function inviro_save_proyek_meta($post_id) {
    if (get_post_type($post_id) !== 'proyek_pelanggan') {
        return;
    }
    
    if (!isset($_POST['inviro_proyek_meta_nonce']) || !wp_verify_nonce($_POST['inviro_proyek_meta_nonce'], 'inviro_proyek_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Validate and save client name - Required
    if (isset($_POST['proyek_client_name'])) {
        $client_name = sanitize_text_field($_POST['proyek_client_name']);
        if (empty(trim($client_name))) {
            // Set error and prevent save
            add_filter('redirect_post_location', function($location) {
                return add_query_arg('proyek_error', 'client_name_required', $location);
            });
            return;
        }
        update_post_meta($post_id, '_proyek_client_name', $client_name);
    } else {
        // Set error and prevent save
        add_filter('redirect_post_location', function($location) {
            return add_query_arg('proyek_error', 'client_name_required', $location);
        });
        return;
    }
    
    // Validate and save date - Required
    if (isset($_POST['proyek_date'])) {
        $proyek_date = sanitize_text_field($_POST['proyek_date']);
        if (empty(trim($proyek_date))) {
            // Set error and prevent save
            add_filter('redirect_post_location', function($location) {
                return add_query_arg('proyek_error', 'date_required', $location);
            });
            return;
        }
        update_post_meta($post_id, '_proyek_date', $proyek_date);
    } else {
        // Set error and prevent save
        add_filter('redirect_post_location', function($location) {
            return add_query_arg('proyek_error', 'date_required', $location);
        });
        return;
    }
    
    // Validate region - Required
    $regions = get_the_terms($post_id, 'region');
    if (!$regions || is_wp_error($regions) || empty($regions)) {
        // Set error and prevent save
        add_filter('redirect_post_location', function($location) {
            return add_query_arg('proyek_error', 'region_required', $location);
        });
        return;
    }
    
    // Validate content (editor) - Required
    $content = get_post_field('post_content', $post_id);
    if (empty(trim(strip_tags($content)))) {
        // Set error and prevent save
        add_filter('redirect_post_location', function($location) {
            return add_query_arg('proyek_error', 'content_required', $location);
        });
        return;
    }
    
    // Validate featured image - Required
    if (!has_post_thumbnail($post_id)) {
        // Set error and prevent save
        add_filter('redirect_post_location', function($location) {
            return add_query_arg('proyek_error', 'thumbnail_required', $location);
        });
        return;
    }
}
add_action('save_post_proyek_pelanggan', 'inviro_save_proyek_meta');

/**
 * Show error messages after redirect
 */
function inviro_proyek_show_errors() {
    if (isset($_GET['proyek_error'])) {
        $error = sanitize_text_field($_GET['proyek_error']);
        $message = '';
        
        switch ($error) {
            case 'client_name_required':
                $message = '⚠️ <strong>Nama Klien</strong> wajib diisi!';
                break;
            case 'date_required':
                $message = '⚠️ <strong>Tanggal Proyek</strong> wajib diisi!';
                break;
            case 'region_required':
                $message = '⚠️ <strong>Daerah</strong> wajib dipilih! Silakan pilih Daerah di panel sidebar.';
                break;
            case 'content_required':
                $message = '⚠️ <strong>Deskripsi</strong> wajib diisi! Silakan isi deskripsi di editor di bawah judul.';
                break;
            case 'thumbnail_required':
                $message = '⚠️ <strong>Featured Image</strong> wajib diisi! Silakan upload gambar di panel Featured Image.';
                break;
        }
        
        if ($message) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo $message; ?></p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'inviro_proyek_show_errors');

/**
 * Add JavaScript validation before save
 */
function inviro_proyek_admin_scripts($hook) {
    global $post_type;
    
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        if ($post_type === 'proyek_pelanggan') {
            ?>
            <script type="text/javascript">
            jQuery(document).ready(function($) {
                var $form = $('#post');
                var isValid = true;
                
                $form.on('submit', function(e) {
                    isValid = true;
                    var errors = [];
                    
                    // Validate client name
                    var clientName = $('#proyek_client_name').val().trim();
                    if (!clientName) {
                        errors.push('Nama Klien wajib diisi!');
                        $('#proyek_client_name').css('border-color', '#d32f2f');
                        isValid = false;
                    } else {
                        $('#proyek_client_name').css('border-color', '#ddd');
                    }
                    
                    // Validate date
                    var proyekDate = $('#proyek_date').val().trim();
                    if (!proyekDate) {
                        errors.push('Tanggal Proyek wajib diisi!');
                        $('#proyek_date').css('border-color', '#d32f2f');
                        isValid = false;
                    } else {
                        $('#proyek_date').css('border-color', '#ddd');
                    }
                    
                    // Validate region - check both checkbox and select
                    var regionChecked = $('input[name="tax_input[region][]"]:checked').length;
                    var regionSelect = $('#newregion_parent').val();
                    var regionInput = $('#new-tag-region').val();
                    
                    // Check if region is selected via checkbox
                    if (regionChecked === 0) {
                        // Check if new region is being added
                        if (!regionInput || regionInput.trim() === '') {
                            errors.push('Daerah wajib dipilih! Silakan pilih Daerah di panel "Daerah" di sidebar.');
                            isValid = false;
                        }
                    }
                    
                    // Validate content
                    var content = '';
                    if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                        content = tinymce.get('content').getContent();
                    } else {
                        content = $('#content').val();
                    }
                    if (!content || content.trim() === '' || content.trim() === '<p></p>' || content.trim() === '<p><br></p>') {
                        errors.push('Deskripsi wajib diisi! Silakan isi deskripsi di editor di bawah judul.');
                        isValid = false;
                    }
                    
                    // Validate featured image
                    var hasThumbnail = $('#_thumbnail_id').val() && $('#_thumbnail_id').val() !== '-1';
                    if (!hasThumbnail) {
                        errors.push('Featured Image wajib diisi! Silakan upload gambar di panel Featured Image.');
                        isValid = false;
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        var errorMsg = '⚠️ Mohon lengkapi semua field yang wajib:\n\n' + errors.join('\n');
                        alert(errorMsg);
                        return false;
                    }
                });
            });
            </script>
            <?php
        }
    }
}
add_action('admin_footer', 'inviro_proyek_admin_scripts');
