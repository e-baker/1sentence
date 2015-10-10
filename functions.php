<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'minimum', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'minimum' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Minimum Pro Theme', 'minimum' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/minimum/' );
define( 'CHILD_THEME_VERSION', '3.0.1' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue scripts
add_action( 'wp_enqueue_scripts', 'minimum_enqueue_scripts' );
function minimum_enqueue_scripts() {

	wp_enqueue_script( 'minimum-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'minimum-google-fonts', '//fonts.googleapis.com/css?family=Merriweather:400,900,900italic,700italic,700,400italic|Lato:400,400italic,700,700italic', array(), CHILD_THEME_VERSION );


}

//* Add new image sizes
add_image_size( 'portfolio', 540, 340, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background', array( 'wp-head-callback' => 'minimum_background_callback' ) );

//* Add custom background callback for background color
function minimum_background_callback() {

	if ( ! get_background_color() )
		return;

	printf( '<style>body { background-color: #%s; }</style>' . "\n", get_background_color() );

}

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 320,
	'height'          => 60,
	'header-selector' => '.site-title a',
	'header-text'     => false
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'site-tagline',
	'nav',
	'subnav',
	'home-featured',
	'site-inner',
	'footer-widgets',
	'footer'
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Create portfolio custom post type
add_action( 'init', 'minimum_portfolio_post_type' );
function minimum_portfolio_post_type() {

	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name'          => __( 'Portfolio', 'minimum' ),
				'singular_name' => __( 'Portfolio', 'minimum' ),
			),
			'exclude_from_search' => true,
			'has_archive'         => true,
			'hierarchical'        => true,
			'menu_icon'           => 'dashicons-admin-page',
			'public'              => true,
			'rewrite'             => array( 'slug' => 'portfolio', 'with_front' => false ),
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'page-attributes', 'genesis-seo' ),
		)
	);

}

//* Remove site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//* Reposition the primary navigation menu
//*  remove_action( 'genesis_after_header', 'genesis_do_nav' );
//*  add_action( 'genesis_after_header', 'genesis_do_nav', 15 );


//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'minimum_secondary_menu_args' );
function minimum_secondary_menu_args( $args ){

	if( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;

}

//* Add the site tagline section
add_action( 'genesis_after_header', 'minimum_site_tagline' );
function minimum_site_tagline() {


	printf( '<div %s>', genesis_attr( 'site-tagline' ) );
	genesis_structural_wrap( 'site-tagline' );

		printf( '<div %s>', genesis_attr( 'site-tagline-left' ) );
		printf( '<p %s>%s</p>', genesis_attr( 'site-description' ), esc_html( get_bloginfo( 'description' ) ) );
		echo '</div>';

		printf( '<div %s>', genesis_attr( 'site-tagline-right' ) );
		genesis_widget_area( 'site-tagline-right' );
		echo '</div>';

	genesis_structural_wrap( 'site-tagline', 'close' );
	echo '</div>';

}

//* Hook after post widget after the entry content
add_action( 'genesis_after_entry', 'minimum_after_entry', 5 );
function minimum_after_entry() {

	if ( is_singular( 'story' ) )
		genesis_widget_area( 'after-entry', array(
			'before' => '<div class="after-entry widget-area">',
			'after'  => '</div>',
		) );

}

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'minimum_author_box_gravatar' );
function minimum_author_box_gravatar( $size ) {

	return 144;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'minimum_comments_gravatar' );
function minimum_comments_gravatar( $args ) {

	$args['avatar_size'] = 96;
	return $args;

}

//* Change the number of portfolio items to be displayed (props Bill Erickson)
add_action( 'pre_get_posts', 'minimum_portfolio_items' );
function minimum_portfolio_items( $query ) {

	if ( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '6' );
	}

}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'minimum_remove_comment_form_allowed_tags' );
function minimum_remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'site-tagline-right',
	'name'        => __( 'Site Tagline Right', 'minimum' ),
	'description' => __( 'This is the site tagline right section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-1',
	'name'        => __( 'Home Featured 1', 'minimum' ),
	'description' => __( 'This is the home featured 1 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-2',
	'name'        => __( 'Home Featured 2', 'minimum' ),
	'description' => __( 'This is the home featured 2 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-3',
	'name'        => __( 'Home Featured 3', 'minimum' ),
	'description' => __( 'This is the home featured 3 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-4',
	'name'        => __( 'Home Featured 4', 'minimum' ),
	'description' => __( 'This is the home featured 4 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'after-entry',
	'name'        => __( 'After Entry', 'minimum' ),
	'description' => __( 'This is the after entry widget area.', 'minimum' ),
) );

add_filter( 'bbp_verify_nonce_request_url', 'my_bbp_verify_nonce_request_url', 999, 1 );
function my_bbp_verify_nonce_request_url( $requested_url )
{
    return 'http://1sentence.org' . $_SERVER['REQUEST_URI'];
}

//* Modify the admin bar

function remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');          // Remove the WordPress logo
    $wp_admin_bar->remove_menu('about');            // Remove the about WordPress link
    $wp_admin_bar->remove_menu('wporg');            // Remove the WordPress.org link
    $wp_admin_bar->remove_menu('documentation');    // Remove the WordPress documentation link
    $wp_admin_bar->remove_menu('support-forums');   // Remove the support forums link
    $wp_admin_bar->remove_menu('feedback');         // Remove the feedback link
    $wp_admin_bar->remove_menu('site-name');        // Remove the site name menu
    $wp_admin_bar->remove_menu('view-site');        // Remove the view site link
    $wp_admin_bar->remove_menu('updates');          // Remove the updates link
    $wp_admin_bar->remove_menu('comments');         // Remove the comments link
    $wp_admin_bar->remove_menu('new-content');      // Remove the content link
		$wp_admin_bar->remove_node( 'edit' );
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

//* edit footer

add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$creds = '[footer_copyright] &middot; OneSentence &middot; Built on the <a href="http://www.studiopress.com/themes/genesis" title="Genesis Framework">Genesis Framework</a>';
	return $creds;
}

//* Enqueue Droid San + Serif Google font
add_action( 'wp_enqueue_scripts', 'sp_load_google_fonts' );
function sp_load_google_fonts() {
	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Droid+Sans:400,700|//fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic', array(), CHILD_THEME_VERSION );
}

//* Enqueue Dashicons for menus

add_action( 'wp_enqueue_scripts', 'add_dashicons_front_end' );
function add_dashicons_front_end() {
	wp_enqueue_style( 'dashicons-style', get_stylesheet_uri(), array('dashicons'), '1.0' );

}

//* Enable gravity forms visibility settings

add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

//* Remove titles from pages

add_action( 'get_header', 'remove_titles_all_single_pages' );
function remove_titles_all_single_pages() {
    if ( is_singular('page') ) {
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    }
}

/**
 * Gravity Wiz // Gravity Forms // Limit Submissions Per Time Period (by IP, User, Role, Form URL, or Field Value)
 *
 * Limit the number of times a form can be submitted per a specific time period. You modify this limit to apply to
 * the visitor's IP address, the user's ID, the user's role, a specific form URL, or the value of a specific field.
 * These "limiters" can be combined to create more complex limitations.
 *
 * @version	2.4
 * @author  David Smith <david@gravitywiz.com>
 * @license GPL-2.0+
 * @link    http://gravitywiz.com/better-limit-submission-per-time-period-by-user-or-ip/
 */
class GW_Submission_Limit {

    var $_args;
	var $_notification_event;

	private static $forms_with_individual_settings = array();

    function __construct($args) {

	    // make sure we're running the required minimum version of Gravity Forms
	    if( ! property_exists( 'GFCommon', 'version' ) || ! version_compare( GFCommon::$version, '1.8', '>=' ) )
		    return;

        $this->_args = wp_parse_args( $args, array(
            'form_id' => false,
            'limit' => 1,
            'limit_by' => 'ip', // 'ip', 'user_id', 'role', 'embed_url', 'field_value'
            'time_period' => 60 * 60 * 24, // integer in seconds or 'day', 'month', 'year' to limit to current day, month, or year respectively
            'limit_message' => __( 'Aha! Sorry, only 1 submission per prompt. Please check back next month!' ),
	        'apply_limit_per_form' => true,
	        'enable_notifications' => false
        ) );

        if( ! is_array( $this->_args['limit_by'] ) ) {
            $this->_args['limit_by'] = array( $this->_args['limit_by'] );
        }

	    if( $this->_args['form_id'] ) {
		    self::$forms_with_individual_settings[] = $this->_args['form_id'];
	    }

        add_action( 'init', array( $this, 'init' ) );

    }

	function init() {

		add_filter( 'gform_pre_render', array( $this, 'pre_render' ) );
		add_filter( 'gform_validation', array( $this, 'validate' ) );

		if( $this->_args['enable_notifications'] ) {

			$this->enable_notifications();

			add_action( 'gform_after_submission', array( $this, 'maybe_send_limit_reached_notifications' ), 10, 2 );

		}

	}

    function pre_render( $form ) {

        if( ! $this->is_applicable_form( $form ) || ! $this->is_limit_reached( $form['id'] ) ) {
	        return $form;
        }

        $submission_info = rgar( GFFormDisplay::$submission, $form['id'] );

        // if no submission, hide form
        // if submission and not valid, hide form
        // unless 'field_value' limiter is applied
        if( ( ! $submission_info || ! rgar( $submission_info, 'is_valid' ) ) && ! $this->is_limited_by_field_value() ) {
            add_filter( 'gform_get_form_filter_' . $form['id'], create_function( '', 'return \'<div class="limit-message">' . $this->_args['limit_message'] . '</div>\';' ) );
        }

        return $form;

    }

    function validate( $validation_result ) {

        if( ! $this->is_applicable_form( $validation_result['form'] ) || ! $this->is_limit_reached( $validation_result['form']['id'] ) ) {
            return $validation_result;
        }

        $validation_result['is_valid'] = false;

        if( $this->is_limited_by_field_value() ) {
	        $field_ids = array_map( 'intval', $this->get_limit_field_ids() );
            foreach( $validation_result['form']['fields'] as &$field ) {
                if( in_array( $field['id'], $field_ids ) ) {
                    $field['failed_validation'] = true;
                    $field['validation_message'] = do_shortcode( $this->_args['limit_message'] );
                }
            }
        }

        return $validation_result;
    }

    public function is_limit_reached($form_id) {
        global $wpdb;

        $where = array();
        $join = array();

	    $where[] = 'l.status = "active"';

        foreach( $this->_args['limit_by'] as $limiter ) {
            switch( $limiter ) {
                case 'role': // user ID is required when limiting by role
                case 'user_id':
                    $where[] = $wpdb->prepare( 'l.created_by = %s', get_current_user_id() );
                    break;
                case 'embed_url':
                    $where[] = $wpdb->prepare( 'l.source_url = %s', GFFormsModel::get_current_page_url());
                    break;
                case 'field_value':

                    $values = $this->get_limit_field_values( $form_id, $this->get_limit_field_ids() );

                    // if there is no value submitted for any of our fields, limit is never reached
                    if( empty( $values ) ) {
                         return false;
                    }

					foreach( $values as $field_id => $value ) {
						$table_slug = sprintf( 'ld%s', str_replace( '.', '_', $field_id ) );
						$join[]     = "INNER JOIN {$wpdb->prefix}rg_lead_detail {$table_slug} ON {$table_slug}.lead_id = l.id";
						//$where[]    = $wpdb->prepare( "CAST( {$table_slug}.field_number as unsigned ) = %f AND {$table_slug}.value = %s", $field_id, $value );
						$where[]    = $wpdb->prepare( "\n( ( {$table_slug}.field_number BETWEEN %s AND %s ) AND {$table_slug}.value = %s )", doubleval( $field_id ) - 0.001, doubleval( $field_id ) + 0.001, $value );
					}

                    break;
                default:
                    $where[] = $wpdb->prepare( 'ip = %s', GFFormsModel::get_ip() );
            }
        }

	    if( $this->_args['apply_limit_per_form'] ) {
		    $where[] = $wpdb->prepare( 'l.form_id = %d', $form_id );
	    }

        $time_period = $this->_args['time_period'];
        $time_period_sql = false;

        if( $time_period === false ) {
            // no time period
        } else if( intval( $time_period ) > 0 ) {
            $time_period_sql = $wpdb->prepare( 'date_created BETWEEN DATE_SUB(utc_timestamp(), INTERVAL %d SECOND) AND utc_timestamp()', $this->_args['time_period'] );
        } else {
            switch( $time_period ) {
                case 'per_day':
                case 'day':
                    $time_period_sql = 'DATE( date_created ) = DATE( utc_timestamp() )';
                break;
                case 'per_month':
                case 'month':
                    $time_period_sql = 'MONTH( date_created ) = MONTH( utc_timestamp() )';
                break;
                case 'per_year':
                case 'year':
                    $time_period_sql = 'YEAR( date_created ) = YEAR( utc_timestamp() )';
                break;
            }
        }

        if( $time_period_sql ) {
            $where[] = $time_period_sql;
        }

        $where = implode( ' AND ', $where );
        $join = implode( "\n", $join );

        $sql = "SELECT count( l.id )
                FROM {$wpdb->prefix}rg_lead l
                $join
                WHERE $where";

        $entry_count = $wpdb->get_var( $sql );

        return $entry_count >= $this->get_limit();
    }

    public function is_limited_by_field_value() {
        return in_array( 'field_value', $this->_args['limit_by'] );
    }

    public function get_limit_field_ids() {

	    $limit = $this->_args['limit'];

	    if( is_array( $limit ) ) {
		    $field_ids = array( call_user_func( 'array_shift', array_keys( $this->_args['limit'] ) ) );
	    } else {
		    $field_ids = $this->_args['fields'];
	    }

        return $field_ids;
    }

    public function get_limit_field_values( $form_id, $field_ids ) {

	    $form   = GFAPI::get_form( $form_id );
	    $values = array();

	    foreach( $field_ids as $field_id ) {

		    $field      = GFFormsModel::get_field( $form, $field_id );
		    $input_name = 'input_' . str_replace( '.', '_', $field_id );
		    $value      = GFFormsModel::prepare_value( $form, $field, rgpost( $input_name ), $input_name, null );

		    if( ! rgblank( $value ) ) {
			    $values[ $field_id ] = $value;
		    }

	    }

        return $values;
    }

    public function get_limit() {

        $limit = $this->_args['limit'];

        if( $this->is_limited_by_field_value() ) {
            $limit = is_array( $limit ) ? array_shift( $limit ) : intval( $limit );
        } else if( in_array( 'role', $this->_args['limit_by'] ) ) {
            $limit = rgar( $limit, $this->get_user_role() );
        }

        return intval( $limit );
    }

    public function get_user_role() {

        $user = wp_get_current_user();
        $role = reset( $user->roles );

        return $role;
    }

	public function enable_notifications() {

		if( ! class_exists( 'GW_Notification_Event' ) ) {

			_doing_it_wrong( 'GW_Inventory::$enable_notifications', __( 'Inventory notifications require the \'GW_Notification_Event\' class.' ), '1.0' );

		} else {

			$event_slug = implode( array_filter( array( "gw_submission_limit_limit_reached", $this->_args['form_id'] ) ) );
			$event_name = GFForms::get_page() == 'notification_edit' ? __( 'Submission limit reached' ) : __( 'Event name is only populated on Notification Edit view; saves a DB call to get the form on every ' );

			$this->_notification_event = new GW_Notification_Event( array(
				'form_id'    => $this->_args['form_id'],
				'event_name' => $event_name,
				'event_slug' => $event_slug
				//'trigger'    => array( $this, 'notification_event_listener' )
			) );

		}

	}

	public function maybe_send_limit_reached_notifications( $entry, $form ) {

		if( $this->is_applicable_form( $form ) && $this->is_limit_reached( $form['id'] ) ) {
			$this->send_limit_reached_notifications( $form, $entry );
		}

	}

	public function send_limit_reached_notifications( $form, $entry ) {

		$this->_notification_event->send_notifications( $this->_notification_event->get_event_slug(), $form, $entry, true );

	}

	function is_applicable_form( $form ) {

		$form_id          = isset( $form['id'] ) ? $form['id'] : $form;
		$is_global_form   = empty( $this->_args['form_id'] ) && ! in_array( $form_id, self::$forms_with_individual_settings );
		$is_specific_form = $form_id == $this->_args['form_id'];

		return $is_global_form || $is_specific_form;
	}

}

class GWSubmissionLimit extends GW_Submission_Limit { }


# Configuration

new GW_Submission_Limit( array(
    'form_id' => 1,
		'limit' => 1,
		'limit_message' => 'Thank you for your critique! Feel free to leave a comment below.',
    // when "limit_by" is set to "role", "limit" must be provided as array with roles and their corresponding limits
		'limit_by' => array( 'embed_url', 'user_id' )
) );

new GW_Submission_Limit( array(
    'form_id' => 3,
		'limit' => 1,
		'limit_message' => 'Sorry, you have reached the maximum number of submissions for a single prompt.',
    // when "limit_by" is set to "role", "limit" must be provided as array with roles and their corresponding limits
		'limit_by' => array( 'embed_url', 'user_id' )
) );

new GW_Submission_Limit( array(
    'form_id' => 4,
		'limit' => 2,
		'limit_message' => 'Sorry, you have reached the maximum number of submissions for a single prompt.',
		'limit_by' => array( 'embed_url', 'user_id' )
) );

//* Custom login screen

function my_custom_login() {
echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/login/custom-login-styles.css" />';
}
add_action('login_head', 'my_custom_login');

//* Custom redirect login

if( !function_exists('custom_user_login_redirect') ) {
function custom_user_login_redirect() {
$redirect_to = 'http://1sentence.org/dashboard/';
return $redirect_to;
}
add_filter('login_redirect','custom_user_login_redirect',10,3);
}

//* Add stories to buddypress profiles

function bpfr_my_post_on_profile() {

    // to get all post, comment the line 'author'

    $myposts = get_posts(  array(
    'posts_per_page' => 3, // set the number of post to show
    'author'         => bp_displayed_user_id(), // show only this member post
    'post_type'      => 'story',
    'orderby'          => 'post_date',
    'order'            => 'DESC',
    'post_status'      => 'publish'
    ));

    if( ! empty( $myposts ) ) {

        foreach($myposts as $post) {
            setup_postdata( $post );
            $page_object = get_post( $post );

        // uncomment next line to show only a list of titles linked to full post
        // if you uncomment, you have to comment the 2 echo below

            echo '<h3 class="profile_post"><a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</h3></a>';

        // comment the 2 following lines (or remove them) if you use the above

            //echo '<h3 class="profile_post"><a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</h3></a>';
            //echo $page_object->post_content;
        }

        wp_reset_postdata();

    } else {

        echo '<div class="info" id="message">' . __('No posts found.') . '</div>'; // is translated by WP
    }
}
add_action ( 'my_profile_post', 'bpfr_my_post_on_profile' );


function bpfr_post_profile_setup_nav() {
    global $bp;
    $parent_slug = 'stories';
    $child_slug = 'posts_sub';

    bp_core_new_nav_item( array(
    'name' => __( 'Stories' ),
    'slug' => $parent_slug,
    'screen_function' => 'bpfr_profile_post_screen',
    'position' => 40,
    'default_subnav_slug' => $child_slug
    ) );

    //Add subnav item
    bp_core_new_subnav_item( array(
    'name' => __( 'Latest Stories' ),
    'slug' => $child_slug,
    'parent_url' => $bp->loggedin_user->domain . $parent_slug.'/',
    'parent_slug' => $parent_slug,
    'screen_function' => 'bpfr_profile_post_screen'
    ) );
}

function bpfr_profile_post_screen() {
    add_action( 'bp_template_content', 'bpfr_profile_post_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function bpfr_profile_post_screen_content() {

    do_action( 'my_profile_post' );
}

add_action( 'bp_setup_nav', 'bpfr_post_profile_setup_nav' );

//* Changing post author hyperlink for buddypress

add_action('init', 'cng_author_base');
function cng_author_base() {
    global $wp_rewrite;
    $author_slug = 'members'; // change slug name
    $wp_rewrite->author_base = $author_slug;
}

//* Limiting comment length

add_filter( 'preprocess_comment', 'wpb_preprocess_comment' );

function wpb_preprocess_comment($comment) {
    if ( strlen( $comment['comment_content'] ) > 500 ) {
        wp_die('Comment is too long. Please keep your comment under 5000 characters.');
    }
if ( strlen( $comment['comment_content'] ) < 1 ) {
        wp_die('Comment is too short. Please use at least 60 characters.');
    }
    return $comment;
}

/**
 * Change default Header URL.
 *
 * @author Jen Baumann
 * @link http://dreamwhisperdesigns.com/genesis-tutorials/change-genesis-header-home-link/
 */
function child_header_title( $title, $inside, $wrap ) {
    $inside = sprintf( '<a href="http://1sentence.org/dashboard/" title="%s">%s</a>', esc_attr( get_bloginfo( 'name' ) ), get_bloginfo( 'name' ) );
    return sprintf( '<%1$s class="site-title">%2$s</%1$s>', $wrap, $inside );
}

//* Gravityforms dynamically populate story authors email

add_filter('gform_field_value_author_email', 'populate_post_author_email');
function populate_post_author_email($value){
    global $post;

    $author_email = get_the_author_meta('email', $post->post_author);

    return $author_email;
}

// Adding notifications for story published
add_post_type_support( 'story', 'buddypress-activity' );

function customize_page_tracking_args() {
    // Check if the Activity component is active before using it.
    if ( ! bp_is_active( 'activity' ) ) {
        return;
    }

    bp_activity_set_post_type_tracking_args( 'story', array(
        'component_id'             => 'activity',
        'action_id'                => 'new_story',
        'bp_activity_admin_filter' => __( 'Published a new story', 'custom-domain' ),
        'bp_activity_front_filter' => __( 'Stories', 'custom-domain' ),
        'contexts'                 => array( 'activity', 'member' ),
        'bp_activity_new_post'     => __( '%1$s published a new <a href="%2$s">Story</a>', '1sentence' ),
        'bp_activity_new_post_ms'  => __( '%1$s published a new <a href="%2$s">Story</a>, on the site %3$s', '1sentence' ),
        'position'                 => 100,
    ) );
}
add_action( 'init', 'customize_page_tracking_args', 1000 );

//* add Primary Genre to stories
add_action('genesis_post_content', 'primary-genre');
function primary_genre() {
if ( is_single() && genesis_get_custom_field('primary-genre') )
echo '<hr /><div id="primary-genre">Genre: '. genesis_get_custom_field('primary-genre') .'</div>';
}

//* Remove "Howdy" from admin bar

function howdy_message($translated_text, $text, $domain) {
    $new_message = str_replace('Howdy, ', '', $text);
    return $new_message;
}
add_filter('gettext', 'howdy_message', 10, 3);

//* Admin bar adding forums 

add_action('admin_bar_menu', 'add_toolbar_items', 100);
function add_toolbar_items($admin_bar){
	$admin_bar->add_menu( array(
		'id'    => 'forum',
		'title' => 'Forum',
		'href'  => '/forums',
		'parent' => 'top-secondary',
		'meta'  => array(
			'title' => __('Forums'),
		),
	));
}
