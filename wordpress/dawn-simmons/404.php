<?php get_header(); ?>

<main id="main-content">
    <section style="min-height:60vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:120px 48px 80px">
        <div>
            <div style="font-family:var(--ff-display);font-size:120px;font-weight:900;color:oklch(25% 0.015 260);line-height:1;margin-bottom:24px">404</div>
            <h1 style="font-family:var(--ff-display);font-size:clamp(28px,4vw,48px);font-weight:900;letter-spacing:-0.025em;margin-bottom:16px">
                <?php esc_html_e( 'Page Not', 'dawn-simmons' ); ?> <em style="font-style:italic;color:var(--accent)"><?php esc_html_e( 'Found', 'dawn-simmons' ); ?></em>
            </h1>
            <p style="font-size:16px;color:var(--text2);max-width:420px;margin:0 auto 40px;line-height:1.75">
                <?php esc_html_e( "The page you're looking for doesn't exist or has been moved.", 'dawn-simmons' ); ?>
            </p>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">
                    <?php esc_html_e( '← Back to Home', 'dawn-simmons' ); ?>
                </a>
                <a href="<?php echo esc_url( get_permalink( get_option( 'ds_page_blog' ) ) ); ?>" class="btn-outline">
                    <?php esc_html_e( 'Read the Blog', 'dawn-simmons' ); ?>
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
