<?php
/**
 * Define the Tabs appearing on the Theme Options page
 * Tabs contains sections
 * Options are assigned to both Tabs and Sections
 * See README.md for a full list of option types
 */

$general_settings_tab = array(
    "name" => "general_tab",
    "title" => __( "General", "gpp" ),
    "sections" => array(
        "general_section_1" => array(
            "name" => "general_section_1",
            "title" => __( "General", "gpp" ),
            "description" => __( "", "gpp" )
        )
    )
);

sell_media_register_theme_option_tab( $general_settings_tab );


$slideshow_tab = array(
    "name" => "slideshow_tab",
    "title" => __( "Slideshow", "gpp" ),
    "sections" => array(
        "slideshow_section_1" => array(
            "name" => "slideshow_section_1",
            "title" => __( "Slideshow", "gpp" ),
            "description" => __( "", "gpp" )
        )
    )
);

sell_media_register_theme_option_tab( $slideshow_tab );

 /**
 * The following example shows you how to register theme options and assign them to tabs and sections:
*/
$options = array(
    'test-mode' => array(
        "tab" => "general_tab",
        "name" => "test-mode",
        "title" => __( "Test Mode", "gpp" ),
        "description" => __( sprintf( 'To accept real payments, select No. To fully use test mode, you must have %s.', '<a href="https://developer.paypal.com/" target="_blank">Paypal sandbox (test) account</a>' ), 'sell_media' ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "select",
        "default" => "",
        "valid_options" => array(
            "yes" => array(
                "name" => "yes",
                "title" => __( "Yes", "gpp" )
            ),
            "no" => array(
                "name" => "no",
                "title" => __( "No", "gpp" )
            )
        )
    )
);

sell_media_register_theme_options( $options );

?>