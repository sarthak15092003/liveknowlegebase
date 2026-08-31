<?php
/**
 * CMGALAXY Custom Header Layout
 */
$s_value = get_search_query() ? get_search_query() : '';
?>

<div class="collapse navbar-collapse cmgalaxy-header" id="navbarSupportedContent">
    
    <!-- Close Button for Mobile -->
    <button class="navbar-close-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close navigation">
        <span class="close-icon">&times;</span>
    </button>
    
    <!-- Main Header Row -->
    <div class="cmgalaxy-header-main d-flex align-items-center w-100">
        <!-- Logo Section -->
        <div class="cmgalaxy-logo-section d-flex align-items-center">
            <div class="cmgalaxy-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="d-block">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="Knowledge Base Logo" class="cmgalaxy-logo-img">
                </a>
            </div>
        </div>

        <!-- Search Section -->
        <div class="cmgalaxy-search-section flex-grow-1" style="margin-left: 0; margin-right: 20px;">
            <div class="d-flex align-items-center gap-3">
                <form action="<?php echo esc_url(home_url("/")) ?>" class="cmgalaxy-search-form flex-grow-1" method="get">
                    <div class="search-input-wrapper">
                        <input type="search" 
                               placeholder="<?php esc_attr_e("Search ('/' to focus)", 'docy'); ?>" 
                               name="s" 
                               value="<?php echo esc_attr($s_value) ?>"
                               class="cmgalaxy-search-input"
                               autocomplete="off">
                        <span class="cmgalaxy-search-loader" aria-hidden="true"></span>
                        <div class="cmgalaxy-search-suggestions" id="search-suggestions"></div>
                    </div>
                </form>
                
                <!-- Ask Lex Button -->
                <a href="#" class="cmgalaxy-ask-lex-btn" style="height: 40px;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/lexlogo.svg' ); ?>" alt="Lex Logo" class="cmgalaxy-ask-lex-logo me-2">
                    Ask Lex
                </a>
            </div>
        </div>

        <!-- Right Actions -->
        <div class="cmgalaxy-actions d-flex align-items-center">

            <?php if ( is_user_logged_in() ) : 
                $current_user = wp_get_current_user();
                $brand_name = get_user_meta($current_user->ID, 'brand_name', true);
                $display_title = !empty($brand_name) ? $brand_name : (!empty($current_user->first_name) ? $current_user->first_name : $current_user->display_name);
                $initial = strtoupper(substr($display_title, 0, 1));
                $user_email = $current_user->user_email;
                $plan_status = get_user_meta($current_user->ID, 'plan_status', true);
                if (empty($plan_status)) $plan_status = 'paid';
            ?>
                <!-- User Profile Dropdown -->
                <div class="cmg-user-profile-dropdown position-relative">
                    <button type="button" class="cmg-profile-trigger" id="cmgProfileTrigger" aria-expanded="false" aria-haspopup="true">
                        <span class="cmg-avatar-circle"><?php echo esc_html($initial); ?></span>
                        <span class="cmg-user-name text-truncate"><?php echo esc_html($display_title); ?></span>
                        <svg class="cmg-chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="cmg-dropdown-menu" id="cmgProfileDropdownMenu">
                        <div class="cmg-dropdown-header">
                            <div class="cmg-dropdown-user-name"><?php echo esc_html($display_title); ?></div>
                            <div class="cmg-dropdown-user-email" title="<?php echo esc_attr($user_email); ?>"><?php echo esc_html($user_email); ?></div>
                        </div>
                        <div class="cmg-dropdown-divider"></div>
                        <a href="https://platform.cmgalaxy.com" target="_blank" rel="noopener noreferrer" class="cmg-dropdown-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            Platform App
                        </a>
                        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="cmg-dropdown-item cmg-logout-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Sign Out
                        </a>
                    </div>
                </div>
            <?php else : 
                $curr_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            ?>
                <!-- Sign In Link -->
                <a href="<?php echo esc_url( add_query_arg('redirect_to', urlencode($curr_url), home_url('/signin/')) ); ?>" class="cmgalaxy-nav-link">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/signin.svg' ); ?>" alt="Sign In" class="cmgalaxy-signin-icon me-2">
                    Sign In
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Navigation Menu Row -->
    <div class="cmgalaxy-nav-menu w-100">
        <?php
        if ( has_nav_menu('main_menu') ) {
            wp_nav_menu( array (
                'menu' => 'main_menu',
                'theme_location' => 'main_menu',
                'container' => null,
                'menu_class' => "cmgalaxy-main-nav d-flex list-unstyled mb-0",
                'walker' => new Docy_Nav_Walker(),
                'depth' => 4
            ));
        } else {
            // Default menu items if no menu is set
            ?>
            <ul class="cmgalaxy-main-nav d-flex list-unstyled mb-0">
                <li class="nav-item me-4"><a href="<?php echo home_url('/'); ?>" class="nav-link">Home</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Get Started</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Docs</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Guide</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">FAQs</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">API Docs</a></li>
            </ul>
            <?php
        }

        ?>
    </div>
</div>

<button id="lex-side-trigger" class="lex-side-trigger" aria-label="Open Lex Assistant">
    <svg width="12" height="20" viewBox="0 0 12 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 2l-8 10 8 10" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<!-- Lex Drawer -->
<div id="lex-drawer" class="lex-drawer" role="dialog" aria-modal="true" aria-label="Lex Assistant">
    <div class="lex-drawer-panel">
        <div class="lex-drawer-body">
            <div class="lex-drawer-content">
                <iframe id="lex-assistant-frame" src="<?php echo esc_url( add_query_arg('ajax_url', admin_url('admin-ajax.php'), get_template_directory_uri() . '/assets/html/lex-assistant.html') ); ?>" style="width: 100%; height: 100%; border: none;" title="Lex Assistant"></iframe>
            </div>
        </div>
    </div>
    <div class="lex-drawer-overlay"></div>
</div>

<!-- Lex Logic configuration -->
<script>
    const lexLogoUrl = "<?php echo get_template_directory_uri(); ?>/assets/img/lexlogo.svg";
    const cmgalaxy_ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<div id="cmgalaxy-search-backdrop" class="cmgalaxy-search-backdrop"></div>
<!-- Lex Logic moved to assets/js/cmgalaxy-header-v2.js -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    var trigger = document.getElementById('cmgProfileTrigger');
    var menu = document.getElementById('cmgProfileDropdownMenu');

    if (trigger && menu) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = menu.classList.contains('show');
            if (isOpen) {
                menu.classList.remove('show');
                trigger.classList.remove('active');
                trigger.setAttribute('aria-expanded', 'false');
            } else {
                menu.classList.add('show');
                trigger.classList.add('active');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });

        document.addEventListener('click', function(e) {
            if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('show');
                trigger.classList.remove('active');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
</script>
