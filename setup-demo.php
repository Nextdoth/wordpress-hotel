<?php
/**
 * Hotel Krone — Demo Data Seeder
 * Run once: http://localhost/hotel/wordpress-hotel/setup-demo.php
 * DELETE this file after running!
 */

define( 'ABSPATH_CHECK', true );
require_once __DIR__ . '/wp-load.php';

// Security — only run if not already done
if ( get_option( 'hk_demo_installed' ) ) {
    wp_die( '✅ Demo data already installed. Please delete setup-demo.php' );
}

if ( ! current_user_can( 'manage_options' ) ) {
    // Auto-login as first admin for CLI/setup purposes
    $admins = get_users( [ 'role' => 'administrator', 'number' => 1 ] );
    if ( $admins ) {
        wp_set_current_user( $admins[0]->ID );
    }
}

$log = [];

// ── 1. ROOMS ──────────────────────────────────────────────────
$rooms_data = [
    [
        'title'    => 'Classic Rhine View Room',
        'content'  => 'A beautifully appointed classic room with panoramic Rhine River views. Furnished with antique-inspired pieces and modern amenities, this room offers the perfect blend of historic charm and contemporary comfort.',
        'excerpt'  => 'Classic comfort with stunning Rhine River views.',
        'price'    => '129',
        'adults'   => '2',
        'children' => '1',
        'size'     => '22',
        'bed'      => 'Double',
        'view'     => 'Rhine River View',
        'floor'    => '2',
        'amenities'=> 'Free WiFi, Flat-screen TV, Minibar, Safe, Hair Dryer, Air Conditioning',
    ],
    [
        'title'    => 'Superior River Suite',
        'content'  => 'Our Superior River Suite offers expansive Rhine views from a private balcony. Featuring a separate living area, premium king-size bed, and luxury bathroom with rain shower, this suite is the ultimate Rhine River retreat.',
        'excerpt'  => 'Spacious suite with private balcony and Rhine panorama.',
        'price'    => '199',
        'adults'   => '2',
        'children' => '2',
        'size'     => '38',
        'bed'      => 'King',
        'view'     => 'Rhine River View',
        'floor'    => '3',
        'amenities'=> 'Free WiFi, Private Balcony, Flat-screen TV, Minibar, Espresso Machine, Safe, Bathrobe, Slippers',
    ],
    [
        'title'    => 'Deluxe Garden Room',
        'content'  => 'A peaceful garden-facing room with elegant décor and a serene atmosphere. Perfect for guests who prefer a quieter retreat while still enjoying all the hotel\'s premium amenities.',
        'excerpt'  => 'Tranquil garden views with elegant furnishings.',
        'price'    => '109',
        'adults'   => '2',
        'children' => '0',
        'size'     => '20',
        'bed'      => 'Double',
        'view'     => 'Garden View',
        'floor'    => '1',
        'amenities'=> 'Free WiFi, Flat-screen TV, Safe, Hair Dryer, Air Conditioning',
    ],
    [
        'title'    => 'Family Rhine Suite',
        'content'  => 'Designed for families, this spacious suite features a king bedroom with connecting twin room, perfect for families with children. Enjoy Rhine River views and generous living space for everyone to relax.',
        'excerpt'  => 'Spacious family suite connecting two rooms with Rhine views.',
        'price'    => '249',
        'adults'   => '4',
        'children' => '2',
        'size'     => '55',
        'bed'      => 'King + Twin',
        'view'     => 'Rhine River View',
        'floor'    => '2',
        'amenities'=> 'Free WiFi, Two Bathrooms, Flat-screen TV, Minibar, Safe, Children\'s Welcome Pack',
    ],
    [
        'title'    => 'Historic Krone Suite',
        'content'  => 'Our most prestigious suite, the Historic Krone Suite occupies the original corner of the hotel building dating to the 19th century. Original exposed beams, antique furnishings, and an enormous Rhine-facing terrace make this a truly unique experience.',
        'excerpt'  => 'Our most prestigious suite with original historic features and panoramic Rhine terrace.',
        'price'    => '329',
        'adults'   => '2',
        'children' => '1',
        'size'     => '68',
        'bed'      => 'King',
        'view'     => 'Panoramic Rhine View',
        'floor'    => '4',
        'amenities'=> 'Free WiFi, Private Terrace, Jacuzzi, Flat-screen TV, Full Minibar, Espresso Machine, Safe, Bathrobe, Slippers, Welcome Champagne',
    ],
    [
        'title'    => 'Standard Single Room',
        'content'  => 'A comfortable and well-appointed single room, ideal for solo travellers. Cosy yet functional, with all essential amenities and a peaceful atmosphere.',
        'excerpt'  => 'Cosy and comfortable for solo travellers.',
        'price'    => '79',
        'adults'   => '1',
        'children' => '0',
        'size'     => '16',
        'bed'      => 'Single',
        'view'     => 'Courtyard View',
        'floor'    => '1',
        'amenities'=> 'Free WiFi, Flat-screen TV, Hair Dryer',
    ],
];

$post_type = post_type_exists( 'hb_room' ) ? 'hb_room' : 'hk_room';

foreach ( $rooms_data as $i => $room ) {
    $post_id = wp_insert_post( [
        'post_type'    => $post_type,
        'post_title'   => $room['title'],
        'post_content' => $room['content'],
        'post_excerpt' => $room['excerpt'],
        'post_status'  => 'publish',
        'menu_order'   => $i,
    ] );
    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, '_hb_price_per_night',   $room['price'] );
        update_post_meta( $post_id, '_hb_capacity_adults',   $room['adults'] );
        update_post_meta( $post_id, '_hb_capacity_children', $room['children'] );
        update_post_meta( $post_id, '_hb_room_size',         $room['size'] );
        update_post_meta( $post_id, '_hb_bed_type',          $room['bed'] );
        update_post_meta( $post_id, '_hb_view',              $room['view'] );
        update_post_meta( $post_id, '_hb_floor',             $room['floor'] );
        update_post_meta( $post_id, '_hb_amenities',         $room['amenities'] );
        $log[] = "✅ Room created: {$room['title']}";
    }
}

// ── 2. TESTIMONIALS ───────────────────────────────────────────
$testimonials = [
    [
        'name'     => 'Margarete & Hans Fischer',
        'location' => 'München, Germany',
        'rating'   => 5,
        'date'     => '2024-08',
        'text'     => 'Absolut wunderbar! Das Zimmer mit Rheinblick war traumhaft. Das Frühstück war ausgezeichnet und das Personal extrem freundlich. Wir kommen definitiv wieder.',
    ],
    [
        'name'     => 'Sarah & James Thompson',
        'location' => 'London, UK',
        'rating'   => 5,
        'date'     => '2024-07',
        'text'     => 'What an incredible find! The hotel is steeped in history and the Rhine views from our room were simply breathtaking. Restaurant La Corona served the best pasta we\'ve had outside of Italy.',
    ],
    [
        'name'     => 'François Dubois',
        'location' => 'Paris, France',
        'rating'   => 5,
        'date'     => '2024-06',
        'text'     => 'Un hôtel magnifique avec une histoire riche. La vue sur le Rhin est à couper le souffle. Le personnel est aux petits soins. Je recommande vivement.',
    ],
    [
        'name'     => 'Anita van der Berg',
        'location' => 'Amsterdam, Netherlands',
        'rating'   => 5,
        'date'     => '2024-09',
        'text'     => 'Perfect location on the Rhine. The room was spotlessly clean and the bed incredibly comfortable. La Corona restaurant was a highlight — wonderful Italian food with a stunning river view.',
    ],
    [
        'name'     => 'Michael & Lisa Chen',
        'location' => 'Singapore',
        'rating'   => 5,
        'date'     => '2024-05',
        'text'     => 'We came for the UNESCO heritage landscape and stayed for the hospitality. This historic hotel exceeded every expectation. The suite with private balcony was worth every penny.',
    ],
    [
        'name'     => 'Elena Rossi',
        'location' => 'Rome, Italy',
        'rating'   => 5,
        'date'     => '2024-08',
        'text'     => 'Come italiana, sono rimasta sorpresa dalla qualità del ristorante La Corona. Autentica cucina italiana in un ambiente storico straordinario. Un\'esperienza indimenticabile.',
    ],
];

foreach ( $testimonials as $t ) {
    $post_id = wp_insert_post( [
        'post_type'    => 'hk_testimonial',
        'post_title'   => $t['name'],
        'post_content' => $t['text'],
        'post_status'  => 'publish',
    ] );
    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, '_hk_guest_name',     $t['name'] );
        update_post_meta( $post_id, '_hk_guest_location', $t['location'] );
        update_post_meta( $post_id, '_hk_rating',         $t['rating'] );
        update_post_meta( $post_id, '_hk_stay_date',      $t['date'] );
        $log[] = "✅ Testimonial created: {$t['name']}";
    }
}

// ── 3. PAGES ──────────────────────────────────────────────────
$pages = [
    [
        'title'    => 'Home',
        'slug'     => 'home',
        'template' => '',
        'content'  => '',
    ],
    [
        'title'    => 'Rooms & Suites',
        'slug'     => 'rooms',
        'template' => 'page-templates/template-rooms.php',
        'content'  => '',
    ],
    [
        'title'    => 'Restaurant La Corona',
        'slug'     => 'restaurant',
        'template' => 'page-templates/template-restaurant.php',
        'content'  => '',
    ],
    [
        'title'    => 'Contact',
        'slug'     => 'contact',
        'template' => 'page-templates/template-contact.php',
        'content'  => '',
    ],
    [
        'title'    => 'About',
        'slug'     => 'about',
        'template' => 'page-templates/template-about.php',
        'content'  => '',
    ],
    [
        'title'    => 'Gallery',
        'slug'     => 'gallery',
        'template' => 'page-templates/template-gallery.php',
        'content'  => '',
    ],
    [
        'title'    => 'Book',
        'slug'     => 'book',
        'template' => '',
        'content'  => '[hotel_booking]',
    ],
];

$created_pages = [];
foreach ( $pages as $page ) {
    $existing = get_page_by_path( $page['slug'] );
    if ( $existing ) {
        $created_pages[ $page['slug'] ] = $existing->ID;
        $log[] = "⏭ Page already exists: {$page['title']}";
        continue;
    }
    $post_id = wp_insert_post( [
        'post_type'    => 'page',
        'post_title'   => $page['title'],
        'post_name'    => $page['slug'],
        'post_content' => $page['content'],
        'post_status'  => 'publish',
    ] );
    if ( $post_id && ! is_wp_error( $post_id ) ) {
        if ( $page['template'] ) {
            update_post_meta( $post_id, '_wp_page_template', $page['template'] );
        }
        $created_pages[ $page['slug'] ] = $post_id;
        $log[] = "✅ Page created: {$page['title']}";
    }
}

// ── 4. SET FRONT PAGE ─────────────────────────────────────────
if ( isset( $created_pages['home'] ) ) {
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $created_pages['home'] );
    $log[] = "✅ Front page set to: Home";
}

// ── 5. CREATE PRIMARY MENU ────────────────────────────────────
$menu_name = 'Primary Menu';
$menu_exists = wp_get_nav_menu_object( $menu_name );
if ( ! $menu_exists ) {
    $menu_id = wp_create_nav_menu( $menu_name );
    if ( ! is_wp_error( $menu_id ) ) {
        $menu_items = [
            [ 'Home',              'home' ],
            [ 'Rooms & Suites',    'rooms' ],
            [ 'Restaurant',        'restaurant' ],
            [ 'Gallery',           'gallery' ],
            [ 'About',             'about' ],
            [ 'Contact',           'contact' ],
        ];
        foreach ( $menu_items as $item ) {
            $page = get_page_by_path( $item[1] );
            if ( $page ) {
                wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title'     => $item[0],
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ] );
            }
        }
        // Add Book Now as last item
        wp_update_nav_menu_item( $menu_id, 0, [
            'menu-item-title'  => 'Book Now',
            'menu-item-url'    => home_url( '/book/' ),
            'menu-item-type'   => 'custom',
            'menu-item-classes'=> 'hk-nav-book',
            'menu-item-status' => 'publish',
        ] );

        // Assign to theme location
        $locations = get_theme_mod( 'nav_menu_locations', [] );
        $locations['primary'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
        $log[] = "✅ Menu created and assigned: {$menu_name}";
    }
} else {
    $log[] = "⏭ Menu already exists: {$menu_name}";
}

// ── 6. CUSTOMIZER DEFAULTS ────────────────────────────────────
$customizer_defaults = [
    'hk_phone'            => '+49 6742 2313',
    'hk_email'            => 'info@rhein-hotel-krone.de',
    'hk_address'          => 'Rheinuferstraße 8, 56154 Boppard am Rhein',
    'hk_founded_year'     => '950',
    'hk_total_rooms'      => '35',
    'hk_review_score'     => '4.8',
    'hk_years_experience' => '1000',
    'hk_restaurant_hours' => 'Daily 12:00–14:30 & 18:00–22:00',
    'hk_hero_title'       => 'Historic Elegance on the Rhine',
    'hk_hero_subtitle'    => 'Experience over 1,000 years of hospitality on the shores of the Middle Rhine',
];
foreach ( $customizer_defaults as $key => $value ) {
    if ( ! get_theme_mod( $key ) ) {
        set_theme_mod( $key, $value );
    }
}
$log[] = "✅ Customizer defaults set";

// ── 7. PERMALINK FLUSH ────────────────────────────────────────
update_option( 'permalink_structure', '/%postname%/' );
flush_rewrite_rules();
$log[] = "✅ Permalinks flushed (set to /%postname%/)";

// ── 8. MARK DONE ──────────────────────────────────────────────
update_option( 'hk_demo_installed', true );

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Hotel Krone Setup</title>
<style>
  body { font-family: -apple-system, sans-serif; background: #0D1620; color: #fff; padding: 40px; }
  h1   { color: #C9A84C; font-size: 2rem; margin-bottom: 8px; }
  h2   { color: #C9A84C; font-size: 1.1rem; font-weight: 400; margin-bottom: 32px; opacity: 0.7; }
  .log { background: #1A2535; border-radius: 8px; padding: 24px; max-width: 600px; }
  .log p { margin: 6px 0; font-size: 14px; }
  .cta { margin-top: 32px; }
  .btn { display: inline-block; background: #C9A84C; color: #0D1620; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: 700; margin-right: 12px; }
  .btn-o { background: transparent; border: 2px solid #C9A84C; color: #C9A84C; }
  .warn { margin-top: 24px; background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.3); border-radius: 6px; padding: 16px; font-size: 13px; color: #F5E9C0; max-width: 600px; }
</style>
</head>
<body>
<h1>✅ Hotel Krone Setup Complete</h1>
<h2>Demo data successfully installed</h2>
<div class="log">
  <?php foreach ( $log as $line ) echo "<p>{$line}</p>"; ?>
</div>
<div class="cta">
  <a href="<?php echo home_url('/'); ?>" class="btn">View Website</a>
  <a href="<?php echo admin_url(); ?>" class="btn btn-o">WordPress Admin</a>
</div>
<div class="warn">
  ⚠️ <strong>Important:</strong> Delete <code>setup-demo.php</code> from your server root after viewing the site!
</div>
</body>
</html>
