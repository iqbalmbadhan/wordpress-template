<?php get_header(); ?>

<header class="page-hero">
    <div class="hero-bg-text" aria-hidden="true"><?php echo esc_html( mb_substr( get_the_title(), 0, 4 ) ); ?></div>
    <div class="page-hero-inner">
        <div>
            <div class="page-eyebrow">
                <span class="page-eyebrow-line"></span>
                <?php esc_html_e( 'Page', 'dawn-simmons' ); ?>
            </div>
            <?php the_title( '<h1 class="page-hero-title">', '</h1>' ); ?>
        </div>
    </div>
</header>

<main id="main-content" class="ds-page-main">
    <div class="ds-page-container">
        <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'ds-page-article' ); ?>>
            <div class="ds-entry-content">
                <?php the_content(); ?>
            </div>
            <?php
            wp_link_pages( [
                'before' => '<div class="ds-page-links">' . __( 'Pages:', 'dawn-simmons' ),
                'after'  => '</div>',
            ] );
            ?>
        </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
