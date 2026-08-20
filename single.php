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
                    <div class="cmgalaxy-engagement-block mt-5" style=" border-radius: 12px; background: #ffffff;">
                        <p class="lead mb-4" style="color: #484a61 !important; font-size: 1.125rem; line-height: 1.75;">
                            Thanks for being here with us! We are beyond excited to see how you'll use CMGalaxy to drive growth for your business.
                        </p>

                        <div class="related-articles-section" style="margin-top: 3.0rem; margin-bottom: 1.5rem;">
                            <h5 class="fw-semibold mb-3" id="related-articles" style="color: #484A61 !important; font-size: 20px !important; font-weight: 600 !important;">Related Articles</h5>
                            <ul class="list-unstyled mb-0" style="border: 1px solid #e5e7eb; border-radius: 8px;">
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
                                            <li class="">
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
                            flex-shrink: 0;
                        }
                        @media (max-width: 768px) {
                            .cm-feedback-top {
                                flex-direction: column !important;
                                align-items: center !important;
                                text-align: center !important;
                                gap: 14px !important;
                            }
                            .cm-feedback-title {
                                text-align: center !important;
                                width: 100% !important;
                            }
                            .cm-feedback-buttons {
                                width: 100% !important;
                                gap: 12px !important;
                                justify-content: center !important;
                            }
                            .cm-btn-vote {
                                flex: 1 !important;
                                justify-content: center !important;
                            }
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
                                        Like
                                    </button>
                                    <button class="cm-btn-vote" id="cm-vote-no">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3"></path></svg>
                                        Dislike
                                    </button>
                                </div>
                            </div>

                            <div class="cm-feedback-login-prompt" id="cm-feedback-login-prompt" style="display: none; padding: 18px 16px; text-align: center; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; margin-top: 16px;">
                                <p style="margin: 0; color: #475569; font-size: 14px; line-height: 1.5;">
                                    Please <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" style="color: #3b82f6; font-weight: 600; text-decoration: underline;">Sign In</a> or <a href="<?php echo esc_url( wp_registration_url() ); ?>" style="color: #3b82f6; font-weight: 600; text-decoration: underline;">Sign Up</a> to leave your feedback.
                                </p>
                            </div>
                            
                            <div class="cm-feedback-form-area" id="cm-feedback-form" style="display: none;">
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

                                <div class="cm-feedback-custom-text-wrap" id="cm-custom-text-wrap" style="display: none; margin-top: 14px; margin-bottom: 14px;">
                                    <textarea id="cm-feedback-custom-text" placeholder="Please specify your feedback..." style="width: 100%; min-height: 80px; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit; box-sizing: border-box; resize: vertical; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                                </div>

                                <div class="cm-feedback-actions">
                                    <button type="button" class="cm-btn-cancel" id="cm-feedback-cancel">Cancel</button>
                                    <button type="button" class="cm-btn-submit" id="cm-feedback-submit">Submit feedback</button>
                                </div>
                            </div>

                            <div class="cm-feedback-success-area" id="cm-feedback-success" style="display: none; padding: 20px 16px; text-align: center; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; margin-top: 16px;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 8px; display: inline-block;">
                                    <circle cx="12" cy="12" r="10" fill="#22C55E"/>
                                    <path d="M8 12L11 15L16 9" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div id="cm-feedback-success-text" style="color: #15803d; font-weight: 600; font-size: 15px; line-height: 1.4;">Thank you for your feedback!</div>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const isLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
                            const btnYes = document.getElementById('cm-vote-yes');
                            const btnNo = document.getElementById('cm-vote-no');
                            const formArea = document.getElementById('cm-feedback-form');
                            const loginPrompt = document.getElementById('cm-feedback-login-prompt');
                            const btnCancel = document.getElementById('cm-feedback-cancel');
                            const btnSubmit = document.getElementById('cm-feedback-submit');
                            const title = document.getElementById('cm-feedback-title');
                            const optionsYes = document.getElementById('cm-options-yes');
                            const optionsNo = document.getElementById('cm-options-no');
                            const customWrap = document.getElementById('cm-custom-text-wrap');
                            const customTextarea = document.getElementById('cm-feedback-custom-text');
                            const successArea = document.getElementById('cm-feedback-success');
                            const successText = document.getElementById('cm-feedback-success-text');
                            
                            function resetCustomText() {
                                if (customWrap) customWrap.style.display = 'none';
                                if (customTextarea) customTextarea.value = '';
                            }

                            function hideSuccess() {
                                if (successArea) successArea.style.display = 'none';
                            }
                            
                            function showForm(vote) {
                                hideSuccess();

                                if (vote === 'like' || vote === 'yes') {
                                    btnYes.classList.add('active');
                                    btnNo.classList.remove('active');
                                } else {
                                    btnNo.classList.add('active');
                                    btnYes.classList.remove('active');
                                }

                                if (!isLoggedIn) {
                                    if (formArea) formArea.style.display = 'none';
                                    if (loginPrompt) loginPrompt.style.display = 'block';
                                    return;
                                }

                                if (loginPrompt) loginPrompt.style.display = 'none';
                                if (formArea) formArea.style.display = 'block';
                                
                                document.querySelectorAll('.cm-feedback-radio').forEach(r => r.checked = false);
                                resetCustomText();
                                
                                if (vote === 'like' || vote === 'yes') {
                                    title.innerText = 'Great! What worked best for you?';
                                    optionsYes.style.display = 'flex';
                                    optionsNo.style.display = 'none';
                                } else {
                                    title.innerText = 'How can we improve our product?';
                                    optionsNo.style.display = 'flex';
                                    optionsYes.style.display = 'none';
                                }
                            }

                            document.querySelectorAll('.cm-feedback-radio').forEach(radio => {
                                radio.addEventListener('change', function() {
                                    if (this.checked && this.value === 'Something else') {
                                        if (customWrap) customWrap.style.display = 'block';
                                        if (customTextarea) customTextarea.focus();
                                    } else {
                                        resetCustomText();
                                    }
                                });
                            });
                            
                            if (btnYes) btnYes.addEventListener('click', () => showForm('like'));
                            if (btnNo) btnNo.addEventListener('click', () => showForm('dislike'));
                            
                            if (btnCancel) {
                                btnCancel.addEventListener('click', () => {
                                    if (formArea) formArea.style.display = 'none';
                                    if (loginPrompt) loginPrompt.style.display = 'none';
                                    btnYes.classList.remove('active');
                                    btnNo.classList.remove('active');
                                    document.querySelectorAll('.cm-feedback-radio').forEach(r => r.checked = false);
                                    resetCustomText();
                                    hideSuccess();
                                });
                            }
                            
                            if (btnSubmit) {
                                btnSubmit.addEventListener('click', () => {
                                    const selected = document.querySelector('.cm-feedback-radio:checked');
                                    if (!selected) {
                                        alert('Please select an option first.');
                                        return;
                                    }
                                    
                                    const vote = btnYes.classList.contains('active') ? 'like' : 'dislike';
                                    let reason = selected.value;
                                    if (reason === 'Something else' && customTextarea) {
                                        const customVal = customTextarea.value.trim();
                                        if (customVal) {
                                            reason = 'Something else: ' + customVal;
                                        }
                                    }
                                    const postId = <?php echo get_the_ID(); ?>;
                                    const postTitle = <?php echo json_encode(get_the_title()); ?>;
                                    const userId = <?php echo get_current_user_id(); ?>;
                                    const userName = <?php $cu = wp_get_current_user(); echo json_encode( $cu->exists() ? ( $cu->display_name ? $cu->display_name : $cu->user_login ) : '' ); ?>;
                                    const userEmail = <?php $cu = wp_get_current_user(); echo json_encode( $cu->exists() ? $cu->user_email : '' ); ?>;
                                    
                                    const formData = new FormData();
                                    formData.append('action', 'cm_submit_feedback');
                                    formData.append('post_id', postId);
                                    formData.append('post_title', postTitle);
                                    formData.append('user_id', userId);
                                    formData.append('user_name', userName);
                                    formData.append('user_email', userEmail);
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
                                            const thankMsg = (vote === 'dislike' || vote === 'no')
                                                ? 'Thank you for your feedback! We will get back to you.'
                                                : 'Thank you for your feedback!';
                                                
                                            if (formArea) formArea.style.display = 'none';
                                            if (successText) successText.innerText = thankMsg;
                                            if (successArea) successArea.style.display = 'block';
                                        } else {
                                            alert(data.data || 'Something went wrong. Please try again.');
                                        }
                                    })
                                    .catch(error => {
                                        alert('Error saving feedback.');
                                        console.error(error);
                                    })
                                    .finally(() => {
                                        btnSubmit.innerText = originalText;
                                        btnSubmit.disabled = false;
                                        btnYes.classList.remove('active');
                                        btnNo.classList.remove('active');
                                        document.querySelectorAll('.cm-feedback-radio').forEach(r => r.checked = false);
                                        resetCustomText();
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
                                    <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="nav-card prev-card">
                                        <div class="nav-card-content">
                                            <h5 class="mb-3"><?php echo esc_html( get_the_title( $prev_post ) ); ?></h5>
                                            <div class="nav-direction d-flex align-items-center">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                                    <path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                Previous
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $next_post ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="nav-card next-card">
                                        <div class="nav-card-content text-end">
                                            <h5 class="mb-3"><?php echo esc_html( get_the_title( $next_post ) ); ?></h5>
                                            <div class="nav-direction d-flex align-items-center justify-content-end">
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
                <div class="col-lg-2 doc-sidebar pe-lg-0 ps-lg-2 d-none d-lg-block">
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
                            padding: 6px 0 6px 20px !important;
                            background: transparent !important;
                            border: 0 !important;
                            box-shadow: none !important;
                            transition: color 0.2s ease !important;
                            font-weight: 400 !important;
                            color: #4B5563 !important;
                        }

                        .single-post .doc-sidebar #docy-toc .toc-h1 > .nav-link,
                        .single-post .doc-sidebar #docy-toc .toc-h2 > .nav-link {
                            padding-left: 20px !important;
                            font-weight: 400 !important;
                        }

                        .single-post .doc-sidebar #docy-toc .toc-h3 > .nav-link {
                            padding-left: 32px !important;
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
                                    
                                    // If active element is out of the scrollable area, scroll ONLY the TOC container
                                    if (elRect.top < tocRect.top || elRect.bottom > tocRect.bottom) {
                                        toc.scrollTop = activeEl.offsetTop - (toc.clientHeight / 2);
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
                            e.stopPropagation();
                            e.stopImmediatePropagation();

                            isClickScrolling = true;
                            lastActiveId = id;
                            highlightTOC(id);

                            var adminBarHeight = $('#wpadminbar').length > 0 ? $('#wpadminbar').outerHeight() : 0;
                            var headerOffset = ($(window).width() <= 1024 ? 90 : 140) + adminBarHeight;
                            var targetPosition = Math.max(0, $target.offset().top - headerOffset);

                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });

                            if (clickTimeout) clearTimeout(clickTimeout);
                            clickTimeout = setTimeout(function() {
                                isClickScrolling = false;
                            }, 400);
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

