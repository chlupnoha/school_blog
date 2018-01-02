<?php

namespace App\AdminModule\Presenters;

use Nette;
use IPub\VisualPaginator\Components as VisualPaginator;

/**
 * Base presenter for all application presenters.
 */
abstract class BaseAdminPresenter extends Nette\Application\UI\Presenter
{

		/** @var \App\Model\PictureRepository @inject */
		public $picture;

		protected function startup()
		{
				parent::startup();
				if( !$this->user->isLoggedIn() )
				{
						$this->redirect( 'Sign:in' );
				}
		}

}
