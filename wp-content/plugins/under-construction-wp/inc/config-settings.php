<?php
/**
 * Config
 *
 * @package WordPress
 * @subpackage seed_ucp
 * @since 0.1.0
 */

/**
 * Config Settings
 */
function seed_ucp_get_options(){

    /**
     * Create new menus
     */

    $seed_ucp_options[ ] = array(
        "type" => "menu",
        "menu_type" => "add_options_page",
        "page_name" => __( "Under Construction", 'seed_ucp' ),
        "menu_slug" => "seed_ucp",
        "layout" => "2-col"
    );

    /**
     * Settings Tab
     */
    $seed_ucp_options[ ] = array(
        "type" => "tab",
        "id" => "seed_ucp_setting",
        "label" => __( "Content", 'seed_ucp' ),
    );

    $seed_ucp_options[ ] = array(
        "type" => "setting",
        "id" => "seed_ucp_settings_content",
    );

    $seed_ucp_options[ ] = array(
        "type" => "section",
        "id" => "seed_ucp_section_general",
        "label" => __( "General", 'seed_ucp' ),
    );

    $seed_ucp_options[ ] = array(
        "type" => "radio",
        "id" => "status",
        "label" => __( "Status", 'seed_ucp' ),
        "option_values" => array(
            '0' => __( 'Disabled', 'seed_ucp' ),
            '1' => __( 'Enable Under Construction Mode', 'seed_ucp' ),
            '2' => __( 'Enable Maintenance Mode', 'seed_ucp' )
        ),
        "desc" => __( "When you are logged in you'll see your normal website. Logged out visitors will see the Under Construction or Maintenance page. Under Construction Mode will be available to search engines if your site is not private. Maintenance Mode will notify search engines that the site is unavailable.", 'seed_ucp' ),
        "default_value" => "0"
    );


    $csp4_maintenance_file = WP_CONTENT_DIR."/maintenance.php";
    if (file_exists($csp4_maintenance_file)) {
    $seed_ucp_options[ ] = array(
        "type" => "checkbox",
        "id" => "enable_maintenance_php",
        "label" => __( "Use maintenance.php", 'seed_ucp' ),
        "desc" => __('maintenance.php detected, would you like to use this for your maintenance page?', 'seed_ucp'),
        "option_values" => array(
             'name' => __( 'Yes', 'seed_ucp' ),
             //'required' => __( 'Make Name Required', 'seed_ucp' ),
        )
    );
    }

    // Page Setttings
    $seed_ucp_options[ ] = array(
        "type" => "section",
        "id" => "seed_ucp_section_page_settings",
        "label" => __( "Page Settings", 'seed_ucp' )
    );


    $seed_ucp_options[ ] = array(
        "type" => "wpeditor",
        "id" => "description",
        "label" => __( "Message", 'seed_ucp' ),
        "desc" => __( 'Tell the visitor what to expect from your site. Looking for Shortcode Support? Upgrade to the <a href="http://www.seedprod.com/ultimate-coming-soon-page-vs-coming-soon-pro/?utm_source=under-construction-plugin&amp;utm_medium=banner&amp;utm_campaign=under-construction-link-in-plugin" target="_blank">Pro Version</a>!', 'seed_ucp' ),
        "class" => "large-text"
    );

    $seed_ucp_options[ ] = array(
        "type" => "upload",
        "id" => "bg_image",
        "label" => __( "Optional Background Image", 'seed_ucp' ),
    );

    $seed_ucp_options[ ] = array(
        "type" => "checkbox",
        "id" => "disable_default_excluded_urls",
        "label" => __( "Disable Default Excluded URLs", 'seed_ucp' ),
        "desc" => __("By default we exclude urls with the terms: login, admin, dashboard and account to prevent lockouts. Check to disable.", 'seed_ucp'),
        "option_values" => array(
             '1' => __( 'Disable', 'seed_ucp' ),
        ),
        "default" => "1",
    );

     $seed_ucp_options[ ] = array( "type" => "radio",
        "id" => "footer_credit",
        "label" => __("Powered By SeedProd", 'seed_ucp'),
        "option_values" => array('0'=>__('Nope - Got No Love', 'seed_ucp'),'1'=>__('Yep - I Love You Man', 'seed_ucp')),
        "desc" => __("Can we show a <strong>cool stylish</strong> footer credit at the bottom the page.", 'seed_ucp'),
        "default_value" => "0",
    );
    // Page Setttings
    $seed_ucp_options[ ] = array(
        "type" => "section",
        "id" => "seed_ucp_section_custom_settings",
        "label" => __( "Custom HTML", 'seed_ucp' )
    );

    $seed_ucp_options[ ] = array(
        "type" => "textarea",
        "id" => "custom_html",
        "class" => "large-text",
        "label" => __( "HTML", 'seed_ucp' ),
        "desc" => __("This will replace the entire page's output with your custom html. Leave empty if you are not using custom html.", 'seed_ucp'),
    );


 




    return $seed_ucp_options;

}
