<?php
/**
 * The template for displaying all pages
 *
 * @package INVIRO
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="page-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'inviro'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
            </article>
            <?php
            
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();

