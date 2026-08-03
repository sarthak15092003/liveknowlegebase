<?php
/**
 * Template Name: All Categories List
 *
 * This custom template lists all categories in a layout similar to Triple Whale KB collections.
 *
 * @package docy
 */

get_header();

$taxonomy = 'category'; 

$terms = get_terms( array(
    'taxonomy'   => $taxonomy,
    'hide_empty' => false,
) );
?>

<div class="container" style="max-width: 100% !important; padding-left: 30px !important; padding-right: 30px !important;">
	<div class="row">
		<!-- Sidebar Column -->
		<div class="col-lg-3 mb-4 category-left-sidebar-col" style="flex: 0 0 20% !important; max-width: 20% !important;">
			<?php get_template_part('template-parts/sidebar-modern'); ?>
		</div>

		<!-- Content Column -->
		<div class="col-lg-9 category-main-col" style="flex: 0 0 80% !important; max-width: 80% !important; padding-top: 30px !important;">
			<nav aria-label="breadcrumb" class="mb-4" style="margin-bottom: 15px;">
				<ol class="custom-breadcrumb" style="display: flex !important; flex-wrap: wrap !important; align-items: center !important; padding: 0 !important; margin-bottom: 15px !important; list-style: none; gap: 5px;">
					<li style="padding: 0; margin: 0; font-size: 11px !important;"><a href="<?php echo esc_url(home_url('/')); ?>" style="color: #3b82f6; text-decoration: none; font-size: 11px !important;">Home</a></li>
					<li style="color: #94a3b8; padding: 0; margin: 0; font-size: 11px !important;">/</li>
					<li class="active" aria-current="page" style="color: #484a61 !important; padding: 0; margin: 0; font-size: 11px !important;">All Categories</li>
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
			
			$byline = 'By CMGalaxy';

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
						// Always use CMGalaxy logo as author avatar
						echo '<div class="tw-author-avatar" style="width: 24px; height: 24px; min-width: 24px; border-radius: 50%; overflow: hidden;"><img src="https://docs.cmgalaxy.com/wp-content/uploads/2026/06/cropped-Group-1000004539-300x300-1.png" alt="CMGalaxy" style="width: 100%; height: 100%; object-fit: cover;"></div>';
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
