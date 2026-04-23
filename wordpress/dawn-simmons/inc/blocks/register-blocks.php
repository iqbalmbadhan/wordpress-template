<?php
defined( 'ABSPATH' ) || exit;

/**
 * Register all Dawn Simmons custom Gutenberg blocks.
 * Each block uses a PHP render callback so content stays editable
 * via block attributes and the post editor without a JS build step for rendering.
 */
add_action( 'init', 'ds_register_blocks' );

function ds_register_blocks(): void {
    $blocks = [
        'hero',
        'services',
        'ai-section',
        'about',
        'testimonials',
        'contact',
    ];

    foreach ( $blocks as $block ) {
        $dir = DS_DIR . '/inc/blocks/' . $block;
        if ( file_exists( $dir . '/block.json' ) ) {
            register_block_type( $dir );
        }
    }
}

/* ─────────────────────────────────────────────────────────────
   PHP render callbacks — one per block
   ───────────────────────────────────────────────────────────── */

function ds_render_hero( array $attrs ): string {
    $eyebrow   = esc_html( $attrs['eyebrow']   ?? 'ServiceNow Consultant & AI Strategist' );
    $heading   = wp_kses_post( $attrs['heading']   ?? 'Transforming Enterprise IT Through <em>Intelligent Design</em>' );
    $sub       = esc_html( $attrs['subheading'] ?? 'Senior ServiceNow consultant and AI automation specialist with 12+ years driving digital transformation for Fortune 500 enterprises.' );
    $btn1_text = esc_html( $attrs['btnPrimaryText']   ?? 'View My Work' );
    $btn1_url  = esc_url( $attrs['btnPrimaryUrl']    ?? '#services' );
    $btn2_text = esc_html( $attrs['btnSecondaryText'] ?? "Let's Connect" );
    $btn2_url  = esc_url( $attrs['btnSecondaryUrl']  ?? '#contact' );
    $photo_url = esc_url( $attrs['photoUrl'] ?? '' );

    $roles_raw = $attrs['roles'] ?? "ServiceNow Elite Partner Consultant\nAI/ML Integration Specialist\nDigital Transformation Lead";
    $roles     = array_filter( array_map( 'sanitize_text_field', explode( "\n", $roles_raw ) ) );

    $stats = $attrs['stats'] ?? [
        [ 'num' => 50, 'suffix' => '+', 'label' => 'Enterprise Deployments' ],
        [ 'num' => 12, 'suffix' => '+', 'label' => 'Years Experience' ],
        [ 'num' => 98, 'suffix' => '%', 'label' => 'Client Satisfaction' ],
    ];

    ob_start();
    ?>
    <section id="hero">
        <div class="bg-grid" aria-hidden="true"></div>
        <div class="bg-glow" aria-hidden="true"></div>
        <div class="hero-grid container">
            <div class="hero-content">
                <div class="hero-eyebrow"><?php echo $eyebrow; ?></div>
                <h1><?php echo $heading; ?></h1>
                <p class="hero-sub"><?php echo $sub; ?></p>
                <div class="hero-roles">
                    <?php foreach ( $roles as $role ) : ?>
                    <div class="hero-role">
                        <span class="hero-role-dot"></span>
                        <?php echo esc_html( $role ); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="hero-btns">
                    <a href="<?php echo $btn1_url; ?>" class="btn-primary"><?php echo $btn1_text; ?></a>
                    <a href="<?php echo $btn2_url; ?>" class="btn-outline"><?php echo $btn2_text; ?></a>
                </div>
                <div class="hero-stats">
                    <?php foreach ( $stats as $stat ) : ?>
                    <div>
                        <div class="stat-num">
                            <span class="counter" data-target="<?php echo esc_attr( $stat['num'] ); ?>"><?php echo esc_html( $stat['num'] ); ?></span><?php echo esc_html( $stat['suffix'] ); ?>
                        </div>
                        <div class="stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-card-accent" aria-hidden="true"></div>
                <div class="hero-card-accent2" aria-hidden="true"></div>
                <div class="hero-card">
                    <div class="hero-photo-placeholder">
                        <?php if ( $photo_url ) : ?>
                            <img src="<?php echo $photo_url; ?>" alt="<?php esc_attr_e( 'Profile photo', 'dawn-simmons' ); ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                        <?php else : ?>
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 10-16 0"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="hero-card-name">Dawn C. Simmons</div>
                    <div class="hero-card-title"><?php echo $eyebrow; ?></div>
                    <div class="hero-card-badges">
                        <span class="badge">ServiceNow</span>
                        <span class="badge">ITSM</span>
                        <span class="badge">AI/ML</span>
                        <span class="badge">ITOM</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

function ds_render_services( array $attrs ): string {
    $eyebrow  = esc_html( $attrs['eyebrow'] ?? 'What I Do' );
    $title    = wp_kses_post( $attrs['title'] ?? 'End-to-End <em>ServiceNow</em> Excellence' );
    $sub      = esc_html( $attrs['sub'] ?? '' );
    $services = $attrs['services'] ?? [
        [ 'num' => '01', 'title' => 'ServiceNow Implementation', 'desc' => 'Full-cycle ITSM, ITOM, and CSM implementations tailored to your business processes.', 'tags' => 'ITSM, ITOM, CSM' ],
        [ 'num' => '02', 'title' => 'AI & Process Automation',   'desc' => 'Intelligent automation using Flow Designer, IntegrationHub, and custom ML pipelines.', 'tags' => 'AI, ML, Flow Designer' ],
        [ 'num' => '03', 'title' => 'Platform Architecture',     'desc' => 'Instance strategy, multi-instance design, and technical governance for enterprise scale.', 'tags' => 'Architecture, Governance' ],
        [ 'num' => '04', 'title' => 'Executive Advisory',        'desc' => 'Strategic roadmapping and board-level digital transformation counsel.', 'tags' => 'Strategy, Advisory' ],
        [ 'num' => '05', 'title' => 'Training & Enablement',     'desc' => 'Custom training programmes and internal capability building.', 'tags' => 'Training, Enablement' ],
        [ 'num' => '06', 'title' => 'Managed Services',          'desc' => 'Ongoing platform optimisation and continuous improvement retainers.', 'tags' => 'Support, Optimisation' ],
    ];

    ob_start();
    ?>
    <section id="services">
        <div class="container">
            <div class="section-header">
                <div class="section-eyebrow"><?php echo $eyebrow; ?></div>
                <h2 class="section-title"><?php echo $title; ?></h2>
                <?php if ( $sub ) : ?><p class="section-sub"><?php echo $sub; ?></p><?php endif; ?>
            </div>
            <div class="services-grid">
                <?php foreach ( $services as $svc ) :
                    $tags = array_filter( array_map( 'trim', explode( ',', $svc['tags'] ?? '' ) ) );
                ?>
                <div class="service-card fade-in">
                    <div class="service-num"><?php echo esc_html( $svc['num'] ); ?></div>
                    <div class="service-title"><?php echo esc_html( $svc['title'] ); ?></div>
                    <p class="service-desc"><?php echo esc_html( $svc['desc'] ); ?></p>
                    <div class="service-tags">
                        <?php foreach ( $tags as $tag ) : ?>
                        <span class="stag"><?php echo esc_html( $tag ); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

function ds_render_ai_section( array $attrs ): string {
    $eyebrow  = esc_html( $attrs['eyebrow'] ?? 'AI & Automation' );
    $headline = wp_kses_post( $attrs['headline'] ?? 'Powering the Future with <em>Intelligent Automation</em>' );
    $lead     = esc_html( $attrs['lead'] ?? '' );
    $pills    = array_filter( array_map( 'sanitize_text_field', explode( "\n", $attrs['pills'] ?? 'Predictive Analytics\nNLP\nProcess Mining\nML Ops' ) ) );
    $steps    = $attrs['flowSteps'] ?? [
        [ 'icon' => '🔍', 'name' => 'Discovery & Assessment',   'desc' => 'Map current processes and pain points' ],
        [ 'icon' => '🧠', 'name' => 'AI Model Design',          'desc' => 'Select and train models for your data' ],
        [ 'icon' => '⚙',  'name' => 'ServiceNow Integration',   'desc' => 'Deploy via Flow Designer & IntegrationHub' ],
        [ 'icon' => '📈', 'name' => 'Continuous Optimisation',  'desc' => 'Monitor KPIs and retrain models' ],
    ];
    $cards    = $attrs['cards'] ?? [
        [ 'icon' => '🤖', 'title' => 'AI-Powered ITSM',        'desc' => 'Intelligent ticket routing and auto-resolution.' ],
        [ 'icon' => '📊', 'title' => 'Predictive Analytics',    'desc' => 'Forecast capacity and predict outages.' ],
        [ 'icon' => '🔗', 'title' => 'Process Automation',      'desc' => 'End-to-end workflow automation.' ],
        [ 'icon' => '🛡', 'title' => 'AI Governance Framework', 'desc' => 'Responsible AI with audit trails.' ],
    ];

    ob_start();
    ?>
    <section id="ai">
        <div class="container">
            <div class="ai-intro-grid">
                <div>
                    <div class="section-eyebrow"><?php echo $eyebrow; ?></div>
                    <h2 class="ai-headline"><?php echo $headline; ?></h2>
                    <?php if ( $lead ) : ?><p class="ai-lead"><?php echo $lead; ?></p><?php endif; ?>
                    <div class="ai-pills">
                        <?php foreach ( $pills as $pill ) : ?>
                        <span class="ai-pill"><?php echo esc_html( $pill ); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="ai-visual">
                    <div class="ai-glow" aria-hidden="true"></div>
                    <div class="ai-visual-label"><?php esc_html_e( 'AI Automation Flow', 'dawn-simmons' ); ?></div>
                    <div class="ai-flow">
                        <?php foreach ( $steps as $i => $step ) : ?>
                            <?php if ( $i > 0 ) : ?><div class="ai-connector"></div><?php endif; ?>
                            <div class="ai-flow-step">
                                <div class="ai-flow-icon"><?php echo esc_html( $step['icon'] ?? '⚙' ); ?></div>
                                <div class="ai-flow-text">
                                    <div class="ai-flow-name"><?php echo esc_html( $step['name'] ); ?></div>
                                    <div class="ai-flow-desc"><?php echo esc_html( $step['desc'] ); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="ai-cards-grid">
                <?php foreach ( $cards as $card ) : ?>
                <div class="ai-card fade-in">
                    <div class="ai-card-icon"><?php echo esc_html( $card['icon'] ?? '' ); ?></div>
                    <div class="ai-card-title"><?php echo esc_html( $card['title'] ); ?></div>
                    <div class="ai-card-desc"><?php echo esc_html( $card['desc'] ); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

function ds_render_about( array $attrs ): string {
    $eyebrow  = esc_html( $attrs['eyebrow'] ?? 'About Me' );
    $title    = wp_kses_post( $attrs['title'] ?? 'Bridging Technology &amp; <em>Human Impact</em>' );
    $bio1     = esc_html( $attrs['bio1'] ?? "With over 12 years in enterprise IT and digital transformation, I've helped organisations across healthcare, finance, and government modernise their service management platforms." );
    $bio2     = esc_html( $attrs['bio2'] ?? "My approach combines technical depth with strategic vision — ensuring technology investments translate into measurable business outcomes." );
    $photo    = esc_url( $attrs['photoUrl'] ?? '' );
    $skills   = $attrs['skills'] ?? [
        [ 'skill' => 'ServiceNow Platform', 'pct' => 95 ],
        [ 'skill' => 'AI/ML Integration',   'pct' => 88 ],
        [ 'skill' => 'ITIL & Frameworks',   'pct' => 92 ],
        [ 'skill' => 'Solution Architecture','pct' => 85 ],
    ];
    $details  = $attrs['details'] ?? [
        [ 'label' => 'Location',       'value' => 'Remote / USA' ],
        [ 'label' => 'Certifications', 'value' => 'CSA, CAD, CIS-ITSM, PMP' ],
        [ 'label' => 'Availability',   'value' => 'Consulting & Advisory' ],
        [ 'label' => 'Industries',     'value' => 'Healthcare, Finance, Gov' ],
    ];

    ob_start();
    ?>
    <section id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-photo">
                    <?php if ( $photo ) : ?>
                        <img src="<?php echo $photo; ?>" alt="<?php esc_attr_e( 'About photo', 'dawn-simmons' ); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                    <?php else : ?>
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity:.3"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 10-16 0"/></svg>
                    <?php endif; ?>
                    <div class="about-photo-accent" aria-hidden="true"></div>
                </div>
                <div class="about-info">
                    <div class="section-eyebrow"><?php echo $eyebrow; ?></div>
                    <h2 class="section-title"><?php echo $title; ?></h2>
                    <p><?php echo $bio1; ?></p>
                    <p><?php echo $bio2; ?></p>
                    <div class="skills-list">
                        <?php foreach ( $skills as $skill ) : ?>
                        <div>
                            <div class="skill-head">
                                <span><?php echo esc_html( $skill['skill'] ); ?></span>
                                <span class="skill-pct"><?php echo esc_html( $skill['pct'] ); ?>%</span>
                            </div>
                            <div class="skill-track">
                                <div class="skill-fill" data-width="<?php echo esc_attr( $skill['pct'] ); ?>"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="about-details">
                        <?php foreach ( $details as $d ) : ?>
                        <div class="detail-item">
                            <div class="detail-label"><?php echo esc_html( $d['label'] ); ?></div>
                            <div class="detail-val"><?php echo esc_html( $d['value'] ); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

function ds_render_testimonials( array $attrs ): string {
    $eyebrow = esc_html( $attrs['eyebrow'] ?? 'Client Voices' );
    $title   = wp_kses_post( $attrs['title'] ?? 'What Leaders <em>Say</em>' );
    $items   = $attrs['testimonials'] ?? [
        [ 'text' => "Dawn's ServiceNow implementation transformed our IT operations. Ticket resolution time dropped 60% in the first quarter.", 'name' => 'Michael Chen',    'role' => 'CIO, HealthFirst Systems',   'initial' => 'M' ],
        [ 'text' => "The AI automation framework Dawn architected processes 40,000 requests monthly with 94% auto-resolution. Transformative.", 'name' => 'Sarah Johnson',   'role' => 'VP Technology, Apex Capital', 'initial' => 'S' ],
        [ 'text' => "Dawn translates complex technical solutions into clear business value. Our board finally understands IT investment ROI.",   'name' => 'Robert Williams', 'role' => 'COO, Federal Agency',         'initial' => 'R' ],
    ];

    ob_start();
    ?>
    <section id="testimonials">
        <div class="container">
            <div class="section-header">
                <div class="section-eyebrow"><?php echo $eyebrow; ?></div>
                <h2 class="section-title"><?php echo $title; ?></h2>
            </div>
            <div class="testimonials-grid">
                <?php foreach ( $items as $t ) : ?>
                <div class="tcard fade-in">
                    <div class="tcard-quote" aria-hidden="true">"</div>
                    <p class="tcard-text"><?php echo esc_html( $t['text'] ); ?></p>
                    <div class="tcard-author">
                        <div class="tcard-avatar" aria-hidden="true"><?php echo esc_html( $t['initial'] ); ?></div>
                        <div>
                            <div class="tcard-name"><?php echo esc_html( $t['name'] ); ?></div>
                            <div class="tcard-role"><?php echo esc_html( $t['role'] ); ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

function ds_render_contact( array $attrs ): string {
    $eyebrow  = esc_html( $attrs['eyebrow'] ?? "Let's Work Together" );
    $title    = wp_kses_post( $attrs['title'] ?? 'Start Your <em>Transformation</em>' );
    $sub      = esc_html( $attrs['sub'] ?? "Ready to modernise your IT operations? Let's discuss your ServiceNow roadmap." );
    $email    = sanitize_email( $attrs['email'] ?? 'dawn@dawnsimmons.com' );
    $location = esc_html( $attrs['location'] ?? 'Remote — Available Worldwide' );
    $response = esc_html( $attrs['responseTime'] ?? 'Within 24 hours' );
    $cf7_id   = absint( $attrs['cf7Id'] ?? 0 );

    ob_start();
    ?>
    <section id="contact">
        <div class="container">
            <div class="section-header">
                <div class="section-eyebrow"><?php echo $eyebrow; ?></div>
                <h2 class="section-title"><?php echo $title; ?></h2>
                <p class="section-sub"><?php echo $sub; ?></p>
            </div>
            <div class="contact-grid">
                <div>
                    <div class="contact-item">
                        <div class="contact-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
                        <div>
                            <div class="contact-label"><?php esc_html_e( 'Email', 'dawn-simmons' ); ?></div>
                            <div class="contact-val"><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
                        <div>
                            <div class="contact-label"><?php esc_html_e( 'Location', 'dawn-simmons' ); ?></div>
                            <div class="contact-val"><?php echo $location; ?></div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                        <div>
                            <div class="contact-label"><?php esc_html_e( 'Response Time', 'dawn-simmons' ); ?></div>
                            <div class="contact-val"><?php echo $response; ?></div>
                        </div>
                    </div>
                </div>
                <div>
                    <?php if ( $cf7_id && shortcode_exists( 'contact-form-7' ) ) : ?>
                        <?php echo do_shortcode( "[contact-form-7 id=\"{$cf7_id}\"]" ); ?>
                    <?php else : ?>
                    <form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <?php wp_nonce_field( 'ds_contact', 'ds_contact_nonce' ); ?>
                        <input type="hidden" name="action" value="ds_contact_submit">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ds-name"><?php esc_html_e( 'Name', 'dawn-simmons' ); ?></label>
                                <input type="text" id="ds-name" name="ds_name" required>
                            </div>
                            <div class="form-group">
                                <label for="ds-email"><?php esc_html_e( 'Email', 'dawn-simmons' ); ?></label>
                                <input type="email" id="ds-email" name="ds_email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ds-message"><?php esc_html_e( 'Message', 'dawn-simmons' ); ?></label>
                            <textarea id="ds-message" name="ds_message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn-primary form-submit"><?php esc_html_e( 'Send Message', 'dawn-simmons' ); ?></button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// ── Contact form handler ──────────────────────────────────────────────────────
add_action( 'admin_post_nopriv_ds_contact_submit', 'ds_handle_contact_form' );
add_action( 'admin_post_ds_contact_submit',        'ds_handle_contact_form' );

function ds_handle_contact_form(): void {
    if ( ! wp_verify_nonce( $_POST['ds_contact_nonce'] ?? '', 'ds_contact' ) ) {
        wp_die( esc_html__( 'Security check failed.', 'dawn-simmons' ) );
    }
    $name    = sanitize_text_field( $_POST['ds_name']    ?? '' );
    $email   = sanitize_email( $_POST['ds_email']        ?? '' );
    $message = sanitize_textarea_field( $_POST['ds_message'] ?? '' );
    $to      = get_option( 'admin_email' );
    $subject = sprintf( __( 'New contact from %s', 'dawn-simmons' ), $name );
    $body    = "Name: {$name}\nEmail: {$email}\n\n{$message}";
    wp_mail( $to, $subject, $body, [ "Reply-To: {$email}" ] );
    wp_safe_redirect( add_query_arg( 'ds_sent', '1', wp_get_referer() ) );
    exit;
}
