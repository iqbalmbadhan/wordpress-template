/* Dawn Simmons — Gutenberg block registrations (pre-built, no npm required)
 * Registers blocks at script load time (no wp.domReady wrapper) so Gutenberg
 * picks them up before the editor initialises its block-type registry.
 * Attributes come from block.json via PHP register_block_type() — NOT re-declared here.
 */
(function () {
    'use strict';

    var el       = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var __       = wp.i18n.__;
    var reg      = wp.blocks.registerBlockType;
    var SSR      = wp.serverSideRender;
    var IC       = wp.blockEditor.InspectorControls;
    var PB       = wp.components.PanelBody;
    var TC       = wp.components.TextControl;
    var TAC      = wp.components.TextareaControl;

    /* ── Helpers ── */
    function tc(label, key, props) {
        return el(TC, {
            label: label,
            value: props.attributes[key] !== undefined ? String(props.attributes[key]) : '',
            onChange: function (v) { var a = {}; a[key] = v; props.setAttributes(a); }
        });
    }
    function tac(label, key, props) {
        return el(TAC, {
            label: label,
            rows: 3,
            value: props.attributes[key] !== undefined ? String(props.attributes[key]) : '',
            onChange: function (v) { var a = {}; a[key] = v; props.setAttributes(a); }
        });
    }
    function jsonTac(label, key, props) {
        var raw = props.attributes[key];
        var display = Array.isArray(raw) || (raw && typeof raw === 'object')
            ? JSON.stringify(raw, null, 2)
            : (typeof raw === 'string' ? raw : '');
        return el(TAC, {
            label: label + ' (JSON array)',
            rows: 6,
            value: display,
            onChange: function (v) {
                try {
                    var parsed = JSON.parse(v);
                    var a = {}; a[key] = parsed; props.setAttributes(a);
                } catch (e) { /* wait for valid JSON */ }
            }
        });
    }

    function previewWrapper(block, props) {
        return el('div', { style: { pointerEvents: 'none', minHeight: 80 } },
            el(SSR, { block: block, attributes: props.attributes })
        );
    }

    function makeEdit(blockName, controlsFn) {
        return function (props) {
            return el(Fragment, null,
                el(IC, null,
                    el(PB, { title: __('Block Settings', 'dawn-simmons'), initialOpen: true },
                        controlsFn(props)
                    )
                ),
                previewWrapper(blockName, props)
            );
        };
    }

    /* ── Hero ── */
    reg('dawn-simmons/hero', {
        edit: makeEdit('dawn-simmons/hero', function (props) {
            return [
                tc('Eyebrow Text', 'eyebrow', props),
                tac('Heading (HTML: use <em> for italic)', 'heading', props),
                tac('Sub-heading', 'subheading', props),
                tc('Primary Button Text', 'btnPrimaryText', props),
                tc('Primary Button URL', 'btnPrimaryUrl', props),
                tc('Secondary Button Text', 'btnSecondaryText', props),
                tc('Secondary Button URL', 'btnSecondaryUrl', props),
                tac('Roles — one per line', 'roles', props),
                tc('Profile Photo URL', 'photoUrl', props),
                jsonTac('Stats', 'stats', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── AI Section ── */
    reg('dawn-simmons/ai-section', {
        edit: makeEdit('dawn-simmons/ai-section', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Headline (HTML allowed)', 'headline', props),
                tac('Lead Paragraph', 'lead', props),
                tac('AI Pills — one per line', 'pills', props),
                jsonTac('Flow Steps [{icon,name,desc}]', 'flowSteps', props),
                jsonTac('Feature Cards [{icon,title,desc}]', 'cards', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── Services ── */
    reg('dawn-simmons/services', {
        edit: makeEdit('dawn-simmons/services', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Title (HTML allowed)', 'title', props),
                tac('Subtitle', 'sub', props),
                jsonTac('Services [{num,title,desc,tags}]', 'services', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── About ── */
    reg('dawn-simmons/about', {
        edit: makeEdit('dawn-simmons/about', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Title (HTML allowed)', 'title', props),
                tac('Bio Paragraph 1', 'bio1', props),
                tac('Bio Paragraph 2', 'bio2', props),
                tc('Profile Photo URL', 'photoUrl', props),
                jsonTac('Skills [{skill,pct}]', 'skills', props),
                jsonTac('Details [{label,value}]', 'details', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── Testimonials ── */
    reg('dawn-simmons/testimonials', {
        edit: makeEdit('dawn-simmons/testimonials', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Title (HTML allowed)', 'title', props),
                jsonTac('Testimonials [{text,name,role,initial}]', 'testimonials', props)
            ];
        }),
        save: function () { return null; }
    });

    /* ── Contact ── */
    reg('dawn-simmons/contact', {
        edit: makeEdit('dawn-simmons/contact', function (props) {
            return [
                tc('Eyebrow', 'eyebrow', props),
                tac('Title (HTML allowed)', 'title', props),
                tac('Subtitle', 'sub', props),
                tc('Email', 'email', props),
                tc('Location', 'location', props),
                tc('Response Time', 'responseTime', props),
                tc('Contact Form 7 ID (0 = native form)', 'cf7Id', props)
            ];
        }),
        save: function () { return null; }
    });

}());
