<?php

namespace App\AdminModule\Presenters;

use IPub\VisualPaginator\Components as VisualPaginator;

class UserPresenter extends BaseAdminPresenter
{
      public function renderDefault(){
            $this->redirect('Article:default');
//            $this->user->identity = "";
      }

}
