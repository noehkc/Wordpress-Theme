<?php 

/*

@package sun-restaurant-systems-t1

 ==============
 Admin Page
 ==============
*/


function srs_add_admin_page() {

    //Generate Admin Page
    add_menu_page( 'SRS Theme Options', 'SRS', 'manage_options', 'srs', 'srs_theme_create_page', get_template_directory_uri() . '/img/icon.png', 110 );

    //generate Admin Sub Pages
    add_submenu_page( 'srs', 'SRS Theme Options', 'General Settings', 'manage_options', 'srs', 'srs_theme_create_page'  );
    add_submenu_page( 'srs', 'SRS CSS Options', 'Custom CSS', 'manage_options', 'srs_css', 'srs_theme_settings_page'  );

    //activate custom settings
    add_action( 'admin_init', 'srs_custom_settings' );

}
add_action( 'admin_menu', 'srs_add_admin_page' );

function srs_custom_settings() {
    register_setting( 'srs-settings-group', 'first_name' );
    add_settings_section( 'srs-sidebar-options', 'Sidebar Options', 'srs_sidebar_options', 'srs' );
    add_settings_field( 'sidebar-name', 'First Name', 'srs_sidebar_name', 'srs', 'srs-sidebar-options' );
}

function srs_sidebar_options() {
    echo 'Customize your sidebar information';
}

function srs_sidebar_name() {
    echo '<input type="text" name="first_name" value="" />';
}

function srs_theme_create_page() {
    //generation of our admin page
    require_once( get_template_directory(). '/inc/templates/srs-admin.php' );
}

function srs_theme_settings_page() {

}