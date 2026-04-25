/* Dawn Simmons — Gutenberg block registrations (no build step, vanilla WP globals) */
(function () {
    'use strict';

    var el        = wp.element.createElement;
    var Fragment  = wp.element.Fragment;
    var __        = wp.i18n.__;
    var reg       = wp.blocks.registerBlockType;
    var SSR       = wp.serverSideRender;
    var IC        = wp.blockEditor.InspectorControls;
    var PB        = wp.components.PanelBody;
    var TC        = wp.components.TextControl;
    var TAC       = wp.components.TextareaControl;

    /* helpers */
    function tc(label, key, props) {
        return el(TC, {
            label: label,
            value: props.attributes[key] || '',
            onChange: function (v) { var a = {}; a[key] = v; props.setAttributes(a); }
        });
    }
    function tac(label, key, props) {
        return el(TAC, {
            label: label,
            value: props.attributes[key] || '',
            onChange: function (v) { var a = {}; a[key] = v; props.setAttributes(a); }
        });
    }
    function jsonTac(label, key, props) {
        var raw = props.attributes[key];
        var display = (typeof raw === 'string') ? raw : JSON.stringify(raw, null, 2);
        return el(TAC, {
            label: label + ' (JSON)',
            rows: 6,
            value: display || '',
            onChange: function (v) {
                try { var parsed = JSON.parse(v); var a = {}; a[key] = parsed; props.setAttributes(a); }
                catch (e) { /* keep raw until valid JSON */ }
            }
        });
    }
    function makeEdit(name, ctrlsFn) {
        return function (props) {
            return el(Fragment, null,
                el(IC, null, el(PB, { title: __('Block Settings', 'dawn-simmons'), initialOpen: true }, ctrlsFn(props))),
                el('div', { style: { pointerEvents: 'none' } },
                    el(SSR, { block: name, attributes: props.attributes })
                )
            );
        };
    }

    /* ── Hero ── */
    reg('dawn-simmons/hero', {
        attributes: {
            eyebrow:          { type: 'string',  default: 'ServiceNow Consultant & AI Strategist' },
            heading:          { type: 'string',  default: 'Transforming Enterprise IT Through <em>Intelligent Design</em>' },
            subheading:       { type: 'string',  default: '' },
            btnPrimaryText:   { type: 'string',  default: 'View My Work' },
            btnPrimaryUrl:    { type: 'string',  default: '#services' },
            btnSecondaryText: { type: 'string',  default: "Let's Connect" },
            btnSecondaryUrl:  { type: 'string',  default: '#contact' },
            photoUrl:         { type: 'string',  default: '' },
            roles:            { type: 'string',  default: "ServiceNow Elite Partner Consultant\nAI/ML Integration Specialist\nDigital Transformation Lead" },
            stats:            { type: 'array',   default: [{ num: 50, suffix: '+', label: 'Enterprise Deployments' }, { num: 12, suffix: '+', label: 'Years Experience' }, { num: 98, suffix: '%', label: 'Client Satisfaction' }] }
        },
        edit: makeEdit('dawn-simmons/hero', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Heading (HTML allowed)', 'heading', props),
                tac('Sub-heading', 'subheading', props),
                tc('Primary Button Text', 'btnPrimaryText', props),
                tc('Primary Button URL', 'btnPrimaryUrl', props),
                tc('Secondary Button Text', 'btnSecondaryText', props),
                tc('Secondary Button URL', 'btnSecondaryUrl', props),
                tac('Roles (one per line)', 'roles', props),
                tc('Photo URL', 'photoUrl', props),
                jsonTac('Stats', 'stats', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── AI Section ── */
    reg('dawn-simmons/ai-section', {
        attributes: {
            eyebrow:   { type: 'string', default: 'AI & Automation' },
            headline:  { type: 'string', default: 'Powering the Future with <em>Intelligent Automation</em>' },
            lead:      { type: 'string', default: '' },
            pills:     { type: 'string', default: "Predictive Analytics\nNatural Language Processing\nProcess Mining\nML Ops\nAI Governance" },
            flowSteps: { type: 'array',  default: [{ icon: '🔍', name: 'Discovery & Assessment', desc: 'Map current processes and pain points' }, { icon: '🧠', name: 'AI Model Design', desc: 'Select and train models for your data' }, { icon: '⚙', name: 'ServiceNow Integration', desc: 'Deploy via Flow Designer & IntegrationHub' }, { icon: '📈', name: 'Continuous Optimisation', desc: 'Monitor KPIs and retrain models' }] },
            cards:     { type: 'array',  default: [{ icon: '🤖', title: 'AI-Powered ITSM', desc: 'Intelligent ticket routing.' }, { icon: '📊', title: 'Predictive Analytics', desc: 'Forecast capacity and predict outages.' }, { icon: '🔗', title: 'Process Automation', desc: 'End-to-end workflow automation.' }, { icon: '🛡', title: 'AI Governance', desc: 'Responsible AI with audit trails.' }] }
        },
        edit: makeEdit('dawn-simmons/ai-section', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Headline (HTML allowed)', 'headline', props),
                tac('Lead Paragraph', 'lead', props),
                tac('AI Pills (one per line)', 'pills', props),
                jsonTac('Flow Steps', 'flowSteps', props),
                jsonTac('Feature Cards', 'cards', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── Services ── */
    reg('dawn-simmons/services', {
        attributes: {
            eyebrow:  { type: 'string', default: 'What I Do' },
            title:    { type: 'string', default: 'End-to-End <em>ServiceNow</em> Excellence' },
            sub:      { type: 'string', default: '' },
            services: { type: 'array',  default: [{ num: '01', title: 'ServiceNow Implementation', desc: 'Full-cycle ITSM, ITOM, and CSM implementations.', tags: 'ITSM, ITOM, CSM' }, { num: '02', title: 'AI & Process Automation', desc: 'Intelligent automation using Flow Designer.', tags: 'AI, ML, Flow Designer' }] }
        },
        edit: makeEdit('dawn-simmons/services', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Title (HTML allowed)', 'title', props),
                tac('Subtitle', 'sub', props),
                jsonTac('Services', 'services', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── About ── */
    reg('dawn-simmons/about', {
        attributes: {
            eyebrow:  { type: 'string', default: 'About Me' },
            title:    { type: 'string', default: 'Bridging Technology &amp; <em>Human Impact</em>' },
            bio1:     { type: 'string', default: "With over 12 years in enterprise IT and digital transformation, I've helped organisations across healthcare, finance, and government modernise their service management platforms." },
            bio2:     { type: 'string', default: 'My approach combines technical depth with strategic vision — ensuring technology investments translate into measurable business outcomes.' },
            photoUrl: { type: 'string', default: '' },
            skills:   { type: 'array',  default: [{ skill: 'ServiceNow Platform', pct: 95 }, { skill: 'AI/ML Integration', pct: 88 }, { skill: 'ITIL & Frameworks', pct: 92 }, { skill: 'Solution Architecture', pct: 85 }] },
            details:  { type: 'array',  default: [{ label: 'Location', value: 'Remote / USA' }, { label: 'Certifications', value: 'CSA, CAD, CIS-ITSM, PMP' }] }
        },
        edit: makeEdit('dawn-simmons/about', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Title (HTML allowed)', 'title', props),
                tac('Bio Paragraph 1', 'bio1', props),
                tac('Bio Paragraph 2', 'bio2', props),
                tc('Photo URL', 'photoUrl', props),
                jsonTac('Skills', 'skills', props),
                jsonTac('Detail Items', 'details', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── Testimonials ── */
    reg('dawn-simmons/testimonials', {
        attributes: {
            eyebrow:      { type: 'string', default: 'Client Voices' },
            title:        { type: 'string', default: 'What Leaders <em>Say</em>' },
            testimonials: { type: 'array',  default: [{ text: "Dawn's ServiceNow implementation transformed our IT operations.", name: 'Michael Chen', role: 'CIO, HealthFirst Systems', initial: 'M' }, { text: 'The AI automation framework Dawn architected processes 40,000 requests monthly with 94% auto-resolution.', name: 'Sarah Johnson', role: 'VP Technology, Apex Capital', initial: 'S' }, { text: 'Dawn translates complex technical solutions into clear business value.', name: 'Robert Williams', role: 'COO, Federal Agency', initial: 'R' }] }
        },
        edit: makeEdit('dawn-simmons/testimonials', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Title (HTML allowed)', 'title', props),
                jsonTac('Testimonials', 'testimonials', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── Contact ── */
    reg('dawn-simmons/contact', {
        attributes: {
            eyebrow:      { type: 'string',  default: "Let's Work Together" },
            title:        { type: 'string',  default: 'Start Your <em>Transformation</em>' },
            sub:          { type: 'string',  default: "Ready to modernise your IT operations? Let's discuss your ServiceNow roadmap." },
            email:        { type: 'string',  default: 'dawn@dawnsimmons.com' },
            location:     { type: 'string',  default: 'Remote — Available Worldwide' },
            responseTime: { type: 'string',  default: 'Within 24 hours' },
            cf7Id:        { type: 'integer', default: 0 }
        },
        edit: makeEdit('dawn-simmons/contact', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Title (HTML allowed)', 'title', props),
                tac('Subtitle', 'sub', props),
                tc('Email', 'email', props),
                tc('Location', 'location', props),
                tc('Response Time', 'responseTime', props),
                tc('CF7 Form ID (0 = native form)', 'cf7Id', props)
            ];
        }),
        save: function () { return null; }
    });

}());
