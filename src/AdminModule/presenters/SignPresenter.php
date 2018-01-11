<?php

namespace App\AdminModule\Presenters;

use App\Forms\SignFormFactory;
use Nette\Application\UI\Form;

class SignPresenter extends BasePresenter
{

		/** @var SignFormFactory @inject */
		public $factory;

		protected function createComponentSignInForm() : Form
		{
				$form = $this->factory->create();
				$form->onSuccess[] = function ($form)
				{
						$form->getPresenter()->redirect( 'Homepage:' );
				};
				return $form;
		}

		public function actionOut()
		{
				$this->getUser()->logout();
				$this->flashMessage( 'You have been signed out.' );
				$this->redirect( 'in' );
		}

}
