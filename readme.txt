=== Custom Widget ===
Contributors: matstars, voceplatforms  
Tags: post, widget  
Tested up to: 3.9.1
Requires at least: 3.6  
Stable tag: 0.1.3
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  


Customized widget that has inputs for a title, a CTA text and link, textarea and image.

== Description ==

Provides a widget that allows you to output a title, a call to action (CTA) text with a link, textarea and an image. It comes with a prebuilt template, but you can easily extend it using filters and add your own template on a global basis.



## Usage of cw_template filter

Add a template directory and file named "custom-cw.php" to your template directory. The "custom-cw.php" file will be your custom template for this example, see below.

#### Example of using a custom template to globally override all instances of Custom Widget from within your theme pre-PHP 5.3:



`<?php

    function customize_cw_template_filter( $template ){
        $template_dir = get_template_directory();
        return $template_dir . '/views/custom-cw.php';    
    }
    add_filter( 'cw_template', 'customize_cw_template_filter' );
?>`


#### Example of using a custom template to globally override all instances of Custom Widget from within your theme using PHP 5.3+ which allows anonymous functions:

`<?php
    add_filter( 'cw_template', function( $template ){
        $template_dir = get_template_directory();
        return $template_dir . '/views/custom-cw.php';
    });
?>`


== Installation ==

1. If installing manually, unzip and copy the resulting directory to your plugin directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the widget to any widgetized area/sidebar and configure as desired.

== Frequently Asked Questions ==

= This isn't working! =

Are you using WordPress 3.6+?


== Changelog ==

= 0.1.3 =
* Bugfixes, sanitization and escaping all the things

= 0.1.2 =
* Incremental bugfixes


= 0.1.1 =
* Incremental bugfixes


= 0.1.0 =
* Initial release
