<?php get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php echo esc_html( get_the_category_list( ', ' ) ); ?></span>
            <h1><?php the_title(); ?></h1>
            <p><?php echo esc_html( get_the_date() ); ?> &mdash; <?php the_author(); ?></p>
        </div>
    </div>
</div>

<section class="hk-section">
    <div class="container" style="max-width:900px;">
        <?php hk_breadcrumbs(); ?>
        <?php while ( have_posts() ) : the_post(); ?>
        <?php if ( has_post_thumbnail() ) : ?>
        <div style="margin-bottom:40px;border-radius:12px;overflow:hidden;">
            <?php the_post_thumbnail( 'full' ); ?>
        </div>
        <?php endif; ?>
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
