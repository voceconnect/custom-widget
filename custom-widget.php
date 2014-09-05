<?php

/*
  Plugin Name: Custom Widget
  Description: Customized widget that has inputs for a title, a CTA text and link, textarea and image with a template that can easily be customized using filters.
  Version: 0.1.7
  Author: matstars
  Author URI: http://vocecplatforms.com
 */

require_once('lib/class-custom-widget.php');
$custom_widget = new Custom_Widget;
$custom_widget->init();