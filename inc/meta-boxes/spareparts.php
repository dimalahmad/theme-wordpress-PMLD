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
        '💰 Harga',
        'inviro_sparepart_price_callback',
        'spareparts',
        'side',
        'default'
    );
    
    add_meta_box(
        'sparepart_specifications',
        '⚙️ Spesifikasi',
        'inviro_sparepart_specifications_callback',
        'spareparts',
        'normal',
        'default'
    );
    
    add_meta_box(
        'sparepart_reviews',
        '⭐ Ulasan',
        'inviro_sparepart_reviews_callback',
        'spareparts',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'inviro_add_sparepart_meta_boxes');

function inviro_sparepart_price_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    $price_raw = get_post_meta($post->ID, '_sparepart_price', true);
    $original_price_raw = get_post_meta($post->ID, '_sparepart_original_price', true);
    $promo = get_post_meta($post->ID, '_sparepart_promo', true);
    
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
        <input type="number" id="sparepart_original_price" name="sparepart_original_price" 
               value="<?php echo esc_attr($original_price); ?>" 
               placeholder="20000000" 
               min="0"
               required
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
        <p class="description" style="margin-top: 8px; font-size: 13px; margin-bottom: 15px;">
            Harga asli dalam Rupiah (tanpa titik/koma) - <strong>Wajib diisi</strong>
        </p>
        
        <p style="margin-bottom: 10px;"><strong>Harga Promo (Opsional):</strong></p>
        <input type="number" id="sparepart_price" name="sparepart_price" 
               value="<?php echo esc_attr($price); ?>" 
               placeholder="15000000" 
               min="0"
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px; margin-bottom: 15px;">
        <p class="description" style="margin-top: 8px; font-size: 13px; margin-bottom: 15px;">
            Harga promo (akan ditampilkan sebagai harga saat ini). Kosongkan jika tidak ada promo.
        </p>
        
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
    <script>
    jQuery(document).ready(function($) {
        $('#post').on('submit', function(e) {
            var originalPrice = $('#sparepart_original_price').val();
            if (!originalPrice || originalPrice.trim() === '' || parseInt(originalPrice) <= 0) {
                e.preventDefault();
                alert('Harga Asli wajib diisi!');
                $('#sparepart_original_price').focus();
                return false;
            }
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
        $specs = array(array('component' => '', 'quantity' => '', 'unit' => ''));
    }
    ?>
    <div style="padding: 10px 0;">
        <p class="description" style="margin-bottom: 15px; font-size: 13px;">
            Tambahkan spesifikasi produk dengan format: Nama Komponen/Spesifikasi Teknis/Fungsi, Jumlah, dan Satuan
        </p>
        <div id="specifications-list">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                <thead>
                    <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                        <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">No</th>
                        <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Nama Komponen, Spesifikasi Teknis & Fungsi</th>
                        <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Jml</th>
                        <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Sat</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($specs as $index => $spec) : ?>
                        <tr class="spec-row">
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center; width: 50px;">
                                <?php echo $index + 1; ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <input type="text" name="spec_component[]" 
                                       value="<?php echo esc_attr($spec['component']); ?>"
                                       placeholder="Nama Komponen, Spesifikasi Teknis & Fungsi"
                                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; width: 100px;">
                                <input type="text" name="spec_quantity[]" 
                                       value="<?php echo esc_attr($spec['quantity']); ?>"
                                       placeholder="Jumlah"
                                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; width: 100px;">
                                <input type="text" name="spec_unit[]" 
                                       value="<?php echo esc_attr($spec['unit']); ?>"
                                       placeholder="Satuan"
                                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center; width: 100px;">
                                <button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="button" id="add-spec" class="button button-primary" style="margin-top: 10px;">+ Tambah Spesifikasi</button>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var specIndex = <?php echo count($specs); ?>;
        
        $('#add-spec').on('click', function() {
            specIndex++;
            var newRow = '<tr class="spec-row">' +
                '<td style="padding: 10px; border: 1px solid #ddd; text-align: center; width: 50px;">' + specIndex + '</td>' +
                '<td style="padding: 10px; border: 1px solid #ddd;">' +
                '<input type="text" name="spec_component[]" placeholder="Nama Komponen, Spesifikasi Teknis & Fungsi" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">' +
                '</td>' +
                '<td style="padding: 10px; border: 1px solid #ddd; width: 100px;">' +
                '<input type="text" name="spec_quantity[]" placeholder="Jumlah" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">' +
                '</td>' +
                '<td style="padding: 10px; border: 1px solid #ddd; width: 100px;">' +
                '<input type="text" name="spec_unit[]" placeholder="Satuan" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">' +
                '</td>' +
                '<td style="padding: 10px; border: 1px solid #ddd; text-align: center; width: 100px;">' +
                '<button type="button" class="remove-spec button" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Hapus</button>' +
                '</td>' +
                '</tr>';
            $('#specifications-list tbody').append(newRow);
        });
        
        $(document).on('click', '.remove-spec', function() {
            if ($('.spec-row').length > 1) {
                $(this).closest('.spec-row').remove();
                // Update nomor urut
                $('#specifications-list tbody .spec-row').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            } else {
                alert('Minimal harus ada 1 spesifikasi');
            }
        });
    });
    </script>
    <?php
}

function inviro_sparepart_reviews_callback($post) {
    wp_nonce_field('inviro_sparepart_meta', 'inviro_sparepart_meta_nonce');
    ?>
    <div style="padding: 10px 0;">
        <p class="description" style="margin-bottom: 15px; font-size: 13px;">
            Ulasan dapat ditambahkan melalui form di halaman detail produk atau melalui menu <strong>Ulasan Spare Parts</strong> di sidebar.
        </p>
        <p class="description" style="margin-bottom: 15px; font-size: 13px;">
            Untuk menambahkan ulasan manual, silakan buka menu <strong>Ulasan Spare Parts</strong> dan tambahkan ulasan baru dengan mengisi:
        </p>
        <ul style="margin-left: 20px; margin-bottom: 15px;">
            <li><strong>Nama:</strong> Nama reviewer (meta field: _reviewer_name)</li>
            <li><strong>Tanggal:</strong> Tanggal ulasan (gunakan post date)</li>
            <li><strong>Bintang:</strong> Rating 1-5 (meta field: _review_rating)</li>
            <li><strong>Pesan:</strong> Isi ulasan (post content)</li>
        </ul>
        <p class="description" style="font-size: 13px;">
            Pastikan untuk mengisi meta field <strong>_review_sparepart_id</strong> dengan ID sparepart ini (<?php echo $post->ID; ?>) dan <strong>_review_status</strong> dengan "approved" agar ulasan muncul di halaman detail.
        </p>
    </div>
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
    
    // Harga asli wajib diisi
    if (isset($_POST['sparepart_original_price']) && !empty($_POST['sparepart_original_price'])) {
        update_post_meta($post_id, '_sparepart_original_price', absint($_POST['sparepart_original_price']));
    } else {
        // Jika harga asli kosong, set error
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Harga Asli wajib diisi!</p></div>';
        });
    }
    
    // Harga promo opsional
    if (isset($_POST['sparepart_price']) && !empty($_POST['sparepart_price'])) {
        update_post_meta($post_id, '_sparepart_price', absint($_POST['sparepart_price']));
    } else {
        // Jika tidak ada harga promo, gunakan harga asli
        if (isset($_POST['sparepart_original_price']) && !empty($_POST['sparepart_original_price'])) {
            update_post_meta($post_id, '_sparepart_price', absint($_POST['sparepart_original_price']));
        }
    }
    
    if (isset($_POST['sparepart_promo'])) {
        update_post_meta($post_id, '_sparepart_promo', '1');
    } else {
        update_post_meta($post_id, '_sparepart_promo', '0');
    }
    
    if (isset($_POST['spec_component']) && isset($_POST['spec_quantity']) && isset($_POST['spec_unit'])) {
        $specs = array();
        foreach ($_POST['spec_component'] as $index => $component) {
            if (!empty($component)) {
                $specs[] = array(
                    'component' => sanitize_text_field($component),
                    'quantity' => sanitize_text_field($_POST['spec_quantity'][$index]),
                    'unit' => sanitize_text_field($_POST['spec_unit'][$index])
                );
            }
        }
        update_post_meta($post_id, '_sparepart_specifications', json_encode($specs));
    }
}
add_action('save_post_spareparts', 'inviro_save_sparepart_meta');
