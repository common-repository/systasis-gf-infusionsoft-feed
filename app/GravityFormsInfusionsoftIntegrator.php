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
 * @version   2.5.0
 * @copyright Copyright (c) 2017, Systasis Computer Systems
 */
defined('ABSPATH') or exit();

class GravityFormsInfusionsoftIntegrator extends \GFFeedAddOn
{
    /**
     *
     */
    const REFERRAL_TYPE_COOKIE = 0;
    const REFERRAL_TYPE_PERMANENT = 1;
    const REFERRAL_TYPE_MANUAL = 2;
    const FEED_CONDITIONAL_API_GOALS = 'ConditionalApiGoals';
    const FEED_UNCONDITIONAL_API_GOAL = 'UnConditionalApiGoal';
    const FEED_COMBINE_FIELDS = 'contactCombineFields';
    const FEED_DUPLICATE_CHECK = 'contactDuplicateCheck';
    const FEED_CHECK_TYPE = 'contactDuplicateCheckType';
    const FEED_LEAD_SOURCE = 'contactLeadSource';
    const FEED_CONTACT_MARKETABLE = 'contactEmailMarketable';
    const FEED_ATTACHMENTS_LIST = 'contactAttachments';
    const ENABLE = 'enable';
    const COMBINE_FIELDS_NAME = 'combineFields';
    const COMBINE_FIELDS_ID = '_field_list';
    const COMBINE_FIELDS_CSSCLASS = 'cssClass';
    const COMBINE_FIELDS_FIELDMAP = 'fieldMap';
    const CONFIG = 'config';
    const FIELD_ID = 'field_id';
    const OPERATOR = 'operator';
    const VALUE = 'value';
    const NAME = 'name';

    const INFUSIONSOFT_SDK_APP_NAME = 'infusionsoft_sdk_app_name';
    const INFUSIONSOFT_SDK_API_KEY = 'infusionsoft_sdk_api_key';

    const CONTACT_CUSTOM_CACHE = "_contact_custom";
    const LEAD_SOURCE_CACHE = "_lead_source";

    /**
     * Contains an instance of this class, if available.
     *
     * @since  1.0
     * @access private
     * @var    object $_instance If available, contains an instance of this class.
     */
    private static $_instance = null;
    /**
     * Defines this class' pseudo-type. See javascript,
     */
    protected $_types = array('conditional_api_goal', 'combine_fields');
    /**
     * Defines the version of the Infusionsoft CRM Feed Add-On.
     *
     * @since  1.0
     * @access protected
     * @var    string $_version Contains the version, defined from infusionsoftcrm.php
     */
    protected $_version = SYSTASISGFIFSCRM_VERSION;
    /**
     * Defines the minimum Gravity Forms version required.
     *
     * @since  1.0
     * @access protected
     * @var    string $_min_gravityforms_version The minimum version required.
     */
    protected $_min_gravityforms_version = '2.0';
    /**
     * Defines the plugin slug.
     *
     * @since  1.0
     * @access protected
     * @var    string $_slug The slug used for this plugin.
     */
    protected $_slug = SYSTASISGFIFSCRM_SLUG;
    /**
     * Defines the main plugin file.
     *
     * @since  1.0
     * @access protected
     * @var    string $_path The path to the main plugin file, relative to the plugins folder.
     */
    protected $_path = SYSTASISGFIFSCRM_PATH . '/' . SYSTASISGFIFSCRM_FILE;
    /**
     * Defines the full path to this class file.
     *
     * @since  1.0
     * @access protected
     * @var    string $_full_path The full path.
     */
    protected $_full_path = SYSTASISGFIFSCRM_PATH . '/' . SYSTASISGFIFSCRM_FILE; // Do not use __FILE__
    /**
     * Defines the URL where this Feed Add-On can be found.
     *
     * @since  1.0
     * @access protected
     * @var    string The URL of the Feed Add-On.
     */
    protected $_url = 'https://systasis.co';
    /**
     * Defines the title of this Feed Add-On.
     *
     * @since  1.0
     * @access protected
     * @var    string $_title The title of the Feed Add-On.
     */
    protected $_title = 'Keap CRM Feed Add-On, from Systasis Computer Systems';
    /**
     * Defines the short title of the Feed Add-On.
     *
     * @since  1.0
     * @access protected
     * @var    string $_short_title The short title.
     */
    protected $_short_title = "KeapConnect";
    /**
     * Defines if Feed Add-On should use Gravity Forms servers for update data.
     *
     * @since  1.0
     * @access protected
     * @var    bool
     */
    protected $_enable_rg_autoupgrade = false;
    /**
     * Defines the capability needed to access the Feed Add-On settings page.
     *
     * @since  1.0
     * @access protected
     * @var    string $_capabilities_settings_page The capability needed to access the Feed Add-On settings page.
     */
    protected $_capabilities_settings_page = 'systasis_gf_infusionsoftcrm';
    /**
     * Defines the capability needed to access the Feed Add-On form settings page.
     *
     * @since  1.0
     * @access protected
     * @var    string $_capabilities_form_settings The capability needed to access the Feed Add-On form settings page.
     */
    protected $_capabilities_form_settings = 'systasis_gf_infusionsoftcrm';
    /**
     * Defines the capability needed to uninstall the Feed Add-On.
     *
     * @since  1.0
     * @access protected
     * @var    string $_capabilities_uninstall The capability needed to uninstall the Feed Add-On.
     */
    protected $_capabilities_uninstall = 'systasis_gf_infusionsoftcrm_uninstall';
    /**
     * Defines the capabilities needed for the Infusionsoft CRM Feed Add-On
     *
     * @since  1.0
     * @access protected
     * @var    array $_capabilities The capabilities needed for the Feed Add-On
     */
    protected $_capabilities = array('systasis_gf_infusionsoftcrm', 'systasis_gf_infusionsoftcrm_uninstall');
    /**
     * Defines the text translation domain for the Infusionsoft CRM Feed Add-On
     *
     * @since  1.0
     * @access protected
     * @var    string $_domain The text translation domain for the Feed Add-On
     */
    protected $_domain = SYSTASISGFIFSCRM_DOMAIN;
    /**
     * Contains an instance of the Infusionsoft CRM API libray, if available.
     *
     * @since  1.0
     * @access protected
     * @var    object $api If available, contains an instance of the Infusionsoft CRM API library.
     */
    protected $api = null;
    /**
     * Defines the transient name used to cache Infusionsoft CRM custom fields.
     *
     * @since  1.0
     * @access protected
     * @var    string $fields_transient_name Transient name used to cache Infusionsoft CRM custom fields.
     */
    protected $fields_transient_name = 'gform_infusionsoftcrm_fields';

    /**
     * Contains an instance of the admin library when plugin provides front-end support
     */
    protected $_admin = null;
    protected $error = "";

    /**
     * Get an instance of this class.
     *
     * @return GravityFormsInfusionsoftIntegrator
     */
    public static function get_instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new GravityFormsInfusionsoftIntegrator();
        }

        return self::$_instance;
    }

    /**
     * Plugin starting point. Handles hooks, loading of language files.
     */
    public function init()
    {
        parent::init();

        \GF_Fields::register(new AffiliateField());

        // Add a label to the Entry display for this Feed Add-On
        add_filter('gform_entry_meta', function ($entry_meta, $form_id) {
            /*
             * data will be stored with the meta key named contact_id
             * label - entry list will use Id as the column header
             * is_numeric - used when sorting the entry list, indicates whether the data should be treated as numeric when sorting
             * is_default_column - when set to true automatically adds the column to the entry list, without having to edit and add the column for display
             * filter - used on the results pages, the entry list search and export entries page.
             */
            $feeds = \GFAPI::get_feeds(null, $form_id);
            if (!is_a($feeds, "WP_Error")) {
                foreach ($feeds as $feed) {
                    if ($feed['addon_slug'] == $this->_slug) {
                        $entry_meta['contact_id'] = array(
                            'label' => 'Contact Id',
                            'is_numeric' => true,
                            'is_default_column' => true,
                            'filter' => array('operators' => array('is', 'isnot', '>', '<'))
                        );
                    }
                }
            }

            return $entry_meta;
        }, 10, 2);

        add_action('admin_notices', function () {
            if (!current_user_can('administrator')) {
                return;
            }
            $apikey = $this->get_plugin_setting(self::INFUSIONSOFT_SDK_API_KEY);
            if (substr($apikey, 0, strlen(SystasisGFIFSCrypto::RAW_APIKEY_PREFIX)) != SystasisGFIFSCrypto::RAW_APIKEY_PREFIX) {
                // _e('Please configure your Keap Service Account key on the <a href="admin.php?page=gf_settings&subview=systasisgfifscrm">Gravity Forms Infusionsoft Feed Add-On Settings</a> page', SYSTASISGFIFSCRM_DOMAIN);
                // $admin_url = admin_url('options-general.php?page=' . $this->_path);
?>
                <div class="gf-notice notice notice-warning">
                    <p><?php echo self::configure_addon_message(); ?></p>
                </div>
<?php
            }
        });
    }

    public function export_form($form)
    {
        $feed = parent::get_feeds($form['id']);
        $form['feeds'] = $feed;
        return $form;
    }

    /**
     * Register needed hooks for Add-On.
     *
     * @since  1.0
     * @access public
     */
    public function pre_init()
    {
        parent::pre_init();
        add_filter('gform_export_form', array($this, 'export_feeds_with_form'));
        add_action('gform_forms_post_import', array($this, 'import_feeds_with_form'));
    }

    /*
     * Declare minimum software version dependencies.
     *
     * @since  1.0
     * @access public
     *
     * @return array $styles
     */
    public function minimum_requirements()
    {
        $deps = array(
            // Require PHP version 5.6 or higher.
            'php' => array('version' => '5.6')
        );

        return $deps;
    }

    /**
     * Register needed styles.
     *
     * @since  1.0
     * @access public
     *
     * @return array $styles
     */
    public function styles()
    {
        $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG || isset($_GET['gform_debug']) ? '' : '.min';

        $styles = array(
            array(
                'handle' => 'gform_infusionsoftcrm_form_settings_css',
                'src' => $this->get_base_url() . "/css/form_settings{$min}.css",
                'version' => $this->_version,
                'enqueue' => array(array(
                    'admin_page' => array('form_settings'),
                    'tab' => $this->_slug,
                ))
            )
        );

        return array_merge(parent::styles(), $styles);
    }

    /**
     * Register needed scripts.
     *
     * @since  1.0
     * @access public
     *
     * @return array $scripts
     */
    public function scripts()
    {
        $min = ''; // defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

        $scripts = array(
            array(
                'handle' => 'gform-combine-fields-scripts',
                'src' => $this->get_base_url() . "/js/combine_fields{$min}.js",
                'version' => $this->_version,
                'deps' => array('jquery'),
                'enqueue' => array(
                    array(
                        'admin_page' => array('form_settings'),
                        'tab' => $this->_slug
                    )
                )
            ),
            array(
                'handle' => 'gform-conditional-api-goal-scripts',
                'src' => $this->get_base_url() . "/js/conditional_api_goal{$min}.js",
                'version' => $this->_version,
                'deps' => array('jquery'),
                'enqueue' => array(
                    array(
                        'admin_page' => array('form_settings'),
                        'tab' => $this->_slug
                    )
                )
            ),
            array(
                'handle' => 'gform_placeholder',
                'enqueue' => array(
                    array(
                        'admin_page' => array('form_settings'),
                        'field_types' => array('conditional_api_goal'), // Must match js/conditional_api_goal.js
                    )
                )
            )
        );

        return array_merge(parent::scripts(), $scripts);
    }

    /**
     * Uninstall the feed addon
     */
    public function uninstall()
    {
        delete_transient($this->fields_transient_name . self::CONTACT_CUSTOM_CACHE); // Ignore return result
        delete_transient($this->fields_transient_name . self::LEAD_SOURCE_CACHE); // Ignore return result
        parent::uninstall();
    }

    // # PLUGIN SETTINGS -------------------------------------------------------------------------------------------------

    /**
     * Add clear custom fields cache check.
     *
     * @since  2.3.6
     * @access public
     *
     */
    public function plugin_settings_page()
    {
        $this->maybe_clear_fields_cache();

        parent::plugin_settings_page();
    }

    /**
     * Clear the Infusionsoft CRM custom fields cache.
     *
     * @since  1.1
     * @access public
     *
     */
    public function maybe_clear_fields_cache()
    {
        // If the clear_field_cache parameter isn't set, exit.
        if ('true' !== rgget('clear_field_cache')) {
            return;
        }

        // Clear the cache.
        delete_transient($this->fields_transient_name . self::CONTACT_CUSTOM_CACHE);
        delete_transient($this->fields_transient_name . self::LEAD_SOURCE_CACHE);

        // Add success message.
        \GFCommon::add_message('Cache cleared.');
        \GFCommon::display_admin_message();
    }

    // public function validate_clear_cache_settings($field, $field_value)

    public function plugin_settings_fields()
    {
        // Prepare plugin description.
        $description = '<p>';
        $description .= sprintf(
            esc_html__(
                'Infusionsoft CRM is a contact management tool that gives you a'
                    . ' 360-degree view of your complete sales cycle and pipeline. Use Gravity Forms to collect customer'
                    . ' information and automatically add it to your Infusionsoft CRM account.'
                    . '%1$sIf you don\'t have an Infusionsoft CRM account, you can %2$ssign up for one here.%3$s',
                SYSTASISGFIFSCRM_DOMAIN
            ),
            '<p>',
            '<a href="https://www.infusionsoft.com" target="_blank">',
            '</a></p>'
        );
        $fields = array(
            array(
                'name'                => 'authorization',
                'type'                => 'authorization',
                // 'save_callback'       => array($this, 'save_authorization'),
                'args'                => array(
                    'appName'            => array(
                        'name'              => self::INFUSIONSOFT_SDK_APP_NAME,
                        'type'              => 'text',
                        'tooltip'           => 'Your app name is the first part of the URL you use to access Keap.',
                        'after_input'       => 'The part of your Keap URL prior to “.infusionsoft.com”',
                    ),
                    'apiKey'             => array(
                        'name'                => self::INFUSIONSOFT_SDK_API_KEY,
                        'type'                => 'text',
                        'tooltip'             => 'Your Service Account key proves this application has access to your Keap account.',
                        'after_input'         => '<a href="https://help.infusionsoft.com/help/api-key" target="_blank">Click here</a> for instructions on generating your Service Account key.',
                    )
                ),
            ),
            array(
                'type'                => 'save',
            ),
            array(
                'name'              => 'clear_cache',
                'type'              => 'clear_cache',
            ),
        );
        return array(
            array('title' => '', 'description' => $description, 'fields' => $fields),
        );
    }

    /**
     * Generates clear custom fields cache button field markup.
     *
     * @param  array $field Field properties.
     * @param  bool  $echo  Display field contents. Defaults to true.
     *
     * @since  2.3.6
     *
     * @return string
     */
    public function settings_clear_cache()
    {
        $html = '<div><h1>' . esc_html__('Clear Cache', SYSTASISGFIFSCRM_DOMAIN) . '</h1></div>';
        $html .= '<p>' .
            esc_html__('Due to Infusionsoft CRM\'s daily API usage limits, Gravity Forms stores Infusionsoft CRM lead sources and custom ' .
                'fields data for twelve hours. If you make a change these values in Infusionsoft, you might not see it reflected ' .
                'immediately due to this data caching. To manually clear the custom fields cache, click the button below.', SYSTASISGFIFSCRM_DOMAIN) .
            '</p>';
        $html .= '<p><a href="' . add_query_arg('clear_field_cache', 'true') . '" class="primary button large">' .
            esc_html__('Clear Custom Fields Cache', SYSTASISGFIFSCRM_DOMAIN) . '</a></p>';

        return $html;
    }

    // apikey type defined in plugin_settings_fields()
    public function settings_authorization($field)
    {
        echo '<div><h1>' . esc_html__('API connection settings', SYSTASISGFIFSCRM_DOMAIN) . '</h1></div>';
        echo '<div style="padding-top:.25rem;"><h2>' . esc_html__('Application name', SYSTASISGFIFSCRM_DOMAIN) . '</h2>';
        $this->settings_text($field['args']['appName']);
        echo '</div><div style="padding-top:.25rem;"><h2>' . esc_html__('Service Account key', SYSTASISGFIFSCRM_DOMAIN) . '</h2>';
        $this->settings_text($field['args']['apiKey']);
        echo '</div>';
    }

    // public function validate_save_settings($field, $field_value)
    // apikey type defined in plugin_settings_fields()
    public function validate_authorization_settings($field, $field_value)
    {
        try {
            /* $app = */
            \Infusionsoft_AppPool::addApp(new \Infusionsoft_App(
                rgar($field_value, self::INFUSIONSOFT_SDK_APP_NAME, "") . '.infusionsoft.com',
                SystasisGFIFSCrypto::decrypt(rgar($field_value, self::INFUSIONSOFT_SDK_API_KEY, ""))
            ));
            // $app->logger(new GravityFormsInfusionsoftLogger());
            // $app->enableDebug();
            $result = \Infusionsoft_DataService::getAppSetting('Contact', 'optiontypes');
            $pos = strrpos($result, "ERROR");
            return ($pos === false) ?  true : false;
        } catch (\Exception $e) {
            $message = 'Service Account key validation failed: ' . $e->getMessage();
            parent::set_field_error($field, $message);
            \GFCommon::add_error_message($message);
            $this->log_error('Service Account key validation failed: ' . $e);
        }
        \GFCommon::display_admin_message();
    }

    /**
     * Check if the plugin settings have changed.
     *
     * @since  2.3.6
     * @access public
     *
     * @return bool
     */
    public function have_plugin_settings_changed()
    {

        // Get previous and new settings.
        $old_settings = $this->get_previous_settings();
        $new_settings = $this->get_posted_settings();

        // Delete cached fields.
        delete_transient($this->fields_transient_name . self::CONTACT_CUSTOM_CACHE);
        delete_transient($this->fields_transient_name . self::LEAD_SOURCE_CACHE);

        // If the app name has changed, return true.
        if (rgar($old_settings, self::INFUSIONSOFT_SDK_APP_NAME) !== rgar($new_settings, self::INFUSIONSOFT_SDK_APP_NAME)) {
            return true;
        }

        // If the Service Account key has changed, return true.
        $old_settings[self::INFUSIONSOFT_SDK_API_KEY] = SystasisGFIFSCrypto::decrypt(rgar($old_settings, self::INFUSIONSOFT_SDK_API_KEY));
        if (rgar($old_settings, self::INFUSIONSOFT_SDK_API_KEY) !== rgar($new_settings, self::INFUSIONSOFT_SDK_API_KEY)) {
            return true;
        }

        return false;
    }

    /**
     * Update Infusionsoft API settings.
     *
     * @since  2.3.6
     * @access public
     *
     * @param array  $field       Field properties.
     * @param string $field_value Current field value.
     *
     * @return string|null
     */
    public function save_authorization($value)
    {
        // If settings have not changed, do not update infusionsoft_sdk values
        if (!$this->have_plugin_settings_changed()) {
            return $value;
        }

        $settings = $this->get_posted_settings();
        // If the app name or Service Account key are empty, set result to null.
        if (!rgar($settings, self::INFUSIONSOFT_SDK_APP_NAME) || !rgar($settings, self::INFUSIONSOFT_SDK_API_KEY)) {
            return null;
        }

        $settings[self::INFUSIONSOFT_SDK_API_KEY] = SystasisGFIFSCrypto::encrypt(rgar($settings, self::INFUSIONSOFT_SDK_API_KEY));
        return $settings;
    }

    // # FEED SETTINGS -------------------------------------------------------------------------------------------------

    /**
     * Setup fields for feed settings.
     *
     * @since  1.0
     * @access public
     *
     * @uses GFFeedAddOn::get_default_feed_name()
     * @uses GravityFormsInfusionsoftIntegrator::contact_feed_settings_fields()
     *
     * @return array
     */
    public function feed_settings_fields()
    {
        if (!has_filter('gform_addon_field_map_choices', array($this, 'prune_field_map_choices'))) {
            add_filter('gform_addon_field_map_choices', array($this, 'prune_field_map_choices'), 10, 4);
        }

        // Prepare module feed settings sections
        $contact_fields = $this->contact_feed_settings_fields();
        if (empty($contact_fields)) {
            \GFCommon::display_admin_message();
            return null;
        }

        // Prepare base feed settings section
        $base_fields = array(
            'fields' => array(
                array(
                    'name' => 'feedName',
                    'label' => esc_html__('Feed Name', SYSTASISGFIFSCRM_DOMAIN),
                    'type' => 'text',
                    'required' => true,
                    'default_value' => $this->get_default_feed_name(),
                    'tooltip' => '<h6>' . esc_html__('Name', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__('Enter a feed name to uniquely identify this setup.', SYSTASISGFIFSCRM_DOMAIN)
                ),
                array(
                    'name' => 'action',
                    'label' => esc_html__('Action', SYSTASISGFIFSCRM_DOMAIN),
                    'required' => true,
                    'type' => 'select',
                    'onchange' => "jQuery(this).parents('form').submit();",
                    'tooltip' => '<h6>' . esc_html__('Action', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__('Choose what will happen when this feed is processed.', SYSTASISGFIFSCRM_DOMAIN),
                    'default_value' => 'contact',
                    'choices' => array(
                        array('label' => esc_html__('Create a New Contact', SYSTASISGFIFSCRM_DOMAIN), 'value' => 'contact')
                    )
                )
            )
        );

        $result[] = $base_fields;
        $result[] = $contact_fields;

        // Get file field choices.
        $file_choices = $this->get_file_fields_for_feed_setting('contact');

        // Add Attachments settings field if file field choices exist.
        if (!empty($file_choices)) {
            $file_upload_fields = array(
                'title' => esc_html__('File Upload', SYSTASISGFIFSCRM_DOMAIN),
                'description' => __('Your form has a file upload field. Set the checkbox to enable upload to contact\'s filebox.<br/>Infusionsoft CRM has a maximum file size of 10MB. Any file larger than this will not be uploaded.', SYSTASISGFIFSCRM_DOMAIN),
                'fields' => array(
                    array(
                        'name' => self::FEED_ATTACHMENTS_LIST,
                        'type' => 'checkbox',
                        // 'label' => esc_html__('Enable Upload To Filebox', SYSTASISGFIFSCRM_DOMAIN),
                        'choices' => $file_choices,
                    ),
                )
            );
            $result[] = $file_upload_fields;
        }

        // Prepare API goal settings field section.
        $api_goal_fields = array(
            'title' => esc_html__('API Goal Logic', $this->_domain),
            'fields' => array(
                array(
                    'name' => self::FEED_UNCONDITIONAL_API_GOAL,
                    'label' => esc_html__('Unconditional API Goal', $this->_domain),
                    'type' => 'text',
                    'tooltip' => '<h6>' . esc_html__('Unconditional API Goal. Every Contact will achieve this goal', $this->_domain) . '</h6>'
                ),
                array(
                    'name' => self::FEED_CONDITIONAL_API_GOALS,
                    'type' => $this->_types[0],
                    'label' => esc_html__('Conditional API Goal', SYSTASISGFIFSCRM_DOMAIN),
                    'checkbox_label' => esc_html__('Enable', SYSTASISGFIFSCRM_DOMAIN),
                    'instructions' => esc_html__('Process this API Goal if', SYSTASISGFIFSCRM_DOMAIN),
                    'tooltip' => '<h6>' . esc_html__('Conditional API Goal', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__('Configure conditional API goal. Only certain Contacts will achieve this goal.', SYSTASISGFIFSCRM_DOMAIN)
                ),
            )
        );

        $result[] = $api_goal_fields;

        // Prepare combine fields field section
        $combine_fields =  array(
            'fields' => array(
                array(
                    'name' => self::FEED_COMBINE_FIELDS,
                    'type' => $this->_types[1],
                    'label' => esc_html__('Combine field values', SYSTASISGFIFSCRM_DOMAIN),
                    'checkbox_label' => esc_html__('Enable', SYSTASISGFIFSCRM_DOMAIN),
                    'instructions' => esc_html__('Combine fields', SYSTASISGFIFSCRM_DOMAIN),
                    'tooltip' => '<h6>' . esc_html__('Combine Fields', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__("Condense several form field values as comma-separated values into a Contact field.", SYSTASISGFIFSCRM_DOMAIN),
                ),
            )
        );

        $result[] = $combine_fields;

        // Prepare conditional logic settings section
        $feed_conditional_fields = array(
            'fields' => array(
                array(
                    'name' => 'feedCondition',
                    'type' => 'feed_condition',
                    'label' => esc_html__('Feed Conditional Logic', SYSTASISGFIFSCRM_DOMAIN),
                    'checkbox_label' => esc_html__('Enable', SYSTASISGFIFSCRM_DOMAIN),
                    'instructions' => esc_html__('Export to Infusionsoft CRM if', SYSTASISGFIFSCRM_DOMAIN),
                    'tooltip' => '<h6>' . esc_html__('Conditional Logic', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__('When conditional logic is enabled, form submissions will only be exported to Infusionsoft CRM when the condition is met. When disabled, all form submissions will be posted.', SYSTASISGFIFSCRM_DOMAIN)
                ),
            )
        );

        $result[] = $feed_conditional_fields;

        return $result; // array($base_fields, $contact_fields, $file_upload_fields, $api_goal_fields, $combine_fields, $feed_conditional_fields);
    }

    /**
     * Get form file fields for feed field settings.
     *
     * @since  1.0
     * @access public
     *
     * @param  string $module Module to prepare file fields for. Defaults to contact.
     *
     * @uses GFAddOn::get_current_form()
     * @uses GFAPI::get_fields_by_type()
     *
     * @return array
     */
    public function get_file_fields_for_feed_setting($module = 'contact')
    {
        // Initialize choices array.
        $choices = array();

        // Get the form.
        $form = $this->get_current_form();

        // Get file fields.
        $file_fields = \GFAPI::get_fields_by_type($form, array('fileupload'), true);

        // If file fields exist, prepare them as choices.
        if (!empty($file_fields)) {
            // Loop through file fields.
            foreach ($file_fields as $field) {
                // Add file field as choice.
                $choices[] = array('name' => $module . 'Attachments[' . $field->id . ']', 'default_value' => 1, 'value' => 1, 'label' => $field->label);
            }
        }

        return $choices;
    }

    /**
     * COMBINE FIELDS
     */
    /**
     * Assemble combine fields fields
     */
    public function settings_combine_fields($field, $echo = TRUE)
    {
        $checkbox_field = $this->get_combine_fields_checkbox($field);
        $instructions = esc_html__('Process this API goal if', SYSTASISGFIFSCRM_DOMAIN);
        $cssClassName_field = $this->get_combine_fields_css_class_text_field($field);
        $mapping_fields =  $this->get_combine_fields_mapping_select_field($field);
        $html = $this->settings_checkbox($checkbox_field, FALSE);
        $html .= '<div id="combine_fields_container">' . esc_html__('CSS Class', SYSTASISGFIFSCRM_DOMAIN) . $this->settings_text($cssClassName_field, FALSE);
        $html .= '<div id="combine_fields_container">' . esc_html__('Mapping', SYSTASISGFIFSCRM_DOMAIN) . $this->settings_select($mapping_fields, FALSE);
        $html .= '<script type="text/javascript"> var combineFieldsObj = new CombineFieldsObj({' .
            'strings: { objectDescription: "' . esc_attr($instructions) . '" },' .
            '}); </script></div>';

        if ($this->field_failed_validation($field)) {
            $html .= $this->get_error_icon($field);
        }

        if ($echo) {
            echo $html;
        }
        return $html;
    }

    public function get_combine_fields_checkbox($field)
    {
        $checkbox_label = esc_html__('Enable Combine Fields', SYSTASISGFIFSCRM_DOMAIN);

        $checkbox_field = array(
            'name' => $field->type . '_mapping',
            'type' => 'checkbox',
            'choices' => array(
                array(
                    'label' => $checkbox_label,
                    'name' => self::FEED_COMBINE_FIELDS,
                ),
            ),
            'onclick' => 'jQuery("#combine_fields_container").toggle();',
        );

        return $checkbox_field;
    }

    public function get_combine_fields_css_class_text_field($field)
    {
        $text_label = esc_html__('CSS Class', SYSTASISGFIFSCRM_DOMAIN);

        $text_field = array(
            'name' => $this->_types[1] . '_' . self::COMBINE_FIELDS_CSSCLASS,
            'type' => 'text',
            'label' => $text_label,
            'tooltip' => 'CSS class',
        );

        return $text_field;
    }

    public function get_combine_fields_mapping_select_field($field)
    {
        $crmFields = $this->get_combine_fields_dynamic_field_choices();

        $select_field = array(
            'name' => $this->_types[1] . '_' . self::COMBINE_FIELDS_FIELDMAP,
            'label' => esc_html__('Mapping', SYSTASISGFIFSCRM_DOMAIN),
            'type' => 'select',
            'disable_custom' => true,
            'choices' => $crmFields,
        );

        return $select_field;
    }

    /*
     * Setup dynamic field list of CRM fields.
     * Infusionsoft
     *
     * @since  1.0
     * @access public
     *
     * @return array
     */
    public function get_combine_fields_dynamic_field_choices()
    {
        $customFields = $this->get_field_map_for_module("Contacts", "constrained");

        // Loop through module fields.
        $choices[] = array('field_type' => 'Text', 'value' => '', 'label' => 'Please select');
        foreach ($customFields as &$customField) {
            // If this is a dynamic field and the field isn't mappable, skip it.
            if (!$this->fieldIsMappable($customField)) {
                continue;
            }
            switch ($customField['field_type']) {
                case "Date":
                case "Whole Number":
                case "Text":
                case "Yes/No":
                case "Currency":
                case "Website":
                case "Phone Number":
                case "Text Area":
                    break;
                case "Radio":
                case "Dropdown":
                case "List Box":
                    unset($customField['choices']); // Do not generate OPTGROUP
                    break;
                default:
                    break;
            }
            $choices[] = $customField;
        }

        return $choices;
    }

    public function validate_combine_fields_settings($field, $field_setting)
    {
        $combine_fields_enabled = rgar($field_setting, self::FEED_COMBINE_FIELDS);
        $combine_fields_css_class = rgar($field_setting, $this->_types[1] . '_' . self::COMBINE_FIELDS_CSSCLASS);
        $combine_fields_map_field = rgar($field_setting, $this->_types[1] . '_' . self::COMBINE_FIELDS_FIELDMAP);

        if ($combine_fields_enabled) {
            if (empty($combine_fields_css_class)) {
                parent::set_field_error($field, 'You need to set CSS class name.');
                \GFCommon::add_error_message('You need to set CSS class name.');
            }
            if (empty($combine_fields_map_field)) {
                parent::set_field_error($field, 'You need to set mapped field name.');
                \GFCommon::add_error_message('You need to set mapped field name.');
            }
        }
    }

    /**
     * CONDITIONAL API GOAL
     */
    /**
     * Assemble conditional api goal fields
     */
    public function settings_conditional_api_goal($field, $echo = TRUE)
    {
        $conditional_logic = $this->get_conditional_api_goal_logic();
        $checkbox_field = $this->get_conditional_api_goal_checkbox($field);
        $hidden_field = $this->get_conditional_api_goal_hidden_field();
        $instructions = isset($field['instructions']) ? $field['instructions'] : esc_html__('Process this API goal if', SYSTASISGFIFSCRM_DOMAIN);
        $goalName_field = $this->get_conditional_api_goal_text_field($field);
        $html = $this->settings_checkbox($checkbox_field, FALSE);
        $html .= $this->settings_hidden($hidden_field, FALSE);
        $html .= '<div id="conditional_api_goal_name_container">' . esc_html__('API Goal Name', SYSTASISGFIFSCRM_DOMAIN) . $this->settings_text($goalName_field, FALSE);
        $html .= '<div id="conditional_api_goal_conditional_logic_container" style="padding-top:0.5rem;"><!-- dynamically populated --></div>';
        $html .= '<script type="text/javascript"> var conditionalApiGoal = new ConditionalApiGoalObj({' .
            'strings: { objectDescription: "' . esc_attr($instructions) . '" },' .
            'goalName: "",' .
            'logicObject: ' . $conditional_logic .
            '}); </script></div>';

        if ($this->field_failed_validation($field)) {
            $html .= $this->get_error_icon($field);
        }

        if ($echo) {
            echo $html;
        }
        return $html;
    }

    public function get_conditional_api_goal_checkbox($field)
    {
        $checkbox_label = esc_html__('Enable Conditional API Goal', SYSTASISGFIFSCRM_DOMAIN);

        $checkbox_field = array(
            'name' => $field->type . '_conditional_logic',
            'type' => 'checkbox',
            'choices' => array(
                array(
                    'label' => $checkbox_label,
                    'name' => $field->type . '_conditional_logic',
                ),
            ),
            'onclick' => 'jQuery("#conditional_api_goal_name_container").toggle();ToggleConditionalLogic( false, "conditional_api_goal" );',
        );

        return $checkbox_field;
    }

    public function get_conditional_api_goal_hidden_field()
    {
        $conditional_logic = $this->get_conditional_api_goal_logic();
        $hidden_field = array(
            'name' => $this->_types[0] . '_logic_object',
            'type' => 'hidden',
            'value' => $conditional_logic,
        );

        return $hidden_field;
    }

    public function get_conditional_api_goal_logic()
    {
        $conditional_logic_object = array();
        $saved_goal = $this->get_setting('conditional_api_goal_logic_object');
        $goal_list = empty($saved_goal) ? [] : $saved_goal;
        $goal = array_shift($goal_list);

        if (is_array($goal) && count($goal) > 0) {
            $conditional_logic_object = $goal;
        } else {
            $name = rgars($goal, self::NAME) . '/';
            $conditional_logic_object = array(
                'actionType' => 'show', 'logicType' => 'all', 'rules' => array(array(
                    'fieldId' => rgars($goal, $name . self::FIELD_ID), 'operator' => rgars($goal, $name . self::OPERATOR), 'value' => rgars($goal, $name . self::VALUE)
                ))
            );
        }

        $form_id = rgget('id');
        $form = \GFFormsModel::get_form_meta($form_id);
        $conditional_logic = json_encode(\GFFormsModel::trim_conditional_logic_values_from_element($conditional_logic_object, $form));

        return $conditional_logic;
    }

    public function get_conditional_api_goal_text_field($field)
    {
        $text_label = esc_html__('API Goal', SYSTASISGFIFSCRM_DOMAIN);

        $text_field = array(
            'name' => $field->type . '_name',
            'type' => 'text',
            'label' => $text_label,
            'tooltip' => 'Campaign API goal',
            'value' => ""
        );

        return $text_field;
    }

    public function get_conditional_logic_fields()
    {
        $form = $this->get_current_form();
        $fields = array();

        foreach ($form['fields'] as $field) {
            if ($field->is_conditional_logic_supported()) {
                $inputs = $field->get_entry_inputs();

                if ($inputs) {
                    $choices = array();

                    foreach ($inputs as $input) {
                        if (rgar($input, 'isHidden')) {
                            continue;
                        }
                        $choices[] = array(
                            'value' => $input['id'],
                            'label' => \GFCommon::get_label($field, $input['id'], true)
                        );
                    }

                    if (!empty($choices)) {
                        $fields[] = array('choices' => $choices, 'label' => \GFCommon::get_label($field));
                    }
                } else {
                    $fields[] = array('value' => $field->id, 'label' => \GFCommon::get_label($field));
                }
            }
        }

        return $fields;
    }

    /**
     * Validate goal name
     */
    public function validate_conditional_api_goal_settings($field, $field_Setting)
    {
        $settings = $this->get_posted_settings();
        $conditional_api_goal_enabled = rgar($settings,  $this->_types[0] . '_conditional_logic');

        /*
         * If conditional api goal name is set, save feed setting as usual
    	 *
    	 * Otherwise, show error
       	 */
        if ($conditional_api_goal_enabled === '1' && $this->is_save_postback() === true) {
            if ((empty(rgar($settings, $this->_types[0] . '_name')))) {
                parent::set_field_error($field, 'You need to set conditional API goal name first.');
                \GFCommon::add_error_message('You need to set conditional API goal name first.');
            }
        }
    }

    /*
     * Prune bad values from field map
     * Remove fields whose values are floats; they are field choices and should not be in the dynamic field map
     * This filter should be removed at some future date when such values are absent.
     */
    public function prune_field_map_choices($GFFields, $form_id, $field_type, $exclude_field_types)
    {
        return $GFFields;
    }

    /**
     * Setup contact fields for feed settings.
     *
     * @since  1.0
     * @access public
     *
     * @uses GFAddOn::add_field_after()
     * @uses GravityFormsInfusionsoftIntegrator::get_field_map_for_module()
     * @uses GravityFormsInfusionsoftIntegrator::get_file_fields_for_feed_setting()
     * @uses GravityFormsInfusionsoftIntegrator::get_lead_sources_for_feed_setting()
     *
     * @return array
     */
    public function contact_feed_settings_fields()
    {
        $contactStandardFields = $this->get_field_map_for_module('Contacts');
        if (empty($contactStandardFields)) {
            \GFCommon::add_error_message(__('No Infusionsoft fields found!', SYSTASISGFIFSCRM_DOMAIN));
            $this->log_error(__METHOD__ . '(): No Infusionsoft fields found!');
            return null;
        }
        // Prepare contact settings fields.
        $fields = array(
            'title' => esc_html__('Contact Details', SYSTASISGFIFSCRM_DOMAIN),
            'fields' => array(
                array(
                    'name' => 'contactStandardFields',
                    'label' => esc_html__('Map Fields', SYSTASISGFIFSCRM_DOMAIN),
                    'type' => 'field_map',
                    'field_map' => $contactStandardFields,
                    'tooltip' => '<h6>' . esc_html__('Map Fields', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__('Select which Gravity Form fields pair with their respective Infusionsoft CRM fields.', SYSTASISGFIFSCRM_DOMAIN)
                ),
                array(
                    'name' => 'contactCustomFields',
                    'label' => null,
                    'type' => 'dynamic_field_map',
                    'disable_custom' => true,
                    'exclude_field_types' => 'time',
                    'field_map' => $this->get_field_map_for_module('Contacts', 'dynamic')
                ),
                array(
                    'name' => self::FEED_LEAD_SOURCE,
                    'label' => esc_html__('Lead Source', SYSTASISGFIFSCRM_DOMAIN),
                    'type' => 'select_custom',
                    'choices' => $this->get_lead_sources_for_feed_setting(),
                    'tooltip' => '<h6>' . esc_html__('Lead Source', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__('Select a pre-defined lead source, or select "Add Custom Lead Source", then enter a feed-specific value.', SYSTASISGFIFSCRM_DOMAIN)
                ),
                array(
                    'name' => self::FEED_CONTACT_MARKETABLE,
                    'label' => esc_html__('Mark contact marketable', SYSTASISGFIFSCRM_DOMAIN),
                    'type' => 'checkbox',
                    'choices' => array(
                        array('name' => self::FEED_CONTACT_MARKETABLE, 'label' => 'Yes', 'default_value' => 1, 'value' => 1)
                    ),
                    'tooltip' => '<h6>' . esc_html__('Mark contact marketable', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__('Contact consents to receive marketing from you. Does not confirm the email. Consider implementing a campaign to send email confirmation.', SYSTASISGFIFSCRM_DOMAIN)
                ),
                array(
                    'name' => self::FEED_DUPLICATE_CHECK,
                    'label' => esc_html__('Avoid duplicate contacts', SYSTASISGFIFSCRM_DOMAIN),
                    'type' => 'checkbox_and_select',
                    'checkbox' => array(
                        'name' => self::FEED_DUPLICATE_CHECK,
                        'label' => esc_html__('Yes', SYSTASISGFIFSCRM_DOMAIN)
                    ),
                    'select' => array(
                        'name' => self::FEED_CHECK_TYPE,
                        'choices' => array(
                            array('label' => 'and dup check using email', 'value' => 'Email'),
                            array('label' => 'and dup check using email, name', 'value' => 'EmailAndName'),
                            array('label' => 'and dup check using email, name, company', 'value' => 'EmailAndNameAndCompany')
                        )
                    ),
                    'tooltip' => '<h6>' . esc_html__('Avoid duplicate contacts', SYSTASISGFIFSCRM_DOMAIN) . '</h6>' .
                        esc_html__('Yes: Attempt to update existing contact based on value(s) to find, otherwise add.</br>No: Always add the contact, possibly creating duplicates.', SYSTASISGFIFSCRM_DOMAIN)
                )
            )
        );

        return $fields;
    }

    public function fieldIsMappable($field)
    {
        $type = rgar($field, 'type');
        $type = rgar($field, 'field_type', $type);
        return (!in_array($type, array('Date/Time', 'User', 'Drilldown', 'Social Security Number')));
    }

    /**
     * Get field map fields for a Infusionsoft CRM module.
     *
     * @since  1.0
     * @access public
     *
     * @param string $module Module name.
     * @param string $field_map_type Type of field map: standard or dynamic. Defaults to standard.
     *
     * @return array $field_map
     */
    public function get_field_map_for_module($module, $field_map_type = 'standard')
    {

        // These Contact string fields are also available for storage
        $mappable =  [
            "Address1Type",       "Address2Street2", "Address2Street1", "Address2Type",  "Address3Street1",
            "Address3Street2",    "Address3Type",    "AssistantName",   "AssistantPhone",
            "BillingInformation", "City",            "City2",           "City3",       "Company",
            "ContactNotes",       "ContactType",     "Country",         "Country2",    "Country3",
            "Email",              "EmailAddress2",   "EmailAddress3",   "Fax1",        "Fax1Type",
            "Fax2",               "Fax2Type",        "FirstName",       "Groups",      "JobTitle",
            "Language",           "LastName",        "Leadsource",      "MiddleName",  "Nickname",
            "Password",           "Phone1",          "Phone1Ext",       "Phone1Type",  "Phone2",
            "Phone2Ext",          "Phone2Type",      "Phone3",          "Phone3Ext",   "Phone3Type",
            "Phone4",             "Phone4Ext",       "Phone4Type",      "Phone5",      "Phone5Ext",
            "Phone5Type",         "PostalCode",      "PostalCode2",     "PostalCode3", "ReferralCode",
            "SpouseName",         "State",           "State2",          "State3",      "StreetAddress1",
            "StreetAddress2",     "Suffix",          "TimeZone",        "Title",       "Username",
            "Validated",          "Website",         "ZipFour1",        "ZipFour2",    "ZipFour3",
        ];

        // Initialize field map.
        $field_map = array();

        // Define standard field labels.
        $standard_fields = array('Company', 'Email', 'FirstName', 'LastName');

        // Get fields for module.
        $fields = $this->get_module_fields($module);

        // Sort module fields in alphabetical order.
        usort($fields, array($this, 'sort_module_fields_by_label'));

        // Loop through module fields.
        foreach ($fields as $field) {
            // If this is a non-supported field type, skip it.
            if (!$this->fieldIsMappable($field)) {
                continue;
            }

            // If this is a standard field map and the field is not a standard field or is not required, skip it.
            if ('standard' === $field_map_type && !$field['required'] && !in_array($field['name'], $standard_fields)) {
                continue;
            }

            // If this is a dynamic field map and the field matches a standard field or is required, skip it.
            if ('dynamic' === $field_map_type && ($field['required'] || in_array($field['name'], $standard_fields))) {
                continue;
            }

            // If this is a constrained field map and the field matches a standard field or is required, skip it.
            if ('constrained' === $field_map_type && ($field['required'] || in_array($field['name'], $standard_fields))) {
                continue;
            }

            // Get Gravity Forms field type.
            switch ($field['type']) {
                case 'Year':
                case 'Date':
                    $field_type = 'date';
                    break;

                case 'Email':
                    $field_type = array('email', 'hidden');
                    break;

                case 'Phone':
                    $field_type = 'phone';
                    break;

                default:
                    $field_type = null;
                    break;
            }

            // Add field to field map.
            if ('constrained' === $field_map_type) {
                if (in_array($field['name'], $mappable)) {
                    $field_map[] = array(
                        'field_type' => 'Text',
                        'label' => $field['label'],
                        'name' => $field['name'],
                        'value' => $field['name'],
                    );
                } else {
                    switch ($field['type']) {
                        case 'Text':
                        case 'Text Area':
                            $field_map[] = array(
                                'field_type' => 'Text',
                                'label' => $field['label'],
                                'name' => $field['name'],
                                'value' => $field['name'],
                            );
                    }
                }
            } else {
                $field_map[] = array(
                    'field_type' => $field_type,
                    'label' => $field['label'],
                    'name' => $field['name'],
                    'value' => $field['name'],
                    'required' => $field['required'],
                );
            }
        }

        $this->log_debug(__METHOD__ . "() field_map\n" . print_r($field_map, true));
        return $field_map;
    }

    /**
     * Get fields for a Infusionsoft CRM module.
     *
     * @since  1.0
     * @access public
     *
     * @param string $module Module to get fields for. Defaults to all modules.
     *
     * @uses GravityFormsInfusionsoftIntegrator::update_cached_fields()
     *
     * @return array
     */
    public function get_module_fields($module = null)
    {
        // If module fields are not cached, retrieve them.
        if (false === ($fields = get_transient($this->fields_transient_name . self::CONTACT_CUSTOM_CACHE))) {
            $fields = $this->update_cached_fields(self::CONTACT_CUSTOM_CACHE);
        }
        if (!empty($fields)) {
            $fields = json_decode($fields, true);
        } else {
            // Log that contact custom fields cache could not be retrieved.
            $message = "Unable to update {$module} custom fields cache.";
            $this->log_error(__METHOD__ . "(): {$message}");
            \GFCommon::add_error_message($message);
            $fields = array();
        }

        // Synthesize contact fields
        $contactFields = (new \Infusionsoft_Contact())->getFields();
        foreach ($contactFields as $contactField) {
            $field = array(
                'custom_field' => false,
                'label' => $contactField,
                'name' => $contactField,
                'required' => false,
                'type' => "",
            );
            $fields[] = $field;
        }


        return rgar($fields, $module) ? rgar($fields, $module) : $fields;
    }

    /**
     * Update the cached fields for all the needed modules.
     *
     * @since  1.0
     * @access public
     *
     * @uses GravityFormsInfusionsoftIntegrator::get_fields()
     *
     * @return string $fields JSON encoded string of all module fields.
     */
    public function update_cached_fields($which)
    {
        switch ($which) {
            case self::CONTACT_CUSTOM_CACHE:
                return $this->update_cached_contact_custom_fields();
                break;
            case self::LEAD_SOURCE_CACHE:
                return $this->update_cached_lead_sources();
                break;
            default:
                return "";
        }
    }

    /**
     * Update the lead sources cache
     *
     * @since  1.0
     * @access public
     *
     * @uses GravityFormsInfusionsoftIntegrator::get_fields()
     *
     * @return JSON encoded string of all module fields
     */
    public function update_cached_lead_sources()
    {
        $this->initialize_api();

        $lead_sources = array();

        try {
            // Get Infusionsoft lead sources
            $lead_sources = \Infusionsoft_DataService::queryWithOrderBy(new \Infusionsoft_LeadSource(), array('Status' => 'Active'), 'Name');
        } catch (\Exception $e) {
            // Log that lead sources could not be retrieved
            $message = "Unable to get lead sources: " . $e->getMessage() . "\nCheck API settings.";
            $this->log_error(__METHOD__ . "(): {$message}");
            \GFCommon::add_error_message($message);
        }
        $transient = array();

        // Convert lead sources object data to array
        foreach ($lead_sources as $lead_source) {
            $transient[] = $lead_source->toArray();
        }

        $transient = json_encode($transient);

        // Store lead sources
        set_transient($this->fields_transient_name . self::LEAD_SOURCE_CACHE, $transient, 60 * 60 * 12);

        return $transient;
    }

    /**
     * Update the contact custom fields cache.
     *
     * @since  1.0
     * @access public
     *
     * @uses GravityFormsInfusionsoftIntegrator::get_fields()
     *
     * @return string $fields JSON encoded string of all module fields.
     */
    public function update_cached_contact_custom_fields()
    {
        /*
         * Key - Infusionsoft type id
         * Value - array(Infusionsoft type name, has options?)
         */
        $typeMaps = [
            1 => 'Phone Number',
            2 => 'Social Security Number',
            3 => 'Currency',
            4 => 'Percent',
            5 => 'State',
            6 => 'Yes/No',
            7 => 'Year',
            8 => 'Month',
            9 => 'Day of Week',
            10 => 'Name',
            11 => 'Decimal Number',
            12 => 'Whole Number',
            13 => 'Date',
            14 => 'Date/Time',
            15 => 'Text',
            16 => 'Text Area',
            17 => 'List Box',
            18 => 'Website',
            19 => 'Email',
            20 => 'Radio',
            21 => 'Dropdown',
            22 => 'User',
            23 => 'Drilldown',
        ];

        $this->initialize_api();

        // Initialize fields array.
        $fields = array();

        try {
            // Get module fields.
            $contact = new \Infusionsoft_Contact();
            $customFields = \Infusionsoft_DataService::getCustomFields($contact);
        } catch (\Exception $e) {
            // Log that custom fields could not be retrieved.
            $message = "Unable to get custom fields: " . $e->getMessage() . "\nCheck API settings.";
            $this->log_error(__METHOD__ . "(): {$message}");
            \GFCommon::add_error_message($message);
            return $fields;
        }

        // Loop through modules.
        foreach ($customFields as $customField) {
            // Prepare field details.
            $type = rgar($typeMaps, $customField[0]->DataType);
            $field = array(
                'custom_field' => true,
                'label' => $customField[0]->Label,
                'name' => $customField[0]->Name,
                'required' => false,
                'type' => $type,
            );
            $fields[] = $field;
        }

        // Convert fields array to JSON string.
        $fields = json_encode($fields);

        // Store fields.
        set_transient($this->fields_transient_name . self::CONTACT_CUSTOM_CACHE, $fields, 60 * 60 * 12);

        return $fields;
    }

    // # FEED PROCESSING -----------------------------------------------------------------------------------------------

    /**
     * Initializes the Infusionsoft CRM API.
     *
     * @since  1.0
     * @access public
     *
     * @uses Infusionsoft_AppPool::addApp()
     * @uses Infusionsoft_AppPool::getApp()
     * @uses Infusionsoft_App:enableDebug()
     *
     * @return bool API initialization state.
     */
    public function initialize_api()
    {
        /**
         * Get the IFS API credentials
         */
        $this->api = \Infusionsoft_AppPool::addApp(new \Infusionsoft_App(
            $this->get_plugin_setting(self::INFUSIONSOFT_SDK_APP_NAME) . '.infusionsoft.com',
            SystasisGFIFSCrypto::decrypt($this->get_plugin_setting(self::INFUSIONSOFT_SDK_API_KEY))
        ));
    }

    /**
     * Get Infusionsoft CRM lead sources.
     *
     * @since  1.0
     * @access public
     *
     * @uses GFAddOn::log_error()
     *
     * @return array $lead_sources
     */
    public function get_lead_sources_for_feed_setting()
    {
        // If module fields are not cached, retrieve them.
        if (false === ($transient = get_transient($this->fields_transient_name . self::LEAD_SOURCE_CACHE))) {
            $transient = $this->update_cached_fields(self::LEAD_SOURCE_CACHE);
        }

        if ("[]" != $transient) {
            $transient = json_decode($transient, true);
            foreach ($transient as $data) {
                $lead_source = new \Infusionsoft_LeadSource();
                $lead_source->loadFromArray($data, true);
                $lead_sources[] = $lead_source;
            }
        } else {
            // Log that contact custom fields cache could not be retrieved.
            $message = "Unable to update lead source cache.";
            $this->log_error(__METHOD__ . "(): {$message}");
            \GFCommon::add_error_message($message);
            $lead_sources = array();
        }

        // Initialize choices array.
        $choices = array(array('label' => esc_html__('- No lead source -', SYSTASISGFIFSCRM_DOMAIN), 'value' => ''));
        // If Infusionsoft CRM lead sources exist, add them as choices.
        if (!empty($lead_sources)) {
            // Loop through Infusionsoft lead sources.
            foreach ($lead_sources as $lead_source) {
                // Add lead source as choice.
                $choices[] = array('label' => esc_html($lead_source->Name), 'value' => esc_attr($lead_source->Name));
            }
        }

        return $choices;
    }

    /**
     * Insert settings field after another field.
     * (Forked to allow for passing a single settings section.)
     *
     * @since  1.3.1
     * @access public
     *
     * @param string $name Field name to insert settings field after.
     * @param array $fields Settings field.
     * @param array $settings Settings section to add field to.
     *
     * @return array
     */
    public function add_field_after($name, $fields, $settings)
    {

        // Move settings into another array.
        $settings = array($settings);

        // Add field.
        $settings = parent::add_field_after($name, $fields, $settings);

        // Return the first settings section.
        return $settings[0];
    }

    public function maybe_save_feed_settings($feed_id, $form_id)
    {
        return parent::maybe_save_feed_settings($feed_id, $form_id);
    }

    /**
     * Get choices for a specifc Infusionsoft CRM module field formatted for field settings.
     *
     * @since  1.0
     * @access public
     *
     * @param string $module
     * @param string $field_name
     *
     * @uses GravityFormsInfusionsoftIntegrator::get_module_field()
     *
     * @return array
     */
    public function get_module_field_choices($module, $field_name)
    {
        // Initialize choices array.
        $choices = array();

        // Get module field for field name.
        $field = $this->get_module_field($module, $field_name);

        // If no field choices exist, return choices.
        if (empty($field['choices'])) {
            return $choices;
        }

        // Loop through field choices.
        foreach ($field['choices'] as $choice) {

            // If choice is an array, use content as choice.
            if (is_array($choice) && rgar($choice, 'content')) {
                $choice = $choice['content'];
            }

            // Add field choice as choice.
            $choices[] = array('label' => esc_html($choice), 'value' => esc_attr($choice));
        }

        return $choices;
    }

    /**
     * Get field from a Infusionsoft CRM module.
     *
     * @since  1.0
     * @access public
     *
     * @param string $module Module to get field from.
     * @param string $field_name Field name to retrieve.
     *
     * @uses GravityFormsInfusionsoftIntegrator::get_module_fields()
     *
     * @return array
     */
    public function get_module_field($module, $field_name)
    {

        // Get fields for module.
        $module_fields = $this->get_module_fields($module);

        // Loop through module fields.
        foreach ($module_fields as $module_field) {

            // If field label matches the field name, return field.
            if (rgar($module_field, 'label') === $field_name) {
                return $module_field;
            }
        }

        return array();
    }

    /**
     * Insert settings field before another field.
     * (Forked to allow for passing a single settings section.)
     *
     * @since  1.3.1
     * @access public
     *
     * @param string $name Field name to insert settings field after.
     * @param array $fields Settings field.
     * @param array $settings Settings section to add field to.
     *
     * @return array
     */
    public function add_field_before($name, $fields, $settings)
    {

        // Move settings into another array.
        $settings = array($settings);

        // Add field.
        $settings = parent::add_field_before($name, $fields, $settings);

        // Return the first settings section.
        return $settings[0];
    }

    // # IMPORT / EXPORT -----------------------------------------------------------------------------------------------

    /**
     * Export Systasis Gravity Forms Infusionsoft Feed Add-On feeds when exporting form.
     *
     * @since  1.0
     * @access public
     *
     * @param array $form The current Form object being exported.
     *
     * @uses   GFAddOn::get_slug()
     * @uses   GFFeedAddOn::get_feeds()
     *
     * @return array
     */
    public function export_feeds_with_form($form)
    {

        // Get feeds for form.
        $feeds = $this->get_feeds($form['id']);

        // If form does not have a feeds property, create it.
        if (!isset($form['feeds'])) {
            $form['feeds'] = array();
        }

        // Add feeds to form object. They will be removed during subsequent import.
        $form['feeds'][$this->get_slug()] = $feeds;

        return $form;
    }

    /**
     * Import Systasis Gravity Forms Infusionsoft Feed Add-On feeds when importing form.
     *
     * @since  1.0
     * @access public
     *
     * @param array $forms Imported Form objects.
     *
     * @uses   GFAPI::add_feed()
     * @uses   GFAPI::get_form()
     * @uses   GFAPI::update_form()
     */
    public function import_feeds_with_form($forms)
    {

        // Loop through each form being imported.
        foreach ($forms as $import_form) {

            // Ensure the imported form is the latest. Compensates for a bug in Gravity Forms < 2.1.1.13
            $form = \GFAPI::get_form($import_form['id']);

            // If the form does not have any Infusionsoft Add-On feeds, skip.
            if (!rgars($form, 'feeds/' . $this->get_slug())) {
                continue;
            }

            // Loop through feeds, move feeds/ form object placed there via export to meta/ form object
            foreach (rgars($form, 'feeds/' . $this->get_slug()) as $feed) {

                // Import feed.
                \GFAPI::add_feed($form['id'], $feed['meta'], $this->get_slug());
            }

            // Remove feeds/ form object placed there via export
            unset($form['feeds'][$this->get_slug()]);

            // Remove feeds property if empty.
            if (empty($form['feeds'])) {
                unset($form['feeds']);
            }

            // Update form.
            \GFAPI::update_form($form);
        }
    }

    // # HELPER FUNCTIONS ----------------------------------------------------------------------------------------------

    /**
     * Set feed creation control.
     *
     * @since  1.0
     * @access public
     *
     * @return bool
     */
    public function can_create_feed()
    {
        return current_user_can('manage_options');
    }

	public function configure_addon_message() {

		$settings_label = sprintf( __( '%s Settings', 'gravityforms' ), $this->get_short_title() );
		$settings_link  = sprintf( '<a href="%s">%s</a>', esc_url( $this->get_plugin_settings_url() ), $settings_label );
        // ' <a href="admin.php?page=gf_settings&subview=systasisgfifscrm">Gravity Forms Infusionsoft Feed Add-On Settings</a> page', );
		return sprintf( __( 'Please configure your Keap Service Account key on the %s.', SYSTASISGFIFSCRM_DOMAIN ), $settings_link );
	}

    /**
     * Enable feed duplication.
     *
     * @since  1.1.7
     * @access public
     *
     * @param int $id Feed ID requesting duplication.
     *
     * @return bool
     */
    public function can_duplicate_feed($id)
    {
        return true;
    }

    /**
     * Setup columns for feed list table.
     *
     * @since  1.0
     * @access public
     *
     * @return array
     */
    public function feed_list_columns()
    {
        return array('feedName' => esc_html__('Name', SYSTASISGFIFSCRM_DOMAIN), 'action' => esc_html__('Action', SYSTASISGFIFSCRM_DOMAIN));
    }

    /**
     * Get value for action feed list column.
     *
     * @since  1.0
     * @access public
     *
     * @param  array $feed Feed for current table row.
     *
     * @return string
     */
    public function get_column_value_action($feed)
    {

        // Display contact action string.
        if (rgars($feed, 'meta/action') == 'contact') {
            return esc_html__('Create a New Contact', SYSTASISGFIFSCRM_DOMAIN);
        }

        return esc_html__('No Action', SYSTASISGFIFSCRM_DOMAIN);
    }

    /**
     * Process the Infusionsoft CRM feed.
     *
     * @since  1.0
     * @access public
     *
     * @param  array $feed Feed object.
     * @param  array $entry Entry object.
     * @param  array $form Form object.
     *
     * @uses GFAddOn::log_debug()
     * @uses GFAddOn::log_error()
     * @uses GravityFormsInfusionsoftIntegrator::create_contact()
     * @uses GravityFormsInfusionsoftIntegrator::initialize_api()
     * @uses GravityFormsInfusionsoftIntegrator::upload_attachments()
     */
    public function process_feed($feed, $entry, $form)
    {
        // If API instance is not initialized, exit.
        $this->initialize_api();

        // Create contact.
        switch (rgars($feed, 'meta/action')) {
            case 'contact':
                // Get contact ID.
                $contact_id = $this->create_contact($feed, $entry, $form);

                if (rgblank($contact_id)) {
                    return;
                }

                // Upload attachments
                $this->upload_attachments($contact_id, $feed, $entry, $form);

                // Affiliate referral tracking
                $affiliate_code = $this->track_affiliate_referral($contact_id, $feed, $entry, $form);

                /**
                 * There will be two different API goal metadata fields. One conditional, the other unconditional.
                 * The API goal name is required when the conditional API goal is enabled
                 */

                /*
                 * Get and process any conditional API goals
                 *
                 * Get all conditional goals
     			 * @since  2.0
                 */
                $r0 = rgars($feed, 'meta/' . $this->_types[0] . '_logic_object');
                $goals[] = array(
                    'enabled' => rgars($feed, 'meta/' . $this->_types[0] . '_conditional_logic'), 'conditionalLogic' => array_shift($r0), 'name' => rgars($feed, 'meta/' . $this->_types[0] . '_name')
                );

                /*
                * Process the conditional goal list
                */
                foreach ($goals as $goal) {
                    if ($this->is_conditional_api_goal_condition_met($form, $entry, $goal)) {
                        $api_goal_name = rgars($goal, self::NAME);
                        $this->achieve_goal($feed, $entry, $form, $api_goal_name, $contact_id, true);
                    } else {
                        $this->log_debug(__METHOD__ . '(): condition not met for contact id: ' . $contact_id);
                    }
                }

                /*
                 * Get and process any unconditional API goal
                 */
                $unconditionalApiGoal = rgars($feed, 'meta/' . self::FEED_UNCONDITIONAL_API_GOAL);

                if (!empty($unconditionalApiGoal)) {
                    $this->achieve_goal($feed, $entry, $form, $unconditionalApiGoal, $contact_id, false);
                } else {
                    $this->log_debug(__METHOD__ . '(): No unconditional goal for contact id: ' . $contact_id);
                }

                break;
        }
    }

    /**
     * Create a new Infusionsoft contact from a feed.
     *
     * @since  1.0
     * @access public
     *
     * @param array $feed Feed object.
     * @param array $entry Entry object.
     * @param array $form Form object.
     *
     * @uses GFAddOn::get_field_map_fields()
     * @uses GFAddOn::get_field_value()
     * @uses GFAddOn::log_debug()
     * @uses GFAddOn::log_error()
     * @uses GFCommon::replace_variables()
     * @uses GFFeedAddOn::add_feed_error()
     * @uses GravityFormsInfusionsoftIntegrator::get_dynamic_field_map_fields()
     * @uses Infusionsoft_ContactService::addWithDupCheck()
     * @uses Infusionsoft_ContactService::add()
     *
     * @return int|null $contact_id
     */
    public function create_contact($feed, $entry, $form)
    {
        /*
         * Attempt to map the GF type to Infusionsoft.
         * Called by get_field_value()
         * Does not handle Yes/No fixup since that field type appears as GF_Field_Radio
         */
        add_filter('gform_addon_field_value', function ($field_value, $form, $entry, $field_id, $slug) {
            if (!rgblank($field_value)) {
                if ($this->_slug == $slug) {
                    $field = \RGFormsModel::get_field($form, $field_id);
                    switch (true) {
                        case (is_a($field, "GF_Field_Date")):
                            $field_value = \Infusionsoft_ContactService::apiDate($field_value);
                            break;
                    }
                }
            }
            return $field_value;
        }, 10, 5);

        // Initialize contact object.
        $r0 = rgars($feed, 'meta/' . self::FEED_LEAD_SOURCE);
        $contact["Leadsource"] = (false == stristr($r0, 'gf_custom')) ? $r0 : rgars($feed, 'meta/' . self::FEED_LEAD_SOURCE . '_custom');

        // Get standard and custom fields.
        $standard_fields = $this->get_field_map_fields($feed, 'contactStandardFields');
        $custom_fields = $this->get_dynamic_field_map_fields($feed, 'contactCustomFields');

        // Merge standard and custom fields arrays.
        $mapped_fields = array_merge($standard_fields, $custom_fields);

        /*
         * Convert CRM field array to keyed array for fast lookup.
         * Used to fixup XMLRPC for Infusionsoft Yes/No goodness.
         */
        $result = $this->get_module_fields("Contacts");
        $module_fields = [];
        foreach ($result as $key => $value) {
            $module_fields[rgar($result[$key], "name")] = $value;
        }

        // Loop through mapped fields.
        foreach ($mapped_fields as $field_name => $field_id) {
            /*
             * Get field value.
             * Calls filter 'gform_addon_field_value' established above to fixup any date fields.
             */
            $field_value = $this->get_field_value($form, $entry, $field_id);

            // If field value is empty, skip it.
            if (rgblank($field_value)) {
                continue;
            }

            // Typecast the value for XMLRPC goodness
            switch (rgar($module_fields[$field_name], "type")) {
                case "Yes/No":
                    $field_value = (int) $field_value;
                    break;
            }

            // Add mapped field to contact object.
            $contact[$field_name] = $field_value;
        }

        // Process any field concatenation
        $combineFieldsEnabled = (1 == rgars($feed, 'meta/contactCombineFields'));
        if ($combineFieldsEnabled) {
            $dest = rgars($feed, 'meta/' . $this->_types[1] . '_' . self::COMBINE_FIELDS_FIELDMAP);
            $cssClass = rgars($feed, 'meta/' . $this->_types[1] . '_' . self::COMBINE_FIELDS_CSSCLASS);
            $keys = array_filter(array_map(function ($field) use ($cssClass) {
                return (stristr($field['cssClass'], $cssClass)) ? $field['id'] : false;
            }, $form['fields']));
            $values = array_filter(array_map(function ($field_id) use ($entry, $form) {
                return $this->get_field_value($form, $entry, $field_id);
            }, $keys));
            $contact[$dest] = count($values) > 0 ? implode(", ", $values) : " ";
        }

        // Create new contact
        $contact_id = null;
        try {
            if (1 == rgars($feed, 'meta/' . self::FEED_DUPLICATE_CHECK)) {
                $duplicateCheckType = rgars($feed, 'meta/' . self::FEED_CHECK_TYPE);
                $contact_id = \Infusionsoft_ContactService::addWithDupCheck($contact, $duplicateCheckType);
            } else {
                $contact_id = \Infusionsoft_ContactService::add($contact);
            }
            $this->log_debug(__METHOD__ . "(): Contact Id " . $contact_id . "\nContact\n" . print_r($contact, true) . "mapped fields\n" . print_r($mapped_fields, true));
        } catch (\Exception $e) {
            // Log that contact could not be created.
            $this->add_feed_error('Could not create contact: ' . esc_html($e->getMessage()), $feed, $entry, $form);
            $this->log_error(print_r(array('mapped fields' => $mapped_fields, 'contact' => $contact), true));
            $contact_id = null;
        }

        gform_update_meta($entry['id'], 'contact_id', $contact_id, $form['id']);

        // Maybe mark email as marketable
        if (1 == rgars($feed, 'meta/' . self::FEED_CONTACT_MARKETABLE)) {
            try {
                if (isset($contact["Email"])) {
                    $this->optin($contact["Email"]);
                }
            } catch (\Exception $e) {
                $this->add_feed_error(esc_html($e->getMessage()), $feed, $entry, $form);
            }
        }
        return $contact_id;
    }

    public function optin($email = null)
    {
        /*
         * Attempt email opt-in
         */
        if (isset($email)) {
            try {
                $title = $this->_short_title;
                \Infusionsoft_EmailService::optIn($email, $title);
                $this->log_debug(__METHOD__ . '(): ' . $email . ' flagged marketable.');
            } catch (\Exception $e) {
                throw new \Exception("Could not flag email marketable");
            }
        }
    }

    /**
     * Upload attachments from a feed.
     *
     * @since  1.0
     * @access public
     *
     * @param int $contact_id Contact ID to add attachment.
     * @param array $feed Feed object.
     * @param array $entry Entry object.
     * @param array $form Form object.
     *
     * @uses GFAddOn::get_field_value()
     * @uses GFAddOn::is_json()
     * @uses GFAddOn::log_debug()
     * @uses GFAddOn::log_error()
     * @uses Infusionsoft_FileService::uploadFile()
     */
    public function upload_attachments($contact_id, $feed, $entry, $form)
    {

        $attachment_list = rgars($feed, 'meta/' . self::FEED_ATTACHMENTS_LIST);

        // If no file upload fields are selected as attachments, exit.
        if (!is_array($attachment_list)) {
            return;
        }

        // Initialize array to store file upload fields.
        $file_fields = array();

        // Loop through attachments settings field choices.
        foreach ($attachment_list as $field_id => $value) {

            // If this field is enabled for attachments, add it to the file upload fields array.
            if ('1' == $value) {
                $file_fields[] = $field_id;
            }
        }

        // If no file upload fields are defined, exit.
        if (empty($file_fields)) {
            return;
        }

        // Loop through file upload fields.
        foreach ($file_fields as $file_field) {

            // Get files for field.
            $files = $this->get_field_value($form, $entry, $file_field);

            // If no files were uploaded for this field, skip it.
            if (empty($files)) {
                continue;
            }

            // Convert files value to array.
            $files = $this->is_json($files) ? json_decode($files, true) : explode(' , ', $files);

            // Loop through the files.
            foreach ($files as $i => &$file) {

                // Convert file URL to local path.
                $file_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $file);

                // If the file is larger than the maximum allowed by Infusionsoft CRM, skip it.
                if (filesize($file_path) > 10000000) {
                    $this->log_error(__METHOD__ . '(): Unable to upload file "' . basename($file_path) . '" because it is larger than 10MB.');
                    continue;
                }

                // Upload file.
                try {
                    $filespec = basename($file_path);
                    \Infusionsoft_FileService::uploadFile($contact_id, basename($file_path), base64_encode(file_get_contents($file_path)));
                    \GFAPI::add_note($entry['id'], 0 /* user id */, '' /* user name */, "Uploaded \"{$filespec}\"");
                    $this->log_debug(__METHOD__ . '(): File "' . basename($file_path) . '" has been uploaded to contact #' . $contact_id . '.');
                } catch (\Exception $e) {
                    $this->log_error(__METHOD__ . '(): File "' . basename($file_path) . '" could not be uploaded; ' . $e->getMessage());
                }
            }
        }
    }

    /**
     *
     * @abstract Evaluate Conditional Logic for an API Goal block
     * @since  2.0
     *
     * @param array GF Feed object
     * @param array GF Form object
     * @param array API Goal block
     */
    public function is_conditional_api_goal_condition_met($form, $entry, $goal = null)
    {
        if (false == rgars($goal, "enabled")) {
            // The goal is not enabled so we handle it as if the rules are not met.
            return FALSE;
        }

        return \GFCommon::evaluate_conditional_logic($goal['conditionalLogic'], $form, $entry);
    }

    /**
     * Start a contact in an Infusionsoft campaign
     *
     * @since  1.0
     * @access public
     *
     * @param array $feed Feed object.
     * @param array $entry Entry object.
     * @param array $form Form object.
     * @param string $api_goal Infusionsoft API goal.
     * @param integer $contact_id Infusionsoft contact id.
     *
     * @uses GFAddOn::add_feed_error()
     * @uses GFAddOn:log_debug()
     * @uses Infusionsoft_FunnelService::achieveGoal()
     */
    public function achieve_goal($feed, $entry, $form, $api_goal = "", $contact_id = 0, $conditional = false)
    {
        $integration = $this->get_plugin_setting(self::INFUSIONSOFT_SDK_APP_NAME);

        if (!empty($api_goal)) {
            try {
                $result = \Infusionsoft_FunnelService::achieveGoal($integration, $api_goal, $contact_id);
                if (0 < strlen($result[0]["msg"])) {
                    throw new \Exception($result[0]["msg"]);
                }
                $message = sprintf("Achieved%sAPI goal \"%s\"", ($conditional ? " conditional " : " "), $api_goal);
                \GFAPI::add_note($entry['id'], 0 /* user id */, '' /* user name */, $message);
                $this->log_debug(__METHOD__ . '(): API Goal ' . $api_goal . ' set for contact id:' . $contact_id);
            } catch (\Exception $e) {
                // Log that contact could not be started in campaign.
                $this->add_feed_error("Could not achieve goal \"$api_goal\"\n" . esc_html($e->getMessage()), $feed, $entry, $form);
            }
        } else {
            $this->log_debug(__METHOD__ . '(): No API goal for contact id: ' . $contact_id);
        }
    }

    /**
     * Create an affiliate tracking record
     *
     * @param integer $contact_id
     * @param array $feed Feed object.
     * @param array $entry Entry object.
     * @param array $form Form object.
     *
     * @uses AffiliateField::getAffiliateId()
     * @uses Infusionsoft_DataService::apiDateFormat
     *
     * @return NULL|string|mixed
     */
    public function track_affiliate_referral($contact_id, $feed, $entry, $form)
    {
        $affiliate_id = $affiliate_code = null;

        foreach ($form['fields'] as $field) {
            if ($field['type'] == 'AffiliateCode') {
                $affiliate_code = rgar($entry, $field->id);
            }
        }

        if (!empty($affiliate_code)) {
            try {
                $affiliate_id = AffiliateField::getAffiliateId($affiliate_code);
            } catch (\Exception $e) {
                $this->add_feed_error('Could not track affiliate referral: ' . esc_html($e->getMessage()), $feed, $entry, $form);
            }

            if (!empty($affiliate_id)) {
                $referral = new \Infusionsoft_Referral();
                $referral->ContactId = $contact_id;
                $referral->AffiliateId = $affiliate_id;
                $referral->DateSet = date(\Infusionsoft_DataService::apiDateFormat);
                $referral->Type = self::REFERRAL_TYPE_MANUAL;
                $referral->IPAddress = rgar($_SERVER, "REMOTE_ADDR");
                $referral->Source = $this->_short_title;

                try {
                    $referral->save();
                    $this->log_debug(__METHOD__ . '(): Affiliate referral for ' . $affiliate_code . ' created.');
                } catch (\Exception $e) {
                    $this->add_feed_error('Could not track affiliate referral: ' . esc_html($e->getMessage()), $feed, $entry, $form);
                }
            }
        }
        return $affiliate_code;
    }

    /**
     * Sort module fields alphabeically by label.
     *
     * @since  1.0
     * @access public
     *
     * @param  array $a First array item.
     * @param  array $b Second array item.
     *
     * @return int
     */
    public function sort_module_fields_by_label($a, $b)
    {
        return strcmp($a['label'], $b['label']);
    }
}
