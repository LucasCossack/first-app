<?php

namespace App\Presenters; //HomepagePresenter jsme zde upravili tak, abychom se zbavili závislosti na Nette\Database\Context a použijeme závislost na naší nové třídě

use Nette;
use App\Model\ArticleManager;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
	/** @var ArticleManager */
	private $articleManager;

	public function __construct(ArticleManager $articleManager) // Pomocí constructoru si požádáme o ArticleManager, který si přidáme do vlastnosti $articleManager
	{
		$this->articleManager = $articleManager;
	}

	public function renderDefault($page = 1)
	{
		$posts = $this->articleManager->findPublishedArticles();
		$lastPage = 0;
		$this->template->posts = $posts->page($page, 5, $lastPage);
		 
		$this->template->page = $page;
 		$this->template->lastPage = $lastPage;
	}
}