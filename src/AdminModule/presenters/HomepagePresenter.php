<?php

namespace App\AdminModule\Presenters;

use Nette\Application\UI\Form;
use IPub\VisualPaginator\Components as VisualPaginator;

/**
 * Presenter pro praci s investicnimi prilezitostmi
 */
class HomepagePresenter extends BaseAdminPresenter
{
      public function renderDefault(){
            $this->redirect('Article:default');
      }

}
