<?php
/**
 * The main template file
 *
 * @package INVIRO
 */

get_header();
?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Article">
                    <header class="entry-header">
                        <h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
                        <?php if (is_single()) : ?>
                            <div class="entry-meta">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                                <?php if (get_the_author()) : ?>
                                    <span class="entry-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name"><?php the_author(); ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </header>
                    
                    <?php if (has_post_thumbnail() && is_single()) : ?>
                        <div class="entry-thumbnail">
                            <?php 
                            the_post_thumbnail('large', array(
                                'loading' => 'lazy',
                                'itemprop' => 'image'
                            )); 
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="entry-content" itemprop="articleBody">
                        <?php the_content(); ?>
                    </div>
                    
                    <?php if (is_single() && has_tag()) : ?>
                        <footer class="entry-footer">
                            <div class="entry-tags">
                                <?php the_tags('<span class="tags-label">' . esc_html__('Tags:', 'inviro') . '</span> ', ' ', ''); ?>
                            </div>
                        </footer>
                    <?php endif; ?>
                </article>
                <?php
            endwhile;
        else :
            ?>
            <section class="no-results">
                <h2><?php esc_html_e('Tidak ada konten yang ditemukan.', 'inviro'); ?></h2>
                <p><?php esc_html_e('Maaf, tidak ada konten yang sesuai dengan kriteria Anda.', 'inviro'); ?></p>
            </section>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();

