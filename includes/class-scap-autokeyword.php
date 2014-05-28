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
 * Auto Keyword Generation Calss for Shutterstock Custom Affiliate Plugin.
 * 
 * This class will generate the best suitable keyword in a page to use it to 
 * get appropiate and relevant content for Shutterstock Custom Affiliate plugin
 * automatically. 
 * 
 * This class is specific to Wordpress therefore cannot be used outside wordpress.
 * 
 * @since 1.0 
 *
 * @package SCAP
 * @author  Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 */
class SCAP_Autokey {

    /**
     * Selected Keyword
     * 
     * @since 1.0.1
     * 
     * @var string 
     */
    private $keyword;

    /**
     * Initialize the Class.
     * 
     * @since 1.0.1
     */
    public function __construct() {
        $this->set_keyword();
    }

    /**
     * Function to serve Keyword.
     * 
     * @since 1.0.1
     * 
     * @return string Selected Keyword
     */
    public function get_keyword() {
        return $this->keyword;
    }

    /**
     * This is the main controller function to execute the Algorithm to generate
     * Auto keyword related to the page Content
     * 
     * @since 1.0.1
     * @return boolean TRUE
     */
    private function set_keyword() {
        
        /*******
         * Check for the Single Post Then select Keyword from among its own terms.
         *******/
        $object = get_queried_object();
        if (isset($object->post_type)) {
            $object_id = get_queried_object_id();   //Post ID
            $object_keywords = $this->get_object_key($object_id);

            if ($object_keywords) {
                usort($object_keywords, function($a, $b) {
                    return $a['count'] - $b['count'];
                });
                $object_keywords_r = array_reverse($object_keywords);
                $this->keyword = strtolower($object_keywords_r[0]['name']);
                return true;
            }
        }

        /*******
         * Else Check the URL to get Some Relative Keywor to Compare
         *******/
        $url_keys = $this->get_uri_key();   //GET Keyword By parsing URL
        $tags_key = $this->get_tag_key();   // GET ALL the Terms used
        // Check Our URL Key in the List of Terms.
        foreach ($url_keys as $url_key) {

            if ($this->search_in_tags($url_key, $tags_key)) {
                // if Found set as Keyword and return
                $this->keyword = strtolower($url_key);
                return true;
            }
        }

        /********
         * If Not Found by previous two, Select a Random Keyword From Mostly 
         * Used Keywords in the Whole sites
         *********/
        
        //IF First one is not working get top keywords        
        // Sort our Array For Count Number
        usort($tags_key, function($a, $b) {
            return $a['count'] - $b['count'];
        });
        // Make Desending
        $rtags_key = array_reverse($tags_key);
        // Select Best 10
        $best_tag_keys = array_slice($rtags_key, 0, 10);
        // Choose One Random 
        $rand_key = array_rand($best_tag_keys);
        // Set as Our Keyword
        $this->keyword = strtolower($best_tag_keys[$rand_key]['name']);

        return true;
    }

    /**
     * Custom Array Search Function To check for $needle in Multidimentonal $haystack
     * 
     * @since 1.0.1
     * 
     * @param string $needle Serch Term
     * @param array $haystack MultiDimentional Array
     * 
     * @return boolean
     */
    private function search_in_tags($needle, $haystack) {

        foreach ($haystack as $item) {
            if (strtolower($item['name']) === strtolower($needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * This function will provide possible keywords from URL
     * 
     * @since 1.0.1
     * 
     * @return mixed Array Containig the keywords
     */
    private function get_uri_key() {

        $path = $_SERVER['REQUEST_URI'];
        $preg = preg_split('/([^a-zA-Z0-9])/', $path);

        $url_vars = array();

        foreach ($preg as $key) {

            if ("" !== $key) {

                $url_vars[] = $key;
            }
        }

        return $url_vars;
    }

    /**
     * This function will provide possible keywords from a post/object Terms
     * 
     * @since 1.0.1
     * 
     * @param int $object_id The post/object id 
     * 
     * @return mixed Array Containig the keywords
     */
    private function get_object_key($object_id) {

        $all_taxonomies = get_taxonomies('', 'names');

        if (in_array('post_tag', $all_taxonomies)) {
            $desired = 'post_tag';
        } else if (in_array('category', $all_taxonomies)) {
            $desired = 'category';
        } else {
            $desired = null;
        }

        $tag_keywords = array();
        $index = 0;

        if (isset($desired)) {
            $tax_terms = get_the_terms($object_id, $desired);
            foreach ($tax_terms as $term) {
                if ('uncategorized' !== $term->slug) {
                    $tag_keywords[$index]['name'] = $term->slug;
                    $tag_keywords[$index]['count'] = $term->count;
                    $index++;
                }
            }
        } else {
            foreach ($all_taxonomies as $taxonomy) {
                if ($taxonomy !== 'post_format' && $taxonomy !== 'link_category' && $taxonomy !== 'nav_menu') {
                    $tax_terms = get_the_terms($object_id, $taxonomy);
                    foreach ($tax_terms as $term) {
                        if ('uncategorized' !== $term->slug) {
                            $tag_keywords[$index]['name'] = $term->slug;
                            $tag_keywords[$index]['count'] = $term->count;
                            $index++;
                        }
                    }
                }
            }
        }
        return $tag_keywords;
    }

    /**
     * This function will provide possible keywords from Used Tags
     * 
     * @since 1.0.1
     * 
     * @return mixed Array Containig the keywords
     */
    private function get_tag_key() {

        $all_taxonomies = get_taxonomies('', 'names');

        $taxonomies = array();

        foreach ($all_taxonomies as $taxonomy) {
            if ($taxonomy !== 'post_format' && $taxonomy !== 'link_category' && $taxonomy !== 'nav_menu') {
                $taxonomies[] = $taxonomy;
            }
        }

        $tag_keywords = array();
        $index = 0;
        $term_args = array(
            'orderby' => 'count',
            'order' => 'DESC'
        );
        $tax_terms = get_terms($taxonomies, $term_args);
        foreach ($tax_terms as $term) {
            if ('uncategorized' !== $term->slug) {
                $tag_keywords[$index]['name'] = $term->slug;
                $tag_keywords[$index]['count'] = $term->count;
                $index++;
            }
        }

        return $tag_keywords;
    }

}