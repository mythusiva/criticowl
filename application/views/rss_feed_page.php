<?php
header("Content-Type: application/rss+xml; charset=UTF-8");
 
$xml = new SimpleXMLElement('<rss/>');
$xml->addAttribute("version", "2.0");

$channel = $xml->addChild("channel");
$channel->addChild("title", "CriticOwl Updates");
$channel->addChild("link", base_url());
$channel->addChild("description", "CriticOwl aspires to be the go-to website when you’re deciding on which movie to watch.");
$channel->addChild("language", "en-us");
$channel->addChild("copyright", 'Copyright (C) 2013 - '.date("Y").' www.criticowl.com');

foreach ($feed_results as $row) {
    $item = $channel->addChild("item");
    $article_url = base_url() . get_uri_prefix_by_article_type($row['article_type']) . $row['uri_segment'];
    $img_link = get_compressed_thumbnail_url($row['image_link']);
    $item->addChild("title", clean_xml_string($row['title']));
    $item->addChild("link", $article_url);
    $item->addChild("description", "<img src='{$img_link}' alt='' /><br />".clean_xml_string($row['preview_text']));
    $item->addChild("pubDate", date("D, d M Y H:i:s O", strtotime($row['date_notified'])));
    $item->addChild("guid", $article_url);
}
echo $xml->asXML();