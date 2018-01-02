<?php

namespace App\AdminModule\Presenters;

use Nette;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

		protected function startup()
		{
				parent::startup();
//				$user = $this->getUser();
//				$user->getStorage()->setNamespace( 'admin' );
		}
}
