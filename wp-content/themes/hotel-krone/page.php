<?php get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<section class="hk-section">
    <div class="container">
        <?php hk_breadcrumbs(); ?>
        <?php while ( have_posts() ) : the_post(); ?>
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
