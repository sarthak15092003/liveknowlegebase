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
