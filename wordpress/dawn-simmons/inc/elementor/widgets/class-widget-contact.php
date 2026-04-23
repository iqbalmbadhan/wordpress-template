<?php
defined( 'ABSPATH' ) || exit;

class DS_Widget_Contact extends \Elementor\Widget_Base {

    public function get_name(): string    { return 'ds-contact'; }
    public function get_title(): string   { return __( 'DS Contact Section', 'dawn-simmons' ); }
    public function get_icon(): string    { return 'eicon-envelope'; }
    public function get_categories(): array { return [ 'dawn-simmons' ]; }

    protected function register_controls(): void {
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'dawn-simmons' ),
        ] );
        $this->add_control( 'eyebrow',      [ 'label' => __( 'Eyebrow', 'dawn-simmons' ),    'type' => \Elementor\Controls_Manager::TEXT,    'default' => "Let's Work Together" ] );
        $this->add_control( 'title',        [ 'label' => __( 'Title', 'dawn-simmons' ),      'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Start Your <em>Transformation</em>' ] );
        $this->add_control( 'sub',          [ 'label' => __( 'Subtitle', 'dawn-simmons' ),   'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => "Ready to modernise your IT operations? Let's discuss your ServiceNow roadmap and transformation goals." ] );
        $this->add_control( 'email',        [ 'label' => __( 'Email', 'dawn-simmons' ),      'type' => \Elementor\Controls_Manager::TEXT,    'default' => 'dawn@dawnsimmons.com' ] );
        $this->add_control( 'location',     [ 'label' => __( 'Location', 'dawn-simmons' ),   'type' => \Elementor\Controls_Manager::TEXT,    'default' => 'Remote — Available Worldwide' ] );
        $this->add_control( 'response_time',[ 'label' => __( 'Response Time', 'dawn-simmons' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Within 24 hours' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'section_form', [
            'label' => __( 'Form', 'dawn-simmons' ),
        ] );
        $this->add_control( 'cf7_id', [
            'label'       => __( 'Contact Form 7 ID', 'dawn-simmons' ),
            'description' => __( 'Leave blank to use the built-in form.', 'dawn-simmons' ),
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'default'     => '',
        ] );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <section id="contact">
            <div class="container">
                <div class="section-header">
                    <div class="section-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
                    <h2 class="section-title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
                    <p class="section-sub"><?php echo esc_html( $s['sub'] ); ?></p>
                </div>
                <div class="contact-grid">
                    <div>
                        <div class="contact-item">
                            <div class="contact-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <div class="contact-label"><?php esc_html_e( 'Email', 'dawn-simmons' ); ?></div>
                                <div class="contact-val"><a href="mailto:<?php echo esc_attr( $s['email'] ); ?>"><?php echo esc_html( $s['email'] ); ?></a></div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <div class="contact-label"><?php esc_html_e( 'Location', 'dawn-simmons' ); ?></div>
                                <div class="contact-val"><?php echo esc_html( $s['location'] ); ?></div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="contact-label"><?php esc_html_e( 'Response Time', 'dawn-simmons' ); ?></div>
                                <div class="contact-val"><?php echo esc_html( $s['response_time'] ); ?></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <?php
                        if ( ! empty( $s['cf7_id'] ) && function_exists( 'wpcf7_contact_form_tag_func' ) ) {
                            echo do_shortcode( '[contact-form-7 id="' . esc_attr( $s['cf7_id'] ) . '"]' );
                        } else {
                            $this->render_fallback_form( $s['email'] );
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }

    private function render_fallback_form( string $to_email ): void {
        ?>
        <form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'ds_contact', 'ds_contact_nonce' ); ?>
            <input type="hidden" name="action" value="ds_contact_submit">
            <div class="form-row">
                <div class="form-group">
                    <label for="ds-name"><?php esc_html_e( 'Name', 'dawn-simmons' ); ?></label>
                    <input type="text" id="ds-name" name="ds_name" required placeholder="<?php esc_attr_e( 'Jane Smith', 'dawn-simmons' ); ?>">
                </div>
                <div class="form-group">
                    <label for="ds-email"><?php esc_html_e( 'Email', 'dawn-simmons' ); ?></label>
                    <input type="email" id="ds-email" name="ds_email" required placeholder="<?php esc_attr_e( 'jane@company.com', 'dawn-simmons' ); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="ds-subject"><?php esc_html_e( 'Subject', 'dawn-simmons' ); ?></label>
                <input type="text" id="ds-subject" name="ds_subject" placeholder="<?php esc_attr_e( 'ServiceNow Consulting Enquiry', 'dawn-simmons' ); ?>">
            </div>
            <div class="form-group">
                <label for="ds-message"><?php esc_html_e( 'Message', 'dawn-simmons' ); ?></label>
                <textarea id="ds-message" name="ds_message" rows="5" required placeholder="<?php esc_attr_e( 'Tell me about your project…', 'dawn-simmons' ); ?>"></textarea>
            </div>
            <button type="submit" class="btn-primary form-submit"><?php esc_html_e( 'Send Message', 'dawn-simmons' ); ?></button>
            <div class="form-success" id="formSuccess" role="alert" style="display:none">
                <?php esc_html_e( '✓ Message sent! I\'ll be in touch shortly.', 'dawn-simmons' ); ?>
            </div>
        </form>
        <?php
    }
}
