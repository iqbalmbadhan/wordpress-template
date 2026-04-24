<?php get_header(); ?>

<main id="main-content" class="ds-page-wrap">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'ds-page-content' ); ?>>
            <header class="ds-page-header">
                <?php the_title( '<h1 class="ds-page-title">', '</h1>' ); ?>
            </header>
            <div class="ds-entry-content">
                <?php the_content(); ?>
            </div>
        </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
