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
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <div class="docy-share-wrap" style="padding: 10px 5px;">
                                <p class="share-modal-title" style="text-align: center; font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Copy link</p>
                                
                                <div class="docy-copy-url-wrap" style="margin-bottom: 20px;">
                                    <div class="share-this-docs" style="display: flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; gap: 8px; cursor: pointer;">
                                        <input readonly type="text" value="<?php the_permalink(); ?>" class="word-wrap cm-share-input" style="flex: 1; border: none; background: transparent; font-size: 13.5px; color: #475569; outline: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;">
                                        <button type="button" class="cm-copy-action-btn" title="Copy link" style="border: none; background: transparent; cursor: pointer; display: flex; align-items: center; color: #3b82f6; padding: 0;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="cm-copy-toast" style="display: none; color: #10b981; font-size: 12px; text-align: center; margin-top: 6px; font-weight: 500;">Link copied to clipboard!</div>
                                </div>

                                <div class="social-links" style="display: flex; align-items: center; justify-content: center; gap: 14px;">
                                    <!-- WhatsApp -->
                                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" title="WhatsApp" style="width: 42px; height: 42px; border-radius: 50%; background: #25D366; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none;">
                                        <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
                                    </a>
                                    <!-- Facebook -->
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" title="Facebook" style="width: 42px; height: 42px; border-radius: 50%; background: #1877F2; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none;">
                                        <i class="fab fa-facebook-f" style="font-size: 18px;"></i>
                                    </a>
                                    <!-- LinkedIn -->
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink(); ?>" target="_blank" title="LinkedIn" style="width: 42px; height: 42px; border-radius: 50%; background: #0A66C2; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none;">
                                        <i class="fab fa-linkedin-in" style="font-size: 18px;"></i>
                                    </a>
                                    <!-- Twitter/X -->
                                    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" title="X" style="width: 42px; height: 42px; border-radius: 50%; background: #000000; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; text-decoration: none;">
                                        <i class="fab fa-x-twitter" style="font-size: 18px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/clone.svg" alt="<?php esc_attr_e( 'Docy theme', 'docy' ); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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