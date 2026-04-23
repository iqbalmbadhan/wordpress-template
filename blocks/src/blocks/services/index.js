import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import metadata from '../../../../../../../wordpress/dawn-simmons/inc/blocks/services/block.json';

registerBlockType( metadata.name, {
    ...metadata,

    edit( { attributes, setAttributes } ) {
        const { eyebrow, title, sub, services } = attributes;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'Section Header', 'dawn-simmons' ) }>
                        <TextControl label={ __( 'Eyebrow', 'dawn-simmons' ) } value={ eyebrow } onChange={ v => setAttributes( { eyebrow: v } ) } />
                        <TextareaControl label={ __( 'Title (HTML ok)', 'dawn-simmons' ) } value={ title } onChange={ v => setAttributes( { title: v } ) } />
                        <TextareaControl label={ __( 'Subtitle', 'dawn-simmons' ) } value={ sub } onChange={ v => setAttributes( { sub: v } ) } />
                    </PanelBody>
                    <PanelBody title={ __( 'Service Cards', 'dawn-simmons' ) } initialOpen={ false }>
                        { services.map( ( svc, i ) => (
                            <div key={ i } style={ { borderBottom: '1px solid #eee', paddingBottom: 12, marginBottom: 12 } }>
                                <TextControl
                                    label={ `${ __( 'Card', 'dawn-simmons' ) } ${ i + 1 } — ${ __( 'Title', 'dawn-simmons' ) }` }
                                    value={ svc.title }
                                    onChange={ v => {
                                        const next = [ ...services ];
                                        next[ i ] = { ...next[ i ], title: v };
                                        setAttributes( { services: next } );
                                    } }
                                />
                                <TextareaControl
                                    label={ __( 'Description', 'dawn-simmons' ) }
                                    value={ svc.desc }
                                    onChange={ v => {
                                        const next = [ ...services ];
                                        next[ i ] = { ...next[ i ], desc: v };
                                        setAttributes( { services: next } );
                                    } }
                                />
                                <TextControl
                                    label={ __( 'Tags (comma-separated)', 'dawn-simmons' ) }
                                    value={ svc.tags }
                                    onChange={ v => {
                                        const next = [ ...services ];
                                        next[ i ] = { ...next[ i ], tags: v };
                                        setAttributes( { services: next } );
                                    } }
                                />
                            </div>
                        ) ) }
                    </PanelBody>
                </InspectorControls>

                <div style={ { background: '#111827', padding: '40px 32px', borderRadius: 8, border: '1px dashed #2a2d3e' } }>
                    <p style={ { color: '#00b4d8', fontSize: 11, letterSpacing: '0.12em', textTransform: 'uppercase', marginBottom: 8 } }>{ eyebrow }</p>
                    <h2 style={ { fontFamily: 'Georgia, serif', fontSize: 28, color: '#ebecf0', marginBottom: 8 } } dangerouslySetInnerHTML={ { __html: title } } />
                    <p style={ { color: '#9fa3b8', marginBottom: 24, fontSize: 14 } }>{ sub }</p>
                    <div style={ { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 16 } }>
                        { services.map( ( svc, i ) => (
                            <div key={ i } style={ { background: '#1a1d2e', border: '1px solid #2a2d3e', borderRadius: 8, padding: 20 } }>
                                <div style={ { fontSize: 32, color: '#2a2d3e', fontFamily: 'Georgia, serif', marginBottom: 8 } }>{ svc.num }</div>
                                <div style={ { fontWeight: 600, color: '#ebecf0', marginBottom: 6 } }>{ svc.title }</div>
                                <p style={ { fontSize: 13, color: '#9fa3b8' } }>{ svc.desc }</p>
                            </div>
                        ) ) }
                    </div>
                </div>
            </>
        );
    },

    save: () => null,
} );
