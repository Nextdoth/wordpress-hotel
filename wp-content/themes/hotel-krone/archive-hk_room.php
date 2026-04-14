<?php get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php esc_html_e( 'Accommodation', 'hotel-krone' ); ?></span>
            <h1><?php esc_html_e( 'Our Rooms & Suites', 'hotel-krone' ); ?></h1>
            <p><?php esc_html_e( 'Choose your perfect retreat with Rhine River views', 'hotel-krone' ); ?></p>
        </div>
    </div>
</div>

<section class="hk-section hk-rooms">
    <div class="container">
        <?php hk_breadcrumbs(); ?>

        <?php if ( have_posts() ) : ?>
        <div class="hk-rooms-grid">
            <?php while ( have_posts() ) : the_post(); ?>
            <?php
            $price = hk_get_room_meta( get_the_ID(), 'price' );
            $adults= hk_get_room_meta( get_the_ID(), 'adults', 2 );
            $size  = hk_get_room_meta( get_the_ID(), 'size' );
            $bed   = hk_get_room_meta( get_the_ID(), 'bed' );
            ?>
            <article <?php post_class( 'hk-room-card hk-animate' ); ?>>
                <div class="hk-room-card-img">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'hk-room-card' ); ?></a>
                    <?php endif; ?>
                    <?php if ( $price ) : ?>
                    <span class="hk-room-price-badge"><?php echo esc_html( hk_price( $price ) ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="hk-room-card-body">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php the_excerpt(); ?></p>
                    <div class="hk-room-specs">
                        <?php if ( $adults ) : ?><span class="hk-spec"><?php echo hk_icon( 'guests' ); ?> <?php echo esc_html( $adults ); ?></span><?php endif; ?>
                        <?php if ( $size )   : ?><span class="hk-spec"><?php echo hk_icon( 'size' ); ?> <?php echo esc_html( $size ); ?>m²</span><?php endif; ?>
                        <?php if ( $bed )    : ?><span class="hk-spec"><?php echo hk_icon( 'bed' ); ?> <?php echo esc_html( ucfirst( $bed ) ); ?></span><?php endif; ?>
                    </div>
                    <div class="hk-room-card-footer">
                        <div>
                            <div class="hk-room-from"><?php esc_html_e( 'from', 'hotel-krone' ); ?></div>
                            <span class="hk-room-price"><?php echo $price ? esc_html( hk_price( $price ) ) : '—'; ?></span>
                            <span class="hk-room-night"> /<?php esc_html_e( 'night', 'hotel-krone' ); ?></span>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm"><?php esc_html_e( 'View Room', 'hotel-krone' ); ?></a>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php else : ?>
        <div style="text-align:center;padding:80px 0;">
            <p><?php esc_html_e( 'No rooms found.', 'hotel-krone' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
