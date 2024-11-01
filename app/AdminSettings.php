<?php

namespace systasisgfifscrm;

use GFCommon;

/**
 * Systasis Gravity Forms Infusionsoft Feed Add-On
 *
 * @since     2.4
 * @package   GravityForms
 * @author    Systasis Computer Systems, Inc.
 * @version   1.0
 * @copyright Copyright (c) 2017, Systasis Computer Systems
 */
defined('ABSPATH') or exit();

class AdminSettings
{

	public static function do_plugin_settings_fields()
	{
		$me = GravityFormsInfusionsoftIntegrator::get_instance();
		return array(
				array('title' => esc_html__($me->short_title(), $me->domain()),
						'fields' => array(
								array('after_input' => ' .infusionsoft.com',
										'class' => 'small',
										'default_value' => get_option('infusionsoft_sdk_app_name'),
										'label' => esc_html__('Keap Max App Name', $me->domain()),
										'name' => 'app_name',
										'required' => true,
										'save_callback' => array('systasisgfifscrm\AdminSettings', 'save_callback'),
										'tooltip' => esc_html__('Your app name is first part of the URL you use to access Keap Max.', $me->domain()),
										'type' => 'text'),
								array(
										'after_input' => '<p><a href="//help.infusionsoft.com/userguides/get-started/tips-and-tricks/api-key" target="_blank">Click&nbsp;here</a> for instructions on finding your API key.</p>',
										'class' => 'large',
										'default_value' => get_option('infusionsoft_sdk_api_key'),
										'label' => esc_html__('Keap Max API Key', $me->domain()),
										'name' => 'api_key',
										'required' => true,
										'save_callback' => array('systasisgfifscrm\AdminSettings', 'save_callback'),
										'tooltip' => esc_html__('Each request to the Keap API must include an API key.', $me->domain()),
										'type' => 'text'),
								array('after_input' => 'yes',
										'choices' => array(
												array('label' => esc_html__('Yes', $me->domain()),
														'name' => 'clear_cache',
														'value' => '1',
														'default_value' => '1')),
										'label' => esc_html__('Clear Local Cache', $me->domain()),
										'name' => 'clear_cache',
										'save_callback' => array('systasisgfifscrm\AdminSettings', 'do_clear_cache'),
										'tooltip' => esc_html__('Due to Infusionsoft CRM\'s daily API usage limits,
				this Gravity Forms feed stores Keap Max custom fields data for twelve hours.<br/>
				If you make a change to your custom fields, you might not see it reflected
				immediately due to this data caching.', $me->domain()),
										'type' => 'checkbox'))));
	}

	public static function save_callback($field, $field_setting)
	{
		update_option('infusionsoft_sdk_' . rgget('name', $field), $field_setting);
	}

	public static function do_clear_cache($field, $field_setting)
	{
		if (! empty($field_setting)) {
			$me = GravityFormsInfusionsoftIntegrator::get_instance();

			delete_transient($me->fields_transient_name);

			GFCommon::add_message('Custom fields cache has been cleared.');
		}
	}

	/**
	 * Remove non-alphanumeric characters.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function sanitize($value)
	{
		return preg_replace("/[^a-zA-Z0-9]+/", "", $value);
	}

	/*
	 * Define the fields used on the settings page.
	 */
	public static function load()
	{
	}
}
