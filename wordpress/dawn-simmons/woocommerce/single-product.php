<?php get_header(); ?>

<main id="main-content">
    <?php while ( have_posts() ) : the_post(); ?>
    <div class="woocommerce" style="padding-top:80px">
        <div style="max-width:1200px;margin:0 auto;padding:20px 48px 0">
            <?php woocommerce_breadcrumb(); ?>
        </div>
        <?php woocommerce_content(); ?>
    </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
