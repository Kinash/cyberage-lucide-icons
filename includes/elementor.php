<?php
/**
 * START OF: Lucide tab in Elementor's icon picker
 * Purpose: Adds a "Lucide" tab to Elementor's icon picker, so any widget with an icon control
 *          (Icon, Icon Box, Icon List, Button, Social Icons, and others) can use Lucide icons.
 * Context: Elementor builds the picker from the 'elementor/icons_manager/additional_tabs' filter,
 *          in the editor and when it renders icons on the front end.
 * Effect: Icons render on the page as inline <svg> through the tab's render_callback (no icon
 *         font, no front-end CSS). In the editor, the picker and live preview draw each icon
 *         with a CSS mask from assets/editor.css.
 * Dependencies: Elementor (free) 4.x; the render_callback key of an icon tab, proved on
 *               Elementor 4.3.4 only (spike S1, 16 Oct 2026; Gate B, 23 Oct 2026). Test on any
 *               other Elementor version before relying on it. CyberAge_Lucide_Library;
 *               assets/picker.json and assets/editor.css from tools/build-icons.mjs.
 * Inputs: Elementor's icon value, e.g. array( 'library' => 'lucide', 'value' => 'lucide lucide-flask-conical' ).
 * Outputs: Inline SVG on the front end; picker data and preview CSS in the editor.
 * Safety: An unknown or removed icon falls back to Elementor's own <i> markup, so the page still
 *         renders. editor.css (about 0.9 MB) is loaded only in the editor and its preview, never
 *         for site visitors.
 * Developer note: The tab id "lucide" and the "lucide lucide-<name>" value format are stored in
 *                 every page that uses an icon. Changing either breaks existing pages. After any
 *                 change to the render output, clear Elementor's element cache (Elementor >
 *                 Tools > Clear Files & Data) or old markup keeps being served.
 * Author: Benson Matheka
 * Email: benson.matheka@outlook.com
 * Phone: +254728121374
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'elementor/icons_manager/additional_tabs', 'cyberage_lucide_register_tab' );

/**
 * Register the Lucide tab.
 *
 * Inputs: $tabs, Elementor's existing icon tabs.
 * Outputs: $tabs with 'lucide' added.
 * Developer note: 'enqueue' stays empty on purpose; a URL there would load the editor CSS for
 *                 every visitor of every page that shows a Lucide icon.
 */
function cyberage_lucide_register_tab( $tabs ) {
	$tabs['lucide'] = array(
		'name'            => 'lucide',
		'label'           => __( 'Lucide', 'cyberage-lucide-icons' ),
		'url'             => CYBERAGE_LUCIDE_URL . 'assets/editor.css',
		'enqueue'         => array(),
		'prefix'          => 'lucide-',
		'displayPrefix'   => 'lucide',
		'labelIcon'       => 'lucide lucide-shapes',
		'ver'             => CYBERAGE_LUCIDE_VERSION,
		'fetchJson'       => CYBERAGE_LUCIDE_URL . 'assets/picker.json',
		'native'          => false,
		'render_callback' => 'cyberage_lucide_render_icon',
	);
	return $tabs;
}

/**
 * Render one Lucide icon for Elementor (the tab's render_callback).
 *
 * Inputs: $icon (library + value), $attributes (classes and aria attributes from the widget),
 *         $tag (the fallback tag Elementor would use).
 * Outputs: Inline SVG markup, or Elementor-style <i> markup when the icon is unknown.
 * Safety: Fallback class and tag are escaped; the tag is limited to i or span.
 * Developer note: 'e-font-icon-svg' is added so Elementor's own icon sizing rules
 *                 (font-size => width/height of the svg) apply to Lucide icons too.
 */
function cyberage_lucide_render_icon( $icon, $attributes = array(), $tag = 'i' ) {
	$value      = isset( $icon['value'] ) ? (string) $icon['value'] : '';
	$attributes = is_array( $attributes ) ? $attributes : array();
	$class      = isset( $attributes['class'] ) ? $attributes['class'] : '';
	$attributes['class'] = trim( 'e-font-icon-svg ' . ( is_array( $class ) ? implode( ' ', $class ) : (string) $class ) );

	$svg = CyberAge_Lucide_Library::svg( $value, $attributes );
	if ( '' !== $svg ) {
		return $svg;
	}
	$tag = in_array( $tag, array( 'i', 'span' ), true ) ? $tag : 'i';
	return '<' . $tag . ' class="' . esc_attr( $value ) . '" aria-hidden="true"></' . $tag . '>';
}

/**
 * Load the preview CSS inside the editor's preview frame, where Elementor redraws widgets with
 * JavaScript (class names, not our PHP output) while the user edits.
 *
 * Context: 'elementor/preview/enqueue_styles' fires only in the editor preview iframe.
 */
add_action(
	'elementor/preview/enqueue_styles',
	static function () {
		wp_enqueue_style( 'cyberage-lucide-editor', CYBERAGE_LUCIDE_URL . 'assets/editor.css', array(), CYBERAGE_LUCIDE_VERSION );
	}
);

/**
 * Keep the editor CSS away from site visitors.
 *
 * Context: Elementor enqueues an icon tab's 'url' stylesheet (handle 'elementor-icons-lucide')
 *          on every page that uses one of its icons, in wp_head and again in the footer
 *          (Elementor 4.3.4, includes/frontend.php enqueue_icon_fonts()). Found in Gate B,
 *          23 Oct 2026: the ~0.9 MB preview CSS reached the public page.
 * Effect: On public pages the stylesheet tag is removed; visitors' icons are inline SVG and
 *         need no CSS. In wp-admin (the editor) and the editor preview frame it is kept.
 * Safety: Only this one handle is touched; every other stylesheet passes through unchanged.
 */
add_filter(
	'style_loader_tag',
	static function ( $tag, $handle ) {
		if ( 'elementor-icons-lucide' !== $handle || is_admin() ) {
			return $tag;
		}
		$elementor = class_exists( '\Elementor\Plugin' ) ? \Elementor\Plugin::$instance : null;
		if ( $elementor && isset( $elementor->preview ) && $elementor->preview->is_preview_mode() ) {
			return $tag;
		}
		return '';
	},
	10,
	2
);

/**
 * END OF: Lucide tab in Elementor's icon picker
 */
