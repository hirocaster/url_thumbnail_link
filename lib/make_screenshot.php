<?php

function resize_image($filename, $width=240, $height=180, $dir_path='./')
{
  $original_full_path = $dir_path . $filename;

  $resize_filename = resize_filename($filename, $width, $height);
  $resize_full_path = $dir_path . $resize_filename;
  
  if(!file_exists($resize_full_path)){
    list($width_orig, $height_orig) = getimagesize($dir_path . $filename);
      $ratio_orig = $width_orig/$height_orig;

      if($width/$height > $ratio_orig){
        $width = $height * $ratio_orig;
      }else{
        $height = $width / $ratio_orig;
      }

      $image_p = imagecreatetruecolor($width, $height);
      $image = imagecreatefromjpeg($original_full_path);
      imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

      imagejpeg($image_p, $resize_full_path, 100);
  }
  return $resize_filename;
}


function resize_filename($filename, $width, $height)
{
  $patterns = array();
  $patterns[0] = '/\.jpg$/';

  $replacements = array();
  $replacements[0] = '_' . "$width" . 'x' . "$height" . '.jpg';
  
  return preg_replace($patterns, $replacements, $filename);
}


function make_screenshot($url, $dir_path='./')
{
  $file = formating_filename($url);
  $full_path = $dir_path . $file;
  if(!file_exists($full_path))
    {
      screenshot($url, $full_path);
    }
  return $file;
}

function formating_filename($url)
{
  $patterns = array();
  $patterns[0] = '/^https:\/\//';
  $patterns[1] = '/^http:\/\//';
  $patterns[2] = '/\/$/';
  $patterns[3] = '/\/+/';
  $patterns[4] = '/\?+/';
  $patterns[5] = '/\&+/';
  $patterns[6] = '/\=+/';
  $patterns[7] = '/#038;/';

  $replacements = array();
  $replacements[0] = '';
  $replacements[1] = '';
  $replacements[2] = '';
  $replacements[3] = '_';
  $replacements[4] = '_';
  $replacements[5] = '_';
  $replacements[6] = '_';
  $replacements[7] = '';
  
  return preg_replace($patterns, $replacements, $url) . '.jpg';  
}


function screenshot($url, $file)
{
  exec(dirname(__FILE__) . '/wkhtmltoimage-amd64 --width 1024 --height 768 \''.$url . '\' \''. $file . '\' > /dev/null');
}
