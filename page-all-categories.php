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
		max-width: 800px;
		margin: 60px auto;
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
		background: #f0f7ff;
		color: #0366d6;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 24px;
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
		font-size: 15px;
		color: #586069;
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

	/* Different icon background colors based on a pseudo-random attribute (for visual variety) */
	.tw-category-card:nth-child(2n) .tw-category-icon { background: #f1f8eb; color: #28a745; }
	.tw-category-card:nth-child(3n) .tw-category-icon { background: #fdf3f4; color: #cb2431; }
	.tw-category-card:nth-child(4n) .tw-category-icon { background: #fff5e6; color: #dbab09; }
</style>

<div class="tw-category-container">
	<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
		<?php foreach ( $terms as $term ) : ?>
			<?php 
			// Fallback icon based on slug or term ID
			$icon_html = '<i class="fa fa-folder-open"></i>'; // Default FontAwesome icon if docy uses FA
			
			// You can implement custom logic here to assign different icons per category slug
			if ( strpos( $term->slug, 'start' ) !== false ) {
				$icon_html = '<i class="fa fa-play-circle"></i>';
			} elseif ( strpos( $term->slug, 'market' ) !== false ) {
				$icon_html = '<i class="fa fa-globe"></i>';
			} elseif ( strpos( $term->slug, 'analytic' ) !== false || strpos( $term->slug, 'summary' ) !== false ) {
				$icon_html = '<i class="fa fa-bar-chart"></i>';
			} elseif ( strpos( $term->slug, 'creative' ) !== false ) {
				$icon_html = '<i class="fa fa-pencil-square-o"></i>';
			}

			$term_link = get_term_link( $term );
			
			// Approximate metadata (Authors and Article Count)
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
							<div class="tw-author-avatar">A</div>
							<div class="tw-author-avatar" style="background:#28a745">B</div>
							<div class="tw-author-avatar" style="background:#6f42c1">C</div>
						</div>
						<span>By Authors &bull; <?php echo esc_html( $articles_text ); ?></span>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	<?php else : ?>
		<p>No categories found.</p>
	<?php endif; ?>
</div>

<?php
get_footer();
