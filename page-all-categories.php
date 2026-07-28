<?php
/**
 * Template Name: All Categories List
 *
 * This custom template lists all categories in a layout similar to Triple Whale KB collections.
 *
 * @package docy
 */

get_header();

// You can change 'category' to 'doc_dir' if you are using Eazydocs for this!
$taxonomy = 'category'; 

$terms = get_terms( array(
    'taxonomy'   => $taxonomy,
    'hide_empty' => false,
) );
?>

<style>
	.tw-category-container {
		width: 100%;
		margin: 20px 0;
		padding: 0 20px;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
	}

	.tw-category-card {
		display: flex;
		align-items: flex-start;
		background: #ffffff;
		border: 1px solid #e1e4e8;
		border-radius: 12px;
		padding: 24px;
		margin-bottom: 20px;
		text-decoration: none !important;
		color: inherit !important;
		transition: all 0.2s ease;
		box-shadow: 0 1px 3px rgba(27, 31, 35, 0.04);
	}

	.tw-category-card:hover {
		box-shadow: 0 4px 12px rgba(27, 31, 35, 0.1);
		border-color: #d1d5da;
		transform: translateY(-2px);
	}

	.tw-category-icon {
		width: 56px;
		height: 56px;
		min-width: 56px;
		background: #ffffff; /* Removed blue background so image looks clean */
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-right: 24px;
	}

	.tw-category-content {
		flex-grow: 1;
		display: flex;
		flex-direction: column;
	}

	.tw-category-title {
		font-size: 18px;
		font-weight: 600;
		color: #24292e;
		margin: 0 0 6px 0;
		line-height: 1.3;
	}

	.tw-category-desc {
		font-size: 14px !important;
		color: #484A61 !important;
		margin: 0 0 16px 0;
		line-height: 1.5;
	}

	.tw-category-meta {
		display: flex;
		align-items: center;
		font-size: 13px;
		color: #6a737d;
	}

	.tw-authors-avatars {
		display: flex;
		margin-right: 12px;
	}

	.tw-author-avatar {
		width: 26px;
		height: 26px;
		border-radius: 50%;
		border: 2px solid #fff;
		margin-left: -8px;
		background: #0366d6;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 11px;
		font-weight: 600;
	}

	.tw-author-avatar:first-child {
		margin-left: 0;
	}

	.tw-author-avatar img {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		object-fit: cover;
	}


</style>

<div class="container" style="max-width: 100% !important; padding-left: 30px !important; padding-right: 30px !important;">
	<div class="row">
		<!-- Sidebar Column -->
		<div class="col-lg-3 mb-4 category-left-sidebar-col" style="flex: 0 0 20% !important; max-width: 20% !important;">
			<style>
				.modern-sidebar {
					background: #ffffff;
					border: none !important;
					box-shadow: none !important;
					border-radius: 8px;
					padding: 0;
					margin-bottom: 2rem;
					position: sticky;
					top: 110px;
					max-height: calc(100vh - 120px);
					overflow-y: auto;
				}
				@media (max-width: 1024px) {
					.category-left-sidebar-col {
						flex: 0 0 100% !important;
						max-width: 100% !important;
						display: none !important; /* Hide on mobile by default like category pages */
					}
					.category-main-col {
						flex: 0 0 100% !important;
						max-width: 100% !important;
					}
				}
			</style>
			<?php get_template_part('template-parts/sidebar-modern'); ?>
		</div>

		<!-- Content Column -->
		<div class="col-lg-9 category-main-col" style="flex: 0 0 80% !important; max-width: 80% !important;">
			<nav aria-label="breadcrumb" class="mb-4" style="margin-top: 50px;">
				<ol class="breadcrumb" style="font-size: 10px !important; gap: 5px !important; display: flex !important; flex-wrap: wrap !important; align-items: center !important; padding: 0 !important; margin-bottom: 15px !important; list-style: none;">
					<li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>" style="color: #3b82f6; text-decoration: none;">Home</a></li>
					<span style="color: #94a3b8; margin: 0 5px;">/</span>
					<li class="breadcrumb-item active" aria-current="page" style="color: #484a61 !important;">All Categories</li>
				</ol>
			</nav>
			
			<h1 class="post-title mb-4" style="font-size: 36px; font-weight: 700; color: #111827; line-height: 1.2; margin-top: 20px;">All Categories</h1>
			
			<div class="tw-category-container">
	<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
		<?php foreach ( $terms as $term ) : ?>
			<?php 
			// --- ICON LOGIC (Same as Home Page) ---
			$cat_name = $term->name;
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
				$icon_html = '<img src="' . esc_url($custom_icons[$cat_name]) . '" alt="' . esc_attr($cat_name) . '" style="width: 32px; height: 32px; object-fit: contain;">';
			} else {
				$icon_html = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
					. '<path d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16l-6-3-6 3V4z" stroke="#0052cc" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>'
					. '</svg>';
			}

			$term_link = get_term_link( $term );
			
			// --- AUTHOR LOGIC (Same as Home Page) ---
			$author_names = [];
			$author_ids = [];
			$author_query = new WP_Query([
				'post_type' => 'post', // Or whatever post type Eazydocs uses if taxonomy is doc_dir
				'posts_per_page' => 10,
				'cat' => $term->term_id,
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
			} else {
				$byline = 'By Authors';
			}

			// Approximate metadata (Article Count)
			$count = $term->count;
			$articles_text = sprintf( _n( '%s article', '%s articles', $count, 'docy' ), $count );
			?>
			
			<a href="<?php echo esc_url( $term_link ); ?>" class="tw-category-card">
				<div class="tw-category-icon">
					<?php echo $icon_html; ?>
				</div>
				<div class="tw-category-content">
					<h3 class="tw-category-title"><?php echo esc_html( $term->name ); ?></h3>
					
					<?php if ( ! empty( $term->description ) ) : ?>
						<p class="tw-category-desc"><?php echo esc_html( wp_trim_words( $term->description, 15, '...' ) ); ?></p>
					<?php else : ?>
						<p class="tw-category-desc">Explore our comprehensive guide and resources about <?php echo esc_html( strtolower($term->name) ); ?>.</p>
					<?php endif; ?>
					
					<div class="tw-category-meta">
						<div class="tw-authors-avatars">
							<?php 
							if ( !empty($author_ids) ) {
								foreach ($author_ids as $aid) {
									echo '<div class="tw-author-avatar">' . get_avatar($aid, 26) . '</div>';
								}
							} else {
								// Fallback if no authors found
								echo '<div class="tw-author-avatar" style="background:#0366d6">C</div>';
							}
							?>
						</div>
						<span><?php echo esc_html($byline); ?> &bull; <?php echo esc_html( $articles_text ); ?></span>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	<?php else : ?>
		<p>No categories found.</p>
	<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
