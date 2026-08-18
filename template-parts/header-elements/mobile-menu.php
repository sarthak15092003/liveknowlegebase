<?php
$opt = get_option( 'docy_opt' );
?>
<div class="mobile_main_menu <?php Docy_helper()->navbar_type(); ?>" id="<?php docy_sticky_navbar('id', 'mobile') ?>">
    <div class="container">
        <div class="mobile_menu_left">
            <div class="cmgalaxy-mobile-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="CMGALAXY Knowledge Base" style="height: 51px !important; width: 90% !important; max-width: none !important;">
                </a>
            </div>
        </div>
        <div class="mobile_menu_right">
            <button type="button" class="navbar-toggler mobile_menu_btn">
                <span class="menu_toggle ">
                    <span class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </span>
            </button>
        </div>
    </div>
</div>

<!-- Left Side Menu (Main Navigation) -->
<div class="side_menu dark_menu">
    <div class="mobile_menu_header">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="CMGalaxy Logo" style="height: 25px;">
        <div class="close_nav">
            <i class="icon_close"></i>
        </div>
    </div>

    <div class="mobile_nav_wrapper">
        <nav class="mobile_nav_bottom">
            <?php
            // Main menu removed as per request
            ?>
            
            <?php if (!is_front_page() && !is_home()): ?>
            <div class="mobile-sidebar-container" style="margin-top: 20px;">
                <?php get_template_part('template-parts/sidebar-modern'); ?>
            </div>
            <?php endif; ?>
        </nav>
    </div>
</div>



<!-- Overlay -->
<div class="click_capture"></div>



<script>
(function($) {
    'use strict';
    $(document).ready(function() {
        // No extra custom JS needed for mobile sidebar as sidebar-modern.php handles itself now.
    });
})(jQuery);
</script>