=== SCAP - Shutterstock Custom Affiliate Plugin ===
Contributors: arifin4web
Tags: shutterstock,
Requires at least: 3.5.1
Tested up to: 3.9
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will help you to use the shutterstock images specially in case to fit to your custom design.

== Description ==

The Oficial/exisiting Shutterstock Affiliate plugin for wordpress is great. However I have found that 
Version somehow not very suitable to use specially in case of adapting custom design needs.
That is the primary reason to create this plugin i.e, to use the shutterstock Images to suit our 
custom design.You can get images with supplied keyword OR this plugins auto algorithm will generate
the best suited images related to your current page/site content. Can be used anywhere. Provide Shortcode.
And there is also a template function in support of the Developer to make it easy to integrate 
according to their custom design needs.

= Key features =

*   Show shutterstock images anywhere using Shortcode. 
*   Get shutterstock images anywhere in your template and make your own design (Mainly for developers)
*   Automatically generate/detect keyword to populate the best suited images related to your 
    current page/site content.

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'wp-scap'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `wp-scap.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `wp-scap.zip`
2. Extract the `wp-scap` directory to your computer
3. Upload the `wp-scap` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==

= What do I need to configure? =

You need to set some options in order to make the plugin functional. From Dashboard->SCAP Set 
    i.)    API Username
    ii.)   API Key
    iii.)  Affiliate ID.

Note: Without these options the plugin will not work.

= What is the Basic Usage example? =

The most Basic options is to use The “shortcode”. Shortcodes are easy to use and 
can be used in Page/Post content also In Widgets. In this plugin you can use 
Shortcode in Two Ways.

	a.  Without “Keyword” parameter :  This is the simpliest way to output the 
shuttestock images. Just put the following shortcode to any post/page content OR as widget content :

	[scap]

This will show the shutterstock Images relevant to the page content. 

	b. With “Keyword” parameter : You can also use the shortcode supplied with
 keyword parameter value in following format:

	[scap keyword="your-keyword"]

an example :

	[scap keyword="football"]

= What is the current output format in case of shortcode? =

Currently This plugin shows a 4x2 Grids of relevant shutterstock images as shortcode output.
The grid is fluid therefore will adjust to your design automatically. 
More design Options will be added in Future.

= How to get the shutterstock image data in any template file? =

Developers can use this plugin to get the shutterstock image data from any template file.
'scap_template_func' template function is provided to serve this purpose.

Usages: 
?php 
    if ( function_exists('scap_template_func')){
        $args = array(
                'keyword' => 'football' // Your desired keyword for images
                'number' => 8 //Number of images to get
                );
        $shutterstock_images = scap_template_func($args);
    }
?>
Returns Value
(array) Array of Stadard Objects

image - contain shutterstock image object.
aff_link - contain the affiliate link of that image.



== Screenshots ==

will be added later

== Changelog ==

= 1.0 =
* Initial Release.

== Upgrade Notice ==
= 1.0 =
Initial Release.

== Updates ==