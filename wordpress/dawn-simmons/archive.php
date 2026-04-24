<?php get_header(); ?>

<!-- PAGE HERO -->
<header class="page-hero">
    <div class="hero-bg-text" aria-hidden="true">Blog</div>
    <div class="page-hero-inner">
        <div>
            <div style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--accent);display:flex;align-items:center;gap:10px;margin-bottom:14px">
                <span style="display:block;width:24px;height:1px;background:var(--accent)"></span>
                <?php
                if ( is_category() ) :
                    single_cat_title();
                elseif ( is_tag() ) :
                    single_tag_title( __( 'Tag: ', 'dawn-simmons' ) );
                elseif ( is_author() ) :
                    the_author();
                elseif ( is_search() ) :
                    printf( esc_html__( 'Search Results for: %s', 'dawn-simmons' ), '<em>' . get_search_query() . '</em>' );
                else :
                    esc_html_e( 'Insights & Ideas', 'dawn-simmons' );
                endif;
                ?>
            </div>
            <h1>Expert <em>Perspectives</em></h1>
            <p class="page-hero-sub"><?php esc_html_e( 'ServiceNow, AI automation, ITSM, digital transformation — straight from the field.', 'dawn-simmons' ); ?></p>
        </div>
    </div>
</header>

<main id="main-content">
    <div class="blog-layout">
        <section aria-label="<?php esc_attr_e( 'Blog posts', 'dawn-simmons' ); ?>">
            <?php if ( have_posts() ) : ?>
            <div class="posts-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="post-card fade-in">
                    <div class="post-img"><?php ds_post_thumbnail(); ?></div>
                    <div class="post-body">
                        <div class="post-cat">
                            <?php echo esc_html( implode( ' · ', array_map( fn($c) => $c->name, get_the_category() ) ) ); ?>
                        </div>
                        <h2 class="post-title"><?php the_title(); ?></h2>
                        <p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        <div class="post-meta">
                            <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                            <div class="post-meta-dot"></div>
                            <span><?php echo esc_html( ds_reading_time() ); ?></span>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
            <?php ds_pagination(); ?>
            <?php else : ?>
            <p><?php esc_html_e( 'No posts found.', 'dawn-simmons' ); ?></p>
            <?php endif; ?>
        </section>

        <aside class="sidebar" aria-label="<?php esc_attr_e( 'Blog sidebar', 'dawn-simmons' ); ?>">
            <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
                <?php dynamic_sidebar( 'sidebar-blog' ); ?>
            <?php else : ?>
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'Search', 'dawn-simmons' ); ?></div>
                <?php get_search_form(); ?>
            </div>
            <div class="sidebar-card">
                <div class="sidebar-title"><?php esc_html_e( 'Categories', 'dawn-simmons' ); ?></div>
                <div class="cat-list">
                    <?php wp_list_categories( [ 'title_li' => '', 'show_count' => true ] ); ?>
                </div>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</main>

<?php get_footer(); ?>
