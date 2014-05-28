<?php

/**
 * SCAP - Shutterstock Custom Affiliate Plugin.
 *
 * @package   SCAP
 * @author    Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 * @license   GPL-2.0+
 * @link      http://arifinbinmatin.com
 * @copyright 2014 Md. Arifin Ibne Matin

/**
 * Plugin Admin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * @package SCAP
 * @author  Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 */
class SCAP_Admin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {



        /*
         * Call $plugin_slug from public plugin class.		
         */
        $plugin = SCAP::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        // Add the options page and menu item.
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename(plugin_dir_path(realpath(dirname(__FILE__))) . $this->plugin_slug . '.php');
        add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));

        /*
         * Define custom functionalities Here.
         */
        // Initialize Setting Option
        add_action('admin_init', array($this, 'initialize_setting_option'));
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         */
        $this->plugin_screen_hook_suffix = add_menu_page(
                __('Shutterstock Custom Affiliate Plugin', $this->plugin_slug), __('SCAP', $this->plugin_slug), 'manage_options', $this->plugin_slug, array($this, 'display_plugin_admin_page'), plugins_url('assets/img/icon.png', __FILE__), 81
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        include_once( 'views/admin.php' );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links) {

        return array_merge(
                array(
            'settings' => '<a href="' . admin_url('admin.php?page=' . $this->plugin_slug) . '">' . __('Settings', $this->plugin_slug) . '</a>'
                ), $links
        );
    }

    /* ------------------------------------------------------------------------ *
     * The following functionalities are for the setting page Using Wordpress   *
     * Setting API                                                              *
     * ------------------------------------------------------------------------ */

    /**
     * Initialize our settings options
     *
     * @since    1.0.0
     */
    public function initialize_setting_option() {

        // If the theme options don't exist, create them.
        if (false == get_option('scap_options')) {
            add_option('scap_options');
        } // end if
        // First We register Our Sections. Every Field Must be under a Sections
        $this->register_option_sections();

        // Next, we'll register the fields for saving our API and Affiliate Data
        $this->register_api_fields();
        
        // Finally, we register the fields with WordPress
        register_setting(
                'scap_options', 'scap_options'
        );
    }

    /**
     * Register all the setting sections needed.
     * 
     * @since 1.0.0
     */
    private function register_option_sections() {
        
        //API And Affiliate Information Section
        add_settings_section(
                'scap_api_section', // ID used to identify this section and with which to register options
                __('Shutterstock API and Affiliate Settings', $this->plugin_slug), // Title to be displayed on the administration page
                array($this, 'scap_api_section_callback'), // Callback used to render the description of the section
                $this->plugin_slug // Page on which to add this section of options
        );
    }
    /**
     * Register the API fields
     * 
     * @since 1.0.0
     */
    private function register_api_fields() {
        //API Username Field
        add_settings_field(
                'scap_api_user', // ID used to identify the field throughout the plugin
                __('API Username', $this->plugin_slug), // The label to the left of the option interface element
                array($this, 'scap_api_user_callback'), // The name of the function responsible for rendering the option interface
                $this->plugin_slug, // The page on which this option will be displayed
                'scap_api_section' // The name of the section to which this field belongs               
        );
        //API Key Field
        add_settings_field(
                'scap_api_key', // ID used to identify the field throughout the plugin
                __('API Key', $this->plugin_slug), // The label to the left of the option interface element
                array($this, 'scap_api_key_callback'), // The name of the function responsible for rendering the option interface
                $this->plugin_slug, // The page on which this option will be displayed
                'scap_api_section' // The name of the section to which this field belongs               
        );
        //Affiliate ID field
        add_settings_field(
                'scap_api_affiliateID', // ID used to identify the field throughout the plugin
                __('Affiliate ID', $this->plugin_slug), // The label to the left of the option interface element
                array($this, 'scap_api_affiliateID_callback'), // The name of the function responsible for rendering the option interface
                $this->plugin_slug, // The page on which this option will be displayed
                'scap_api_section' // The name of the section to which this field belongs               
        );
    }

    /* ------------------------------------------------------------------------ *
     * Section Callbacks
     * ------------------------------------------------------------------------ */

    /**
     * A simple description for the Input Section.
     */
    public function scap_api_section_callback() {
        echo '<p>' . __('Provide Shutterstock API and Affiliate Informations', $this->plugin_slug) . '</p>';
    }

    /**
     * A simple description for the Input Section.
     */
    public function scap_visual_section_callback() {
        echo '<p>' . __('Various setting options related to our visual output will go here ! ', $this->plugin_slug) . '</p>';
    }

    /* ------------------------------------------------------------------------ *
     * Field Callbacks
     * ------------------------------------------------------------------------ */

    /**
     * This function renders the interface elements for API Username Option
     */
    public function scap_api_user_callback($args) {

        // First, we read the options collection
        $options = get_option('scap_options');

        $username = '';
        if (isset($options['scap_api_user'])) {
            $username = trim($options['scap_api_user']);
        } // end if
        
        $html = '<input type="text" id="scap_api_user" name="scap_options[scap_api_user]" value="' . $username . '" />';
        $html .= '<label for="scap_api_user">&nbsp;' . $args[0] . '</label>';

        echo $html;
    }

    /**
     * This function renders the interface elements for API Key Option.
     */
    public function scap_api_key_callback($args) {

        // First, we read the options collection
        $options = get_option('scap_options');

        $key = '';
        if (isset($options['scap_api_key'])) {
            $key = trim($options['scap_api_key']);
        } // end if
        
        $html = '<input type="text" size="50" id="scap_api_key" name="scap_options[scap_api_key]" value="' . $key . '" />';
        $html .= '<label for="scap_api_key">&nbsp;' . $args[0] . '</label>';

        echo $html;
    }

    /**
     * This function renders the interface elements for Affiliated ID Option.
     */
    public function scap_api_affiliateID_callback($args) {

        // First, we read the options collection
        $options = get_option('scap_options');

        $affiliateID = '';
        if (isset($options['scap_api_affiliateID'])) {
            $affiliateID = trim($options['scap_api_affiliateID']);
        } // end if
        $html = '<input type="text" id="scap_api_affiliateID" name="scap_options[scap_api_affiliateID]" value="' . $affiliateID . '" />';
        $html .= '<label for="scap_api_affiliateID">&nbsp;' . $args[0] . '</label>';

        echo $html;
    }

}