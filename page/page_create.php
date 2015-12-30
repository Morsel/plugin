<?php

/**
 * Nishant page 
 *
 * @since 2.8.0
 */

function morsel_page_plugin_add() {   
    global $wpdb;

    $the_page_title = 'Morsel Info';
    $the_page_name = 'morsel-info';

    // the menu entry...
    delete_option("morsel_plugin_page_title");
    add_option("morsel_plugin_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("morsel_plugin_page_name");
    add_option("morsel_plugin_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("morsel_plugin_page_id");
    add_option("morsel_plugin_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[morsel_post_des]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_author'] = '1' ;
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    } else {
        // the plugin may have been previously active and the page may just be trashed...
        $the_page_id = $the_page->ID;
        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );
    }

    delete_option( 'morsel_plugin_page_id' );
    add_option( 'morsel_plugin_page_id', $the_page_id );
 }   



function morsel_page_plugin_remove() {

    global $wpdb;

    $the_page_title = get_option( "morsel_plugin_page_title" );
    $the_page_name = get_option( "morsel_plugin_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'morsel_plugin_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("morsel_plugin_page_title");
    delete_option("morsel_plugin_page_name");
    delete_option("morsel_plugin_page_id");

}  

function morsel_page_add_user_preference() {   
    global $wpdb;

    $the_page_title = 'Morsel User Preference';
    $the_page_name = 'morsel-user-preference';

    // the menu entry...
    delete_option("morsel_user_preference_page_title");
    add_option("morsel_user_preference_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("morsel_user_preference_page_name");
    add_option("morsel_user_preference_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("morsel_user_preference_page_id");
    add_option("morsel_user_preference_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_up = array();
        $_up['post_title'] = $the_page_title;
        $_up['post_content'] = "[morsel_user_preference]";
        $_up['post_status'] = 'publish';
        $_up['post_type'] = 'page';
        $_up['comment_status'] = 'closed';
        $_up['ping_status'] = 'closed';
        $_up['post_author'] = '1' ;
        $_up['post_category'] = array(1); // the default 'Uncatrgorised'

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_up );

    } else {
        // the plugin may have been previously active and the page may just be trashed...
        $the_page_id = $the_page->ID;
        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );
    }

    delete_option( 'morsel_user_preference_page_id' );
    add_option( 'morsel_user_preference_page_id', $the_page_id );
 }   


function morsel_page_user_preference_remove() {

    global $wpdb;

    $the_page_title = get_option( "morsel_user_preference_page_title" );
    $the_page_name = get_option( "morsel_user_preference_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'morsel_user_preference_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("morsel_user_preference_page_title");
    delete_option("morsel_user_preference_page_name");
    delete_option("morsel_user_preference_page_id");

} 
?>
