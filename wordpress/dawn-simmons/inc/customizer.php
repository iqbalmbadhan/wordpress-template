<?php
defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {

    // ── Panel: Dawn Simmons Theme ─────────────────────────────────────────
    $wp_customize->add_panel( 'ds_theme_panel', [
        'title'    => __( 'Dawn Simmons Theme', 'dawn-simmons' ),
        'priority' => 30,
    ] );

    // ── Section: Colors ───────────────────────────────────────────────────
    $wp_customize->add_section( 'ds_colors', [
        'title'  => __( 'Color Palette', 'dawn-simmons' ),
        'panel'  => 'ds_theme_panel',
    ] );

    $wp_customize->add_setting( 'ds_accent_color', [
        'default'           => 'teal',
        'sanitize_callback' => function( $v ) { return in_array( $v, [ 'teal', 'violet', 'gold', 'coral' ], true ) ? $v : 'teal'; },
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'ds_accent_color', [
        'label'   => __( 'Accent Color', 'dawn-simmons' ),
        'section' => 'ds_colors',
        'type'    => 'radio',
        'choices' => [
            'teal'   => __( 'Teal (Default)', 'dawn-simmons' ),
            'violet' => __( 'Violet',         'dawn-simmons' ),
            'gold'   => __( 'Gold',           'dawn-simmons' ),
            'coral'  => __( 'Coral',          'dawn-simmons' ),
        ],
    ] );

    $wp_customize->add_setting( 'ds_bg_theme', [
        'default'           => 'dark',
        'sanitize_callback' => function( $v ) { return in_array( $v, [ 'dark', 'midnight', 'warm' ], true ) ? $v : 'dark'; },
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'ds_bg_theme', [
        'label'   => __( 'Background Theme', 'dawn-simmons' ),
        'section' => 'ds_colors',
        'type'    => 'radio',
        'choices' => [
            'dark'     => __( 'Dark (Default)', 'dawn-simmons' ),
            'midnight' => __( 'Midnight',       'dawn-simmons' ),
            'warm'     => __( 'Warm Dark',      'dawn-simmons' ),
        ],
    ] );

    // ── Section: Typography ───────────────────────────────────────────────
    $wp_customize->add_section( 'ds_typography', [
        'title' => __( 'Typography', 'dawn-simmons' ),
        'panel' => 'ds_theme_panel',
    ] );

    $wp_customize->add_setting( 'ds_font_pair', [
        'default'           => 'playfair',
        'sanitize_callback' => function( $v ) { return in_array( $v, [ 'playfair', 'geo', 'editorial' ], true ) ? $v : 'playfair'; },
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'ds_font_pair', [
        'label'   => __( 'Font Pairing', 'dawn-simmons' ),
        'section' => 'ds_typography',
        'type'    => 'radio',
        'choices' => [
            'playfair'  => __( 'Elegant — Playfair Display + DM Sans', 'dawn-simmons' ),
            'geo'       => __( 'Geometric — DM Sans only',             'dawn-simmons' ),
            'editorial' => __( 'Editorial — Playfair + DM Sans',       'dawn-simmons' ),
        ],
    ] );

    // ── Section: Header / Footer ──────────────────────────────────────────
    $wp_customize->add_section( 'ds_header', [
        'title' => __( 'Header & Footer', 'dawn-simmons' ),
        'panel' => 'ds_theme_panel',
    ] );

    $wp_customize->add_setting( 'ds_logo_text', [
        'default'           => 'Dawn.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'ds_logo_text', [
        'label'   => __( 'Logo Text', 'dawn-simmons' ),
        'section' => 'ds_header',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'ds_cta_text', [
        'default'           => "Let's Talk",
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'ds_cta_text', [
        'label'   => __( 'Nav CTA Button Text', 'dawn-simmons' ),
        'section' => 'ds_header',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'ds_cta_url', [
        'default'           => '#contact',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'ds_cta_url', [
        'label'       => __( 'Nav CTA Button URL', 'dawn-simmons' ),
        'description' => __( 'e.g. #contact, /contact, or a full URL', 'dawn-simmons' ),
        'section'     => 'ds_header',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'ds_footer_copy', [
        'default'           => '© 2026 Dawn Christine Simmons — ServiceNow Consultant & AI Transformation Expert',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'ds_footer_copy', [
        'label'   => __( 'Footer Copyright Text', 'dawn-simmons' ),
        'section' => 'ds_header',
        'type'    => 'textarea',
    ] );

} );

// ── Customizer live preview (postMessage JS) ──────────────────────────────
add_action( 'customize_preview_init', function () {
    wp_enqueue_script( 'ds-customizer-preview', DS_ASSETS . '/js/customizer-preview.js', [ 'customize-preview', 'jquery' ], DS_VERSION, true );
} );
