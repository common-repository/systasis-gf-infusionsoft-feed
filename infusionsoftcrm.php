<?php
/*
 Plugin Name: KeapConnect
 Plugin URI: https://systasis.co
 Description: Sync form submissions between Gravity Forms and Keap.
 Version: 3.0.0
 Author: Systasis Computer Systems, Inc.
 Text Domain: systasisgfifscrm

 ------------------------------------------------------------------------
 Copyright 2017, Systasis Computer Systems, Inc.
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see http://www.gnu.org/licenses.
 */

namespace systasisgfifscrm;

define('SYSTASISGFIFSCRM_VERSION', '3.0.0');
define('SYSTASISGFIFSCRM_DOMAIN', 'systasisgfifscrm');
define('SYSTASISGFIFSCRM_PATH', 'systasis-gf-infusionsoft-feed');
define('SYSTASISGFIFSCRM_FILE', 'infusionsoftcrm.php');
define('SYSTASISGFIFSCRM_SLUG', 'systasisgfifscrm');

class GravityFormsInfusionsoftIntegrator_Bootstrap
{

	/**
	 * If the Feed Add-On Framework exists, InfusionsoftGravityForms_Plugin Add-On is loaded.
	 *
	 * @since  1.0
	 * @access public
	 */
	public static function load()
	{
		if (!method_exists('GFForms', 'include_feed_addon_framework')) {
			return;
		}
		require_once __DIR__ . '/vendor/autoload.php';
		\GFAddOn::register(SYSTASISGFIFSCRM_DOMAIN . '\GravityFormsInfusionsoftIntegrator');
		$is_export_settings = rgget('page') == 'gf_export' && rgget('subview') == 'export_form';
		if ($is_export_settings) {
			$instance = GravityFormsInfusionsoftIntegrator::get_instance();
			add_filter("gform_export_form", array($instance, 'export_feeds_with_form'), 10, 2);
		}
	}
}

// If Gravity Forms is loaded, bootstrap the InfusionsoftGravityForms_Plugin Add-On.
// Read more here: https://docs.gravityforms.com/gform_loaded/
add_action('gform_loaded', array(SYSTASISGFIFSCRM_DOMAIN . '\GravityFormsInfusionsoftIntegrator_Bootstrap', 'load'), 5);

if (false) {
	add_action('shutdown', function () {
		global $wpdb;
		error_log("//////////////////////////////////////////");
		foreach ($wpdb->queries as $q) {
			error_log($q[0] . " - ($q[1] s)");
		}
	});
}

/*
 * Function required_plugins_notices
 *
 * It checks for all required plugins on current Wordpress installation and show notifications
 */
function required_plugins_notices()
{
	if (!is_plugin_active('gravityforms/gravityforms.php')) { /* */
		echo "<div class=\"notice notice-error is-dismissible\"><p><strong>" .
			"Please install <a href=\"https://gravityforms.com/\" target=\"_blank\">" .
			"GravityForms plugin </a> to make use of Infusionsoft-GravityForms Integrator plugin!</div>";
	}

	if (is_plugin_active('infusionsoft-sdk/infusionsoft-sdk.php')) {
		echo "<div class=\"notice notice-error is-dismissible\"><p><strong>" .
			"Please deactivate infusionsoft-sdk to make use of Infusionsoft-GravityForms Integrator plugin!</div>";
	}
}

add_action('admin_notices', SYSTASISGFIFSCRM_DOMAIN . '\required_plugins_notices');
