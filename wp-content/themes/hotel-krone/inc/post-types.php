<?php
defined( 'ABSPATH' ) || exit;

/**
 * Register theme-specific custom post types and taxonomies.
 * Note: Room CPT (hb_room) is registered by the Hotel Booking plugin.
 * This file handles theme-only CPTs: Testimonials, Gallery.
 */

function hk_register_post_types() {

    // ─── Testimonials ────────────────────────────────────────────────────
    register_post_type( 'hk_testimonial', [
        'labels' => [
            'name'          => __( 'Testimonials', 'hotel-krone' ),
            'singular_name' => __( 'Testimonial', 'hotel-krone' ),
            'add_new_item'  => __( 'Add Testimonial', 'hotel-krone' ),
            'edit_item'     => __( 'Edit Testimonial', 'hotel-krone' ),
        ],
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_rest'      => true,
        'supports'          => [ 'title', 'editor', 'thumbnail' ],
        'menu_icon'         => 'dashicons-star-filled',
        'menu_position'     => 25,
    ] );

    // ─── Testimonial meta boxes ────────────────────────────────────────
    add_action( 'add_meta_boxes', function() {
        add_meta_box(
            'hk_testimonial_meta',
            __( 'Guest Details', 'hotel-krone' ),
            'hk_render_testimonial_meta',
            'hk_testimonial',
            'normal',
            'high'
        );
    });

    // ─── Gallery Items ────────────────────────────────────────────────
    register_post_type( 'hk_gallery', [
        'labels' => [
            'name'          => __( 'Gallery', 'hotel-krone' ),
            'singular_name' => __( 'Gallery Item', 'hotel-krone' ),
            'add_new_item'  => __( 'Add Photo', 'hotel-krone' ),
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title', 'thumbnail', 'page-attributes' ],
        'menu_icon'     => 'dashicons-format-gallery',
        'menu_position' => 26,
    ] );
}
add_action( 'init', 'hk_register_post_types' );

/**
 * Render testimonial meta box.
 */
function hk_render_testimonial_meta( $post ) {
    wp_nonce_field( 'hk_save_testimonial', 'hk_testimonial_nonce' );

    $guest    = get_post_meta( $post->ID, '_hk_guest_name', true );
    $location = get_post_meta( $post->ID, '_hk_guest_location', true );
    $rating   = get_post_meta( $post->ID, '_hk_rating', true ) ?: 5;
    $date     = get_post_meta( $post->ID, '_hk_stay_date', true );
    ?>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:10px 0;">
        <div>
            <label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Guest Name', 'hotel-krone' ); ?></label>
            <input type="text" name="hk_guest_name" value="<?php echo esc_attr( $guest ); ?>" style="width:100%;">
        </div>
        <div>
            <label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Location', 'hotel-krone' ); ?></label>
            <input type="text" name="hk_guest_location" value="<?php echo esc_attr( $location ); ?>" placeholder="e.g. Frankfurt, Germany" style="width:100%;">
        </div>
        <div>
            <label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Rating (1-5)', 'hotel-krone' ); ?></label>
            <select name="hk_rating" style="width:100%;">
                <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                <option value="<?php echo esc_attr( $i ); ?>" <?php selected( (int) $rating, $i ); ?>><?php echo str_repeat( '★', $i ) . str_repeat( '☆', 5 - $i ); ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Stay Date', 'hotel-krone' ); ?></label>
            <input type="month" name="hk_stay_date" value="<?php echo esc_attr( $date ); ?>" style="width:100%;">
        </div>
    </div>
    <?php
}

/**
 * Save testimonial meta.
 */
function hk_save_testimonial_meta( $post_id ) {
    if ( ! isset( $_POST['hk_testimonial_nonce'] ) || ! wp_verify_nonce( $_POST['hk_testimonial_nonce'], 'hk_save_testimonial' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    update_post_meta( $post_id, '_hk_guest_name',     sanitize_text_field( $_POST['hk_guest_name'] ?? '' ) );
    update_post_meta( $post_id, '_hk_guest_location', sanitize_text_field( $_POST['hk_guest_location'] ?? '' ) );
    update_post_meta( $post_id, '_hk_rating',         absint( $_POST['hk_rating'] ?? 5 ) );
    update_post_meta( $post_id, '_hk_stay_date',      sanitize_text_field( $_POST['hk_stay_date'] ?? '' ) );
}
add_action( 'save_post_hk_testimonial', 'hk_save_testimonial_meta' );
