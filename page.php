<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package docy
 */

get_header();

$opt = get_option('docy_opt');

if ( docy_toc('page') == '1' ) {
	wp_enqueue_script( 'anchor' );
	wp_enqueue_script( 'bootstrap-toc' );
}

$padding = "";

$wrap_class = 'page_wrapper';
if ( class_exists('bbPress') ) {
    $wrap_class .= is_post_type_archive('forum') || is_post_type_archive('topic') || is_singular('forum') ? ' forum-page-content' : '';
} elseif ( in_array('woocommerce', get_body_class()) || in_array('woocommerce-page', get_body_class() ) ) {
    $wrap_class = '';
}

while ( have_posts() ) : the_post();
    ?>
    <div class="sec_pad <?php echo esc_attr($wrap_class) ?>">
        <div class="container">

	        <?php
	        if ( docy_toc('page') == '1' ) :
            ?>
            <div id="toc_stick" class="row">
                <div class="col-lg-2 doc_mobile_menu doc-sidebar display_none">
                    <aside class="left_sidebarlist">
                        <nav data-toggle="toc" class="nav-sidebar doc-nav" id="docy-toc"> </nav>
                    </aside>
                </div>

                <div class="sc-jtXEFf jrBzsJ" id="cm-bottom-action-bar">
                    <div class="sc-eldieg eYVFtH">
                        <div class="overlay" id="toc-overlay"></div>
                        <button class="sc-kiIyQV fqmceZ table_content" aria-expanded="false" aria-controls="docy-toc">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; flex-shrink: 0;">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span><?php esc_html_e('On this Page', 'docy'); ?></span>
                        </button>
                        <aside class="bottom_table_content" id="docy-tocs" aria-hidden="true">
                            <button class="close-toc">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <h6 class="toc-title mb-3"><?php esc_html_e('On this Page', 'docy'); ?></h6>
                            <nav class="nav-sidebar doc-nav" id="docy-tocs-mobile"></nav>
                        </aside>
                        <button class="sc-kiIyQV fqmceZ table_share_btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; flex-shrink: 0;">
                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                <polyline points="16 6 12 2 8 6"></polyline>
                                <line x1="12" y1="2" x2="12" y2="15"></line>
                            </svg>
                            <span><?php esc_html_e('Share', 'docy'); ?></span>
                        </button>
                        <div class="docy-modal-content" id="share-modal" aria-hidden="true">
                            <button class="close docy-close" aria-label="Close Share Modal">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <div class="docy-share-wrap" style="padding: 10px 0;">
                                <p class="share-modal-title" style="text-align: center; font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 20px;">Share</p>
                                
                                <div class="social-links" style="display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap;">
                                    <!-- Copy Link Button -->
                                    <button type="button" class="share-social-icon cm-copy-action-btn" data-url="<?php the_permalink(); ?>" title="Copy link" style="width: 46px; height: 46px; border-radius: 50%; background: #f1f5f9; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; justify-content: center; color: #3b82f6; cursor: pointer; padding: 0;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                    </button>
                                    <!-- WhatsApp -->
                                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" class="share-social-icon" title="WhatsApp" style="width: 46px; height: 46px; border-radius: 50%; background: #25D366; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none;">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                    </a>
                                    <!-- Facebook -->
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="share-social-icon" title="Facebook" style="width: 46px; height: 46px; border-radius: 50%; background: #1877F2; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <!-- LinkedIn -->
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink(); ?>" target="_blank" class="share-social-icon" title="LinkedIn" style="width: 46px; height: 46px; border-radius: 50%; background: #0A66C2; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                        </svg>
                                    </a>
                                    <!-- Twitter/X -->
                                    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-social-icon" title="X" style="width: 46px; height: 46px; border-radius: 50%; background: #000000; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class="cm-copy-toast" style="display: none; color: #10b981; font-size: 13px; text-align: center; margin-top: 14px; font-weight: 500;">Link copied to clipboard!</div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                (function() {
                    function initFooterHideBar() {
                        var bar = document.getElementById('cm-bottom-action-bar') || document.querySelector('.jrBzsJ');
                        if (!bar) return;

                        var footers = document.querySelectorAll('footer, .footer_area, .doc_footer_top, .footer-content, .footer-copyright');
                        if (!footers.length) return;

                        if ('IntersectionObserver' in window) {
                            var observer = new IntersectionObserver(function(entries) {
                                var isAnyVisible = entries.some(function(e) { return e.isIntersecting; });
                                if (isAnyVisible) {
                                    bar.classList.add('hidden-by-footer');
                                } else {
                                    bar.classList.remove('hidden-by-footer');
                                }
                            }, {
                                rootMargin: '0px 0px 30px 0px',
                                threshold: 0
                            });
                            footers.forEach(function(f) { observer.observe(f); });
                        }

                        function onScrollCheck() {
                            var footer = document.querySelector('footer, .footer_area, .doc_footer_top, .footer-content, .footer-copyright');
                            if (!footer) return;
                            var rect = footer.getBoundingClientRect();
                            var vh = window.innerHeight || document.documentElement.clientHeight;
                            if (rect.top <= vh + 15) {
                                bar.classList.add('hidden-by-footer');
                            } else {
                                bar.classList.remove('hidden-by-footer');
                            }
                        }
                        window.addEventListener('scroll', onScrollCheck, { passive: true });
                        window.addEventListener('touchmove', onScrollCheck, { passive: true });
                        window.addEventListener('resize', onScrollCheck, { passive: true });
                        window.addEventListener('orientationchange', onScrollCheck, { passive: true });
                        onScrollCheck();
                    }
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initFooterHideBar);
                    } else {
                        initFooterHideBar();
                    }
                })();
                </script>
            <?php endif; ?>

                <div class="col-lg-10 anchor-enabled">
                <?php endif; ?>
                    <?php
                    the_content();
                    wp_link_pages(array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'docy' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'docy' ) . ' </span>%',
                        'separator'   => '<span class="screen-reader-text">, </span>',
                    ));

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                    echo docy_toc('page') == '1' ? '
                </div> 
            </div>' : ''; // Close the row
            ?>
        </div>
    </div>

<?php
endwhile; // End of the loop.

if ( is_post_type_archive( array('forum', 'topic') ) ) {
    if ( docy_opt('is_forum_btm_c2a') == '1' ) {
        get_template_part('template-parts/forum/c2a-bottom');
    }
}

get_footer();