<?php

/**
 * SCAP - Shutterstock Custom Affiliate Plugin.
 *
 * @package   SCAP
 * @author    Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 * @license   GPL-2.0+
 * @link      http://arifinbinmatin.com
 * @copyright 2014 Md. Arifin Ibne Matin
 */

/**
 * Plugin class. This class is used to work with the
 * public-facing side of the WordPress site.
 *
 * @package SCAP
 * @author  Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 */
class SCAP {

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */
    const VERSION = '1.0';

    /**
     * Unique identifier for your plugin.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_slug = 'scap';

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     1.0.0
     */
    private function __construct() {

        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));

        // Activate plugin when new blog is added
        add_action('wpmu_new_blog', array($this, 'activate_new_site'));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        /*
         * Define custom functionalities Here.
         */

        // Add our Shortcode
        add_shortcode('scap', array($this, 'scap_shortcode'));
        // Enable shortcodes in widgets
        add_filter('widget_text', 'do_shortcode');
    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug() {
        return $this->plugin_slug;
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
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate($network_wide) {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Deactivate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       deactivated on an individual blog.
     */
    public static function deactivate($network_wide) {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since    1.0.0
     *
     * @param    int    $blog_id    ID of the new blog.
     */
    public function activate_new_site($blog_id) {

        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since    1.0.0
     *
     * @return   array|false    The blog ids, false if no matches.
     */
    private static function get_blog_ids() {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate() {
        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate() {
        if (get_option('scap_options')) {
            delete_option("scap_options");
        }
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__), array(), self::VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('assets/js/public.js', __FILE__), array('jquery'), self::VERSION);
    }

    /**
     * Will output shutterstock images with affiliate link in our custom visual
     * format. usages example : [scap keyword="nature"]    * 
     * 
     * @since 1.0.0
     * 
     * @param mixed $attr shotrcode attributes
     */
    public function scap_shortcode($attr) {

        
        $html = '';
        
        if(empty($attr['keyword'])){
            // If empty Get it from auto keyword class
            $keyword =  $this->scap_auto_keyword();
        }else{            
            // Get our keyword from Shortcode Attribute
            $keyword = $attr['keyword'];
        }
        // Get the Images through API Class
        $images = $this->get_scap_images($keyword);

        if ($images) {
            if (is_wp_error($images)) {
                // Error Message if somethin Is Wrong
                $html .= $images->get_error_message();
            } else {
                // Output Our Desired Content
                $html .= $this->get_scap_output($images);                
            }
        }
        
        return $html;
    }
    
    /**
     * This function will get the Keyword usig SCAP_Autokey Class
     * 
     * @since 1.0.1
     * 
     * @return string Best Suited Keyword
     */
    private function scap_auto_keyword(){
        
        $key = new SCAP_Autokey();
        return $key->get_keyword();
        
    }
    
    /**
     * This Function Construct our desired design/orientationand return the HTML
     * based on supplied shutterstock Image Object Array 
     * 
     * @since 1.0.1
     * 
     * @param type $images     * 
     * @return string
     */
    private function get_scap_output($images) {
        
        $html = '';
        $html .= '<div class="scap_container">';
        foreach ($images->results as $image) {
            $description = $image->safe_description;
            $thumb = $image->preview->url;
            //Construct The Affiliate link from the Image url
            $aff_link = $this->get_scap_affiliate_link($image->web_url);
            // Create Our Out put in our
            $html .= '<div class="img_boundary" style="width: 90px; height: 90px">';
            $html .= '<a href="' . $aff_link . '" target="_blank">';
            $html .= '<img class="image_to_crop" src="' . $thumb . '" alt="' . htmlspecialchars($description) . '"/>';
            $html .= '</a>';
            $html .= '</div>';
        }
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Construct the Affiliate Link from the resourse/Image Url
     * 
     * @since 1.0.0
     * 
     * @param string $url Image/resourse URL     * 
     * @return string Affiliated Link
     */
    private function get_scap_affiliate_link($url) {

        // Get Affiliate ID
        $options = get_option('scap_options');
        $aff_id = $options['scap_api_affiliateID'];

        //Encode the URL 
        $url = urlencode($url);

        // Affiliate Link
        $aff_link = "http://shutterstock.7eer.net/c/" . $aff_id . "/43068/1305?u=" . $url;

        return $aff_link;
    }

    /**
     * This function is used to get Shutterstock Images Using out ShutterstockAPI
     * 
     * @since 1.0.0
     * 
     * @param type $keyword the search terms     * 
     * @return object The Image object array On successful Operation Otherwise WP_Error object
     */
    private function get_scap_images($keyword) {

        $options = get_option('scap_options');



        if (!isset($options['scap_api_user']) || !isset($options['scap_api_key']) || !isset($options['scap_api_affiliateID'])) {
            return new WP_Error(
                    'falseapi', __("API Username, Key or Affiliate ID is not Properly Set", $this->plugin_slug)
            );
        } else {
            $username = $options['scap_api_user'];
            $key = $options['scap_api_key'];


            $shutterstock = new Shutterstock_API($username, $key);

            $images = $shutterstock->search($keyword);

            return $images;
        }
    }

}