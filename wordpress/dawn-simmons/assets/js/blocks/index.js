/* Dawn Simmons — Gutenberg block registrations
 *
 * Must run at script-load time (no wp.domReady wrapper) so blocks are
 * registered before Gutenberg initialises its editor.
 *
 * WordPress 6.x sends server-registered block types to the JS registry via
 * wp-blocks inline scripts before this file executes. We therefore
 * unregister any pre-existing copy before re-registering with our edit
 * function — otherwise registerBlockType returns early and the inspector
 * controls are never attached.
 *
 * Attribute definitions are declared here in full (matching block.json)
 * so Gutenberg can parse saved block-comment JSON into props.attributes
 * even when the server-sent schema hasn't been applied yet.
 */
(function () {
    'use strict';

    var el              = wp.element.createElement;
    var Fragment        = wp.element.Fragment;
    var __              = wp.i18n.__;
    var PB              = wp.components.PanelBody;
    var TC              = wp.components.TextControl;
    var TAC             = wp.components.TextareaControl;
    var IC              = wp.blockEditor.InspectorControls;
    var SSR             = wp.serverSideRender;
    var useBlockProps   = wp.blockEditor.useBlockProps;

    /* ─── Safe registration helper ─────────────────────────────────────────── */
    function safeReg( name, settings ) {
        if ( wp.blocks.getBlockType( name ) ) {
            wp.blocks.unregisterBlockType( name );
        }
        wp.blocks.registerBlockType( name, settings );
    }

    /* ─── Control helpers ───────────────────────────────────────────────────── */
    function tc( label, key, props ) {
        return el( TC, {
            label: label,
            value: props.attributes[ key ] !== undefined ? String( props.attributes[ key ] ) : '',
            onChange: function ( v ) { var a = {}; a[ key ] = v; props.setAttributes( a ); }
        } );
    }

    function tac( label, key, props ) {
        return el( TAC, {
            label: label,
            rows: 3,
            value: props.attributes[ key ] !== undefined ? String( props.attributes[ key ] ) : '',
            onChange: function ( v ) { var a = {}; a[ key ] = v; props.setAttributes( a ); }
        } );
    }

    function jsonTac( label, key, props ) {
        var raw = props.attributes[ key ];
        var display = ( Array.isArray( raw ) || ( raw && typeof raw === 'object' ) )
            ? JSON.stringify( raw, null, 2 )
            : ( typeof raw === 'string' ? raw : '' );
        return el( TAC, {
            label: label + ' (JSON array)',
            rows: 6,
            value: display,
            onChange: function ( v ) {
                try {
                    var parsed = JSON.parse( v );
                    var a = {}; a[ key ] = parsed; props.setAttributes( a );
                } catch ( e ) { /* wait for valid JSON */ }
            }
        } );
    }

    /* ─── Edit wrapper ──────────────────────────────────────────────────────── */
    function makeEdit( blockName, controlsFn ) {
        return function ( props ) {
            var blockProps = useBlockProps( { style: { padding: 0 } } );
            return el( Fragment, null,
                el( IC, null,
                    el( PB, { title: __( 'Block Settings', 'dawn-simmons' ), initialOpen: true },
                        controlsFn( props )
                    )
                ),
                el( 'div', blockProps,
                    el( 'div', { style: { pointerEvents: 'none', minHeight: 80 } },
                        el( SSR, { block: blockName, attributes: props.attributes } )
                    )
                )
            );
        };
    }

    /* ═══════════════════════════════════════════════════════════════════════
       HERO
    ═══════════════════════════════════════════════════════════════════════ */
    safeReg( 'dawn-simmons/hero', {
        apiVersion: 3,
        title:      __( 'DS Hero Section', 'dawn-simmons' ),
        category:   'dawn-simmons',
        icon:       'cover-image',
        attributes: {
            eyebrow:          { type: 'string', default: 'ServiceNow Consultant & AI Strategist' },
            heading:          { type: 'string', default: 'Transforming Enterprise IT Through <em>Intelligent Design</em>' },
            subheading:       { type: 'string', default: 'Senior ServiceNow consultant and AI automation specialist with 12+ years driving digital transformation for Fortune 500 enterprises.' },
            btnPrimaryText:   { type: 'string', default: 'View My Work' },
            btnPrimaryUrl:    { type: 'string', default: '#services' },
            btnSecondaryText: { type: 'string', default: "Let's Connect" },
            btnSecondaryUrl:  { type: 'string', default: '#contact' },
            photoUrl:         { type: 'string', default: '' },
            roles:            { type: 'string', default: "ServiceNow Elite Partner Consultant\nAI/ML Integration Specialist\nDigital Transformation Lead" },
            stats: {
                type: 'array',
                default: [
                    { num: 50, suffix: '+', label: 'Enterprise Deployments' },
                    { num: 12, suffix: '+', label: 'Years Experience' },
                    { num: 98, suffix: '%', label: 'Client Satisfaction' }
                ]
            }
        },
        edit: makeEdit( 'dawn-simmons/hero', function ( props ) {
            return [
                tc(  'Eyebrow Text',                  'eyebrow',          props ),
                tac( 'Heading (HTML: <em> for italic)','heading',          props ),
                tac( 'Sub-heading',                   'subheading',       props ),
                tc(  'Primary Button Text',            'btnPrimaryText',   props ),
                tc(  'Primary Button URL',             'btnPrimaryUrl',    props ),
                tc(  'Secondary Button Text',          'btnSecondaryText', props ),
                tc(  'Secondary Button URL',           'btnSecondaryUrl',  props ),
                tac( 'Roles — one per line',           'roles',            props ),
                tc(  'Profile Photo URL',              'photoUrl',         props ),
                jsonTac( 'Stats [{num,suffix,label}]', 'stats',            props )
            ];
        } ),
        save: function () { return null; }
    } );

    /* ═══════════════════════════════════════════════════════════════════════
       AI SECTION
    ═══════════════════════════════════════════════════════════════════════ */
    safeReg( 'dawn-simmons/ai-section', {
        apiVersion: 3,
        title:      __( 'DS AI & Automation Section', 'dawn-simmons' ),
        category:   'dawn-simmons',
        icon:       'admin-generic',
        attributes: {
            eyebrow:   { type: 'string', default: 'AI & Automation' },
            headline:  { type: 'string', default: 'Powering the Future with <em>Intelligent Automation</em>' },
            lead:      { type: 'string', default: 'From AI-powered ITSM to predictive analytics, I design automation frameworks that eliminate toil and unlock strategic capacity.' },
            pills:     { type: 'string', default: "Predictive Analytics\nNatural Language Processing\nProcess Mining\nML Ops\nAI Governance" },
            flowSteps: {
                type: 'array',
                default: [
                    { icon: '🔍', name: 'Discovery & Assessment',  desc: 'Map current processes and pain points' },
                    { icon: '🧠', name: 'AI Model Design',          desc: 'Select and train models for your data' },
                    { icon: '⚙',  name: 'ServiceNow Integration',   desc: 'Deploy via Flow Designer & IntegrationHub' },
                    { icon: '📈', name: 'Continuous Optimisation',  desc: 'Monitor KPIs and retrain models' }
                ]
            },
            cards: {
                type: 'array',
                default: [
                    { icon: '🤖', title: 'AI-Powered ITSM',        desc: 'Intelligent ticket routing and auto-resolution.' },
                    { icon: '📊', title: 'Predictive Analytics',    desc: 'Forecast capacity and predict outages.' },
                    { icon: '🔗', title: 'Process Automation',      desc: 'End-to-end workflow automation.' },
                    { icon: '🛡', title: 'AI Governance Framework', desc: 'Responsible AI with audit trails.' }
                ]
            }
        },
        edit: makeEdit( 'dawn-simmons/ai-section', function ( props ) {
            return [
                tc(     'Eyebrow',                          'eyebrow',   props ),
                tac(    'Headline (HTML allowed)',           'headline',  props ),
                tac(    'Lead Paragraph',                   'lead',      props ),
                tac(    'AI Pills — one per line',          'pills',     props ),
                jsonTac('Flow Steps [{icon,name,desc}]',    'flowSteps', props ),
                jsonTac('Feature Cards [{icon,title,desc}]','cards',     props )
            ];
        } ),
        save: function () { return null; }
    } );

    /* ═══════════════════════════════════════════════════════════════════════
       SERVICES
    ═══════════════════════════════════════════════════════════════════════ */
    safeReg( 'dawn-simmons/services', {
        apiVersion: 3,
        title:      __( 'DS Services Section', 'dawn-simmons' ),
        category:   'dawn-simmons',
        icon:       'portfolio',
        attributes: {
            eyebrow:  { type: 'string', default: 'What I Do' },
            title:    { type: 'string', default: 'End-to-End <em>ServiceNow</em> Excellence' },
            sub:      { type: 'string', default: 'From strategy through delivery, I bring senior-level expertise across every phase of the ServiceNow lifecycle.' },
            services: {
                type: 'array',
                default: [
                    { num: '01', title: 'ServiceNow Implementation', desc: 'Full-cycle ITSM, ITOM, and CSM implementations tailored to your business processes.', tags: 'ITSM, ITOM, CSM' },
                    { num: '02', title: 'AI & Process Automation',   desc: 'Intelligent automation using Flow Designer, IntegrationHub, and custom ML pipelines.', tags: 'AI, ML, Flow Designer' },
                    { num: '03', title: 'Platform Architecture',     desc: 'Instance strategy, multi-instance design, and technical governance for enterprise scale.', tags: 'Architecture, Governance' },
                    { num: '04', title: 'Executive Advisory',        desc: 'Strategic roadmapping and board-level digital transformation counsel.', tags: 'Strategy, Advisory' },
                    { num: '05', title: 'Training & Enablement',     desc: 'Custom training programmes and internal capability building.', tags: 'Training, Enablement' },
                    { num: '06', title: 'Managed Services',          desc: 'Ongoing platform optimisation and continuous improvement retainers.', tags: 'Support, Optimisation' }
                ]
            }
        },
        edit: makeEdit( 'dawn-simmons/services', function ( props ) {
            return [
                tc(     'Eyebrow',                           'eyebrow',  props ),
                tac(    'Title (HTML allowed)',               'title',    props ),
                tac(    'Subtitle',                          'sub',      props ),
                jsonTac('Services [{num,title,desc,tags}]',  'services', props )
            ];
        } ),
        save: function () { return null; }
    } );

    /* ═══════════════════════════════════════════════════════════════════════
       ABOUT
    ═══════════════════════════════════════════════════════════════════════ */
    safeReg( 'dawn-simmons/about', {
        apiVersion: 3,
        title:      __( 'DS About Section', 'dawn-simmons' ),
        category:   'dawn-simmons',
        icon:       'admin-users',
        attributes: {
            eyebrow:  { type: 'string', default: 'About Me' },
            title:    { type: 'string', default: 'Bridging Technology &amp; <em>Human Impact</em>' },
            bio1:     { type: 'string', default: "With over 12 years in enterprise IT and digital transformation, I've helped organisations across healthcare, finance, and government modernise their service management platforms." },
            bio2:     { type: 'string', default: 'My approach combines technical depth with strategic vision — ensuring technology investments translate into measurable business outcomes and improved employee experiences.' },
            photoUrl: { type: 'string', default: '' },
            skills: {
                type: 'array',
                default: [
                    { skill: 'ServiceNow Platform',  pct: 95 },
                    { skill: 'AI/ML Integration',    pct: 88 },
                    { skill: 'ITIL & Frameworks',    pct: 92 },
                    { skill: 'Solution Architecture',pct: 85 }
                ]
            },
            details: {
                type: 'array',
                default: [
                    { label: 'Location',       value: 'Remote / USA' },
                    { label: 'Certifications', value: 'CSA, CAD, CIS-ITSM, PMP' },
                    { label: 'Availability',   value: 'Consulting & Advisory' },
                    { label: 'Industries',     value: 'Healthcare, Finance, Gov' }
                ]
            }
        },
        edit: makeEdit( 'dawn-simmons/about', function ( props ) {
            return [
                tc(     'Eyebrow',                       'eyebrow',  props ),
                tac(    'Title (HTML allowed)',           'title',    props ),
                tac(    'Bio Paragraph 1',               'bio1',     props ),
                tac(    'Bio Paragraph 2',               'bio2',     props ),
                tc(     'Profile Photo URL',             'photoUrl', props ),
                jsonTac('Skills [{skill,pct}]',          'skills',   props ),
                jsonTac('Details [{label,value}]',       'details',  props )
            ];
        } ),
        save: function () { return null; }
    } );

    /* ═══════════════════════════════════════════════════════════════════════
       TESTIMONIALS
    ═══════════════════════════════════════════════════════════════════════ */
    safeReg( 'dawn-simmons/testimonials', {
        apiVersion: 3,
        title:      __( 'DS Testimonials Section', 'dawn-simmons' ),
        category:   'dawn-simmons',
        icon:       'format-quote',
        attributes: {
            eyebrow: { type: 'string', default: 'Client Voices' },
            title:   { type: 'string', default: 'What Leaders <em>Say</em>' },
            testimonials: {
                type: 'array',
                default: [
                    { text: "Dawn's ServiceNow implementation transformed our IT operations. Ticket resolution time dropped 60% in the first quarter.", name: 'Michael Chen',    role: 'CIO, HealthFirst Systems',   initial: 'M' },
                    { text: 'The AI automation framework Dawn architected processes 40,000 requests monthly with 94% auto-resolution. Transformative.',  name: 'Sarah Johnson',   role: 'VP Technology, Apex Capital', initial: 'S' },
                    { text: 'Dawn translates complex technical solutions into clear business value. Our board finally understands IT investment ROI.',    name: 'Robert Williams', role: 'COO, Federal Agency',         initial: 'R' }
                ]
            }
        },
        edit: makeEdit( 'dawn-simmons/testimonials', function ( props ) {
            return [
                tc(     'Eyebrow',                                    'eyebrow',      props ),
                tac(    'Title (HTML allowed)',                        'title',        props ),
                jsonTac('Testimonials [{text,name,role,initial}]',     'testimonials', props )
            ];
        } ),
        save: function () { return null; }
    } );

    /* ═══════════════════════════════════════════════════════════════════════
       CONTACT
    ═══════════════════════════════════════════════════════════════════════ */
    safeReg( 'dawn-simmons/contact', {
        apiVersion: 3,
        title:      __( 'DS Contact Section', 'dawn-simmons' ),
        category:   'dawn-simmons',
        icon:       'email',
        attributes: {
            eyebrow:      { type: 'string',  default: "Let's Work Together" },
            title:        { type: 'string',  default: 'Start Your <em>Transformation</em>' },
            sub:          { type: 'string',  default: "Ready to modernise your IT operations? Let's discuss your ServiceNow roadmap and transformation goals." },
            email:        { type: 'string',  default: 'dawn@dawnsimmons.com' },
            location:     { type: 'string',  default: 'Remote — Available Worldwide' },
            responseTime: { type: 'string',  default: 'Within 24 hours' },
            cf7Id:        { type: 'integer', default: 0 }
        },
        edit: makeEdit( 'dawn-simmons/contact', function ( props ) {
            return [
                tc(  'Eyebrow',                        'eyebrow',      props ),
                tac( 'Title (HTML allowed)',            'title',        props ),
                tac( 'Subtitle',                       'sub',          props ),
                tc(  'Email',                          'email',        props ),
                tc(  'Location',                       'location',     props ),
                tc(  'Response Time',                  'responseTime', props ),
                tc(  'Contact Form 7 ID (0 = native)', 'cf7Id',        props )
            ];
        } ),
        save: function () { return null; }
    } );

}());
