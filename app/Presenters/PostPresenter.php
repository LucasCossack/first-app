<?php //vytvoříme samostatný presenter pro POST (PostPresenter.php), v něm použijeme constructor, který nás připojí k databázi a metodu renderShow, která nám pomůže zobrazit jeden konkrétní příspěvek z databáze

namespace App\Presenters; //POZOR na uvedení správného namespacu!

use Nette;
use Nette\Application\UI\Form;

class PostPresenter extends Nette\Application\UI\Presenter
{
	/** @var Nette\Database\Context */
	private $database;

	public function __construct (Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderShow(int $postId): void
	{
		$post = $this->database->table('posts')->get($postId);
		if (!$post) {
			$this->error('Stránka nebyla nalezena'); //chybová hláška, pokud příspěvek nebyl nalezen
		}

	$this->template->post = $post;
	}

}