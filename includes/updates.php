<?php
/**
 * START OF: Automatic updates from GitHub releases
 * Purpose: Lets every client site receive new versions of this plugin through the normal
 *          WordPress "update available" notice, without a licence server or access token.
 * Context: Runs on every request; Plugin Update Checker itself only contacts GitHub on its own
 *          schedule (about every 12 hours) and when an admin checks for updates.
 * Effect: When a newer release exists on https://github.com/Kinash/cyberage-lucide-icons,
 *         WordPress shows an update and installs the release's zip asset.
 * Dependencies: vendor/plugin-update-checker (Plugin Update Checker v5.7, MIT, by Jānis
 *               Elsts); a PUBLIC GitHub repository whose releases carry an asset named
 *               cyberage-lucide-icons.zip (built by tools/release.mjs).
 * Inputs: None; the repository URL is fixed below.
 * Outputs: Update data for WordPress's plugin update screen.
 * Safety: The repository is public, so no token is stored on client sites (agreed 30 Sep 2026).
 *         If the library is missing, updates are simply skipped and the plugin keeps working.
 * Developer note: Release flow: bump the version in cyberage-lucide-icons.php (header and
 *                 constant), run tools/release.mjs, then publish a GitHub release tagged
 *                 v<version> with the zip attached. Test each release on a Laragon copy first.
 * Author: Benson Matheka
 * Email: benson.matheka@outlook.com
 * Phone: +254728121374
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cyberage_lucide_puc = CYBERAGE_LUCIDE_DIR . 'vendor/plugin-update-checker/plugin-update-checker.php';
if ( is_readable( $cyberage_lucide_puc ) ) {
	require_once $cyberage_lucide_puc;
	$cyberage_lucide_updates = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/Kinash/cyberage-lucide-icons/',
		CYBERAGE_LUCIDE_FILE,
		'cyberage-lucide-icons'
	);
	// Install the attached release zip (it contains only the plugin files), not GitHub's
	// automatic source archive, which would also contain the tools/ folder.
	$cyberage_lucide_updates->getVcsApi()->enableReleaseAssets( '/cyberage-lucide-icons\.zip$/' );
}
unset( $cyberage_lucide_puc );

/**
 * END OF: Automatic updates from GitHub releases
 */
