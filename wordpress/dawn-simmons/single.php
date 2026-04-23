<?php get_header(); ?>

<!-- ARTICLE HERO -->
<header class="article-hero" role="banner">
    <div class="article-hero-inner">
        <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dawn-simmons' ); ?>">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'dawn-simmons' ); ?></a> ›
            <a href="<?php echo esc_url( get_permalink( get_option('ds_page_blog') ) ); ?>"><?php esc_html_e( 'Blog', 'dawn-simmons' ); ?></a> ›
            <?php the_title( '<span aria-current="page">', '</span>' ); ?>
        </nav>
        <?php
        $cats = get_the_category();
        if ( $cats ) :
            $cat_names = implode( ' · ', array_map( fn($c) => esc_html( $c->name ), $cats ) );
            echo '<div class="article-cat-badge">' . $cat_names . '</div>';
        endif;
        ?>
        <?php the_title( '<h1 class="article-title">', '</h1>' ); ?>
        <div class="article-meta" role="contentinfo">
            <div class="article-meta-avatar">
                <?php echo get_avatar( get_the_author_meta('ID'), 36, '', get_the_author(), [ 'class' => '' ] ); ?>
            </div>
            <strong><?php the_author(); ?></strong>
            <div class="meta-dot"></div>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
            <div class="meta-dot"></div>
            <span><?php echo esc_html( ds_reading_time() ); ?></span>
        </div>
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="article-feature-img">
            <?php the_post_thumbnail( 'ds-featured', [ 'alt' => get_the_title() ] ); ?>
        </div>
        <?php endif; ?>
    </div>
</header>

<main id="main-content">
    <div class="article-layout">

        <!-- ARTICLE BODY -->
        <article class="article-body" itemscope itemtype="https://schema.org/BlogPosting">
            <?php while ( have_posts() ) : the_post(); ?>
            <div class="ds-entry-content">
                <?php the_content(); ?>
            </div>
            <div class="article-tags" role="list" aria-label="<?php esc_attr_e( 'Article tags', 'dawn-simmons' ); ?>">
                <?php
                $tags = get_the_tags();
                if ( $tags ) :
                    foreach ( $tags as $tag ) :
                        echo '<a href="' . esc_url( get_tag_link( $tag ) ) . '" class="article-tag" role="listitem">' . esc_html( $tag->name ) . '</a>';
                    endforeach;
                endif;
                ?>
            </div>
            <?php endwhile; ?>
        </article>

        <!-- SIDEBAR -->
        <aside class="sidebar" aria-label="<?php esc_attr_e( 'Article sidebar', 'dawn-simmons' ); ?>">
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'About the Author', 'dawn-simmons' ); ?></div>
                <div style="display:flex;gap:12px;align-items:center;margin-bottom:12px">
                    <div style="width:52px;height:52px;border-radius:50%;overflow:hidden;flex-shrink:0;border:2px solid var(--accent)">
                        <?php echo get_avatar( get_the_author_meta('ID'), 52 ); ?>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:600"><?php the_author(); ?></div>
                        <div style="font-size:12px;color:var(--muted)"><?php the_author_meta( 'description' ); ?></div>
                    </div>
                </div>
            </div>
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'Share This Article', 'dawn-simmons' ); ?></div>
                <div class="share-btns">
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="share-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                        <?php esc_html_e( 'Share on LinkedIn', 'dawn-simmons' ); ?>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" rel="noopener" class="share-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        <?php esc_html_e( 'Share on X', 'dawn-simmons' ); ?>
                    </a>
                </div>
            </div>
            <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
                <?php dynamic_sidebar( 'sidebar-blog' ); ?>
            <?php endif; ?>
        </aside>
    </div>

    <!-- RELATED POSTS -->
    <?php
    $related = new WP_Query( [
        'category__in'   => wp_get_post_categories( get_the_ID() ),
        'post__not_in'   => [ get_the_ID() ],
        'posts_per_page' => 3,
        'orderby'        => 'rand',
    ] );
    if ( $related->have_posts() ) :
    ?>
    <section class="ds-related" style="background:var(--bg2);border-top:1px solid var(--border);padding:64px 48px" aria-label="<?php esc_attr_e( 'Related articles', 'dawn-simmons' ); ?>">
        <div style="max-width:1200px;margin:0 auto">
            <h2 style="font-family:var(--ff-display);font-size:clamp(24px,3vw,36px);font-weight:900;letter-spacing:-0.02em;margin-bottom:36px">
                Related <em style="font-style:italic;color:var(--accent)">Articles</em>
            </h2>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px">
                <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="post-card" style="text-decoration:none">
                    <div class="post-img"><?php ds_post_thumbnail(); ?></div>
                    <div class="post-body">
                        <div class="post-cat"><?php echo esc_html( implode( ' · ', array_map( fn($c) => $c->name, get_the_category() ) ) ); ?></div>
                        <h3 class="post-title"><?php the_title(); ?></h3>
                        <p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
                        <div class="post-meta"><time><?php echo esc_html( get_the_date() ); ?></time></div>
                    </div>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
