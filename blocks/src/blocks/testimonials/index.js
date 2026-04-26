import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import metadata from '../../../../wordpress/dawn-simmons/inc/blocks/testimonials/block.json';

registerBlockType( metadata.name, {
    ...metadata,

    edit( { attributes, setAttributes } ) {
        const { eyebrow, title, testimonials } = attributes;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'Header', 'dawn-simmons' ) }>
                        <TextControl label={ __( 'Eyebrow', 'dawn-simmons' ) } value={ eyebrow } onChange={ v => setAttributes( { eyebrow: v } ) } />
                        <TextareaControl label={ __( 'Title (HTML ok)', 'dawn-simmons' ) } value={ title } onChange={ v => setAttributes( { title: v } ) } />
                    </PanelBody>
                    <PanelBody title={ __( 'Testimonials', 'dawn-simmons' ) } initialOpen={ false }>
                        { testimonials.map( ( t, i ) => (
                            <div key={ i } style={ { borderBottom: '1px solid #eee', paddingBottom: 12, marginBottom: 12 } }>
                                <TextareaControl
                                    label={ `${ __( 'Quote', 'dawn-simmons' ) } ${ i + 1 }` }
                                    value={ t.text }
                                    onChange={ v => {
                                        const next = [ ...testimonials ];
                                        next[ i ] = { ...next[ i ], text: v };
                                        setAttributes( { testimonials: next } );
                                    } }
                                />
                                <TextControl
                                    label={ __( 'Name', 'dawn-simmons' ) }
                                    value={ t.name }
                                    onChange={ v => {
                                        const next = [ ...testimonials ];
                                        next[ i ] = { ...next[ i ], name: v };
                                        setAttributes( { testimonials: next } );
                                    } }
                                />
                                <TextControl
                                    label={ __( 'Role', 'dawn-simmons' ) }
                                    value={ t.role }
                                    onChange={ v => {
                                        const next = [ ...testimonials ];
                                        next[ i ] = { ...next[ i ], role: v };
                                        setAttributes( { testimonials: next } );
                                    } }
                                />
                            </div>
                        ) ) }
                    </PanelBody>
                </InspectorControls>

                <div style={ { background: '#111827', padding: '40px 32px', borderRadius: 8, border: '1px dashed #2a2d3e' } }>
                    <p style={ { color: '#00b4d8', fontSize: 11, letterSpacing: '0.12em', textTransform: 'uppercase', marginBottom: 8 } }>{ eyebrow }</p>
                    <h2 style={ { fontFamily: 'Georgia, serif', fontSize: 28, color: '#ebecf0', marginBottom: 24 } } dangerouslySetInnerHTML={ { __html: title } } />
                    <div style={ { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 16 } }>
                        { testimonials.map( ( t, i ) => (
                            <div key={ i } style={ { background: '#1a1d2e', border: '1px solid #2a2d3e', borderRadius: 8, padding: 20 } }>
                                <div style={ { fontSize: 28, color: '#00b4d8', fontFamily: 'Georgia, serif', lineHeight: 1 } }>"</div>
                                <p style={ { fontSize: 13, color: '#9fa3b8', margin: '8px 0 16px' } }>{ t.text }</p>
                                <div style={ { fontWeight: 600, color: '#ebecf0', fontSize: 14 } }>{ t.name }</div>
                                <div style={ { fontSize: 12, color: '#6b7190' } }>{ t.role }</div>
                            </div>
                        ) ) }
                    </div>
                </div>
            </>
        );
    },

    save: () => null,
} );
