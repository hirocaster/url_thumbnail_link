<?php
/*
  Plugin Name: ImageURL
  Plugin URI: http://github.com/hirocaster
  Description: ImageURL
  Version: 0.0.1
  Author: Hiroki OHTSUKA
  Author URI: http://hiroki.jp/
*/

require_once('./lib/make_screenshot.php');

function imageurl($atts){
  extract(shortcode_atts(array(
                               'url'         => null,
                               'width'       => 240,
                               'height'      => 180,
                               'title'       => null,
                               'desctiption' => null,
                               ), $atts));

  $dir_path = get_option('upload_path');
  $filename = make_screenshot($url, $dir_path);
  $resize_filename = resize_image($filename, $width, $height, $dir_path);
  return "<img src='$dir_path . $resize_filename' />";
}

add_shortcode('imageurl', 'imageurl');
