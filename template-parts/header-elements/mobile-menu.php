<?php
$opt = get_option( 'docy_opt' );
?>
<div class="mobile_main_menu <?php Docy_helper()->navbar_type(); ?>" id="<?php docy_sticky_navbar('id', 'mobile') ?>">
    <div class="container">
        <div class="mobile_menu_left">
            <div class="cmgalaxy-mobile-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
                    <img src="https://docs.cmgalaxy.com/wp-content/uploads/2026/06/cropped-Group-1000004539-300x300-1.png" alt="CMGalaxy Logo" style="height: 32px !important; width: 32px !important; object-fit: contain; flex-shrink: 0;">
                    <span class="cmgalaxy-mobile-logo-text" style="font-size: 16px; font-weight: 700; color: #111827; letter-spacing: -0.2px; white-space: nowrap;">Knowledge Base</span>
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
        <a href="<?php echo esc_url(home_url('/')); ?>" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
            <img src="https://docs.cmgalaxy.com/wp-content/uploads/2026/06/cropped-Group-1000004539-300x300-1.png" alt="CMGalaxy Logo" style="height: 28px !important; width: 28px !important; object-fit: contain; flex-shrink: 0;">
            <span class="cmgalaxy-mobile-logo-text" style="font-size: 15px; font-weight: 700; color: #111827; letter-spacing: -0.2px; white-space: nowrap;">Knowledge Base</span>
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
(function() {
    'use strict';
    try {
        localStorage.removeItem('docy_side_menu_open');
        sessionStorage.removeItem('docy_side_menu_open');
    } catch(e) {}

    function closeSideMenu() {
        var sideMenus = document.querySelectorAll('.side_menu');
        sideMenus.forEach(function(sm) {
            sm.classList.remove('menu-opened');
        });
        document.body.classList.remove('menu-is-opened');
        document.body.classList.add('menu-is-closed');
        try { localStorage.removeItem('docy_side_menu_open'); } catch(e) {}
    }

    document.addEventListener('DOMContentLoaded', function() {
        closeSideMenu();

        // When any navigation link inside side_menu is clicked, immediately close the drawer
        document.addEventListener('click', function(e) {
            var link = e.target.closest('.side_menu a');
            if (link && !link.closest('.expand-icon') && !link.closest('.expand-icon-subcat')) {
                var href = link.getAttribute('href');
                if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                    closeSideMenu();
                }
            }
        });
    });

    window.addEventListener('pageshow', function() {
        closeSideMenu();
    });
})();
</script>