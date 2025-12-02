<?php
/**
 * Template part for displaying testimonials section
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get selected testimonials (up to 32)
$selected_testimonials = array();
for ($i = 1; $i <= 32; $i++) {
    $testimonial_id = get_theme_mod('inviro_testimonial_' . $i);
    if ($testimonial_id) {
        $selected_testimonials[] = $testimonial_id;
    }
}
?>

<section id="testimonials" class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_testimonials_title', 'Dipercaya Oleh Banyak Pelanggan')); ?></h2>
            <p class="section-subtitle" itemprop="description"><?php echo esc_html(get_theme_mod('inviro_testimonials_subtitle', '95% Pelanggan INVIRO di berbagai daerah di Indonesia merasa puas dengan pelayanan & produk INVIRO')); ?></p>
        </div>
        
        <div class="testimonials-carousel">
            <div class="testimonials-track">
                <?php if (!empty($selected_testimonials)) : ?>
                    <?php
                    $testimonials = new WP_Query(array(
                        'post_type' => 'testimoni',
                        'post__in' => $selected_testimonials,
                        'orderby' => 'post__in',
                        'posts_per_page' => -1
                    ));
                    
                    if ($testimonials->have_posts()) :
                        while ($testimonials->have_posts()) : $testimonials->the_post();
                            $testimonial_id = get_the_ID();
                            $customer_name = get_post_meta($testimonial_id, '_testimonial_customer_name', true);
                            $rating = get_post_meta($testimonial_id, '_testimonial_rating', true);
                            $message = get_post_meta($testimonial_id, '_testimonial_message', true);
                            $date = get_post_meta($testimonial_id, '_testimonial_date', true);
                            
                            if (!$customer_name) $customer_name = get_the_title($testimonial_id);
                            if (!$rating) $rating = 5;
                            if (!$message) {
                                $post_obj = get_post($testimonial_id);
                                $message = $post_obj ? $post_obj->post_content : '';
                            }
                            if (!$date) $date = get_the_date('d / m / Y', $testimonial_id);
                            
                            $testimonial_image_url = inviro_get_image_url($testimonial_id, 'inviro-testimonial');
                            
                            $testimonial_data = array(
                                'customer_name' => $customer_name,
                                'name' => $customer_name,
                                'rating' => $rating,
                                'message' => $message,
                                'date' => $date,
                                'image_url' => $testimonial_image_url,
                                'avatar' => $testimonial_image_url,
                                'has_schema' => true
                            );
                            
                            inviro_render_testimonial_card($testimonial_data);
                        endwhile;
                        wp_reset_postdata();
                    endif;
                else :
                    // Fallback to dummy data
                    $dummy_testimonials = function_exists('inviro_get_dummy_testimonials') ? inviro_get_dummy_testimonials() : array();
                    if (!empty($dummy_testimonials)) :
                        foreach ($dummy_testimonials as $testimonial) :
                            $testimonial['has_schema'] = true;
                            inviro_render_testimonial_card($testimonial);
                        endforeach;
                    else :
                        // Ultimate fallback - hardcoded testimonials
                        $default_testimonials = array(
                            array('name' => 'Robert B.', 'rating' => 5, 'date' => '1/1/2024', 'message' => 'Wow... I am so happy to see this business is turned out to be more than my expectations and as for the service, I am so pleased. Thank you so much!', 'content' => 'Wow... I am so happy to see this business is turned out to be more than my expectations and as for the service, I am so pleased. Thank you so much!', 'avatar' => 'https://via.placeholder.com/100x100/75C6F1/FFFFFF?text=RB', 'has_schema' => false),
                            array('name' => 'Diana M.', 'rating' => 5, 'date' => '1/1/2024', 'message' => 'Wow... I am very happy to use this service. It turned out to be more than my expectations and as for the service, I am so pleased. Thank you so much!', 'content' => 'Wow... I am very happy to use this service. It turned out to be more than my expectations and as for the service, I am so pleased. Thank you so much!', 'avatar' => 'https://via.placeholder.com/100x100/FF8B25/FFFFFF?text=DM', 'has_schema' => false),
                            array('name' => 'Syahrani P.', 'rating' => 5, 'date' => '1/10/2024', 'message' => 'Wow... I am very happy to use this Service. It turned out to be more than my expectations and so far there have been no problems. Inviro always the best.', 'content' => 'Wow... I am very happy to use this Service. It turned out to be more than my expectations and so far there have been no problems. Inviro always the best.', 'avatar' => 'https://via.placeholder.com/100x100/4FB3E8/FFFFFF?text=SP', 'has_schema' => false)
                        );
                        
                        foreach ($default_testimonials as $testimonial) :
                            inviro_render_testimonial_card($testimonial);
                        endforeach;
                    endif;
                endif;
                ?>
            </div>
            
            <div class="testimonials-controls">
                <button class="testimonial-prev" aria-label="Previous testimonial">‹</button>
                <button class="testimonial-next" aria-label="Next testimonial">›</button>
            </div>
            
            <div class="testimonials-indicators"></div>
        </div>
    </div>
</section>
