<?php
defined( 'ABSPATH' ) || exit;

/**
 * Registers the Room custom post type and related taxonomies.
 */
class Hotel_Booking_Post_Types {

    public static function register() {
        self::register_room_cpt();
        self::register_room_meta();
    }

    private static function register_room_cpt() {
        $labels = [
            'name'               => __( 'Rooms', 'hotel-booking' ),
            'singular_name'      => __( 'Room', 'hotel-booking' ),
            'menu_name'          => __( 'Rooms', 'hotel-booking' ),
            'add_new'            => __( 'Add Room', 'hotel-booking' ),
            'add_new_item'       => __( 'Add New Room', 'hotel-booking' ),
            'edit_item'          => __( 'Edit Room', 'hotel-booking' ),
            'new_item'           => __( 'New Room', 'hotel-booking' ),
            'view_item'          => __( 'View Room', 'hotel-booking' ),
            'search_items'       => __( 'Search Rooms', 'hotel-booking' ),
            'not_found'          => __( 'No rooms found', 'hotel-booking' ),
            'not_found_in_trash' => __( 'No rooms found in trash', 'hotel-booking' ),
        ];

        register_post_type( 'hb_room', [
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => 'hotel-booking',
            'show_in_rest'        => true,
            'query_var'           => true,
            'rewrite'             => [ 'slug' => 'room', 'with_front' => false ],
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
        ] );
    }

    private static function register_room_meta() {
        $meta_fields = [
            '_hb_price_per_night' => 'number',
            '_hb_capacity_adults' => 'integer',
            '_hb_capacity_children' => 'integer',
            '_hb_room_size'       => 'number',
            '_hb_bed_type'        => 'string',
            '_hb_amenities'       => 'string', // comma-separated
            '_hb_view'            => 'string',
            '_hb_floor'           => 'string',
        ];

        foreach ( $meta_fields as $key => $type ) {
            register_post_meta( 'hb_room', $key, [
                'single'       => true,
                'type'         => $type,
                'show_in_rest' => true,
                'auth_callback'=> function() {
                    return current_user_can( 'edit_posts' );
                },
            ] );
        }

        // Add meta boxes for admin
        add_action( 'add_meta_boxes', [ __CLASS__, 'add_room_meta_boxes' ] );
        add_action( 'save_post_hb_room', [ __CLASS__, 'save_room_meta' ] );
    }

    public static function add_room_meta_boxes() {
        add_meta_box(
            'hb_room_details',
            __( 'Room Details', 'hotel-booking' ),
            [ __CLASS__, 'render_room_meta_box' ],
            'hb_room',
            'normal',
            'high'
        );
    }

    public static function render_room_meta_box( $post ) {
        wp_nonce_field( 'hb_save_room_meta', 'hb_room_meta_nonce' );

        $price    = get_post_meta( $post->ID, '_hb_price_per_night', true );
        $adults   = get_post_meta( $post->ID, '_hb_capacity_adults', true ) ?: 2;
        $children = get_post_meta( $post->ID, '_hb_capacity_children', true ) ?: 0;
        $size     = get_post_meta( $post->ID, '_hb_room_size', true );
        $bed      = get_post_meta( $post->ID, '_hb_bed_type', true );
        $amenities= get_post_meta( $post->ID, '_hb_amenities', true );
        $view     = get_post_meta( $post->ID, '_hb_view', true );
        $floor    = get_post_meta( $post->ID, '_hb_floor', true );
        $quantity = get_post_meta( $post->ID, '_hb_room_quantity', true ) ?: 1;
        $gallery_ids = get_post_meta( $post->ID, '_hb_gallery_ids', true ) ?: '';
        ?>
        <style>
        .hb-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 12px 0; }
        .hb-meta-field label { display: block; font-weight: 600; margin-bottom: 4px; color: #1a2535; }
        .hb-meta-field input, .hb-meta-field select { width: 100%; }
        .hb-meta-full { grid-column: 1 / -1; }
        .hb-meta-sep { grid-column: 1 / -1; border: 0; border-top: 1px solid #e0e0e0; margin: 8px 0; }
        #hb-gallery-preview { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
        .hb-gallery-thumb { position: relative; width: 80px; height: 80px; border-radius: 4px; overflow: hidden; border: 2px solid #ddd; }
        .hb-gallery-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .hb-gallery-remove { position: absolute; top: 2px; right: 2px; background: rgba(0,0,0,0.7); color: #fff; border: none; border-radius: 50%; width: 18px; height: 18px; font-size: 12px; line-height: 1; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center; }
        </style>
        <div class="hb-meta-grid">
            <div class="hb-meta-field">
                <label for="hb_price"><?php esc_html_e( 'Price per Night (€)', 'hotel-booking' ); ?></label>
                <input type="number" id="hb_price" name="hb_price_per_night" value="<?php echo esc_attr( $price ); ?>" min="0" step="0.01" />
            </div>
            <div class="hb-meta-field">
                <label for="hb_size"><?php esc_html_e( 'Room Size (m²)', 'hotel-booking' ); ?></label>
                <input type="number" id="hb_size" name="hb_room_size" value="<?php echo esc_attr( $size ); ?>" min="0" />
            </div>
            <div class="hb-meta-field">
                <label for="hb_adults"><?php esc_html_e( 'Max Adults', 'hotel-booking' ); ?></label>
                <input type="number" id="hb_adults" name="hb_capacity_adults" value="<?php echo esc_attr( $adults ); ?>" min="1" max="10" />
            </div>
            <div class="hb-meta-field">
                <label for="hb_children"><?php esc_html_e( 'Max Children', 'hotel-booking' ); ?></label>
                <input type="number" id="hb_children" name="hb_capacity_children" value="<?php echo esc_attr( $children ); ?>" min="0" max="5" />
            </div>
            <div class="hb-meta-field">
                <label for="hb_bed_type"><?php esc_html_e( 'Bed Type', 'hotel-booking' ); ?></label>
                <select id="hb_bed_type" name="hb_bed_type">
                    <?php
                    $beds = [ '' => '— Select —', 'single' => 'Single', 'double' => 'Double', 'twin' => 'Twin', 'king' => 'King', 'suite' => 'Suite' ];
                    foreach ( $beds as $v => $l ) {
                        printf( '<option value="%s"%s>%s</option>', esc_attr( $v ), selected( $bed, $v, false ), esc_html( $l ) );
                    }
                    ?>
                </select>
            </div>
            <div class="hb-meta-field">
                <label for="hb_view"><?php esc_html_e( 'Room View', 'hotel-booking' ); ?></label>
                <input type="text" id="hb_view" name="hb_view" value="<?php echo esc_attr( $view ); ?>" placeholder="Rhine River View" />
            </div>
            <div class="hb-meta-field">
                <label for="hb_floor"><?php esc_html_e( 'Floor', 'hotel-booking' ); ?></label>
                <input type="text" id="hb_floor" name="hb_floor" value="<?php echo esc_attr( $floor ); ?>" placeholder="2nd Floor" />
            </div>
            <div class="hb-meta-field">
                <label for="hb_quantity"><?php esc_html_e( 'Units Available (rooms of this type)', 'hotel-booking' ); ?></label>
                <input type="number" id="hb_quantity" name="hb_room_quantity" value="<?php echo esc_attr( $quantity ); ?>" min="1" max="100" />
            </div>
            <div class="hb-meta-field hb-meta-full">
                <label for="hb_amenities"><?php esc_html_e( 'Amenities (comma-separated)', 'hotel-booking' ); ?></label>
                <input type="text" id="hb_amenities" name="hb_amenities" value="<?php echo esc_attr( $amenities ); ?>" placeholder="WiFi, TV, Minibar, Safe, AC, Bathrobe" />
            </div>

            <hr class="hb-meta-sep">

            <!-- Gallery Images -->
            <div class="hb-meta-field hb-meta-full">
                <label><?php esc_html_e( 'Room Photo Gallery', 'hotel-booking' ); ?></label>
                <div id="hb-gallery-preview">
                <?php
                if ( $gallery_ids ) {
                    foreach ( explode( ',', $gallery_ids ) as $gid ) {
                        $gid = absint( trim( $gid ) );
                        if ( ! $gid ) continue;
                        $thumb = wp_get_attachment_image_src( $gid, 'thumbnail' );
                        if ( $thumb ) {
                            printf(
                                '<div class="hb-gallery-thumb" data-id="%d"><img src="%s"><button type="button" class="hb-gallery-remove" title="Remove">&times;</button></div>',
                                $gid, esc_url( $thumb[0] )
                            );
                        }
                    }
                }
                ?>
                </div>
                <input type="hidden" id="hb_gallery_ids" name="hb_gallery_ids" value="<?php echo esc_attr( $gallery_ids ); ?>">
                <button type="button" id="hb-gallery-add" class="button"><?php esc_html_e( '+ Add Images', 'hotel-booking' ); ?></button>
                <p class="description" style="margin-top:6px;"><?php esc_html_e( 'Select multiple images from the media library. These appear in the room gallery on the website.', 'hotel-booking' ); ?></p>
            </div>
        </div>
        <?php
    }

    public static function save_room_meta( $post_id ) {
        if ( ! isset( $_POST['hb_room_meta_nonce'] ) || ! wp_verify_nonce( $_POST['hb_room_meta_nonce'], 'hb_save_room_meta' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        $fields = [
            '_hb_price_per_night'    => 'floatval',
            '_hb_capacity_adults'    => 'absint',
            '_hb_capacity_children'  => 'absint',
            '_hb_room_size'          => 'floatval',
            '_hb_bed_type'           => 'sanitize_text_field',
            '_hb_amenities'          => 'sanitize_text_field',
            '_hb_view'               => 'sanitize_text_field',
            '_hb_floor'              => 'sanitize_text_field',
            '_hb_room_quantity'      => 'absint',
            '_hb_gallery_ids'        => 'sanitize_text_field',
        ];

        $post_map = [
            '_hb_price_per_night'   => 'hb_price_per_night',
            '_hb_capacity_adults'   => 'hb_capacity_adults',
            '_hb_capacity_children' => 'hb_capacity_children',
            '_hb_room_size'         => 'hb_room_size',
            '_hb_bed_type'          => 'hb_bed_type',
            '_hb_amenities'         => 'hb_amenities',
            '_hb_view'              => 'hb_view',
            '_hb_floor'             => 'hb_floor',
            '_hb_room_quantity'     => 'hb_room_quantity',
            '_hb_gallery_ids'       => 'hb_gallery_ids',
        ];

        foreach ( $fields as $meta_key => $sanitizer ) {
            $post_key = $post_map[ $meta_key ];
            if ( isset( $_POST[ $post_key ] ) ) {
                update_post_meta( $post_id, $meta_key, $sanitizer( $_POST[ $post_key ] ) );
            }
        }
    }
}
