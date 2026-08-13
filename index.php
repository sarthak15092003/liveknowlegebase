<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package docy
 */

get_header();



// Breadcrumb now moved to header card - no duplicate needed here

// Prepare category header data for ?cat pages or category archives
$cat_header_data = null;
$is_category_page = (isset($_GET['cat']) && !empty($_GET['cat'])) || is_category();

if ($is_category_page) {
	if (isset($_GET['cat']) && !empty($_GET['cat'])) {
		$current_cat_id = intval($_GET['cat']);
	} else {
		$current_cat_id = get_queried_object_id();
	}

	$cat_header_data = docy_prepare_category_header_data($current_cat_id);
	$GLOBALS['cat_header_data'] = $cat_header_data;
}

if (!function_exists('docy_render_category_header_card')) {
	// This function is now in template-functions.php
}

$opt = get_option('docy_opt');
$has_sidebar = false; // Hide sidebar for entire site
$blog_column = $has_sidebar ? '8' : '12';
$blog_layout_opt = !empty($opt['blog_layout']) ? $opt['blog_layout'] : 'list';
$blog_layout = !empty($_GET['blog_layout']) ? $_GET['blog_layout'] : $blog_layout_opt;

// Force cards layout on the blog home so items can sit side-by-side
if (is_home() && $blog_layout === 'list') {
	$blog_layout = 'blog_category';
}

// Force blog_category layout for category pages with ?cat parameter or category archives
if ($is_category_page) {
	$blog_layout = 'blog_category';
}


if ($blog_layout == 'list') {
	$sec_class = 'doc_blog_classic_area sec_pad';
	$is_row = '';
} elseif ($blog_layout == 'grid') {
	$sec_class = 'doc_blog_grid_area sec_pad';
	$is_row = '';
} elseif ($blog_layout == 'blog_category') {
	$sec_class = 'doc_blog_grid_area';
	$is_row = ' blog_grid_tab';
} else {
	$sec_class = 'doc_blog_classic_area sec_pad';
	$is_row = '';
}

// Is Sticky - but not for category pages
if ($blog_layout == 'blog_category' && !$is_category_page) {
	while (have_posts()):
		the_post();
		get_template_part('template-parts/contents/content-sticky');
	endwhile;
}
?>

<section class="<?php echo esc_attr($sec_class) ?>"
	style="<?php echo $is_category_page ? 'margin-top: 0 !important;' : ''; ?>">
	<?php
	// Debug: Show current layout and category
	echo '<!-- Debug: blog_layout = ' . $blog_layout . ', is_category_page = ' . ($is_category_page ? 'yes' : 'no') . ' -->';

	if ($blog_layout == 'blog_category' && !$is_category_page) {
		// Dynamic Category Grid (top-level categories) - only show on home page, not category pages
		$docy_categories = get_categories([
			'hide_empty' => true,
			'parent' => 0,
		]);

		// Custom sort order for categories
		$custom_order = [
			'Getting started' => 1,
			'Getting Started' => 1,
			'Account Management' => 2,
			'Account Mangement' => 2,
			'User Management' => 3,
			'User Manegement' => 3,
			'Master Dashboard' => 4,
			'Main Dashboard' => 5,
			'Funnel Attribution' => 6,
			'Integrations' => 7,
			'Google Dashboard' => 8,
			'Meta Dashboard' => 9,
			'Linkedin Dashboard' => 10,
			'LinkedIn Dashboard' => 10,
			'linkedin dashbaord' => 10,
			'Teads Dashboard' => 11,
			'Pinterest Dashboard' => 12,
			'DV360 Dashboard' => 13,
			'Amazon Dashboard' => 14,
			'Recommendation' => 15,
			'Milestone' => 16,
			'milestone' => 16,
			'Notification Center' => 17,
			'Ticket/ Support' => 18,
			'Tickets / Supports' => 18,
			'Tickets / Support' => 18,
			'Report hub' => 19,
			'Reporting Hub' => 19,
			'Reporting HUb' => 19,
			'Lex' => 20,
			'User Jounery' => 21,
			'User Journey' => 21
		];

		usort($docy_categories, function($a, $b) use ($custom_order) {
			$pos_a = isset($custom_order[$a->name]) ? $custom_order[$a->name] : 999;
			$pos_b = isset($custom_order[$b->name]) ? $custom_order[$b->name] : 999;
			if ($pos_a == $pos_b) {
				return strcmp($a->name, $b->name);
			}
			return $pos_a - $pos_b;
		});
		if (!empty($docy_categories)) {
			echo '<div class="container mb-4">
                <div class="row">
                <div class="col-12">
				<p class="category-intro lead mb-10" style="font-size: 18px;
    font-weight: 400;
    line-height: 170%;
    text-align: center;
    margin: 10px auto 50px;
    max-width: 900px;"> Your CMGalaxy questions, answered. Learn how to connect your platforms, read your attribution data, analyse creatives, and turn insights into better ROAS.</p>
                </div>
                </div>
                </div>';
			echo '<div class="container">
			<div class="row g-4 mb-4">';

			// Add "All Categories" entry card
			if (is_array($docy_categories) && !empty($docy_categories)) {
				$first_cat = reset($docy_categories);
				$all_cat_link = ($first_cat && isset($first_cat->term_id)) ? home_url('/?cat=' . $first_cat->term_id . '&infinite=1') : '#';
				echo '<div class="col-md-6 col-lg-6" style="display: none;">';
				echo '<a class="card h-100 category-card border text-reset text-decoration-none" href="' . esc_url($all_cat_link) . '" style="background: #ffffff;">';
				echo '<div class="card-body" style="padding: 24px;">';
				echo '<div class="category-card__icon custom-icon" aria-hidden="true" style="margin-bottom: 16px;">'
					. '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
					. '<path d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16l-6-3-6 3V4z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>'
					. '</svg>'
					. '</div>';
				echo '<h5 class="card-title mb-2">All Categories</h5>';
				echo '<p class="card-text text-muted mb-3">Browse all documentation topics in a continuous, easy-to-read scroll.</p>';
				echo '<div class="category-card__meta small text-primary fw-bold">';
				echo '<span>Start Reading →</span>';
				echo '</div>';
				echo '</div>';
				echo '</a>';
				echo '</div>';
			}

			foreach ($docy_categories as $cat) {
				$cat_link = get_category_link($cat->term_id);
				$cat_name = esc_html($cat->name);
				$cat_desc = esc_html(wp_trim_words(category_description($cat), 18, '…'));
				$cat_count = intval($cat->count);

				// Build simple author byline from latest posts in this category
				$author_names = [];
				$author_ids = [];
				$author_query = new WP_Query([
					'post_type' => 'post',
					'posts_per_page' => 10,
					'cat' => $cat->term_id,
					'ignore_sticky_posts' => true,
					'fields' => 'ids',
				]);
				if ($author_query->have_posts()) {
					foreach ($author_query->posts as $post_id) {
						$aid = (int) get_post_field('post_author', $post_id);
						if ($aid && !in_array($aid, $author_ids, true)) {
							$author_ids[] = $aid;
							$author_names[] = get_the_author_meta('display_name', $aid);
						}
						if (count($author_names) >= 3) {
							break;
						}
					}
				}
				wp_reset_postdata();
				$byline = '';
				$author_count_total = count($author_ids);
				if ($author_count_total === 1) {
					$byline = sprintf(__('By %s', 'docy'), esc_html($author_names[0]));
				} elseif ($author_count_total === 2) {
					$byline = sprintf(__('By %1$s and %2$s', 'docy'), esc_html($author_names[0]), esc_html($author_names[1]));
				} elseif ($author_count_total > 2) {
					$byline = sprintf(__('By %1$s and %2$s others', 'docy'), esc_html($author_names[0]), number_format_i18n($author_count_total - 1));
				}
				echo '<div class="col-md-4 col-lg-4">';
				echo '<a class="card h-100 category-card border text-reset text-decoration-none" href="' . esc_url($cat_link) . '">';
				echo '<div class="card-body" style="padding: 24px;">';
				$custom_icons = [
					'User Manegement' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/usermanagement.png',
					'User Management' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/usermanagement.png',
					'Account Mangement' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/account-management-1.png',
					'Account Management' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/account-management-1.png',
					'Master Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/master-dashboard.png',
					'Main Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/category-2.png',
					'Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/category-2.png',
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
					'Tickets / Supports' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/support.png',
					'Reporting HUb' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/report.png',
					'Reporting Hub' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/report.png',
					'Lex' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/recommendation.png',
					'User Journey' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/user-jounery.png',
					'Onboarding' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/onboarding.png',
					'Linkedin Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/linkedin.png',
					'LinkedIn Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/linkedin.png',
					'Teads Dashboard' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/teads.png',
					'Getting started' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/onboarding.png',
					'Getting Started' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/onboarding.png',
					'UTM Parameters Guidelines' => 'https://docs.cmgalaxy.com/wp-content/uploads/2026/07/UTM-.png'
				];

				if (array_key_exists($cat_name, $custom_icons)) {
					echo '<div class="category-card__icon" aria-hidden="true" style="margin-bottom: 16px;">';
					echo '<img src="' . esc_url($custom_icons[$cat_name]) . '" alt="' . esc_attr($cat_name) . '" style="width: 32px; height: 32px; object-fit: contain;">';
				} else {
					echo '<div class="category-card__icon custom-icon" aria-hidden="true" style="margin-bottom: 16px;">';
					echo '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
						. '<path d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16l-6-3-6 3V4z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>'
						. '</svg>';
				}
				echo '</div>';
				echo '<h5 class="card-title mb-2">' . $cat_name . '</h5>';
				echo '<p class="card-text text-muted mb-3">' . $cat_desc . '</p>';
				echo '<div class="category-card__meta small text-muted">';
				$author_icon_path = 'fallback';
				$author_icon_url = 'https://docs.cmgalaxy.com/wp-content/uploads/2026/06/cropped-Group-1000004539-300x300-1.png';
				$author_markup = '';
				if ($author_icon_path) {
					$author_markup .= '<span class="category-card__author-icon">'
						. '<img class="category-card__author-icon-img" src="' . esc_url($author_icon_url) . '" alt="" />'
						. '</span>';
				} else {
					$author_markup .= '<span class="category-card__author-icon" aria-hidden="true">'
						. '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
						. '<path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v2h16v-2c0-2.761-3.582-5-8-5z" fill="currentColor"/>'
						. '</svg>'
						. '</span>';
				}
				if ($byline) {
					$author_markup .= '<span class="category-card__byline">' . esc_html($byline) . '</span>';
				}
				if ($author_markup) {
					echo '<div class="category-card__author d-flex align-items-center gap-2">' . $author_markup . '</div>';
				}
				echo '<span>' . sprintf(_n('%s article', '%s articles', $cat_count, 'docy'), number_format_i18n($cat_count)) . '</span>';
				echo '</div>';
				echo '</div>';
				echo '</a>';
				echo '</div>';
			}
			echo '</div></div>';
			?>
			<section class="container highest-rated-section" style="display: none;">
				<div class="text-center mb-5">
					<h2 class="highest-rated-title" style="font-weight:600;">Highest rated articles</h2>
				</div>
				<div class="row g-4">
					<div class="col-md-4">
						<div class="highest-card h-100">
							<div class="highest-card__icon" aria-hidden="true">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/act.png'); ?>" alt=""
									width="380" height="225" loading="lazy" />
							</div>
							<h3 class="highest-card__title">Act on Data in CMGalaxy</h3>
							<p class="highest-card__desc">Automate actions, trigger workflows, and extend HubSpot logic to fit
								your business.</p>
							<ul class="highest-card__links list-unstyled">
								<li><a href="#">Custom Coded Workflows</a></li>
								<li><a href="#">Custom Action Builders</a></li>
								<li><a href="#">Gated Content</a></li>
								<li><a href="#">Conversations API</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-4">
						<div class="highest-card h-100">
							<div class="highest-card__icon" aria-hidden="true">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/vizulize.png'); ?>"
									alt="" width="380" height="225" loading="lazy" />
							</div>
							<h3 class="highest-card__title">Visualize Data in CMGalaxy</h3>
							<p class="highest-card__desc">Add custom UI elements (like CRM cards) to display your data right
								inside of CMGalaxy.</p>
							<ul class="highest-card__links list-unstyled">
								<li><a href="#">Custom Cards</a></li>
								<li><a href="#">App settings page</a></li>
								<li><a href="#">Custom Quote Templates</a></li>
								<li><a href="#">Custom Calling Extensions</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-4">
						<div class="highest-card h-100">
							<div class="highest-card__icon" aria-hidden="true">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/get.png'); ?>" alt=""
									width="380" height="225" loading="lazy" />
							</div>
							<h3 class="highest-card__title">Get Data In and Out of CMGalaxy</h3>
							<p class="highest-card__desc">Automate actions, trigger workflows, and extend HubSpot logic to fit
								your business.</p>
							<ul class="highest-card__links list-unstyled">
								<li><a href="#">Import/Export Data</a></li>
								<li><a href="#">Custom Channels</a></li>
								<li><a href="#">CRM Object Sync</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<?php
		}
		?>
		<section class="home-cta-banner" style="padding: 80px 0 80px !important;">
			<div class="container">
				<div class="home-cta-banner__inner">
					<div class="home-cta-banner__copy">
						<h2 class="home-cta-banner__title">See How We Helped Businesses Like Yours <span>Grow 3x
							</span>Faster.</h2>
						<p class="home-cta-banner__subtitle">Let’s build a performance-driven ad strategy that works for
							your business.</p>
					</div>
					<div class="home-cta-banner__actions">
						<a class="home-cta-banner__primary" href="https://cmgalaxy.com/book-a-demo">Book a Demo</a>
						<a class="home-cta-banner__secondary" href="https://platform.cmgalaxy.com/sign-up">
							<span class="home-cta-banner__icon" aria-hidden="true">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/message.svg'); ?>"
									alt="" width="24" height="24">
							</span>
							Try CMGalaxy
						</a>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
	?>
	<?php
	// Show container for category pages or non-home pages
	$show_container = !(is_home() && $blog_layout == 'blog_category' && !$is_category_page);
	if ($show_container): ?>
		<div class="container">
			<div class="row <?php echo esc_attr($is_row) ?>">
				<?php
				/**
				 * Render posts based on the selected category or default query.
				 * 
				 * @return void
				 */
				function extracted(): void
				{
					// Sanitize category input from URL parameters.
					$category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';

					if ($category) {
						// Create a custom query if a specific category is selected.
						$cat_posts = new WP_Query([
							'post_type' => 'post',
							'posts_per_page' => -1,
							'category_name' => $category,
							'ignore_sticky_posts' => true,
						]);

						// Output posts from the custom query.
						if ($cat_posts->have_posts()) {
							echo '<div class="col-lg-12">';
							echo '<div class="row">';
							while ($cat_posts->have_posts()):
								$cat_posts->the_post();
								get_template_part('template-parts/contents/content-grid');
							endwhile;
							echo '</div>';
							echo '</div>';
						}
						wp_reset_postdata(); // Reset global post data after custom query.
			
					} else {
						// Default query loop if no category is selected.
						echo '<div class="col-lg-12">';
						echo '<div class="row">';
						while (have_posts()):
							the_post();
							get_template_part('template-parts/contents/content-grid');
						endwhile;
						echo '</div>';
						echo '</div>';
					}
				}

				if ($blog_layout == 'list') {
					// Check if this is a category page with ?cat parameter
					$show_cat_sidebar = $is_category_page;

					if ($show_cat_sidebar) {
						// Reset the query to start from the beginning
						rewind_posts();
						?>
						<!-- Sidebar Column for Category Page -->
						<div class="col-lg-3 mb-4 category-left-sidebar-col">

							<?php get_template_part('template-parts/sidebar-modern'); ?>
						</div>

						<!-- Content Column -->
						<div class="col-lg-9 category-main-col">
							<?php
							if (isset($GLOBALS['cat_header_data']) && !empty($GLOBALS['cat_header_data'])) {
								docy_render_category_header_card($GLOBALS['cat_header_data']);
							}
							?>
							<div class="row">
								<?php
								get_template_part('template-parts/contents/content-category-detail');
								?>
							</div>
							<?php // Docy_helper()->pagination(); ?>
						</div>
						<?php
					} else {
						// Normal list layout without sidebar
						if ($has_sidebar) {
							get_sidebar();
						}
						?>
						<div class="col-lg-<?php echo esc_attr($blog_column) ?> pe-4">
							<?php
							while (have_posts()):
								the_post();
								get_template_part('template-parts/contents/content', get_post_format());
							endwhile;
							Docy_helper()->pagination();
							?>
						</div>
						<?php
					}
				} elseif ($blog_layout == 'grid') {
					if ($has_sidebar) {
						get_sidebar();
					}
					// Grid layout content removed - not needed for category pages
				} elseif ($blog_layout == 'blog_category') {
					// Add sidebar for category pages with ?cat parameter OR pretty permalink category archives
					$show_sidebar = (isset($_GET['cat']) && !empty($_GET['cat'])) || is_category();
					$current_cat_id = 0;

					if (isset($_GET['cat']) && !empty($_GET['cat'])) {
						$current_cat_id = intval($_GET['cat']);
					} elseif (is_category()) {
						$current_cat_id = get_queried_object_id();
					}

					if ($show_sidebar) {
						// Debug: Add comment to check if this section is reached
						echo '<!-- Category sidebar section reached for cat: ' . $current_cat_id . ' -->';

						// Apply 3-column layout with both sidebars to all category pages
						if ($show_sidebar) {
							echo '<!-- Category ' . $current_cat_id . ' special layout with left modern sidebar and right image sidebar -->';
							?>
							<!-- Left Modern Sidebar for Cat 3 -->
							<div class="mb-4 category-left-sidebar-col"
								style="flex: 0 0 20% !important;max-width: 20% !important;">

								<?php get_template_part('template-parts/sidebar-modern'); ?>
							</div>


							<!-- Content Column for Cat 3 -->
							<div class="category-main-col category-main-with-right" id="category-posts-container"
								data-current-cat="<?php echo esc_attr($current_cat_id); ?>"
								data-cat-slug="<?php echo esc_attr(get_category($current_cat_id)->slug); ?>">
								<!-- Top Loading Indicator -->
								<div id="infinite-scroll-loader-top"
									style="display: none; text-align: center; height: 100px; padding: 20px 0;">
									<div class="spinner-border text-primary" role="status"></div>
									<p class="mt-2 text-muted" style="font-size: 14px;">Loading previous category...</p>
								</div>
								<?php
								if (isset($GLOBALS['cat_header_data']) && !empty($GLOBALS['cat_header_data'])) {
									// Replace the old card with the single post style header
									$cat_title = $GLOBALS['cat_header_data']['name'];
									$cat_count = $GLOBALS['cat_header_data']['count'];
									?>
									<nav aria-label="breadcrumb" class="mb-4" style="margin-bottom: 15px;">
										<ol class="custom-breadcrumb" style="display: flex !important; flex-wrap: wrap !important; align-items: center !important; padding: 0 !important; margin-bottom: 15px !important; list-style: none; gap: 5px;">
											<li style="padding: 0; margin: 0; font-size: 11px !important;"><a href="<?php echo esc_url(home_url('/')); ?>" style="color: #3b82f6; text-decoration: none; font-size: 11px !important;">Home</a></li>
											<li style="color: #94a3b8; padding: 0; margin: 0; font-size: 11px !important;">/</li>
											<li class="active" aria-current="page" style="color: #484a61 !important; padding: 0; margin: 0; font-size: 11px !important;"><?php echo esc_html($cat_title); ?></li>
										</ol>
									</nav>
									
									<h1 class="post-title mb-3" style="font-size: 36px; font-weight: 700; color: #111827; line-height: 1.2; margin-top: 20px;"><?php echo esc_html($cat_title); ?></h1>
									
									<div class="post-author-meta-box d-flex align-items-center mb-3 mt-3" style="gap: 8px; padding: 2px 0;">
										<div class="author-avatar" style="width: 18px; height: 18px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
											<img src="https://docs.cmgalaxy.com/wp-content/uploads/2026/06/cropped-Group-1000004539-300x300-1.png" alt="Author Avatar" class="rounded-circle" style="width: 18px; height: 18px; object-fit: contain; border: none !important; display: block;">
										</div>
										<div class="author-info" style="line-height: 1.4; color: #6b7280;">
											<div style="font-size: 14px; display: flex; align-items: center;">By &nbsp;<span style="color: #4b5563; font-weight: 500;">CMGalaxy</span> &nbsp;&middot;&nbsp; <?php echo esc_html($cat_count); ?> articles</div>
										</div>
									</div>
									<?php
								}
								?>
								<div class="row category-posts-row"
									data-cat-slug="<?php echo esc_attr(get_category($current_cat_id)->slug); ?>">
									<?php
									// Render posts and subcategories for category page
									get_template_part('template-parts/contents/content-category-detail');
									?>
								</div>

								<!-- Loading Indicator -->
								<div id="infinite-scroll-loader" style="display: none; text-align: center; padding: 20px;">
									<div class="spinner-border text-primary" role="status">
										<span class="visually-hidden">Loading next category...</span>
									</div>
									<p class="mt-2 text-muted">Loading next category...</p>
								</div>

								<?php
								// Show pagination if infinite scroll is NOT active
								// if (!(isset($_GET['infinite']) && $_GET['infinite'] == '1')) {
								//	Docy_helper()->pagination();
								// }
								?>
							</div>

							<!-- Right Image Sidebar for Cat 3 -->
							<div class="mb-4 category-right-sidebar-col" style="flex: 0 0 25% !important; max-width: 25% !important;">
								<div class="cat-3-right-sidebar">
									<div class="sidebar-widget">
										<!-- <h5 class="widget-title">Featured Image</h5> -->
										<?php
										// Array of random images from assets folder
										$random_images = array(

											get_template_directory_uri() . '/assets/img/sidebarimg.png',

										);

										// Select a random image
										$random_image = $random_images[array_rand($random_images)];
										?>
										<div class="random-image-container">
											<a href="https://cmgalaxy.com/book-a-demo" target="_blank" rel="noopener noreferrer">
												<img src="<?php echo esc_url($random_image); ?>" alt="Book a Demo - CMGalaxy"
													class="img-fluid rounded">
											</a>
										</div>
									</div>

									<?php /* Sidebar CTA Card - commented out
							   <div class="sidebar-cta-card">
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
								   <div class="sidebar-cta-text">
									   <p class="sidebar-cta-eyebrow">New to CMGalaxy?</p>
									   <p class="sidebar-cta-body">Check our <a href="#" class="sidebar-cta-link">Get Started</a> guides.</p>
								   </div>
							   </div>
						   */ ?>

									<!-- <div class="sidebar-widget mt-4">
										<h5 class="widget-title">Quick Info</h5>
										<div class="quick-info-content">
											<p>Welcome to our knowledge base! Explore our articles and find the information you need.</p>
											<ul class="info-list">
												<li><i class="fa fa-check"></i> Comprehensive guides</li>
												<li><i class="fa fa-check"></i> Regular updates</li>
												<li><i class="fa fa-check"></i> Expert insights</li>
											</ul>
										</div>
									</div> -->
								</div>

							</div>
							<?php
						} // This closes the `if ( $category_id == 3 )` block
					} else {
						// No sidebar - use full width
						extracted();
						Docy_helper()->pagination();
					}

					echo '</div>';
					wp_reset_postdata();
				}
				?>
			</div>
		</div>
	<?php endif; ?>
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
</section>

<?php
get_footer();