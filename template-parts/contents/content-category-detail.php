<?php
/**
 * Category Detail Layout (replaces the standard content-grid loop)
 * Displays articles and subcategories matching the Triple Whale KB design.
 */

$current_cat_id = get_queried_object_id();
if ( isset( $_GET['cat'] ) && ! empty( $_GET['cat'] ) ) {
	$current_cat_id = intval( $_GET['cat'] );
}
if ( ! $current_cat_id ) {
	return;
}

?>

<style>
/* New styles for the Category Detail layout */
.tw-cat-detail-container {
    width: 100%;
}
.tw-cat-articles-card {
    background: #ffffff;
    border: 1px solid #e1e4e8;
    border-radius: 12px;
    margin-bottom: 24px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(27, 31, 35, 0.04);
}
.tw-article-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e1e4e8;
    text-decoration: none !important;
    transition: background-color 0.2s ease;
}
.tw-article-row:last-child {
    border-bottom: none;
}
.tw-article-row:hover {
    background-color: #f9f9f9;
}
.tw-article-content {
    padding-right: 20px;
}
.tw-article-title {
    font-size: 18px !important;
    font-weight: 600;
    color: #161C52 !important;
    margin: 0 0 8px 0;
    line-height: 1.35;
}
.tw-article-desc {
    font-size: 14px !important;
    color: #484A61 !important;
    margin: 0;
    line-height: 1.5;
}
.tw-article-arrow {
    color: #0366d6;
}

.tw-subcategory-header {
    padding: 24px 24px 16px;
    border-bottom: 1px solid #e1e4e8;
    background-color: #f8fafc;
    position: relative;
    cursor: pointer;
    transition: background-color 0.2s ease;
}
.tw-subcategory-header:hover {
    background-color: #f1f5f9;
}
.tw-subcategory-title {
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
}
.tw-subcategory-desc {
    font-size: 14px;
    color: #586069;
    margin: 0;
}
</style>

<div class="tw-cat-detail-container">
	<?php
	// 1. Render articles that belong directly to the current category
	$main_articles = new WP_Query([
		'post_type' => 'post',
		'category__in' => [ $current_cat_id ],
		'posts_per_page' => -1,
		'ignore_sticky_posts' => true,
	]);

	if ( $main_articles->have_posts() ) {
		echo '<div class="tw-cat-articles-card">';
		while ( $main_articles->have_posts() ) {
			$main_articles->the_post();
			?>
			<a href="<?php the_permalink(); ?>" class="tw-article-row">
				<div class="tw-article-content">
					<h4 class="tw-article-title"><?php the_title(); ?></h4>
					<p class="tw-article-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15, '...' ) ); ?></p>
				</div>
				<div class="tw-article-arrow">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</div>
			</a>
			<?php
		}
		echo '</div>';
	}
	wp_reset_postdata();

	// 2. Fetch and render subcategories
	$subcategories = get_terms([
		'taxonomy' => 'category',
		'parent'   => $current_cat_id,
		'hide_empty' => false,
	]);

	if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) {
		foreach ( $subcategories as $subcat ) {
			
			// Fetch articles for this subcategory
			$sub_articles = new WP_Query([
				'post_type' => 'post',
				'category__in' => [ $subcat->term_id ],
				'posts_per_page' => -1,
				'ignore_sticky_posts' => true,
			]);

			if ( $sub_articles->have_posts() ) {
				echo '<div class="tw-cat-articles-card">';
				
				// Subcategory Header (Make it distinct and clickable)
				echo '<div class="tw-subcategory-header" onclick="window.location.href=\''. esc_url(get_category_link($subcat->term_id)) .'\'">';
				echo '<h3 class="tw-subcategory-title">';
				// Add a folder icon to differentiate from articles
				echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>';
				echo esc_html( $subcat->name );
				echo '</h3>';
				if ( ! empty( $subcat->description ) ) {
					echo '<p class="tw-subcategory-desc" style="margin-left: 28px;">' . esc_html( $subcat->description ) . '</p>';
				} else {
                    echo '<p class="tw-subcategory-desc" style="margin-left: 28px;">Articles relating to ' . esc_html( $subcat->name ) . '</p>';
                }
				echo '</div>';

				// Articles in this subcategory
				while ( $sub_articles->have_posts() ) {
					$sub_articles->the_post();
					?>
					<a href="<?php the_permalink(); ?>" class="tw-article-row">
						<div class="tw-article-content">
							<h4 class="tw-article-title"><?php the_title(); ?></h4>
							<p class="tw-article-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15, '...' ) ); ?></p>
						</div>
						<div class="tw-article-arrow">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="9 18 15 12 9 6"></polyline>
							</svg>
						</div>
					</a>
					<?php
				}
				echo '</div>';
			}
			wp_reset_postdata();
		}
	}
	?>
</div>
