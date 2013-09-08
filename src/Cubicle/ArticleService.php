<?php

namespace Cubicle;

class ArticleService {
	private static $ARTICLES_PER_PAGE = 5;

	private $app = null;
	private $articles = array();

	/********************************************************************
	*
	********************************************************************/
	public function __construct($app) {
		$this->app = $app;
		$this->articles = \Spyc::YAMLLoad(ROOT_DIR . 'content/articles.yaml');
	}

	/********************************************************************
	*
	********************************************************************/
	public function getArticles() {
		return $this->articles;
	}

	/********************************************************************
	*
	********************************************************************/
	public function getArticle($name) {
		$article = $this->findArticle($name);
		if ($article !== null) {
			$content = explode('---', file_get_contents(ROOT_DIR. 'content/' .$article['id'].'.txt'));
			return array(
				'id' =>         $article['id'],
				'date' =>       date('j.n.Y', strtotime($article['date'])),
				'title' =>      $article['title'],
				'intro' =>      Markdown($content[0]),
				'content' =>    Markdown($content[1])
			);
		}
		return null;
	}

	/********************************************************************
	*
	********************************************************************/
	public function getPageList($page = 0) {
		return array(
			'articles' => $this->getPageArticles($page),
			'next_page' => $this->nextPage($page),
			'prev_page' => $this->previousPage($page)
		);
	}

	/********************************************************************
	*
	********************************************************************/
	private function getPageArticles($page = 0) {
		$pageArticles = array();

		$index = $page * ArticleService::$ARTICLES_PER_PAGE;
		for ($i = 0; $i < ArticleService::$ARTICLES_PER_PAGE && $index < count($this->articles); $i++, $index++) {
			$content = explode('---', file_get_contents(ROOT_DIR. 'content/' .$this->articles[$index]['id'].'.txt'));
			$pageArticles[] = array(
				'id' =>         $this->articles[$index]['id'],
				'date' =>       date('j.n.Y', strtotime($this->articles[$index]['date'])),
				'title' =>      $this->articles[$index]['title'],
				'intro' =>      Markdown($content[0])
			);
		}
		return $pageArticles;
	}

	/********************************************************************
	*
	********************************************************************/
	private function nextPage($page = 0) {
		if ($page * ArticleService::$ARTICLES_PER_PAGE + ArticleService::$ARTICLES_PER_PAGE < count($this->articles)) {
			return $page + 1;
		}
		return -1;
	}

	/********************************************************************
	*
	********************************************************************/
	private function previousPage($page = 0) {
		if ($page > 0) {
			return $page - 1;
		}
		return -1;
	}

	/********************************************************************
	*
	********************************************************************/
	private function findArticle($name) {
		foreach ($this->articles as $article) {
			if ($article['id'] == $name) {
				return $article;
			}
		}
		return null;
	}

	/********************************************************************
	*
	********************************************************************/
	public function getRenderData($name) {
		$article = $this->getArticle($name);
		$ret = array(
			'title' => $article['title'],
			'article' => $article
		);
		return $ret;
	}
}
