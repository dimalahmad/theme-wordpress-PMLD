<?php
/**
 * Proyek Pelanggan Meta Boxes
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
    // Remove default editor (we'll add custom one)
    remove_post_type_support('proyek_pelanggan', 'editor');
    
    // Panduan Lengkap
    add_meta_box(
        'proyek_panduan',
        '📋 Panduan Mengisi Proyek',
        'inviro_proyek_panduan_callback',
        'proyek_pelanggan',
        'side',
        'high'
    );
    
    // Nama Klien
    add_meta_box(
        'proyek_client_name',
        '👤 Nama Klien',
        'inviro_proyek_client_name_callback',
        'proyek_pelanggan',
        'side',
        'default'
    );
    
    // Tanggal Proyek
    add_meta_box(
        'proyek_date',
        '📅 Tanggal Proyek',
        'inviro_proyek_date_callback',
        'proyek_pelanggan',
        'side',
        'default'
    );
    
    // Deskripsi Lengkap (Custom Editor)
    add_meta_box(
        'proyek_description',
        '📝 Deskripsi Proyek Lengkap',
        'inviro_proyek_description_callback',
        'proyek_pelanggan',
        'normal',
        'high'
    );
    
    // Ringkasan Singkat
    add_meta_box(
        'proyek_excerpt',
        '✍️ Ringkasan Singkat (untuk Card)',
        'inviro_proyek_excerpt_callback',
        'proyek_pelanggan',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_proyek_meta_boxes');

function inviro_proyek_panduan_callback($post) {
    ?>
    <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; border-left: 4px solid #2196F3;">
        <h4 style="margin-top: 0; color: #1976D2;">✅ Checklist:</h4>
        <ol style="margin: 0; padding-left: 20px; line-height: 1.8;">
            <li><strong>Judul:</strong> Nama proyek lengkap</li>
            <li><strong>Featured Image:</strong> Upload foto (sidebar kanan) ⚠️ WAJIB</li>
            <li><strong>Daerah:</strong> Pilih region (sidebar kanan) ⚠️ WAJIB</li>
            <li><strong>Deskripsi:</strong> Detail proyek (di bawah)</li>
            <li><strong>Ringkasan:</strong> 1-2 kalimat (di bawah)</li>
            <li><strong>Nama Klien:</strong> PIC/pemilik (di bawah)</li>
            <li><strong>Tanggal:</strong> Kapan selesai (di bawah)</li>
        </ol>
        <p style="margin-bottom: 0; margin-top: 10px; font-size: 13px; color: #666;">
            💡 <strong>Tip:</strong> Foto berkualitas tinggi akan meningkatkan kredibilitas!
        </p>
    </div>
    <?php
}

function inviro_proyek_client_name_callback($post) {
    wp_nonce_field('inviro_proyek_meta', 'inviro_proyek_meta_nonce');
    $client_name = get_post_meta($post->ID, '_proyek_client_name', true);
    ?>
    <div style="padding: 10px 0;">
        <input type="text" id="proyek_client_name" name="proyek_client_name" 
               value="<?php echo esc_attr($client_name); ?>" 
               placeholder="Contoh: Oleh Agung INVIRO" 
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Nama pemilik/klien proyek. Contoh: "Oleh Agung INVIRO", "Ibu Ruth", dll.
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
               style="width: 100%; padding: 10px; font-size: 14px; border: 2px solid #ddd; border-radius: 6px;">
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            Tanggal pemasangan atau selesai proyek. Max: hari ini.
        </p>
    </div>
    <?php
}

function inviro_proyek_description_callback($post) {
    $description = get_post_meta($post->ID, '_proyek_description', true);
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 10px; color: #666; font-size: 13px;">
            📝 Tulis deskripsi lengkap proyek: lokasi detail, spesifikasi produk yang digunakan, proses pemasangan, dll. (Min. 50 karakter)
        </p>
        <textarea id="proyek_description" name="proyek_description" rows="10" 
                  style="width: 100%; padding: 12px; font-size: 14px; line-height: 1.6; border: 2px solid #ddd; border-radius: 6px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;"
                  placeholder="Contoh:&#10;&#10;Pemasangan Depot Air Minum Isi Ulang di Giwangan, Umbulharjo, Yogyakarta.&#10;&#10;Lokasi: Jl. Giwangan No. 123, Umbulharjo, Yogyakarta&#10;Nama Klien: Bapak Agung&#10;Produk yang digunakan: DAMIU Paket A&#10;&#10;Spesifikasi:&#10;- Filter Air 5 tahap&#10;- Pompa elektrik otomatis&#10;- Tangki penampungan 1000L&#10;&#10;Proses pemasangan berjalan lancar dalam 2 hari."><?php echo esc_textarea($description); ?></textarea>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            💡 Semakin detail, semakin profesional dan kredibel!
        </p>
    </div>
    <?php
}

function inviro_proyek_excerpt_callback($post) {
    $excerpt = get_post_meta($post->ID, '_proyek_excerpt', true);
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 10px; color: #666; font-size: 13px;">
            ✍️ Tulis ringkasan singkat 1-2 kalimat untuk tampilan card di Halaman Pelanggan (Max. 150 karakter)
        </p>
        <textarea id="proyek_excerpt" name="proyek_excerpt" rows="3" 
                  maxlength="150"
                  style="width: 100%; padding: 12px; font-size: 14px; line-height: 1.6; border: 2px solid #ddd; border-radius: 6px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;"
                  placeholder="Contoh: Pemasangan Depot Air Minum di Giwangan, Yogyakarta. Nama Konsumen: Bapak Agung."><?php echo esc_textarea($excerpt); ?></textarea>
        <p class="description" style="margin-top: 8px; font-size: 13px;">
            <span id="excerpt-counter">0</span>/150 karakter
        </p>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('proyek_excerpt');
        const counter = document.getElementById('excerpt-counter');
        
        function updateCounter() {
            counter.textContent = textarea.value.length;
            if (textarea.value.length >= 150) {
                counter.style.color = '#d32f2f';
                counter.style.fontWeight = 'bold';
            } else {
                counter.style.color = '#666';
                counter.style.fontWeight = 'normal';
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
    </script>
    <?php
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
    
    if (isset($_POST['proyek_client_name'])) {
        update_post_meta($post_id, '_proyek_client_name', sanitize_text_field($_POST['proyek_client_name']));
    }
    
    if (isset($_POST['proyek_date'])) {
        update_post_meta($post_id, '_proyek_date', sanitize_text_field($_POST['proyek_date']));
    }
    
    if (isset($_POST['proyek_description'])) {
        update_post_meta($post_id, '_proyek_description', wp_kses_post($_POST['proyek_description']));
    }
    
    if (isset($_POST['proyek_excerpt'])) {
        $excerpt = sanitize_textarea_field($_POST['proyek_excerpt']);
        // Limit to 150 characters
        if (strlen($excerpt) > 150) {
            $excerpt = substr($excerpt, 0, 150);
        }
        update_post_meta($post_id, '_proyek_excerpt', $excerpt);
    }
}
add_action('save_post_proyek_pelanggan', 'inviro_save_proyek_meta');


