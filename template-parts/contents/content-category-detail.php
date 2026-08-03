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
	// Fetch subcategories
	$subcategories = get_terms([
		'taxonomy' => 'category',
		'parent'   => $current_cat_id,
		'hide_empty' => false,
	]);

	$has_subcategories = ! empty( $subcategories ) && ! is_wp_error( $subcategories );

	if ( $has_subcategories ) {
		// Display Subcategories as Accordions (Open by default)
		foreach ( $subcategories as $subcat ) {
            // Fetch articles for this subcategory
            $sub_articles = new WP_Query([
                'post_type' => 'post',
                'category__in' => [ $subcat->term_id ],
                'posts_per_page' => -1,
                'ignore_sticky_posts' => true,
            ]);

			echo '<div class="tw-cat-articles-card" style="margin-bottom: 16px;">';
			
			// Subcategory Header (acts as accordion toggle)
			echo '<div class="tw-subcategory-header" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === \'none\' ? \'block\' : \'none\';" style="cursor: pointer; padding: 16px;">';
			echo '<h3 class="tw-subcategory-title" style="display: flex; align-items: center; margin-bottom: 8px;">';
			// Add a folder icon to differentiate from articles
			echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; flex-shrink: 0;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>';
			echo '<span>' . esc_html( $subcat->name ) . '</span> <span style="font-size: 14px; color: #6b7280; font-weight: normal; margin-left: 5px;">(' . intval($subcat->count) . ' articles)</span>';
			echo '</h3>';
			if ( ! empty( $subcat->description ) ) {
				echo '<p class="tw-subcategory-desc" style="margin-left: 28px; margin-bottom: 0;">' . esc_html( $subcat->description ) . '</p>';
			} else {
                echo '<p class="tw-subcategory-desc" style="margin-left: 28px; margin-bottom: 0;">Articles relating to ' . esc_html( $subcat->name ) . '</p>';
            }
			echo '</div>'; // End Header

            // Articles List (Visible by default)
            echo '<div class="tw-subcategory-articles" style="display: block; border-top: 1px solid #e5e7eb;">';
            if ( $sub_articles->have_posts() ) {
                while ( $sub_articles->have_posts() ) {
                    $sub_articles->the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="tw-article-row">
                        <div class="tw-article-content">
                            <div class="tw-article-title" style="font-size: 16px; font-weight: 500 !important; color: #1f2937; margin: 0 0 4px 0;"><?php the_title(); ?></div>
                            <p class="tw-article-desc" style="margin: 0; color: #6b7280; font-size: 14px;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15, '...' ) ); ?></p>
                        </div>
                        <div class="tw-article-arrow">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <?php
                }
            } else {
                echo '<p style="padding: 16px; color: #6b7280; margin: 0; margin-left: 12px;">No articles found.</p>';
            }
            echo '</div>'; // End Articles List

			echo '</div>'; // End Card
            wp_reset_postdata();
		}
	} else {
		// No subcategories, render articles that belong directly to the current category
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
						<div class="tw-article-title" style="font-size: 16px; font-weight: 500 !important; color: #1f2937; margin: 0 0 4px 0;"><?php the_title(); ?></div>
						<p class="tw-article-desc" style="margin: 0; color: #6b7280; font-size: 14px;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15, '...' ) ); ?></p>
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
	?>
</div>
