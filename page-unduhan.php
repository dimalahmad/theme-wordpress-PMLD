<?php
/**
 * Template Name: Unduhan
 */

get_header();
?>

<div class="unduhan-page">
    <!-- Hero Section -->
    <section class="unduhan-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Pusat Unduhan</h1>
                <p>Download katalog produk, brosur, dan dokumen pendukung lainnya</p>
            </div>
        </div>
    </section>

    <!-- Search & Filter -->
    <section class="unduhan-filter">
        <div class="container">
            <div class="filter-bar">
                <input type="text" id="unduhan-search" placeholder="🔍 Cari file..." />
                <select id="unduhan-type">
                    <option value="">Semua Tipe File</option>
                    <option value="pdf">PDF</option>
                    <option value="doc">DOC/DOCX</option>
                    <option value="xls">Excel</option>
                    <option value="zip">ZIP/RAR</option>
                    <option value="image">Gambar</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Unduhan Grid -->
    <section class="unduhan-grid-section">
        <div class="container">
            <div class="unduhan-grid">
                <?php
                // Load dummy data from spareparts directly
                $dummy_spareparts = array();
                if (function_exists('inviro_get_dummy_spareparts')) {
                    $dummy_spareparts = inviro_get_dummy_spareparts();
                }
                // Direct fallback if helper doesn't work
                if (empty($dummy_spareparts)) {
                    $json_file = get_template_directory() . '/dummy-data/spareparts.json';
                    if (file_exists($json_file)) {
                        $json_content = file_get_contents($json_file);
                        $dummy_spareparts = json_decode($json_content, true);
                        if (!is_array($dummy_spareparts)) {
                            $dummy_spareparts = array();
                        }
                    }
                }
                
                // Query untuk spareparts (karena unduhan menggunakan data spareparts)
                $spareparts = new WP_Query(array(
                    'post_type' => 'spareparts',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                // Check if we have real posts
                $has_real_posts = ($spareparts->post_count > 0);
                
                if ($has_real_posts) :
                    while ($spareparts->have_posts()) : $spareparts->the_post();
                        $price = get_post_meta(get_the_ID(), '_sparepart_price', true);
                        $stock = get_post_meta(get_the_ID(), '_sparepart_stock', true);
                        $sku = get_post_meta(get_the_ID(), '_sparepart_sku', true);
                ?>
                <div class="unduhan-card" data-type="pdf">
                    <div class="unduhan-icon pdf">
                        📕
                    </div>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="unduhan-thumb">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="unduhan-content">
                        <h3><?php the_title(); ?></h3>
                        
                        <?php if (has_excerpt()) : ?>
                            <p class="unduhan-desc">
                                <?php the_excerpt(); ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="file-info">
                            <span class="file-type-badge">PDF</span>
                            <?php if ($sku) : ?>
                                <span class="file-size">📦 SKU: <?php echo esc_html($sku); ?></span>
                            <?php endif; ?>
                            <span class="download-count">⬇️ 0 download</span>
                        </div>
                        
                        <a href="#" class="download-btn" download>
                            Unduh File
                        </a>
                    </div>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                
                // Load dummy data from spareparts if no real posts
                if (!$has_real_posts && !empty($dummy_spareparts)) :
                    foreach ($dummy_spareparts as $sparepart) :
                            $wa_number = get_theme_mod('inviro_whatsapp', '6281234567890');
                            ?>
                            <div class="unduhan-card" data-type="pdf">
                                <div class="unduhan-icon pdf">
                                    📕
                                </div>
                                
                                <?php if (!empty($sparepart['image'])) : ?>
                                    <div class="unduhan-thumb">
                                        <img src="<?php echo esc_url($sparepart['image']); ?>" alt="<?php echo esc_attr($sparepart['title']); ?>" loading="lazy">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="unduhan-content">
                                    <h3><?php echo esc_html($sparepart['title']); ?></h3>
                                    
                                    <?php if (!empty($sparepart['description'])) : ?>
                                        <p class="unduhan-desc">
                                            <?php echo esc_html(wp_trim_words($sparepart['description'], 20)); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="file-info">
                                        <span class="file-type-badge">PDF</span>
                                        <?php if (!empty($sparepart['sku'])) : ?>
                                            <span class="file-size">📦 SKU: <?php echo esc_html($sparepart['sku']); ?></span>
                                        <?php endif; ?>
                                        <span class="download-count">⬇️ 0 download</span>
                                    </div>
                                    
                                    <a href="#" class="download-btn" download>
                                        Unduh File
                                    </a>
                                </div>
                            </div>
                            <?php
                    endforeach;
                elseif (!$has_real_posts) :
                    ?>
                    <div class="no-results">
                        <p>Belum ada file. Silakan tambahkan di <strong>Spareparts</strong> > <strong>Tambah Spareparts</strong></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="unduhan-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Butuh Informasi Lebih Lanjut?</h2>
                <p>Hubungi tim kami untuk konsultasi gratis seputar produk dan layanan Inviro</p>
                <a href="https://wa.me/6281234567890" class="btn-wa" target="_blank">
                    Konsultasi Via WhatsApp
                </a>
            </div>
        </div>
    </section>
</div>

<style>
.unduhan-page {
    padding-top: 80px;
}

.unduhan-hero {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #ff7a00 100%);
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.unduhan-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.unduhan-hero h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.unduhan-hero p {
    font-size: 1.1rem;
    opacity: 0.95;
}

.unduhan-filter {
    padding: 30px 0;
    background: white;
    border-bottom: 1px solid #e0e0e0;
}

.filter-bar {
    display: flex;
    gap: 15px;
    max-width: 800px;
    margin: 0 auto;
}

#unduhan-search {
    flex: 1;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
}

#unduhan-type {
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    min-width: 200px;
}

#unduhan-search:focus,
#unduhan-type:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.unduhan-grid-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.unduhan-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.unduhan-card {
    background: white;
    border: 1px solid rgba(0, 123, 255, 0.1);
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.1), 0 0 0 1px rgba(0, 123, 255, 0.05);
    transition: all 0.3s;
    text-align: center;
    position: relative;
}

.unduhan-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(135deg, #007bff 0%, #ff7a00 100%);
    opacity: 0;
    transition: opacity 0.3s;
}

.unduhan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0, 123, 255, 0.25), 0 0 20px rgba(0, 123, 255, 0.2);
    border-color: rgba(0, 123, 255, 0.3);
}

.unduhan-card:hover::before {
    opacity: 1;
}

.unduhan-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 12px;
    display: inline-block;
}

.unduhan-icon.pdf {
    background: #ffebee;
}

.unduhan-icon.doc {
    background: #e3f2fd;
}

.unduhan-icon.xls {
    background: #e8f5e9;
}

.unduhan-icon.zip {
    background: #fff3e0;
}

.unduhan-icon.image {
    background: #f3e5f5;
}

.unduhan-icon.default {
    background: #f5f5f5;
}

.unduhan-thumb {
    margin-bottom: 20px;
}

.unduhan-thumb img {
    max-width: 150px;
    border-radius: 8px;
}

.unduhan-content h3 {
    margin-bottom: 15px;
    color: #333;
    font-size: 1.2rem;
}

.unduhan-desc {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
    font-size: 0.95rem;
}

.file-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.file-type-badge {
    display: inline-block;
    padding: 5px 12px;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

.file-size,
.download-count {
    font-size: 0.9rem;
    color: #666;
}

.download-btn {
    display: inline-block;
    width: 100%;
    padding: 15px 30px;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    position: relative;
    overflow: hidden;
}

.download-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.download-btn:hover::before {
    left: 100%;
}

.download-btn:hover {
    background: linear-gradient(135deg, #ff7a00 0%, #e66a00 100%);
    transform: scale(1.02);
    box-shadow: 0 6px 20px rgba(255, 122, 0, 0.4);
}

.no-file {
    display: block;
    padding: 15px;
    color: #999;
    font-style: italic;
}

.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.unduhan-cta {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #ff7a00 100%);
    padding: 80px 0;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.unduhan-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.unduhan-cta h2 {
    font-size: 2rem;
    margin-bottom: 15px;
}

.unduhan-cta p {
    font-size: 1.1rem;
    margin-bottom: 30px;
    opacity: 0.95;
}

.btn-wa {
    display: inline-block;
    padding: 15px 40px;
    background: #25D366;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-wa:hover {
    background: #1fb855;
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(37, 211, 102, 0.4);
}

@media (max-width: 992px) {
    .unduhan-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-bar {
        flex-direction: column;
    }
    
    #unduhan-type {
        min-width: 100%;
    }
}

@media (max-width: 768px) {
    .unduhan-grid {
        grid-template-columns: 1fr;
        gap: 25px;
    }
    
    .unduhan-hero h1 {
        font-size: 2rem;
    }
    
    .unduhan-cta h2 {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('unduhan-search');
    const typeSelect = document.getElementById('unduhan-type');
    const cards = document.querySelectorAll('.unduhan-card');
    
    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeSelect.value;
        
        cards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const desc = card.querySelector('.unduhan-desc');
            const descText = desc ? desc.textContent.toLowerCase() : '';
            const cardType = card.dataset.type;
            
            const matchesSearch = title.includes(searchTerm) || descText.includes(searchTerm);
            const matchesType = !selectedType || cardType === selectedType;
            
            if (matchesSearch && matchesType) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    if (searchInput && typeSelect) {
        searchInput.addEventListener('input', filterCards);
        typeSelect.addEventListener('change', filterCards);
    }
});
</script>

<?php get_footer(); ?>
