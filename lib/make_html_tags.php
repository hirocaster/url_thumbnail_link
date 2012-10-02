<?php

function get_title($url)
{
  $html = file_get_contents_curl($url);

  $doc = new DOMDocument();
  @$doc->loadHTML($html);
  $nodes = $doc->getElementsByTagName('title');
  $title = $nodes->item(0)->nodeValue;

  return $title;
}

function file_get_contents_curl($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
