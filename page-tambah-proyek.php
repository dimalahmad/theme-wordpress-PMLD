<?php
/**
 * Template Name: Tambah Proyek
 * 
 * Form untuk menambah proyek pelanggan
 */

get_header(); 
?>

<div class="tambah-proyek-page">
    <section class="form-hero">
        <div class="container">
            <h1>Tambah Proyek Pelanggan</h1>
            <p>Isi form di bawah untuk menambahkan proyek pemasangan INVIRO</p>
        </div>
    </section>

    <section class="form-section">
        <div class="container">
            <div class="form-wrapper">
                
                <?php if (isset($_GET['success']) && $_GET['success'] == '1') : ?>
                <div class="alert alert-success">
                    <strong>✅ Berhasil!</strong> Proyek berhasil ditambahkan. <a href="<?php echo home_url('/pelanggan'); ?>">Lihat di halaman Pelanggan</a>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])) : ?>
                <div class="alert alert-error">
                    <strong>❌ Gagal!</strong> <?php echo esc_html($_GET['error']); ?>
                </div>
                <?php endif; ?>

                <form id="proyek-form" method="post" enctype="multipart/form-data" class="proyek-form">
                    <?php wp_nonce_field('submit_proyek_action', 'submit_proyek_nonce'); ?>

                    <!-- Upload Gambar -->
                    <div class="form-group">
                        <label for="proyek_image">Foto Proyek <span class="required">*</span></label>
                        <div class="image-upload-area" id="imageUploadArea">
                            <div class="upload-placeholder">
                                <span class="upload-icon">📸</span>
                                <p>Klik atau drag & drop foto proyek di sini</p>
                                <p class="upload-hint">Format: JPG, PNG (Max 5MB)</p>
                            </div>
                            <img id="imagePreview" src="" alt="Preview" style="display: none;">
                            <button type="button" class="remove-image" id="removeImage" style="display: none;">✕ Hapus Foto</button>
                        </div>
                        <input type="file" id="proyek_image" name="proyek_image" accept="image/*" required style="display: none;">
                    </div>

                    <!-- Nama Proyek -->
                    <div class="form-group">
                        <label for="proyek_title">Nama Proyek <span class="required">*</span></label>
                        <input type="text" id="proyek_title" name="proyek_title" 
                               placeholder="Contoh: PEMASANGAN DEPOT AIR ISI ULANG GIWANGAN UMBULHARJO YOGYAKARTA" 
                               required>
                        <small>Format: PEMASANGAN [JENIS] [LOKASI LENGKAP]</small>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="proyek_description">Deskripsi Proyek <span class="required">*</span></label>
                        <textarea id="proyek_description" name="proyek_description" rows="6" 
                                  placeholder="Deskripsikan detail pemasangan, spesifikasi produk, lokasi alamat lengkap, dll..." 
                                  required></textarea>
                        <small>Min. 50 karakter</small>
                    </div>

                    <!-- Ringkasan (Excerpt) -->
                    <div class="form-group">
                        <label for="proyek_excerpt">Ringkasan Singkat <span class="required">*</span></label>
                        <textarea id="proyek_excerpt" name="proyek_excerpt" rows="2" 
                                  placeholder="Ringkasan 1-2 kalimat untuk tampilan card..." 
                                  required></textarea>
                        <small>Max. 150 karakter</small>
                    </div>

                    <div class="form-row">
                        <!-- Nama Klien -->
                        <div class="form-group half">
                            <label for="proyek_client">Nama Klien <span class="required">*</span></label>
                            <input type="text" id="proyek_client" name="proyek_client" 
                                   placeholder="Contoh: Oleh Agung INVIRO" 
                                   required>
                        </div>

                        <!-- Tanggal Proyek -->
                        <div class="form-group half">
                            <label for="proyek_date">Tanggal Proyek <span class="required">*</span></label>
                            <input type="date" id="proyek_date" name="proyek_date" 
                                   max="<?php echo date('Y-m-d'); ?>" 
                                   required>
                        </div>
                    </div>

                    <!-- Pilih Daerah -->
                    <div class="form-group">
                        <label for="proyek_region">Daerah/Region <span class="required">*</span></label>
                        <select id="proyek_region" name="proyek_region" required>
                            <option value="">-- Pilih Daerah --</option>
                            <?php
                            $regions = get_terms(array(
                                'taxonomy' => 'region',
                                'hide_empty' => false,
                            ));
                            if ($regions && !is_wp_error($regions)) {
                                foreach ($regions as $region) {
                                    echo '<option value="' . esc_attr($region->term_id) . '">' . esc_html($region->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions">
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <span class="btn-text">📤 Tambah Proyek</span>
                            <span class="btn-loading" style="display: none;">⏳ Mengirim...</span>
                        </button>
                        <a href="<?php echo home_url('/pelanggan'); ?>" class="btn-cancel">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>

<style>
.tambah-proyek-page {
    padding-top: 80px;
}

.form-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    text-align: center;
}

.form-hero h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.form-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.form-wrapper {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.proyek-form .form-group {
    margin-bottom: 25px;
}

.proyek-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.required {
    color: #e74c3c;
}

.proyek-form input[type="text"],
.proyek-form input[type="date"],
.proyek-form select,
.proyek-form textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.3s;
}

.proyek-form input:focus,
.proyek-form select:focus,
.proyek-form textarea:focus {
    outline: none;
    border-color: #667eea;
}

.proyek-form small {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 13px;
}

.image-upload-area {
    position: relative;
    border: 3px dashed #d0d0d0;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    background: #fafafa;
    cursor: pointer;
    transition: all 0.3s;
}

.image-upload-area:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

.upload-placeholder {
    pointer-events: none;
}

.upload-icon {
    font-size: 48px;
    display: block;
    margin-bottom: 10px;
}

.upload-hint {
    font-size: 13px;
    color: #999;
    margin-top: 5px;
}

#imagePreview {
    max-width: 100%;
    max-height: 400px;
    border-radius: 8px;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(231, 76, 60, 0.9);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
}

.remove-image:hover {
    background: #c0392b;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 35px;
}

.btn-submit,
.btn-cancel {
    padding: 15px 35px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    flex: 1;
}

.btn-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-cancel {
    background: #e0e0e0;
    color: #333;
    border: 2px solid #ccc;
}

.btn-cancel:hover {
    background: #d0d0d0;
}

@media (max-width: 768px) {
    .form-wrapper {
        padding: 25px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-hero h1 {
        font-size: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('proyek-form');
    const imageInput = document.getElementById('proyek_image');
    const uploadArea = document.getElementById('imageUploadArea');
    const imagePreview = document.getElementById('imagePreview');
    const removeBtn = document.getElementById('removeImage');
    const submitBtn = document.getElementById('submitBtn');
    
    // Image upload handling
    uploadArea.addEventListener('click', function() {
        imageInput.click();
    });
    
    // Drag & drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#667eea';
        this.style.background = '#f0f4ff';
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#d0d0d0';
        this.style.background = '#fafafa';
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#d0d0d0';
        this.style.background = '#fafafa';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            previewImage(files[0]);
        }
    });
    
    imageInput.addEventListener('change', function(e) {
        if (this.files.length > 0) {
            previewImage(this.files[0]);
        }
    });
    
    function previewImage(file) {
        // Check file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Max 5MB');
            return;
        }
        
        // Check file type
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar!');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            removeBtn.style.display = 'block';
            uploadArea.querySelector('.upload-placeholder').style.display = 'none';
            uploadArea.style.padding = '20px';
        };
        reader.readAsDataURL(file);
    }
    
    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        imageInput.value = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        this.style.display = 'none';
        uploadArea.querySelector('.upload-placeholder').style.display = 'block';
        uploadArea.style.padding = '40px';
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const description = document.getElementById('proyek_description').value;
        const excerpt = document.getElementById('proyek_excerpt').value;
        
        if (description.length < 50) {
            e.preventDefault();
            alert('Deskripsi minimal 50 karakter!');
            return;
        }
        
        if (excerpt.length > 150) {
            e.preventDefault();
            alert('Ringkasan maksimal 150 karakter!');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.querySelector('.btn-text').style.display = 'none';
        submitBtn.querySelector('.btn-loading').style.display = 'inline';
    });
});
</script>

<?php get_footer(); ?>
