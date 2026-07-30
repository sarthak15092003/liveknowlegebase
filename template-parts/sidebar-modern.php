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
        'slug'  => $cat->slug,
        'title' => $cat->name,
        'icon'  => $icon_url,
        'id'    => $cat->slug
    );
}

// Custom sort logic based on desired category order
$desired_order = array(
    'getting started',
    'account management',
    'dashboard',
    'user management',
    'master dashboard',
    'main dashboard',
    'funnel attribution',
    'integrations',
    'google dashboard',
    'meta dashboard',
    'linkedin dashbaord',
    'linkedin dashboard',
    'teads dashboard',
    'pinterest dashboard',
    'dv360 dashboard',
    'amazon dashboard',
    'recommendation',
    'milestone',
    'notification center',
    'ticket/ support',
    'tickets / supports',
    'report hub',
    'reporting hub',
    'lex',
    'user jounery',
    'user journey'
);

usort($sidebar_sections, function($a, $b) use ($desired_order) {
    $title_a = strtolower(trim($a['title']));
    $title_b = strtolower(trim($b['title']));
    
    $pos_a = array_search($title_a, $desired_order);
    $pos_b = array_search($title_b, $desired_order);
    
    if ($pos_a === false) $pos_a = 999;
    if ($pos_b === false) $pos_b = 999;
    
    if ($pos_a == $pos_b) {
        return strcmp($a['title'], $b['title']);
    }
    
    return $pos_a - $pos_b;
});

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
    <style>
        /* Back button styling */
        .sidebar-back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            margin-bottom: 8px;
            color: #64748b;
            font-size: 13px;
            font-weight: 500 !important;
            text-decoration: none !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .sidebar-back-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
            text-decoration: none !important;
        }
        .sidebar-back-btn svg {
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .sidebar-back-btn:hover svg {
            transform: translateX(-3px);
        }
        .sidebar-back-btn span {
            font-weight: 500 !important;
        }

        /* Single post sidebar - active category header */
        .single-post-sidebar .active-cat-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            margin-bottom: 4px;
            border-radius: 10px;
            background: #dbeafe2b;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease;
            user-select: none;
        }
        .single-post-sidebar .active-cat-header:hover {
            background: #dbeafe55;
        }
        .single-post-sidebar .active-cat-header .cat-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .single-post-sidebar .active-cat-header .cat-icon img {
            width: 22px;
            height: 22px;
            border: none !important;
        }
        .single-post-sidebar .active-cat-header .cat-title {
            flex: 1;
            font-size: 14px;
            color: #161c52 !important;
            font-weight: 500 !important;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .single-post-sidebar .active-cat-header .cat-toggle {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #94a3b8;
            transition: transform 0.3s ease, color 0.2s ease;
            transform: rotate(180deg);
        }
        .single-post-sidebar .active-cat-header .cat-toggle.open {
            transform: rotate(270deg);
            color: #1e40af;
        }

        /* Active category article list */
        .single-post-sidebar .cat-articles {
            padding: 4px 0 0 0;
            max-height: 2500px;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease, padding 0.3s ease;
            opacity: 1;
        }
        .single-post-sidebar .cat-articles.collapsed {
            max-height: 0;
            opacity: 0;
            padding: 0;
        }
        .single-post-sidebar .cat-article-item {
            position: relative;
            padding: 8px 12px 8px 20px;
            border-left: 2px solid transparent;
            transition: all 0.2s ease;
            margin-left: 16px;
        }
        .single-post-sidebar .cat-article-item:hover {
            border-left-color: #93c5fd;
            background: #f8fafc;
            border-radius: 0 6px 6px 0;
        }
        .single-post-sidebar .cat-article-item.active-article {
            border-left-color: transparent;
            background: #dbeafe2b;
            border-radius: 6px;
        }
        .single-post-sidebar .cat-article-item a {
            display: block;
            font-size: 13px;
            font-weight: 400 !important;
            color: #64748b;
            text-decoration: none !important;
            line-height: 1.5;
            transition: color 0.2s ease;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .single-post-sidebar .cat-article-item:hover a {
            color: #1e293b;
        }
        .single-post-sidebar .cat-article-item.active-article a {
            color: #007bff !important;
        }

        /* Divider */
        .sidebar-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 12px 16px;
        }
    </style>

    <div class="sidebar-content">
        <!-- Back Button -->
        <?php
        // Get the link to the All Categories page
        $back_url = home_url('/all-categories/'); // Fallback URL
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-all-categories.php'
        ));
        if (!empty($pages)) {
            $back_url = get_permalink($pages[0]->ID);
        }
        $back_label = 'All Categories';
        ?>
        <a href="<?php echo esc_url($back_url); ?>" class="sidebar-back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>All Categories</span>
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
<div class="modern-sidebar">
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
        <div class="sidebar-section">
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
            <div class="<?php echo esc_attr($content_class); ?>" id="<?php echo esc_attr($section['id']); ?>">
                <?php
                
                if (!empty($subcats)) {
                    foreach ($subcats as $subcat) {
                        // Fetch sub-subcategories (3rd level categories)
                        $sub_subcats = get_categories(array(
                            'parent'     => $subcat->term_id,
                            'hide_empty' => 0
                        ));
                        
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
                            echo '<div class="sub-subcategories" id="' . esc_attr($subcat_target_id) . '" style="display: ' . $sub_subcat_display . '; padding-left: 0; margin-bottom: 10px; border-left: 1px solid #e2e8f0; margin-left: 55px;">';
                            foreach ($sub_subcats as $sub_subcat) {
                                $is_sub_subcat_active = in_array($sub_subcat->slug, $current_categories);
                                $sub_subcat_color = $is_sub_subcat_active ? '#3B82F6' : '#64748b';
                                $sub_subcat_weight = $is_sub_subcat_active ? '500' : '400';
                                ?>
                                <div class="sidebar-sub-subcat-item" style="padding: 6px 0 6px 8px; position: relative;">
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
<?php endif; ?>
