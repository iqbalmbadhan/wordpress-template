<?php
/**
 * Plugin checker — prompts admin to install/activate required plugins.
 */

defined( 'ABSPATH' ) || exit;

class DS_Plugin_Checker {

    const REQUIRED = [];

    const RECOMMENDED = [
        [
            'name'     => 'Elementor',
            'slug'     => 'elementor',
            'file'     => 'elementor/elementor.php',
            'required' => false,
            'desc'     => 'Drag-and-drop visual page builder. Choose during setup.',
        ],
        [
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'file'     => 'contact-form-7/wp-contact-form-7.php',
            'required' => false,
            'desc'     => 'Flexible contact forms for the Contact section.',
        ],
        [
            'name'     => 'WooCommerce',
            'slug'     => 'woocommerce',
            'file'     => 'woocommerce/woocommerce.php',
            'required' => false,
            'desc'     => 'Optional: powers the shop, cart, checkout, and product pages. Skip if you don\'t need eCommerce.',
        ],
    ];

    public static function init(): void {
        add_action( 'admin_notices', [ __CLASS__, 'show_notices' ] );
        add_action( 'wp_ajax_ds_dismiss_plugin_notice', [ __CLASS__, 'dismiss_notice' ] );
    }

    public static function all_plugins(): array {
        return array_merge( self::REQUIRED, self::RECOMMENDED );
    }

    public static function is_active( string $file ): bool {
        return is_plugin_active( $file );
    }

    public static function is_installed( string $slug ): bool {
        return file_exists( WP_PLUGIN_DIR . '/' . $slug );
    }

    public static function get_install_url( string $slug ): string {
        return wp_nonce_url(
            admin_url( 'update.php?action=install-plugin&plugin=' . $slug ),
            'install-plugin_' . $slug
        );
    }

    public static function get_activate_url( string $file ): string {
        return wp_nonce_url(
            admin_url( 'plugins.php?action=activate&plugin=' . $file ),
            'activate-plugin_' . $file
        );
    }

    public static function show_notices(): void {
        if ( get_option( 'ds_plugin_notices_dismissed' ) ) {
            return;
        }
        if ( get_option( 'ds_setup_complete' ) ) {
            return;
        }
        echo '<div class="notice notice-info is-dismissible" id="ds-plugin-notice">';
        printf(
            '<p><strong>Dawn Simmons Theme</strong> — <a href="%s">Open Theme Control</a> to finish setting up your site.</p>',
            esc_url( admin_url( 'index.php?page=ds-setup-wizard' ) )
        );
        echo '</div>';
    }

    public static function dismiss_notice(): void {
        check_ajax_referer( 'ds_nonce', 'nonce' );
        update_option( 'ds_plugin_notices_dismissed', true );
        wp_send_json_success();
    }
}
