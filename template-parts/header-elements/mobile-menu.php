<?php
$opt = get_option( 'docy_opt' );
?>
<div class="mobile_main_menu <?php Docy_helper()->navbar_type(); ?>" id="<?php docy_sticky_navbar('id', 'mobile') ?>">
    <div class="container">
        <div class="mobile_menu_left">
            <div class="cmgalaxy-mobile-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="https://docs.cmgalaxy.com/wp-content/uploads/2026/08/mobile-logo.png" alt="CMGALAXY Logo" style="height: 38px !important; width: auto !important; max-width: none !important; object-fit: contain;">
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
    <div class="mobile_menu_header" style="position: sticky !important; top: 0 !important; background: #ffffff !important; z-index: 1000 !important; display: flex !important; justify-content: space-between !important; align-items: center !important; padding: 14px 20px !important; border-bottom: 1px solid #e5e7eb !important; margin: 0 !important;">
        <a href="<?php echo esc_url(home_url('/')); ?>" style="display: flex; align-items: center; text-decoration: none;">
            <img src="https://docs.cmgalaxy.com/wp-content/uploads/2026/08/mobile-logo.png" alt="CMGalaxy Logo" style="height: 30px !important; width: auto !important; object-fit: contain;">
        </a>
        <div class="close_nav" style="cursor: pointer; display: flex; align-items: center; justify-content: center;">
            <i class="icon_close" style="font-size: 22px; color: #374151;"></i>
        </div>
    </div>

    <div class="mobile_nav_wrapper" style="padding-top: 0 !important; margin-top: 0 !important;">
        <nav class="mobile_nav_bottom" style="padding-top: 0 !important; margin-top: 0 !important;">
            <?php
            // Main menu removed as per request
            ?>
            
            <div class="mobile-sidebar-container" style="margin-top: 0px !important; padding-top: 5px !important;">
                <?php get_template_part('template-parts/sidebar-modern'); ?>
            </div>
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