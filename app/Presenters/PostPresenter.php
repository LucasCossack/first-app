<?php //vytvoříme samostatný presenter pro POST (PostPresenter.php), v něm použijeme constructor, který nás připojí k databázi a metodu renderShow, která nám pomůže zobrazit jeden konkrétní příspěvek z databáze

namespace App\Presenters; //POZOR na uvedení správného namespacu!

use Nette;
use Nette\Application\UI\Form;

class PostPresenter extends Nette\Application\UI\Presenters
{
	/** @var Nette\Database\Context */
	private $database;

	public function __construct (Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderShow(int $postId): void //tato metoda vyžaduje jeden argument - ID jednoho konkrétního článku
	{
		$this->template->post = $this->database->table('posts')->get($postId);
	}
}