<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


class SignPresenter extends Nette\Application\UI\Presenter // vytvoření přihlašovacího formuláře na samostatné stránce Sign
{
	protected function createComponentSignInForm(): Form
	{
		$form = new Form;
		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Prosím vyplňte své uživatelské jméno.'); //setRequired - pole musí být vyplněno

		$form->addPassword('password', 'Heslo:')
			->setRequired('Prosím vyplňte své heslo.');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = [$this, 'signInFormSucceeded'];
		return $form;
    }
    
    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            $this->getUser()->login($values->username, $values->password); // pokud přihlášení proběhne úspěšně, dojde k přesměrování uživatele na Homepage
            $this->redirect('Homepage:');

        } catch (Nette\Security\AuthenticationException $e) { // pokud uživatel zadá nesprávné údaje, objeví se mu o tom hláška
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }

    public function actionOut(): void // po úspěšném odhlášení uživatele proběhne přesměrování na Homepage a objeví se hláška o úspěšném odhlášení
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.');
        $this->redirect('Homepage:');
    }
}