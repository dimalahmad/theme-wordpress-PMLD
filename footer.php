<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="footer-top">
        <div class="footer-wrapper">
            <!-- Left Section: Orange -->
            <div class="footer-left" style="background: linear-gradient(135deg, <?php echo esc_attr(get_theme_mod('inviro_footer_left_bg', '#FF7A00')); ?> 0%, <?php echo esc_attr(get_theme_mod('inviro_footer_left_bg_end', '#FF8C42')); ?> 100%);">
                <div class="footer-brand">
                    <?php
                    // Use custom logo from WordPress (same as header)
                    if (has_custom_logo()) {
                        // Get custom logo ID and URL
                        $custom_logo_id = get_theme_mod('custom_logo');
                        if ($custom_logo_id) {
                            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                            $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                            if (empty($logo_alt)) {
                                $logo_alt = get_bloginfo('name');
                            }
                            ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="custom-logo" />
                            </a>
                            <?php
                        } else {
                            the_custom_logo();
                        }
                    } else {
                        // Fallback: text logo (same structure as header)
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link footer-logo" rel="home" aria-label="<?php bloginfo('name'); ?>">
                            <span class="footer-logo-text"><?php bloginfo('name'); ?><sup>™</sup></span>
                        </a>
                        <?php
                    }
                    ?>
                    <p class="footer-description">
                        <?php echo esc_html(get_theme_mod('inviro_footer_description', 'INVIRO menghadirkan layanan komprehensif dalam bidang pengolahan air. Didukung dengan tenaga yang professional, pengalaman dan jam terbang yang tinggi dalam bidang pengolahan air.')); ?>
                    </p>
                    <div class="footer-social" role="list">
                        <?php 
                        $fb_url = get_theme_mod('inviro_footer_facebook', '#');
                        $ig_url = get_theme_mod('inviro_footer_instagram', '#');
                        $tw_url = get_theme_mod('inviro_footer_twitter', '#');
                        $yt_url = get_theme_mod('inviro_footer_youtube', '#');
                        ?>
                        <?php if ($fb_url && $fb_url !== '#') : ?>
                        <a href="<?php echo esc_url($fb_url); ?>" class="footer-social-link" aria-label="<?php esc_attr_e('Facebook', 'inviro'); ?>" target="_blank" rel="noopener noreferrer" role="listitem">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="sr-only"><?php esc_html_e('Facebook', 'inviro'); ?></span>
                        </a>
                        <?php endif; ?>
                        <?php if ($tw_url && $tw_url !== '#') : ?>
                        <a href="<?php echo esc_url($tw_url); ?>" class="footer-social-link" aria-label="<?php esc_attr_e('Twitter', 'inviro'); ?>" target="_blank" rel="noopener noreferrer" role="listitem">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            <span class="sr-only"><?php esc_html_e('Twitter', 'inviro'); ?></span>
                        </a>
                        <?php endif; ?>
                        <?php if ($ig_url && $ig_url !== '#') : ?>
                        <a href="<?php echo esc_url($ig_url); ?>" class="footer-social-link" aria-label="<?php esc_attr_e('Instagram', 'inviro'); ?>" target="_blank" rel="noopener noreferrer" role="listitem">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                            <span class="sr-only"><?php esc_html_e('Instagram', 'inviro'); ?></span>
                        </a>
                        <?php endif; ?>
                        <?php if ($yt_url && $yt_url !== '#') : ?>
                        <a href="<?php echo esc_url($yt_url); ?>" class="footer-social-link" aria-label="<?php esc_attr_e('YouTube', 'inviro'); ?>" target="_blank" rel="noopener noreferrer" role="listitem">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            <span class="sr-only"><?php esc_html_e('YouTube', 'inviro'); ?></span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Right Section: Light Blue -->
            <div class="footer-right" style="background: linear-gradient(135deg, <?php echo esc_attr(get_theme_mod('inviro_footer_right_bg', '#E3F2FD')); ?> 0%, <?php echo esc_attr(get_theme_mod('inviro_footer_right_bg_end', '#BBDEFB')); ?> 100%);">
                <div class="footer-columns">
                    <div class="footer-column">
                        <h3 class="footer-title"><?php echo esc_html(get_theme_mod('inviro_footer_about_title', 'About')); ?></h3>
                        <ul class="footer-menu">
                            <?php
                            $about_link1_text = get_theme_mod('inviro_footer_about_link1_text', 'How it works');
                            $about_link1_url = get_theme_mod('inviro_footer_about_link1_url', home_url('/profil'));
                            if ($about_link1_text && $about_link1_url) :
                            ?>
                            <li><a href="<?php echo esc_url($about_link1_url); ?>"><?php echo esc_html($about_link1_text); ?></a></li>
                            <?php endif; ?>
                            
                            <?php
                            $about_link2_text = get_theme_mod('inviro_footer_about_link2_text', 'Featured');
                            $about_link2_url = get_theme_mod('inviro_footer_about_link2_url', home_url('/produk'));
                            if ($about_link2_text && $about_link2_url) :
                            ?>
                            <li><a href="<?php echo esc_url($about_link2_url); ?>"><?php echo esc_html($about_link2_text); ?></a></li>
                            <?php endif; ?>
                            
                            <?php
                            $about_link3_text = get_theme_mod('inviro_footer_about_link3_text', 'Partnership');
                            $about_link3_url = get_theme_mod('inviro_footer_about_link3_url', home_url('/pelanggan'));
                            if ($about_link3_text && $about_link3_url) :
                            ?>
                            <li><a href="<?php echo esc_url($about_link3_url); ?>"><?php echo esc_html($about_link3_text); ?></a></li>
                            <?php endif; ?>
                            
                            <?php
                            $about_link4_text = get_theme_mod('inviro_footer_about_link4_text', 'Business Relation');
                            $about_link4_url = get_theme_mod('inviro_footer_about_link4_url', home_url('/pelanggan'));
                            if ($about_link4_text && $about_link4_url) :
                            ?>
                            <li><a href="<?php echo esc_url($about_link4_url); ?>"><?php echo esc_html($about_link4_text); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h3 class="footer-title"><?php echo esc_html(get_theme_mod('inviro_footer_community_title', 'Community')); ?></h3>
                        <ul class="footer-menu">
                            <?php
                            $community_link1_text = get_theme_mod('inviro_footer_community_link1_text', 'Events');
                            $community_link1_url = get_theme_mod('inviro_footer_community_link1_url', home_url('/artikel'));
                            if ($community_link1_text && $community_link1_url) :
                            ?>
                            <li><a href="<?php echo esc_url($community_link1_url); ?>"><?php echo esc_html($community_link1_text); ?></a></li>
                            <?php endif; ?>
                            
                            <?php
                            $community_link2_text = get_theme_mod('inviro_footer_community_link2_text', 'Blog');
                            $community_link2_url = get_theme_mod('inviro_footer_community_link2_url', home_url('/artikel'));
                            if ($community_link2_text && $community_link2_url) :
                            ?>
                            <li><a href="<?php echo esc_url($community_link2_url); ?>"><?php echo esc_html($community_link2_text); ?></a></li>
                            <?php endif; ?>
                            
                            <?php
                            $community_link3_text = get_theme_mod('inviro_footer_community_link3_text', 'Podcast');
                            $community_link3_url = get_theme_mod('inviro_footer_community_link3_url', home_url('/artikel'));
                            if ($community_link3_text && $community_link3_url) :
                            ?>
                            <li><a href="<?php echo esc_url($community_link3_url); ?>"><?php echo esc_html($community_link3_text); ?></a></li>
                            <?php endif; ?>
                            
                            <?php
                            $community_link4_text = get_theme_mod('inviro_footer_community_link4_text', 'Invite a friend');
                            $community_link4_url = get_theme_mod('inviro_footer_community_link4_url', '#');
                            if ($community_link4_text && $community_link4_url) :
                            ?>
                            <li><a href="<?php echo esc_url($community_link4_url); ?>"><?php echo esc_html($community_link4_text); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h3 class="footer-title"><?php echo esc_html(get_theme_mod('inviro_footer_socials_title', 'Socials')); ?></h3>
                        <ul class="footer-menu">
                            <?php
                            $socials_link1_text = get_theme_mod('inviro_footer_socials_link1_text', 'Discord');
                            $socials_link1_url = get_theme_mod('inviro_footer_socials_link1_url', '#');
                            if ($socials_link1_text && $socials_link1_url) :
                            ?>
                            <li><a href="<?php echo esc_url($socials_link1_url); ?>"><?php echo esc_html($socials_link1_text); ?></a></li>
                            <?php endif; ?>
                            
                            <?php if ($ig_url && $ig_url !== '#') : ?>
                            <li><a href="<?php echo esc_url($ig_url); ?>"><?php esc_html_e('Instagram', 'inviro'); ?></a></li>
                            <?php endif; ?>
                            
                            <?php if ($tw_url && $tw_url !== '#') : ?>
                            <li><a href="<?php echo esc_url($tw_url); ?>"><?php esc_html_e('Twitter', 'inviro'); ?></a></li>
                            <?php endif; ?>
                            
                            <?php if ($fb_url && $fb_url !== '#') : ?>
                            <li><a href="<?php echo esc_url($fb_url); ?>"><?php esc_html_e('Facebook', 'inviro'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom: White divider line -->
    <div class="footer-divider"></div>
    
    <div class="footer-bottom">
        <div class="footer-wrapper">
            <p class="copyright">
                <?php
                $copyright_text = get_theme_mod('inviro_footer_copyright', '');
                if (!empty($copyright_text)) {
                    echo esc_html($copyright_text);
                } else {
                    echo '&copy;' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved', 'inviro');
                }
                ?>
            </p>
            <nav class="footer-links" aria-label="<?php esc_attr_e('Footer links', 'inviro'); ?>">
                <?php
                $privacy_url = get_theme_mod('inviro_footer_privacy_url', home_url('/privacy-policy'));
                if ($privacy_url) :
                ?>
                <a href="<?php echo esc_url($privacy_url); ?>"><?php esc_html_e('Privacy & Policy', 'inviro'); ?></a>
                <?php endif; ?>
                
                <?php
                $terms_url = get_theme_mod('inviro_footer_terms_url', home_url('/terms-condition'));
                if ($terms_url) :
                ?>
                <a href="<?php echo esc_url($terms_url); ?>"><?php esc_html_e('Terms & Condition', 'inviro'); ?></a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</footer>

<?php
function inviro_footer_menu_fallback() {
    echo '<ul class="footer-menu">';
    echo '<li><a href="' . esc_url(home_url('/profil')) . '">Profil</a></li>';
    echo '<li><a href="' . esc_url(home_url('/paket-usaha')) . '">Paket Usaha</a></li>';
    echo '<li><a href="' . esc_url(home_url('/pelanggan')) . '">Pelanggan</a></li>';
    echo '</ul>';
}
?>

<div class="whatsapp-float">
    <?php $wa_number = get_theme_mod('inviro_whatsapp', '621234567890'); ?>
    <a href="https://wa.me/<?php echo esc_attr($wa_number); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>
</div>

<?php wp_footer(); ?>
</body>
</html>

