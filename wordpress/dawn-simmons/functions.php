<?php
/**
 * Dawn Simmons Theme — functions.php
 */

defined( 'ABSPATH' ) || exit;

// ── Constants ────────────────────────────────────────────────────────────────
define( 'DS_VERSION',   '1.0.0' );
define( 'DS_DIR',       get_template_directory() );
define( 'DS_URI',       get_template_directory_uri() );
define( 'DS_ASSETS',    DS_URI  . '/assets' );
define( 'DS_INC',       DS_DIR  . '/inc' );

// ── Load core includes ───────────────────────────────────────────────────────
require_once DS_INC . '/class-plugin-checker.php';
require_once DS_INC . '/class-setup-wizard.php';
require_once DS_INC . '/class-demo-importer.php';
require_once DS_INC . '/enqueue.php';
require_once DS_INC . '/customizer.php';
require_once DS_INC . '/template-functions.php';
require_once DS_INC . '/blocks/register-blocks.php';

// ── Initialize classes ───────────────────────────────────────────────────────
DS_Plugin_Checker::init();
DS_Setup_Wizard::init();

// Elementor integration — priority 5 ensures our widget hooks are added
// before Elementor fires elementor/widgets/register during its own init.
add_action( 'plugins_loaded', function () {
    if ( defined( 'ELEMENTOR_VERSION' ) ) {
        require_once DS_INC . '/elementor/class-elementor-manager.php';
        DS_Elementor_Manager::init();
    }
}, 5 );

// ── Theme setup ──────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'dawn-simmons', DS_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [ 'height' => 60, 'width' => 200, 'flex-width' => true ] );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor.css' );

    // WooCommerce
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width'     => 600,
        'single_image_width'        => 900,
        'product_grid'              => [ 'default_rows' => 3, 'min_rows' => 1, 'default_columns' => 3, 'min_columns' => 1, 'max_columns' => 6 ],
    ] );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Navigation menus
    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'dawn-simmons' ),
        'footer'  => __( 'Footer Navigation',  'dawn-simmons' ),
    ] );
} );

// ── Widgets / Sidebars ───────────────────────────────────────────────────────
add_action( 'widgets_init', function () {
    $defaults = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'Blog Sidebar', 'dawn-simmons' ),
        'id'   => 'sidebar-blog',
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'Footer Column 1', 'dawn-simmons' ),
        'id'   => 'footer-1',
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'Footer Column 2', 'dawn-simmons' ),
        'id'   => 'footer-2',
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'Footer Column 3', 'dawn-simmons' ),
        'id'   => 'footer-3',
    ] ) );
} );

// ── Block categories ─────────────────────────────────────────────────────────
add_filter( 'block_categories_all', function ( $cats ) {
    return array_merge(
        [ [ 'slug' => 'dawn-simmons', 'title' => 'Dawn Simmons', 'icon' => 'portfolio' ] ],
        $cats
    );
} );

// ── Image sizes ──────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', function () {
    add_image_size( 'ds-hero',     1600, 900, true );
    add_image_size( 'ds-card',     800,  500, true );
    add_image_size( 'ds-avatar',   200,  200, true );
    add_image_size( 'ds-featured', 1200, 630, true );
} );

// ── Custom post types ─────────────────────────────────────────────────────────
add_action( 'init', function () {
    register_post_type( 'ds_service', [
        'label'               => __( 'Services', 'dawn-simmons' ),
        'labels'              => [
            'name'          => __( 'Services',       'dawn-simmons' ),
            'singular_name' => __( 'Service',        'dawn-simmons' ),
            'add_new_item'  => __( 'Add New Service','dawn-simmons' ),
        ],
        'public'              => true,
        'show_in_rest'        => true,
        'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'           => 'dashicons-portfolio',
        'has_archive'         => false,
        'rewrite'             => [ 'slug' => 'services' ],
    ] );

    register_post_type( 'ds_testimonial', [
        'label'       => __( 'Testimonials', 'dawn-simmons' ),
        'public'      => false,
        'show_in_rest'=> true,
        'supports'    => [ 'title', 'editor', 'thumbnail' ],
        'menu_icon'   => 'dashicons-format-quote',
    ] );
} );

// ── REST API: expose theme settings ──────────────────────────────────────────
add_action( 'rest_api_init', function () {
    register_rest_route( 'dawn-simmons/v1', '/settings', [
        'methods'             => 'GET',
        'callback'            => fn() => rest_ensure_response( [
            'accent'          => get_theme_mod( 'ds_accent_color', 'teal' ),
            'bg'              => get_theme_mod( 'ds_bg_theme',     'dark' ),
            'font'            => get_theme_mod( 'ds_font_pair',    'playfair' ),
            'editor_pref'     => get_option( 'ds_editor_preference', 'gutenberg' ),
        ] ),
        'permission_callback' => '__return_true',
    ] );
} );

// ── Activation hook: trigger setup wizard ───────────────────────────────────
add_action( 'after_switch_theme', function () {
    if ( ! get_option( 'ds_setup_complete' ) ) {
        set_transient( 'ds_redirect_wizard', true, 60 );
    }
} );

add_action( 'admin_init', function () {
    if ( get_transient( 'ds_redirect_wizard' ) ) {
        delete_transient( 'ds_redirect_wizard' );
        wp_safe_redirect( admin_url( 'admin.php?page=ds-setup-wizard' ) );
        exit;
    }
} );

// ── Helper: get current editor preference ───────────────────────────────────
function ds_editor_preference(): string {
    return get_option( 'ds_editor_preference', 'gutenberg' );
}

function ds_is_elementor(): bool {
    return ds_editor_preference() === 'elementor' && defined( 'ELEMENTOR_VERSION' );
}
