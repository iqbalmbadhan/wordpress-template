<?php
/**
 * Demo Content Importer
 * Creates pages, menus, posts, and Elementor/Gutenberg content on first run.
 */

defined( 'ABSPATH' ) || exit;

class DS_Demo_Importer {

    private static array $log = [];

    public static function run(): array {
        self::$log = [];
        self::create_pages();
        self::set_reading_settings();
        self::create_menus();
        self::create_sample_posts();
        self::create_woocommerce_pages();
        self::set_footer_widgets();
        self::create_homepage_content();
        return [ 'log' => self::$log ];
    }

    // ── Create core pages ────────────────────────────────────────────────────
    private static function create_pages(): void {
        $pages = [
            'home'    => [ 'title' => 'Home',         'template' => 'templates/page-home.php'    ],
            'blog'    => [ 'title' => 'Blog',         'template' => ''                           ],
            'about'   => [ 'title' => 'About',        'template' => ''                           ],
            'services'=> [ 'title' => 'Services',     'template' => ''                           ],
            'contact' => [ 'title' => 'Contact',      'template' => ''                           ],
        ];

        foreach ( $pages as $key => $data ) {
            if ( ! get_option( "ds_page_{$key}" ) ) {
                $id = wp_insert_post( [
                    'post_title'   => $data['title'],
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_author'  => 1,
                    'page_template'=> $data['template'],
                ] );
                if ( ! is_wp_error( $id ) ) {
                    update_option( "ds_page_{$key}", $id );
                    self::$log[] = "✓ Created page: {$data['title']} (ID {$id})";
                }
            } else {
                self::$log[] = "→ Page '{$data['title']}' already exists, skipping.";
            }
        }
    }

    // ── Reading settings ─────────────────────────────────────────────────────
    private static function set_reading_settings(): void {
        $home_id = (int) get_option( 'ds_page_home' );
        $blog_id = (int) get_option( 'ds_page_blog' );

        if ( $home_id ) {
            update_option( 'show_on_front',  'page'    );
            update_option( 'page_on_front',   $home_id );
            self::$log[] = "✓ Set homepage to 'Home' page.";
        }
        if ( $blog_id ) {
            update_option( 'page_for_posts', $blog_id );
            self::$log[] = "✓ Set blog page.";
        }
    }

    // ── Navigation menus ─────────────────────────────────────────────────────
    private static function create_menus(): void {
        // Primary menu
        $primary_id = wp_create_nav_menu( 'Primary Menu' );
        if ( ! is_wp_error( $primary_id ) ) {
            $menu_items = [
                [ 'title' => 'Home',     'page' => 'home',     'order' => 1 ],
                [ 'title' => 'Services', 'page' => 'services', 'order' => 2 ],
                [ 'title' => 'About',    'page' => 'about',    'order' => 3 ],
                [ 'title' => 'Blog',     'page' => 'blog',     'order' => 4 ],
                [ 'title' => 'Contact',  'page' => 'contact',  'order' => 5 ],
            ];
            foreach ( $menu_items as $item ) {
                $page_id = (int) get_option( "ds_page_{$item['page']}" );
                if ( $page_id ) {
                    wp_update_nav_menu_item( $primary_id, 0, [
                        'menu-item-title'     => $item['title'],
                        'menu-item-object'    => 'page',
                        'menu-item-object-id' => $page_id,
                        'menu-item-type'      => 'post_type',
                        'menu-item-status'    => 'publish',
                        'menu-item-position'  => $item['order'],
                    ] );
                }
            }
            // Add Shop if WooCommerce active
            $shop_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : -1;
            if ( $shop_id && $shop_id !== -1 ) {
                wp_update_nav_menu_item( $primary_id, 0, [
                    'menu-item-title'     => 'Shop',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $shop_id,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => 6,
                ] );
            }

            $locations = get_theme_mod( 'nav_menu_locations', [] );
            $locations['primary'] = $primary_id;
            set_theme_mod( 'nav_menu_locations', $locations );
            self::$log[] = "✓ Created Primary navigation menu.";
        }

        // Footer menu
        $footer_id = wp_create_nav_menu( 'Footer Menu' );
        if ( ! is_wp_error( $footer_id ) ) {
            foreach ( [ 'blog' => 'Blog', 'about' => 'About', 'contact' => 'Contact' ] as $key => $label ) {
                $pid = (int) get_option( "ds_page_{$key}" );
                if ( $pid ) {
                    wp_update_nav_menu_item( $footer_id, 0, [
                        'menu-item-title'     => $label,
                        'menu-item-object'    => 'page',
                        'menu-item-object-id' => $pid,
                        'menu-item-type'      => 'post_type',
                        'menu-item-status'    => 'publish',
                    ] );
                }
            }
            $locations          = get_theme_mod( 'nav_menu_locations', [] );
            $locations['footer'] = $footer_id;
            set_theme_mod( 'nav_menu_locations', $locations );
            self::$log[] = "✓ Created Footer navigation menu.";
        }
    }

    // ── Sample blog posts ────────────────────────────────────────────────────
    private static function create_sample_posts(): void {
        $posts = [
            [
                'title'   => 'Claude UI/UX Market Signals',
                'content' => '<p>Claude Design triggered the biggest AI and UI/UX market conversation of 2026. Explore how Claude is transforming the design cycle, why ServiceNow and Cognizant stand to benefit, and why the future belongs to human-AI collaboration.</p>',
                'excerpt' => 'How Claude is transforming the AI and UI/UX design market in 2026.',
                'cats'    => [ 'ServiceNow', 'AI Design' ],
            ],
            [
                'title'   => 'AI Upgrades Redefined Now',
                'content' => '<p>ServiceNow Australia brings AI directly into workflows — learn how release test automation and AI validation accelerate upgrades.</p>',
                'excerpt' => 'ServiceNow AI enhancements that are reshaping ITSM workflows.',
                'cats'    => [ 'ServiceNow', 'AI Automation' ],
            ],
            [
                'title'   => 'ServiceNow AI Best Practices',
                'content' => '<p>Stop AI tool sprawl. Use ServiceNow SPM + EAP as your system of record, assign AI ownership across Now Assist, Moveworks, and Claude.</p>',
                'excerpt' => 'A practical guide to managing AI tools inside ServiceNow.',
                'cats'    => [ 'ServiceNow', 'AI Automation', 'ITSM' ],
            ],
        ];

        foreach ( $posts as $p ) {
            // Avoid duplicates
            $existing = get_page_by_title( $p['title'], OBJECT, 'post' );
            if ( $existing ) {
                self::$log[] = "→ Post '{$p['title']}' already exists, skipping.";
                continue;
            }
            // Create categories
            $cat_ids = [];
            foreach ( $p['cats'] as $cat_name ) {
                $cat = get_term_by( 'name', $cat_name, 'category' );
                if ( ! $cat ) {
                    $term    = wp_insert_term( $cat_name, 'category' );
                    $cat_ids[] = is_wp_error( $term ) ? 1 : $term['term_id'];
                } else {
                    $cat_ids[] = $cat->term_id;
                }
            }
            $id = wp_insert_post( [
                'post_title'     => $p['title'],
                'post_content'   => $p['content'],
                'post_excerpt'   => $p['excerpt'],
                'post_status'    => 'publish',
                'post_type'      => 'post',
                'post_author'    => 1,
                'post_category'  => $cat_ids,
            ] );
            if ( ! is_wp_error( $id ) ) {
                self::$log[] = "✓ Created post: {$p['title']} (ID {$id})";
            }
        }
    }

    // ── WooCommerce pages ────────────────────────────────────────────────────
    private static function create_woocommerce_pages(): void {
        if ( ! class_exists( 'WooCommerce' ) ) {
            self::$log[] = '→ WooCommerce not active, skipping shop pages.';
            return;
        }
        if ( function_exists( 'wc_create_pages' ) ) {
            wc_create_pages();
            self::$log[] = '✓ WooCommerce pages created/verified.';
        }
    }

    // ── Footer widgets ───────────────────────────────────────────────────────
    private static function set_footer_widgets(): void {
        $sidebars = get_option( 'sidebars_widgets', [] );

        // Add a text widget to footer-1
        $widget_id = 'text-ds-footer-1';
        $sidebars['footer-1'] = [ $widget_id ];
        $widgets = get_option( 'widget_text', [] );
        $widgets[substr( $widget_id, 5 )] = [
            'title' => 'Dawn C. Simmons',
            'text'  => '<p>Senior ServiceNow Consultant &amp; AI Transformation Expert. Chicago, IL.</p>',
            'filter'=> true,
        ];
        update_option( 'widget_text', $widgets );

        // Recent posts in footer-2
        $sidebars['footer-2'] = [ 'recent-posts-ds-footer' ];

        update_option( 'sidebars_widgets', $sidebars );
        self::$log[] = '✓ Footer widgets configured.';
    }

    // ── Homepage content (Elementor or Gutenberg) ────────────────────────────
    private static function create_homepage_content(): void {
        $home_id = (int) get_option( 'ds_page_home' );
        if ( ! $home_id ) {
            self::$log[] = '✗ Home page ID not found, skipping content creation.';
            return;
        }

        $pref = get_option( 'ds_editor_preference', 'gutenberg' );

        if ( $pref === 'elementor' && defined( 'ELEMENTOR_VERSION' ) ) {
            self::create_elementor_homepage( $home_id );
        } else {
            self::create_gutenberg_homepage( $home_id );
        }
    }

    // ── Gutenberg homepage ───────────────────────────────────────────────────
    private static function create_gutenberg_homepage( int $page_id ): void {
        $content = <<<BLOCKS
<!-- wp:dawn-simmons/hero {"eyebrow":"ServiceNow Expert · AI Transformation · Chicago, IL","headline":"Transforming Business With AI & ServiceNow","roles":["ServiceNow Consultant & AI Architect","Digital Transformation & Business Agent","Enterprise AI & ITSM Solution Architect"],"ctaPrimary":"Start a Conversation","ctaSecondary":"View Resume","stats":[{"value":20,"label":"Years Experience","suffix":"+"},{"value":94,"label":"Client Satisfaction","suffix":"%"},{"value":50,"label":"Enterprise Clients","suffix":"+"}]} /-->
<!-- wp:dawn-simmons/ai-section {"eyebrow":"AI + ServiceNow + Business","headline":"Unlocking AI-Powered Business Intelligence"} /-->
<!-- wp:dawn-simmons/services {"eyebrow":"What I Do","headline":"Expert-level services built on 20+ years"} /-->
<!-- wp:dawn-simmons/about {"eyebrow":"About Me","headline":"Dynamic Leadership with Global Impact"} /-->
<!-- wp:dawn-simmons/testimonials {"eyebrow":"Social Proof","headline":"What colleagues & clients say"} /-->
<!-- wp:dawn-simmons/contact {"eyebrow":"Get in Touch","headline":"Let's work together"} /-->
BLOCKS;

        wp_update_post( [
            'ID'           => $page_id,
            'post_content' => $content,
        ] );
        self::$log[] = '✓ Gutenberg blocks written to homepage.';
    }

    // ── Elementor homepage ───────────────────────────────────────────────────
    private static function create_elementor_homepage( int $page_id ): void {
        $elementor_data = wp_json_encode( self::build_elementor_data() );

        update_post_meta( $page_id, '_elementor_data',          $elementor_data );
        update_post_meta( $page_id, '_elementor_edit_mode',     'builder'       );
        update_post_meta( $page_id, '_elementor_template_type', 'wp-page'       );
        update_post_meta( $page_id, '_elementor_version',       ELEMENTOR_VERSION );

        // Update post content to Elementor's placeholder
        wp_update_post( [
            'ID'           => $page_id,
            'post_content' => '',
        ] );

        // Flush Elementor CSS cache
        if ( class_exists( '\Elementor\Plugin' ) ) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }

        self::$log[] = '✓ Elementor page data written to homepage.';
    }

    private static function build_elementor_data(): array {
        // widgetType must match each widget class's get_name() return value.
        return [
            self::el_section( 'hero-section',    'ds-hero',         self::hero_settings()         ),
            self::el_section( 'ai-section',      'ds-ai-section',   self::ai_settings()           ),
            self::el_section( 'services-section','ds-services',     self::services_settings()     ),
            self::el_section( 'about-section',   'ds-about',        self::about_settings()        ),
            self::el_section( 'testi-section',   'ds-testimonials', self::testimonials_settings() ),
            self::el_section( 'contact-section', 'ds-contact',      self::contact_settings()      ),
        ];
    }

    private static function el_section( string $id, string $widget_type, array $settings ): array {
        return [
            'id'       => $id,
            'elType'   => 'section',
            'settings' => [ 'layout' => 'full_width', '_element_width' => '' ],
            'elements' => [[
                'id'       => $id . '-col',
                'elType'   => 'column',
                'settings' => [ '_column_size' => 100, '_inline_size' => null ],
                'elements' => [[
                    'id'         => $id . '-widget',
                    'elType'     => 'widget',
                    'widgetType' => $widget_type,
                    'settings'   => $settings,
                    'elements'   => [],
                ]],
            ]],
        ];
    }

    private static function hero_settings(): array {
        return [
            'eyebrow'        => 'ServiceNow Expert · AI Transformation · Chicago, IL',
            'headline'       => 'Transforming Business With AI & ServiceNow',
            'role_1'         => 'ServiceNow Consultant & AI Architect',
            'role_2'         => 'Digital Transformation & Business Agent',
            'role_3'         => 'Enterprise AI & ITSM Solution Architect',
            'cta_primary'    => 'Start a Conversation',
            'cta_secondary'  => 'View Resume',
            'stat_1_value'   => '20', 'stat_1_suffix' => '+', 'stat_1_label' => 'Years Experience',
            'stat_2_value'   => '94', 'stat_2_suffix' => '%', 'stat_2_label' => 'Client Satisfaction',
            'stat_3_value'   => '50', 'stat_3_suffix' => '+', 'stat_3_label' => 'Enterprise Clients',
        ];
    }

    private static function ai_settings(): array {
        return [
            'eyebrow'  => 'AI + ServiceNow + Business',
            'headline' => 'Unlocking AI-Powered Business Intelligence',
            'lead_1'   => 'Dawn bridges the gap between cutting-edge artificial intelligence and real-world enterprise operations — embedding AI capabilities directly into ServiceNow workflows.',
            'lead_2'   => 'From predictive incident management to AI-driven CMDB health scoring and intelligent automation across ITSM, HRSD and SecOps.',
        ];
    }

    private static function services_settings(): array {
        return [
            'eyebrow'       => 'What I Do',
            'headline'      => 'Expert-level services built on 20+ years',
            'subtitle'      => 'From global AI-powered program management to hands-on ServiceNow implementation.',
            'service_1_num' => '01', 'service_1_title' => 'Global Program Director',
            'service_1_desc'=> 'Directed multi-million-dollar cloud implementations, aligning ServiceNow Cloud, ITAM, SCCM, GRC, and AI automation.',
            'service_2_num' => '02', 'service_2_title' => 'Enterprise IT & AI Consulting',
            'service_2_desc'=> 'ServiceNow and AI transformations in healthcare, pharma, higher education, and energy.',
            'service_3_num' => '03', 'service_3_title' => 'Strategic Leadership & Advisory',
            'service_3_desc'=> 'Fortune 500 executive guidance — aligning AI and technology strategy with business goals.',
        ];
    }

    private static function about_settings(): array {
        return [
            'eyebrow'   => 'About Me',
            'headline'  => 'Dynamic Leadership with Global Impact',
            'bio_1'     => 'Dawn C. Simmons is a transformative, visionary leader with over 20 years of executive experience in digital transformation, AI-powered business solutions, and ServiceNow implementations.',
            'bio_2'     => 'A recognized expert in enterprise AI integration and ServiceNow architecture, Dawn has delivered measurable results across Fortune 500 companies.',
            'name'      => 'Dawn Christine Simmons',
            'location'  => 'Chicago, IL USA',
            'email'     => 'dawnckhan@gmail.com',
            'phone'     => '+1-925-297-7901',
        ];
    }

    private static function testimonials_settings(): array {
        return [
            'eyebrow'  => 'Social Proof',
            'headline' => 'What colleagues & clients say',
            'items'    => json_encode([
                [ 'init' => 'SW', 'name' => 'Steve West',             'role' => 'Board of Directors, Denver Metro HDI',           'text' => 'Dawn has demonstrated exemplary leadership in the Support Services industry through her incredible efforts.' ],
                [ 'init' => 'LS', 'name' => 'Lori Shaw',              'role' => 'Senior Consultant',                               'text' => 'Very few people equal Dawn in persistence and dedication. I am continually impressed with her insight and intelligence.' ],
                [ 'init' => 'VT', 'name' => 'Venkatesh Thiruvaipati', 'role' => 'Sun Microsystems',                                'text' => '"Solution provider" — that can summarize how good she knows the business. She is GREAT to work with.' ],
                [ 'init' => 'DA', 'name' => 'Dale Avery',             'role' => 'Enterprise Network Services, Sun Microsystems',   'text' => 'Dawn is able to quickly assess the needs of a project and break it into manageable, achievable parts.' ],
                [ 'init' => 'FT', 'name' => 'Frank Tawil',            'role' => 'Sun Microsystems Bay Area',                       'text' => 'Dawn is one of the most passionate and driven people I know. A very well rounded qualified candidate.' ],
                [ 'init' => 'DB', 'name' => 'Deepanker Baderia',      'role' => 'Solution Architect, Sun Microsystems',            'text' => 'Dawn is an excellent project and program manager, bringing diverse skills including effective teamwork and strong leadership.' ],
            ]),
        ];
    }

    private static function contact_settings(): array {
        return [
            'eyebrow'  => 'Get in Touch',
            'headline' => "Let's work together",
            'body'     => "Ready to transform your enterprise? I'd love to hear about your challenges and explore how we can work together.",
            'email'    => 'dawnckhan@gmail.com',
            'phone'    => '+1-925-297-7901',
            'location' => 'Chicago, IL USA',
        ];
    }
}
