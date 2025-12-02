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
        <div class="container">
            <?php 
            $thumbnail_url = '';
            if (has_post_thumbnail()) {
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                if (empty($thumbnail_url)) {
                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                }
                if (empty($thumbnail_url)) {
                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                }
                if (empty($thumbnail_url)) {
                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                }
                if (empty($thumbnail_url)) {
                    $thumbnail_id = get_post_thumbnail_id(get_the_ID());
                    if ($thumbnail_id) {
                        $image_data = wp_get_attachment_image_src($thumbnail_id, 'full');
                        if ($image_data && !empty($image_data[0])) {
                            $thumbnail_url = $image_data[0];
                        }
                    }
                }
            }
            if (!empty($thumbnail_url)) :
            ?>
                <div class="hero-image">
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width: 100% !important; height: 100% !important; max-height: 655px !important; display: block !important; visibility: visible !important; opacity: 1 !important; object-fit: cover !important; object-position: center !important; position: absolute !important; top: 0 !important; left: 0 !important; margin: 0 !important; padding: 0 !important;">
                </div>
            <?php endif; ?>
            
            <div class="hero-content-single">
                <h1><?php the_title(); ?></h1>
                <div class="artikel-meta">
                    <span class="meta-item">
                        👤 <?php echo esc_html($author_name); ?>
                    </span>
                    <span class="meta-item">
                        📅 <?php echo esc_html($post_date); ?>
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
                                <?php while ($related->have_posts()) : $related->the_post(); 
                                    $related_id = get_the_ID();
                                    $related_image_url = '';
                                    if (has_post_thumbnail($related_id)) {
                                        $related_image_url = get_the_post_thumbnail_url($related_id, 'medium_large');
                                        if (empty($related_image_url)) {
                                            $related_image_url = get_the_post_thumbnail_url($related_id, 'large');
                                        }
                                        if (empty($related_image_url)) {
                                            $related_image_url = get_the_post_thumbnail_url($related_id, 'medium');
                                        }
                                        if (empty($related_image_url)) {
                                            $related_image_url = get_the_post_thumbnail_url($related_id, 'thumbnail');
                                        }
                                        if (empty($related_image_url)) {
                                            $related_thumb_id = get_post_thumbnail_id($related_id);
                                            if ($related_thumb_id) {
                                                $related_image_data = wp_get_attachment_image_src($related_thumb_id, 'medium');
                                                if ($related_image_data && !empty($related_image_data[0])) {
                                                    $related_image_url = $related_image_data[0];
                                                }
                                            }
                                        }
                                    }
                                ?>
                                <div class="related-item">
                                    <?php if (!empty($related_image_url)) : ?>
                                        <a href="<?php the_permalink(); ?>" class="related-thumb">
                                            <img src="<?php echo esc_url($related_image_url); ?>" alt="<?php echo esc_attr(get_the_title($related_id)); ?>" style="width: 90px !important; height: 90px !important; object-fit: cover !important; display: block !important; visibility: visible !important; opacity: 1 !important;">
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

<?php
endwhile;
get_footer();
?>
