<?php get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php esc_html_e( 'Hotel News', 'hotel-krone' ); ?></span>
            <h1><?php esc_html_e( 'Latest Updates', 'hotel-krone' ); ?></h1>
        </div>
    </div>
</div>

<section class="hk-section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
        <div class="hk-blog-grid">
            <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( 'hk-post-card hk-animate' ); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="hk-post-img">
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'hk-thumb' ); ?></a>
                </div>
                <?php endif; ?>
                <div class="hk-post-body">
                    <p class="hk-post-date"><?php echo esc_html( get_the_date() ); ?></p>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php the_excerpt(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-dark btn-sm"><?php esc_html_e( 'Read More', 'hotel-krone' ); ?></a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination(); ?>
        <?php else : ?>
        <p><?php esc_html_e( 'No posts found.', 'hotel-krone' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
