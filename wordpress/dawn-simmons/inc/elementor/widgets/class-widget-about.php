<?php
defined( 'ABSPATH' ) || exit;

class DS_Widget_About extends \Elementor\Widget_Base {

    public function get_name(): string    { return 'ds-about'; }
    public function get_title(): string   { return __( 'DS About Section', 'dawn-simmons' ); }
    public function get_icon(): string    { return 'eicon-person'; }
    public function get_categories(): array { return [ 'dawn-simmons' ]; }

    protected function register_controls(): void {
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'dawn-simmons' ),
        ] );
        $this->add_control( 'eyebrow', [ 'label' => __( 'Eyebrow', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'About Me' ] );
        $this->add_control( 'title',   [ 'label' => __( 'Title', 'dawn-simmons' ),   'type' => \Elementor\Controls_Manager::TEXTAREA,  'default' => 'Bridging Technology &amp; <em>Human Impact</em>' ] );
        $this->add_control( 'bio_1',   [ 'label' => __( 'Bio Paragraph 1', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'With over 12 years in enterprise IT and digital transformation, I\'ve helped organisations across healthcare, finance, and government modernise their service management platforms.' ] );
        $this->add_control( 'bio_2',   [ 'label' => __( 'Bio Paragraph 2', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'My approach combines technical depth with strategic vision — ensuring technology investments translate into measurable business outcomes and improved employee experiences.' ] );
        $this->add_control( 'photo',   [ 'label' => __( 'Photo', 'dawn-simmons' ),   'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->end_controls_section();

        /* ── Skills ── */
        $this->start_controls_section( 'section_skills', [
            'label' => __( 'Skills', 'dawn-simmons' ),
        ] );
        $r = new \Elementor\Repeater();
        $r->add_control( 'skill', [ 'label' => __( 'Skill', 'dawn-simmons' ),   'type' => \Elementor\Controls_Manager::TEXT,   'default' => 'ServiceNow Platform' ] );
        $r->add_control( 'pct',   [ 'label' => __( 'Percent', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 95, 'min' => 0, 'max' => 100 ] );
        $this->add_control( 'skills', [
            'label'       => __( 'Skills', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $r->get_controls(),
            'default'     => [
                [ 'skill' => 'ServiceNow Platform', 'pct' => 95 ],
                [ 'skill' => 'AI/ML Integration',   'pct' => 88 ],
                [ 'skill' => 'ITIL & Frameworks',   'pct' => 92 ],
                [ 'skill' => 'Solution Architecture','pct' => 85 ],
            ],
            'title_field' => '{{{ skill }}} — {{{ pct }}}%',
        ] );
        $this->end_controls_section();

        /* ── Details ── */
        $this->start_controls_section( 'section_details', [
            'label' => __( 'Details Grid', 'dawn-simmons' ),
        ] );
        $rd = new \Elementor\Repeater();
        $rd->add_control( 'label', [ 'label' => __( 'Label', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Label' ] );
        $rd->add_control( 'value', [ 'label' => __( 'Value', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Value' ] );
        $this->add_control( 'details', [
            'label'       => __( 'Details', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $rd->get_controls(),
            'default'     => [
                [ 'label' => 'Location',        'value' => 'Remote / USA' ],
                [ 'label' => 'Certifications',  'value' => 'CSA, CAD, CIS-ITSM, PMP' ],
                [ 'label' => 'Availability',    'value' => 'Consulting & Advisory' ],
                [ 'label' => 'Industries',      'value' => 'Healthcare, Finance, Gov' ],
            ],
            'title_field' => '{{{ label }}}',
        ] );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <section id="about">
            <div class="container">
                <div class="about-grid">
                    <div class="about-photo">
                        <?php if ( ! empty( $s['photo']['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $s['photo']['url'] ); ?>" alt="<?php esc_attr_e( 'About photo', 'dawn-simmons' ); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        <?php else : ?>
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity:.3"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 10-16 0"/></svg>
                            <span><?php esc_html_e( 'Add Photo', 'dawn-simmons' ); ?></span>
                        <?php endif; ?>
                        <div class="about-photo-accent" aria-hidden="true"></div>
                    </div>
                    <div class="about-info">
                        <div class="section-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
                        <h2 class="section-title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
                        <p><?php echo esc_html( $s['bio_1'] ); ?></p>
                        <p><?php echo esc_html( $s['bio_2'] ); ?></p>
                        <?php if ( ! empty( $s['skills'] ) ) : ?>
                        <div class="skills-list">
                            <?php foreach ( $s['skills'] as $skill ) : ?>
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
                        <?php endif; ?>
                        <?php if ( ! empty( $s['details'] ) ) : ?>
                        <div class="about-details">
                            <?php foreach ( $s['details'] as $detail ) : ?>
                            <div class="detail-item">
                                <div class="detail-label"><?php echo esc_html( $detail['label'] ); ?></div>
                                <div class="detail-val"><?php echo esc_html( $detail['value'] ); ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
