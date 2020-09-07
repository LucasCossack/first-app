<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


class PostPresenter extends Nette\Application\UI\Presenter
{
	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderShow(int $postId): void
	{
		$this->template->post = $this->database->table('posts')->get($postId);
	}
}