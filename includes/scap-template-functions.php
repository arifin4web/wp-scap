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
 * Create a Template function providing the Shutterstock Images. This will help 
 * to Integrate Shutterstock images along with affiliate Link to Custom Designs.
 *
 * @package SCAP
 * @author  Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 */
class SCAP_Template_Function {

    /**
     * Options for getting the shutterstock Images Currently There are not much
     * options But hope to include much in Future.
     * 
     * @since 1.0
     * 
     * @var array  Arguments to get images from shutterstock.
     */
    private $args;
    
    /**
     * Plugin Slug 
     * 
     * @since 1.0
     * 
     * @var string Plugin Slug 
     */
    private $plugin_slug;

    /**
     * Set the Keyword if not present in the arguments. Also set the plugin slug.
     * 
     * @since 1.0
     * 
     * @param array $arguments Arguments to get images from shutterstock.
     */
    public function __construct($arguments) {
        if (empty($arguments['keyword'])) {
            $key = new SCAP_Autokey();
            $arguments['keyword'] = $key->get_keyword();
        }

        $this->args = $arguments;

        $this->set_plugin_slug();
    }

    /**
     * Set Plugin Slug
     */
    private function set_plugin_slug() {
        $plugin = SCAP::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
    }

    /**
     * Public Facing function to return Image Data. Will Works as a CTRL.
     * 
     * @since 1.0
     * 
     * @return mixed Array of Image object affiliated link Included. WP Error 
     *               Object otherwise.
     */
    public function return_image_data() {
        
        $images = $this->get_images();
        return $this->add_affiliate_link($images);
    }

    /**
     * This wil add an exta field populate with affiliated links to each image object.
     * 
     * @since 1.0
     * 
     * @param mixed $images Image Object array (Usually the return of  $this->get_images() function)
     * @return mixed Array of Image object including affiliated link.
     */
    private function add_affiliate_link($images) {
        if (!is_wp_error($images)) {
            $scap_images = array();     // Array of shutterstock Image objects
            foreach ($images->results as $image) {
                $scap_image = new stdClass();
                $scap_image->image = $image;
                // Get Affiliate ID
                $options = get_option('scap_options');
                $aff_id = $options['scap_api_affiliateID'];

                //Encode the URL 
                $url = urlencode($image->web_url);

                // Affiliate Link
                $aff_link = "http://shutterstock.7eer.net/c/" . $aff_id . "/43068/1305?u=" . $url;
                $scap_image->aff_link = $aff_link;
                $scap_images[] = $scap_image;
            }
            return $scap_images;
        }else{
            return $images;
        }        
    }

    /**
     * This function will get the images using Shutterstock_API Class.
     * 
     * @since 1.0
     * 
     * @return mixed On Successfull operation return an array of Shutterstock 
     *               Image Object (Response from shutterstock API)
     *               Otherwise WP Error Object.
     */
    private function get_images() {

        //Validation
        $options = get_option('scap_options');

        if (empty($options['scap_api_user']) || empty($options['scap_api_key']) || empty($options['scap_api_affiliateID'])) {
            return new WP_Error(
                    'nodata', __("API Username, Key or Affiliate ID is not Properly Set", $this->plugin_slug)
            );
        }
        $username = $options['scap_api_user'];
        $key = $options['scap_api_key'];
        $shutterstock = new Shutterstock_API($username, $key);

        if ('valid' !== $shutterstock->test()->test) {
            return new WP_Error(
                    'invalidapi', __("Provided Username And API Key is not valid", $this->plugin_slug)
            );
        }
        // Reset conncection to avoid curl error.
        $shutterstock->connection_reset();
        // Everything is OK Now Getting Images
        $images = $shutterstock->search($this->args['keyword'], $this->args['number']);
        //Return Data
        return $images;
    }

}

/**
 * This function is for advanced theme Integration. This function will be 
 * accecible from the template files. Return The Shutterstock Image Objects Array
 * along with the affiliated Link.
 * 
 * @since 1.0.0
 * 
 * @param array $args Array of Arguments
 * 
 * @return mixed On Successfull operation return an array consist the 
 *               Affiliate link and Shutterstock Image Object Otherwise WP Error
 *               Object.
 */
function scap_template_func($args) {
    $default = array(
        'keyword' => '',
        'number' => 8
    );

    $arguments = wp_parse_args($args, $default);

    $scap_template_func = new SCAP_Template_Function($arguments);

    $images = $scap_template_func->return_image_data();

    return $images;
}