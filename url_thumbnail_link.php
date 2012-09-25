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

function default_tag(){
  $html = <<<EOS
<div style="width: 80%; margin-left: auto; margin-right: auto;">
<a href="<?php echo \$url ?>" target="_blank">
<img class="alignleft" align="left" border="0" src="<?php echo \$image_src ?>" alt="<?php echo \$title ?>" width="<?php echo \$width ?>" height="<?php echo \$height ?>" /></a>
<p><a href="<?php echo \$url ?>" target="_blank"><?php echo \$title ?></a><img src="http://b.hatena.ne.jp/entry/image/<?php echo \$url ?>" alt="bookmark_counts" /></p><p><small><?php echo \$description ?></small></p>
<br style="clear:both;" />
</div>
EOS;
  return $html;
}

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

  $html = "";
  
  if(!get_option('url_thumbnail_link_tag')){
    $html = default_tag();
  }else{
    $html = get_option('url_thumbnail_link_tag');
  }

  $fail = false;

  ob_start();
  if(eval('?>'. $html) === false){
    $fail = true;
  }
  $evaluatedHtml = ob_get_clean();

  if($fail){
    throw new RuntimeException(sprintf("Evaluation failed: %s", $html));
  }else{
    return $evaluatedHtml;
  }
}

add_shortcode('url_thumbnail_link', 'url_thumbnail_link');

add_action('admin_menu', 'url_thumbnail_link_config');

function url_thumbnail_link_config(){
  add_options_page('url_thumbnail_link', 'url_thumbnail_link', 8, __FILE__, 'url_thumbnail_link_config_page');
}

function url_thumbnail_link_config_page(){
  ?>

  <div class="wrap">
    <h2>url_thumbnail_link_config</h2>

    <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>

<!--INPUT文のNAME属性を前述の変数と合わせます。-->
<textarea name="url_thumbnail_link_tag" rows="4" cols="80">
<?php
    if(get_option('url_thumbnail_link_tag', false)){
      echo get_option('url_thumbnail_link_tag');
    }else{
      echo default_tag();
    }?>
</textarea>

    <!--ここのhiddenも必ず入れてください。複数あるときは、page_optionsは半角カンマで区切って記述。a,b,c　など-->
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="url_thumbnail_link_tag" />
    <p class="submit">

    <!--SUBMITは英語で表記。_eで翻訳されるんです。便利！-->
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
    </form>
    <p>empty is default template</p>
    </div>

  <?php
}

function get_title($url){
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

function fetch_title($url){
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
