import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import metadata from '../../../../wordpress/dawn-simmons/inc/blocks/ai-section/block.json';

registerBlockType( metadata.name, {
    ...metadata,

    edit( { attributes, setAttributes } ) {
        const { eyebrow, headline, lead, pills } = attributes;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'AI Section Content', 'dawn-simmons' ) }>
                        <TextControl label={ __( 'Eyebrow', 'dawn-simmons' ) } value={ eyebrow } onChange={ v => setAttributes( { eyebrow: v } ) } />
                        <TextareaControl label={ __( 'Headline (HTML ok)', 'dawn-simmons' ) } value={ headline } onChange={ v => setAttributes( { headline: v } ) } />
                        <TextareaControl label={ __( 'Lead Text', 'dawn-simmons' ) } value={ lead } onChange={ v => setAttributes( { lead: v } ) } />
                        <TextareaControl label={ __( 'Capability Pills (one per line)', 'dawn-simmons' ) } value={ pills } onChange={ v => setAttributes( { pills: v } ) } />
                    </PanelBody>
                </InspectorControls>

                <div style={ { background: '#0d0f1a', padding: '40px 32px', borderRadius: 8, border: '1px dashed #2a2d3e' } }>
                    <p style={ { color: '#00b4d8', fontSize: 11, letterSpacing: '0.12em', textTransform: 'uppercase', marginBottom: 8 } }>{ eyebrow }</p>
                    <h2 style={ { fontFamily: 'Georgia, serif', fontSize: 28, color: '#ebecf0', marginBottom: 8 } } dangerouslySetInnerHTML={ { __html: headline } } />
                    <p style={ { color: '#9fa3b8', marginBottom: 16, fontSize: 14 } }>{ lead }</p>
                    <div style={ { display: 'flex', flexWrap: 'wrap', gap: 8 } }>
                        { pills.split( '\n' ).filter( Boolean ).map( ( p, i ) => (
                            <span key={ i } style={ { fontSize: 11, padding: '5px 14px', borderRadius: 20, border: '1px solid rgba(0,180,216,0.3)', color: '#00b4d8' } }>{ p }</span>
                        ) ) }
                    </div>
                    <p style={ { color: '#2a2d3e', fontSize: 12, marginTop: 16 } }>{ __( 'Flow steps and cards are configurable via PHP defaults or block attributes.', 'dawn-simmons' ) }</p>
                </div>
            </>
        );
    },

    save: () => null,
} );
