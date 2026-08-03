<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package docy
 */
get_header();

// Fixed 3-7-2 layout for single posts
$post_column        = '7'; // Main content always 7 columns
$p0                 = '';

if ( docy_toc('post') == '1' ) {
    wp_enqueue_script('anchor');
    // Custom H2 TOC is built inline below — bootstrap-toc is NOT needed here
}
$has_toc = docy_toc('post') == '1';
$blog_column = $has_toc ? '7' : '9'; // 3+7+2=12 (with TOC), 3+9=12 (no TOC)

$banner_type_page = docy_meta('banner_type','default');
$banner_type_page = docy_meta('banner_type','default');

$banner_type_opt = docy_opt('banner_type', 'colorful');

if ( $banner_type_page != 'default' ) {
    // Fix old data (that was saved as 'toc'. Now it's 'colorful')
    $banner_type = $banner_type_page == 'toc' ? 'colorful' : $banner_type_page;
} else {
    $banner_type = $banner_type_opt;
}

// Banner
get_template_part( 'template-parts/single-post/banner', $banner_type );

// Add breadcrumb for single posts - DISABLED (breadcrumb now inside content area)
// $is_breadcrumb = docy_meta_apply('is_breadcrumb');
// if ( $is_breadcrumb == '1' ) {
//     get_template_part( 'template-parts/header-elements/search-banner/breadcrumb' );
// }
?>
<section class="blog_area tip_doc_area" id="toc_stick">

    <div class="container">
        <div class="row align-items-start">
            <!-- Left Modern Sidebar (20%) -->
            <div class="col-lg-3 category-left-sidebar-col" style="background: #ffffff !important;">

                <?php get_template_part( 'template-parts/sidebar-modern' ); ?>
            </div>
            <?php
            if ( docy_toc('post') == '1' ) :
                // Will render TOC on the right after the content
            endif; ?>
            <?php // TOC will render after the content column ?>


            <?php 
            $main_col_extra_class = $has_toc ? 'has-toc-sidebar' : '';
            ?>
            <div class="col-lg-<?php echo esc_attr( $blog_column ) ?> blog_single_info category-main-col <?php echo esc_attr($main_col_extra_class); ?> pe-lg-3" style="">
                <div class="main-post <?php echo $has_toc ? 'anchor-enabled' : ''; ?>">
                    <div class="blog_single_item editor-content">
                        <?php
                        // Add breadcrumb inside blog single item
                        if ( function_exists('docy_post_breadcrumbs') ) {
                            echo '';
                            echo '<nav aria-label="breadcrumb" class="mb-4">';
                            docy_post_breadcrumbs();
                            echo '</nav>';
                        }
                        ?>
                        <?php
                        while ( have_posts() ) : the_post();
                            $user_desc = get_the_author_meta( 'description' );
                            ?>
                            <h1 class="post-title mb-3" style="font-size: 36px; font-weight: 700; color: #111827; line-height: 1.2; margin-top: 20px;">
                                <?php the_title(); ?>
                            </h1>
                            <div class="post-author-meta-box d-flex align-items-center mb-4 mt-2" style="gap: 15px; border-radius: 50px; padding: 5px;">
                                <div class="author-avatar">
                                    <img src="https://docs.cmgalaxy.com/wp-content/uploads/2026/06/cropped-Group-1000004539-300x300-1.png" alt="Author Avatar" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover; border: none !important;">
                                </div>
                                <div class="author-info" style="line-height: 1.5; color: #6b7280; font-family: 'Instrument Sans', sans-serif;">
                                    <div style="font-size: 15px;">Written by <span style="color: #4b5563;"><?php echo get_the_author(); ?></span></div>
                                </div>
                            </div>
                            <?php
                            the_post_thumbnail('full', array( 'class' => 'mb-4 featured-image' ) );
                            the_content();
                            wp_link_pages( array(
                                'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'docy' ) . '</span>',
                                'after'       => '</div>',
                                'link_before' => '<span>',
                                'link_after'  => '</span>',
                                'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'docy' ) . ' </span>%',
                                'separator'   => '<span class="screen-reader-text">, </span>',
                            ));
                        endwhile;
                        ?>
                    </div>
                    <?php
                    if ( has_tag() && docy_opt('is_post_tag', '1') == '1' ) : ?>
                        <div class="single_post_tags post-tags">
                            <?php the_tags( esc_html__( 'Tags : ', 'docy' ), ' ' ); ?>
                        </div>
                    <?php endif; ?>

                    <!-- CMGalaxy Engagement Block -->
                    <div class="cmgalaxy-engagement-block mt-5 p-4" style=" border-radius: 12px; background: #ffffff;">
                        <p class="lead mb-4" style="color: #484a61 !important; font-size: 1.125rem; line-height: 1.75;">
                            Thanks for being here with us! We are beyond excited to see how you'll use CMGalaxy to drive growth for your business.
                        </p>

                        <div class="related-articles-section" style="margin-top: 3.0rem; margin-bottom: 1.5rem;">
                            <h5 class="fw-semibold mb-3" style="color: #484a61 !important; font-size: 1.125rem;">Related Articles</h5>
                            <ul class="list-unstyled mb-0" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem !important;">
                                <?php
                                $categories = get_the_category();
                                if ($categories) {
                                    $category_ids = array();
                                    foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
                                    
                                    $args = array(
                                        'category__in' => $category_ids,
                                        'post__not_in' => array(get_the_ID()),
                                        'posts_per_page' => 4,
                                        'ignore_sticky_posts' => 1
                                    );
                                    
                                    $related_query = new WP_Query($args);
                                    
                                    if ($related_query->have_posts()) {
                                        while ($related_query->have_posts()) {
                                            $related_query->the_post();
                                            ?>
                                            <li class="mb-3">
                                                <a href="<?php the_permalink(); ?>" class="d-flex justify-content-between align-items-center related-article-link" style="color: #484a61; text-decoration: none; font-size: 1rem;">
                                                    <span><?php the_title(); ?></span>
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3b82f6;">
                                                        <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        wp_reset_postdata();
                                    } else {
                                        echo '<li class="text-muted" style="font-size: 0.875rem;">No related articles found in this category.</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="feedback-section"  style="margin-top:20px">
                            <p class="fw-semibold mb-2" style="color: #484a61 !important;">Was this helpful?</p>
                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-primary">Yes</button>
                                <button type="button" class="btn btn-outline-secondary">No</button>
                            </div>
                            <p class="small text-muted mb-0">
                                This form is used for documentation feedback only. Learn how to get help with <a href="#" style="color: #3b82f6;">CMGalaxy</a>.
                            </p>
                        </div>

                        <!-- Post Navigation Cards -->
                        <?php
                        $prev_post = get_next_post();
                        $next_post = get_previous_post();
                        if ( $prev_post || $next_post ) : ?>
                            <div class="post-navigation-cards mt-4 d-flex gap-3">
                                <?php if ( $prev_post ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="nav-card prev-card p-4" style="border: 1px solid #e5e7eb; border-radius: 16px; text-decoration: none; background: #ffffff; transition: all 0.2s ease; flex: 1 1 45%; max-width: 48%;">
                                        <div class="nav-card-content">
                                            <h5 class="mb-3" style="color: #1f2937; font-weight: 500!important; font-size: 1rem;"><?php echo esc_html( get_the_title( $prev_post ) ); ?></h5>
                                            <div class="nav-direction d-flex align-items-center" style="color: #3b82f6; font-size: 0.875rem; font-weight: 500;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                                    <path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                Previous
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $next_post ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="nav-card next-card p-4" style="border: 1px solid #e5e7eb; border-radius: 16px; text-decoration: none; background: #ffffff; transition: all 0.2s ease; flex: 1 1 45%; max-width: 48%; margin-left: auto;">
                                        <div class="nav-card-content text-end">
                                            <h5 class="mb-3" style="color: #1f2937; font-weight: 500 !important; font-size: 1rem;"><?php echo esc_html( get_the_title( $next_post ) ); ?></h5>
                                            <div class="nav-direction d-flex align-items-center justify-content-end" style="color: #3b82f6; font-size: 0.875rem; font-weight: 500;">
                                                Next
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ms-2">
                                                    <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>


                    </div>
                </div>
            </div>

            <?php
            // Render TOC on the right side after the content column
            if ( docy_toc('post') == '1' ) : ?>
                <div class="col-lg-2 doc-sidebar pe-lg-0 ps-lg-2">
                    
                    <aside class="left_sidebarlist">
                        <h6 class="toc-title mb-3"><?php esc_html_e('On this Page', 'docy'); ?></h6>
                        <nav class="list-unstyled nav-sidebar doc-nav" id="docy-toc"> </nav>
                        <!-- <div class="toc-sidebar-image mt-4">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/sidebarimg.png' ); ?>" alt="Sidebar CTA" class="img-fluid rounded-3" style="width: 100%;">
                        </div> -->
                        <?php /* Sidebar CTA Card - commented out
                        <div class="sidebar-cta-card" style="display: flex; align-items: center; gap: 16px; margin-top: 20px; padding: 1px 2px; border: 2px solid #3B82F6; border-radius: 18px; background: #f4f8ff; width: 100%; box-sizing: border-box;">
                            <div class="sidebar-cta-icon" aria-hidden="true">
                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="4" y="8" width="36" height="32" rx="8" stroke="#3B82F6" stroke-width="2" />
                                    <path d="M12 18H16" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M12 23H20" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M12 28H20" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M28 18H32" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M28 23H34" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M28 28H34" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div class="sidebar-cta-text" style="display: flex; flex-direction: column; gap: 4px;">
                                <p class="sidebar-cta-eyebrow" style="margin: 0;">New to CMGalaxy?</p>
                                <p class="sidebar-cta-body" style="margin: 0;">Check our <a href="#" class="sidebar-cta-link">Get Started</a> guides.</p>
                            </div>
                        </div>
                        */ ?>
                    </aside>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const toc = document.getElementById('docy-toc');
                        if (!toc) return;
                        
                        // Observe class changes on nav links to detect when they become active
                        const observer = new MutationObserver(function(mutations) {
                            mutations.forEach(function(mutation) {
                                if (mutation.attributeName === 'class' && mutation.target.classList.contains('active')) {
                                    const activeEl = mutation.target;
                                    const tocRect = toc.getBoundingClientRect();
                                    const elRect = activeEl.getBoundingClientRect();
                                    
                                    // If active element is out of the scrollable area, scroll it into view
                                    if (elRect.top < tocRect.top || elRect.bottom > tocRect.bottom) {
                                        activeEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                                    }
                                }
                            });
                        });
                        
                        // Wait for the TOC script to populate the links, then observe them
                        setTimeout(function() {
                            const links = toc.querySelectorAll('.nav-link, .nav-item');
                            links.forEach(link => observer.observe(link, { attributes: true }));
                        }, 1000);
                    });
                    </script>
                </div>

                <div class="sc-jtXEFf jrBzsJ">
                    <div class="sc-eldieg eYVFtH">
                        <div class="overlay" id="toc-overlay"></div>
                        <button class="sc-kiIyQV fqmceZ table_content" aria-expanded="false" aria-controls="docy-toc">
                            <?php esc_html_e('On this Page', 'docy'); ?>
                        </button>
                        <aside class="bottom_table_content" id="docy-tocs" aria-hidden="true">
                            <button class="close-toc">
                                <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_" data-ui-name="Close" width="24" height="24" viewBox="0 0 24 24" data-name="Close" data-group="l" title="Close">
                                    <path d="M20.707 4.707a1 1 0 0 0-1.414-1.414L12 10.586 4.707 3.293a1 1 0 0 0-1.414 1.414L10.586 12l-7.293 7.293a1 1 0 1 0 1.414 1.414L12 13.414l7.293 7.293a1 1 0 0 0 1.414-1.414L13.414 12l7.293-7.293Z" shape-rendering="geometricPrecision"></path>
                                </svg>
                            </button>
                            <h6 class="toc-title mb-3"><?php esc_html_e('On this Page', 'docy'); ?></h6>
                            <nav class="nav-sidebar doc-nav" id="docy-tocs-mobile"></nav>
                        </aside>
                        <button class="sc-kiIyQV fqmceZ table_share_btn">
                            <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_ sc-cLpAjG cfZGuc" data-ui-name="Share" width="16" height="16" viewBox="0 0 16 16" data-name="Share" data-group="m">
                                <path d="M11.707 1.293a1 1 0 1 0-1.414 1.414L12.586 5H7a6 6 0 0 0-6 6v3a1 1 0 1 0 2 0v-3a4 4 0 0 1 4-4h5.586l-2.293 2.293a1 1 0 1 0 1.414 1.414l4-4a1 1 0 0 0 0-1.414l-4-4Z" shape-rendering="geometricPrecision"></path>
                            </svg>
                            <?php esc_html_e('Share', 'docy'); ?>
                        </button>
                        <div class="docy-modal-content" id="share-modal" aria-hidden="true">
                            <button class="close docy-close" aria-label="Close Share Modal">
                                <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_" data-ui-name="Close" width="24" height="24" viewBox="0 0 24 24" data-name="Close" data-group="l" title="Close">
                                    <path d="M20.707 4.707a1 1 0 0 0-1.414-1.414L12 10.586 4.707 3.293a1 1 0 0 0-1.414 1.414L10.586 12l-7.293 7.293a1 1 0 1 0 1.414 1.414L12 13.414l7.293 7.293a1 1 0 0 0 1.414-1.414L13.414 12l7.293-7.293Z" shape-rendering="geometricPrecision"></path>
                                </svg>
                            </button>
                            <div class="docy-share-wrap">
                                <div class="social-links">
                                    <a href="mailto:?subject=<?php the_title(); ?>&amp;body= <?php esc_html_e( 'Check out this doc', 'docy' ); the_permalink(); ?>" target="_blank"><i class="icon_mail"></i></a>
                                    <a href="https://www.facebook.com/share.php?u=<?php the_permalink(); ?>"><i class="social_facebook_circle"></i></a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>"><i class="social_linkedin_square"></i></a>
                                    <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?> &amp;hashtags=<?php echo esc_url(site_url()); ?>"><i class="social_twitter"></i></a>
                                </div>
                                <p>Copy link</p>
                                <div class="docy-copy-url-wrap">
                                    <div class="share-this-docs">
                                        <input readonly type="text" value="<?php the_permalink(); ?>" class="word-wrap">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/clone.svg" alt="<?php esc_attr_e( 'Docy theme', 'docy' ); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Secondary row: Related posts and comments below, without TOC on the side -->
        <!--
        <div class="row mt-5">
            <div class="col-lg-<?php echo esc_attr( $blog_column ) ?> blog_single_info m-auto">
                <?php
                // Related posts
                if ( is_singular('post') ) {
                    get_template_part( 'template-parts/single-post/related-posts' );
                }

                ?>
            </div>
        </div>
        -->
    </div>
</section>
    <script>
        // Remove empty &nbsp; spacer paragraphs from article content
        document.addEventListener('DOMContentLoaded', function() {
            var content = document.querySelector('.blog_single_item, .editor-content, article');
            if (content) {
                var paras = content.querySelectorAll('p');
                paras.forEach(function(p) {
                    var text = p.textContent.replace(/\u00a0/g, '').trim();
                    if (text === '') {
                        p.remove();
                    }
                });
            }
        });
    </script>
    <script>
        (function($) {
            "use strict";

            // 1. UNIFIED SCROLL STATE WRAPPER
            function handleAllScrollEvents() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop || window.scrollY || 0;
                
                // Toggle 'scrolled' class on body
                if (scrollTop > 10) {
                    document.body.classList.add('scrolled');
                } else {
                    document.body.classList.remove('scrolled');
                }
                
                // Trigger ScrollSpy
                if (typeof updateActiveTocOnScroll === 'function') {
                    updateActiveTocOnScroll(scrollTop);
                }
            }
            
            window.addEventListener('scroll', handleAllScrollEvents);
            handleAllScrollEvents(); // Run on load

            // 2. RELIABLE H1+H2 TOC GENERATOR & SCROLLSPY
            $(document).ready(function() {
                var isClickScrolling = false;
                var clickTimeout;
                var lastActiveId = ''; 

                function buildTOC() {
                    var $content = $('.blog_single_item, .main-post, .editor-content, article').first();
                    var $headings = $content.find('h1, h2');
                    
                    if ($headings.length > 0) {
                        var tocHtml = '<ul class="nav flex-column">';
                        $headings.each(function(index) {
                            var $h = $(this);
                            var id = $h.attr('id');
                            if (!id) {
                                id = 'toc-section-' + (index + 1);
                                $h.attr('id', id);
                            }
                            var text = $h.text().trim();
                            var level = this.tagName.toLowerCase(); // 'h1' or 'h2'
                            tocHtml += '<li class="nav-item toc-' + level + '"><a class="nav-link" href="#' + id + '">' + text + '</a></li>';
                        });
                        tocHtml += '</ul>';
                        $('#docy-toc, #docy-tocs-mobile').empty().html(tocHtml);
                        
                        // Initial high-fidelity activation
                        setTimeout(function() { 
                            window.updateActiveTocOnScroll = function(overriddenScroll) {
                                if (isClickScrolling) return;
                                
                                var scrollPos = (overriddenScroll !== undefined ? overriddenScroll : $(window).scrollTop()) + 175;
                                var $headings = $content.find('h1, h2');
                                var currentId = '';
                                
                                if ($headings.length > 0) {
                                    // Default to first item (H1 mimic)
                                    currentId = $headings.first().attr('id');
                                    
                                    $headings.each(function() {
                                        if (scrollPos >= $(this).offset().top) {
                                            currentId = $(this).attr('id');
                                        }
                                    });
                                }

                                if (currentId && currentId !== lastActiveId) {
                                    lastActiveId = currentId;
                                    highlightTOC(currentId);
                                }
                            };
                            updateActiveTocOnScroll(); 
                        }, 100);
                    }
                }

                function highlightTOC(id) {
                    var $targets = $('#docy-toc, #docy-tocs-mobile');
                    $targets.find('.nav-link, a').removeClass('active');
                    $targets.find('li').removeClass('active');
                    
                    var $activeLink = $targets.find('a[href="#' + id + '"]');
                    if ($activeLink.length > 0) {
                        $activeLink.addClass('active');
                        $activeLink.closest('li').addClass('active');
                    }
                }

                // Smooth click handler
                $(document).on('click', '#docy-toc a, #docy-tocs-mobile a', function(e) {
                    var href = $(this).attr('href');
                    if (href && href.startsWith('#')) {
                        var id = href.substring(1);
                        isClickScrolling = true;
                        lastActiveId = id;
                        highlightTOC(id);
                        
                        if (clickTimeout) clearTimeout(clickTimeout);
                        clickTimeout = setTimeout(function() {
                            isClickScrolling = false;
                        }, 2000);
                    }
                });
                
                setTimeout(buildTOC, 600);
            });
        })(jQuery);
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var cols = document.querySelectorAll('.category-left-sidebar-col');
            cols.forEach(function(col) {
                var sidebar = col.querySelector('.modern-sidebar');
                if (sidebar) {
                    col.addEventListener('mouseenter', function() {
                        sidebar.classList.add('sidebar-hovered');
                        sidebar.style.overflowY = 'hidden';
                        requestAnimationFrame(function() {
                            sidebar.style.overflowY = 'auto';
                        });
                    });
                    col.addEventListener('mouseleave', function() {
                        sidebar.classList.remove('sidebar-hovered');
                        sidebar.style.overflowY = 'hidden';
                        requestAnimationFrame(function() {
                            sidebar.style.overflowY = 'auto';
                        });
                    });
                }
            });
        });
    </script>
<?php
get_footer();

