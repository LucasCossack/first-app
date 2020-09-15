<?php

namespace App\Model; // aplikace už začala být nedostatečně funkční. např nechceme, aby šly vybrat jen články, které se právě needitují nebo nejsou rozepsané

use Nette;

class ArticleManager
{
	use Nette\SmartObject;

	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database) // třída vytvořená pomocí konstruktoru, ve kterém si necháváme předat databázový Context
	{
		$this->database = $database;
	}

	public function findPublishedArticles()
	{
		return $this->database->table('posts')
			->where('created_at < ', new \DateTime)
			->order('created_at ASC');
	}
}