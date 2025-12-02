<?php
/**
 * The front page template file
 *
 * @package INVIRO
 */

get_header();
?>

<main id="main" class="site-main">
    <?php
    // Hero Section
    get_template_part('template-parts/front-page/hero-section');
    
    // Statistics Section
    get_template_part('template-parts/front-page/statistics-section');
    
    // About Section
    get_template_part('template-parts/front-page/about-section');
    
    // Products Section
    get_template_part('template-parts/front-page/products-section');
    
    // Testimonials Section
    get_template_part('template-parts/front-page/testimonials-section');
    
    // Contact Section
    get_template_part('template-parts/front-page/contact-section');
    ?>
</main>

<?php
get_footer();
