<?php
/**
 * Products Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom meta boxes for Products
 */
function inviro_add_product_meta_boxes() {
    add_meta_box(
        'inviro_product_description',
        __('Deskripsi Produk', 'inviro'),
        'inviro_product_description_callback',
        'produk',
        'normal',
        'high'
    );
    
    add_meta_box(
        'inviro_product_price',
        __('Harga Produk', 'inviro'),
        'inviro_product_price_callback',
        'produk',
        'normal',
        'high'
    );
    
    add_meta_box(
        'inviro_product_buy_url',
        __('URL Tombol Beli', 'inviro'),
        'inviro_product_buy_url_callback',
        'produk',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_product_meta_boxes');

/**
 * Product Description Meta Box Callback
 */
function inviro_product_description_callback($post) {
    wp_nonce_field('inviro_product_meta_nonce', 'inviro_product_meta_nonce');
    $description = get_post_meta($post->ID, '_product_description', true);
    ?>
    <p>
        <label for="product_description"><?php _e('Deskripsi Produk:', 'inviro'); ?></label><br>
        <textarea name="product_description" id="product_description" rows="5" style="width: 100%; padding: 8px;" placeholder="Mesin RO 20.000 GPD dengan kapasitas setara 2000 liter/jam..."><?php echo esc_textarea($description); ?></textarea>
    </p>
    <p class="description">
        <?php _e('Tulis deskripsi lengkap tentang produk ini.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Product Price Meta Box Callback
 */
function inviro_product_price_callback($post) {
    $price = get_post_meta($post->ID, '_product_price', true);
    $original_price = get_post_meta($post->ID, '_product_original_price', true);
    ?>
    <p>
        <label for="product_price"><?php _e('Harga Jual:', 'inviro'); ?></label><br>
        <input type="text" name="product_price" id="product_price" value="<?php echo esc_attr($price); ?>" placeholder="Rp. 5.000.000" style="width: 100%; padding: 8px;" />
    </p>
    <p>
        <label for="product_original_price"><?php _e('Harga Asli (Opsional - untuk coret harga):', 'inviro'); ?></label><br>
        <input type="text" name="product_original_price" id="product_original_price" value="<?php echo esc_attr($original_price); ?>" placeholder="Rp. 6.000.000" style="width: 100%; padding: 8px;" />
    </p>
    <p class="description">
        <?php _e('Harga asli akan ditampilkan dengan coretan jika diisi. Biarkan kosong jika tidak ada diskon.', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Product Buy URL Meta Box Callback
 */
function inviro_product_buy_url_callback($post) {
    $buy_url = get_post_meta($post->ID, '_product_buy_url', true);
    ?>
    <p>
        <label for="product_buy_url"><?php _e('URL untuk tombol beli:', 'inviro'); ?></label><br>
        <input type="url" name="product_buy_url" id="product_buy_url" value="<?php echo esc_attr($buy_url); ?>" placeholder="https://wa.me/621234567890" style="width: 100%; padding: 8px;" />
    </p>
    <p class="description">
        <?php _e('Contoh: https://wa.me/621234567890 atau https://tokopedia.com/inviro/produk', 'inviro'); ?>
    </p>
    <?php
}

/**
 * Save Product Meta
 */
function inviro_save_product_meta($post_id) {
    // Check post type
    if (get_post_type($post_id) !== 'produk') {
        return;
    }
    
    if (!isset($_POST['inviro_product_meta_nonce']) || !wp_verify_nonce($_POST['inviro_product_meta_nonce'], 'inviro_product_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['product_description'])) {
        update_post_meta($post_id, '_product_description', sanitize_textarea_field($_POST['product_description']));
    }
    
    if (isset($_POST['product_price'])) {
        update_post_meta($post_id, '_product_price', sanitize_text_field($_POST['product_price']));
    }
    
    if (isset($_POST['product_original_price'])) {
        update_post_meta($post_id, '_product_original_price', sanitize_text_field($_POST['product_original_price']));
    }
    
    if (isset($_POST['product_buy_url'])) {
        update_post_meta($post_id, '_product_buy_url', esc_url_raw($_POST['product_buy_url']));
    }
}
add_action('save_post_produk', 'inviro_save_product_meta');


