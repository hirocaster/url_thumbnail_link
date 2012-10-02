<?php

require_once './lib/make_html_tags.php';

class MakeHtmlTagsTest extends PHPUnit_Framework_TestCase
{
  public function testGetTitle()
  {
    $title = get_title('http://yahoo.co.jp/');
    $this->assertEquals('Yahoo! JAPAN', $title);
  }
  public function testGetTitleSjisCode()
  {
    $title = get_title('http://www.tenkaippin.co.jp/');
    $this->assertEquals('天下一品 -こってりラーメンでヘルシーにスタミナ補充!-', $title);
  }
}
