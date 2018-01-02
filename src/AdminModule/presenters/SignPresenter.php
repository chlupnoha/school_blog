<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Forms\SignFormFactory;
use Nette\Application\UI\Form;

class SignPresenter extends BasePresenter
{

		/** @var SignFormFactory @inject */
		public $factory;

		/**
		 * Sign-in form factory.
		 * @return Nette\Application\UI\Form
		 */
		protected function createComponentSignInForm()
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

//		protected function createComponentRegister()
//		{
//				$form = new Form();
//
//				$form->addText( 'email', 'Email' )
//						->setRequired();
//
//				$form->addPassword( 'password', 'Heslo' );
//
//				$form->addSubmit( 'create', 'Vytvořit' )
//						->setAttribute( 'id', 'submit' );
//				$form->onSuccess[] = $this->taskFormSubmitted;
//
//				return $form;
//		}
//
//		public function taskFormSubmitted( Form $form )
//		{
//				$values = $form->getValues();
//				//dump( $values );
//
//				$this->userManager->add($values);
//
//				$this->flashMessage( 'Stezka přidána.', 'success' );
//		}

}
