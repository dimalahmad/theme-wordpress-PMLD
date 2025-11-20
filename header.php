<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (is_singular() && has_excerpt()) : ?>
        <meta name="description" content="<?php echo esc_attr(wp_strip_all_tags(get_the_excerpt())); ?>">
    <?php else : ?>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php endif; ?>
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header" role="banner">
    <div class="header-container">
        <div class="header-wrapper">
            <div class="site-branding">
                <?php
                // Template WordPress Standar: Gunakan Custom Logo, fallback ke text
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    // Text fallback jika tidak ada Custom Logo
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home" aria-label="<?php bloginfo('name'); ?>">
                        <span class="site-title"><?php bloginfo('name'); ?><sup>™</sup></span>
                    </a>
                    <?php
                }
                ?>
            </div>
            
            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Menu Utama', 'inviro'); ?>">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle menu', 'inviro'); ?>">
                    <span class="menu-toggle-line"></span>
                    <span class="menu-toggle-line"></span>
                    <span class="menu-toggle-line"></span>
                </button>
                <?php
                // Cek apakah menggunakan custom navbar menu
                $use_custom_navbar = get_theme_mod('inviro_use_custom_navbar', false);
                
                if ($use_custom_navbar) {
                    // Gunakan custom menu dari Customizer
                    inviro_custom_navbar_menu();
                } else {
                    // Gunakan WordPress Menu atau fallback
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'nav-menu',
                    'fallback_cb'    => 'inviro_default_menu',
                    'link_before'    => '',
                    'link_after'     => '',
                        'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
                ));
                }
                ?>
            </nav>
            
            <div class="header-actions">
                <?php
                $instagram_url = get_theme_mod('inviro_instagram', 'https://instagram.com/inviro');
                if ($instagram_url) :
                ?>
                <a href="<?php echo esc_url($instagram_url); ?>" class="instagram-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Instagram', 'inviro'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                    <span class="sr-only"><?php esc_html_e('Instagram', 'inviro'); ?></span>
                </a>
                <?php endif; ?>
                <button class="search-toggle" aria-label="<?php esc_attr_e('Search', 'inviro'); ?>" aria-expanded="false">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <span class="sr-only"><?php esc_html_e('Search', 'inviro'); ?></span>
                </button>
            </div>
        </div>
    </div>
    
    <div class="search-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Search', 'inviro'); ?>">
        <div class="search-container">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <label for="search-field" class="sr-only"><?php esc_html_e('Search', 'inviro'); ?></label>
                <input 
                    type="search" 
                    id="search-field"
                    class="search-field" 
                    placeholder="<?php esc_attr_e('Cari...', 'inviro'); ?>" 
                    value="<?php echo get_search_query(); ?>" 
                    name="s"
                    autocomplete="off"
                />
                <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Submit search', 'inviro'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <span class="sr-only"><?php esc_html_e('Submit', 'inviro'); ?></span>
                </button>
            </form>
            <button class="search-close" aria-label="<?php esc_attr_e('Close search', 'inviro'); ?>">×</button>
        </div>
    </div>
</header>

