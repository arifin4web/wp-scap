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
 * Shutterstock API class. This class is used to work with the
 * shutterstock to get images using Shutterstock API.
 *
 * @package SCAP
 * @author  Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 */
class Shutterstock_API {

    /**
     * Curl Connection
     * 
     * @since 1.0.0
     * 
     * @var string 
     */
    protected $ch;

    /**
     * Shutterstock API Username
     * 
     * @since 1.0.0
     * 
     * @var string 
     */
    protected $username;

    /**
     * Shutterstock API Key
     * 
     * @since 1.0.0
     * 
     * @var string 
     */
    protected $key;

    /**
     * Initialize the Class by storing the API Username and key.
     * 
     * @since 1.0.0
     * 
     * @param string $username Shuttestock API Username
     * @param string $key Shuttestock API Key
     */
    public function __construct($username, $key) {
        $this->username = $username;
        $this->key = $key;
        $this->ch = null;
    }
    
    public function connection_reset(){
        if (!is_null($this->ch)){
            $this->ch = null;
        }
    }

    /**
     * Function to get the Curl Response.
     * 
     * @since 1.0.0
     * 
     * @param string $url API query sting
     * 
     * @return mixed Curl Output
     */
    protected function getCurl($url) {

        if (is_null($this->ch)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->key);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $this->ch = $ch;
        }

        curl_setopt($this->ch, CURLOPT_URL, $url);

        return $this->ch;
    }

    /**
     * This function is to construct the API Query String and get the response back.
     * 
     * @since 1.0.0
     * 
     * @param type $search_terms Keyword to fetch Content.
     * @param type $type Content Type
     * 
     * @return object json decoded response object
     */
    public function search($search_terms, $num = 8, $type = 'images') {

        $search_terms_for_url = preg_replace('/ /', '+', $search_terms);

        $url = 'http://api.shutterstock.com/' . $type . '/search.json?results_per_page=' . $num . '&searchterm=' . $search_terms_for_url;

        $ch = $this->getCurl($url);
        $json = curl_exec($ch);     // Our response
        $info = curl_getinfo($ch);  // Use this if you need to see the Curl request info.

        curl_close($ch);

        return json_decode($json);
    }

    /**
     * This function is to construct the API Query String and get the response back.
     * 
     * @since 1.0.0
     * 
     * @param type $search_terms Keyword to fetch Content.
     * @param type $type Content Type     * 
     * @return object json decoded response object
     */
    public function test() {

        $url = 'http://api.shutterstock.com/test/echo.json?test=valid';

        $ch = $this->getCurl($url);
        $json = curl_exec($ch);     // Our response
        $info = curl_getinfo($ch);  // Use this if you need to see the Curl request info
        curl_close($ch);

        return json_decode($json);
    }

}