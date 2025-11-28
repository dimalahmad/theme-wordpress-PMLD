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
 * Add custom meta boxes for Paket Usaha
 */
function inviro_add_paket_usaha_meta_boxes() {
    add_meta_box(
        'inviro_paket_description',
        __('Deskripsi Paket', 'inviro'),
        'inviro_paket_description_callback',
        'paket_usaha',
        'normal',
        'high'
    );
    
    add_meta_box(
        'paket_price',
        '💰 Harga Paket',
        'inviro_paket_price_callback',
        'paket_usaha',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_sku',
        '🏷️ Kode SKU',
        'inviro_paket_sku_callback',
        'paket_usaha',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_promo',
        '🎯 Promo',
        'inviro_paket_promo_callback',
        'paket_usaha',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_original_price',
        '💰 Harga Asli (untuk Promo)',
        'inviro_paket_original_price_callback',
        'paket_usaha',
        'side',
        'default'
    );
    
    add_meta_box(
        'paket_gallery',
        '🖼️ Gallery Images',
        'inviro_paket_gallery_callback',
        'paket_usaha',
        'normal',
        'high'
    );
    
    add_meta_box(
        'paket_specifications',
        '⚙️ Spesifikasi',
        'inviro_paket_specifications_callback',
        'paket_usaha',
        'normal',
        'default'
    );
    
    add_meta_box(
        'paket_customer_info',
        '👤 Informasi Konsumen',
        'inviro_paket_customer_info_callback',
        'paket_usaha',
        'normal',
        'high'
    );
    
    add_meta_box(
        'paket_installation_info',
        '📍 Informasi Pemasangan',
        'inviro_paket_installation_info_callback',
        'paket_usaha',
        'normal',
        'high'
    );
    
    add_meta_box(
        'paket_additional_info',
        'ℹ️ Info Tambahan',
        'inviro_paket_additional_info_callback',
        'paket_usaha',
        'normal',
        'default'
    );
    
    add_meta_box(
        'paket_bonus',
        '🎁 Bonus Paket',
        'inviro_paket_bonus_callback',
        'paket_usaha',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_paket_usaha_meta_boxes');

/**
 * Paket Usaha Description Meta Box Callback
 */
function inviro_paket_description_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $description = get_post_meta($post->ID, '_paket_description', true);
    ?>
    <p>
        <label for="paket_description"><?php _e('Deskripsi Paket:', 'inviro'); ?></label><br>
        <textarea name="paket_description" id="paket_description" rows="5" style="width: 100%; padding: 8px;" placeholder="DAMIU Paket A dengan kapasitas..."><?php echo esc_textarea($description); ?></textarea>
    </p>
    <p class="description">
        <?php _e('Tulis deskripsi lengkap tentang paket usaha ini.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Paket Usaha Price Meta Box Callback
 */
function inviro_paket_price_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $price = get_post_meta($post->ID, '_paket_price', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="paket_price" name="paket_price" 
               value="<?php echo esc_attr($price); ?>" 
               placeholder="5000000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Harga dalam Rupiah (tanpa titik/koma)
        </p>
    </div>
    <?php
}

function inviro_paket_sku_callback($post) {
    $sku = get_post_meta($post->ID, '_paket_sku', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="text" id="paket_sku" name="paket_sku" 
               value="<?php echo esc_attr($sku); ?>" 
               placeholder="PKT-001" 
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Kode unik untuk paket usaha
        </p>
    </div>
    <?php
}

function inviro_paket_promo_callback($post) {
    $promo = get_post_meta($post->ID, '_paket_promo', true);
    ?>
    <div style="padding: 10px 0;">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <input type="checkbox" id="paket_promo" name="paket_promo" value="1" 
                   <?php checked($promo, '1'); ?>
                   style="width: 20px; height: 20px; cursor: pointer;">
            <span style="font-size: 14px; font-weight: 500;">Tandai sebagai Promo</span>
        </label>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Centang jika paket ini sedang dalam promo
        </p>
    </div>
    <?php
}

function inviro_paket_original_price_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $original_price = get_post_meta($post->ID, '_paket_original_price', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="paket_original_price" name="paket_original_price" 
               value="<?php echo esc_attr($original_price); ?>" 
               placeholder="6000000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Harga sebelum promo (akan dicoret). Kosongkan jika tidak ada promo.
        </p>
    </div>
    <?php
}

/**
 * Save Paket Usaha Meta
 */
function inviro_save_paket_usaha_meta($post_id, $post = null) {
    // Check post type
    if (get_post_type($post_id) !== 'paket_usaha') {
        return;
    }
    
    // Skip autosave - hanya save saat manual save/publish
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Skip revision
    if (wp_is_post_revision($post_id)) {
        return;
    }
    
    // Check nonce - wajib untuk manual save
    if (!isset($_POST['inviro_paket_meta_nonce']) || !wp_verify_nonce($_POST['inviro_paket_meta_nonce'], 'inviro_paket_meta_nonce')) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Prevent infinite loop
    if (defined('DOING_PAKET_SAVE') && DOING_PAKET_SAVE) {
        return;
    }
    define('DOING_PAKET_SAVE', true);
    
    if (isset($_POST['paket_description'])) {
        update_post_meta($post_id, '_paket_description', sanitize_textarea_field($_POST['paket_description']));
    }
    
    if (isset($_POST['paket_price'])) {
        update_post_meta($post_id, '_paket_price', absint($_POST['paket_price']));
    }
    
    if (isset($_POST['paket_sku'])) {
        update_post_meta($post_id, '_paket_sku', sanitize_text_field($_POST['paket_sku']));
    }
    
    if (isset($_POST['paket_promo'])) {
        update_post_meta($post_id, '_paket_promo', '1');
    } else {
        update_post_meta($post_id, '_paket_promo', '0');
    }
    
    // Auto-assign kategori berdasarkan status promo
    $promo_status = get_post_meta($post_id, '_paket_promo', true);
    
    // Ambil kategori yang sudah ada (termasuk yang dipilih user)
    $current_categories = wp_get_post_terms($post_id, 'paket_usaha_category', array('fields' => 'ids'));
    if (is_wp_error($current_categories)) {
        $current_categories = array();
    }
    
    // Ambil ID kategori status (Promo dan Tersedia)
    $promo_term_obj = get_term_by('slug', 'promo', 'paket_usaha_category');
    $tersedia_term_obj = get_term_by('slug', 'tersedia', 'paket_usaha_category');
    
    $promo_term_id = $promo_term_obj ? $promo_term_obj->term_id : null;
    $tersedia_term_id = $tersedia_term_obj ? $tersedia_term_obj->term_id : null;
    
    // Buat kategori jika belum ada
    if (!$promo_term_id) {
        $promo_term = wp_insert_term('Promo', 'paket_usaha_category', array('slug' => 'promo'));
        if (!is_wp_error($promo_term)) {
            $promo_term_id = is_array($promo_term) ? $promo_term['term_id'] : $promo_term;
        }
    }
    
    if (!$tersedia_term_id) {
        $tersedia_term = wp_insert_term('Tersedia', 'paket_usaha_category', array('slug' => 'tersedia'));
        if (!is_wp_error($tersedia_term)) {
            $tersedia_term_id = is_array($tersedia_term) ? $tersedia_term['term_id'] : $tersedia_term;
        }
    }
    
    // Hapus kategori status lama (Promo/Tersedia) dari array
    $current_categories = array_filter($current_categories, function($cat_id) use ($promo_term_id, $tersedia_term_id) {
        return $cat_id != $promo_term_id && $cat_id != $tersedia_term_id;
    });
    
    // Tambahkan kategori status baru berdasarkan promo
    if ($promo_status == '1' && $promo_term_id) {
        $current_categories[] = $promo_term_id;
    } elseif ($promo_status != '1' && $tersedia_term_id) {
        $current_categories[] = $tersedia_term_id;
    }
    
    // Update kategori post (true = replace semua kategori dengan yang baru)
    wp_set_post_terms($post_id, array_map('intval', array_unique($current_categories)), 'paket_usaha_category', true);
    
    // Save original price - bisa kosong jika tidak ada promo
    if (isset($_POST['paket_original_price'])) {
        $original_price = sanitize_text_field($_POST['paket_original_price']);
        if (!empty($original_price) && is_numeric($original_price)) {
            update_post_meta($post_id, '_paket_original_price', absint($original_price));
        } else {
            // Jika dikosongkan, hapus meta
            delete_post_meta($post_id, '_paket_original_price');
        }
    }
    
    // Save gallery images - ALWAYS check and save
    // Gunakan array_key_exists untuk cek apakah field ada di POST
    $gallery_input = isset($_POST['paket_gallery']) ? $_POST['paket_gallery'] : '';
    $gallery_ids = array();
    
    if (!empty($gallery_input) && trim($gallery_input) !== '') {
        // Convert to array
        if (is_string($gallery_input)) {
            $gallery_ids = array_map('absint', explode(',', trim($gallery_input)));
        } elseif (is_array($gallery_input)) {
            $gallery_ids = array_map('absint', $gallery_input);
        }
        
        // Remove zeros and duplicates
        $gallery_ids = array_filter($gallery_ids, function($id) {
            return $id > 0;
        });
        $gallery_ids = array_unique($gallery_ids);
        $gallery_ids = array_values($gallery_ids);
    }
    
    // FORCE SAVE - selalu update
    if (!empty($gallery_ids)) {
        $gallery_value = implode(',', $gallery_ids);
        $saved = update_post_meta($post_id, '_paket_gallery', $gallery_value);
    } else {
        // Jika kosong, hapus meta
        delete_post_meta($post_id, '_paket_gallery');
    }
    
    if (isset($_POST['spec_label']) && isset($_POST['spec_value'])) {
        $specs = array();
        foreach ($_POST['spec_label'] as $index => $label) {
            $value = isset($_POST['spec_value'][$index]) ? $_POST['spec_value'][$index] : '';
            $quantity = isset($_POST['spec_quantity'][$index]) ? $_POST['spec_quantity'][$index] : '1';
            $unit = isset($_POST['spec_unit'][$index]) ? $_POST['spec_unit'][$index] : 'Unit';
            
            if (!empty($label) || !empty($value)) {
                $specs[] = array(
                    'label' => sanitize_text_field($label),
                    'value' => sanitize_textarea_field($value),
                    'quantity' => sanitize_text_field($quantity),
                    'unit' => sanitize_text_field($unit)
                );
            }
        }
        update_post_meta($post_id, '_paket_specifications', json_encode($specs));
    }
    
    if (isset($_POST['paket_customer_name'])) {
        update_post_meta($post_id, '_paket_customer_name', sanitize_text_field($_POST['paket_customer_name']));
    }
    
    if (isset($_POST['paket_depot_name'])) {
        update_post_meta($post_id, '_paket_depot_name', sanitize_text_field($_POST['paket_depot_name']));
    }
    
    if (isset($_POST['paket_location'])) {
        update_post_meta($post_id, '_paket_location', sanitize_textarea_field($_POST['paket_location']));
    }
    
    if (isset($_POST['paket_location_map'])) {
        update_post_meta($post_id, '_paket_location_map', esc_url_raw($_POST['paket_location_map']));
    }
    
    if (isset($_POST['paket_product_purchased'])) {
        update_post_meta($post_id, '_paket_product_purchased', sanitize_textarea_field($_POST['paket_product_purchased']));
    }
    
    if (isset($_POST['paket_installation_date'])) {
        update_post_meta($post_id, '_paket_installation_date', sanitize_text_field($_POST['paket_installation_date']));
    }
    
    if (isset($_POST['paket_additional_info'])) {
        update_post_meta($post_id, '_paket_additional_info', wp_kses_post($_POST['paket_additional_info']));
    }
    
    if (isset($_POST['bonus_item'])) {
        $bonus_items = array();
        foreach ($_POST['bonus_item'] as $item) {
            $item = trim(sanitize_text_field($item));
            if (!empty($item)) {
                $bonus_items[] = array('item' => $item);
            }
        }
        update_post_meta($post_id, '_paket_bonus', json_encode($bonus_items));
    } else {
        update_post_meta($post_id, '_paket_bonus', '');
    }
}
add_action('save_post_paket_usaha', 'inviro_save_paket_usaha_meta', 10, 2);

// Note: AJAX handler for save_paket_gallery is in inc/ajax/ajax-handlers.php
// Note: Post views counter functions (inviro_set_paket_views, inviro_get_paket_views, inviro_track_paket_views) are in inc/hooks/hooks.php

function inviro_paket_gallery_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    
    // Get gallery IDs - WordPress standard way
    $gallery_value = get_post_meta($post->ID, '_paket_gallery', true);
    $gallery_ids = array();
    
    if (!empty($gallery_value)) {
        if (is_string($gallery_value)) {
            $gallery_ids = array_map('absint', explode(',', $gallery_value));
        } elseif (is_array($gallery_value)) {
            $gallery_ids = array_map('absint', $gallery_value);
        }
        $gallery_ids = array_filter($gallery_ids, function($id) {
            return $id > 0;
        });
    }
    
    $gallery_value = !empty($gallery_ids) ? implode(',', $gallery_ids) : '';
    ?>
    <div style="padding: 10px 0;">
        <!-- Hidden field untuk menyimpan gallery IDs -->
        <input type="hidden" id="paket_gallery" name="paket_gallery" value="<?php echo esc_attr($gallery_value); ?>" autocomplete="off">
        <div id="gallery-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
            <?php foreach ($gallery_ids as $img_id) : 
                $img_id = absint($img_id);
                if ($img_id > 0) {
                    $img_url = wp_get_attachment_image_url($img_id, 'thumbnail');
                    if (!$img_url) {
                        $img_url = wp_get_attachment_url($img_id);
                    }
                    if ($img_url) :
            ?>
                <div class="gallery-item" data-id="<?php echo esc_attr($img_id); ?>" style="position: relative; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
                    <img src="<?php echo esc_url($img_url); ?>" style="width: 100%; height: 150px; object-fit: cover; display: block;">
                    <button type="button" class="remove-gallery-item" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>
                </div>
            <?php 
                    endif;
                }
            endforeach; ?>
        </div>
        <button type="button" id="add-gallery-images" class="button button-primary" style="margin-top: 15px;">+ Tambah Gambar ke Gallery</button>
        <p class="description" style="margin-top: 8px; font-size: 13px; color: #666;">
            Tambahkan beberapa gambar untuk ditampilkan di halaman detail paket. Gambar pertama akan digunakan sebagai thumbnail di halaman listing.
        </p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var galleryFrame;
        
        // Update hidden field function
        function updateGalleryField() {
            var ids = [];
            $('#gallery-preview .gallery-item').each(function() {
                var id = parseInt($(this).data('id'));
                if (id > 0) {
                    ids.push(id);
                }
            });
            var galleryValue = ids.join(',');
            $('#paket_gallery').val(galleryValue);
            
            // AJAX save langsung ke database (auto-save)
            var postId = $('#post_ID').val() || <?php echo $post->ID; ?>;
            if (postId && typeof ajaxurl !== 'undefined') {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'save_paket_gallery',
                        post_id: postId,
                        gallery_ids: galleryValue,
                        nonce: '<?php echo wp_create_nonce('save_paket_gallery_' . $post->ID); ?>'
                    },
                    success: function(response) {
                        if (response && response.success) {
                            console.log('Gallery auto-saved:', galleryValue);
                        } else {
                            console.log('Gallery save failed:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX error:', error);
                    }
                });
            }
        }
        
        // Add images button
        $('#add-gallery-images').on('click', function(e) {
            e.preventDefault();
            
            if (galleryFrame) {
                galleryFrame.open();
                return;
            }
            
            galleryFrame = wp.media({
                title: 'Pilih Gambar Gallery',
                button: { text: 'Tambahkan ke Gallery' },
                multiple: true,
                library: { type: 'image' }
            });
            
            galleryFrame.on('select', function() {
                var selection = galleryFrame.state().get('selection');
                var existingIds = $('#paket_gallery').val() ? $('#paket_gallery').val().split(',') : [];
                
                selection.each(function(attachment) {
                    var id = attachment.id.toString();
                    if (existingIds.indexOf(id) === -1) {
                        existingIds.push(id);
                        
                        var thumbUrl = attachment.attributes.sizes && attachment.attributes.sizes.thumbnail 
                            ? attachment.attributes.sizes.thumbnail.url 
                            : attachment.attributes.url;
                        
                        var $item = $('<div class="gallery-item" data-id="' + id + '" style="position: relative; border: 2px solid #ddd; border-radius: 8px; overflow: hidden; margin-bottom: 10px;">' +
                            '<img src="' + thumbUrl + '" style="width: 100%; height: 150px; object-fit: cover; display: block;">' +
                            '<button type="button" class="remove-gallery-item" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>' +
                            '</div>');
                        
                        $('#gallery-preview').append($item);
                    }
                });
                
                updateGalleryField();
            });
            
            galleryFrame.open();
        });
        
        // Remove image
        $(document).on('click', '.remove-gallery-item', function(e) {
            e.preventDefault();
            $(this).closest('.gallery-item').remove();
            updateGalleryField();
        });
        
        // CRITICAL: Force update before ANY form action
        // Update on form submit
        $('#post').on('submit', function(e) {
            updateGalleryField();
            // Force update one more time after a tiny delay
            setTimeout(function() {
                updateGalleryField();
                // Ensure field is in form
                if (!$('#paket_gallery').length) {
                    $('#post').append('<input type="hidden" id="paket_gallery" name="paket_gallery" value="">');
                }
            }, 50);
        });
        
        // Update on publish/update button click - BEFORE submit
        $(document).on('mousedown', '#publish, #save-post, input[name="save"], input[name="publish"]', function() {
            updateGalleryField();
        });
        
        // Also update on click (before mousedown)
        $(document).on('click', '#publish, #save-post, input[name="save"], input[name="publish"]', function() {
            updateGalleryField();
        });
        
        // Update when leaving page (beforeunload)
        $(window).on('beforeunload', function() {
            updateGalleryField();
        });
        
        // Update periodically (safety net)
        setInterval(function() {
            updateGalleryField();
        }, 2000);
        
        // Initial update
        updateGalleryField();
    });
    </script>
    <?php
}

function inviro_paket_specifications_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $specs = get_post_meta($post->ID, '_paket_specifications', true);
    $specs = $specs ? json_decode($specs, true) : array();
    if (empty($specs)) {
        $specs = array(array('label' => '', 'value' => '', 'quantity' => '1', 'unit' => 'Unit'));
    }
    ?>
    <div style="padding: 10px 0;">
        <p class="description" style="margin-bottom: 15px; font-size: 13px;">
            Tambahkan spesifikasi paket dalam format tabel. Label akan menjadi judul, Value akan menjadi detail spesifikasi.
        </p>
        <div id="specifications-list">
            <?php foreach ($specs as $index => $spec) : ?>
                <div class="spec-row" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 6px; background: #f9f9f9;">
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Komponen & Spesifikasi Teknis:</label>
                            <input type="text" name="spec_label[]" placeholder="Label (contoh: Mesin RO Kaps. 2.000 GPD)" 
                                   value="<?php echo esc_attr(isset($spec['label']) ? $spec['label'] : ''); ?>"
                                   style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 10px;">
                            <textarea name="spec_value[]" placeholder="Detail spesifikasi (contoh: Satu Buah Housing & Membran RO 2.000 GPD...)" 
                                      rows="3"
                                      style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;"><?php echo esc_textarea(isset($spec['value']) ? $spec['value'] : ''); ?></textarea>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jumlah:</label>
                            <input type="text" name="spec_quantity[]" placeholder="1" 
                                   value="<?php echo esc_attr(isset($spec['quantity']) ? $spec['quantity'] : '1'); ?>"
                                   style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 10px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Satuan:</label>
                            <input type="text" name="spec_unit[]" placeholder="Unit" 
                                   value="<?php echo esc_attr(isset($spec['unit']) ? $spec['unit'] : 'Unit'); ?>"
                                   style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                        </div>
                    </div>
                    <button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-spec" class="button" style="margin-top: 10px;">Tambah Spesifikasi</button>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#add-spec').on('click', function() {
            $('#specifications-list').append(
                '<div class="spec-row" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 6px; background: #f9f9f9;">' +
                '<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px; margin-bottom: 10px;">' +
                '<div>' +
                '<label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Komponen & Spesifikasi Teknis:</label>' +
                '<input type="text" name="spec_label[]" placeholder="Label (contoh: Mesin RO Kaps. 2.000 GPD)" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 10px;">' +
                '<textarea name="spec_value[]" placeholder="Detail spesifikasi (contoh: Satu Buah Housing & Membran RO 2.000 GPD...)" rows="3" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;"></textarea>' +
                '</div>' +
                '<div>' +
                '<label style="display: block; margin-bottom: 5px; font-weight: 600;">Jumlah:</label>' +
                '<input type="text" name="spec_quantity[]" placeholder="1" value="1" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 10px;">' +
                '<label style="display: block; margin-bottom: 5px; font-weight: 600;">Satuan:</label>' +
                '<input type="text" name="spec_unit[]" placeholder="Unit" value="Unit" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">' +
                '</div>' +
                '</div>' +
                '<button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>' +
                '</div>'
            );
        });
        $(document).on('click', '.remove-spec', function() {
            $(this).closest('.spec-row').remove();
        });
    });
    </script>
    <?php
}

/**
 * Paket Customer Info Meta Box Callback
 */
function inviro_paket_customer_info_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $customer_name = get_post_meta($post->ID, '_paket_customer_name', true);
    $depot_name = get_post_meta($post->ID, '_paket_depot_name', true);
    ?>
    <div style="padding: 10px 0;">
        <p>
            <label for="paket_customer_name" style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Konsumen/Pembeli:</label>
            <input type="text" id="paket_customer_name" name="paket_customer_name" 
                   value="<?php echo esc_attr($customer_name); ?>" 
                   placeholder="Bapak Ilyas" 
                   style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        </p>
        <p>
            <label for="paket_depot_name" style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Depot Air Minum:</label>
            <input type="text" id="paket_depot_name" name="paket_depot_name" 
                   value="<?php echo esc_attr($depot_name); ?>" 
                   placeholder="TUK WENING" 
                   style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        </p>
    </div>
    <?php
}

/**
 * Paket Installation Info Meta Box Callback
 */
function inviro_paket_installation_info_callback($post) {
    $location = get_post_meta($post->ID, '_paket_location', true);
    $location_map = get_post_meta($post->ID, '_paket_location_map', true);
    $product_purchased = get_post_meta($post->ID, '_paket_product_purchased', true);
    $installation_date = get_post_meta($post->ID, '_paket_installation_date', true);
    ?>
    <div style="padding: 10px 0;">
        <p>
            <label for="paket_location" style="display: block; margin-bottom: 5px; font-weight: 600;">Lokasi/Alamat Pemasangan:</label>
            <textarea id="paket_location" name="paket_location" rows="3"
                   placeholder="Jl. Palagan Tentara Pelajar KM 15, Pelem, Purwobinangun, Pakem, Sleman"
                   style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;"><?php echo esc_textarea($location); ?></textarea>
        </p>
        <p>
            <label for="paket_location_map" style="display: block; margin-bottom: 5px; font-weight: 600;">Detail/Shareloc Lokasi (Google Maps URL):</label>
            <input type="url" id="paket_location_map" name="paket_location_map" 
                   value="<?php echo esc_attr($location_map); ?>" 
                   placeholder="https://maps.google.com/..." 
                   style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        </p>
        <p>
            <label for="paket_product_purchased" style="display: block; margin-bottom: 5px; font-weight: 600;">Produk yang Dibeli di INVIRO:</label>
            <textarea id="paket_product_purchased" name="paket_product_purchased" rows="2"
                   placeholder="DAMIU Paket Kombinasi 200 GPD | Etalase Putih No. 07 + ongkos kirim ke lokasi"
                   style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;"><?php echo esc_textarea($product_purchased); ?></textarea>
        </p>
        <p>
            <label for="paket_installation_date" style="display: block; margin-bottom: 5px; font-weight: 600;">Unit DAMIU Terpasang di Lokasi pada Tanggal:</label>
            <input type="date" id="paket_installation_date" name="paket_installation_date" 
                   value="<?php echo esc_attr($installation_date); ?>" 
                   style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        </p>
    </div>
    <?php
}

/**
 * Paket Additional Info Meta Box Callback
 */
function inviro_paket_additional_info_callback($post) {
    $additional_info = get_post_meta($post->ID, '_paket_additional_info', true);
    ?>
    <div style="padding: 10px 0;">
        <p>
            <label for="paket_additional_info" style="display: block; margin-bottom: 5px; font-weight: 600;">Info Menarik Lainnya (Opsional):</label>
            <textarea id="paket_additional_info" name="paket_additional_info" rows="10"
                   placeholder="Info menarik tentang lokasi, daerah, atau informasi tambahan lainnya..."
                   style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;"><?php echo esc_textarea($additional_info); ?></textarea>
        </p>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Informasi tambahan tentang lokasi, daerah, atau hal menarik lainnya yang relevan dengan artikel.
        </p>
    </div>
    <?php
}

/**
 * Paket Bonus Meta Box Callback
 */
function inviro_paket_bonus_callback($post) {
    wp_nonce_field('inviro_paket_meta_nonce', 'inviro_paket_meta_nonce');
    $bonus = get_post_meta($post->ID, '_paket_bonus', true);
    $bonus = $bonus ? json_decode($bonus, true) : array();
    if (empty($bonus)) {
        $bonus = array(array('item' => ''));
    }
    ?>
    <div style="padding: 10px 0;">
        <p class="description" style="margin-bottom: 15px; font-size: 13px;">
            Tambahkan daftar bonus yang termasuk dalam paket (contoh: Surat Jaminan Lolos Uji Lab, Tutup Galon, Tissue Antiseptic, dll)
        </p>
        <div id="bonus-list">
            <?php foreach ($bonus as $index => $bonus_item) : ?>
                <div class="bonus-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                    <input type="text" name="bonus_item[]" placeholder="Item bonus (contoh: Surat Jaminan Lolos Uji Lab. Dinas Kesehatan)" 
                           value="<?php echo esc_attr($bonus_item['item']); ?>"
                           style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    <button type="button" class="remove-bonus button" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-bonus" class="button" style="margin-top: 10px;">Tambah Bonus</button>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#add-bonus').on('click', function() {
            $('#bonus-list').append(
                '<div class="bonus-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">' +
                '<input type="text" name="bonus_item[]" placeholder="Item bonus (contoh: Surat Jaminan Lolos Uji Lab. Dinas Kesehatan)" style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">' +
                '<button type="button" class="remove-bonus button" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>' +
                '</div>'
            );
        });
        $(document).on('click', '.remove-bonus', function() {
            $(this).closest('.bonus-row').remove();
        });
    });
    </script>
    <?php
}


