<?php
defined( 'ABSPATH' ) || exit;

class DS_Widget_Services extends \Elementor\Widget_Base {

    public function get_name(): string    { return 'ds-services'; }
    public function get_title(): string   { return __( 'DS Services Section', 'dawn-simmons' ); }
    public function get_icon(): string    { return 'eicon-apps'; }
    public function get_categories(): array { return [ 'dawn-simmons' ]; }

    protected function register_controls(): void {
        $this->start_controls_section( 'section_header', [
            'label' => __( 'Header', 'dawn-simmons' ),
        ] );
        $this->add_control( 'eyebrow', [ 'label' => __( 'Eyebrow', 'dawn-simmons' ),   'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'What I Do' ] );
        $this->add_control( 'title',   [ 'label' => __( 'Title', 'dawn-simmons' ),     'type' => \Elementor\Controls_Manager::TEXTAREA,  'default' => 'End-to-End <em>ServiceNow</em> Excellence' ] );
        $this->add_control( 'sub',     [ 'label' => __( 'Subtitle', 'dawn-simmons' ),  'type' => \Elementor\Controls_Manager::TEXTAREA,  'default' => 'From strategy through delivery, I bring senior-level expertise across every phase of the ServiceNow lifecycle.' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'section_services', [
            'label' => __( 'Services', 'dawn-simmons' ),
        ] );
        $r = new \Elementor\Repeater();
        $r->add_control( 'num',   [ 'label' => __( 'Number', 'dawn-simmons' ),      'type' => \Elementor\Controls_Manager::TEXT,     'default' => '01' ] );
        $r->add_control( 'title', [ 'label' => __( 'Title', 'dawn-simmons' ),       'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Service Title' ] );
        $r->add_control( 'desc',  [ 'label' => __( 'Description', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Description.' ] );
        $r->add_control( 'tags',  [ 'label' => __( 'Tags (comma-sep)', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Tag1, Tag2' ] );

        $this->add_control( 'services', [
            'label'       => __( 'Services', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $r->get_controls(),
            'default'     => [
                [ 'num' => '01', 'title' => 'ServiceNow Implementation', 'desc' => 'Full-cycle ITSM, ITOM, and CSM implementations tailored to your business processes and industry.', 'tags' => 'ITSM, ITOM, CSM, CMDB' ],
                [ 'num' => '02', 'title' => 'AI & Process Automation',   'desc' => 'Intelligent automation frameworks using Flow Designer, IntegrationHub, and custom ML pipelines.', 'tags' => 'AI, ML, Flow Designer' ],
                [ 'num' => '03', 'title' => 'Platform Architecture',     'desc' => 'Instance strategy, multi-instance design, and technical governance for enterprise scale.', 'tags' => 'Architecture, Governance' ],
                [ 'num' => '04', 'title' => 'Executive Advisory',        'desc' => 'Strategic roadmapping, technology selection, and board-level digital transformation counsel.', 'tags' => 'Strategy, Advisory' ],
                [ 'num' => '05', 'title' => 'Training & Enablement',     'desc' => 'Custom training programmes, certification support, and internal capability building.', 'tags' => 'Training, Enablement' ],
                [ 'num' => '06', 'title' => 'Managed Services',          'desc' => 'Ongoing platform optimisation, administration, and continuous improvement retainers.', 'tags' => 'Support, Optimisation' ],
            ],
            'title_field' => '{{{ num }}} — {{{ title }}}',
        ] );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <section id="services">
            <div class="container">
                <div class="section-header">
                    <div class="section-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
                    <h2 class="section-title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
                    <p class="section-sub"><?php echo esc_html( $s['sub'] ); ?></p>
                </div>
                <div class="services-grid">
                    <?php foreach ( $s['services'] as $svc ) :
                        $tags = array_filter( array_map( 'trim', explode( ',', $svc['tags'] ) ) );
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
    }
}
