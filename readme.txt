=== Custom Widget ===
Contributors: matstars, voceplatforms  
Tags: post, widget  
Tested up to: 3.8.1  
Requires at least: 3.5  
Stable tag: 0.1.1  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  


Customize Widgets!


== Description ==

Customize Widgets!


## Usage of cw_template filter

Add a "views" directory and file named "custom-cw.php" to your template directory. The "custom-cw.php" file will be your custom template for this example.

#### Example of using a custom template from within your theme pre-PHP 5.3



`<?php

    function customize_cw_template_filter( $template ){
        $template_dir = get_template_directory();
        return $template_dir . '/views/custom-cw.php';    
    }
    add_filter( 'cw_template', 'customize_cw_template_filter' );
?>`


#### Example of using a custom template from within your theme PHP 5.3+ which allows anonymous functions

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

== Screenshots ==

1. Screenshot of widget

== Changelog ==

= 0.5 =
* Initial release
