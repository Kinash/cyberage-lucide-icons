<?php
/**
 * START OF: [lucide] shortcode
 * Purpose: Lets editors place a Lucide icon anywhere shortcodes run (text, headings, menus),
 *          for example [lucide name="flask-conical" size="20"].
 * Context: Front-end rendering of post content and widgets that process shortcodes.
 * Effect: Replaces the shortcode with an inline <svg>.
 * Dependencies: CyberAge_Lucide_Library.
 * Inputs (attributes):
 *   name          Icon name, required (current or old Lucide name).
 *   size          Width and height in px (default 24, limited to 8–512).
 *   stroke-width  Line thickness (default 2, limited to 0.5–4).
 *   color         Any CSS colour; default inherits the text colour (currentColor).
 *   class         Extra CSS classes.
 *   label         Accessible name. When given, the icon is announced (role="img"); when
 *                 empty, the icon is decorative and hidden from screen readers.
 * Outputs: The SVG string, or '' when the name is missing or unknown (nothing breaks).
 * Safety: Numbers are clamped; class names are sanitised; colour is sanitised as a CSS value;
 *         all attribute values are escaped by the library.
 * Developer note: Prefer Elementor's Icon widgets on pages; the shortcode is for places a
 *                 widget cannot go. When the shortcode sits inside an Elementor widget, its
 *                 output can be held in Elementor's element cache: after changing this function,
 *                 clear it (Elementor > Tools > Clear Files & Data) before checking pages.
 * Author: Benson Matheka
 * Email: benson.matheka@outlook.com
 * Phone: +254728121374
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'lucide', 'cyberage_lucide_shortcode' );

function cyberage_lucide_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'name'         => '',
			'size'         => '24',
			'stroke-width' => '2',
			'color'        => '',
			'class'        => '',
			'label'        => '',
		),
		$atts,
		'lucide'
	);

	$size   = max( 8, min( 512, (int) $atts['size'] ) );
	$stroke = max( 0.5, min( 4, (float) $atts['stroke-width'] ) );
	$attrs  = array(
		'width'        => (string) $size,
		'height'       => (string) $size,
		'stroke-width' => (string) $stroke,
		'class'        => implode( ' ', array_map( 'sanitize_html_class', preg_split( '/\s+/', (string) $atts['class'], -1, PREG_SPLIT_NO_EMPTY ) ) ),
	);

	// A colour is applied through the style attribute, after fill:none, so it colours the stroke
	// only. safecss_filter_attr() drops anything that is not a valid CSS declaration.
	$color = safecss_filter_attr( 'color:' . (string) $atts['color'] );
	$attrs['style'] = 'fill:none' . ( '' !== $color && '' !== trim( (string) $atts['color'] ) ? ';' . $color : '' );

	if ( '' !== trim( (string) $atts['label'] ) ) {
		$attrs['aria-hidden'] = null;
		$attrs['role']        = 'img';
		$attrs['aria-label']  = sanitize_text_field( $atts['label'] );
	}

	return CyberAge_Lucide_Library::svg( $atts['name'], $attrs );
}

/**
 * END OF: [lucide] shortcode
 */
