<?php
defined( 'ABSPATH' ) || exit;

/**
 * Dawn Simmons — SEO: meta tags, Open Graph, Twitter Cards, canonical, JSON-LD.
 * Hooked at wp_head priority 2 (after charset/viewport, before everything else).
 */
add_action( 'wp_head', 'ds_seo_head', 2 );

function ds_seo_head(): void {
    global $post;

    /* ── Core data ── */
    $site_name    = get_bloginfo( 'name' );
    $site_desc    = get_bloginfo( 'description' );
    $canonical    = get_the_permalink() ?: home_url( '/' );
    $og_type      = 'website';
    $title        = '';
    $description  = '';
    $image_url    = '';
    $image_width  = 1200;
    $image_height = 630;

    /* ── Per-context values ── */
    if ( is_singular() && $post ) {
        $og_type     = is_singular( 'post' ) ? 'article' : 'website';
        $canonical   = get_the_permalink( $post );
        $title       = get_the_title( $post ) . ' — ' . $site_name;
        $description = wp_strip_all_tags( get_the_excerpt( $post ) );
        if ( ! $description ) {
            $description = wp_trim_words( wp_strip_all_tags( get_the_content( null, false, $post ) ), 30, '…' );
        }

        /* Featured image → OG image */
        if ( has_post_thumbnail( $post ) ) {
            $img = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'ds-featured' );
            if ( $img ) {
                [ $image_url, $image_width, $image_height ] = $img;
            }
        }
        /* Fallback: first in-content image */
        if ( ! $image_url && function_exists( 'ds_first_content_image' ) ) {
            $image_url = ds_first_content_image( $post->ID );
        }

    } elseif ( is_home() || is_front_page() ) {
        $canonical   = home_url( '/' );
        $title       = $site_name . ( $site_desc ? ' — ' . $site_desc : '' );
        $description = $site_desc;

    } elseif ( is_category() ) {
        $term        = get_queried_object();
        $canonical   = get_category_link( $term );
        $title       = single_cat_title( '', false ) . ' — ' . $site_name;
        $description = wp_strip_all_tags( category_description() ) ?: $site_desc;

    } elseif ( is_tag() ) {
        $canonical   = get_tag_link( get_queried_object_id() );
        $title       = single_tag_title( '', false ) . ' — ' . $site_name;
        $description = wp_strip_all_tags( tag_description() ) ?: $site_desc;

    } elseif ( is_archive() ) {
        $title       = get_the_archive_title() . ' — ' . $site_name;
        $description = wp_strip_all_tags( get_the_archive_description() ) ?: $site_desc;

    } elseif ( is_search() ) {
        $title       = sprintf( __( 'Search results for "%s"', 'dawn-simmons' ), get_search_query() ) . ' — ' . $site_name;
        $description = $site_desc;

    } else {
        $title       = $site_name . ( $site_desc ? ' — ' . $site_desc : '' );
        $description = $site_desc;
    }

    /* Trim / sanitize */
    $title       = esc_attr( wp_trim_words( $title,       20, '' ) );
    $description = esc_attr( wp_trim_words( $description, 30, '…' ) );
    $canonical   = esc_url( $canonical );

    /* Site-wide fallback OG image (upload at Customizer → ds_og_image) */
    if ( ! $image_url ) {
        $fallback_id = get_theme_mod( 'ds_og_image', 0 );
        if ( $fallback_id ) {
            $img = wp_get_attachment_image_src( $fallback_id, 'ds-featured' );
            if ( $img ) {
                [ $image_url, $image_width, $image_height ] = $img;
            }
        }
    }

    /* ── Output ── */
    echo "\n<!-- Dawn Simmons SEO -->\n";

    /* Canonical */
    echo '<link rel="canonical" href="' . $canonical . '">' . "\n";

    /* Meta description (only if WordPress hasn't already inserted one via Yoast etc.) */
    if ( $description && ! defined( 'WPSEO_VERSION' ) && ! defined( 'RANK_MATH_VERSION' ) ) {
        echo '<meta name="description" content="' . $description . '">' . "\n";
    }

    /* Open Graph */
    echo '<meta property="og:type"        content="' . esc_attr( $og_type ) . '">' . "\n";
    echo '<meta property="og:title"       content="' . $title . '">' . "\n";
    echo '<meta property="og:description" content="' . $description . '">' . "\n";
    echo '<meta property="og:url"         content="' . $canonical . '">' . "\n";
    echo '<meta property="og:site_name"   content="' . esc_attr( $site_name ) . '">' . "\n";
    echo '<meta property="og:locale"      content="' . esc_attr( str_replace( '-', '_', get_locale() ) ) . '">' . "\n";
    if ( $image_url ) {
        echo '<meta property="og:image"        content="' . esc_url( $image_url ) . '">' . "\n";
        echo '<meta property="og:image:width"  content="' . (int) $image_width . '">' . "\n";
        echo '<meta property="og:image:height" content="' . (int) $image_height . '">' . "\n";
        echo '<meta property="og:image:alt"    content="' . $title . '">' . "\n";
    }

    /* Twitter Card */
    echo '<meta name="twitter:card"        content="' . ( $image_url ? 'summary_large_image' : 'summary' ) . '">' . "\n";
    echo '<meta name="twitter:title"       content="' . $title . '">' . "\n";
    echo '<meta name="twitter:description" content="' . $description . '">' . "\n";
    if ( $image_url ) {
        echo '<meta name="twitter:image" content="' . esc_url( $image_url ) . '">' . "\n";
    }

    /* ── JSON-LD structured data ── */
    $schema = ds_build_schema( $post, $og_type, $title, $description, $image_url, $site_name, $canonical );
    if ( $schema ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }

    echo "<!-- /Dawn Simmons SEO -->\n\n";
}

/**
 * Build the JSON-LD schema array for the current page.
 */
function ds_build_schema( ?WP_Post $post, string $og_type, string $title, string $description, string $image_url, string $site_name, string $canonical ): array {
    /* Always emit WebSite with SearchAction */
    $schemas = [
        [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => $site_name,
            'url'      => home_url( '/' ),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => home_url( '/?s={search_term_string}' ),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ],
    ];

    /* Person schema — consultant persona */
    $person_name  = get_theme_mod( 'ds_person_name',  get_bloginfo( 'name' ) );
    $person_job   = get_theme_mod( 'ds_person_job',   'ServiceNow Consultant & AI Transformation Expert' );
    $person_url   = get_theme_mod( 'ds_person_url',   home_url( '/' ) );
    $person_image = get_theme_mod( 'ds_person_image', '' );

    $person_schema = [
        '@context'  => 'https://schema.org',
        '@type'     => 'Person',
        'name'      => $person_name,
        'jobTitle'  => $person_job,
        'url'       => $person_url,
    ];
    if ( $person_image ) {
        $person_schema['image'] = $person_image;
    }

    /* BlogPosting for single posts */
    if ( $og_type === 'article' && $post ) {
        $date_pub  = get_the_date( 'c', $post );
        $date_mod  = get_the_modified_date( 'c', $post );
        $cats      = get_the_category( $post->ID );
        $cat_names = $cats ? array_map( fn( $c ) => $c->name, $cats ) : [];

        $article = [
            '@context'         => 'https://schema.org',
            '@type'            => 'BlogPosting',
            'headline'         => get_the_title( $post ),
            'description'      => wp_strip_all_tags( $description ),
            'url'              => $canonical,
            'datePublished'    => $date_pub,
            'dateModified'     => $date_mod,
            'author'           => $person_schema,
            'publisher'        => [
                '@type' => 'Person',
                'name'  => $person_name,
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id'   => $canonical,
            ],
        ];
        if ( $image_url ) {
            $article['image'] = $image_url;
        }
        if ( $cat_names ) {
            $article['keywords'] = implode( ', ', $cat_names );
        }
        $schemas[] = $article;
    }

    /* BreadcrumbList for inner pages / single posts */
    if ( ! is_front_page() && ! is_home() ) {
        $breadcrumbs = ds_build_breadcrumb_schema( $post );
        if ( $breadcrumbs ) {
            $schemas[] = $breadcrumbs;
        }
    }

    /* Homepage: add Person schema prominently */
    if ( is_front_page() || is_home() ) {
        $schemas[] = $person_schema;
    }

    return count( $schemas ) === 1 ? $schemas[0] : [ '@graph' => $schemas ];
}

/**
 * Build BreadcrumbList schema for the current page.
 */
function ds_build_breadcrumb_schema( ?WP_Post $post ): array {
    $items   = [];
    $items[] = [
        '@type'    => 'ListItem',
        'position' => 1,
        'name'     => get_bloginfo( 'name' ),
        'item'     => home_url( '/' ),
    ];

    if ( is_singular( 'post' ) && $post ) {
        $cats = get_the_category( $post->ID );
        if ( $cats ) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => $cats[0]->name,
                'item'     => get_category_link( $cats[0] ),
            ];
            $items[] = [
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => get_the_title( $post ),
                'item'     => get_the_permalink( $post ),
            ];
        } else {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => get_the_title( $post ),
                'item'     => get_the_permalink( $post ),
            ];
        }
    } elseif ( is_singular() && $post ) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => get_the_title( $post ),
            'item'     => get_the_permalink( $post ),
        ];
    } elseif ( is_category() ) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => single_cat_title( '', false ),
            'item'     => get_term_link( get_queried_object() ),
        ];
    }

    if ( count( $items ) <= 1 ) {
        return [];
    }

    return [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
}
