=== CyberAge Lucide Icons ===
Contributors: cyberagetechnologies
Tags: icons, lucide, elementor, svg
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds the Lucide icon set to Elementor's icon picker and a [lucide] shortcode, rendered as lightweight inline SVG.

== Description ==

* A "Lucide" tab in Elementor's icon picker for every widget with an icon (Icon, Icon Box, Icon List, Button and others).
* Icons render as inline SVG: no icon font, no extra CSS for visitors, colour follows the widget's icon colour.
* Old (renamed) Lucide icon names keep working, so pages built earlier never lose an icon after an update.
* Shortcode: `[lucide name="flask-conical" size="24" stroke-width="2" color="#00779b" label=""]`. Without `label` the icon is decorative and hidden from screen readers.
* Icons are bundled with the plugin (pinned Lucide version); nothing is loaded from a third-party server.

== Installation ==

1. Upload `cyberage-lucide-icons.zip` in Plugins > Add New > Upload Plugin, then activate.
2. In Elementor, choose an icon and open the "Lucide" tab.

Updates arrive through the normal WordPress update notice, from the plugin's GitHub releases.

== Credits ==

Icons: Lucide (https://lucide.dev), ISC licence, see LICENSE-lucide.txt.
Updates: Plugin Update Checker by Jānis Elsts, MIT licence.

== Changelog ==

= 1.0.2 =
* Fix: icon colours set in Icon List (and other widgets that colour icons with `color` on an `<i>`) now reach Lucide icons. Each icon is rendered inside an `<i>` and sized 1em, so it follows the widget's icon size and colour.

= 1.0.1 =
* Icon picker: the Lucide tab icon now lines up with the Font Awesome tabs.

= 1.0.0 =
* First release: Lucide 1.58.0 (1,891 icons, 268 old names kept), Elementor icon tab, [lucide] shortcode, GitHub release updates.
