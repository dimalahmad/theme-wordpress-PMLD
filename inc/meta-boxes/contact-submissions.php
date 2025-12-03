<?php
/**
 * Contact Submissions Meta Boxes
 * 
 * @package INVIRO
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for contact submissions
 */
function inviro_add_contact_submission_meta_boxes() {
    add_meta_box(
        'contact_submission_info',
        'Informasi Kontak',
        'inviro_contact_submission_info_callback',
        'contact_submission',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'inviro_add_contact_submission_meta_boxes');

/**
 * Contact submission info callback
 */
function inviro_contact_submission_info_callback($post) {
    $name = get_post_meta($post->ID, '_contact_name', true);
    $email = get_post_meta($post->ID, '_contact_email', true);
    $phone = get_post_meta($post->ID, '_contact_phone', true);
    $subject = get_post_meta($post->ID, '_contact_subject', true);
    ?>
    <div style="padding: 15px 0;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold; width: 150px;">Nama:</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo esc_html($name); ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold;">Email:</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                </td>
            </tr>
            <?php if (!empty($phone)) : ?>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold;">Telepon:</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                </td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($subject)) : ?>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd; font-weight: bold;">Subjek:</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo esc_html($subject); ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    <?php
}

