<?php
/**
 * Single Proyek Pelanggan Template
 */

get_header();
?>

<div class="single-proyek-page">
    <?php while (have_posts()) : the_post(); 
        $client_name = get_post_meta(get_the_ID(), '_proyek_client_name', true);
        $proyek_date = get_post_meta(get_the_ID(), '_proyek_date', true);
        $description = get_post_meta(get_the_ID(), '_proyek_description', true);
        $excerpt = get_post_meta(get_the_ID(), '_proyek_excerpt', true);
        $regions = get_the_terms(get_the_ID(), 'region');
        
        // Format date
        $formatted_date = '';
        if ($proyek_date) {
            $formatted_date = date('d F Y', strtotime($proyek_date));
        }
    ?>
    
    <!-- Hero Section -->
    <section class="proyek-hero">
        <?php if (has_post_thumbnail()) : ?>
            <div class="proyek-hero-image">
                <?php the_post_thumbnail('full'); ?>
                <div class="hero-overlay"></div>
            </div>
        <?php endif; ?>
        <div class="container">
            <div class="proyek-hero-content">
                <div class="proyek-meta-top">
                    <?php if ($regions && !is_wp_error($regions)) : ?>
                        <span class="proyek-region">📍 <?php echo esc_html($regions[0]->name); ?></span>
                    <?php endif; ?>
                    <?php if ($formatted_date) : ?>
                        <span class="proyek-date">📅 <?php echo esc_html($formatted_date); ?></span>
                    <?php endif; ?>
                </div>
                <h1 class="proyek-title"><?php the_title(); ?></h1>
                <?php if ($client_name) : ?>
                    <p class="proyek-client">👤 <?php echo esc_html($client_name); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="proyek-content">
        <div class="container">
            <div class="content-wrapper">
                
                <!-- Excerpt/Summary -->
                <?php if (!empty($excerpt)) : ?>
                <div class="proyek-summary">
                    <h3>Ringkasan Proyek</h3>
                    <p><?php echo esc_html($excerpt); ?></p>
                </div>
                <?php endif; ?>

                <!-- Main Content -->
                <div class="proyek-description">
                    <h3>Detail Proyek</h3>
                    <?php 
                    if (!empty($description)) {
                        echo wpautop($description);
                    } else {
                        the_content();
                    }
                    ?>
                </div>

                <!-- Project Info Box -->
                <div class="proyek-info-box">
                    <h3>Informasi Proyek</h3>
                    <div class="info-grid">
                        <?php if ($client_name) : ?>
                        <div class="info-item">
                            <span class="info-icon">👤</span>
                            <div class="info-content">
                                <strong>Nama Klien</strong>
                                <p><?php echo esc_html($client_name); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($formatted_date) : ?>
                        <div class="info-item">
                            <span class="info-icon">📅</span>
                            <div class="info-content">
                                <strong>Tanggal Proyek</strong>
                                <p><?php echo esc_html($formatted_date); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($regions && !is_wp_error($regions)) : ?>
                        <div class="info-item">
                            <span class="info-icon">📍</span>
                            <div class="info-content">
                                <strong>Daerah</strong>
                                <p><?php echo esc_html($regions[0]->name); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <span class="info-icon">🏢</span>
                            <div class="info-content">
                                <strong>Dikerjakan Oleh</strong>
                                <p>INVIRO - Water Treatment Equipment</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="proyek-navigation">
                    <a href="<?php echo home_url('/pelanggan'); ?>" class="btn-back">
                        ← Kembali ke Daftar Proyek
                    </a>
                    
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <div class="nav-arrows">
                        <?php if ($prev_post) : ?>
                            <a href="<?php echo get_permalink($prev_post); ?>" class="nav-arrow prev">
                                ← Proyek Sebelumnya
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <a href="<?php echo get_permalink($next_post); ?>" class="nav-arrow next">
                                Proyek Selanjutnya →
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="proyek-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Tertarik dengan Layanan Kami?</h2>
                <p>Hubungi kami untuk konsultasi gratis dan penawaran terbaik</p>
                <div class="cta-buttons">
                    <a href="<?php echo home_url('/paket-usaha'); ?>" class="btn-primary">Lihat Paket</a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn-whatsapp">
                        💬 Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php endwhile; ?>
</div>

<style>
.single-proyek-page {
    padding-top: 80px;
}

.proyek-hero {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    overflow: hidden;
}

.proyek-hero-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.proyek-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
}

.proyek-hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    padding: 60px 20px;
}

.proyek-meta-top {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.proyek-region,
.proyek-date {
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
}

.proyek-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 15px;
    text-transform: uppercase;
    line-height: 1.3;
}

.proyek-client {
    font-size: 1.2rem;
    opacity: 0.95;
}

.proyek-content {
    padding: 80px 0;
    background: #f8f9fa;
}

.content-wrapper {
    max-width: 900px;
    margin: 0 auto;
}

.proyek-summary {
    background: white;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    border-left: 5px solid #667eea;
}

.proyek-summary h3 {
    color: #667eea;
    margin-bottom: 15px;
}

.proyek-description {
    background: white;
    padding: 40px;
    border-radius: 12px;
    margin-bottom: 30px;
    line-height: 1.8;
}

.proyek-description h3 {
    color: #333;
    margin-bottom: 20px;
    font-size: 1.8rem;
}

.proyek-description p {
    margin-bottom: 15px;
    color: #555;
}

.proyek-info-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 12px;
    margin-bottom: 40px;
}

.proyek-info-box h3 {
    margin-bottom: 25px;
    text-align: center;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}

.info-item {
    display: flex;
    gap: 15px;
    background: rgba(255, 255, 255, 0.1);
    padding: 20px;
    border-radius: 10px;
}

.info-icon {
    font-size: 2rem;
}

.info-content strong {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
    opacity: 0.9;
}

.info-content p {
    font-size: 16px;
    font-weight: 600;
}

.proyek-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 40px;
    gap: 20px;
    flex-wrap: wrap;
}

.btn-back {
    background: #667eea;
    color: white;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #5568d3;
    transform: translateX(-5px);
}

.nav-arrows {
    display: flex;
    gap: 15px;
}

.nav-arrow {
    background: #e0e0e0;
    color: #333;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.nav-arrow:hover {
    background: #d0d0d0;
}

.proyek-cta {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    text-align: center;
}

.cta-content h2 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.cta-content p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.95;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary,
.btn-whatsapp {
    padding: 15px 35px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s;
}

.btn-primary {
    background: white;
    color: #667eea;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.btn-whatsapp {
    background: #25D366;
    color: white;
}

.btn-whatsapp:hover {
    background: #22c55e;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

@media (max-width: 768px) {
    .proyek-title {
        font-size: 1.8rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .proyek-navigation {
        flex-direction: column;
    }
    
    .nav-arrows {
        width: 100%;
        flex-direction: column;
    }
    
    .proyek-description {
        padding: 25px;
    }
}
</style>

<?php get_footer(); ?>
