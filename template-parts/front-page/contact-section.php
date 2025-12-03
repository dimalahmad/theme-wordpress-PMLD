<?php
/**
 * Template part for displaying contact section
 *
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$map_url = get_theme_mod('inviro_contact_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.5775443510283!2d110.36378417593333!3d-7.8344554921866685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a57b27e98278d%3A0x894c7b28ef19130d!2sINVIRO%20Jogja!5e0!3m2!1sid!2sid!4v1764681840160!5m2!1sid!2sid');
?>

<section id="contact" class="contact-section" itemscope itemtype="https://schema.org/ContactPage">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" itemprop="name"><?php echo esc_html(get_theme_mod('inviro_contact_title', 'Hubungi Kami untuk Layanan Terbaik')); ?></h2>
            <p class="section-subtitle" itemprop="description"><?php echo esc_html(get_theme_mod('inviro_contact_description', 'Untuk informasi lebih lanjut mengenai produk dan layanan kami, jangan ragu untuk menghubungi kami')); ?></p>
        </div>
        
        <div class="contact-content">
            <div class="contact-map">
                <?php if ($map_url) : ?>
                    <?php
                    // Check if it's a full iframe code or just URL
                    if (strpos($map_url, '<iframe') !== false) {
                        // If it's full iframe code, extract src or use wp_kses
                        preg_match('/src=["\']([^"\']+)["\']/', $map_url, $matches);
                        if (!empty($matches[1])) {
                            $map_url = $matches[1];
                        } else {
                            // Allow iframe with wp_kses
                            $allowed_html = array(
                                'iframe' => array(
                                    'src' => array(),
                                    'width' => array(),
                                    'height' => array(),
                                    'style' => array(),
                                    'allowfullscreen' => array(),
                                    'loading' => array(),
                                    'referrerpolicy' => array(),
                                    'frameborder' => array(),
                                ),
                            );
                            echo wp_kses($map_url, $allowed_html);
                            $map_url = ''; // Prevent double rendering
                        }
                    }
                    
                    if ($map_url) :
                        $map_url = esc_url_raw($map_url);
                        ?>
                        <iframe 
                            src="<?php echo esc_attr($map_url); ?>" 
                            width="100%" 
                            height="100%" 
                            style="border:0; position: absolute; top: 0; left: 0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            frameborder="0">
                        </iframe>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="map-placeholder" itemscope itemtype="https://schema.org/LocalBusiness">
                        <div class="map-placeholder-content">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <p><?php esc_html_e('Peta akan ditampilkan di sini', 'inviro'); ?></p>
                            <p class="small"><?php esc_html_e('Tambahkan URL embed Google Maps di Customizer', 'inviro'); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="contact-features">
                <?php for ($i = 1; $i <= 3; $i++) : 
                    $icon = get_theme_mod('inviro_contact_feature_' . $i . '_icon', $i == 1 ? 'phone' : ($i == 2 ? 'tag' : 'map-pin'));
                    $title = get_theme_mod('inviro_contact_feature_' . $i . '_title', $i == 1 ? 'Customer Support' : ($i == 2 ? 'Harga & Kualitas Terjamin' : 'Banyak Lokasi'));
                    $description = get_theme_mod('inviro_contact_feature_' . $i . '_description', $i == 1 ? 'Tim support kami siap membantu Anda 24/7' : ($i == 2 ? 'Harga terbaik dengan kualitas premium' : 'Hadir di berbagai kota di Indonesia'));
                    $color = get_theme_mod('inviro_contact_feature_' . $i . '_color', $i == 1 ? '#28a745' : ($i == 2 ? '#ff8c00' : '#dc3545'));
                    ?>
                    <div class="feature-item">
                        <div class="feature-icon" style="background-color: <?php echo esc_attr($color); ?>;">
                            <?php echo inviro_get_feature_icon($icon); ?>
                        </div>
                        <div class="feature-content">
                            <h3><?php echo esc_html($title); ?></h3>
                            <p><?php echo esc_html($description); ?></p>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        
        <div class="contact-form-wrapper">
            <h3 class="form-title"><?php esc_html_e('Silakan tinggalkan saran atau tanggapan Anda!', 'inviro'); ?></h3>
            <form id="inviro-contact-form" class="contact-form">
                <?php wp_nonce_field('submit_contact', 'contact_nonce'); ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact-name"><?php esc_html_e('Nama', 'inviro'); ?> <span class="required">*</span></label>
                        <input type="text" id="contact-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-email"><?php esc_html_e('Email', 'inviro'); ?> <span class="required">*</span></label>
                        <input type="email" id="contact-email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact-subject"><?php esc_html_e('Subjek', 'inviro'); ?></label>
                        <input type="text" id="contact-subject" name="subject">
                    </div>
                    <div class="form-group">
                        <label for="contact-phone"><?php esc_html_e('Nomor Ponsel', 'inviro'); ?></label>
                        <input type="tel" id="contact-phone" name="phone">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="contact-message"><?php esc_html_e('Pesan', 'inviro'); ?> <span class="required">*</span></label>
                    <textarea id="contact-message" name="message" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary"><?php esc_html_e('Kirim Pesan', 'inviro'); ?></button>
                <div class="form-message"></div>
            </form>
        </div>
    </div>
</section>

