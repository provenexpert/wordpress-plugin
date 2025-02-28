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
  useBlockProps
} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import {
  onChangeWidth,
  onChangeFixed,
  onChangeOrigin,
  onChangePosition,
  onChangeSide
} from "../../components";
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
	 * Collect return for the edit-function
	 */
	return (
    <div {...useBlockProps()}>
      <InspectorControls>
        <PanelBody initialOpen={true} title={ __( 'Settings', 'provenexpert' ) }>
          <RangeControl
            __nextHasNoMarginBottom
            label={ __( 'Width', 'provenexpert' ) }
            value={ object.attributes.width }
            onChange={ ( value ) => onChangeWidth( value, object ) }
            min={ 60 }
            max={ 300 }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
          <ToggleControl
            label={__('Dock on browser margin', 'provenexpert')}
            checked={ object.attributes.fixed }
            onChange={ value => onChangeFixed( value, object ) }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
          {object.attributes.fixed && <div>
              <ToggleGroupControl label={ __( 'Distance measured from top or bottom browser margin?', 'provenexpert' ) } isBlock onChange={ value => onChangeOrigin( value, object ) } value={ object.attributes.origin } disabled={ ! window.provenexpert_config.enable_fields }>
                <ToggleGroupControlOption value="top" label={ __( 'Top', 'provenexpert' ) } />
                <ToggleGroupControlOption value="bottom" label={ __( 'Bottom', 'provenexpert' ) } />
              </ToggleGroupControl>
              <RangeControl
                label={ __( 'Position', 'provenexpert' ) }
                value={ object.attributes.position }
                onChange={ ( value ) => onChangePosition( value, object ) }
                min={ 0 }
                max={ 1200 }
                disabled={ ! window.provenexpert_config.enable_fields }
              />
              <ToggleGroupControl label={ __( 'Browser side on which ratings seal is docked', 'provenexpert' ) } isBlock onChange={ value => onChangeSide( value, object ) } value={ object.attributes.side } disabled={ ! window.provenexpert_config.enable_fields }>
                <ToggleGroupControlOption value="left" label={ __( 'Left', 'provenexpert' ) } />
                <ToggleGroupControlOption value="right" label={ __( 'Right', 'provenexpert' ) } />
              </ToggleGroupControl>
            </div>
          }
        </PanelBody>
      </InspectorControls>
      {object.attributes.fixed && <div className="provenexpert-hint"><p>{ __( 'Fixed positioned widget is only clearly visible in the frontend', 'provenexpert' ) }</p></div>}
      <ServerSideRender
        block="provenexpert/circle"
        attributes={object.attributes}
        httpMethod="POST"
      />
    </div>
  );
}
