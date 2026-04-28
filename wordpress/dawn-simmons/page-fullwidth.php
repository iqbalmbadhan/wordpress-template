<?php
/**
 * Template Name: Full Width Page
 *
 * Use this template for pages like Services that should render
 * edge-to-edge with no page-hero header or container constraints.
 */
get_header(); ?>

<main id="main-content">
    <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'ds-fullwidth-article' ); ?>>
        <?php the_content(); ?>
    </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
