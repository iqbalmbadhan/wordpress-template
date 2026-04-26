import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import {
    InspectorControls,
    MediaUpload,
    MediaUploadCheck,
} from '@wordpress/block-editor';
import {
    PanelBody,
    TextControl,
    TextareaControl,
    Button,
} from '@wordpress/components';
import metadata from '../../../../wordpress/dawn-simmons/inc/blocks/hero/block.json';

registerBlockType( metadata.name, {
    ...metadata,

    edit( { attributes, setAttributes } ) {
        const { eyebrow, heading, subheading, btnPrimaryText, btnPrimaryUrl,
                btnSecondaryText, btnSecondaryUrl, photoUrl, roles } = attributes;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'Hero Content', 'dawn-simmons' ) }>
                        <TextControl
                            label={ __( 'Eyebrow Text', 'dawn-simmons' ) }
                            value={ eyebrow }
                            onChange={ v => setAttributes( { eyebrow: v } ) }
                        />
                        <TextareaControl
                            label={ __( 'Heading (HTML allowed for <em>)', 'dawn-simmons' ) }
                            value={ heading }
                            onChange={ v => setAttributes( { heading: v } ) }
                        />
                        <TextareaControl
                            label={ __( 'Subheading', 'dawn-simmons' ) }
                            value={ subheading }
                            onChange={ v => setAttributes( { subheading: v } ) }
                        />
                        <TextareaControl
                            label={ __( 'Roles (one per line)', 'dawn-simmons' ) }
                            value={ roles }
                            onChange={ v => setAttributes( { roles: v } ) }
                        />
                        <TextControl
                            label={ __( 'Primary Button Text', 'dawn-simmons' ) }
                            value={ btnPrimaryText }
                            onChange={ v => setAttributes( { btnPrimaryText: v } ) }
                        />
                        <TextControl
                            label={ __( 'Primary Button URL', 'dawn-simmons' ) }
                            value={ btnPrimaryUrl }
                            onChange={ v => setAttributes( { btnPrimaryUrl: v } ) }
                        />
                        <TextControl
                            label={ __( 'Secondary Button Text', 'dawn-simmons' ) }
                            value={ btnSecondaryText }
                            onChange={ v => setAttributes( { btnSecondaryText: v } ) }
                        />
                        <TextControl
                            label={ __( 'Secondary Button URL', 'dawn-simmons' ) }
                            value={ btnSecondaryUrl }
                            onChange={ v => setAttributes( { btnSecondaryUrl: v } ) }
                        />
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={ media => setAttributes( { photoUrl: media.url } ) }
                                allowedTypes={ [ 'image' ] }
                                value={ photoUrl }
                                render={ ( { open } ) => (
                                    <div>
                                        <p style={ { fontSize: 11, textTransform: 'uppercase', marginBottom: 4 } }>
                                            { __( 'Profile Photo', 'dawn-simmons' ) }
                                        </p>
                                        { photoUrl && (
                                            <img src={ photoUrl } alt="" style={ { width: '100%', marginBottom: 8 } } />
                                        ) }
                                        <Button variant="secondary" onClick={ open }>
                                            { photoUrl ? __( 'Change Photo', 'dawn-simmons' ) : __( 'Upload Photo', 'dawn-simmons' ) }
                                        </Button>
                                    </div>
                                ) }
                            />
                        </MediaUploadCheck>
                    </PanelBody>
                </InspectorControls>

                <div style={ { background: '#0d0f1a', padding: '40px 32px', borderRadius: 8, border: '1px dashed #2a2d3e' } }>
                    <p style={ { color: '#00b4d8', fontSize: 11, letterSpacing: '0.12em', textTransform: 'uppercase', marginBottom: 12 } }>
                        { eyebrow }
                    </p>
                    <h2 style={ { fontFamily: 'Georgia, serif', fontSize: 36, fontWeight: 900, color: '#ebecf0', marginBottom: 12 } }
                        dangerouslySetInnerHTML={ { __html: heading } }
                    />
                    <p style={ { color: '#9fa3b8', marginBottom: 20 } }>{ subheading }</p>
                    <div style={ { display: 'flex', gap: 12 } }>
                        <span style={ { background: '#00b4d8', color: '#0d0f1a', padding: '10px 24px', borderRadius: 4, fontSize: 13 } }>{ btnPrimaryText }</span>
                        <span style={ { border: '1px solid #2a2d3e', color: '#ebecf0', padding: '10px 24px', borderRadius: 4, fontSize: 13 } }>{ btnSecondaryText }</span>
                    </div>
                    { photoUrl && <img src={ photoUrl } alt="" style={ { width: 80, height: 80, borderRadius: '50%', marginTop: 16, objectFit: 'cover' } } /> }
                </div>
            </>
        );
    },

    save: () => null,
} );
