<?php
/**
 * First-run Setup Wizard
 * Handles: plugin installation, editor selection, demo content import.
 */

defined( 'ABSPATH' ) || exit;

class DS_Setup_Wizard {

    public static function init(): void {
        add_action( 'admin_menu',        [ __CLASS__, 'register_page'     ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue'       ] );
        add_action( 'wp_ajax_ds_save_editor_pref',   [ __CLASS__, 'ajax_save_editor'   ] );
        add_action( 'wp_ajax_ds_run_demo_import',    [ __CLASS__, 'ajax_demo_import'   ] );
        add_action( 'wp_ajax_ds_finish_wizard',      [ __CLASS__, 'ajax_finish'        ] );
    }

    public static function register_page(): void {
        add_dashboard_page(
            __( 'Theme Setup', 'dawn-simmons' ),
            __( 'Theme Setup', 'dawn-simmons' ),
            'manage_options',
            'ds-setup-wizard',
            [ __CLASS__, 'render' ]
        );
    }

    public static function enqueue( string $hook ): void {
        // Wizard uses inline styles/scripts — nothing external to enqueue.
        if ( $hook !== 'dashboard_page_ds-setup-wizard' ) {
            return;
        }
    }

    // ── AJAX: save editor preference ─────────────────────────────────────────
    public static function ajax_save_editor(): void {
        check_ajax_referer( 'ds_wizard_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized', 403 );
        }
        $pref = sanitize_key( $_POST['preference'] ?? 'gutenberg' );
        if ( ! in_array( $pref, [ 'gutenberg', 'elementor' ], true ) ) {
            $pref = 'gutenberg';
        }
        update_option( 'ds_editor_preference', $pref );

        // If Elementor selected but not active, flag it
        $elementor_active = is_plugin_active( 'elementor/elementor.php' );
        wp_send_json_success( [
            'preference'      => $pref,
            'elementor_ready' => $elementor_active,
        ] );
    }

    // ── AJAX: run demo import ────────────────────────────────────────────────
    public static function ajax_demo_import(): void {
        check_ajax_referer( 'ds_wizard_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized', 403 );
        }
        $result = DS_Demo_Importer::run();
        wp_send_json_success( $result );
    }

    // ── AJAX: finish wizard ──────────────────────────────────────────────────
    public static function ajax_finish(): void {
        check_ajax_referer( 'ds_wizard_nonce', 'nonce' );
        update_option( 'ds_setup_complete', true );
        wp_send_json_success( [ 'redirect' => admin_url() ] );
    }

    // ── Render wizard page ───────────────────────────────────────────────────
    public static function render(): void {
        $plugins     = DS_Plugin_Checker::all_plugins();
        $editor_pref = get_option( 'ds_editor_preference', '' );
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo( 'charset' ); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php esc_html_e( 'Theme Setup — Dawn Simmons', 'dawn-simmons' ); ?></title>
            <style>
                *{box-sizing:border-box;margin:0;padding:0}
                body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#0a0b10;color:#e8eaf0;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 20px}
                .wizard{width:100%;max-width:760px;background:#12141e;border:1px solid #2a2d3e;border-radius:16px;overflow:hidden;box-shadow:0 40px 80px rgba(0,0,0,.6)}
                .wizard-header{padding:40px 48px 32px;border-bottom:1px solid #2a2d3e}
                .wizard-logo{font-size:24px;font-weight:900;color:#e8eaf0;letter-spacing:-.02em;margin-bottom:8px}
                .wizard-logo span{color:#00c9a7}
                .wizard-subtitle{font-size:14px;color:#6b7280}
                .wizard-steps{display:flex;gap:0;border-bottom:1px solid #2a2d3e}
                .step-tab{flex:1;padding:16px;text-align:center;font-size:12px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#6b7280;border-bottom:2px solid transparent;cursor:pointer;transition:all .2s}
                .step-tab.active{color:#00c9a7;border-bottom-color:#00c9a7}
                .step-tab.done{color:#22c55e;border-bottom-color:#22c55e}
                .wizard-body{padding:40px 48px;min-height:360px}
                .step{display:none}.step.active{display:block}
                .step-title{font-size:22px;font-weight:700;margin-bottom:8px;letter-spacing:-.02em}
                .step-desc{font-size:14px;color:#9ca3af;margin-bottom:32px;line-height:1.6}
                .plugin-list{display:flex;flex-direction:column;gap:12px;margin-bottom:32px}
                .plugin-item{display:flex;align-items:center;justify-content:space-between;background:#1a1d2e;border:1px solid #2a2d3e;border-radius:10px;padding:16px 20px}
                .plugin-info{display:flex;flex-direction:column;gap:4px}
                .plugin-name{font-size:15px;font-weight:600}
                .plugin-desc{font-size:12px;color:#9ca3af}
                .plugin-badge{font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:3px 8px;border-radius:3px;background:rgba(0,201,167,.15);color:#00c9a7;margin-left:8px}
                .plugin-badge.optional{background:rgba(107,114,128,.15);color:#9ca3af}
                .plugin-status{display:flex;align-items:center;gap:8px;font-size:13px}
                .status-active{color:#22c55e}
                .status-inactive{color:#f59e0b}
                .btn{display:inline-flex;align-items:center;gap:8px;padding:11px 24px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .2s;text-decoration:none;font-family:inherit}
                .btn-primary{background:#00c9a7;color:#0a0b10}
                .btn-primary:hover{opacity:.85}
                .btn-outline{background:transparent;color:#e8eaf0;border:1px solid #2a2d3e}
                .btn-outline:hover{border-color:#00c9a7;color:#00c9a7}
                .btn-sm{padding:7px 16px;font-size:12px}
                .btn-install{background:#3b82f6;color:#fff}
                .btn-install:hover{background:#2563eb}
                .editor-cards{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:32px}
                .editor-card{background:#1a1d2e;border:2px solid #2a2d3e;border-radius:12px;padding:28px 24px;cursor:pointer;transition:all .2s;text-align:center}
                .editor-card:hover{border-color:#00c9a7}
                .editor-card.selected{border-color:#00c9a7;background:rgba(0,201,167,.05)}
                .editor-icon{font-size:40px;margin-bottom:16px}
                .editor-name{font-size:17px;font-weight:700;margin-bottom:6px}
                .editor-desc{font-size:13px;color:#9ca3af;line-height:1.5}
                .wizard-footer{padding:24px 48px;border-top:1px solid #2a2d3e;display:flex;justify-content:space-between;align-items:center}
                .progress-dots{display:flex;gap:6px}
                .dot{width:8px;height:8px;border-radius:50%;background:#2a2d3e;transition:background .2s}
                .dot.active{background:#00c9a7}
                .dot.done{background:#22c55e}
                .import-options{display:flex;flex-direction:column;gap:12px;margin-bottom:32px}
                .import-option{display:flex;align-items:flex-start;gap:16px;background:#1a1d2e;border:2px solid #2a2d3e;border-radius:10px;padding:20px;cursor:pointer;transition:all .2s}
                .import-option:hover,.import-option.selected{border-color:#00c9a7}
                .import-option input[type=radio]{margin-top:3px;accent-color:#00c9a7}
                .import-label{font-size:15px;font-weight:600;margin-bottom:4px}
                .import-sublabel{font-size:13px;color:#9ca3af}
                .log{background:#0a0b10;border:1px solid #2a2d3e;border-radius:8px;padding:16px;font-family:monospace;font-size:12px;color:#22c55e;min-height:100px;overflow-y:auto;margin-bottom:24px}
                .spinner{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;margin-right:6px}
                @keyframes spin{to{transform:rotate(360deg)}}
                .success-icon{font-size:60px;text-align:center;margin-bottom:20px}
                .success-title{font-size:26px;font-weight:900;text-align:center;margin-bottom:12px;letter-spacing:-.02em}
                .success-links{display:flex;gap:12px;justify-content:center;margin-top:28px}
            </style>
        </head>
        <body>
        <div class="wizard">

            <!-- Header -->
            <div class="wizard-header">
                <div class="wizard-logo">Dawn<span>.</span> Theme Setup</div>
                <div class="wizard-subtitle"><?php esc_html_e( "Let's get your site configured in 3 quick steps.", 'dawn-simmons' ); ?></div>
            </div>

            <!-- Step tabs -->
            <div class="wizard-steps">
                <div class="step-tab active" data-step="1">1. Plugins</div>
                <div class="step-tab"        data-step="2">2. Editor</div>
                <div class="step-tab"        data-step="3">3. Content</div>
                <div class="step-tab"        data-step="4">4. Done</div>
            </div>

            <!-- Bodies -->
            <div class="wizard-body">

                <!-- Step 1: Plugins -->
                <div class="step active" id="step-1">
                    <div class="step-title"><?php esc_html_e( 'Install Required Plugins', 'dawn-simmons' ); ?></div>
                    <div class="step-desc"><?php esc_html_e( 'The following plugins power key features of this theme. Install and activate them now, or skip and do it later.', 'dawn-simmons' ); ?></div>
                    <div class="plugin-list">
                        <?php foreach ( DS_Plugin_Checker::all_plugins() as $plugin ) :
                            $active    = DS_Plugin_Checker::is_active( $plugin['file'] );
                            $installed = DS_Plugin_Checker::is_installed( $plugin['slug'] );
                        ?>
                        <div class="plugin-item">
                            <div class="plugin-info">
                                <div class="plugin-name">
                                    <?php echo esc_html( $plugin['name'] ); ?>
                                    <span class="plugin-badge <?php echo $plugin['required'] ? '' : 'optional'; ?>">
                                        <?php echo $plugin['required'] ? esc_html__( 'Required', 'dawn-simmons' ) : esc_html__( 'Recommended', 'dawn-simmons' ); ?>
                                    </span>
                                </div>
                                <div class="plugin-desc"><?php echo esc_html( $plugin['desc'] ); ?></div>
                            </div>
                            <div class="plugin-status">
                                <?php if ( $active ) : ?>
                                    <span class="status-active">✓ <?php esc_html_e( 'Active', 'dawn-simmons' ); ?></span>
                                <?php elseif ( $installed ) : ?>
                                    <a href="<?php echo esc_url( DS_Plugin_Checker::get_activate_url( $plugin['file'] ) ); ?>" class="btn btn-sm btn-primary"><?php esc_html_e( 'Activate', 'dawn-simmons' ); ?></a>
                                <?php else : ?>
                                    <a href="<?php echo esc_url( DS_Plugin_Checker::get_install_url( $plugin['slug'] ) ); ?>" class="btn btn-sm btn-install"><?php esc_html_e( 'Install', 'dawn-simmons' ); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Step 2: Editor -->
                <div class="step" id="step-2">
                    <div class="step-title"><?php esc_html_e( 'Choose Your Editor', 'dawn-simmons' ); ?></div>
                    <div class="step-desc"><?php esc_html_e( 'Select your preferred page builder. You can switch later in Appearance → Theme Settings.', 'dawn-simmons' ); ?></div>
                    <div class="editor-cards">
                        <div class="editor-card <?php echo $editor_pref === 'gutenberg' ? 'selected' : ''; ?>" data-editor="gutenberg">
                            <div class="editor-icon">⬡</div>
                            <div class="editor-name">Block Editor</div>
                            <div class="editor-desc">WordPress's native block editor. Fast, built-in, and ideal for content-focused sites. No extra plugins needed.</div>
                        </div>
                        <div class="editor-card <?php echo $editor_pref === 'elementor' ? 'selected' : ''; ?>" data-editor="elementor">
                            <div class="editor-icon">⚡</div>
                            <div class="editor-name">Elementor</div>
                            <div class="editor-desc">Drag-and-drop visual builder. Requires Elementor plugin (free). Maximum design flexibility.</div>
                        </div>
                    </div>
                    <?php if ( ! is_plugin_active( 'elementor/elementor.php' ) ) : ?>
                    <p style="font-size:13px;color:#f59e0b;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);border-radius:6px;padding:10px 14px;">
                        <?php esc_html_e( '⚠ Elementor is not active. If you choose Elementor, install it first (Step 1) or your demo content will use Block Editor.', 'dawn-simmons' ); ?>
                    </p>
                    <?php endif; ?>
                </div>

                <!-- Step 3: Demo Content -->
                <div class="step" id="step-3">
                    <div class="step-title"><?php esc_html_e( 'Import Demo Content', 'dawn-simmons' ); ?></div>
                    <div class="step-desc"><?php esc_html_e( 'Auto-create the homepage, blog, shop pages, menus, and sample content to match the theme demo.', 'dawn-simmons' ); ?></div>
                    <div class="import-options">
                        <label class="import-option selected">
                            <input type="radio" name="import_choice" value="yes" checked>
                            <div>
                                <div class="import-label"><?php esc_html_e( 'Yes — import demo content', 'dawn-simmons' ); ?></div>
                                <div class="import-sublabel"><?php esc_html_e( 'Creates homepage, blog page, shop page, sample posts, navigation menus, and footer widgets.', 'dawn-simmons' ); ?></div>
                            </div>
                        </label>
                        <label class="import-option">
                            <input type="radio" name="import_choice" value="no">
                            <div>
                                <div class="import-label"><?php esc_html_e( 'No — start with a blank site', 'dawn-simmons' ); ?></div>
                                <div class="import-sublabel"><?php esc_html_e( "I'll add my own content. Just set up the theme structure.", 'dawn-simmons' ); ?></div>
                            </div>
                        </label>
                    </div>
                    <div class="log" id="import-log" style="display:none"></div>
                </div>

                <!-- Step 4: Done -->
                <div class="step" id="step-4">
                    <div class="success-icon">🎉</div>
                    <div class="success-title"><?php esc_html_e( "You're all set!", 'dawn-simmons' ); ?></div>
                    <p style="text-align:center;color:#9ca3af;font-size:15px;line-height:1.6"><?php esc_html_e( 'Your Dawn Simmons theme is configured and ready. Visit your site or head to the dashboard to start editing.', 'dawn-simmons' ); ?></p>
                    <div class="success-links">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="btn btn-primary">View Site →</a>
                        <a href="<?php echo esc_url( admin_url() ); ?>" class="btn btn-outline">Go to Dashboard</a>
                    </div>
                </div>

            </div><!-- /.wizard-body -->

            <!-- Footer -->
            <div class="wizard-footer">
                <div class="progress-dots">
                    <div class="dot active" data-for="1"></div>
                    <div class="dot"        data-for="2"></div>
                    <div class="dot"        data-for="3"></div>
                    <div class="dot"        data-for="4"></div>
                </div>
                <div style="display:flex;gap:10px">
                    <button class="btn btn-outline" id="btn-skip" style="display:none">
                        <?php esc_html_e( 'Skip', 'dawn-simmons' ); ?>
                    </button>
                    <button class="btn btn-outline" id="btn-back" style="display:none">
                        ← <?php esc_html_e( 'Back', 'dawn-simmons' ); ?>
                    </button>
                    <button class="btn btn-primary" id="btn-next">
                        <?php esc_html_e( 'Next', 'dawn-simmons' ); ?> →
                    </button>
                </div>
            </div>

        </div><!-- /.wizard -->

        <script>
        (function(){
            const nonce   = <?php echo wp_json_encode( wp_create_nonce( 'ds_wizard_nonce' ) ); ?>;
            const ajaxUrl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
            let currentStep = 1;
            const totalSteps = 4;

            const steps   = document.querySelectorAll('.step');
            const tabs    = document.querySelectorAll('.step-tab');
            const dots    = document.querySelectorAll('.dot');
            const btnNext = document.getElementById('btn-next');
            const btnBack = document.getElementById('btn-back');
            const btnSkip = document.getElementById('btn-skip');

            function goTo(n) {
                currentStep = n;
                steps.forEach((s,i) => s.classList.toggle('active', i === n-1));
                tabs.forEach((t,i) => {
                    t.classList.toggle('active', i === n-1);
                    t.classList.toggle('done',   i < n-1);
                });
                dots.forEach((d,i) => {
                    d.classList.toggle('active', i === n-1);
                    d.classList.toggle('done',   i < n-1);
                });
                btnBack.style.display = n > 1 && n < 4 ? '' : 'none';
                btnSkip.style.display = n < 3 ? '' : 'none';
                if (n === totalSteps) { btnNext.style.display = 'none'; }
                else { btnNext.style.display = ''; }
                btnNext.textContent = n === 3 ? 'Import & Finish →' : 'Next →';
            }

            // Editor card selection
            document.querySelectorAll('.editor-card').forEach(card => {
                card.addEventListener('click', () => {
                    document.querySelectorAll('.editor-card').forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                });
            });

            // Import option selection
            document.querySelectorAll('.import-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    document.querySelectorAll('.import-option').forEach(o => o.classList.remove('selected'));
                    opt.classList.add('selected');
                });
            });

            btnBack.addEventListener('click', () => goTo(currentStep - 1));
            btnSkip.addEventListener('click', () => goTo(currentStep + 1));

            btnNext.addEventListener('click', async () => {
                if (currentStep === 2) {
                    // Save editor preference
                    const sel = document.querySelector('.editor-card.selected');
                    if (!sel) { alert('Please choose an editor.'); return; }
                    const pref = sel.dataset.editor;
                    const fd = new FormData();
                    fd.append('action', 'ds_save_editor_pref');
                    fd.append('nonce', nonce);
                    fd.append('preference', pref);
                    await fetch(ajaxUrl, { method: 'POST', body: fd });
                    goTo(3);
                } else if (currentStep === 3) {
                    // Demo import
                    const choice = document.querySelector('input[name=import_choice]:checked')?.value;
                    if (choice === 'yes') {
                        const log = document.getElementById('import-log');
                        log.style.display = 'block';
                        log.textContent = 'Starting import…\n';
                        btnNext.disabled = true;
                        btnNext.innerHTML = '<span class="spinner"></span> Importing…';
                        const fd = new FormData();
                        fd.append('action', 'ds_run_demo_import');
                        fd.append('nonce', nonce);
                        const res = await fetch(ajaxUrl, { method:'POST', body:fd });
                        const json = await res.json();
                        if (json.success) {
                            json.data.log.forEach(l => { log.textContent += l + '\n'; });
                        }
                    }
                    // Finish
                    const fd2 = new FormData();
                    fd2.append('action', 'ds_finish_wizard');
                    fd2.append('nonce', nonce);
                    await fetch(ajaxUrl, { method:'POST', body:fd2 });
                    goTo(4);
                } else {
                    goTo(currentStep + 1);
                }
            });

            goTo(1);
        })();
        </script>
        </body>
        </html>
        <?php
    }
}
