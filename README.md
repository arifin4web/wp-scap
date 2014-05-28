# SCAP - Shutterstock Custom Affiliate Plugin

This plugin will help you to use the shutterstock images to fit to your custom design.

## Description

The Oficial Shutterstock Affiliate plugin for wordpress is great. However I have found the 
Official Version somehow not very suitable to use specially in case of adapting custom design needs.
That is the primary reason to create this plugin i.e, to use the shutterstock Images to suit our 
custom design.You can get images with supplied keyword OR this plugins auto algorithm will generate
the best suited images related to your current page/site content. Can be used anywhere. Provide Shortcode.
And there is also a template function in support of the Developer to make it easy to integrate 
according to their custom design needs.

Currently shortcode Output is a 4x2 Grid of shutterstock images with affiliate link. Fluid design. Hopefully 
add other designs and more design options in future.


## Features : 

* Show shutterstock images anywhere using Shortcode. 
* Get shutterstock images anywhere in your template and make your own design (Mainly for developers)
* Automatically generate/detect keyword to populate the best suited images related to your 
  current page/site content.

## Installation

Standard Wordpress plugin installation Procedure.

## Usages

### Configuration

From Dashboard->SCAP Set

- API Username
- API Key
- Affiliate ID.

Note: Without these options the plugin will not work.

### Using Shortcode

The most Basic options is to use The “shortcode”. Shortcodes are easy to use and 
can be used in Page/Post content also In Widgets. In this plugin you can use 
Shortcode in Two Ways.

i) Without Keyword parameter :
   This is the simpliest way to output the shuttestock images. Just put the following shortcode to any post/page content OR as widget content :

   > `[scap]`

   This will show the shutterstock Images relevant to the page content. 

ii) With Keyword parameter :

   You can also use the shortcode supplied with keyword parameter value in following format:

   > '[scap keyword="your-keyword"]'

   an example :

   > `[scap keyword="football"]`

   This will show the shutterstock Images regarding “football”.

### Template Function

  `scap_template_func` is provided for the advanced developer usages. 

##Changelog

####1.0
  * Initial Release.