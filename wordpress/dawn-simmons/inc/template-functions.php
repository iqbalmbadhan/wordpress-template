<?php
defined( 'ABSPATH' ) || exit;

/**
 * Output the navbar.
 */
function ds_navbar(): void {
    $logo_text = get_theme_mod( 'ds_logo_text', 'Dawn.' );
    $cta_text  = get_theme_mod( 'ds_cta_text', "Let's Talk" );
    $home_url  = home_url( '/' );
    ?>
    <nav id="navbar" role="navigation" aria-label="<?php esc_attr_e( 'Main navigation', 'dawn-simmons' ); ?>">
        <a href="<?php echo esc_url( $home_url ); ?>" class="nav-logo"><?php echo esc_html( $logo_text ); ?></a>
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'nav-links',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
            'fallback_cb'    => 'ds_fallback_menu',
        ] );
        ?>
        <a href="<?php echo esc_url( get_permalink( get_option('ds_page_contact') ) ?: $home_url . '#contact' ); ?>" class="nav-cta">
            <?php echo esc_html( $cta_text ); ?>
        </a>
        <button class="nav-hamburger" id="navHamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'dawn-simmons' ); ?>" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </nav>
    <div class="nav-mobile-drawer" id="mobileDrawer" aria-hidden="true">
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => '',
            'items_wrap'     => '<ul>%3$s</ul>',
            'fallback_cb'    => false,
        ] );
        ?>
    </div>
    <?php
}

/**
 * Fallback menu when no menu is assigned.
 */
function ds_fallback_menu(): void {
    echo '<ul class="nav-links">';
    wp_list_pages( [ 'title_li' => '', 'echo' => true ] );
    echo '</ul>';
}

/**
 * Output the footer.
 */
function ds_footer(): void {
    $copy = get_theme_mod( 'ds_footer_copy', '© ' . date('Y') . ' Dawn Christine Simmons — ServiceNow Consultant &amp; AI Transformation Expert' );
    ?>
    <footer role="contentinfo">
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="footer-logo">
            <?php echo esc_html( get_theme_mod( 'ds_logo_text', 'Dawn.' ) ); ?>C.Simmons
        </a>
        <div class="footer-copy"><?php echo wp_kses_post( $copy ); ?></div>
        <nav class="footer-links" aria-label="<?php esc_attr_e( 'Footer navigation', 'dawn-simmons' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'fallback_cb'    => false,
            ] );
            ?>
        </nav>
    </footer>
    <?php
}

/**
 * Apply CSS custom properties for current theme settings.
 */
function ds_css_variables(): void {
    $accent_map = [
        'teal'   => [ 'oklch(72% 0.155 195)', 'oklch(72% 0.155 145)' ],
        'violet' => [ 'oklch(68% 0.18 290)',  'oklch(68% 0.18 260)'  ],
        'gold'   => [ 'oklch(78% 0.15 85)',   'oklch(78% 0.15 60)'   ],
        'coral'  => [ 'oklch(68% 0.18 30)',   'oklch(68% 0.18 10)'   ],
    ];
    $bg_map = [
        'dark'     => [ 'oklch(11% 0.01 260)',  'oklch(14% 0.012 260)', 'oklch(17% 0.012 260)' ],
        'midnight' => [ 'oklch(8% 0.025 265)',  'oklch(11% 0.03 265)',  'oklch(14% 0.03 265)'  ],
        'warm'     => [ 'oklch(10% 0.015 40)',  'oklch(13% 0.018 40)',  'oklch(16% 0.018 40)'  ],
    ];
    $font_map = [
        'playfair'  => [ "'Playfair Display', Georgia, serif", "'DM Sans', Helvetica, sans-serif" ],
        'geo'       => [ "'DM Sans', Helvetica, sans-serif",   "'DM Sans', Helvetica, sans-serif" ],
        'editorial' => [ "'Playfair Display', Georgia, serif", "'DM Sans', Helvetica, sans-serif" ],
    ];

    $accent = get_theme_mod( 'ds_accent_color', 'teal' );
    $bg     = get_theme_mod( 'ds_bg_theme',     'dark' );
    $font   = get_theme_mod( 'ds_font_pair',    'playfair' );

    $a = $accent_map[ $accent ] ?? $accent_map['teal'];
    $b = $bg_map[ $bg ]         ?? $bg_map['dark'];
    $f = $font_map[ $font ]     ?? $font_map['playfair'];

    echo '<style id="ds-css-vars">:root{';
    echo '--accent:'     . esc_attr( $a[0] ) . ';';
    echo '--accent2:'    . esc_attr( $a[1] ) . ';';
    echo '--bg:'         . esc_attr( $b[0] ) . ';';
    echo '--bg2:'        . esc_attr( $b[1] ) . ';';
    echo '--bg3:'        . esc_attr( $b[2] ) . ';';
    echo '--ff-display:' . esc_attr( $f[0] ) . ';';
    echo '--ff-body:'    . esc_attr( $f[1] ) . ';';
    echo '}</style>' . "\n";
}
add_action( 'wp_head', 'ds_css_variables', 5 );

/**
 * Post thumbnail with fallback.
 */
function ds_post_thumbnail( string $size = 'ds-card' ): void {
    if ( has_post_thumbnail() ) {
        the_post_thumbnail( $size, [ 'class' => 'ds-thumbnail' ] );
    } else {
        echo '<div class="ds-thumbnail-placeholder"><span>Image</span></div>';
    }
}

/**
 * Estimated reading time for the current post.
 */
function ds_reading_time(): string {
    $words = str_word_count( strip_tags( get_the_content() ) );
    $mins  = max( 1, (int) round( $words / 200 ) );
    return sprintf( _n( '%d min read', '%d min read', $mins, 'dawn-simmons' ), $mins );
}

/**
 * Pagination output.
 */
function ds_pagination(): void {
    the_posts_pagination( [
        'mid_size'           => 2,
        'prev_text'          => '← ' . __( 'Previous', 'dawn-simmons' ),
        'next_text'          => __( 'Next', 'dawn-simmons' ) . ' →',
        'before_page_number' => '<span class="screen-reader-text">' . __( 'Page', 'dawn-simmons' ) . ' </span>',
    ] );
}
