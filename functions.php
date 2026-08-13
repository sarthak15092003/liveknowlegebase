<?php
/**
 * docy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package docy
 */


if (!function_exists('docy_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function docy_setup () {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on gull, use a find and replace
         * to change 'gull' to the name of your theme in all the template files.
         */
        load_theme_textdomain('docy', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Enable excerpt support for page
        add_post_type_support('page', 'excerpt');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
        add_theme_support('woocommerce');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        add_post_type_support('forum', 'thumbnail');
        add_post_type_support('topic', 'thumbnail');
        add_theme_support('post-formats', array( 'video', 'quote', 'link' ));

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'main_menu' => esc_html__('Main Menu', 'docy'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        add_editor_style('style-editor.css');
        add_theme_support('responsive-embeds');

        // Disable Sidebar widgets block editor
        if ( docy_opt('is_sidebar_editor') == 'classic' ) {
            add_filter('use_widgets_block_editor', '__return_false');
        }
    }
endif;
add_action('after_setup_theme', 'docy_setup');


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function docy_content_width () {
    $GLOBALS[ 'content_width' ] = apply_filters('docy_content_width', 1270);
}

add_action('after_setup_theme', 'docy_content_width', 0);


/**
 * Constants
 * Defining default asset paths
 */
define('DOCY_DIR_CSS', get_template_directory_uri() . '/assets/css');
define('DOCY_DIR_JS', get_template_directory_uri() . '/assets/js');
define('DOCY_DIR_VEND', get_template_directory_uri() . '/assets/vendors');
define('DOCY_DIR_IMG', get_template_directory_uri() . '/assets/img');
define('DOCY_DIR_FONT', get_template_directory_uri() . '/assets/fonts');

$my_theme = wp_get_theme('docy');
define('DOCY_VERSION', $my_theme->Version);

/**
 * Required plugins activation
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Theme's helper functions
 */
require get_template_directory() . '/inc/classes/Docy_helper.php';

/**
 * Theme settings
 */
// Include CSF
require_once get_template_directory() . '/inc/csf/classes/setup.class.php';
if ( class_exists('CSF') ) {

    // Theme settings
	require_once get_template_directory() . '/inc/admin/settings-options.php';

    // Metaboxes
	require get_template_directory() . '/inc/meta/all-meta-boxes.php';
	require get_template_directory() . '/inc/meta/meta-register-login.php';
	require get_template_directory() . '/inc/meta/meta-post-format.php';
	require get_template_directory() . '/inc/meta/remove-meta.php';

}

// ACF widgets abd blocks
require get_template_directory() . '/inc/acf/widgets-blocks.php';

/**
 * Theme's filters and actions
 */
require get_template_directory() . '/inc/filter_actions.php';
require get_template_directory() . '/inc/woo_config.php';
require get_template_directory() . '/inc/ajax_actions.php';
require get_template_directory() . '/inc/reg_process.php';

/**
 * Classes
 */
require get_template_directory() . '/inc/classes/Docy_Mobile_Nav_Walker.php';
require get_template_directory() . '/inc/classes/Docy_Walker_Comment.php';
require get_template_directory() . '/inc/classes/Docy_Forum_Class.php';

//updater
require get_template_directory() . '/inc/classes/Docy_base.php';
require get_template_directory() . '/inc/classes/Docy_register_theme.php';
require get_template_directory() . '/inc/classes/Docy_update_checker.php';

/**
 * Admin notices
 */
if ( is_admin() ) {
    require_once get_template_directory() . '/inc/admin/notices/welcome-notice.php';
}

/**
 * Configure one click demo
 */
require get_template_directory() . '/inc/demo_config.php';

/**
 * Required plugins activation
 */
require get_template_directory() . '/inc/tgm/plugin_activation.php';

/**
 * Bootstrap Nav Walker
 */
require get_template_directory() . '/inc/classes/Docy_Nav_Walker.php';
require get_template_directory() . '/inc/classes/Docy_Walker_Docs.php';

/**
 * Register Sidebar Areas
 */
require get_template_directory() . '/inc/sidebars.php';

/**
 * Admin Page
 */
require get_template_directory() . '/inc/Admin.php';

/**
 * Dynamically fix broken localhost image paths in blog content
 * This is useful if posts were created in a local environment and moved to production
 */
function docy_fix_localhost_paths($content) {
    if (is_admin()) return $content;
    
    // Get current site URL
    $site_url = get_site_url();
    
    // Define the local path to search for
    $local_path = 'http://localhost/knowlege'; 
    
    // Replace hardcoded localhost paths with dynamic site URL
    $content = str_replace($local_path, $site_url, $content);
    
    return $content;
}
add_filter('the_content', 'docy_fix_localhost_paths', 99);
add_filter('the_excerpt', 'docy_fix_localhost_paths', 99);
add_filter('post_thumbnail_html', 'docy_fix_localhost_paths', 99);

/**
 * Change the order of EazyDocs posts to ascending order by date
 * (newly uploaded articles come last)
 */
function docy_docs_custom_order( $query ) {
    if ( is_admin() ) return;
    
    $post_type = $query->get( 'post_type' );
    $is_docs = false;
    
    if ( $post_type === 'docs' || ( is_array( $post_type ) && in_array( 'docs', $post_type ) ) ) {
        $is_docs = true;
    } elseif ( $query->get('post_type') == '' && ( $query->is_tax('doc_dir') || $query->is_post_type_archive('docs') ) ) {
        $is_docs = true;
    }

    if ( $is_docs ) {
        $query->set( 'order', 'ASC' );
        $query->set( 'orderby', 'menu_order date' );
        $query->set( 'posts_per_page', -1 );
        $query->set( 'nopaging', true );
    }

    // Disable pagination and show all posts on category archives
    if ( ! is_admin() && $query->is_main_query() && $query->is_category() ) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'nopaging', true );
    }
}
add_action( 'pre_get_posts', 'docy_docs_custom_order', 99 );

/**
 * Force breadcrumb font size
 */
function docy_force_breadcrumb_css() {
    echo '<style>
        .breadcrumb .breadcrumb-item a,
        .breadcrumb .breadcrumb-item.active,
        .breadcrumb .breadcrumb-item,
        .ezd-breadcrumb,
        .ezd-breadcrumb li,
        .ezd-breadcrumb li a,
        .page_breadcrumb .breadcrumb-item a,
        .page_breadcrumb .breadcrumb-item,
        ul.breadcrumb li, ul.breadcrumb li a,
        .bbp-breadcrumb {
            font-size: 12px !important;
            letter-spacing: 0px !important;
            word-spacing: normal !important;
        }
        .breadcrumb-item+.breadcrumb-item,
        .ezd-breadcrumb li+li,
        ul.breadcrumb li+li {
            padding-left: 0 !important;
        }
        .breadcrumb-item+.breadcrumb-item::before,
        .ezd-breadcrumb li+li::before,
        ul.breadcrumb li+li::before {
            padding-right: 4px !important;
            padding-left: 4px !important;
        }
        /* Heading Font Size Overrides */
        h3, .h3,
        .blog_single_item h3, .blog_single_item .h3,
        .topic_comment_item h3, .topic_comment_item .h3,
        .shortcode_title h3, .shortcode_title .h3,
        .details_tab h3, .details_tab .h3,
        .topic-content h3, .topic-content .h3,
        .ezd-grid h3, .ezd-grid .h3,
        .entry-content h3, .entry-content .h3,
        .post-content h3, .post-content .h3,
        #post h3, #post .h3,
        article h3, article .h3,
        h3.wp-block-heading, .editor-content h3 {
            font-size: 18px !important;
        }
        h4, .h4,
        .blog_single_item h4, .blog_single_item .h4,
        .topic_comment_item h4, .topic_comment_item .h4,
        .shortcode_title h4, .shortcode_title .h4,
        .details_tab h4, .details_tab .h4,
        .topic-content h4, .topic-content .h4,
        .ezd-grid h4, .ezd-grid .h4,
        .entry-content h4, .entry-content .h4,
        .post-content h4, .post-content .h4,
        #post h4, #post .h4,
        article h4, article .h4 {
            font-size: 18px !important;
        }
        /* Bold font weight override for single posts */
        .single-post strong, .single-post b {
            font-weight: 700 !important;
        }
    </style>';
}
add_action('wp_head', 'docy_force_breadcrumb_css', 999);

// =====================================================================
// Custom Category Sidebar Order
// =====================================================================

// 1. Add "Sidebar Order" field to "Add New Category" screen
function cmgalaxy_category_add_order_field() {
    ?>
    <div class="form-field">
        <label for="cmgalaxy_sidebar_order"><?php _e( 'Sidebar Order', 'docy' ); ?></label>
        <input type="number" name="cmgalaxy_sidebar_order" id="cmgalaxy_sidebar_order" value="">
        <p class="description"><?php _e( 'Enter a number to order categories in the modern sidebar. Lower numbers appear first. (e.g. 10, 20, 30)', 'docy' ); ?></p>
    </div>
    <?php
}
add_action( 'category_add_form_fields', 'cmgalaxy_category_add_order_field' );

// 2. Add "Sidebar Order" field to "Edit Category" screen
function cmgalaxy_category_edit_order_field( $term ) {
    $order = get_term_meta( $term->term_id, '_cmgalaxy_sidebar_order', true );
    ?>
    <tr class="form-field">
        <th scope="row"><label for="cmgalaxy_sidebar_order"><?php _e( 'Sidebar Order', 'docy' ); ?></label></th>
        <td>
            <input type="number" name="cmgalaxy_sidebar_order" id="cmgalaxy_sidebar_order" value="<?php echo esc_attr( $order ); ?>">
            <p class="description"><?php _e( 'Enter a number to order categories in the modern sidebar. Lower numbers appear first. (e.g. 10, 20, 30)', 'docy' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'category_edit_form_fields', 'cmgalaxy_category_edit_order_field' );

// 3. Save the "Sidebar Order" field value
function cmgalaxy_save_category_order( $term_id ) {
    if ( isset( $_POST['cmgalaxy_sidebar_order'] ) ) {
        update_term_meta( $term_id, '_cmgalaxy_sidebar_order', sanitize_text_field( $_POST['cmgalaxy_sidebar_order'] ) );
    }
}
add_action( 'created_category', 'cmgalaxy_save_category_order' );
add_action( 'edited_category', 'cmgalaxy_save_category_order' );

// 4. Helper function to sort term objects based on the Sidebar Order meta
function cmgalaxy_sort_terms_by_order( $a, $b ) {
    // Safety check to prevent fatal errors if the items are not term objects
    if (!is_object($a) || !isset($a->term_id) || !is_object($b) || !isset($b->term_id)) {
        return 0;
    }

    $order_a = get_term_meta( $a->term_id, '_cmgalaxy_sidebar_order', true );
    $order_b = get_term_meta( $b->term_id, '_cmgalaxy_sidebar_order', true );
    
    // Default to 9999 if no order is set so they appear at the bottom
    $val_a = ($order_a !== '') ? intval($order_a) : 9999;
    $val_b = ($order_b !== '') ? intval($order_b) : 9999;
    
    if ( $val_a == $val_b ) {
        // Fallback to creation order (term_id) instead of alphabetical if order is the same
        return $a->term_id - $b->term_id;
    }
    return ( $val_a < $val_b ) ? -1 : 1;
}

// 5. AJAX handler for drag and drop sorting
function cmgalaxy_update_category_order_callback() {
    if ( ! current_user_can( 'manage_categories' ) ) {
        wp_send_json_error( 'Permission denied' );
    }

    if ( isset( $_POST['ordered_ids'] ) ) {
        $ordered_ids = json_decode( stripslashes( $_POST['ordered_ids'] ), true );
        if ( is_array( $ordered_ids ) ) {
            $order = 10;
            foreach ( $ordered_ids as $term_id ) {
                update_term_meta( intval( $term_id ), '_cmgalaxy_sidebar_order', $order );
                $order += 10;
            }
            wp_send_json_success( 'Order updated' );
        }
    }
    wp_send_json_error( 'Invalid data' );
}
add_action( 'wp_ajax_cmgalaxy_update_category_order', 'cmgalaxy_update_category_order_callback' );

// Enqueue modern sidebar styles in head
add_action("wp_enqueue_scripts", function() {
    // Version .59 = force cache bust for sidebar featured image link (2026-08-13)
    // Depends on 'docy-root' (style.css) so our overrides load last and win
    wp_enqueue_style("cmgalaxy-sidebar-modern", get_template_directory_uri() . "/assets/css/sidebar-modern.css", array('docy-root'), DOCY_VERSION . '.59');
});

// Remove empty paragraphs (only &nbsp; or whitespace) generated by the editor or API
function cmgalaxy_remove_empty_nbsp_paragraphs($content) {
    // Regex matches <p> with any/no style, containing only &nbsp; entities or whitespace
    $content = preg_replace('/<p[^>]*>(\s|&nbsp;|\xc2\xa0)*<\/p>/i', '', $content);
    return $content;
}
add_filter('the_content', 'cmgalaxy_remove_empty_nbsp_paragraphs', 20);

// =====================================================================
// CM Custom Tooltip for Sidebar (injected via wp_footer for reliability)
// =====================================================================
function cm_sidebar_tooltip_script() {
    ?>
    <style>
    .cm-custom-tooltip {
        position: fixed;
        background: #ffffff;
        color: #111827;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 400;
        white-space: nowrap;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        z-index: 999999;
        pointer-events: none;
        border: 1px solid #e5e7eb;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.15s ease, visibility 0.15s ease;
        font-family: 'Instrument Sans', -apple-system, sans-serif;
    }
    .cm-custom-tooltip.cm-tooltip-visible {
        opacity: 1;
        visibility: visible;
    }
    .cm-custom-tooltip::before {
        content: '';
        position: absolute;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        border-width: 5px 6px 5px 0;
        border-style: solid;
        border-color: transparent #ffffff transparent transparent;
        filter: drop-shadow(-2px 0 1px rgba(0,0,0,0.04));
    }
    </style>
    <script>
    (function() {
        // Create tooltip element
        var tt = document.createElement('div');
        tt.className = 'cm-custom-tooltip';
        document.body.appendChild(tt);

        var hideTimer = null;

        document.addEventListener('mouseover', function(e) {
            var link = e.target.closest('.cm-tooltip-target');
            if (!link) return;

            var text = link.getAttribute('data-cm-tooltip');
            if (!text) return;

            if (hideTimer) { clearTimeout(hideTimer); hideTimer = null; }

            tt.textContent = text;
            tt.classList.add('cm-tooltip-visible');

            var rect = link.getBoundingClientRect();
            var sidebar = link.closest('.modern-sidebar');
            var sidebarRight = sidebar ? sidebar.getBoundingClientRect().right : rect.right;

            tt.style.top = Math.max(10, (rect.top + (rect.height / 2) - (tt.offsetHeight / 2))) + 'px';
            tt.style.left = (sidebarRight - 15) + 'px';
        }, true);

        document.addEventListener('mouseout', function(e) {
            var link = e.target.closest('.cm-tooltip-target');
            if (!link) return;

            // Don't hide if moving to a child inside the same link
            if (e.relatedTarget && link.contains(e.relatedTarget)) return;

            hideTimer = setTimeout(function() {
                tt.classList.remove('cm-tooltip-visible');
            }, 50);
        }, true);
    })();
    </script>
    <?php
}
add_action('wp_footer', 'cm_sidebar_tooltip_script', 999);

// =====================================================================
// CM Feedback System
// =====================================================================

// Function to create the custom table on admin init (if it doesn't exist)
function cm_feedback_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cm_feedback';
    $charset_collate = $wpdb->get_charset_collate();

    // Only run if the table doesn't exist to avoid performance hit
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id mediumint(9) NOT NULL,
            post_title varchar(255) NOT NULL,
            ip_address varchar(100) NOT NULL,
            vote varchar(10) NOT NULL,
            reason text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}
add_action( 'admin_init', 'cm_feedback_create_table' );

// Handle AJAX submission
add_action( 'wp_ajax_cm_submit_feedback', 'cm_handle_submit_feedback' );
add_action( 'wp_ajax_nopriv_cm_submit_feedback', 'cm_handle_submit_feedback' );

function cm_handle_submit_feedback() {
    global $wpdb;

    $post_id    = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    $post_title = isset( $_POST['post_title'] ) ? sanitize_text_field( $_POST['post_title'] ) : '';
    $vote       = isset( $_POST['vote'] ) ? sanitize_text_field( $_POST['vote'] ) : '';
    $reason     = isset( $_POST['reason'] ) ? sanitize_text_field( $_POST['reason'] ) : '';
    $ip_address = $_SERVER['REMOTE_ADDR'];

    if ( ! $post_id || ! $vote || ! $reason ) {
        wp_send_json_error( 'Missing required fields.' );
    }

    $table_name = $wpdb->prefix . 'cm_feedback';

    $inserted = $wpdb->insert(
        $table_name,
        array(
            'post_id'    => $post_id,
            'post_title' => $post_title,
            'ip_address' => $ip_address,
            'vote'       => $vote,
            'reason'     => $reason,
        )
    );

    if ( $inserted ) {
        wp_send_json_success( 'Feedback saved successfully.' );
    } else {
        wp_send_json_error( 'Failed to save feedback.' );
    }
}

// Add Admin Menu Page
add_action( 'admin_menu', 'cm_feedback_admin_menu' );

function cm_feedback_admin_menu() {
    add_menu_page(
        'CM Feedback',
        'CM Feedback',
        'manage_options',
        'cm-feedback-entries',
        'cm_feedback_entries_page',
        'dashicons-feedback',
        30
    );
}

// Display the Admin Page
function cm_feedback_entries_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cm_feedback';
    
    $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC" );
    
    echo '<div class="wrap">';
    echo '<h1>CM Feedback Entries</h1>';
    
    if ( $results ) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>ID</th>';
        echo '<th>Date</th>';
        echo '<th>Post Title</th>';
        echo '<th>IP Address</th>';
        echo '<th>Vote</th>';
        echo '<th>Reason</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        
        foreach ( $results as $row ) {
            echo '<tr>';
            echo '<td>' . esc_html( $row->id ) . '</td>';
            echo '<td>' . esc_html( $row->created_at ) . '</td>';
            echo '<td><a href="' . get_permalink( $row->post_id ) . '" target="_blank">' . esc_html( $row->post_title ) . '</a></td>';
            echo '<td>' . esc_html( $row->ip_address ) . '</td>';
            echo '<td>' . esc_html( ucfirst( $row->vote ) ) . '</td>';
            echo '<td>' . esc_html( $row->reason ) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No feedback entries found.</p>';
    }
    
    echo '</div>';
}
