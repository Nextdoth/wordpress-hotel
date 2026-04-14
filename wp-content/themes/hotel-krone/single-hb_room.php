<?php
/**
 * Single template for hb_room post type.
 * Loads the shared single-hk_room.php template.
 */
$template = locate_template( 'single-hk_room.php' );
if ( $template ) {
    include $template;
}
