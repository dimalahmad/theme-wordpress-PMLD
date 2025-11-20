<?php
/**
 * Template Name: Spare Parts
 */

get_header();
?>

<div class="spareparts-page">
    <!-- Hero Section -->
    <section class="spareparts-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Spare Parts</h1>
                <p>INVIRO menyediakan spareparts untuk berbagai mesin air minum isi ulang</p>
            </div>
        </div>
    </section>

    <!-- Search & Filter -->
    <section class="spareparts-filter">
        <div class="container">
            <div class="filter-bar">
                <input type="text" id="sparepart-search" placeholder="🔍 Cari spare part..." />
                <select id="sort-by">
                    <option value="latest">Terbaru</option>
                    <option value="price-low">Harga: Rendah - Tinggi</option>
                    <option value="price-high">Harga: Tinggi - Rendah</option>
                    <option value="name">Nama A-Z</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Spare Parts Grid -->
    <section class="spareparts-grid-section">
        <div class="container">
            <div class="spareparts-grid">
                <?php
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
                <div class="sparepart-card" data-price="<?php echo esc_attr($price); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="sparepart-image">
                            <?php the_post_thumbnail('medium'); ?>
                            <?php if ($stock && $stock > 0) : ?>
                                <span class="stock-badge in-stock">✓ Tersedia</span>
                            <?php else : ?>
                                <span class="stock-badge out-stock">✕ Habis</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="sparepart-content">
                        <?php if ($sku) : ?>
                            <span class="sparepart-sku"><?php echo esc_html($sku); ?></span>
                        <?php endif; ?>
                        
                        <h3><?php the_title(); ?></h3>
                        
                        <?php if (has_excerpt()) : ?>
                            <p class="sparepart-desc"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                        <?php endif; ?>
                        
                        <div class="sparepart-meta">
                            <?php if ($price) : ?>
                                <span class="sparepart-price">
                                    Rp <?php echo number_format($price, 0, ',', '.'); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($stock) : ?>
                                <span class="sparepart-stock">
                                    Stok: <?php echo esc_html($stock); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="sparepart-actions">
                            <a href="https://wa.me/6281234567890?text=Saya%20tertarik%20dengan%20<?php echo urlencode(get_the_title()); ?>" 
                               class="btn-order" target="_blank">
                                💬 Pesan via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                
                // Load dummy data if no real posts
                if (!$has_real_posts) :
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
                        }
                    }
                    if (!empty($dummy_spareparts)) :
                        foreach ($dummy_spareparts as $sparepart) :
                            $wa_number = get_theme_mod('inviro_whatsapp', '6281234567890');
                            $wa_message = 'Saya%20tertarik%20dengan%20' . urlencode($sparepart['title']);
                            ?>
                            <div class="sparepart-card" data-price="<?php echo esc_attr($sparepart['price']); ?>" data-name="<?php echo esc_attr($sparepart['title']); ?>">
                                <div class="sparepart-image">
                                    <img src="<?php echo esc_url($sparepart['image']); ?>" alt="<?php echo esc_attr($sparepart['title']); ?>" loading="lazy">
                                    <?php if ($sparepart['stock'] > 0) : ?>
                                        <span class="stock-badge in-stock">✓ Tersedia</span>
                                    <?php else : ?>
                                        <span class="stock-badge out-stock">✕ Habis</span>
                                    <?php endif; ?>
                                </div>
                                <div class="sparepart-content">
                                    <?php if (!empty($sparepart['sku'])) : ?>
                                        <span class="sparepart-sku"><?php echo esc_html($sparepart['sku']); ?></span>
                                    <?php endif; ?>
                                    <h3><?php echo esc_html($sparepart['title']); ?></h3>
                                    <?php if (!empty($sparepart['description'])) : ?>
                                        <p class="sparepart-desc"><?php echo esc_html(wp_trim_words($sparepart['description'], 15)); ?></p>
                                    <?php endif; ?>
                                    <div class="sparepart-meta">
                                        <span class="sparepart-price">
                                            Rp <?php echo number_format($sparepart['price'], 0, ',', '.'); ?>
                                        </span>
                                        <span class="sparepart-stock">
                                            Stok: <?php echo esc_html($sparepart['stock']); ?>
                                        </span>
                                    </div>
                                    <div class="sparepart-actions">
                                        <a href="https://wa.me/<?php echo esc_attr($wa_number); ?>?text=<?php echo esc_attr($wa_message); ?>" 
                                           class="btn-order" target="_blank">
                                            💬 Pesan via WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    else :
                        ?>
                        <div class="no-results">
                            <p>Belum ada spare part. Silakan tambahkan di <strong>Spare Parts</strong> > <strong>Tambah Spare Part</strong></p>
                        </div>
                        <?php
                    endif;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="spareparts-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Butuh Konsultasi?</h2>
                <p>Hubungi kami untuk bantuan memilih spare part yang tepat</p>
                <a href="https://wa.me/6281234567890" class="btn-whatsapp" target="_blank">
                    💬 Chat WhatsApp
                </a>
            </div>
        </div>
    </section>
</div>

<style>
.spareparts-page {
    padding-top: 80px;
}

.spareparts-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    text-align: center;
}

.spareparts-hero h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.spareparts-hero p {
    font-size: 1.1rem;
    opacity: 0.95;
}

.spareparts-filter {
    padding: 30px 0;
    background: white;
    border-bottom: 1px solid #e0e0e0;
}

.filter-bar {
    display: flex;
    gap: 15px;
    max-width: 600px;
    margin: 0 auto;
}

#sparepart-search,
#sort-by {
    padding: 12px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
}

#sparepart-search {
    flex: 1;
}

#sort-by {
    min-width: 200px;
}

.spareparts-grid-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.spareparts-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}

.sparepart-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.sparepart-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.sparepart-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.sparepart-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.stock-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.stock-badge.in-stock {
    background: #4caf50;
    color: white;
}

.stock-badge.out-stock {
    background: #f44336;
    color: white;
}

.sparepart-content {
    padding: 20px;
}

.sparepart-sku {
    display: inline-block;
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 10px;
}

.sparepart-content h3 {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #333;
}

.sparepart-desc {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.5;
    margin-bottom: 15px;
}

.sparepart-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
}

.sparepart-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #667eea;
}

.sparepart-stock {
    font-size: 0.9rem;
    color: #666;
}

.sparepart-actions {
    margin-top: 15px;
}

.btn-order {
    display: block;
    text-align: center;
    background: #25D366;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-order:hover {
    background: #22c55e;
}

.spareparts-cta {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    text-align: center;
}

.spareparts-cta h2 {
    font-size: 2rem;
    margin-bottom: 15px;
}

.spareparts-cta p {
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.btn-whatsapp {
    display: inline-block;
    background: #25D366;
    color: white;
    padding: 15px 40px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s;
}

.btn-whatsapp:hover {
    background: #22c55e;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

@media (max-width: 992px) {
    .spareparts-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .spareparts-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .filter-bar {
        flex-direction: column;
    }
    
    #sort-by {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .spareparts-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sparepart-search');
    const sortSelect = document.getElementById('sort-by');
    const cards = document.querySelectorAll('.sparepart-card');
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            cards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const desc = card.querySelector('.sparepart-desc');
                const descText = desc ? desc.textContent.toLowerCase() : '';
                
                if (title.includes(searchTerm) || descText.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Sort functionality
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const grid = document.querySelector('.spareparts-grid');
            const cardsArray = Array.from(cards);
            
            cardsArray.sort((a, b) => {
                switch(sortValue) {
                    case 'price-low':
                        return parseInt(a.dataset.price || 0) - parseInt(b.dataset.price || 0);
                    case 'price-high':
                        return parseInt(b.dataset.price || 0) - parseInt(a.dataset.price || 0);
                    case 'name':
                        return a.dataset.name.localeCompare(b.dataset.name);
                    default: // latest
                        return 0;
                }
            });
            
            cardsArray.forEach(card => grid.appendChild(card));
        });
    }
});
</script>

<?php get_footer(); ?>
