<?php
$opt = get_option( 'docy_opt' );
?>
<div class="mobile_main_menu <?php Docy_helper()->navbar_type(); ?>" id="<?php docy_sticky_navbar('id', 'mobile') ?>">
    <div class="container">
        <div class="mobile_menu_left">
            <button type="button" class="navbar-toggler mobile_menu_btn">
                <span class="menu_toggle ">
                    <span class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </span>
            </button>
            <div class="cmgalaxy-mobile-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="CMGALAXY Knowledge Base" style="height: 51px !important; width: 90% !important; max-width: none !important;">
                </a>
            </div>
        </div>
        <div class="mobile_menu_right">
            <!-- Right Hamburger for Modern Sidebar -->
            <?php if ( !is_front_page() && !is_home() ) : ?>
                <button type="button" class="navbar-toggler modern_sidebar_btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12H21M3 6H21M3 18H21" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            <?php endif; ?>
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
            if ( has_nav_menu('main_menu') ) {
                wp_nav_menu( array (
                    'menu' => 'main_menu',
                    'theme_location' => 'main_menu',
                    'container' => null,
                    'menu_class' => "navbar-nav menu ml-auto",
                    'walker' => new Docy_Mobile_Nav_Walker(),
                    'depth' => 4
                ));
            }
            ?>
        </nav>
    </div>
</div>

<!-- Right Side Menu (Modern Sidebar with Expandable Categories) -->
<div class="modern_sidebar_drawer">
    <div class="mobile_menu_header">
        <div class="mobile_logo">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="Navigate" style="height: 25px;">
        </div>
        <div class="close_modern_sidebar">
            <i class="icon_close"></i>
        </div>
    </div>
    <div class="modern_sidebar_drawer_content">
        <?php
        // Get categories
        $categories = get_categories(array(
            'orderby' => 'name',
            'order'   => 'ASC',
            'hide_empty' => false,
        ));
        

        if (!empty($categories)) :
            foreach ($categories as $category) :
                // Get posts in this category
                $cat_posts = get_posts(array(
                    'category' => $category->term_id,
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                // Check if active
                $is_active = false;
                if ( is_single() && in_category($category->term_id) ) {
                    $is_active = true;
                } elseif ( is_category($category->term_id) ) {
                    $is_active = true;
                }

                $header_class = $is_active ? 'active' : '';
                $content_class = $is_active ? 'open' : '';
                $content_style = $is_active ? 'style="display: block;"' : '';
        ?>
            <div class="mobile-category-accordion">
                <div class="mobile-category-header <?php echo esc_attr($header_class); ?>" data-category="<?php echo esc_attr($category->slug); ?>">
                    <span class="category-name"><?php echo esc_html($category->name); ?></span>
                    <span class="category-toggle">
                        <svg class="toggle-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="mobile-category-posts <?php echo esc_attr($content_class); ?>" id="cat-<?php echo esc_attr($category->slug); ?>" <?php echo $content_style; ?>>
                    <?php if (!empty($cat_posts)) : ?>
                        <?php foreach ($cat_posts as $cat_post) : 
                             $active_post_class = ( get_the_ID() == $cat_post->ID ) ? 'active-post' : ''; 
                        ?>
                            <a href="<?php echo esc_url(get_permalink($cat_post->ID)); ?>" class="mobile-post-item <?php echo esc_attr($active_post_class); ?>">
                                <?php echo esc_html($cat_post->post_title); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <span class="no-posts">No articles yet</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</div>

<!-- Overlay -->
<div class="click_capture"></div>



<script>
(function($) {
    'use strict';
    $(document).ready(function() {
        // Toggle Modern Sidebar (Right)
        $(document).on('click', '.modern_sidebar_btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close left menu first
            $('.side_menu.dark_menu').removeClass('menu-opened');
            
            $('.modern_sidebar_drawer').addClass('open');
            $('.click_capture').fadeIn(300);
            $('body').addClass('menu-is-opened');
        });
        
        // Close Modern Sidebar
        $(document).on('click', '.close_modern_sidebar', function(e) {
            e.preventDefault();
            $('.modern_sidebar_drawer').removeClass('open');
            $('.click_capture').fadeOut(300);
            $('body').removeClass('menu-is-opened');
        });
        
        // Toggle Category Accordion
        $(document).on('click', '.mobile-category-header', function(e) {
            e.preventDefault();
            var $this = $(this);
            var catSlug = $this.data('category');
            var $posts = $('#cat-' + catSlug);
            var isOpen = $this.hasClass('active');
            
            // Close all other accordions first
            $('.mobile-category-header').not($this).removeClass('active');
            $('.mobile-category-posts').not($posts).slideUp(200);
            
            // Toggle current based on state
            if (isOpen) {
                $this.removeClass('active');
                $posts.slideUp(200);
            } else {
                $this.addClass('active');
                $posts.slideDown(200);
            }
        });
        
        // Close on overlay click
        $(document).on('click', '.click_capture', function() {
            $('.modern_sidebar_drawer').removeClass('open');
        });
    });
})(jQuery);
</script>