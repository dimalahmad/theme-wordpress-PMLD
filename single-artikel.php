<?php
/**
 * Template for single artikel posts
 */

get_header();

while (have_posts()) : the_post();
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name');
    $author_desc = get_the_author_meta('description');
    $author_avatar = get_avatar($author_id, 80);
    $post_date = get_the_date('d F Y');
    
    // Estimate reading time
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Avg 200 words per minute
?>

<div class="artikel-single">
    <!-- Hero Section -->
    <section class="artikel-hero-single">
        <?php if (has_post_thumbnail()) : ?>
            <div class="hero-image">
                <?php the_post_thumbnail('full'); ?>
                <div class="hero-overlay"></div>
            </div>
        <?php endif; ?>
        
        <div class="container">
            <div class="hero-content-single">
                <h1><?php the_title(); ?></h1>
                <div class="artikel-meta">
                    <span class="meta-item">
                        👤 <?php echo esc_html($author_name); ?>
                    </span>
                    <span class="meta-item">
                        📅 <?php echo esc_html($post_date); ?>
                    </span>
                    <span class="meta-item">
                        ⏱️ <?php echo $reading_time; ?> menit
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section class="artikel-content-section">
        <div class="container">
            <div class="artikel-layout">
                <div class="artikel-main">
                    <div class="artikel-content-full">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="artikel-share">
                        <h4>Bagikan Artikel:</h4>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-btn facebook">
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-btn twitter">
                                Twitter
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" class="share-btn whatsapp">
                                WhatsApp
                            </a>
                        </div>
                    </div>
                    
                    <!-- Author Bio -->
                    <?php if ($author_desc) : ?>
                    <div class="author-bio">
                        <div class="author-avatar">
                            <?php echo $author_avatar; ?>
                        </div>
                        <div class="author-info">
                            <h4>Tentang Penulis</h4>
                            <h5><?php echo esc_html($author_name); ?></h5>
                            <p><?php echo esc_html($author_desc); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Navigation -->
                    <div class="artikel-navigation">
                        <div class="nav-previous">
                            <?php 
                            $prev_post = get_previous_post();
                            if ($prev_post) :
                                $prev_url = get_permalink($prev_post->ID);
                                $prev_title = get_the_title($prev_post->ID);
                            ?>
                                <a href="<?php echo esc_url($prev_url); ?>">
                                    <span class="nav-label">← Artikel Sebelumnya</span>
                                    <span class="nav-title"><?php echo esc_html($prev_title); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="nav-next">
                            <?php 
                            $next_post = get_next_post();
                            if ($next_post) :
                                $next_url = get_permalink($next_post->ID);
                                $next_title = get_the_title($next_post->ID);
                            ?>
                                <a href="<?php echo esc_url($next_url); ?>">
                                    <span class="nav-label">Artikel Berikutnya →</span>
                                    <span class="nav-title"><?php echo esc_html($next_title); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Comments -->
                    <?php if (comments_open() || get_comments_number()) : ?>
                        <div class="artikel-comments">
                            <?php comments_template(); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="artikel-sidebar">
                    <!-- Related Articles -->
                    <div class="sidebar-widget">
                        <h3>Artikel Terkait</h3>
                        <?php
                        $related = new WP_Query(array(
                            'post_type' => 'artikel',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'rand'
                        ));
                        
                        if ($related->have_posts()) :
                        ?>
                            <div class="related-articles">
                                <?php while ($related->have_posts()) : $related->the_post(); ?>
                                <div class="related-item">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>" class="related-thumb">
                                            <?php the_post_thumbnail('thumbnail'); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="related-content">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                        <span class="related-date"><?php echo get_the_date('d/m/Y'); ?></span>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                    
                    <!-- CTA -->
                    <div class="sidebar-widget cta-widget">
                        <h3>Butuh Bantuan?</h3>
                        <p>Konsultasikan kebutuhan air minum bisnis Anda dengan kami!</p>
                        <a href="https://wa.me/6281234567890" class="btn-wa" target="_blank">
                            Hubungi Via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.artikel-single {
    padding-top: 80px;
}

.artikel-hero-single {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 120px 0 60px;
    overflow: hidden;
}

.hero-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.3;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.9));
}

.hero-content-single {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.hero-content-single h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    line-height: 1.3;
}

.artikel-meta {
    display: flex;
    justify-content: center;
    gap: 25px;
    flex-wrap: wrap;
}

.meta-item {
    font-size: 0.95rem;
    opacity: 0.95;
}

.artikel-content-section {
    padding: 60px 0;
    background: white;
}

.artikel-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 50px;
    max-width: 1200px;
    margin: 0 auto;
}

.artikel-content-full {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #333;
}

.artikel-content-full p {
    margin-bottom: 20px;
}

.artikel-content-full h2,
.artikel-content-full h3 {
    margin-top: 30px;
    margin-bottom: 15px;
    color: #667eea;
}

.artikel-content-full img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}

.artikel-content-full ul,
.artikel-content-full ol {
    margin-bottom: 20px;
    padding-left: 30px;
}

.artikel-content-full li {
    margin-bottom: 10px;
}

.artikel-share {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e0e0e0;
}

.artikel-share h4 {
    margin-bottom: 15px;
    color: #333;
}

.share-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.share-btn {
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s;
}

.share-btn.facebook {
    background: #3b5998;
}

.share-btn.twitter {
    background: #1da1f2;
}

.share-btn.whatsapp {
    background: #25D366;
}

.share-btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.author-bio {
    display: flex;
    gap: 20px;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 12px;
    margin-top: 40px;
}

.author-avatar img {
    border-radius: 50%;
}

.author-info h4 {
    margin-bottom: 5px;
    color: #667eea;
}

.author-info h5 {
    margin-bottom: 10px;
    color: #333;
}

.author-info p {
    color: #666;
    line-height: 1.6;
    margin: 0;
}

.artikel-navigation {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 50px;
}

.nav-previous,
.nav-next {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s;
}

.nav-previous:hover,
.nav-next:hover {
    border-color: #667eea;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
}

.nav-previous a,
.nav-next a {
    display: block;
    padding: 20px;
    text-decoration: none;
    color: #333;
}

.nav-next a {
    text-align: right;
}

.nav-label {
    display: block;
    font-size: 0.85rem;
    color: #667eea;
    margin-bottom: 8px;
    font-weight: 600;
}

.nav-title {
    display: block;
    font-size: 1rem;
    line-height: 1.4;
}

.artikel-comments {
    margin-top: 50px;
    padding-top: 40px;
    border-top: 2px solid #e0e0e0;
}

.artikel-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.sidebar-widget {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.sidebar-widget h3 {
    margin-bottom: 20px;
    color: #333;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
}

.related-item {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.related-item:last-child {
    margin-bottom: 0;
}

.related-thumb {
    flex-shrink: 0;
}

.related-thumb img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.related-content a {
    display: block;
    color: #333;
    text-decoration: none;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 5px;
    transition: color 0.3s;
}

.related-content a:hover {
    color: #667eea;
}

.related-date {
    font-size: 0.85rem;
    color: #999;
}

.cta-widget {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.cta-widget h3 {
    color: white;
    border-color: rgba(255,255,255,0.3);
}

.cta-widget p {
    margin-bottom: 20px;
    line-height: 1.6;
}

.btn-wa {
    display: inline-block;
    width: 100%;
    padding: 12px 20px;
    background: #25D366;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-wa:hover {
    background: #1fb855;
    transform: translateY(-2px);
}

@media (max-width: 992px) {
    .artikel-layout {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .artikel-sidebar {
        position: static;
    }
    
    .hero-content-single h1 {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .artikel-navigation {
        grid-template-columns: 1fr;
    }
    
    .nav-next a {
        text-align: left;
    }
    
    .author-bio {
        flex-direction: column;
        text-align: center;
    }
    
    .artikel-meta {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<?php
endwhile;
get_footer();
?>
