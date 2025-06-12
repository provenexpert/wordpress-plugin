## How can I use shortcodes to output widgets in WordPress?

## Requirements

* A ProvenExpert account.
* The free WordPress plugin ProvenExpert: https://wordpress.org/plugins/provenexpert/
* The WordPress plugin must be connected to your ProvenExpert account.

## Basics

Shortcodes are structured as follows in WordPress:

`[shortcode_name attribute="value"]`

whereby all components are variable and different attributes are possible depending on the shortcode.

## ProvenExpert Widgets

The following widgets are available as shortcodes:

* Awards
* Bar
* Circle
* Landing
* ProSeal
* Seal

Details on their shortcode structure and possibilities are given below.

### Awards

Structure: `[provenexpert_awards width="100"]`

Possible attributes:

* width
  * Specifies the width.
  * Numerical value in pixels
  * Default value: 100
* fixed
  * Specifies whether the widget is fixed in position or not.
  * Value 1 or 0
  * Default value: 0
* origin
  * Specifies the direction in which the widget is positioned.
  * Values “top” or “bottom”
  * Default value: “top”
* position
  * Specifies the widget position.
  * Number value
  * Default value: 0
* award_type
  * Specifies the award type to be displayed.
  * Possible values: “recommend”, ‘topservice’ or “toprecommend”
  * Default value: “recommend”

### Bar

Structure: `[provenexpert_bar stye="black"]`

Possible attributes:

* style
  * Specifies the style of the widget.
  * Values “white” or “black”
  * Default value: “white”
* Feedback
  * Specifies whether feedback should also be displayed.
  * Value 1 or 0
  * Default value: 0

### Circle

Structure: `[provenexpert_circle]`

Possible attributes:

* width
  * Specifies the width.
  * Numerical value in pixels
  * Default value: 100
* fixed
  * Specifies whether the widget is fixed in position or not.
  * Value 1 or 0
  * Default value: 0
* origin
  * Specifies the direction in which the widget is positioned.
  * Values “top” or “bottom”
  * Default value: “top”
* position
  * Specifies the widget position.
  * Number value
  * Default value: 0
* side
  * Specifies the side alignment of the widget.
  * Possible values: “left” or “right”
  * Default value: “left”

### Landing

Structure: `[provenexpert_landing stye="black"]`

Possible attributes:

* style
  * Specifies the style of the widget.
  * Values “white” or “black”
  * Default value: “white”
* Feedback
  * Specifies whether feedback should also be displayed.
  * Value 1 or 0
  * Default value: 0
* Avatar
  * Specifies whether the avatar should be displayed.
  * Value 1 or 0
  * Default value: 0

### Landing

Structure: `[provenexpert_proseal]`

Possible attributes:

* bannercolor
  * Specifies the color for the banner.
  * Value must be a valid hex code of a color, e.g. #121212
  * Default value: #000000
* textcolor
  * Specifies the text color.
  * Value must be a valid hex code of a color, e.g. #121212
  * Default value: #ffffff
* showbackpage
  * Specifies whether the back page should be displayed.
  * Value 1 or 0
  * Default value: 0
* showreviews
  * Specifies whether reviews are to be displayed.
  * Value 1 or 0
  * Default value: 0
* hidedate
  * Specifies whether the date should be hidden.
  * Value 1 or 0
  * Default value: 0
* hidename
  * Specifies whether the name should be hidden.
  * Value 1 or 0
  * Default value: 0
* googlestars
  * Specifies whether Google rating stars should also be displayed.
  * Value 1 or 0
  * Default value: 0
* displayreviewerlastname
  * Specifies whether the name of the last reviewer should be displayed.
  * Value 1 or 0
  * Default value: 0
* bottom
  * Specifies the distance from the bottom.
  * Number value
  * Default value: 100
* stickytoside
  * Specifies whether the widget should be displayed on the side.
  * Value 1 or 0
  * Default value: 0
* zindex
  * Specifies the number of levels to avoid overlaps in the output.
  * zindex value
  * Default value: 9999

### Seal

Structure: `[provenexpert_seal width="100"]`

Possible attributes:

* width
  * Specifies the width.
  * Numerical value in pixels
  * Default value: 100
* fixed
  * Specifies whether the widget is positioned fixed or not.
  * Value 1 or 0
  * Default value: 0
* origin
  * Specifies the direction in which the widget is positioned.
  * Values “top” or “bottom”
  * Default value: “top”
* position
  * Specifies the widget position.
  * Number value
  * Default value: 0
* side
  * Specifies the side alignment of the widget.
  * Possible values: “left” or “right”
  * Default value: “left”
* seal_type
  * Specifies the seal type.
  * Values: “portrait”, ‘square’ or “landscape”
  * Default value: “portrait”
* slider
  * Specifies whether the slider should be displayed or not.
  * Values 1 or 0
  * Default value: 0

## Questions?

If you have any questions about shortcodes, please contact the WordPress plugin support forum: https://wordpress.org/support/plugin/provenexpert/
