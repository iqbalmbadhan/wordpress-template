<?php get_header(); ?>

<!-- ARTICLE HERO -->
<header class="article-hero" role="banner">
    <div class="article-hero-inner">
        <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dawn-simmons' ); ?>">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'dawn-simmons' ); ?></a> ›
            <a href="<?php echo esc_url( get_permalink( get_option( 'ds_page_blog' ) ) ?: home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'dawn-simmons' ); ?></a> ›
            <?php
            $hero_cats = get_the_category();
            if ( $hero_cats ) :
                $first_cat = $hero_cats[0];
                echo '<a href="' . esc_url( get_category_link( $first_cat ) ) . '">' . esc_html( $first_cat->name ) . '</a> › ';
            endif;
            ?>
            <?php the_title( '<span aria-current="page">', '</span>' ); ?>
        </nav>
        <?php
        $cats = get_the_category();
        if ( $cats ) :
            $cat_names = implode( ' · ', array_map( fn( $c ) => esc_html( $c->name ), $cats ) );
            echo '<div class="article-cat-badge">' . $cat_names . '</div>';
        endif;
        ?>
        <?php the_title( '<h1 class="article-title">', '</h1>' ); ?>
        <div class="article-meta" role="contentinfo">
            <div class="article-meta-avatar">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 36, '', get_the_author(), [ 'class' => '' ] ); ?>
            </div>
            <strong><?php the_author(); ?></strong>
            <div class="meta-dot"></div>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
            <div class="meta-dot"></div>
            <span><?php echo esc_html( ds_reading_time() ); ?></span>
            <?php if ( $cats ) : ?>
            <div class="meta-dot"></div>
            <span style="color:var(--accent)"><?php echo esc_html( $cats[0]->name ); ?></span>
            <?php endif; ?>
        </div>
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="article-feature-img" aria-label="<?php esc_attr_e( 'Article feature image', 'dawn-simmons' ); ?>">
            <div class="article-feature-img-glow"></div>
            <?php the_post_thumbnail( 'ds-featured', [ 'alt' => get_the_title(), 'style' => 'position:relative;z-index:1;width:100%;height:100%;object-fit:cover;' ] ); ?>
        </div>
        <?php endif; ?>
    </div>
</header>

<main id="main-content">
    <div class="article-layout">

        <!-- ARTICLE BODY -->
        <article class="article-body" itemscope itemtype="https://schema.org/BlogPosting">
            <?php while ( have_posts() ) : the_post(); ?>
            <meta itemprop="headline" content="<?php echo esc_attr( get_the_title() ); ?>">
            <meta itemprop="datePublished" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
            <meta itemprop="author" content="<?php echo esc_attr( get_the_author() ); ?>">
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

            <!-- About the Author -->
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'About the Author', 'dawn-simmons' ); ?></div>
                <div style="display:flex;gap:12px;align-items:center;margin-bottom:12px">
                    <div style="width:52px;height:52px;border-radius:50%;overflow:hidden;flex-shrink:0;border:2px solid var(--accent)">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 52, '', get_the_author(), [ 'style' => 'width:100%;height:100%;object-fit:cover;object-position:center top' ] ); ?>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:600"><?php the_author(); ?></div>
                        <div style="font-size:12px;color:var(--muted)"><?php echo esc_html( get_the_author_meta( 'description' ) ?: __( 'ServiceNow Consultant · AI Expert', 'dawn-simmons' ) ); ?></div>
                    </div>
                </div>
                <p style="font-size:13px;color:var(--text2);line-height:1.65;margin-bottom:12px"><?php echo esc_html( get_the_author_meta( 'user_description' ) ?: __( '20+ years delivering digital transformation and ServiceNow solutions across Fortune 500 enterprises. Chicago, IL.', 'dawn-simmons' ) ); ?></p>
                <a href="<?php echo esc_url( get_permalink( get_option( 'ds_page_contact' ) ) ?: home_url( '/#contact' ) ); ?>" style="font-size:13px;color:var(--accent);text-decoration:none"><?php esc_html_e( 'Contact Dawn →', 'dawn-simmons' ); ?></a>
            </div>

            <!-- Share -->
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

    <!-- RELATED ARTICLES -->
    <?php
    $related = new WP_Query( [
        'category__in'   => wp_get_post_categories( get_the_ID() ),
        'post__not_in'   => [ get_the_ID() ],
        'posts_per_page' => 3,
        'orderby'        => 'rand',
    ] );
    if ( $related->have_posts() ) :
    ?>
    <section class="ds-related" aria-labelledby="related-heading">
        <div class="ds-related-inner">
            <div class="ds-related-eyebrow">
                <span class="ds-related-eyebrow-line"></span>
                <?php esc_html_e( 'Keep Reading', 'dawn-simmons' ); ?>
            </div>
            <h2 id="related-heading" class="ds-related-title">Related <em><?php esc_html_e( 'Articles', 'dawn-simmons' ); ?></em></h2>
            <div class="ds-related-grid">
                <?php while ( $related->have_posts() ) : $related->the_post();
                    $rc = get_the_category();
                    $rc_str = $rc ? esc_html( implode( ' · ', array_map( fn( $c ) => $c->name, $rc ) ) ) : '';
                ?>
                <a href="<?php the_permalink(); ?>" class="post-card">
                    <div class="post-img">
                        <div class="post-img-accent"></div>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'ds-card', [ 'alt' => get_the_title(), 'style' => 'position:relative;z-index:1;width:100%;height:100%;object-fit:cover;' ] ); ?>
                        <?php else : ?>
                            <span><?php esc_html_e( 'article image', 'dawn-simmons' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="post-body">
                        <div class="post-cat"><?php echo $rc_str; ?></div>
                        <h3 class="post-title"><?php the_title(); ?></h3>
                        <p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
                        <div class="post-meta">
                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                            <div class="post-meta-dot"></div>
                            <span><?php echo esc_html( ds_reading_time() ); ?></span>
                        </div>
                    </div>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
