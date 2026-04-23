<?php get_header(); ?>

<main id="main-content">
<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        // If Elementor is active and this page uses Elementor builder,
        // the_content() will output the Elementor-rendered page.
        // If Gutenberg, the_content() renders our custom blocks.
        the_content();
    endwhile;
endif;
?>
</main>

<?php get_footer(); ?>
