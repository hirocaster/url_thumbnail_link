<?php
/*
  Plugin Name: Url Thumbnail Link
  Plugin URI: http://github.com/hirocaster/url_thumbnail_link
  Description: Make Thumbnail URL and Link HTML
  Version: 0.0.1
  Author: Hiroki OHTSUKA
  Author URI: http://hiroki.jp/
*/

require_once(dirname(__FILE__) . '/lib/make_screenshot.php');

function url_thumbnail_link($atts){
  extract(shortcode_atts(array(
                               'url'         => null,
                               'width'       => 240,
                               'height'      => 180,
                               'title'       => null,
                               'description' => null,
                               ), $atts));

  $upload_info = wp_upload_dir();
  $dir_path = $upload_info['path'] . '/url_thumbnail_link/';
  if(!file_exists($dir_path)){
      mkdir($dir_path);
  }
  
  $filename = make_screenshot($url, $dir_path);
  $resize_filename = resize_image($filename, $width, $height, $dir_path);
  $image_src = $upload_info['url'] . '/url_thumbnail_link/' . $resize_filename;

  if(is_null($title)){
      $title = get_title($url);
  }
  
  return "<div style='width: 80%; margin-left: auto; margin-right: auto;'><a href='$url' target='_blank'><img class='alignleft' align='left' border='0' src='$image_src' alt='$title' width='$width' height='$height' /></a><p><a href='$url' target='_blank'>$title</a><img src='http://b.hatena.ne.jp/entry/image/$url' alt='bookmark_counts' /></p><p><small>$description</small></p><br style='clear:both;' /></div>";

}

add_shortcode('url_thumbnail_link', 'url_thumbnail_link');

function get_title($url)
{
  $json_full_path = get_option('upload_path') . '/imageurl/title.json';

  if (file_exists($json_full_path)){
      $json = json_decode(file_get_contents($json_full_path), true);
  }else {
      $json = array();
  }

  if(isset($json["$url"])){
      $title = $json["$url"];
  }else{
        $json["$url"] = fetch_title($url);
        file_put_contents($json_full_path, json_encode($json));
        $title = $json["$url"];
  }
  return $title;
}


function fetch_title($url)
{
  $file = fopen ($url, "r");
  if (!$file){
    $title = 'Can\'t get title.';
  }
  while (!feof ($file)){
      $line = fgets ($file, 1024);
      /* タイトルとタグが同じ行にある場合のみ動作します。 */
      if (preg_match ("@\<title\>(.*)\</title\>@i", $line, $out)){
          $title = $out[1];
          break;
      }
  }
  fclose($file);
  return $title;
}

