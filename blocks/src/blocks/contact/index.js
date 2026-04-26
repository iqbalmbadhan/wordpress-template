import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import metadata from '../../../../wordpress/dawn-simmons/inc/blocks/contact/block.json';

registerBlockType( metadata.name, {
    ...metadata,

    edit( { attributes, setAttributes } ) {
        const { eyebrow, title, sub, email, location, responseTime, cf7Id } = attributes;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'Contact Content', 'dawn-simmons' ) }>
                        <TextControl label={ __( 'Eyebrow', 'dawn-simmons' ) } value={ eyebrow } onChange={ v => setAttributes( { eyebrow: v } ) } />
                        <TextareaControl label={ __( 'Title (HTML ok)', 'dawn-simmons' ) } value={ title } onChange={ v => setAttributes( { title: v } ) } />
                        <TextareaControl label={ __( 'Subtitle', 'dawn-simmons' ) } value={ sub } onChange={ v => setAttributes( { sub: v } ) } />
                        <TextControl label={ __( 'Email', 'dawn-simmons' ) } value={ email } onChange={ v => setAttributes( { email: v } ) } />
                        <TextControl label={ __( 'Location', 'dawn-simmons' ) } value={ location } onChange={ v => setAttributes( { location: v } ) } />
                        <TextControl label={ __( 'Response Time', 'dawn-simmons' ) } value={ responseTime } onChange={ v => setAttributes( { responseTime: v } ) } />
                        <TextControl
                            label={ __( 'Contact Form 7 ID (0 = built-in form)', 'dawn-simmons' ) }
                            type="number"
                            value={ cf7Id }
                            onChange={ v => setAttributes( { cf7Id: parseInt( v, 10 ) } ) }
                        />
                    </PanelBody>
                </InspectorControls>

                <div style={ { background: '#111827', padding: '40px 32px', borderRadius: 8, border: '1px dashed #2a2d3e' } }>
                    <p style={ { color: '#00b4d8', fontSize: 11, letterSpacing: '0.12em', textTransform: 'uppercase', marginBottom: 8 } }>{ eyebrow }</p>
                    <h2 style={ { fontFamily: 'Georgia, serif', fontSize: 28, color: '#ebecf0', marginBottom: 8 } } dangerouslySetInnerHTML={ { __html: title } } />
                    <p style={ { color: '#9fa3b8', marginBottom: 20, fontSize: 14 } }>{ sub }</p>
                    <div style={ { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 24 } }>
                        <div>
                            <p style={ { color: '#9fa3b8', fontSize: 13, marginBottom: 4 } }>📧 { email }</p>
                            <p style={ { color: '#9fa3b8', fontSize: 13, marginBottom: 4 } }>📍 { location }</p>
                            <p style={ { color: '#9fa3b8', fontSize: 13 } }>⏱ { responseTime }</p>
                        </div>
                        <div style={ { background: '#1a1d2e', borderRadius: 8, padding: 20, border: '1px solid #2a2d3e' } }>
                            <p style={ { color: '#6b7190', fontSize: 13 } }>
                                { cf7Id ? `CF7 Form ID: ${ cf7Id }` : __( 'Built-in contact form', 'dawn-simmons' ) }
                            </p>
                        </div>
                    </div>
                </div>
            </>
        );
    },

    save: () => null,
} );
