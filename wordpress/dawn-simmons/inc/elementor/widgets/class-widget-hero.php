<?php
defined( 'ABSPATH' ) || exit;

class DS_Widget_Hero extends \Elementor\Widget_Base {

    public function get_name(): string    { return 'ds-hero'; }
    public function get_title(): string   { return __( 'DS Hero Section', 'dawn-simmons' ); }
    public function get_icon(): string    { return 'eicon-header'; }
    public function get_categories(): array { return [ 'dawn-simmons' ]; }

    protected function register_controls(): void {
        /* ── Content ── */
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'dawn-simmons' ),
        ] );

        $this->add_control( 'eyebrow', [
            'label'   => __( 'Eyebrow', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'ServiceNow Consultant & AI Strategist',
        ] );

        $this->add_control( 'heading', [
            'label'   => __( 'Heading', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Transforming Enterprise IT Through <em>Intelligent Design</em>',
        ] );

        $this->add_control( 'subheading', [
            'label'   => __( 'Subheading', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Senior ServiceNow consultant and AI automation specialist with 12+ years driving digital transformation for Fortune 500 enterprises.',
        ] );

        $this->add_control( 'btn_primary_text', [
            'label'   => __( 'Primary Button Text', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'View My Work',
        ] );

        $this->add_control( 'btn_primary_url', [
            'label'       => __( 'Primary Button URL', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::URL,
            'placeholder' => '#services',
        ] );

        $this->add_control( 'btn_secondary_text', [
            'label'   => __( 'Secondary Button Text', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => "Let's Connect",
        ] );

        $this->add_control( 'btn_secondary_url', [
            'label'       => __( 'Secondary Button URL', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::URL,
            'placeholder' => '#contact',
        ] );

        $this->add_control( 'photo', [
            'label' => __( 'Profile Photo', 'dawn-simmons' ),
            'type'  => \Elementor\Controls_Manager::MEDIA,
        ] );

        $this->end_controls_section();

        /* ── Roles ── */
        $this->start_controls_section( 'section_roles', [
            'label' => __( 'Role Bullets', 'dawn-simmons' ),
        ] );

        $this->add_control( 'roles', [
            'label'       => __( 'Roles (one per line)', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'default'     => "ServiceNow Elite Partner Consultant\nAI/ML Integration Specialist\nDigital Transformation Lead",
        ] );

        $this->end_controls_section();

        /* ── Stats ── */
        $this->start_controls_section( 'section_stats', [
            'label' => __( 'Stats', 'dawn-simmons' ),
        ] );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'num',   [ 'label' => __( 'Number', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 50 ] );
        $repeater->add_control( 'suffix', [ 'label' => __( 'Suffix', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '+' ] );
        $repeater->add_control( 'label', [ 'label' => __( 'Label', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Projects' ] );

        $this->add_control( 'stats', [
            'label'       => __( 'Stats', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'num' => 50, 'suffix' => '+', 'label' => 'Enterprise Deployments' ],
                [ 'num' => 12, 'suffix' => '+', 'label' => 'Years Experience' ],
                [ 'num' => 98,  'suffix' => '%', 'label' => 'Client Satisfaction' ],
            ],
            'title_field' => '{{{ label }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $s         = $this->get_settings_for_display();
        $photo_url = $s['photo']['url'] ?? '';
        $roles     = array_filter( array_map( 'trim', explode( "\n", $s['roles'] ) ) );
        $btn1_href = ! empty( $s['btn_primary_url']['url'] )   ? esc_url( $s['btn_primary_url']['url'] )   : '#services';
        $btn2_href = ! empty( $s['btn_secondary_url']['url'] ) ? esc_url( $s['btn_secondary_url']['url'] ) : '#contact';
        ?>
        <section id="hero">
            <div class="bg-grid" aria-hidden="true"></div>
            <div class="bg-glow" aria-hidden="true"></div>
            <div class="hero-grid container">
                <div class="hero-content">
                    <div class="hero-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
                    <h1><?php echo wp_kses_post( $s['heading'] ); ?></h1>
                    <p class="hero-sub"><?php echo esc_html( $s['subheading'] ); ?></p>
                    <div class="hero-roles">
                        <?php foreach ( $roles as $role ) : ?>
                        <div class="hero-role">
                            <span class="hero-role-dot"></span>
                            <?php echo esc_html( $role ); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="hero-btns">
                        <a href="<?php echo $btn1_href; ?>" class="btn-primary"><?php echo esc_html( $s['btn_primary_text'] ); ?></a>
                        <a href="<?php echo $btn2_href; ?>" class="btn-outline"><?php echo esc_html( $s['btn_secondary_text'] ); ?></a>
                    </div>
                    <?php if ( ! empty( $s['stats'] ) ) : ?>
                    <div class="hero-stats">
                        <?php foreach ( $s['stats'] as $stat ) : ?>
                        <div>
                            <div class="stat-num">
                                <span class="counter" data-target="<?php echo esc_attr( $stat['num'] ); ?>"><?php echo esc_html( $stat['num'] ); ?></span><?php echo esc_html( $stat['suffix'] ); ?>
                            </div>
                            <div class="stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="hero-visual">
                    <div class="hero-card-accent" aria-hidden="true"></div>
                    <div class="hero-card-accent2" aria-hidden="true"></div>
                    <div class="hero-card">
                        <div class="hero-photo-placeholder">
                            <?php if ( $photo_url ) : ?>
                                <img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php esc_attr_e( 'Profile photo', 'dawn-simmons' ); ?>" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                            <?php else : ?>
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 10-16 0"/></svg>
                                <span><?php esc_html_e( 'Add Photo', 'dawn-simmons' ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="hero-card-name">Dawn C. Simmons</div>
                        <div class="hero-card-title"><?php echo esc_html( $s['eyebrow'] ); ?></div>
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
    }
}
