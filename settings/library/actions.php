<?php

/**
 * Custom CSS
 */
function sell_media_custom_css() {

    $theme_options = get_option( sell_media_get_current_theme_id() . '_options' );

    if ( isset( $theme_options['css'] ) && '' != $theme_options['css'] ) {
        echo '<!-- BeginHeader --><style type="text/css">';
        echo stripslashes_deep( $theme_options['css'] );
        echo '</style><!-- EndHeader -->';
    }
}

add_action( 'wp_head', 'sell_media_custom_css', 11);