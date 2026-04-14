<?php
/**
 * Template Name: About / History
 * Template Post Type: page
 */
get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php esc_html_e( 'Our Story', 'hotel-krone' ); ?></span>
            <h1><?php esc_html_e( 'History & Heritage', 'hotel-krone' ); ?></h1>
            <p><?php esc_html_e( 'Over a thousand years of hospitality on the legendary Rhine River', 'hotel-krone' ); ?></p>
        </div>
    </div>
</div>

<!-- Heritage Intro -->
<section class="hk-section" style="background:var(--white);">
    <div class="container">
        <?php hk_breadcrumbs(); ?>
        <div class="hk-about-inner">

            <div class="hk-about-image hk-animate-left">
                <img src="<?php echo esc_url( HK_URI . '/assets/images/hotel-exterior.jpg' ); ?>"
                     alt="<?php esc_attr_e( 'Rhein-Hotel Krone historic exterior', 'hotel-krone' ); ?>"
                     width="600" height="720"
                     onerror="this.style.background='var(--cream-dark)';this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'">
                <div class="hk-about-badge">
                    <span class="year">~950</span>
                    <span class="label">AD</span>
                </div>
            </div>

            <div class="hk-about-text hk-animate-right">
                <span class="hk-section-tag"><?php esc_html_e( 'Founded ~950 AD', 'hotel-krone' ); ?></span>
                <h2><?php esc_html_e( 'A Living Piece of German History', 'hotel-krone' ); ?></h2>
                <p class="hk-tagline"><?php esc_html_e( '"More than a hotel — a chapter of the Rhine"', 'hotel-krone' ); ?></p>
                <p><?php esc_html_e( 'The Rhein-Hotel Krone is one of Germany\'s oldest continuously operating hotels. Its origins trace back to around 950 AD, when Boppard was a thriving Roman town and the Rhine River was the lifeblood of European commerce and travel.', 'hotel-krone' ); ?></p>
                <p><?php esc_html_e( 'Over the centuries, the hotel has welcomed emperors, merchants, poets, and pilgrims. Today, it continues that proud tradition — combining centuries of authentic hospitality with every modern comfort, all against the breathtaking backdrop of the Middle Rhine Valley.', 'hotel-krone' ); ?></p>
                <p><?php esc_html_e( 'The hotel\'s distinctive "Krone" (Crown) name reflects its regal place in the community — a crown jewel of the Rhine, offering guests a rare chance to sleep where history was made.', 'hotel-krone' ); ?></p>
            </div>

        </div>
    </div>
</section>

<!-- Timeline -->
<section class="hk-section" style="background:var(--cream);">
    <div class="container">
        <div class="hk-section-header hk-animate">
            <span class="hk-section-tag"><?php esc_html_e( 'Timeline', 'hotel-krone' ); ?></span>
            <h2><?php esc_html_e( 'Milestones Through the Centuries', 'hotel-krone' ); ?></h2>
            <div class="hk-divider"></div>
        </div>

        <div class="hk-timeline">
            <?php
            $timeline = [
                [ '~950 AD',  __( 'Foundations', 'hotel-krone' ),       __( 'The first guesthouse on this site opens to welcome travellers and merchants along the Rhine trade route.', 'hotel-krone' ) ],
                [ '1300s',    __( 'Medieval Growth', 'hotel-krone' ),    __( 'Boppard becomes an important town of the Holy Roman Empire. The inn gains recognition as a premier resting place for noble travellers.', 'hotel-krone' ) ],
                [ '1800s',    __( 'Romantic Rhine Era', 'hotel-krone' ), __( 'The Romantic movement brings artists, poets and tourists to the Rhine Valley. The hotel expands to accommodate the growing wave of visitors.', 'hotel-krone' ) ],
                [ '1900s',    __( 'Modern Renovation', 'hotel-krone' ),  __( 'Extensive renovations blend historic character with modern amenities. Restaurant La Corona opens, introducing Italian cuisine to the Rhine.', 'hotel-krone' ) ],
                [ '2000s',    __( 'UNESCO Recognition', 'hotel-krone' ), __( 'The Upper Middle Rhine Valley, home to our hotel, is inscribed as a UNESCO World Heritage Site — cementing Boppard\'s global significance.', 'hotel-krone' ) ],
                [ 'Today',    __( 'Living Legacy', 'hotel-krone' ),      __( 'Rhein-Hotel Krone continues to welcome guests from around the world, honouring its thousand-year tradition of warm Rhine hospitality.', 'hotel-krone' ) ],
            ];
            foreach ( $timeline as $i => $entry ) : ?>
            <div class="hk-timeline-item hk-animate hk-stagger-<?php echo ( $i % 5 ) + 1; ?>">
                <div class="hk-timeline-year"><?php echo esc_html( $entry[0] ); ?></div>
                <div class="hk-timeline-dot"></div>
                <div class="hk-timeline-content">
                    <h4><?php echo esc_html( $entry[1] ); ?></h4>
                    <p><?php echo esc_html( $entry[2] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Location & UNESCO -->
<section class="hk-section" style="background:var(--navy);">
    <div class="container">
        <div class="grid-2" style="gap:60px;align-items:center;">

            <div class="hk-animate-left">
                <span class="hk-section-tag"><?php esc_html_e( 'Location', 'hotel-krone' ); ?></span>
                <h2 style="color:var(--white);"><?php esc_html_e( 'UNESCO World Heritage Setting', 'hotel-krone' ); ?></h2>
                <p style="color:rgba(255,255,255,0.7);"><?php esc_html_e( 'Boppard am Rhein sits at the heart of the Upper Middle Rhine Valley — designated a UNESCO World Heritage Site in 2002. Surrounded by terraced vineyards, medieval castles, and the ever-flowing Rhine, our hotel is positioned in one of Europe\'s most captivating landscapes.', 'hotel-krone' ); ?></p>
                <p style="color:rgba(255,255,255,0.7);"><?php esc_html_e( 'The famous Rhine Gorge, stretching 65 km through dramatic slate cliffs, is accessible directly from our doorstep. Board a Rhine cruise, hike the vineyard trails, or simply watch the river from your room window.', 'hotel-krone' ); ?></p>
                <div style="margin-top:28px;display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="<?php echo esc_url( home_url( '/rooms/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'View Our Rooms', 'hotel-krone' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Get in Touch', 'hotel-krone' ); ?></a>
                </div>
            </div>

            <div class="hk-animate-right">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <?php
                    $facts = [
                        [ hk_get_option( 'hk_founded_year', '950' ),  __( 'Founded', 'hotel-krone' ) ],
                        [ hk_get_option( 'hk_total_rooms', '35' ),     __( 'Rooms', 'hotel-krone' ) ],
                        [ '2002',                                       __( 'UNESCO Year', 'hotel-krone' ) ],
                        [ hk_get_option( 'hk_review_score', '4.8' ) . '★', __( 'Rating', 'hotel-krone' ) ],
                    ];
                    foreach ( $facts as $fact ) : ?>
                    <div style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:var(--radius);padding:28px;text-align:center;">
                        <div style="font-family:var(--font-elegant);font-size:2.8rem;font-weight:300;color:var(--gold);line-height:1;"><?php echo esc_html( $fact[0] ); ?></div>
                        <div style="font-family:var(--font-display);font-size:10px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-top:8px;"><?php echo esc_html( $fact[1] ); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Team / Values -->
<section class="hk-section" style="background:var(--white);">
    <div class="container">
        <div class="hk-section-header hk-animate">
            <span class="hk-section-tag"><?php esc_html_e( 'Our Values', 'hotel-krone' ); ?></span>
            <h2><?php esc_html_e( 'What Makes Us Special', 'hotel-krone' ); ?></h2>
            <div class="hk-divider"></div>
        </div>

        <div class="grid-3">
            <?php
            $values = [
                [ 'river',     __( 'Location', 'hotel-krone' ),      __( 'Directly on the Rhine riverbank, at the heart of the UNESCO World Heritage Upper Middle Rhine Valley.', 'hotel-krone' ) ],
                [ 'restaurant',__( 'Fine Dining', 'hotel-krone' ),   __( 'Our La Corona restaurant brings authentic Italian cuisine and curated Rhine Valley wines to your table.', 'hotel-krone' ) ],
                [ 'guests',    __( 'Personal Service', 'hotel-krone' ), __( 'Our family-run team treats every guest as a valued friend — personal, attentive, and genuinely warm.', 'hotel-krone' ) ],
                [ 'bed',       __( 'Comfort', 'hotel-krone' ),        __( 'All 35 rooms blend historic charm with modern comfort — premium beds, spotless bathrooms, and Rhine views.', 'hotel-krone' ) ],
                [ 'cruise',    __( 'Experiences', 'hotel-krone' ),    __( 'Rhine river cruises, vineyard hikes, castle visits, wine tastings — we arrange it all.', 'hotel-krone' ) ],
                [ 'star',      __( 'Heritage', 'hotel-krone' ),       __( 'Over a thousand years of unbroken hospitality — we are living proof that great traditions never go out of style.', 'hotel-krone' ) ],
            ];
            foreach ( $values as $i => $val ) : ?>
            <div class="hk-animate hk-stagger-<?php echo ( $i % 5 ) + 1; ?>" style="text-align:center;padding:32px 24px;border:1px solid var(--border);border-radius:var(--radius);">
                <div style="width:56px;height:56px;background:var(--gold-pale);border:1px solid var(--gold-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:var(--gold);">
                    <?php echo hk_icon( $val[0] ); ?>
                </div>
                <h4 style="margin-bottom:10px;"><?php echo esc_html( $val[1] ); ?></h4>
                <p style="font-size:14px;color:var(--text-muted);margin:0;"><?php echo esc_html( $val[2] ); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_template_part( 'template-parts/cta-banner' ); ?>

<?php get_footer(); ?>
