<?php
/**
 * Testimonials Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom meta boxes for Testimonials
 */
function inviro_add_testimonial_meta_boxes() {
    add_meta_box(
        'inviro_testimonial_info',
        __('Informasi Testimoni', 'inviro'),
        'inviro_testimonial_info_callback',
        'testimoni',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_testimonial_meta_boxes');

/**
 * Testimonial Info Meta Box Callback
 */
function inviro_testimonial_info_callback($post) {
    wp_nonce_field('inviro_testimonial_meta_nonce', 'inviro_testimonial_meta_nonce');
    
    $customer_name = get_post_meta($post->ID, '_testimonial_customer_name', true);
    $rating = get_post_meta($post->ID, '_testimonial_rating', true);
    $message = get_post_meta($post->ID, '_testimonial_message', true);
    $date = get_post_meta($post->ID, '_testimonial_date', true);
    
    if (!$date) {
        $date = date('d / m / Y');
    }
    ?>
    <p>
        <label for="testimonial_customer_name"><?php _e('Nama Pelanggan:', 'inviro'); ?></label><br>
        <input type="text" name="testimonial_customer_name" id="testimonial_customer_name" value="<?php echo esc_attr($customer_name); ?>" placeholder="Robert R." style="width: 100%; padding: 8px;" required />
    </p>
    
    <p>
        <label for="testimonial_rating"><?php _e('Rating (Bintang):', 'inviro'); ?></label><br>
        <select name="testimonial_rating" id="testimonial_rating" style="width: 100%; padding: 8px;">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>><?php echo $i; ?> Bintang</option>
            <?php endfor; ?>
        </select>
    </p>
    
    <p>
        <label for="testimonial_message"><?php _e('Pesan Testimoni:', 'inviro'); ?></label><br>
        <textarea name="testimonial_message" id="testimonial_message" rows="5" style="width: 100%; padding: 8px;" placeholder="Wow... I am very happy to use this Service, it turned out to be more than my expectations Inviro always the best."><?php echo esc_textarea($message); ?></textarea>
    </p>
    
    <p>
        <label for="testimonial_date"><?php _e('Tanggal Testimoni:', 'inviro'); ?></label><br>
        <input type="text" name="testimonial_date" id="testimonial_date" value="<?php echo esc_attr($date); ?>" placeholder="1 / 10 / 2025" style="width: 100%; padding: 8px;" />
    </p>
    
    <p class="description">
        <?php _e('Foto profil pelanggan dapat diatur di bagian "Featured Image" di sebelah kanan.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Save Testimonial Meta
 */
function inviro_save_testimonial_meta($post_id) {
    // Check post type
    if (get_post_type($post_id) !== 'testimoni') {
        return;
    }
    
    if (!isset($_POST['inviro_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['inviro_testimonial_meta_nonce'], 'inviro_testimonial_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['testimonial_customer_name'])) {
        update_post_meta($post_id, '_testimonial_customer_name', sanitize_text_field($_POST['testimonial_customer_name']));
    }
    
    if (isset($_POST['testimonial_rating'])) {
        update_post_meta($post_id, '_testimonial_rating', intval($_POST['testimonial_rating']));
    }
    
    if (isset($_POST['testimonial_message'])) {
        update_post_meta($post_id, '_testimonial_message', sanitize_textarea_field($_POST['testimonial_message']));
    }
    
    if (isset($_POST['testimonial_date'])) {
        update_post_meta($post_id, '_testimonial_date', sanitize_text_field($_POST['testimonial_date']));
    }
}
add_action('save_post_testimoni', 'inviro_save_testimonial_meta');


