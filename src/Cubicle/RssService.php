<?php

namespace Cubicle;

class RssService {
	private $app;
	private $link;

	public function __construct($app) {
		$this->app = $app;
		$this->link = "http://www.mod.fi/rss";
		if (!PRODUCTION_ENV) {
			$this->link = "http://blog.loc/rss";
		}
	}

	public function render() {
		$feed = new FeedWriter(RSS2);
		$feed->setLink($this->link);
		$feed->setTitle("Janne Kaistinen");
		$feed->setDescription("RSS feed of www.mod.fi");
		$feed->setImage("www.mod.fi", $this->link, "http://www.mod.fi/images/tree.png");
		$feed->setChannelElement("language", "en-us");
		$feed->setChannelElement("pubDate", date(DATE_RSS, time()));

		$articles = $this->app['article_service']->getArticles();

		foreach ($articles AS $article) {
			$content = $this->app['article_service']->getArticle($article['id']);
			$item = $feed->createNewItem();
			$item->setTitle($article['title']);
			$item->setLink('http://www.mod.fi/article/' .$article['id']);
			$item->setDate(strtotime($article['date']));
			$item->setDescription($content['intro']);
			$item->addElement('guid', 'http://www.mod.fi/article/' .$article['id'], array('isPermaLink' => 'true'));
			$feed->addItem($item);
		}

		return $feed->genarateFeed();
	}
}
