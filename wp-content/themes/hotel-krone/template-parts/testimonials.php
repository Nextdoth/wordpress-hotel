<?php
defined( 'ABSPATH' ) || exit;

$testimonials = hk_get_testimonials( 6 );

// Fallback testimonials if none exist
if ( empty( $testimonials ) ) {
    $testimonials = [
        (object) [
            'post_title'   => 'Wonderful Rhine River Experience',
            'post_content' => 'The view from our room was absolutely breathtaking. Waking up to the Rhine River every morning was magical. The staff was warm, attentive, and made us feel like royalty. We will definitely return!',
            'guest_name'   => 'Sarah M.',
            'location'     => 'London, UK',
            'rating'       => 5,
            'date'         => 'March 2024',
            'is_fallback'  => true,
        ],
        (object) [
            'post_title'   => 'Perfect Getaway in the Rhine Valley',
            'post_content' => 'Ein wunderschönes Hotel mit unglaublicher Aussicht auf den Rhein. Das Essen im La Corona war hervorragend. Das Frühstück jeden Morgen war ein Traum. Absolut zu empfehlen!',
            'guest_name'   => 'Klaus B.',
            'location'     => 'München, Deutschland',
            'rating'       => 5,
            'date'         => 'February 2024',
            'is_fallback'  => true,
        ],
        (object) [
            'post_title'   => 'Historic Charm with Modern Comfort',
            'post_content' => 'Staying in a hotel that has been welcoming guests for over 1000 years is truly special. The rooms are beautifully maintained, the restaurant La Corona is excellent, and Boppard is a charming town.',
            'guest_name'   => 'Jean-Pierre L.',
            'location'     => 'Paris, France',
            'rating'       => 5,
            'date'         => 'January 2024',
            'is_fallback'  => true,
        ],
        (object) [
            'post_title'   => 'Romantic Rhine Holiday',
            'post_content' => 'My wife and I celebrated our anniversary here and it was perfect. The staff arranged a special dinner, the room had amazing river views, and the whole atmosphere was romantic and tranquil.',
            'guest_name'   => 'Thomas K.',
            'location'     => 'Amsterdam, Netherlands',
            'rating'       => 5,
            'date'         => 'December 2023',
            'is_fallback'  => true,
        ],
        (object) [
            'post_title'   => 'Excellent Base for Rhine Valley',
            'post_content' => 'The hotel\'s location in Boppard is ideal for exploring the Rhine Valley by boat or car. The breakfast is generous and delicious. The room was spotlessly clean and very comfortable.',
            'guest_name'   => 'Emma W.',
            'location'     => 'Sydney, Australia',
            'rating'       => 4,
            'date'         => 'November 2023',
            'is_fallback'  => true,
        ],
        (object) [
            'post_title'   => 'Sehr empfehlenswertes Hotel',
            'post_content' => 'Sehr freundliches Personal, saubere und gemütliche Zimmer, hervorragendes Frühstück. Der Ausblick auf den Rhein ist unvergesslich. Wir kommen auf jeden Fall wieder!',
            'guest_name'   => 'Ursula F.',
            'location'     => 'Hamburg, Deutschland',
            'rating'       => 5,
            'date'         => 'October 2023',
            'is_fallback'  => true,
        ],
    ];
}
?>

<section class="hk-testimonials hk-section" id="reviews">
    <div class="container">

        <div class="hk-section-header hk-animate">
            <span class="hk-section-tag"><?php esc_html_e( 'Guest Reviews', 'hotel-krone' ); ?></span>
            <h2><?php esc_html_e( 'What Our Guests Say', 'hotel-krone' ); ?></h2>
            <div class="hk-divider" style="background:linear-gradient(90deg,var(--gold),var(--gold-light));"></div>
        </div>

        <div class="hk-testimonials-slider">
            <div class="hk-testimonials-track">
                <?php foreach ( $testimonials as $t ) :
                    if ( isset( $t->is_fallback ) && $t->is_fallback ) {
                        $name    = $t->guest_name;
                        $loc     = $t->location;
                        $rating  = $t->rating;
                        $text    = $t->post_content;
                        $date    = $t->date;
                    } else {
                        $name    = get_post_meta( $t->ID, '_hk_guest_name', true ) ?: $t->post_title;
                        $loc     = get_post_meta( $t->ID, '_hk_guest_location', true );
                        $rating  = (int) ( get_post_meta( $t->ID, '_hk_rating', true ) ?: 5 );
                        $text    = $t->post_content;
                        $date    = get_post_meta( $t->ID, '_hk_stay_date', true );
                    }
                    $initial = strtoupper( substr( $name, 0, 1 ) );
                ?>
                <div class="hk-testimonial-slide">
                    <div class="hk-testimonial-card">
                        <?php echo hk_star_rating( $rating ); ?>
                        <div class="hk-testimonial-quote">"</div>
                        <p class="hk-testimonial-text"><?php echo esc_html( wp_trim_words( $text, 40, '…' ) ); ?></p>
                        <div class="hk-testimonial-footer">
                            <div class="hk-testimonial-avatar"><?php echo esc_html( $initial ); ?></div>
                            <div class="hk-testimonial-meta">
                                <h5><?php echo esc_html( $name ); ?></h5>
                                <span><?php echo esc_html( $loc ); ?><?php if ( $date ) echo ' · ' . esc_html( $date ); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Slider Dots -->
        <div class="hk-slider-dots" role="tablist" aria-label="<?php esc_attr_e( 'Testimonial Slides', 'hotel-krone' ); ?>"></div>

    </div>
</section>
