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

	$page = 'gpp-settings';

	if ( isset( $_GET['page'] ) && 'gpp-reference' == $_GET['page'] ) {
		$page = 'gpp-reference';
	} else {
		// do nothing
	}

    $current = sell_media_get_current_tab();

	if ( 'gpp-settings' == $page ) {
        $tabs = $sell_media_tabs;
	} else if ( 'gpp-reference' == $page ) {
        $tabs = sell_media_get_reference_page_tabs();
	}

    $links = array();
    $i = 0;
    foreach( $tabs as $tab ) {
		if( isset( $tab['name'] ) )
			$tabname = $tab['name'];
		if( isset( $tab['title'] ) )
			$tabtitle = $tab['title'];
        if ( $tabname == $current ) {
            $links[] = "<a class='nav-tab nav-tab-active' href='?page=$page&tab=$tabname&i=$i'>$tabtitle</a>";
        } else {
            $links[] = "<a class='nav-tab' href='?page=$page&tab=$tabname&i=$i'>$tabtitle</a>";
        }
        $i++;
    }
    sell_media_utility_links();
    echo '<div id="icon-themes" class="icon32"><br /></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ( $links as $link )
        echo $link;
    echo '</h2>';

}