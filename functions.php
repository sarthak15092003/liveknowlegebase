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
require get_template_directory() . '/inc/cmgalaxy-api-auth.php';
require get_template_directory() . '/inc/cmgalaxy-sso.php';

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
    // Version .67 = fix scrollbar always visible + remove conflict
    // Depends on 'docy-root' (style.css) so our overrides load last and win
    wp_enqueue_style("cmgalaxy-sidebar-modern", get_template_directory_uri() . "/assets/css/sidebar-modern.css", array('docy-root'), time());

    // Inject scrollbar CSS inline (always wins over any other CSS)
    $scrollbar_css = "
        .modern-sidebar {
            overflow-y: hidden !important;
        }
        .modern-sidebar:hover {
            overflow-y: auto !important;
        }
        .modern-sidebar::-webkit-scrollbar {
            width: 4px !important;
        }
        .modern-sidebar::-webkit-scrollbar-track {
            background: transparent !important;
        }
        .modern-sidebar::-webkit-scrollbar-thumb {
            background: #9ca3af !important;
            border-radius: 4px !important;
        }
        .modern-sidebar::-webkit-scrollbar-thumb:hover {
            background: #6b7280 !important;
        }
        .modern-sidebar::-webkit-scrollbar-button {
            display: none !important;
        }
    ";
    wp_add_inline_style('cmgalaxy-sidebar-modern', $scrollbar_css);
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
    @media (max-width: 1024px) {
        .cm-custom-tooltip,
        .cm-custom-tooltip.cm-tooltip-visible {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
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
            // Never show tooltip on mobile/tablets
            if (window.innerWidth <= 1024) return;

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
            if (window.innerWidth <= 1024) return;

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

    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id mediumint(9) NOT NULL,
            post_title varchar(255) NOT NULL,
            user_id bigint(20) DEFAULT 0 NOT NULL,
            user_name varchar(255) DEFAULT '' NOT NULL,
            user_email varchar(255) DEFAULT '' NOT NULL,
            ip_address varchar(100) NOT NULL,
            vote varchar(10) NOT NULL,
            reason text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    } else {
        // Auto-migration for existing installations
        $col_user_id = $wpdb->get_results( "SHOW COLUMNS FROM `{$table_name}` LIKE 'user_id'" );
        if ( empty( $col_user_id ) ) {
            $wpdb->query( "ALTER TABLE `{$table_name}` ADD COLUMN user_id bigint(20) DEFAULT 0 NOT NULL AFTER post_title" );
        }
        $col_user_name = $wpdb->get_results( "SHOW COLUMNS FROM `{$table_name}` LIKE 'user_name'" );
        if ( empty( $col_user_name ) ) {
            $wpdb->query( "ALTER TABLE `{$table_name}` ADD COLUMN user_name varchar(255) DEFAULT '' NOT NULL AFTER user_id" );
        }
        $col_user_email = $wpdb->get_results( "SHOW COLUMNS FROM `{$table_name}` LIKE 'user_email'" );
        if ( empty( $col_user_email ) ) {
            $wpdb->query( "ALTER TABLE `{$table_name}` ADD COLUMN user_email varchar(255) DEFAULT '' NOT NULL AFTER user_name" );
        }
    }
}
add_action( 'admin_init', 'cm_feedback_create_table' );

// Handle AJAX submission
add_action( 'wp_ajax_cm_submit_feedback', 'cm_handle_submit_feedback' );
add_action( 'wp_ajax_nopriv_cm_submit_feedback', 'cm_handle_submit_feedback' );

function cm_handle_submit_feedback() {
    global $wpdb;

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( 'You must be signed in to submit feedback.' );
    }

    $post_id    = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    $post_title = isset( $_POST['post_title'] ) ? sanitize_text_field( $_POST['post_title'] ) : '';
    $vote       = isset( $_POST['vote'] ) ? sanitize_text_field( $_POST['vote'] ) : '';
    $reason     = isset( $_POST['reason'] ) ? sanitize_textarea_field( $_POST['reason'] ) : '';
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Capture User ID, User Name, and User Email
    $user_id    = get_current_user_id();
    $user_name  = '';
    $user_email = '';

    if ( $user_id ) {
        $user_obj = wp_get_current_user();
        if ( $user_obj ) {
            $user_name  = ! empty( $user_obj->display_name ) ? $user_obj->display_name : $user_obj->user_login;
            $user_email = ! empty( $user_obj->user_email ) ? $user_obj->user_email : '';
        }
    }

    if ( ! $user_id && ! empty( $_POST['user_id'] ) ) {
        $user_id = intval( $_POST['user_id'] );
    }
    if ( empty( $user_name ) && ! empty( $_POST['user_name'] ) ) {
        $user_name = sanitize_text_field( $_POST['user_name'] );
    }
    if ( empty( $user_email ) && ! empty( $_POST['user_email'] ) ) {
        $user_email = sanitize_email( $_POST['user_email'] );
    }

    if ( ! $post_id || ! $vote || ! $reason ) {
        wp_send_json_error( 'Missing required fields.' );
    }

    $table_name = $wpdb->prefix . 'cm_feedback';

    $inserted = $wpdb->insert(
        $table_name,
        array(
            'post_id'    => $post_id,
            'post_title' => $post_title,
            'user_id'    => $user_id,
            'user_name'  => $user_name,
            'user_email' => $user_email,
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
        echo '<th>User Name</th>';
        echo '<th>Email</th>';
        echo '<th>IP Address</th>';
        echo '<th>Vote</th>';
        echo '<th>Reason</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        
        foreach ( $results as $row ) {
            $user_name_val = '—';
            if ( isset( $row->user_name ) && ! empty( $row->user_name ) ) {
                $user_name_val = esc_html( $row->user_name );
            } elseif ( isset( $row->user_id ) && $row->user_id > 0 ) {
                $u_info = get_userdata( $row->user_id );
                if ( $u_info ) {
                    $user_name_val = esc_html( $u_info->display_name ? $u_info->display_name : $u_info->user_login );
                }
            }
            
            $user_email_val = ( isset( $row->user_email ) && ! empty( $row->user_email ) ) ? esc_html( $row->user_email ) : '—';

            echo '<tr>';
            echo '<td>' . esc_html( $row->id ) . '</td>';
            echo '<td>' . esc_html( $row->created_at ) . '</td>';
            echo '<td><a href="' . get_permalink( $row->post_id ) . '" target="_blank">' . esc_html( $row->post_title ) . '</a></td>';
            echo '<td>' . $user_name_val . '</td>';
            echo '<td>' . $user_email_val . '</td>';
            echo '<td>' . esc_html( $row->ip_address ) . '</td>';
            $vote_val = strtolower( $row->vote );
            if ( $vote_val === 'yes' || $vote_val === 'like' ) {
                $vote_display = 'Like';
            } elseif ( $vote_val === 'no' || $vote_val === 'dislike' ) {
                $vote_display = 'Dislike';
            } else {
                $vote_display = ucfirst( $row->vote );
            }
            echo '<td>' . esc_html( $vote_display ) . '</td>';
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


/**
 * =========================================================================
 * CMGalaxy Post Restriction & Paywall Gate
 * =========================================================================
 */
function cmg_is_post_restricted_to_logged_in($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    if (!$post_id) {
        return false;
    }

    $rbcr_logged_in_only = get_post_meta($post_id, '_rbcr_logged_in_only', true);
    if ($rbcr_logged_in_only === '1' || $rbcr_logged_in_only === 1 || $rbcr_logged_in_only === true) {
        return true;
    }

    $rbcr_login_status = get_post_meta($post_id, '_rbcr_login_status', true);
    if (!empty($rbcr_login_status) && (strpos($rbcr_login_status, 'logged_in') !== false || $rbcr_login_status === 'logged_in_only')) {
        return true;
    }

    if (function_exists('content_control_user_can_view_post')) {
        if (!content_control_user_can_view_post($post_id)) {
            return true;
        }
    }

    $meta_keys = array(
        '_rbcr_logged_in_only',
        '_rbcr_login_status',
        '_content_control_restriction_type',
        '_content_control_user_status',
        '_content_control_who',
        'content_control_restriction_type',
        '_ca_content_control_settings',
        '_content_control_settings',
        'content_control_settings',
        '_content_control_post_restricted',
        'content_control',
        '_content_control',
        'restrict_access_type',
        '_restrict_access_type',
        '_restriction_type',
        'restriction_type'
    );

    foreach ($meta_keys as $key) {
        $val = get_post_meta($post_id, $key, true);
        if (!empty($val)) {
            if (is_string($val)) {
                $val_lower = strtolower($val);
                if (strpos($val_lower, 'logged_in') !== false || strpos($val_lower, 'logged-in') !== false || $val_lower === 'users' || $val_lower === 'loggedin') {
                    return true;
                }
            } elseif (is_array($val)) {
                $serialized = strtolower(serialize($val));
                if (strpos($serialized, 'logged_in') !== false || strpos($serialized, 'logged-in') !== false || strpos($serialized, 'logged_in_only') !== false) {
                    return true;
                }
            }
        }
    }

    $all_meta = get_post_meta($post_id);
    if (!empty($all_meta) && is_array($all_meta)) {
        foreach ($all_meta as $m_key => $m_values) {
            if (strpos($m_key, 'content_control') !== false || strpos($m_key, 'restrict') !== false) {
                foreach ((array)$m_values as $m_val) {
                    $m_val_lower = strtolower(is_string($m_val) ? $m_val : serialize($m_val));
                    if (strpos($m_val_lower, 'logged_in') !== false || strpos($m_val_lower, 'logged-in') !== false) {
                        return true;
                    }
                }
            }
        }
    }

    return false;
}

/**
 * Renders the sticky paywall gate with readable teaser and blurred background content
 */
function cmg_render_paywall_gate($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $raw_post_content = get_post_field('post_content', $post_id);
    $formatted_content = wpautop($raw_post_content);
    
    // Split content into block chunks (paragraphs, headings, lists, etc.)
    $blocks = preg_split('/(<\/p>|<\/h[1-6]>|<\/div>|<\/ul>|<\/ol>)/i', $formatted_content, -1, PREG_SPLIT_DELIM_CAPTURE);
    
    $teaser_html = '';
    $blurred_html = '';
    $block_count = 0;
    $max_teaser = 2; // 1-2 blocks for readable teaser

    if (is_array($blocks) && count($blocks) > 1) {
        for ($k = 0; $k < count($blocks) - 1; $k += 2) {
            $chunk = $blocks[$k] . (isset($blocks[$k + 1]) ? $blocks[$k + 1] : '');
            if (empty(trim(strip_tags($chunk)))) continue;
            
            $block_count++;
            if ($block_count <= $max_teaser) {
                $teaser_html .= $chunk;
            } else {
                $blurred_html .= $chunk;
            }
        }
    }

    if (empty($teaser_html)) {
        $teaser_html = '<p>' . wp_trim_words(strip_tags($raw_post_content), 40) . '</p>';
    }

    if (empty(trim(strip_tags($blurred_html)))) {
        $blurred_html = '<p>To access the complete step-by-step instructions, comprehensive guidelines, configuration options, advanced examples, and downloadable assets, upgrade to a paid account or sign in to your existing active membership.</p>'
                      . '<p>Our knowledge base contains in-depth documentation crafted by industry experts to help you scale your workflow, optimize configurations, and solve complex integrations effortlessly.</p>'
                      . '<p>Unlock uninterrupted access across all guides, developer tutorials, and real-time updates tailored for high-growth operations and seamless deployment.</p>'
                      . '<p>Join thousands of professionals and teams who rely on CMGalaxy daily for complete technical accuracy and accelerated development.</p>';
    }

    ob_start();
    ?>
    <div class="cmg-paywall-container">
        <!-- Top Teaser (Legible) -->
        <div class="cmg-teaser-content">
            <?php echo wp_kses_post($teaser_html); ?>
        </div>

        <!-- Sticky Paywall Gate -->
        <div class="cmg-paywall-gate">
            <!-- Blurred Backdrop Content (scrolls underneath sticky card) -->
            <div class="cmg-blurred-backdrop" aria-hidden="true">
                <?php echo wp_kses_post($blurred_html); ?>
            </div>

            <!-- Sticky Overlay with Centered Modal Card -->
            <div class="cmg-paywall-sticky-overlay">
                <div class="cmg-paywall-card-wrap">
                    <?php get_template_part('template-parts/modal-upgrade'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_filter('the_content', function($content) {
    if (is_admin() || !is_singular() || is_user_logged_in()) {
        return $content;
    }
    if (cmg_is_post_restricted_to_logged_in(get_the_ID())) {
        return cmg_render_paywall_gate(get_the_ID());
    }
    return $content;
}, 999);

add_filter('the_excerpt', function($excerpt) {
    $post_id = get_the_ID();
    if ($post_id && !is_user_logged_in() && cmg_is_post_restricted_to_logged_in($post_id)) {
        return 'Unlock full access to this premium guide and resource by upgrading to a paid account.';
    }
    return $excerpt;
}, 1);

add_filter('content_control/content/replacement_content', function($replacement, $post_id = null) {
    if (!is_user_logged_in() && is_singular()) {
        return cmg_render_paywall_gate($post_id);
    }
    return $replacement;
}, 999, 2);

add_filter('content_control_restriction_message', function($message) {
    if (!is_user_logged_in() && is_singular()) {
        return cmg_render_paywall_gate(get_the_ID());
    }
    return $message;
}, 999);

add_filter('content_control/prevent_redirect', '__return_true', 999);
add_filter('content_control/do_redirect', '__return_false', 999);
add_filter('rbcr_do_redirect', '__return_false', 999);
add_filter('rbcr_redirect_url', '__return_false', 999);
add_filter('rbcr_allow_redirect', '__return_false', 999);

add_action('template_redirect', function() {
    if (is_singular() && !is_user_logged_in()) {
        $post_id = get_the_ID();
        if ($post_id && cmg_is_post_restricted_to_logged_in($post_id)) {
            global $wp_filter;
            if (isset($wp_filter['template_redirect']) && is_object($wp_filter['template_redirect']) && isset($wp_filter['template_redirect']->callbacks) && is_array($wp_filter['template_redirect']->callbacks)) {
                foreach ($wp_filter['template_redirect']->callbacks as $priority => $callbacks) {
                    if (is_array($callbacks)) {
                        foreach ($callbacks as $idx => $callback) {
                            $func_name = '';
                            if (isset($callback['function'])) {
                                if (is_array($callback['function']) && isset($callback['function'][0])) {
                                    $class = is_object($callback['function'][0]) ? get_class($callback['function'][0]) : (is_string($callback['function'][0]) ? $callback['function'][0] : '');
                                    $method = isset($callback['function'][1]) && is_string($callback['function'][1]) ? $callback['function'][1] : '';
                                    $func_name = $class . '::' . $method;
                                } elseif (is_string($callback['function'])) {
                                    $func_name = $callback['function'];
                                }
                            }

                            if (!empty($func_name) && (stripos($func_name, 'rbcr') !== false || stripos($func_name, 'content_control') !== false || stripos($func_name, 'restrict') !== false)) {
                                unset($wp_filter['template_redirect']->callbacks[$priority][$idx]);
                            }
                        }
                    }
                }
            }
        }
    }
}, 0);

add_shortcode('cmg_upgrade_modal', function($atts) {
    if (is_user_logged_in()) {
        return '';
    }
    ob_start();
    get_template_part('template-parts/modal-upgrade');
    return ob_get_clean();
});

add_filter('show_admin_bar', function($show) {
    if (!current_user_can('administrator')) {
        return false;
    }
    return $show;
});

add_action('admin_init', function() {
    if (is_user_logged_in() && !current_user_can('administrator') && !wp_doing_ajax()) {
        wp_safe_redirect(home_url('/'));
        exit;
    }
});

add_filter('login_redirect', function($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (!in_array('administrator', $user->roles)) {
            return !empty($request) ? $request : home_url('/');
        }
    }
    return $redirect_to;
}, 10, 3);

add_filter('template_include', function($template) {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $path = trim(parse_url($request_uri, PHP_URL_PATH), '/');

    if ($path === 'signin' || $path === 'login') {
        $signin_template = get_template_directory() . '/page-signin.php';
        if (file_exists($signin_template)) {
            global $wp_query;
            $wp_query->is_404 = false;
            status_header(200);
            return $signin_template;
        }
    }

    return $template;
}, 99);

