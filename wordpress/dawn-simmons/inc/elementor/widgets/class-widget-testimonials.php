<?php
defined( 'ABSPATH' ) || exit;

class DS_Widget_Testimonials extends \Elementor\Widget_Base {

    public function get_name(): string    { return 'ds-testimonials'; }
    public function get_title(): string   { return __( 'DS Testimonials Section', 'dawn-simmons' ); }
    public function get_icon(): string    { return 'eicon-testimonial'; }
    public function get_categories(): array { return [ 'dawn-simmons' ]; }

    protected function register_controls(): void {
        $this->start_controls_section( 'section_header', [
            'label' => __( 'Header', 'dawn-simmons' ),
        ] );
        $this->add_control( 'eyebrow', [ 'label' => __( 'Eyebrow', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT,    'default' => 'Client Voices' ] );
        $this->add_control( 'title',   [ 'label' => __( 'Title', 'dawn-simmons' ),   'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'What Leaders <em>Say</em>' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'section_testimonials', [
            'label' => __( 'Testimonials', 'dawn-simmons' ),
        ] );
        $r = new \Elementor\Repeater();
        $r->add_control( 'text',    [ 'label' => __( 'Quote', 'dawn-simmons' ),      'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Quote text here.' ] );
        $r->add_control( 'name',    [ 'label' => __( 'Name', 'dawn-simmons' ),       'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Client Name' ] );
        $r->add_control( 'role',    [ 'label' => __( 'Role', 'dawn-simmons' ),       'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'CIO, Company' ] );
        $r->add_control( 'initial', [ 'label' => __( 'Avatar Initial', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'A' ] );
        $this->add_control( 'testimonials', [
            'label'       => __( 'Testimonials', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $r->get_controls(),
            'default'     => [
                [ 'text' => "Dawn's ServiceNow implementation transformed our IT operations. Ticket resolution time dropped 60% in the first quarter — results that speak for themselves.", 'name' => 'Michael Chen',     'role' => 'CIO, HealthFirst Systems',   'initial' => 'M' ],
                [ 'text' => "The AI automation framework Dawn architected for us processes 40,000 requests monthly with 94% auto-resolution. Transformative doesn't begin to cover it.", 'name' => 'Sarah Johnson',    'role' => 'VP Technology, Apex Capital', 'initial' => 'S' ],
                [ 'text' => "What sets Dawn apart is her ability to translate complex technical solutions into clear business value. Our board finally understands IT investment ROI.", 'name' => 'Robert Williams',  'role' => 'COO, Federal Agency',         'initial' => 'R' ],
            ],
            'title_field' => '{{{ name }}}',
        ] );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <section id="testimonials">
            <div class="container">
                <div class="section-header">
                    <div class="section-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
                    <h2 class="section-title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
                </div>
                <div class="testimonials-grid">
                    <?php foreach ( $s['testimonials'] as $t ) : ?>
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
    }
}
