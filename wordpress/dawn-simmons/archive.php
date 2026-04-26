<?php get_header(); ?>

<!-- PAGE HERO -->
<header class="page-hero">
    <div class="hero-bg-text" aria-hidden="true">Blog</div>
    <div class="page-hero-inner">
        <div>
            <div class="page-eyebrow">
                <span class="page-eyebrow-line"></span>
                <?php
                if ( is_category() ) :
                    single_cat_title();
                elseif ( is_tag() ) :
                    single_tag_title( __( 'Tag: ', 'dawn-simmons' ) );
                elseif ( is_author() ) :
                    the_author();
                elseif ( is_search() ) :
                    printf( esc_html__( 'Search Results for: %s', 'dawn-simmons' ), get_search_query() );
                else :
                    esc_html_e( 'Insights & Ideas', 'dawn-simmons' );
                endif;
                ?>
            </div>
            <h1>Expert <em>Perspectives</em></h1>
            <p class="page-hero-sub"><?php esc_html_e( 'ServiceNow, AI automation, ITSM, digital transformation — straight from the field.', 'dawn-simmons' ); ?></p>
        </div>
        <?php if ( ! is_category() && ! is_tag() && ! is_author() && ! is_search() ) : ?>
        <div class="hero-stats">
            <?php
            $total_posts = (int) wp_count_posts()->publish;
            $total_pages = (int) $GLOBALS['wp_query']->max_num_pages;
            $total_cats  = wp_count_terms( [ 'taxonomy' => 'category', 'hide_empty' => true ] );
            $total_cats  = is_wp_error( $total_cats ) ? 0 : (int) $total_cats;
            ?>
            <div class="hero-stat">
                <div class="hero-stat-num"><?php echo $total_pages > 0 ? $total_pages : '1'; ?><span>+</span></div>
                <div class="hero-stat-label"><?php esc_html_e( 'Pages', 'dawn-simmons' ); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num" style="color:var(--accent)"><?php echo $total_posts; ?><span>+</span></div>
                <div class="hero-stat-label"><?php esc_html_e( 'Articles', 'dawn-simmons' ); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num"><?php echo $total_cats; ?><span>+</span></div>
                <div class="hero-stat-label"><?php esc_html_e( 'Categories', 'dawn-simmons' ); ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</header>

<!-- FILTER BAR -->
<?php
$filter_cats = get_categories( [ 'orderby' => 'count', 'order' => 'DESC', 'number' => 8, 'hide_empty' => true ] );
if ( $filter_cats ) :
    $current_cat_id = is_category() ? get_queried_object_id() : 0;
    $blog_url = get_permalink( get_option( 'ds_page_blog' ) ) ?: get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' );
?>
<div class="filter-bar" role="navigation" aria-label="<?php esc_attr_e( 'Category filters', 'dawn-simmons' ); ?>">
    <div class="filter-inner">
        <a href="<?php echo esc_url( $blog_url ); ?>" class="filter-pill<?php echo ! is_category() && ! is_tag() ? ' active' : ''; ?>"><?php esc_html_e( 'All Posts', 'dawn-simmons' ); ?></a>
        <?php foreach ( $filter_cats as $cat ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="filter-pill<?php echo $current_cat_id === $cat->term_id ? ' active' : ''; ?>"><?php echo esc_html( $cat->name ); ?></a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<main id="main-content">
    <div class="blog-layout">

        <!-- POSTS COLUMN -->
        <section aria-label="<?php esc_attr_e( 'Blog posts', 'dawn-simmons' ); ?>">
            <?php if ( have_posts() ) : ?>

            <?php
            /* ── Featured post: first post in main loop ── */
            the_post();
            $fp_cats = get_the_category();
            $fp_cat_str = $fp_cats
                ? esc_html( implode( ' · ', array_map( fn( $c ) => $c->name, $fp_cats ) ) )
                : '';
            ?>

            <!-- FEATURED POST -->
            <a href="<?php the_permalink(); ?>" class="featured-post fade-in" aria-label="<?php echo esc_attr( sprintf( __( 'Featured: %s', 'dawn-simmons' ), get_the_title() ) ); ?>">
                <div class="fp-img">
                    <div class="fp-img-glow"></div>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'ds-featured', [ 'alt' => get_the_title(), 'style' => 'position:relative;z-index:1;width:100%;height:100%;object-fit:cover;' ] ); ?>
                    <?php else : ?>
                        <span><?php esc_html_e( 'featured image', 'dawn-simmons' ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="fp-body">
                    <div class="fp-eyebrow">
                        <span class="fp-badge"><?php esc_html_e( 'Featured', 'dawn-simmons' ); ?></span>
                        <span class="fp-cat"><?php echo $fp_cat_str; ?></span>
                    </div>
                    <h2 class="fp-title"><?php the_title(); ?></h2>
                    <p class="fp-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
                    <div class="fp-meta">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                        <div class="fp-meta-dot"></div>
                        <span><?php echo esc_html( ds_reading_time() ); ?></span>
                    </div>
                    <div class="fp-read">
                        <?php esc_html_e( 'Read article', 'dawn-simmons' ); ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </a>

            <!-- POSTS GRID (remaining posts) -->
            <?php if ( have_posts() ) : ?>
            <div class="posts-section">
                <h2><?php esc_html_e( 'Recent Posts', 'dawn-simmons' ); ?></h2>
                <div class="posts-grid">
                    <?php while ( have_posts() ) : the_post();
                        $pc_cats = get_the_category();
                        $pc_cat_str = $pc_cats
                            ? esc_html( implode( ' · ', array_map( fn( $c ) => $c->name, $pc_cats ) ) )
                            : '';
                    ?>
                    <a href="<?php the_permalink(); ?>" class="post-card fade-in">
                        <div class="post-img">
                            <div class="post-img-accent"></div>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'ds-card', [ 'alt' => get_the_title(), 'style' => 'position:relative;z-index:1;width:100%;height:100%;object-fit:cover;' ] ); ?>
                            <?php else : ?>
                                <span><?php esc_html_e( 'image', 'dawn-simmons' ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="post-body">
                            <div class="post-cat"><?php echo $pc_cat_str; ?></div>
                            <h3 class="post-title"><?php the_title(); ?></h3>
                            <p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                            <div class="post-meta">
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                                <div class="post-meta-dot"></div>
                                <span><?php echo esc_html( ds_reading_time() ); ?></span>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- PAGINATION -->
            <?php
            global $wp_query;
            $paged     = max( 1, get_query_var( 'paged' ) );
            $max_pages = (int) $wp_query->max_num_pages;
            if ( $max_pages > 1 ) :
            ?>
            <div class="pagination-wrap fade-in">
                <div class="pagination-label">
                    <?php printf(
                        esc_html__( 'Showing page %1$d of %2$d', 'dawn-simmons' ),
                        $paged, $max_pages
                    ); ?>
                </div>
                <nav class="pagination" aria-label="<?php esc_attr_e( 'Blog pagination', 'dawn-simmons' ); ?>">
                    <?php
                    $page_links = paginate_links( [
                        'total'     => $max_pages,
                        'current'   => $paged,
                        'prev_text' => '← ' . __( 'Prev', 'dawn-simmons' ),
                        'next_text' => __( 'Next', 'dawn-simmons' ) . ' →',
                        'type'      => 'array',
                        'mid_size'  => 3,
                    ] );
                    if ( $page_links ) :
                        foreach ( $page_links as $link ) :
                            $is_current = strpos( $link, 'current' ) !== false;
                            $is_dots    = strpos( $link, 'dots' )    !== false;
                            $is_wide    = strpos( $link, 'prev' )    !== false || strpos( $link, 'next' ) !== false;
                            if ( $is_dots ) {
                                echo '<span class="page-btn dots">…</span>';
                            } elseif ( $is_current ) {
                                echo '<span class="page-btn active">' . strip_tags( $link ) . '</span>';
                            } else {
                                echo preg_replace( '/class="[^"]*"/', 'class="page-btn' . ( $is_wide ? ' wide' : '' ) . '"', $link );
                            }
                        endforeach;
                    endif;
                    ?>
                </nav>
            </div>
            <?php endif; ?>

            <?php else : ?>
            <p class="ds-no-posts"><?php esc_html_e( 'No posts found.', 'dawn-simmons' ); ?></p>
            <?php endif; ?>
        </section>

        <!-- SIDEBAR -->
        <aside class="sidebar" aria-label="<?php esc_attr_e( 'Blog sidebar', 'dawn-simmons' ); ?>">
            <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
                <?php dynamic_sidebar( 'sidebar-blog' ); ?>
            <?php else : ?>

            <!-- About / Author card -->
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'About', 'dawn-simmons' ); ?></div>
                <?php
                $author_id = 1;
                $author_display = get_the_author_meta( 'display_name', $author_id );
                $author_bio     = get_the_author_meta( 'description', $author_id )
                    ?: __( '20+ years delivering digital transformation and ServiceNow solutions across Fortune 500 enterprises. Chicago, IL.', 'dawn-simmons' );
                ?>
                <div class="author-wrap">
                    <div class="author-avatar">
                        <?php echo get_avatar( $author_id, 56, '', $author_display ); ?>
                    </div>
                    <div>
                        <div class="author-name"><?php echo esc_html( $author_display ); ?></div>
                        <div class="author-role"><?php esc_html_e( 'ServiceNow · AI Expert', 'dawn-simmons' ); ?></div>
                    </div>
                </div>
                <p class="author-bio"><?php echo esc_html( $author_bio ); ?></p>
                <a href="<?php echo esc_url( get_permalink( get_option( 'ds_page_contact' ) ) ?: home_url( '/#contact' ) ); ?>" style="font-size:13px;color:var(--accent);text-decoration:none"><?php esc_html_e( 'Get in touch →', 'dawn-simmons' ); ?></a>
            </div>

            <!-- Search -->
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'Search', 'dawn-simmons' ); ?></div>
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-box">
                    <input
                        type="search" name="s" class="search-input"
                        placeholder="<?php esc_attr_e( 'Search articles…', 'dawn-simmons' ); ?>"
                        value="<?php echo esc_attr( get_search_query() ); ?>"
                        aria-label="<?php esc_attr_e( 'Search blog', 'dawn-simmons' ); ?>"
                    >
                    <button type="submit" class="search-btn" aria-label="<?php esc_attr_e( 'Search', 'dawn-simmons' ); ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    </button>
                </form>
            </div>

            <!-- Top Categories -->
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'Top Categories', 'dawn-simmons' ); ?></div>
                <div class="cat-list">
                    <?php
                    $top_cats = get_categories( [ 'orderby' => 'count', 'order' => 'DESC', 'number' => 6, 'hide_empty' => true ] );
                    foreach ( $top_cats as $cat ) :
                    ?>
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="cat-item">
                        <?php echo esc_html( $cat->name ); ?>
                        <span class="cat-count"><?php echo absint( $cat->count ); ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Popular Tags -->
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'Popular Tags', 'dawn-simmons' ); ?></div>
                <div class="tags-wrap">
                    <?php
                    $pop_tags = get_tags( [ 'orderby' => 'count', 'order' => 'DESC', 'number' => 12, 'hide_empty' => true ] );
                    if ( $pop_tags ) :
                        foreach ( $pop_tags as $tag ) :
                    ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag"><?php echo esc_html( $tag->name ); ?></a>
                    <?php
                        endforeach;
                    else :
                        foreach ( [ 'ServiceNow', 'AI Automation', 'ITSM', 'Now Assist', 'CMDB', 'GRC', 'Chicago', 'ITIL v4', 'Digital Transformation' ] as $t ) :
                    ?>
                    <span class="tag"><?php echo esc_html( $t ); ?></span>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>

            <?php endif; ?>
        </aside>

    </div>
</main>

<?php get_footer(); ?>
