<?php
defined( 'ABSPATH' ) || exit;

/**
 * Registers the Dawn Simmons Elementor widget category and loads all widgets.
 */
class DS_Elementor_Manager {

    public static function init(): void {
        // 'elementor/widgets/register' is the current hook (Elementor 3.5+).
        // 'elementor/widgets_registered' is the legacy hook (older versions).
        // We hook both so the theme works across Elementor version ranges.
        add_action( 'elementor/widgets/register',   [ __CLASS__, 'register_widgets' ] );
        add_action( 'elementor/widgets_registered', [ __CLASS__, 'register_widgets' ] );
        add_action( 'elementor/elements/categories_registered', [ __CLASS__, 'register_category' ] );
    }

    // Guard against double-registration if both hooks fire.
    private static bool $widgets_registered = false;

    public static function register_category( $manager ): void {
        $manager->add_category( 'dawn-simmons', [
            'title' => __( 'Dawn Simmons', 'dawn-simmons' ),
            'icon'  => 'fa fa-star',
        ] );
    }

    public static function register_widgets( $manager = null ): void {
        if ( self::$widgets_registered ) {
            return;
        }
        self::$widgets_registered = true;

        // When called via the legacy hook the manager isn't passed — fetch it.
        if ( ! $manager instanceof \Elementor\Widgets_Manager ) {
            if ( ! class_exists( '\Elementor\Plugin' ) ) {
                return;
            }
            $manager = \Elementor\Plugin::$instance->widgets_manager ?? null;
            if ( ! $manager ) {
                return;
            }
        }
        $widget_dir = DS_INC . '/elementor/widgets/';
        $widgets = [
            'class-widget-hero',
            'class-widget-ai-section',
            'class-widget-services',
            'class-widget-about',
            'class-widget-testimonials',
            'class-widget-contact',
        ];

        foreach ( $widgets as $file ) {
            $path = $widget_dir . $file . '.php';
            if ( file_exists( $path ) ) {
                require_once $path;
            }
        }

        $classes = [
            'DS_Widget_Hero',
            'DS_Widget_AI_Section',
            'DS_Widget_Services',
            'DS_Widget_About',
            'DS_Widget_Testimonials',
            'DS_Widget_Contact',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $manager->register( new $class() );
            }
        }
    }
}
