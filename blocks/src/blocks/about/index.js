import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button } from '@wordpress/components';
import metadata from '../../../../../../../wordpress/dawn-simmons/inc/blocks/about/block.json';

registerBlockType( metadata.name, {
    ...metadata,

    edit( { attributes, setAttributes } ) {
        const { eyebrow, title, bio1, bio2, photoUrl, skills } = attributes;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'About Content', 'dawn-simmons' ) }>
                        <TextControl label={ __( 'Eyebrow', 'dawn-simmons' ) } value={ eyebrow } onChange={ v => setAttributes( { eyebrow: v } ) } />
                        <TextareaControl label={ __( 'Title (HTML ok)', 'dawn-simmons' ) } value={ title } onChange={ v => setAttributes( { title: v } ) } />
                        <TextareaControl label={ __( 'Bio Paragraph 1', 'dawn-simmons' ) } value={ bio1 } onChange={ v => setAttributes( { bio1: v } ) } />
                        <TextareaControl label={ __( 'Bio Paragraph 2', 'dawn-simmons' ) } value={ bio2 } onChange={ v => setAttributes( { bio2: v } ) } />
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={ media => setAttributes( { photoUrl: media.url } ) }
                                allowedTypes={ [ 'image' ] }
                                value={ photoUrl }
                                render={ ( { open } ) => (
                                    <>
                                        { photoUrl && <img src={ photoUrl } alt="" style={ { width: '100%', marginBottom: 8 } } /> }
                                        <Button variant="secondary" onClick={ open }>
                                            { photoUrl ? __( 'Change Photo', 'dawn-simmons' ) : __( 'Upload Photo', 'dawn-simmons' ) }
                                        </Button>
                                    </>
                                ) }
                            />
                        </MediaUploadCheck>
                    </PanelBody>
                    <PanelBody title={ __( 'Skills', 'dawn-simmons' ) } initialOpen={ false }>
                        { skills.map( ( s, i ) => (
                            <div key={ i } style={ { marginBottom: 12 } }>
                                <TextControl
                                    label={ `${ __( 'Skill', 'dawn-simmons' ) } ${ i + 1 }` }
                                    value={ s.skill }
                                    onChange={ v => {
                                        const next = [ ...skills ];
                                        next[ i ] = { ...next[ i ], skill: v };
                                        setAttributes( { skills: next } );
                                    } }
                                />
                                <TextControl
                                    label={ __( 'Percentage', 'dawn-simmons' ) }
                                    type="number"
                                    value={ s.pct }
                                    onChange={ v => {
                                        const next = [ ...skills ];
                                        next[ i ] = { ...next[ i ], pct: parseInt( v, 10 ) };
                                        setAttributes( { skills: next } );
                                    } }
                                />
                            </div>
                        ) ) }
                    </PanelBody>
                </InspectorControls>

                <div style={ { background: '#0d0f1a', padding: '40px 32px', borderRadius: 8, border: '1px dashed #2a2d3e' } }>
                    <p style={ { color: '#00b4d8', fontSize: 11, letterSpacing: '0.12em', textTransform: 'uppercase', marginBottom: 8 } }>{ eyebrow }</p>
                    <h2 style={ { fontFamily: 'Georgia, serif', fontSize: 28, color: '#ebecf0', marginBottom: 12 } } dangerouslySetInnerHTML={ { __html: title } } />
                    <p style={ { color: '#9fa3b8', marginBottom: 8, fontSize: 14 } }>{ bio1 }</p>
                    <p style={ { color: '#9fa3b8', marginBottom: 16, fontSize: 14 } }>{ bio2 }</p>
                    { skills.map( ( s, i ) => (
                        <div key={ i } style={ { marginBottom: 10 } }>
                            <div style={ { display: 'flex', justifyContent: 'space-between', fontSize: 13, color: '#ebecf0', marginBottom: 4 } }>
                                <span>{ s.skill }</span><span style={ { color: '#00b4d8' } }>{ s.pct }%</span>
                            </div>
                            <div style={ { height: 3, background: '#2a2d3e', borderRadius: 2 } }>
                                <div style={ { height: '100%', width: `${ s.pct }%`, background: '#00b4d8', borderRadius: 2 } } />
                            </div>
                        </div>
                    ) ) }
                </div>
            </>
        );
    },

    save: () => null,
} );
