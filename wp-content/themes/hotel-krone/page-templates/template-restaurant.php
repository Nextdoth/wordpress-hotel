<?php
/**
 * Template Name: Restaurant La Corona
 * Template Post Type: page
 */
get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php esc_html_e( 'Fine Dining', 'hotel-krone' ); ?></span>
            <h1><?php esc_html_e( 'Restaurant', 'hotel-krone' ); ?></h1>
            <p class="hk-tagline">"La Corona"</p>
        </div>
    </div>
</div>

<!-- Restaurant Hero -->
<section class="hk-section" style="background:var(--white);">
    <div class="container">
        <?php hk_breadcrumbs(); ?>
        <?php get_template_part( 'template-parts/restaurant' ); ?>
    </div>
</section>

<!-- Menu Section -->
<section class="hk-section" style="background:var(--cream);">
    <div class="container">
        <div class="hk-section-header hk-animate">
            <span class="hk-section-tag"><?php esc_html_e( 'Our Menu', 'hotel-krone' ); ?></span>
            <h2><?php esc_html_e( 'Italian Flavours, Rhine Valley Setting', 'hotel-krone' ); ?></h2>
            <div class="hk-divider"></div>
        </div>

        <div class="grid-3" style="margin-bottom:48px;">

            <!-- Starters -->
            <div class="hk-animate hk-stagger-1">
                <div style="background:var(--white);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow-sm);border:1px solid var(--border);">
                    <h3 style="color:var(--gold);font-size:1.1rem;margin-bottom:20px;border-bottom:2px solid var(--gold-light);padding-bottom:10px;">
                        <?php esc_html_e( 'Antipasti', 'hotel-krone' ); ?>
                    </h3>
                    <?php $starters = [
                        [ 'Bruschetta al Pomodoro', '€7.50', __( 'Grilled bread, fresh tomatoes, basil', 'hotel-krone' ) ],
                        [ 'Carpaccio di Manzo', '€14.90', __( 'Thinly sliced beef, arugula, parmesan', 'hotel-krone' ) ],
                        [ 'Zuppa del Giorno', '€8.50', __( 'Soup of the day', 'hotel-krone' ) ],
                        [ 'Insalata Caprese', '€11.90', __( 'Buffalo mozzarella, tomatoes, basil oil', 'hotel-krone' ) ],
                    ];
                    foreach ( $starters as $item ) : ?>
                    <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:14px;padding-bottom:10px;border-bottom:1px dashed var(--border);">
                        <div>
                            <strong style="font-size:14px;"><?php echo esc_html( $item[0] ); ?></strong>
                            <p style="font-size:12px;color:var(--text-muted);margin:2px 0 0;"><?php echo esc_html( $item[2] ); ?></p>
                        </div>
                        <span style="color:var(--gold);font-weight:700;white-space:nowrap;margin-left:12px;"><?php echo esc_html( $item[1] ); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Mains -->
            <div class="hk-animate hk-stagger-2">
                <div style="background:var(--white);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow-sm);border:1px solid var(--border);">
                    <h3 style="color:var(--gold);font-size:1.1rem;margin-bottom:20px;border-bottom:2px solid var(--gold-light);padding-bottom:10px;">
                        <?php esc_html_e( 'Secondi', 'hotel-krone' ); ?>
                    </h3>
                    <?php $mains = [
                        [ 'Osso Buco alla Milanese', '€28.90', __( 'Braised veal shank, gremolata, saffron risotto', 'hotel-krone' ) ],
                        [ 'Filetto di Branzino', '€26.90', __( 'Sea bass fillet, lemon butter, capers', 'hotel-krone' ) ],
                        [ 'Pollo alla Romana', '€22.90', __( 'Roman-style chicken, peppers, white wine', 'hotel-krone' ) ],
                        [ 'Pasta Carbonara', '€18.90', __( 'Spaghetti, guanciale, egg yolk, pecorino', 'hotel-krone' ) ],
                    ];
                    foreach ( $mains as $item ) : ?>
                    <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:14px;padding-bottom:10px;border-bottom:1px dashed var(--border);">
                        <div>
                            <strong style="font-size:14px;"><?php echo esc_html( $item[0] ); ?></strong>
                            <p style="font-size:12px;color:var(--text-muted);margin:2px 0 0;"><?php echo esc_html( $item[2] ); ?></p>
                        </div>
                        <span style="color:var(--gold);font-weight:700;white-space:nowrap;margin-left:12px;"><?php echo esc_html( $item[1] ); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Desserts -->
            <div class="hk-animate hk-stagger-3">
                <div style="background:var(--white);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow-sm);border:1px solid var(--border);">
                    <h3 style="color:var(--gold);font-size:1.1rem;margin-bottom:20px;border-bottom:2px solid var(--gold-light);padding-bottom:10px;">
                        <?php esc_html_e( 'Dolci', 'hotel-krone' ); ?>
                    </h3>
                    <?php $desserts = [
                        [ 'Tiramisù della Casa', '€8.90', __( 'House tiramisù, espresso, mascarpone', 'hotel-krone' ) ],
                        [ 'Panna Cotta', '€7.90', __( 'Vanilla panna cotta, raspberry coulis', 'hotel-krone' ) ],
                        [ 'Gelato Artigianale', '€6.90', __( '3 scoops of artisanal ice cream', 'hotel-krone' ) ],
                        [ 'Cannolo Siciliano', '€7.50', __( 'Crispy shell, ricotta, candied orange', 'hotel-krone' ) ],
                    ];
                    foreach ( $desserts as $item ) : ?>
                    <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:14px;padding-bottom:10px;border-bottom:1px dashed var(--border);">
                        <div>
                            <strong style="font-size:14px;"><?php echo esc_html( $item[0] ); ?></strong>
                            <p style="font-size:12px;color:var(--text-muted);margin:2px 0 0;"><?php echo esc_html( $item[2] ); ?></p>
                        </div>
                        <span style="color:var(--gold);font-weight:700;white-space:nowrap;margin-left:12px;"><?php echo esc_html( $item[1] ); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <div style="text-align:center;background:var(--navy);border-radius:var(--radius);padding:32px 24px;" class="hk-animate">
            <p style="color:var(--gold-light);margin-bottom:16px;font-style:italic;"><?php esc_html_e( 'Table reservations recommended for dinner. Hotel guests receive 10% discount.', 'hotel-krone' ); ?></p>
            <a href="mailto:<?php echo esc_attr( hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' ) ); ?>?subject=Restaurant+Reservation+La+Corona" class="btn btn-primary">
                <?php esc_html_e( 'Reserve a Table', 'hotel-krone' ); ?>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
