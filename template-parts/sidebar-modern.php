<?php
/**
 * Modern Sidebar Template - Clean Design with Icons
 * On single post pages: shows only the active category with a back button
 * On other pages: shows all categories
 */

// Determine if we're on a single post page or category page
$is_single_post = is_singular('post');
$current_post_id = get_queried_object_id();

// Get current post's categories or current archive's category
$current_categories = array();
if (is_singular('post')) {
    $cats = get_the_category($current_post_id);
    if ($cats) {
        // Filter out parent categories if a subcategory is also assigned
        $filtered_cats = array();
        foreach ($cats as $cat) {
            $is_parent = false;
            foreach ($cats as $other_cat) {
                if ($other_cat->parent == $cat->term_id) {
                    $is_parent = true;
                    break;
                }
            }
            if (!$is_parent) {
                $filtered_cats[] = $cat;
            }
        }
        
        foreach ($filtered_cats as $cat) {
            $current_categories[] = $cat->slug;
        }
    }
} elseif (is_category()) {
    $cat = get_queried_object();
    if ($cat && isset($cat->slug)) {
        $current_categories[] = $cat->slug;
    }
} elseif (isset($_GET['cat']) && !empty($_GET['cat'])) {
    $cat = get_category(intval($_GET['cat']));
    if ($cat && !is_wp_error($cat) && isset($cat->slug)) {
        $current_categories[] = $cat->slug;
    }
}

// Define custom icons mapping for dynamic categories based on category name
$custom_icons = array(
    'User Manegement' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/usermanagement.png',
    'User Management' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/usermanagement.png',
    'Account Mangement' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/account-management-1.png',
    'Account Management' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/account-management-1.png',
    'Master Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/master-dashboard.png',
    'Main Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/category-2.png',
    'Funnel Attribution' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/funnel.png',
    'Integrations' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/integrations.png',
    'Google Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/google.png',
    'Meta Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/meta.png',
    'DV360 Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/DV360.png',
    'Amazon Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/amzone.png',
    'Recommendation' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/recommendation.png',
    'Pinterest Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/pinterest.png',
    'Milestone' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/milestonte.png',
    'Notification Center' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/notification.png',
    'Ticket/ Support' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/support.png',
    'Tickets / Supports' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/support.png',
    'Reporting HUb' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/report.png',
    'Reporting Hub' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/report.png',
    'Report hub' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/report.png',
    'Lex' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/recommendation.png',
    'User Journey' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/user-jounery.png',
    'User Jounery' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/user-jounery.png',
    'Onboarding' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/onboarding.png',
    'Linkedin Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/linkedin.png',
    'LinkedIn Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/linkedin.png',
    'linkedin dashbaord' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/linkedin.png',
    'Teads Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/teads.png',
    'Getting started' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/onboarding.png',
    'Getting Started' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/onboarding.png',
    'Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/category-2.png',
    'UTM Parameters Guidelines' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/UTM-.png'
);

// Fetch categories dynamically
$wp_categories = get_categories(array(
    'parent'     => 0, // Only fetch top-level categories
    'hide_empty' => 1, // Only show categories with articles (subcategories shown within them)
    'exclude'    => array(1), // Exclude 'Uncategorized' (usually ID 1)
    'orderby'    => 'ID',
    'order'      => 'ASC'
));

$sidebar_sections = array();
foreach ($wp_categories as $cat) {
    // Default fallback icon
    $icon_url = get_template_directory_uri() . '/assets/img/clone.svg';
    if (isset($custom_icons[$cat->name])) {
        $icon_url = $custom_icons[$cat->name];
    }
    
    $sidebar_sections[] = array(
        'slug'    => $cat->slug,
        'title'   => $cat->name,
        'icon'    => $icon_url,
        'id'      => $cat->slug,
        'term_id' => $cat->term_id
    );
}

// Custom sort logic based on the 'Sidebar Order' meta field
if (!empty($sidebar_sections)) {
    usort($sidebar_sections, function($a, $b) {
        $term_id_a = isset($a['term_id']) ? intval($a['term_id']) : 0;
        $term_id_b = isset($b['term_id']) ? intval($b['term_id']) : 0;

        $order_a = $term_id_a ? get_term_meta($term_id_a, '_cmgalaxy_sidebar_order', true) : '';
        $order_b = $term_id_b ? get_term_meta($term_id_b, '_cmgalaxy_sidebar_order', true) : '';

        $val_a = ($order_a !== '') ? intval($order_a) : 9999;
        $val_b = ($order_b !== '') ? intval($order_b) : 9999;

        if ($val_a == $val_b) {
            return strcasecmp($a['title'], $b['title']);
        }
        return ($val_a < $val_b) ? -1 : 1;
    });
}

// Fallback if no categories are found (unlikely but safe)
if (empty($sidebar_sections)) {
    $sidebar_sections = array(
        array(
            'slug'  => 'getting-started',
            'title' => 'Getting Started',
            'icon'  => 'getting start.svg',
            'id'    => 'getting-started',
        )
    );
}
?>

<?php if ($is_single_post) : ?>
<!-- ==================== SINGLE POST SIDEBAR ==================== -->
<div class="modern-sidebar single-post-sidebar">


    <div class="sidebar-content">
        <!-- Back Button -->
        <?php
        // Get the link to the top-level category of this post
        $back_url = home_url('/'); // Fallback
        $cats = get_the_category(get_queried_object_id());
        if (!empty($cats)) {
            $top_cat = $cats[0];
            // Walk up to find the top-level parent
            while ($top_cat->parent) {
                $top_cat = get_category($top_cat->parent);
            }
            $back_url = get_category_link($top_cat->term_id);
        }
        ?>
        <a href="<?php echo esc_url($back_url); ?>" class="sidebar-back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Back to categories</span>
        </a>


        <?php
        // Find and display only the active category
        $active_section_found = false;
        foreach ($sidebar_sections as $section) :
            if (in_array($section['slug'], $current_categories)) :
                $active_section_found = true;
        ?>
                <!-- Active Category Header (Always expanded) -->
                <div class="active-cat-header">
                    <div class="cat-icon">
                        <img src="<?php echo esc_url($section['icon']); ?>" alt="<?php echo esc_attr($section['title']); ?>" style="width: 22px; height: 22px; object-fit: contain;">
                    </div>
                    <div class="cat-title">
                        <?php echo esc_html($section['title']); ?>
                    </div>
                </div>

                <!-- Articles in this category -->
                <div class="cat-articles">
                    <?php
                    $cat_obj = get_category_by_slug($section['slug']);
                    $cat_id = $cat_obj ? $cat_obj->term_id : 0;
                    
                    // Fetch and display subcategories first
                    $subcats = get_categories(array(
                        'parent'     => $cat_id,
                        'hide_empty' => 0
                    ));
                    if (function_exists('cmgalaxy_sort_terms_by_order')) {
                        usort($subcats, 'cmgalaxy_sort_terms_by_order');
                    }
                    
                    if (!empty($subcats)) {
                        foreach ($subcats as $subcat) {
                            $is_subcat_active = in_array($subcat->slug, $current_categories);
                            $subcat_wrapper_class = $is_subcat_active ? 'cat-article-item active-article' : 'cat-article-item';
                            ?>
                            <div class="<?php echo esc_attr($subcat_wrapper_class); ?>" style="padding-left: 20px;">
                                <a href="<?php echo esc_url(get_category_link($subcat->term_id)); ?>" title="<?php echo esc_attr($subcat->name); ?>" style="display: flex; justify-content: space-between; align-items: center; width: 100%; overflow: hidden;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;"><?php echo esc_html($subcat->name); ?></span>
                                    <span style="font-size: 11px; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #94a3b8; font-weight: 500; margin-left: 8px; flex-shrink: 0;"><?php echo esc_html($subcat->count); ?></span>
                                </a>
                            </div>
                            <?php
                        }
                    }

                    // Only show articles if we are not on a category page (i.e. we are on a single post)
                    if (!is_category()) :
                        $cat_args = array(
                            'cat'            => $cat_id,
                            'posts_per_page' => -1,
                            'orderby'        => 'date',
                            'order'          => 'ASC',
                        );
                        $cat_query = new WP_Query($cat_args);

                        if ($cat_query->have_posts()) :
                            while ($cat_query->have_posts()) : $cat_query->the_post();
                                $is_current = (get_the_ID() == $current_post_id);
                        ?>
                            <div class="cat-article-item <?php echo $is_current ? 'active-article' : ''; ?>">
                                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></a>
                            </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <div class="cat-article-item">
                                <span style="color: #94a3b8; font-size: 13px;">No articles found</span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
        <?php
            endif;
        endforeach;

        // Fallback: if no matching section found, show first category
        if (!$active_section_found && !empty($current_categories)) :
            $first_cat = get_category_by_slug($current_categories[0]);
            if ($first_cat) :
            $fallback_icon_url = get_template_directory_uri() . '/assets/img/link.svg';
            if (isset($custom_icons[$first_cat->name])) {
                $fallback_icon_url = $custom_icons[$first_cat->name];
            }
        ?>
                <div class="active-cat-header">
                    <div class="cat-icon">
                        <img src="<?php echo esc_url($fallback_icon_url); ?>" alt="<?php echo esc_attr($first_cat->name); ?>" style="width: 22px; height: 22px; object-fit: contain;">
                    </div>
                    <div class="cat-title">
                        <?php echo esc_html($first_cat->name); ?>
                    </div>
                </div>

                <div class="cat-articles">
                    <?php
                    // Only show articles if we are not on a category page (i.e. we are on a single post)
                    if (!is_category()) :
                        $cat_args = array(
                            'cat'            => $first_cat->term_id,
                            'posts_per_page' => 20,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        );
                        $cat_query = new WP_Query($cat_args);

                        if ($cat_query->have_posts()) :
                            while ($cat_query->have_posts()) : $cat_query->the_post();
                                $is_current = (get_the_ID() == $current_post_id);
                        ?>
                            <div class="cat-article-item <?php echo $is_current ? 'active-article' : ''; ?>">
                                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></a>
                            </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <div class="cat-article-item">
                                <span style="color: #94a3b8; font-size: 13px;">No articles found</span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
        <?php
            endif;
        endif;
        ?>
    </div>
</div>

<script>
function toggleCatArticles(header) {
    var articles = header.nextElementSibling;
    while (articles && !articles.classList.contains('cat-articles')) {
        articles = articles.nextElementSibling;
    }
    var toggle = header.querySelector('.cat-toggle');
    if (articles) {
        articles.classList.toggle('collapsed');
    }
    if (toggle) {
        toggle.classList.toggle('open');
    }
}
</script>

<?php else : ?>
<!-- ==================== DEFAULT SIDEBAR (All Categories) ==================== -->
<div class="modern-sidebar cmgalaxy-sortable-top">
    <div class="sidebar-content">
        <?php foreach ($sidebar_sections as $section) :
            // Get the category object to ensure we have the correct ID
            $slug_to_check = $section['slug'];
            $cat_obj = get_category_by_slug($slug_to_check);
            if ( ! $cat_obj ) $cat_obj = get_category_by_slug(strtolower($slug_to_check));
            if ( ! $cat_obj && is_numeric($section['id']) ) $cat_obj = get_term($section['id'], 'category');
            if ( ! $cat_obj ) $cat_obj = get_term_by('name', $section['title'], 'category');
            
            $cat_id = $cat_obj ? $cat_obj->term_id : 0;
            
            // Fetch subcategories
            $subcats = get_categories(array(
                'parent'     => $cat_id,
                'hide_empty' => 0 // Set to 0 so we can see newly added subcategories even without posts
            ));
            if (function_exists('cmgalaxy_sort_terms_by_order')) {
                usort($subcats, 'cmgalaxy_sort_terms_by_order');
            }
            
            $has_subcats = !empty($subcats);
            
            // Check if current page is in this category OR any of its subcategories
            $is_active_cat = in_array($section['slug'], $current_categories);
            if (!$is_active_cat && $has_subcats) {
                foreach ($subcats as $sub) {
                    if (in_array($sub->slug, $current_categories)) {
                        $is_active_cat = true;
                        break;
                    }
                    $sub_subcats_check = get_categories(array('parent' => $sub->term_id, 'hide_empty' => 0));
                    foreach ($sub_subcats_check as $ss) {
                        if (in_array($ss->slug, $current_categories)) {
                            $is_active_cat = true;
                            break 2;
                        }
                    }
                }
            }
            
            $header_class = 'section-header' . ($has_subcats ? ' expandable' : '') . ($is_active_cat ? ' active' : '');
            $content_class = 'section-content' . ($is_active_cat && $has_subcats ? ' expanded' : '');
            $expand_class = 'expand-icon' . ($is_active_cat ? ' expanded' : '');
        ?>
        <div class="sidebar-section" data-term-id="<?php echo esc_attr($cat_id); ?>">
            <div class="<?php echo esc_attr($header_class); ?>" data-target="<?php echo esc_attr($section['id']); ?>">
                <div class="section-icon">
                    <img src="<?php echo esc_url($section['icon']); ?>" alt="<?php echo esc_attr($section['title']); ?> Icon" style="width: 22px; height: 22px; object-fit: contain;">
                </div>
                <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="section-title" style="text-decoration: none; color: inherit; display: block; flex: 1;"><?php echo esc_html($section['title']); ?></a>
                <?php if ($has_subcats) : ?>
                <span class="<?php echo esc_attr($expand_class); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 6L9 12.0001L15 18" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="16" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <?php endif; ?>
            </div>

            <?php if ($has_subcats) : ?>
            <div class="<?php echo esc_attr($content_class); ?> cmgalaxy-sortable-sub" id="<?php echo esc_attr($section['id']); ?>">
                <?php
                
                if (!empty($subcats)) {
                    foreach ($subcats as $subcat) {
                        // Fetch sub-subcategories (3rd level categories)
                        $sub_subcats = get_categories(array(
                            'parent'     => $subcat->term_id,
                            'hide_empty' => 0
                        ));
                        if (function_exists('cmgalaxy_sort_terms_by_order')) {
                            usort($sub_subcats, 'cmgalaxy_sort_terms_by_order');
                        }
                        
                        $has_sub_subcats = !empty($sub_subcats);
                        
                        // Check if any sub-subcat is active to expand the parent subcat
                        $is_any_sub_subcat_active = false;
                        if ($has_sub_subcats) {
                            foreach ($sub_subcats as $ss) {
                                if (in_array($ss->slug, $current_categories)) {
                                    $is_any_sub_subcat_active = true;
                                    break;
                                }
                            }
                        }

                        $is_subcat_active = in_array($subcat->slug, $current_categories) || $is_any_sub_subcat_active;
                        
                        $subcat_wrapper_class = 'subsection-item';
                        if ($is_subcat_active) $subcat_wrapper_class .= ' current-page';
                        if ($has_sub_subcats) $subcat_wrapper_class .= ' expandable-subcat';
                        
                        $subcat_font_weight = $is_subcat_active ? 'bold' : '500';
                        $subcat_target_id = 'subcat-' . $subcat->term_id;
                        ?>
                        <div class="cmgalaxy-subcat-wrapper" data-term-id="<?php echo esc_attr($subcat->term_id); ?>">
                            <div class="<?php echo esc_attr($subcat_wrapper_class); ?>" style="padding-left: 55px; display: flex; align-items: center; padding-top: 8px; padding-bottom: 8px; cursor: pointer;" data-target="<?php echo esc_attr($subcat_target_id); ?>">
                                <a href="<?php echo esc_url(get_category_link($subcat->term_id)); ?>" class="subsection-title" style="color:#475569; font-weight:<?php echo $subcat_font_weight; ?>; font-size: 14px; text-decoration: none; flex: 1; padding-right: 10px; display: flex; align-items: center; justify-content: space-between; width: 100%; overflow: hidden;" title="<?php echo esc_attr($subcat->name); ?>">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;"><?php echo esc_html($subcat->name); ?></span>
                                    <span style="font-size: 11px; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #94a3b8; font-weight: 500; margin-left: 8px; flex-shrink: 0;"><?php echo esc_html($subcat->count); ?></span>
                                </a>
                                <?php if ($has_sub_subcats) : ?>
                                <span class="expand-icon-subcat" style="color: #64748b; margin-right: 15px; display: inline-flex; transition: transform 0.3s; transform: <?php echo $is_subcat_active ? 'rotate(270deg)' : 'rotate(180deg)'; ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M15 6L9 12.0001L15 18" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="16" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php
                            if ($has_sub_subcats) {
                                $sub_subcat_display = $is_subcat_active ? 'block' : 'none';
                                echo '<div class="sub-subcategories cmgalaxy-sortable-sub-sub" id="' . esc_attr($subcat_target_id) . '" style="display: ' . $sub_subcat_display . '; padding-left: 0; margin-bottom: 10px; border-left: 1px solid #e2e8f0; margin-left: 55px;">';
                                foreach ($sub_subcats as $sub_subcat) {
                                    $is_sub_subcat_active = in_array($sub_subcat->slug, $current_categories);
                                    $sub_subcat_color = $is_sub_subcat_active ? '#3B82F6' : '#64748b';
                                    $sub_subcat_weight = $is_sub_subcat_active ? '500' : '400';
                                    ?>
                                    <div class="sidebar-sub-subcat-item" data-term-id="<?php echo esc_attr($sub_subcat->term_id); ?>" style="padding: 6px 0 6px 8px; position: relative;">
                                    <?php if ($is_sub_subcat_active): ?>
                                        <div style="position: absolute; left: -1px; top: 0; bottom: 0; width: 2px; background: #3B82F6;"></div>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url(get_category_link($sub_subcat->term_id)); ?>" style="color: <?php echo $sub_subcat_color; ?>; font-size: 13.5px; text-decoration: none; font-weight: <?php echo $sub_subcat_weight; ?>; display: flex; align-items: center; justify-content: space-between; line-height: 1.4; width: 100%; overflow: hidden;">
                                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;"><?php echo esc_html($sub_subcat->name); ?></span>
                                        <span style="font-size: 11px; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #94a3b8; font-weight: 500; margin-left: 8px; flex-shrink: 0;"><?php echo esc_html($sub_subcat->count); ?></span>
                                    </a>
                                </div>
                                <?php
                            }
                            echo '</div>';
                        }
                        ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle expandable sections
    const expandableHeaders = document.querySelectorAll('.section-header.expandable');

    expandableHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') return;

            const targetId = this.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const expandIcon = this.querySelector('.expand-icon');

            if (content) {
                const isExpanding = !content.classList.contains('expanded');

                // If we are opening a section, close all others first
                if (isExpanding) {
                    expandableHeaders.forEach(otherHeader => {
                        if (otherHeader !== this) {
                            const otherTargetId = otherHeader.getAttribute('data-target');
                            const otherContent = document.getElementById(otherTargetId);
                            const otherIcon = otherHeader.querySelector('.expand-icon');
                            
                            otherHeader.classList.remove('active');
                            if (otherContent) otherContent.classList.remove('expanded');
                            if (otherIcon) {
                                otherIcon.style.transform = 'rotate(180deg)';
                                otherIcon.classList.remove('expanded');
                            }
                        }
                    });
                }

                // Toggle the clicked section
                content.classList.toggle('expanded');
                this.classList.toggle('active');

                if (content.classList.contains('expanded')) {
                    expandIcon.style.transform = 'rotate(270deg)';
                    expandIcon.classList.add('expanded');
                } else {
                    expandIcon.style.transform = 'rotate(180deg)';
                    expandIcon.classList.remove('expanded');
                }
            }
        });
    });

    // Handle non-expandable sections
    const nonExpandableHeaders = document.querySelectorAll('.section-header:not(.expandable)');
    nonExpandableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            console.log('Clicked:', this.querySelector('.section-title').textContent);
        });
    });

    // Handle expandable subcategories
    const expandableSubcats = document.querySelectorAll('.expandable-subcat');
    expandableSubcats.forEach(subcat => {
        subcat.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') return;
            const targetId = this.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const expandIcon = this.querySelector('.expand-icon-subcat');
            
            if (content) {
                const isExpanding = content.style.display === 'none' || content.style.display === '';
                
                if (isExpanding) {
                    content.style.display = 'block';
                    if (expandIcon) expandIcon.style.transform = 'rotate(270deg)';
                } else {
                    content.style.display = 'none';
                    if (expandIcon) expandIcon.style.transform = 'rotate(180deg)';
                }
            }
        });
    });

    // Auto-scroll to active item on load
    const activeItem = document.querySelector('.current-page:not(.expandable-subcat), .active-article');
    if (activeItem) {
        // If it's inside a subcategory, ensure the subcategory is open
        const parentSubcatContent = activeItem.closest('.subcat-content');
        if (parentSubcatContent) {
            parentSubcatContent.style.display = 'block';
            const toggleHeader = document.querySelector(`[data-target="${parentSubcatContent.id}"]`);
            if (toggleHeader) {
                const icon = toggleHeader.querySelector('.expand-icon-subcat');
                if (icon) icon.style.transform = 'rotate(270deg)';
            }
        }
        
        // Scroll into view (centered to avoid sticky headers)
        setTimeout(() => {
            activeItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }
});
</script>

<?php if (current_user_can('manage_categories')): ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function handleSort(evt) {
        let container = evt.from;
        let items = container.children;
        let orderedIds = [];
        for (let i = 0; i < items.length; i++) {
            let termId = items[i].getAttribute('data-term-id');
            if (termId) orderedIds.push(termId);
        }
        
        if (orderedIds.length > 0) {
            let formData = new FormData();
            formData.append('action', 'cmgalaxy_update_category_order');
            formData.append('ordered_ids', JSON.stringify(orderedIds));
            
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    console.log('Order updated');
                }
            });
        }
    }

    // Initialize Top Level Sortable
    let topContainer = document.querySelector('.cmgalaxy-sortable-top .sidebar-content');
    if (!topContainer) topContainer = document.querySelector('.cmgalaxy-sortable-top'); // Fallback
    if (topContainer) {
        new Sortable(topContainer, {
            animation: 150,
            onEnd: handleSort,
            filter: '.single-post-sidebar' // Don't allow sorting in single post view if they mixed it
        });
    }

    // Initialize Subcategories Sortable
    let subContainers = document.querySelectorAll('.cmgalaxy-sortable-sub');
    subContainers.forEach(container => {
        new Sortable(container, {
            animation: 150,
            onEnd: handleSort
        });
    });

    // Initialize Sub-subcategories Sortable
    let subSubContainers = document.querySelectorAll('.cmgalaxy-sortable-sub-sub');
    subSubContainers.forEach(container => {
        new Sortable(container, {
            animation: 150,
            onEnd: handleSort
        });
    });
});
</script>
<?php endif; ?>

<?php endif; ?>
