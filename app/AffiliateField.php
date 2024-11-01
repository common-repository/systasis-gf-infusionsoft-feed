<?php

namespace systasisgfifscrm;

use GFForms;

GFForms::include_feed_addon_framework();

/**
 * Systasis Gravity Forms Infusionsoft Feed Add-On
 *
 * @since     1.0
 * @package   GravityForms
 * @author    Systasis Computer Systems, Inc.
 * @version   1.1
 * @copyright Copyright (c) 2017, Systasis Computer Systems
 */

use Exception;
use GF_Field_Hidden;

class AffiliateField extends GF_Field_Hidden
{
	public $type = "AffiliateCode";
	public $allowsPrepopulate = true;
	public $label = "Affiliate Code";

	/**
	 * Defines the text translation domain for the Infusionsoft CRM Feed Add-On
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_domain The text translation domain for the Feed Add-On
	 */
	protected $_domain = SYSTASISGFIFSCRM_DOMAIN;
	private static $_affiliate_id = 0;

	public function get_form_editor_field_title()
	{
		return esc_attr__($this->label, 'gravityformsinfusionsoftcrm');
	}

	public function get_field_label($force_frontend_label, $value)
	{
		return $this->label;
	}

	public function get_form_editor_button()
	{
		return array('group' => 'advanced_fields', 'text' => $this->get_form_editor_field_title());
	}

	/**
	 * Convert Affiliate Code to Affiliate Id
	 *
	 * @param String $affiliate_code Affiliate Code to look up.
	 * @throws Exception
	 * @return NULL | String Affiliate Id or NULL if not on file
	 */
	public static function getAffiliateId($affiliate_code)
	{
		if (!self::$_affiliate_id) {
			try {
				$data = self::getDataFromSource($affiliate_code);
			} catch (Exception $e) {
				$data = 0;
			}
			self::$_affiliate_id = $data;
		}
		// @TODO: Implement AffiliateResult filter
		return self::$_affiliate_id;
	}

	private static function getDataFromSource($affiliate_code_or_id)
	{
		// Maybe is affiliate ID already
		if (is_numeric($affiliate_code_or_id)) {
			return $affiliate_code_or_id;
		}

		// must be affiliate code
		try {
			$affiliate = \Infusionsoft_DataService::query(new \Infusionsoft_Affiliate(), array("AffCode" => $affiliate_code_or_id), 1, 0, array("Id"));
			if (0 < count($affiliate)) {
				return $affiliate[0]->Id;
			} else {
				throw new Exception("Affiliate not on file");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
