<?php
/**
 * Front page template.
 *
 * Rendering priority:
 *  1. Elementor — if Elementor is active, edit-mode is "builder", and has data.
 *  2. Gutenberg — if post content contains registered blocks.
 *  3. PHP fallback — direct render via the same callbacks used by the blocks,
 *     so the homepage always looks great even without editor content.
 */

get_header();
?>
<main id="main-content">
<?php
$rendered = false;

if ( have_posts() ) :
    while ( have_posts() ) : the_post();

        $pid = get_the_ID();

        /* ── Detect what content we have ── */
        $has_elementor = (
            class_exists( '\Elementor\Plugin' )
            && 'builder' === get_post_meta( $pid, '_elementor_edit_mode', true )
            && ! empty( get_post_meta( $pid, '_elementor_data', true ) )
        );

        $has_blocks = ! $has_elementor && has_blocks( get_the_content() );

        /* ── Try standard rendering (Elementor or Gutenberg) ── */
        if ( $has_elementor || $has_blocks ) {
            ob_start();
            the_content();
            $content = ob_get_clean();

            if ( ! empty( trim( $content ) ) ) {
                echo $content;
                $rendered = true;
            }
        }

    endwhile;
endif;

/* ── PHP fallback ── */
/* Runs when: Elementor has no/invalid data, blocks are missing,
   or the importer hasn't been run yet. Uses the exact same render
   callbacks as the Gutenberg blocks, so output is identical. */
if ( ! $rendered ) :
    echo ds_render_hero( [] );
    echo ds_render_ai_section( [] );
    echo ds_render_services( [] );
    echo ds_render_about( [] );
    echo ds_render_testimonials( [] );
    echo ds_render_contact( [] );
endif;
?>
</main>

<?php get_footer(); ?>
