<?php

require_once './lib/make_screenshot.php';

class MakeScreenShotTest extends PHPUnit_Framework_TestCase
{
  public function testFormatingFileName()
  {
    $fileName = formating_filename('http://yahoo.co.jp');
    $this->assertEquals('yahoo.co.jp.jpg', $fileName);
  }
  public function testFormatingFileNameHttps()
  {
    $fileName = formating_filename('https://www.facebook.com/');
    $this->assertEquals('www.facebook.com.jpg', $fileName);
  }
  public function testFormatingFileNameParams()
  {
    $url = 'http://b.hatena.ne.jp/search/tag?sort=popular&q=hiroki.jp';
    $fileName = formating_filename($url);
    $this->assertEquals('b.hatena.ne.jp_search_tag_sort_popular_q_hiroki.jp.jpg', $fileName);
  }
  public function testFormatingFileNameParamsGet()
  {
    $url = 'http://jp.rs-online.com/web/generalDisplay.html?id=raspberrypi';
    
    $fileName = formating_filename($url);
    $this->assertEquals('jp.rs-online.com_web_generalDisplay.html_id_raspberrypi.jpg', $fileName);
  }
  public function testResizeFileName()
  {
    $fileName = resize_filename('foobar.jpg', 240, 180);
    $this->assertEquals('foobar_240x180.jpg', $fileName);    
  }
}
