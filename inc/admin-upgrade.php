<?php

$version = get_option( 'sell_media_version' );

if ( $version <= '1.0.1' ){
    /**
     * General Settings
     */
    $sell_media_general_settings = array();
    $sell_media_general_settings['test_mode'] = get_option( 'sell_media_test_mode' );
    $sell_media_general_settings['checkout_page'] = get_option( 'sell_media_cart_page' );
    $sell_media_general_settings['thanks_page'] = get_option( 'sell_media_thanks_page' );

    /**
     * Update new settings with old values
     */
    update_option('sell_media_general_settings', $sell_media_general_settings );

    /**
     * Delete old "general settings"
     */
    delete_option( 'sell_media_test_mode' );
    delete_option( 'sell_media_cart_page' );
    delete_option( 'sell_media_thanks_page' );

    /**
     * Payment Settings
     */
    $sell_media_payment_settings = array();
    $sell_media_payment_settings['paypal_email'] = get_option( 'sell_media_paypal_email' );
    $sell_media_payment_settings['currency'] = get_option( 'sell_media_currency' );
    $sell_media_payment_settings['default_price'] = get_option( 'sell_media_original_price' );

    /**
     * Update new settings with old values
     */
    update_option('sell_media_payment_settings', $sell_media_payment_settings );

    /**
     * Delete old "general settings"
     */
    delete_option( 'sell_media_paypal_email' );
    delete_option( 'sell_media_currency' );
    delete_option( 'sell_media_original_price' );

    /**
     * Email Settings
     */
    $sell_media_email_settings = array();
    $sell_media_email_settings['from_name'] = get_option( 'sell_media_from_email' );
    $sell_media_email_settings['from_email'] = get_option( 'sell_media_from_name' );
    $sell_media_email_settings['success_email_subject'] = get_option( 'sell_media_success_email_body' );
    $sell_media_email_settings['success_email_body'] = get_option( 'sell_media_success_email_subject' );

    /**
     * Update new settings with old values
     */
    update_option('sell_media_email_settings', $sell_media_email_settings );

    /**
     * Delete old "general settings"
     */
    delete_option( 'sell_media_from_email' );
    delete_option( 'sell_media_from_name' );
    delete_option( 'sell_media_success_email_body' );
    delete_option( 'sell_media_success_email_subject' );
}


/**
 * Moves the contents of the meta field "sell_media_item" into the
 * "post_content" field for the "sell_media_item" post_type.
 */
if ( $version <= '1.0.4' ){

    /**
     * Retrive all Post IDs for our "sell_media_item" post type.
     */
    global $wpdb;
    $post_ids = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type LIKE 'sell_media_item';" );


    /**
     * Build an array of IDs and Descriptions, this is the content
     * going into the post_content field
     */
    $tmp_posts = array();
    $new_posts = array();
    foreach( $post_ids as $post ){
        $tmp_posts['post_id'] = $post->ID;
        $tmp_posts['description'] = get_post_meta( $post->ID, 'sell_media_description', true );

        $new_posts[] = $tmp_posts;
    }


    /**
     * Update each post with the "sell_media_description" becoming
     * the "post_content". If successful delete the post_meta field
     * "sell_media_description".
     */
    foreach( $new_posts as $post ){
        $data =  array(
            'ID' => $post['post_id'],
            'post_content' => $post['description']
            );
        $r = wp_update_post( $data );
        if ( $r ) {
            delete_post_meta( $post['post_id'], 'sell_media_description' );
        }
    }
}

if ( $version <= '1.1' ){
    /**
     * Retrive all Post IDs for our "sell_media_item" post type.
     */
    global $wpdb;
    $post_ids = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type LIKE 'sell_media_item';" );

    /**
     * Build an array of IDs and Descriptions, this is the content
     * going into the post_content field
     */
    foreach( $post_ids as $post ){
        $_thumbnail_id = get_post_thumbnail_id( $post->ID );
        if ( $_thumbnail_id ){
            update_post_meta( $post->ID, '_sell_media_attachment_id', $_thumbnail_id );
            // print "update_postmeta: {$post->ID}, '_sell_media_attachment_id', {$_thumbnail_id}\n";
        }
    }
    update_option('sell_media_version', '1.2' );
}

if ( $version <= '1.5.1' ){

    $sizes = get_option( 'sell_media_size_settings' ) ;
    $clean_sizes = array(
        'small' => array(
            'width' => $sizes['small_size_width'],
            'height' => $sizes['small_size_height'],
            'price' => $sizes['small_size_price']
            ),
        'medium' => array(
            'width' => $sizes['medium_size_width'],
            'height' => $sizes['medium_size_height'],
            'price' => $sizes['medium_size_price']
            ),
        'large' => array(
            'width' => $sizes['large_size_width'],
            'height' => $sizes['large_size_height'],
            'price' => $sizes['large_size_price']
            )
        );

    $parent = wp_insert_term( 'Default', 'price-group' );
    if ( is_wp_error( $parent ) ){
        $parent_id = $parent->error_data['term_exists'];
    } else {
        $parent_id = $parent['term_id'];
    }

    foreach( $clean_sizes as $k => $v ){

        $term = wp_insert_term( ucfirst( $k ), 'price-group', array( 'parent' => $parent_id ) );
        if ( is_wp_error( $term ) ){
            $term_id = $term->error_data['term_exists'];
        } else {
            $term_id = $term['term_id'];
        }
        sell_media_update_term_meta( $term_id, 'width', $v['width'] );
        sell_media_update_term_meta( $term_id, 'height', $v['height'] );
        sell_media_update_term_meta( $term_id, 'price', $v['price'] );
    }

    /**
     * This snippet is used to flush the cache only for 'price-group'. Without
     * it this wp bug will exists: https://core.trac.wordpress.org/ticket/14485
     */
    if ( taxonomy_exists( 'price-group' ) ) {
        wp_cache_set( 'last_changed', time( ) - 1800, 'terms' );
        wp_cache_delete( 'all_ids', 'price-group' );
        wp_cache_delete( 'get', 'price-group' );
        delete_option( "price-group_children" );
        _get_term_hierarchy( 'price-group' );
    }

}