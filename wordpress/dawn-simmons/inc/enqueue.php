<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {
    // Google Fonts
    wp_enqueue_style( 'ds-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Playfair+Display:ital,wght@0,700;0,900;1,400&display=swap',
        [], null
    );

    // Main stylesheet
    wp_enqueue_style( 'ds-main', DS_ASSETS . '/css/main.css', [ 'ds-fonts' ], DS_VERSION );

    // WooCommerce styles override
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'ds-woocommerce', DS_ASSETS . '/css/woocommerce.css', [ 'ds-main' ], DS_VERSION );
    }

    // Frontend JS (handles nav, counters, skill bars, fade-in)
    wp_enqueue_script( 'ds-frontend', DS_ASSETS . '/js/frontend.js', [], DS_VERSION, true );
    wp_localize_script( 'ds-frontend', 'dsTheme', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'ds_frontend_nonce' ),
        'restUrl' => get_rest_url( null, 'dawn-simmons/v1/' ),
        'accent'  => get_theme_mod( 'ds_accent_color', 'teal' ),
        'bg'      => get_theme_mod( 'ds_bg_theme',     'dark' ),
        'font'    => get_theme_mod( 'ds_font_pair',    'playfair' ),
    ] );

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
