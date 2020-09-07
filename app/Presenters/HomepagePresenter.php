<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderDefault(): void //metoda render slouží k vykreslení k poslání příspěvků z databáze do šablony, která je následně vykreslí jako HTML kód
{
	$this->template->posts = $this->database->table('posts')
		->order('created_at DESC')
		->limit(5);
}
}