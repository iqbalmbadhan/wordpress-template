<?php
defined( 'ABSPATH' ) || exit;

/* ── Resource hints: preconnect for Google Fonts (before any <link>) ──────── */
add_action( 'wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="https://fonts.googleapis.com">' . "\n";
}, 1 );

/* ── Main enqueue ──────────────────────────────────────────────────────────── */
add_action( 'wp_enqueue_scripts', function () {
    /*
     * Use file-modification timestamps as cache-busting versions so that any
     * saved change is immediately visible without requiring a manual hard reload
     * or cache-clear — avoids the "nothing changed after save" problem entirely.
     */
    $css_ver = filemtime( DS_DIR . '/assets/css/main.css' ) ?: DS_VERSION;
    $js_ver  = filemtime( DS_DIR . '/assets/js/frontend.js' ) ?: DS_VERSION;

    // Google Fonts
    wp_enqueue_style( 'ds-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Playfair+Display:ital,wght@0,700;0,900;1,400&display=swap',
        [], null
    );

    // Main stylesheet (cache-busted by mtime)
    wp_enqueue_style( 'ds-main', DS_ASSETS . '/css/main.css', [ 'ds-fonts' ], $css_ver );

    // WooCommerce styles override
    if ( class_exists( 'WooCommerce' ) ) {
        $woo_ver = filemtime( DS_DIR . '/assets/css/woocommerce.css' ) ?: DS_VERSION;
        wp_enqueue_style( 'ds-woocommerce', DS_ASSETS . '/css/woocommerce.css', [ 'ds-main' ], $woo_ver );
    }

    // Frontend JS — loaded in footer, cache-busted by mtime
    wp_enqueue_script( 'ds-frontend', DS_ASSETS . '/js/frontend.js', [], $js_ver, true );
    wp_localize_script( 'ds-frontend', 'dsTheme', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'ds_frontend_nonce' ),
        'restUrl' => get_rest_url( null, 'dawn-simmons/v1/' ),
        'accent'  => get_theme_mod( 'ds_accent_color', 'teal' ),
        'bg'      => get_theme_mod( 'ds_bg_theme',     'dark' ),
        'font'    => get_theme_mod( 'ds_font_pair',    'playfair' ),
    ] );

    // Add defer to frontend JS for non-blocking load
    wp_script_add_data( 'ds-frontend', 'strategy', 'defer' );

    // Comments reply script
    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }
} );

// Block editor assets
add_action( 'enqueue_block_editor_assets', function () {
    wp_enqueue_style( 'ds-editor', DS_ASSETS . '/css/editor.css', [], DS_VERSION );

    if ( file_exists( DS_DIR . '/assets/js/blocks/index.js' ) ) {
        wp_enqueue_script(
            'ds-blocks',
            DS_ASSETS . '/js/blocks/index.js',
            [ 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor', 'wp-data', 'wp-dom-ready', 'wp-server-side-render' ],
            DS_VERSION,
            true
        );
        wp_set_script_translations( 'ds-blocks', 'dawn-simmons' );
        wp_localize_script( 'ds-blocks', 'dsBlocks', [
            'themeUrl' => DS_URI,
            'restUrl'  => get_rest_url( null, 'dawn-simmons/v1/' ),
            'nonce'    => wp_create_nonce( 'wp_rest' ),
        ] );
    }
} );

// Admin: enqueue editor CSS on block editor screens
add_action( 'admin_enqueue_scripts', function ( string $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        return;
    }
    wp_enqueue_style( 'ds-editor-admin', DS_ASSETS . '/css/editor.css', [], DS_VERSION );
} );

