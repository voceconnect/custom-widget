Custom Widget
===================
Contributors: matstars, voceplatforms  
Tags: post, widget  
Tested up to: 3.8.1  
Requires at least: 3.5  
Stable tag: 0.1.0  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  
  
  
## Description
Customize Widgets!

## Installation
> See [Installing Plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).


## Usage

#### Example of using a custom template from within your theme pre-PHP 5.3

```php
<?php
    function customize_cw_template_filter( $template ){
        $template_dir = get_template_directory();
        return $template_dir . '/views/custom-cw.php';    
    }
    add_filter( 'cw_template', 'customize_cw_template_filter' );
?>
```


#### Example of using a custom template from within your theme PHP 5.3+ which allows anonymous functions

```php
<?php

    add_filter( 'cw_template', function( $template ){
        $template_dir = get_template_directory();
        return $template_dir . '/views/custom-cw.php';
    });
?>
```
## Changelog

**0.1.0**  
*Initial release
