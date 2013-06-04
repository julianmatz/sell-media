<?php
/**
 * Get list of taxonomies
 */
function sell_media_get_taxonomy_list( $taxonomy = 'category', $firstblank = false ) {

	$args = array(
		'hide_empty' => 0
	);

	$terms_obj = get_terms( $taxonomy, $args );
	$terms = array();
	if( $firstblank ) {
		$terms['']['name'] = '';
		$terms['']['title'] = __( '-- Choose One --', 'gpp' );
	}
	foreach ( $terms_obj as $tt ) {
		$terms[ $tt->slug ]['name'] = $tt->slug;
		$terms[ $tt->slug ]['title'] = $tt->name;
	}

	return $terms;
}


/**
 * Get current settings page tab
 */
function sell_media_get_current_tab() {

	global $sell_media_tabs;

	$first_tab = $sell_media_tabs[0]['name'];

    if ( isset( $_GET['tab'] ) ) {
        $current = esc_attr( $_GET['tab'] );
    } else {
    	$current = $first_tab;
    }

	return $current;
}

/**
 * Get current settings page tab
 */
function sell_media_get_current_tab_title( $tabval ) {

	global $sell_media_tabs;

	$current = $sell_media_tabs[ $tabval ]['title'];

	return $current;
}

/**
 * Define gpp Admin Page Tab Markup
 *
 * @uses	sell_media_get_current_tab()	defined in \functions\options.php
 * @uses	sell_media_get_settings_page_tabs()	defined in \functions\options.php
 *
 * @link	http://www.onedesigns.com/tutorials/separate-multiple-theme-options-pages-using-tabs	Daniel Tara
 */
function sell_media_get_page_tab_markup() {

	global $sell_media_tabs;

	$page = 'sell_media_settings_key';

    $current = sell_media_get_current_tab();

    $tabs = $sell_media_tabs;

    $links = array();
    $i = 0;
    foreach( $tabs as $tab ) {
		if( isset( $tab['name'] ) )
			$tabname = $tab['name'];
		if( isset( $tab['title'] ) )
			$tabtitle = $tab['title'];
        if ( $tabname == $current ) {
            $links[] = "<a class='nav-tab nav-tab-active' href='?post_type=sell_media_item&page=$page&tab=$tabname&i=$i'>$tabtitle</a>";
        } else {
            $links[] = "<a class='nav-tab' href='?post_type=sell_media_item&page=$page&tab=$tabname&i=$i'>$tabtitle</a>";
        }
        $i++;
    }


    $plugin_data = get_plugin_data(  plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . 'sell-media.php', $markup = true, $translate = true );

    echo '<div class="theme-options">';
    echo '<ul>';
    echo '<li><a href="' . $plugin_data['PluginURI'] . '" target="_blank">' . $plugin_data['Name'] . '</a></li>';
    echo '<li>' . __( 'Version: ', 'gpp' ) . $plugin_data['Version'] . '</li>';
    echo '<li><a href="' . $plugin_data['AuthorURI'] . '" target="_blank">' . __( 'Support', 'gpp' ) . '</a></li>';
    echo '</ul>';
    echo '<br class="clear">';
    echo '</div>';

    echo '<div id="icon-themes" class="icon32"><br /></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ( $links as $link )
        echo $link;
    echo '</h2>';

}