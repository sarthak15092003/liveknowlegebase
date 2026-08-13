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
<section class="blog_area tip_doc_area" id="toc_stick" style="padding-top: 0px !important;">

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
                            <div class="post-author-meta-box d-flex align-items-center mb-4 mt-2" style="gap: 8px; padding: 2px 0;">
                                <div class="author-avatar" style="width: 18px; height: 18px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                    <img src="https://docs.cmgalaxy.com/wp-content/uploads/2026/06/cropped-Group-1000004539-300x300-1.png" alt="Author Avatar" style="width: 18px; height: 18px; object-fit: contain; border: none !important; display: block;">
                                </div>
                                <div class="author-info" style="line-height: 1.4; color: #6b7280; font-family: 'Instrument Sans', sans-serif;">
                                    <div style="font-size: 14px; display: flex; align-items: center;">Written by &nbsp;<span style="color: #4b5563; font-weight: 500;"><?php echo get_the_author(); ?></span></div>
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
                                                <a href="<?php the_permalink(); ?>" class="d-flex justify-content-between align-items-center related-article-link">
                                                    <span><?php the_title(); ?></span>
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="related-article-icon">
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

                        <style>
                        .cm-feedback-wrapper {
                            margin-top: 40px;
                            margin-bottom: 40px;
                            font-family: 'Instrument Sans', sans-serif;
                        }
                        .cm-feedback-top {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            padding-bottom: 20px;
                        }
                        .cm-feedback-title {
                            font-size: 16px;
                            color: #4b5563;
                            margin: 0;
                        }
                        .cm-feedback-buttons {
                            display: flex;
                            gap: 12px;
                        }
                        .cm-btn-vote {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            padding: 8px 16px;
                            border: 1px solid #d1d5db;
                            background: #ffffff;
                            border-radius: 999px;
                            color: #374151;
                            font-size: 14px;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.2s;
                        }
                        .cm-btn-vote:hover {
                            background: #f9fafb;
                            border-color: #9ca3af;
                        }
                        .cm-btn-vote.active {
                            background: #f3f4f6;
                            border-color: #6b7280;
                        }
                        .cm-btn-vote svg {
                            width: 16px;
                            height: 16px;
                        }
                        .cm-feedback-form-area {
                            display: none;
                            padding-top: 24px;
                            border-top: 1px solid #e5e7eb;
                        }
                        .cm-feedback-form-title {
                            font-size: 18px;
                            font-weight: 600;
                            color: #111827;
                            margin-bottom: 16px;
                        }
                        .cm-feedback-options {
                            display: flex;
                            flex-direction: column;
                            gap: 12px;
                            margin-bottom: 24px;
                        }
                        .cm-feedback-option {
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            cursor: pointer;
                            color: #4b5563;
                            font-size: 15px;
                        }
                        .cm-feedback-radio {
                            appearance: none;
                            width: 20px;
                            height: 20px;
                            border: 1px solid #d1d5db;
                            border-radius: 50%;
                            margin: 0;
                            cursor: pointer;
                            position: relative;
                        }
                        .cm-feedback-radio:checked {
                            border-color: #808080;
                            border-width: 5px;
                        }
                        .cm-feedback-actions {
                            display: flex;
                            gap: 12px;
                        }
                        .cm-btn-cancel {
                            padding: 10px 20px;
                            border: 1px solid #d1d5db;
                            background: #ffffff;
                            border-radius: 12px;
                            color: #374151;
                            font-size: 14px;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.2s;
                        }
                        .cm-btn-cancel:hover {
                            background: #f9fafb;
                        }
                        .cm-btn-submit {
                            padding: 10px 20px;
                            border: none;
                            background: #808080;
                            border-radius: 12px;
                            color: #ffffff;
                            font-size: 14px;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.2s;
                        }
                        .cm-btn-submit:hover {
                            background: #6b7280;
                        }
                        </style>

                        <div class="cm-feedback-wrapper">
                            <div class="cm-feedback-top">
                                <h4 class="cm-feedback-title">Was this page helpful?</h4>
                                <div class="cm-feedback-buttons">
                                    <button class="cm-btn-vote" id="cm-vote-yes">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                        Yes
                                    </button>
                                    <button class="cm-btn-vote" id="cm-vote-no">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3"></path></svg>
                                        No
                                    </button>
                                </div>
                            </div>
                            
                            <div class="cm-feedback-form-area" id="cm-feedback-form">
                                <h3 class="cm-feedback-form-title" id="cm-feedback-title" style="margin-top: 0px;">How can we improve our product?</h3>
                                
                                <div class="cm-feedback-options" id="cm-options-yes" style="display: none;">
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_yes" class="cm-feedback-radio" value="The guide worked as expected">
                                        The guide worked as expected
                                    </label>
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_yes" class="cm-feedback-radio" value="It was easy to find the information I needed">
                                        It was easy to find the information I needed
                                    </label>
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_yes" class="cm-feedback-radio" value="It was easy to understand the product and features">
                                        It was easy to understand the product and features
                                    </label>
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_yes" class="cm-feedback-radio" value="The documentation is up to date">
                                        The documentation is up to date
                                    </label>
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_yes" class="cm-feedback-radio" value="Something else">
                                        Something else
                                    </label>
                                </div>

                                <div class="cm-feedback-options" id="cm-options-no" style="display: none;">
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_no" class="cm-feedback-radio" value="Help me get started faster">
                                        Help me get started faster
                                    </label>
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_no" class="cm-feedback-radio" value="Make it easier to find what I'm looking for">
                                        Make it easier to find what I'm looking for
                                    </label>
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_no" class="cm-feedback-radio" value="Make it easy to understand the product and features">
                                        Make it easy to understand the product and features
                                    </label>
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_no" class="cm-feedback-radio" value="Update this documentation">
                                        Update this documentation
                                    </label>
                                    <label class="cm-feedback-option">
                                        <input type="radio" name="cm_feedback_reason_no" class="cm-feedback-radio" value="Something else">
                                        Something else
                                    </label>
                                </div>

                                <div class="cm-feedback-actions">
                                    <button type="button" class="cm-btn-cancel" id="cm-feedback-cancel">Cancel</button>
                                    <button type="button" class="cm-btn-submit" id="cm-feedback-submit">Submit feedback</button>
                                </div>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const btnYes = document.getElementById('cm-vote-yes');
                            const btnNo = document.getElementById('cm-vote-no');
                            const formArea = document.getElementById('cm-feedback-form');
                            const btnCancel = document.getElementById('cm-feedback-cancel');
                            const btnSubmit = document.getElementById('cm-feedback-submit');
                            const title = document.getElementById('cm-feedback-title');
                            const optionsYes = document.getElementById('cm-options-yes');
                            const optionsNo = document.getElementById('cm-options-no');
                            
                            function showForm(vote) {
                                formArea.style.display = 'block';
                                document.querySelectorAll('.cm-feedback-radio').forEach(r => r.checked = false);
                                
                                if (vote === 'yes') {
                                    btnYes.classList.add('active');
                                    btnNo.classList.remove('active');
                                    title.innerText = 'Great! What worked best for you?';
                                    optionsYes.style.display = 'flex';
                                    optionsNo.style.display = 'none';
                                } else {
                                    btnNo.classList.add('active');
                                    btnYes.classList.remove('active');
                                    title.innerText = 'How can we improve our product?';
                                    optionsNo.style.display = 'flex';
                                    optionsYes.style.display = 'none';
                                }
                            }
                            
                            if (btnYes) btnYes.addEventListener('click', () => showForm('yes'));
                            if (btnNo) btnNo.addEventListener('click', () => showForm('no'));
                            
                            if (btnCancel) {
                                btnCancel.addEventListener('click', () => {
                                    formArea.style.display = 'none';
                                    btnYes.classList.remove('active');
                                    btnNo.classList.remove('active');
                                    document.querySelectorAll('.cm-feedback-radio').forEach(r => r.checked = false);
                                });
                            }
                            
                            if (btnSubmit) {
                                btnSubmit.addEventListener('click', () => {
                                    const selected = document.querySelector('.cm-feedback-radio:checked');
                                    if (!selected) {
                                        alert('Please select an option first.');
                                        return;
                                    }
                                    
                                    const vote = btnYes.classList.contains('active') ? 'yes' : 'no';
                                    const reason = selected.value;
                                    const postId = <?php echo get_the_ID(); ?>;
                                    const postTitle = <?php echo json_encode(get_the_title()); ?>;
                                    
                                    const formData = new FormData();
                                    formData.append('action', 'cm_submit_feedback');
                                    formData.append('post_id', postId);
                                    formData.append('post_title', postTitle);
                                    formData.append('vote', vote);
                                    formData.append('reason', reason);
                                    
                                    // Disable submit button while saving
                                    const originalText = btnSubmit.innerText;
                                    btnSubmit.innerText = 'Saving...';
                                    btnSubmit.disabled = true;

                                    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if(data.success) {
                                            alert('Thank you for your feedback!');
                                        } else {
                                            alert('Something went wrong. Please try again.');
                                        }
                                    })
                                    .catch(error => {
                                        alert('Error saving feedback.');
                                        console.error(error);
                                    })
                                    .finally(() => {
                                        btnSubmit.innerText = originalText;
                                        btnSubmit.disabled = false;
                                        formArea.style.display = 'none';
                                        btnYes.classList.remove('active');
                                        btnNo.classList.remove('active');
                                        document.querySelectorAll('.cm-feedback-radio').forEach(r => r.checked = false);
                                    });
                                });
                            }
                        });
                        </script>

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
                    <style>
                    @media (min-width: 1025px) {
                        .single-post .doc-sidebar .left_sidebarlist {
                            margin-left: 0 !important;
                            padding-left: 0 !important;
                            background: transparent !important;
                            border: 0 !important;
                            border-radius: 0 !important;
                            box-shadow: none !important;
                        }

                        .single-post .doc-sidebar .left_sidebarlist::before,
                        .single-post .doc-sidebar .left_sidebarlist .nav-sidebar::before,
                        .single-post .doc-sidebar #docy-toc > ul::before {
                            content: none !important;
                            display: none !important;
                        }

                        .single-post .doc-sidebar #docy-toc::before {
                            content: "" !important;
                            display: block !important;
                            position: absolute !important;
                            top: 0 !important;
                            bottom: 0 !important;
                            left: 0 !important;
                            width: 2px !important;
                            background: #E5E7EB !important;
                            z-index: 1 !important;
                        }

                        .single-post .doc-sidebar .toc-title {
                            padding-left: 20px !important;
                            padding-bottom: 0 !important;
                            border-bottom: 0 !important;
                        }

                        .single-post .doc-sidebar #docy-toc {
                            position: relative !important;
                            padding-left: 0 !important;
                            background: transparent !important;
                            border: 0 !important;
                            border-left: 0 !important;
                            border-radius: 0 !important;
                            box-shadow: none !important;
                        }

                        .single-post .doc-sidebar #docy-toc ul {
                            border-left: none !important;
                            padding-left: 0 !important;
                            margin-left: 0 !important;
                            list-style: none !important;
                        }

                        .single-post .doc-sidebar #docy-toc .nav-link {
                            position: relative !important;
                            padding: 6px 0 6px 16px !important;
                            background: transparent !important;
                            border: 0 !important;
                            box-shadow: none !important;
                            transition: color 0.2s ease !important;
                            font-weight: 400 !important;
                            color: #4B5563 !important;
                        }

                        .single-post .doc-sidebar #docy-toc .toc-h1 > .nav-link {
                            padding-left: 16px !important;
                            font-weight: 400 !important;
                        }

                        .single-post .doc-sidebar #docy-toc .toc-h2 > .nav-link {
                            padding-left: 28px !important;
                            font-weight: 400 !important;
                        }

                        .single-post .doc-sidebar #docy-toc .toc-h3 > .nav-link {
                            padding-left: 40px !important;
                            font-weight: 400 !important;
                        }

                        .single-post .doc-sidebar #docy-toc .nav-item.active > .nav-link,
                        .single-post .doc-sidebar #docy-toc .nav-link.active,
                        .single-post .doc-sidebar #docy-toc a.active,
                        .single-post .doc-sidebar #docy-toc li.active > a,
                        .single-post .doc-sidebar #docy-toc .toc-h1.active > .nav-link,
                        .single-post .doc-sidebar #docy-toc .toc-h2.active > .nav-link,
                        .single-post .doc-sidebar #docy-toc .toc-h3.active > .nav-link {
                            color: #3B82F6 !important;
                            font-weight: 500 !important;
                            border-left: none !important;
                            margin-left: 0 !important;
                        }

                        .single-post .doc-sidebar #docy-toc .nav-item.active > .nav-link::before,
                        .single-post .doc-sidebar #docy-toc .nav-link.active::before,
                        .single-post .doc-sidebar #docy-toc a.active::before,
                        .single-post .doc-sidebar #docy-toc li.active > a::before,
                        .single-post .doc-sidebar #docy-toc li.active > .nav-link::before,
                        .single-post .doc-sidebar #docy-toc .toc-h1.active > .nav-link::before,
                        .single-post .doc-sidebar #docy-toc .toc-h2.active > .nav-link::before,
                        .single-post .doc-sidebar #docy-toc .toc-h3.active > .nav-link::before {
                            content: "" !important;
                            display: block !important;
                            position: absolute !important;
                            left: 0 !important;
                            top: 4px !important;
                            bottom: 4px !important;
                            width: 2px !important;
                            background: #3B82F6 !important;
                            border-radius: 2px !important;
                            z-index: 10 !important;
                        }

                        .blog_single_item h1, .blog_single_item h2, .blog_single_item h3, .blog_single_item h4, .blog_single_item h5, .blog_single_item h6,
                        .main-post h1, .main-post h2, .main-post h3, .main-post h4, .main-post h5, .main-post h6,
                        .editor-content h1, .editor-content h2, .editor-content h3, .editor-content h4,
                        article h1, article h2, article h3,
                        [id^="toc-section-"] {
                            scroll-margin-top: 140px !important;
                        }
                    }
                    </style>
                    
                    <aside class="left_sidebarlist">
                        <h6 class="toc-title mb-3"><?php esc_html_e('On this Page', 'docy'); ?></h6>
                        <nav class="list-unstyled nav-sidebar doc-nav" id="docy-toc"> </nav>
                        <!-- <div class="toc-sidebar-image mt-4">
                            <a href="https://cmgalaxy.com/book-a-demo" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/sidebarimg.png' ); ?>" alt="Book a Demo - CMGalaxy" class="img-fluid rounded-3" style="width: 100%;">
                            </a>
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

                // Smooth click handler with zero jerk and zero delay
                $(document).on('click', '#docy-toc a, #docy-tocs-mobile a', function(e) {
                    var href = $(this).attr('href');
                    if (href && href.startsWith('#')) {
                        var id = href.substring(1);
                        var $target = $('#' + $.escapeSelector(id));
                        if ($target.length > 0) {
                            e.preventDefault();
                            e.stopImmediatePropagation();

                            isClickScrolling = true;
                            lastActiveId = id;
                            highlightTOC(id);

                            var adminBarHeight = $('#wpadminbar').length > 0 ? $('#wpadminbar').outerHeight() : 0;
                            var headerOffset = ($(window).width() <= 1024 ? 90 : 140) + adminBarHeight;
                            var targetPosition = Math.max(0, $target.offset().top - headerOffset);

                            $('html, body').stop(true, false).animate({
                                scrollTop: targetPosition
                            }, 250, 'swing', function() {
                                isClickScrolling = false;
                            });
                        }
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

