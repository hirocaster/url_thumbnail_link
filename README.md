# url_thumbnail_link for WordPress Plugin

Auto generate thumbnail link by short code.

# Require

    - PHP 5.3.x
    - php5-gd
    - php5-curl
    - wkhtmltoimage(http://code.google.com/p/wkhtmltopdf/)
    - only Linux(amd64) because include linux binaly

# Short Code

example short code. auto get title tag.

    [url_thumbnail_link url="http://yahoo.co.jp/"]
    
manual set title.

    [url_thumbnail_link url="http://yahoo.co.jp/" title="ヤフー！ジャパン"]
    
set url description.

    [url_thumbnail_link url="http://yahoo.co.jp/" description="Famous Search Engine in Japan."]


# for Developper

## Setup

    will install library for ./vendor
    
        $ ./composer.phar update
    
## UnitTest

    $ ./phpuni
