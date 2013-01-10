<?php

/**
 * Add a meta box for bulk tabs
 *
 * @author Thad Allender
 * @since 1.0.9
 */
function sell_media_add_bulk_tabs_meta_box( $post_type ){
    if ( 'sell_media_item' == $post_type )
        add_action( 'edit_form_after_title', 'sell_media_add_bulk_tabs' );
}
add_action( 'add_meta_boxes', 'sell_media_add_bulk_tabs_meta_box' );


/**
 * Build bulk tabs
 *
 * @author Thad Allender
 * @since 1.0.9
 */
function sell_media_add_bulk_tabs(){
    $screen = get_current_screen();
    $single_active = null;
    $bulk_active = null;
    if ( 'sell_media_item' == $screen->id )
        $single_active = " nav-tab-active";
    if ( 'sell_media_item_page_sell_media_add_bulk' == $screen->id ) {
        $bulk_active = " nav-tab-active";
        echo '<div id="icon-edit" class="icon32 icon32-posts-sell_media_item"><br></div>';
        echo '<h2>' . __( 'Sell Media', 'sell_media' ) . '</h2>';
    }
    echo '<h2 id="sell-media-bulk-tabs" class="nav-tab-wrapper">';
    echo '<a href="' . admin_url( 'post-new.php?post_type=sell_media_item' ) . '" class="nav-tab' . $single_active . '">' . __( 'Add New', 'sell_media' ) . '</a>';
    echo '<a href="' . admin_url( 'edit.php?post_type=sell_media_item&page=sell_media_add_bulk' ) . '" class="nav-tab' . $bulk_active . '" >' . __( 'Add Bulk', 'sell_media' ) . '</a>';
    echo '</h2>';
}

/**
 * Build bulk page callback function
 * Called from add_subpage on main sell-media.php file
 *
 * @author Thad Allender
 * @since 1.0.9
 */
function sell_media_add_bulk_callback_fn(){ ?>
    <div class="wrap">
        <?php sell_media_add_bulk_tabs(); ?>
        <div class="tool-box add-bulk">
            <p><?php _e( 'All bulk uploads will inherit the default prices and licenses. You can modify the prices and licenses of each item after doing the bulk upload.', 'sell_media' ); ?></p>
            <p><a class="sell-media-upload-trigger-multiple button"id="_sell_media_button" value="Upload"><?php _e( 'Upload or Select Images', 'sell_media'); ?></a></p>
            <div class="sell_media_bulk_list">
            </div>
            <?php do_action( 'sell_media_bulk_below_uploader' ); ?>
        </div>
    </div>
<?php }