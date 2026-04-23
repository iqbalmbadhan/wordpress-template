<?php
defined( 'ABSPATH' ) || exit;

class DS_Widget_AI_Section extends \Elementor\Widget_Base {

    public function get_name(): string    { return 'ds-ai-section'; }
    public function get_title(): string   { return __( 'DS AI & Automation Section', 'dawn-simmons' ); }
    public function get_icon(): string    { return 'eicon-code'; }
    public function get_categories(): array { return [ 'dawn-simmons' ]; }

    protected function register_controls(): void {
        $this->start_controls_section( 'section_intro', [
            'label' => __( 'Intro', 'dawn-simmons' ),
        ] );

        $this->add_control( 'eyebrow', [
            'label'   => __( 'Eyebrow', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'AI & Automation',
        ] );
        $this->add_control( 'headline', [
            'label'   => __( 'Headline', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Powering the Future with <em>Intelligent Automation</em>',
        ] );
        $this->add_control( 'lead', [
            'label'   => __( 'Lead Text', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'From AI-powered ITSM to predictive analytics, I design automation frameworks that eliminate toil and unlock strategic capacity.',
        ] );

        $this->end_controls_section();

        /* ── Pills ── */
        $this->start_controls_section( 'section_pills', [
            'label' => __( 'Capability Pills', 'dawn-simmons' ),
        ] );
        $this->add_control( 'pills', [
            'label'   => __( 'Pills (one per line)', 'dawn-simmons' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => "Predictive Analytics\nNatural Language Processing\nProcess Mining\nML Ops\nAI Governance",
        ] );
        $this->end_controls_section();

        /* ── Flow Steps ── */
        $this->start_controls_section( 'section_flow', [
            'label' => __( 'Automation Flow', 'dawn-simmons' ),
        ] );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'icon_char', [ 'label' => __( 'Icon (emoji)', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '⚙' ] );
        $repeater->add_control( 'name',      [ 'label' => __( 'Step Name', 'dawn-simmons' ),   'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Step' ] );
        $repeater->add_control( 'desc',      [ 'label' => __( 'Description', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Description' ] );
        $this->add_control( 'flow_steps', [
            'label'       => __( 'Flow Steps', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'icon_char' => '🔍', 'name' => 'Discovery & Assessment',    'desc' => 'Map current processes and pain points' ],
                [ 'icon_char' => '🧠', 'name' => 'AI Model Design',          'desc' => 'Select and train models for your data' ],
                [ 'icon_char' => '⚙',  'name' => 'ServiceNow Integration',   'desc' => 'Deploy via Flow Designer & IntegrationHub' ],
                [ 'icon_char' => '📈', 'name' => 'Continuous Optimisation',  'desc' => 'Monitor KPIs and retrain models' ],
            ],
            'title_field' => '{{{ name }}}',
        ] );
        $this->end_controls_section();

        /* ── Cards ── */
        $this->start_controls_section( 'section_cards', [
            'label' => __( 'AI Capability Cards', 'dawn-simmons' ),
        ] );
        $rc = new \Elementor\Repeater();
        $rc->add_control( 'icon',  [ 'label' => __( 'Icon (emoji)', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '🤖' ] );
        $rc->add_control( 'title', [ 'label' => __( 'Title', 'dawn-simmons' ),        'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'AI Feature' ] );
        $rc->add_control( 'desc',  [ 'label' => __( 'Description', 'dawn-simmons' ),  'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Description here.' ] );
        $this->add_control( 'cards', [
            'label'       => __( 'Cards', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $rc->get_controls(),
            'default'     => [
                [ 'icon' => '🤖', 'title' => 'AI-Powered ITSM',        'desc' => 'Intelligent ticket routing, auto-resolution and predictive incident management.' ],
                [ 'icon' => '📊', 'title' => 'Predictive Analytics',    'desc' => 'Forecast capacity, predict outages and optimise resource allocation.' ],
                [ 'icon' => '🔗', 'title' => 'Process Automation',      'desc' => 'End-to-end workflow automation using ServiceNow Flow Designer.' ],
                [ 'icon' => '🛡', 'title' => 'AI Governance Framework', 'desc' => 'Responsible AI implementation with bias detection and audit trails.' ],
            ],
            'title_field' => '{{{ title }}}',
        ] );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s     = $this->get_settings_for_display();
        $pills = array_filter( array_map( 'trim', explode( "\n", $s['pills'] ) ) );
        ?>
        <section id="ai">
            <div class="container">
                <div class="ai-intro-grid">
                    <div>
                        <div class="section-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
                        <h2 class="ai-headline"><?php echo wp_kses_post( $s['headline'] ); ?></h2>
                        <p class="ai-lead"><?php echo esc_html( $s['lead'] ); ?></p>
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
                            <?php foreach ( $s['flow_steps'] as $i => $step ) : ?>
                                <?php if ( $i > 0 ) : ?><div class="ai-connector"></div><?php endif; ?>
                                <div class="ai-flow-step">
                                    <div class="ai-flow-icon"><?php echo esc_html( $step['icon_char'] ); ?></div>
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
                    <?php foreach ( $s['cards'] as $card ) : ?>
                    <div class="ai-card fade-in">
                        <div class="ai-card-icon" aria-hidden="true"><?php echo esc_html( $card['icon'] ); ?></div>
                        <div class="ai-card-title"><?php echo esc_html( $card['title'] ); ?></div>
                        <div class="ai-card-desc"><?php echo esc_html( $card['desc'] ); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
