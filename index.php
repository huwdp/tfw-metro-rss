<?php

include("simplehtmldom/simple_html_dom.php");

$url = 'https://news.tfw.wales/news/t/metro';

$articles = array();

class Article
{
    public $title;
    public $link;
    public $description;
    public $datetime;
    
    function __construct($title, $link, $description, $datetime)
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->datetime = $datetime;
    }
}

$html = file_get_html($url);

foreach($html->find('article') as $element)
{
    $title = $element->find("h3[class=signpost__title]", 0)->plaintext;
    $description = $element->find("div[class=signpost__caption] p", 0)->innertext;
    $link = $element->find("a", 0);
    $datetime = $element->find("div[class=date]", 0)->plaintext;
    $article = new Article($title, $link->href, $description, $datetime);
    array_push($articles, $article);
}

header( "Content-type: text/xml");

echo "<?xml version='1.0' encoding='UTF-8'?>
 <rss version='2.0'>
 <channel>
 <title>Transport for Wales - Latest News</title>
 <link>https://www.rctcbc.gov.uk</link>
 <description>Transport for Wales - Latest News</description>";
 
foreach ($articles as $article)
{
   $title = htmlspecialchars($article->title);
   $link= $article->link;
   $description = htmlspecialchars($article->description);
   $datetime = htmlspecialchars($article->datetime);
 
   echo "<item>
   <title>$title</title>
   <link>https://news.tfw.wales$link</link>
   <description>$description</description>
   <pubDate>$datetime</pubDate>
   </item>";
 }
 echo "</channel></rss>";
?>
