<?php
require_once('../../../wp-load.php');

$article_title = 'How to Validate and Filter Imported Data in CMGalaxy';
$query = new WP_Query([
    'post_type' => 'docs',
    'title' => $article_title,
    'posts_per_page' => 1
]);

if ($query->have_posts()) {
    $post = $query->posts[0];
    echo "Found article ID: {$post->ID}\n";
    echo "Post Status: {$post->post_status}\n";
    echo "Menu Order: {$post->menu_order}\n";
    echo "Post Parent: {$post->post_parent}\n";
    echo "Post Date: {$post->post_date}\n";
    
    // Get taxonomy terms
    $terms = wp_get_post_terms($post->ID, 'doc_dir');
    echo "Categories (doc_dir):\n";
    foreach($terms as $t) {
        echo "- {$t->name} (ID: {$t->term_id})\n";
    }
} else {
    echo "Article not found by title.\n";
}
