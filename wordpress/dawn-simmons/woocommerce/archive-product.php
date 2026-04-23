<?php get_header(); ?>

<main id="main-content">
    <header class="page-hero">
        <div class="hero-bg-text" aria-hidden="true"><?php esc_html_e( 'Shop', 'dawn-simmons' ); ?></div>
        <div class="page-hero-inner" style="max-width:1200px;margin:0 auto">
            <div>
                <div style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--accent);display:flex;align-items:center;gap:10px;margin-bottom:14px">
                    <span style="display:block;width:24px;height:1px;background:var(--accent)"></span>
                    <?php esc_html_e( 'Store', 'dawn-simmons' ); ?>
                </div>
                <h1><?php woocommerce_page_title(); ?></h1>
            </div>
        </div>
    </header>

    <div style="max-width:1200px;margin:0 auto;padding:60px 48px">
        <?php woocommerce_content(); ?>
    </div>
</main>

<?php get_footer(); ?>
