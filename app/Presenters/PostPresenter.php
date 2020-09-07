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
		$this->template->comments = $post->related('comment')->order('created_at');//nyní zde chceme iterovat přes všechny komentáře, které mají hodnotu sloupce post_id shodnou s $post->id
	}

	protected function createComponentCommentForm(): Form //Nakonfigurujeme zde formulář pro vložení komentářů, který potom vykreslíme v šabloně (zobrazíme na webu)
	{
		$form = new Form; // je Nette\Application\UI\Form, vytvoření nové instance komponenty Form

		$form->addText('name', 'Jméno:') //vykreslí se jako <input type="text" name="name" s <label>Jméno</label>
			->setRequired();

		$form->addEmail('email', 'E-mail:'); //to samé pro emailovou adresu

		$form->addTextArea('content', 'Komentář:') //vykreslí se jako pole pro psaní komentu
			->setRequired();

		$form->addSubmit('send', 'Publikovat komentář'); //<input type="submit">
		$form->onSuccess[] = [$this, 'commentFormSucceeded']; //tento řádek nám říká, že pokud bude formulář úspěšně odeslán, tak zavolej metodu commentFormSucceeded z aktuálního presenteru

		return $form;
	}

	public function commentFormSucceeded(Form $form, \stdClass $values): void //hodnoty odeslané ve formuláři získáme ve $values
	{
		$postId = $this->getParameter('postId');

		$this->database->table('comments')->insert([ //vložení (uložení) získaných dat do databázové tabulky "comments" ve formátu, který vidíme v poli
			'post_id' => $postId,
			'name' => $values->name,
			'email' => $values->email,
			'content' => $values->content,
		]);

		$this->flashMessage('Děkuji za komentář', 'success'); //metoda flashMessage informuje uživatele o výsledku operace, flash zprávy jsou vykreslovány v hlavní šabloně @layout.latte
		$this->redirect('this'); //přesměrování zpět na aktuální stránku, vhodné po každém odeslání formuláře, abychom se vyhnuli hlášce "Chcete odeslat formulář znovu?"
	}

	protected function createComponentPostForm(): Form //Vytvoření formuláře pro ukládání příspěvků
	{
		$form = new Form; //stejná funkčnost jako při vytváření komentářů, akorát zde pro přidávání článků na samostatné stránce
		$form->addText('title', 'Titulek:')
			->setRequired();
		$form->addTextArea('content', 'Obsah:')
			->setRequired();

		$form->addSubmit('send', 'Uložit a publikovat');
		$form->onSuccess[] = [$this, 'postFormSucceeded'];

		return $form;
	}

	public function postFormSucceeded(Form $form, array $values): void //metoda získá data z formuláře, vloží je do databáze, vytvoří zprávu pro uživatele o úspěšném vložení příspěvku a okamžitě přesměruje na stránku s novým příspěvkem, abychom si ho hned mohli prohlédnout
	{
		$post = $this->database->table('posts')->insert($values);

		$this->flashMessage("Příspěvek byl úspěšně publikován.", 'success');
		$this->redirect('show', $post->id);
	}
}