<?php
/**
 * Spare Parts Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Spare Parts
 */
function inviro_add_sparepart_meta_boxes() {
    add_meta_box(
        'sparepart_price',
        '💰 Harga Spare Part',
        'inviro_sparepart_price_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_stock',
        '📦 Stok',
        'inviro_sparepart_stock_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_sku',
        '🏷️ Kode SKU',
        'inviro_sparepart_sku_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_promo',
        '🎯 Promo',
        'inviro_sparepart_promo_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_original_price',
        '💰 Harga Asli (untuk Promo)',
        'inviro_sparepart_original_price_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_gallery',
        '🖼️ Gallery Images',
        'inviro_sparepart_gallery_callback',
        'spareparts',
        'normal',
        'high'
    );
    
    add_meta_box(
        'sparepart_specifications',
        '⚙️ Spesifikasi',
        'inviro_sparepart_specifications_callback',
        'spareparts',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_sparepart_meta_boxes');

function inviro_sparepart_price_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $price = get_post_meta($post->ID, '_sparepart_price', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="sparepart_price" name="sparepart_price" 
               value="<?php echo esc_attr($price); ?>" 
               placeholder="150000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Harga dalam Rupiah (tanpa titik/koma)
        </p>
    </div>
    <?php
}

function inviro_sparepart_stock_callback($post) {
    $stock = get_post_meta($post->ID, '_sparepart_stock', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="sparepart_stock" name="sparepart_stock" 
               value="<?php echo esc_attr($stock); ?>" 
               placeholder="10" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Jumlah stok tersedia
        </p>
    </div>
    <?php
}

function inviro_sparepart_sku_callback($post) {
    $sku = get_post_meta($post->ID, '_sparepart_sku', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="text" id="sparepart_sku" name="sparepart_sku" 
               value="<?php echo esc_attr($sku); ?>" 
               placeholder="SP-001" 
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Kode unik untuk spare part
        </p>
    </div>
    <?php
}

function inviro_sparepart_promo_callback($post) {
    $promo = get_post_meta($post->ID, '_sparepart_promo', true);
    ?>
    <div style="padding: 10px 0;">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <input type="checkbox" id="sparepart_promo" name="sparepart_promo" value="1" 
                   <?php checked($promo, '1'); ?>
                   style="width: 20px; height: 20px; cursor: pointer;">
            <span style="font-size: 14px; font-weight: 500;">Tandai sebagai Promo</span>
        </label>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Centang jika spare part ini sedang dalam promo
        </p>
    </div>
    <?php
}

function inviro_sparepart_original_price_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $original_price = get_post_meta($post->ID, '_sparepart_original_price', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="number" id="sparepart_original_price" name="sparepart_original_price" 
               value="<?php echo esc_attr($original_price); ?>" 
               placeholder="200000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Harga sebelum promo (akan dicoret). Kosongkan jika tidak ada promo.
        </p>
    </div>
    <?php
}

function inviro_sparepart_gallery_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $gallery_ids = get_post_meta($post->ID, '_sparepart_gallery', true);
    $gallery_ids = $gallery_ids ? explode(',', $gallery_ids) : array();
    ?>
    <div style="padding: 10px 0;">
        <input type="hidden" id="sparepart_gallery" name="sparepart_gallery" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>">
        <div id="gallery-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
            <?php foreach ($gallery_ids as $img_id) : 
                if ($img_id) :
                    $img_url = wp_get_attachment_image_url($img_id, 'thumbnail');
            ?>
                <div class="gallery-item" data-id="<?php echo esc_attr($img_id); ?>" style="position: relative; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
                    <img src="<?php echo esc_url($img_url); ?>" style="width: 100%; height: 150px; object-fit: cover; display: block;">
                    <button type="button" class="remove-gallery-item" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>
                </div>
            <?php 
                endif;
            endforeach; ?>
        </div>
        <button type="button" id="add-gallery-images" class="button" style="margin-top: 15px;">Tambah Gambar ke Gallery</button>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Tambahkan beberapa gambar untuk ditampilkan di halaman detail product
        </p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var galleryFrame;
        $('#add-gallery-images').on('click', function(e) {
            e.preventDefault();
            if (galleryFrame) {
                galleryFrame.open();
                return;
            }
            galleryFrame = wp.media({
                title: 'Pilih Gambar Gallery',
                button: { text: 'Gunakan Gambar' },
                multiple: true
            });
            galleryFrame.on('select', function() {
                var selection = galleryFrame.state().get('selection');
                var galleryIds = $('#sparepart_gallery').val().split(',').filter(Boolean);
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    if (galleryIds.indexOf(attachment.id.toString()) === -1) {
                        galleryIds.push(attachment.id);
                        $('#gallery-preview').append(
                            '<div class="gallery-item" data-id="' + attachment.id + '" style="position: relative; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">' +
                            '<img src="' + attachment.sizes.thumbnail.url + '" style="width: 100%; height: 150px; object-fit: cover; display: block;">' +
                            '<button type="button" class="remove-gallery-item" style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>' +
                            '</div>'
                        );
                    }
                });
                $('#sparepart_gallery').val(galleryIds.join(','));
            });
            galleryFrame.open();
        });
        $(document).on('click', '.remove-gallery-item', function() {
            var item = $(this).closest('.gallery-item');
            var imgId = item.data('id').toString();
            var galleryIds = $('#sparepart_gallery').val().split(',').filter(Boolean);
            galleryIds = galleryIds.filter(function(id) { return id !== imgId; });
            $('#sparepart_gallery').val(galleryIds.join(','));
            item.remove();
        });
    });
    </script>
    <?php
}

function inviro_sparepart_specifications_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $specs = get_post_meta($post->ID, '_sparepart_specifications', true);
    $specs = $specs ? json_decode($specs, true) : array();
    if (empty($specs)) {
        $specs = array(array('label' => '', 'value' => ''));
    }
    ?>
    <div style="padding: 10px 0;">
        <div id="specifications-list">
            <?php foreach ($specs as $index => $spec) : ?>
                <div class="spec-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                    <input type="text" name="spec_label[]" placeholder="Label (contoh: Material)" 
                           value="<?php echo esc_attr($spec['label']); ?>"
                           style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    <input type="text" name="spec_value[]" placeholder="Value (contoh: Stainless Steel)" 
                           value="<?php echo esc_attr($spec['value']); ?>"
                           style="flex: 2; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">
                    <button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-spec" class="button" style="margin-top: 10px;">Tambah Spesifikasi</button>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Tambahkan spesifikasi produk (contoh: Material, Ukuran, Berat, dll)
        </p>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#add-spec').on('click', function() {
            $('#specifications-list').append(
                '<div class="spec-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">' +
                '<input type="text" name="spec_label[]" placeholder="Label" style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">' +
                '<input type="text" name="spec_value[]" placeholder="Value" style="flex: 2; padding: 10px; border: 2px solid #ddd; border-radius: 6px;">' +
                '<button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Hapus</button>' +
                '</div>'
            );
        });
        $(document).on('click', '.remove-spec', function() {
            if ($('.spec-row').length > 1) {
                $(this).closest('.spec-row').remove();
            } else {
                alert('Minimal harus ada 1 spesifikasi');
            }
        });
    });
    </script>
    <?php
}

function inviro_save_sparepart_meta($post_id) {
    if (get_post_type($post_id) !== 'spareparts') {
        return;
    }
    
    if (!isset($_POST['inviro_sparepart_meta_nonce']) || !wp_verify_nonce($_POST['inviro_sparepart_meta_nonce'], 'inviro_sparepart_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['sparepart_price'])) {
        update_post_meta($post_id, '_sparepart_price', absint($_POST['sparepart_price']));
    }
    
    if (isset($_POST['sparepart_stock'])) {
        update_post_meta($post_id, '_sparepart_stock', absint($_POST['sparepart_stock']));
    }
    
    if (isset($_POST['sparepart_sku'])) {
        update_post_meta($post_id, '_sparepart_sku', sanitize_text_field($_POST['sparepart_sku']));
    }
    
    if (isset($_POST['sparepart_promo'])) {
        update_post_meta($post_id, '_sparepart_promo', '1');
    } else {
        update_post_meta($post_id, '_sparepart_promo', '0');
    }
    
    if (isset($_POST['sparepart_original_price'])) {
        update_post_meta($post_id, '_sparepart_original_price', absint($_POST['sparepart_original_price']));
    }
    
    if (isset($_POST['sparepart_gallery'])) {
        update_post_meta($post_id, '_sparepart_gallery', sanitize_text_field($_POST['sparepart_gallery']));
    }
    
    if (isset($_POST['spec_label']) && isset($_POST['spec_value'])) {
        $specs = array();
        foreach ($_POST['spec_label'] as $index => $label) {
            if (!empty($label) && !empty($_POST['spec_value'][$index])) {
                $specs[] = array(
                    'label' => sanitize_text_field($label),
                    'value' => sanitize_text_field($_POST['spec_value'][$index])
                );
            }
        }
        update_post_meta($post_id, '_sparepart_specifications', json_encode($specs));
    }
}
add_action('save_post_spareparts', 'inviro_save_sparepart_meta');


