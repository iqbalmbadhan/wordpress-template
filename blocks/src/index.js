/* eslint-disable */
/* Dawn Simmons — Gutenberg block registrations
 *
 * Compiled by wp-scripts (webpack) from blocks/src/index.js.
 * This file is the canonical source — do NOT edit the compiled output at
 * assets/js/blocks/index.js directly; run `npm run build` instead.
 */
(function () {
    'use strict';

    var el              = wp.element.createElement;
    var Fragment        = wp.element.Fragment;
    var __              = wp.i18n.__;
    var PB              = wp.components.PanelBody;
    var TC              = wp.components.TextControl;
    var TAC             = wp.components.TextareaControl;
    var Btn             = wp.components.Button;
    var IC              = wp.blockEditor.InspectorControls;
    var MediaUpload     = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
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

    /* ─── Repeater control — replaces jsonTac for array attributes ──────────── */
    /*
     * fields: [{ key, label, type? }]  (type defaults to 'string'; 'number' coerces)
     * defaultItem: plain object with default values for a new row
     */
    function repeaterControl( label, key, fields, defaultItem, props ) {
        var items = Array.isArray( props.attributes[ key ] ) ? props.attributes[ key ] : [];

        function setItems( next ) {
            var a = {}; a[ key ] = next; props.setAttributes( a );
        }

        function updateField( idx, fieldKey, val ) {
            var copy = items.map( function ( item, i ) {
                if ( i !== idx ) return item;
                var updated = Object.assign( {}, item );
                updated[ fieldKey ] = val;
                return updated;
            } );
            setItems( copy );
        }

        function removeItem( idx ) {
            setItems( items.filter( function ( _, i ) { return i !== idx; } ) );
        }

        function addItem() {
            setItems( items.concat( [ Object.assign( {}, defaultItem ) ] ) );
        }

        var itemEls = items.map( function ( item, idx ) {
            var fieldEls = fields.map( function ( f ) {
                return el( TC, {
                    key: f.key,
                    label: f.label,
                    value: item[ f.key ] !== undefined ? String( item[ f.key ] ) : '',
                    onChange: function ( v ) {
                        updateField( idx, f.key, f.type === 'number' ? parseFloat( v ) || 0 : v );
                    }
                } );
            } );

            return el( 'div', {
                key: idx,
                style: {
                    background: '#f0f0f0', borderRadius: '4px',
                    padding: '10px 12px', marginBottom: '8px'
                }
            },
                el( 'p', { style: { fontSize: '10px', color: '#666', marginBottom: '6px', fontWeight: '600' } },
                    '#' + ( idx + 1 )
                ),
                fieldEls,
                el( Btn, {
                    onClick: function () { removeItem( idx ); },
                    variant: 'link',
                    isDestructive: true,
                    style: { fontSize: '11px', marginTop: '4px', padding: '0' }
                }, __( '✕ Remove', 'dawn-simmons' ) )
            );
        } );

        return el( 'div', { style: { marginBottom: '16px' } },
            el( 'p', {
                style: {
                    fontSize: '11px', fontWeight: '600', textTransform: 'uppercase',
                    letterSpacing: '0.06em', marginBottom: '8px', color: '#1e1e1e'
                }
            }, label ),
            itemEls,
            el( Btn, {
                onClick: addItem,
                variant: 'secondary',
                style: { width: '100%', justifyContent: 'center', marginTop: '4px' }
            }, __( '+ Add Item', 'dawn-simmons' ) )
        );
    }

    /* ─── Testimonial repeater — same as above but with per-item photo upload ─ */
    function testimonialRepeater( props ) {
        var key   = 'testimonials';
        var items = Array.isArray( props.attributes[ key ] ) ? props.attributes[ key ] : [];

        function setItems( next ) {
            var a = {}; a[ key ] = next; props.setAttributes( a );
        }

        function updateField( idx, fieldKey, val ) {
            var copy = items.map( function ( item, i ) {
                if ( i !== idx ) return item;
                var updated = Object.assign( {}, item );
                updated[ fieldKey ] = val;
                return updated;
            } );
            setItems( copy );
        }

        function removeItem( idx ) {
            setItems( items.filter( function ( _, i ) { return i !== idx; } ) );
        }

        function addItem() {
            setItems( items.concat( [ { text: '', name: '', role: '', initial: '', photoUrl: '', photoId: 0 } ] ) );
        }

        var itemEls = items.map( function ( item, idx ) {
            var photoUrl = item.photoUrl || '';
            var photoId  = item.photoId  || 0;

            var photoControl = el( MediaUploadCheck, { key: 'photo-check' },
                el( MediaUpload, {
                    allowedTypes: [ 'image' ],
                    value: photoId,
                    onSelect: function ( media ) {
                        updateField( idx, 'photoUrl', media.url );
                        updateField( idx, 'photoId', media.id );
                    },
                    render: function ( ref ) {
                        return el( 'div', { style: { marginBottom: '8px' } },
                            el( 'p', { style: { fontSize: '10px', color: '#555', marginBottom: '4px' } },
                                __( 'Client Photo', 'dawn-simmons' )
                            ),
                            photoUrl
                                ? el( 'div', {},
                                    el( 'img', {
                                        src: photoUrl,
                                        style: { display: 'block', width: '56px', height: '56px', borderRadius: '50%', objectFit: 'cover', marginBottom: '6px' }
                                    } ),
                                    el( 'div', { style: { display: 'flex', gap: '6px' } },
                                        el( Btn, { onClick: ref.open, variant: 'secondary', size: 'small' }, __( 'Change', 'dawn-simmons' ) ),
                                        el( Btn, {
                                            onClick: function () { updateField( idx, 'photoUrl', '' ); updateField( idx, 'photoId', 0 ); },
                                            variant: 'secondary', size: 'small', isDestructive: true
                                        }, __( 'Remove', 'dawn-simmons' ) )
                                    )
                                )
                                : el( Btn, { onClick: ref.open, variant: 'secondary', size: 'small' },
                                    __( 'Upload Photo', 'dawn-simmons' )
                                )
                        );
                    }
                } )
            );

            return el( 'div', {
                key: idx,
                style: { background: '#f0f0f0', borderRadius: '4px', padding: '10px 12px', marginBottom: '8px' }
            },
                el( 'p', { style: { fontSize: '10px', color: '#666', marginBottom: '6px', fontWeight: '600' } }, '#' + ( idx + 1 ) ),
                photoControl,
                el( TAC, {
                    label: __( 'Quote Text', 'dawn-simmons' ),
                    rows: 3,
                    value: item.text || '',
                    onChange: function ( v ) { updateField( idx, 'text', v ); }
                } ),
                el( TC, {
                    label: __( 'Name', 'dawn-simmons' ),
                    value: item.name || '',
                    onChange: function ( v ) { updateField( idx, 'name', v ); }
                } ),
                el( TC, {
                    label: __( 'Role / Company', 'dawn-simmons' ),
                    value: item.role || '',
                    onChange: function ( v ) { updateField( idx, 'role', v ); }
                } ),
                el( TC, {
                    label: __( 'Initials (fallback)', 'dawn-simmons' ),
                    value: item.initial || '',
                    onChange: function ( v ) { updateField( idx, 'initial', v ); }
                } ),
                el( Btn, {
                    onClick: function () { removeItem( idx ); },
                    variant: 'link', isDestructive: true,
                    style: { fontSize: '11px', marginTop: '4px', padding: '0' }
                }, __( '✕ Remove', 'dawn-simmons' ) )
            );
        } );

        return el( 'div', { style: { marginBottom: '16px' } },
            el( 'p', {
                style: { fontSize: '11px', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.06em', marginBottom: '8px', color: '#1e1e1e' }
            }, __( 'Testimonials', 'dawn-simmons' ) ),
            itemEls,
            el( Btn, {
                onClick: addItem,
                variant: 'secondary',
                style: { width: '100%', justifyContent: 'center', marginTop: '4px' }
            }, __( '+ Add Testimonial', 'dawn-simmons' ) )
        );
    }

    /* ─── Photo upload control (hero / about) ──────────────────────────────── */
    function photoUpload( props ) {
        var photoUrl = props.attributes.photoUrl;
        var photoId  = props.attributes.photoId || 0;

        return el( MediaUploadCheck, {},
            el( MediaUpload, {
                allowedTypes: [ 'image' ],
                value: photoId,
                onSelect: function ( media ) {
                    props.setAttributes( { photoUrl: media.url, photoId: media.id } );
                },
                render: function ( ref ) {
                    return el( 'div', { style: { marginBottom: '16px' } },
                        el( 'p', {
                            style: {
                                fontSize: '11px', fontWeight: '600', marginBottom: '8px',
                                textTransform: 'uppercase', letterSpacing: '0.06em', color: '#1e1e1e'
                            }
                        }, __( 'Profile Photo', 'dawn-simmons' ) ),
                        photoUrl
                            ? el( 'div', {},
                                el( 'img', {
                                    src: photoUrl,
                                    style: { display: 'block', width: '100%', maxHeight: '160px', objectFit: 'cover', borderRadius: '4px', marginBottom: '8px' }
                                } ),
                                el( 'div', { style: { display: 'flex', gap: '8px' } },
                                    el( Btn, { onClick: ref.open, variant: 'secondary', size: 'small' }, __( 'Change', 'dawn-simmons' ) ),
                                    el( Btn, {
                                        onClick: function () { props.setAttributes( { photoUrl: '', photoId: 0 } ); },
                                        variant: 'secondary', size: 'small', isDestructive: true
                                    }, __( 'Remove', 'dawn-simmons' ) )
                                )
                            )
                            : el( Btn, {
                                onClick: ref.open, variant: 'secondary',
                                style: { width: '100%', justifyContent: 'center' }
                            }, __( 'Upload / Select Photo', 'dawn-simmons' ) )
                    );
                }
            } )
        );
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
            photoId:          { type: 'number', default: 0 },
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
                tc(  'Eyebrow Text',                    'eyebrow',          props ),
                tac( 'Heading (HTML: <em> for italic)', 'heading',          props ),
                tac( 'Sub-heading',                     'subheading',       props ),
                tc(  'Primary Button Text',              'btnPrimaryText',   props ),
                tc(  'Primary Button URL',               'btnPrimaryUrl',    props ),
                tc(  'Secondary Button Text',            'btnSecondaryText', props ),
                tc(  'Secondary Button URL',             'btnSecondaryUrl',  props ),
                tac( 'Roles — one per line',             'roles',            props ),
                photoUpload( props ),
                repeaterControl( 'Stats', 'stats',
                    [ { key: 'num', label: 'Number', type: 'number' }, { key: 'suffix', label: 'Suffix (+/%)' }, { key: 'label', label: 'Label' } ],
                    { num: 0, suffix: '+', label: 'Label' },
                    props
                )
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
                tc(  'Eyebrow',             'eyebrow',   props ),
                tac( 'Headline (HTML allowed)', 'headline',  props ),
                tac( 'Lead Paragraph',      'lead',      props ),
                tac( 'AI Pills — one per line', 'pills', props ),
                repeaterControl( 'Flow Steps', 'flowSteps',
                    [ { key: 'icon', label: 'Icon (emoji)' }, { key: 'name', label: 'Step Name' }, { key: 'desc', label: 'Description' } ],
                    { icon: '⚙', name: 'New Step', desc: 'Step description' },
                    props
                ),
                repeaterControl( 'Feature Cards', 'cards',
                    [ { key: 'icon', label: 'Icon (emoji)' }, { key: 'title', label: 'Title' }, { key: 'desc', label: 'Description' } ],
                    { icon: '🔧', title: 'New Feature', desc: 'Feature description.' },
                    props
                )
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
                tc(  'Eyebrow',             'eyebrow',  props ),
                tac( 'Title (HTML allowed)', 'title',    props ),
                tac( 'Subtitle',            'sub',      props ),
                repeaterControl( 'Services', 'services',
                    [
                        { key: 'num',   label: 'Number (01, 02…)' },
                        { key: 'title', label: 'Service Title' },
                        { key: 'desc',  label: 'Description' },
                        { key: 'tags',  label: 'Tags (comma-separated)' }
                    ],
                    { num: '07', title: 'New Service', desc: 'Service description.', tags: '' },
                    props
                )
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
            photoId:  { type: 'number', default: 0 },
            skills: {
                type: 'array',
                default: [
                    { skill: 'ServiceNow Platform',   pct: 95 },
                    { skill: 'AI/ML Integration',     pct: 88 },
                    { skill: 'ITIL & Frameworks',     pct: 92 },
                    { skill: 'Solution Architecture', pct: 85 }
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
                tc(  'Eyebrow',             'eyebrow',  props ),
                tac( 'Title (HTML allowed)', 'title',    props ),
                tac( 'Bio Paragraph 1',     'bio1',     props ),
                tac( 'Bio Paragraph 2',     'bio2',     props ),
                photoUpload( props ),
                repeaterControl( 'Skills', 'skills',
                    [ { key: 'skill', label: 'Skill Name' }, { key: 'pct', label: 'Percentage (0–100)', type: 'number' } ],
                    { skill: 'New Skill', pct: 80 },
                    props
                ),
                repeaterControl( 'Details', 'details',
                    [ { key: 'label', label: 'Label' }, { key: 'value', label: 'Value' } ],
                    { label: 'Label', value: 'Value' },
                    props
                )
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
            title:   { type: 'string', default: 'What Colleagues & Clients Say' },
            testimonials: {
                type: 'array',
                default: [
                    { text: "Dawn's ServiceNow implementation transformed our IT operations. Ticket resolution time dropped 60% in the first quarter.", name: 'Michael Chen',    role: 'CIO, HealthFirst Systems',   initial: 'M', photoUrl: '', photoId: 0 },
                    { text: 'The AI automation framework Dawn architected processes 40,000 requests monthly with 94% auto-resolution. Transformative.',  name: 'Sarah Johnson',   role: 'VP Technology, Apex Capital', initial: 'S', photoUrl: '', photoId: 0 },
                    { text: 'Dawn translates complex technical solutions into clear business value. Our board finally understands IT investment ROI.',    name: 'Robert Williams', role: 'COO, Federal Agency',         initial: 'R', photoUrl: '', photoId: 0 }
                ]
            }
        },
        edit: makeEdit( 'dawn-simmons/testimonials', function ( props ) {
            return [
                tc(  'Eyebrow',             'eyebrow', props ),
                tac( 'Title (HTML allowed)', 'title',   props ),
                testimonialRepeater( props )
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

    /* ═══════════════════════════════════════════════════════════════════════
       BLOG SECTION
    ═══════════════════════════════════════════════════════════════════════ */
    safeReg( 'dawn-simmons/blog-section', {
        apiVersion: 3,
        title:      __( 'DS Blog Section', 'dawn-simmons' ),
        category:   'dawn-simmons',
        icon:       'rss',
        attributes: {
            eyebrow:  { type: 'string', default: 'Latest Insights' },
            title:    { type: 'string', default: 'From the <em>Blog</em>' },
            allLabel: { type: 'string', default: 'All Articles' },
            count:    { type: 'number', default: 3 }
        },
        edit: makeEdit( 'dawn-simmons/blog-section', function ( props ) {
            return [
                tc(  'Eyebrow Text',         'eyebrow',  props ),
                tac( 'Title (HTML allowed)',  'title',    props ),
                tc(  '"All Articles" Label',  'allLabel', props ),
                tc(  'Number of Posts (1–6)', 'count',    props )
            ];
        } ),
        save: function () { return null; }
    } );

}());
