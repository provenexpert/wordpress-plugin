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
  onChangeFeedback,
  onChangeStyle
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
          <ToggleGroupControl label={ __( 'Background color', 'provenexpert' ) } onChange={ value => onChangeStyle( value, object ) } isBlock value={ object.attributes.style } disabled={ ! window.provenexpert_config.enable_fields }>
            <ToggleGroupControlOption value="black" label={ __( 'Black', 'provenexpert' ) } />
            <ToggleGroupControlOption value="white" label={ __( 'White', 'provenexpert' ) } />
          </ToggleGroupControl>
          <ToggleControl
            label={__('Display customer votes', 'provenexpert')}
            checked={ object.attributes.feedback }
            onChange={ value => onChangeFeedback( value, object ) }
            disabled={ ! window.provenexpert_config.enable_fields }
          />
        </PanelBody>
      </InspectorControls>
      <div className="provenexpert-hint"><p>{ __( 'This widget is only clearly visible in the frontend', 'provenexpert' ) }</p></div>
      <ServerSideRender
        block="provenexpert/bar"
        attributes={object.attributes}
        httpMethod="POST"
      />
    </div>
  );
}
