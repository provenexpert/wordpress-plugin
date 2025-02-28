/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * Add individual dependencies.
 */
import {
  PanelBody,
  RangeControl,
  ToggleControl,
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import {
  InspectorControls,
  PanelColorSettings,
  useBlockProps
} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
const { useEffect } = wp.element;

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param object
 * @return {WPElement} Element to render.
 */
export default function Edit( object ) {

	// secure id of this block
	useEffect(() => {
		object.setAttributes({blockId: object.clientId});
	});

  /**
   * Set the banner color value.
   *
   * @param newValue
   * @param object
   */
  const onChangeBannerColor = ( newValue, object ) => {
    object.setAttributes( { bannercolor: newValue } );
  }

  /**
   * Set the text color value.
   *
   * @param newValue
   * @param object
   */
  const onChangeTextColor = ( newValue, object ) => {
    object.setAttributes( { textcolor: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeShowBackPage = ( newValue, object ) => {
    object.setAttributes( { showbackpage: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeShowReviews = ( newValue, object ) => {
    object.setAttributes( { showreviews: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeHideDate = ( newValue, object ) => {
    object.setAttributes( { hidedate: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeHideName = ( newValue, object ) => {
    object.setAttributes( { hidename: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeGoogleStars = ( newValue, object ) => {
    object.setAttributes( { googlestars: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeDisplayReviewerLastName = ( newValue, object ) => {
    object.setAttributes( { displayreviewerlastname: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeBottom = ( newValue, object ) => {
    object.setAttributes( { bottom: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeStickyToSide = ( newValue, object ) => {
    object.setAttributes( { stickytoside: newValue } );
  }

  /**
   * Set the value.
   *
   * @param newValue
   * @param object
   */
  const onChangeZIndex = ( newValue, object ) => {
    object.setAttributes( { zindex: newValue } );
  }

	/**
	 * Collect return for the edit-function
	 */
	return (
    <div {...useBlockProps()}>
      <InspectorControls>
        <PanelColorSettings
          title={__( 'Colors', 'provenexpert' )}
          colorSettings={ [
            {
              label: __( 'Banner color', 'provenexpert' ),
              value: object.attributes.bannercolor,
              onChange: ( value ) => onChangeBannerColor( value, object ),
            },
            {
              label: __( 'Text color', 'provenexpert' ),
              value: object.attributes.textcolor,
              onChange: ( value ) => onChangeTextColor( value, object ),
            }
          ] }
        />
        <PanelBody initialOpen={true} title={ __( 'Settings', 'provenexpert' ) }>
          <ToggleControl
            label={__('Show back page', 'provenexpert')}
            checked={ object.attributes.showbackpage }
            onChange={ value => onChangeShowBackPage( value, object ) }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
          <ToggleControl
            label={__('Show reviews', 'provenexpert')}
            checked={ object.attributes.showreviews }
            onChange={ value => onChangeShowReviews( value, object ) }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
          <ToggleControl
            label={__('Hide date', 'provenexpert')}
            checked={ object.attributes.hidedate }
            onChange={ value => onChangeHideDate( value, object ) }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
          <ToggleControl
            label={__('Hide name', 'provenexpert')}
            checked={ object.attributes.hidename }
            onChange={ value => onChangeHideName( value, object ) }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
          <ToggleControl
            label={__('Google Stars', 'provenexpert')}
            checked={ object.attributes.googlestars }
            onChange={ value => onChangeGoogleStars( value, object ) }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
          <ToggleControl
            label={__('Display reviewer last name', 'provenexpert')}
            checked={ object.attributes.displayreviewerlastname }
            onChange={ value => onChangeDisplayReviewerLastName( value, object ) }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
          <RangeControl
            __nextHasNoMarginBottom
            label={ __( 'Bottom', 'provenexpert' ) }
            value={ object.attributes.bottom }
            onChange={ ( value ) => onChangeBottom( value, object ) }
            min={ 10 }
            max={ 300 }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
        </PanelBody>
        <ToggleGroupControl label={ __( 'Sticky to side', 'provenexpert' ) } isBlock onChange={ value => onChangeStickyToSide( value, object ) } value={ object.attributes.stickytoside } disabled={ ! window.provenexpert_config.enable_fields }>
          <ToggleGroupControlOption value="left" label={ __( 'Left', 'provenexpert' ) } />
          <ToggleGroupControlOption value="right" label={ __( 'Right', 'provenexpert' ) } />
        </ToggleGroupControl>
        <RangeControl
          __nextHasNoMarginBottom
          label={ __( 'Z-Index', 'provenexpert' ) }
          value={ object.attributes.zindex }
          onChange={ ( value ) => onChangeZIndex( value, object ) }
          min={ 0 }
          max={ 10000 }
          disabled={ ! window.provenexpert_config.enable_fields }
        />
      </InspectorControls>
      <div className="provenexpert-hint"><p>{ __( 'This widget is only clearly visible in the frontend', 'provenexpert' ) }</p></div>
      <ServerSideRender
        block="provenexpert/proseal"
        attributes={object.attributes}
        httpMethod="POST"
      />
    </div>
  );
}
