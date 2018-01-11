<?php

namespace App\AdminModule\Presenters;

use App\Forms\UserFormFactory;
use App\Grids\UserGridFactory;
use App\Model\UserRepository;
use Mesour\DataGrid\Grid;

class UserPresenter extends BaseAdminPresenter
{
    /** @var UserGridFactory @inject */
    public $grid;

    /** @var UserFormFactory @inject */
    public $userForm;

    /** @var UserRepository @inject */
    public $userRepository;

    public function actionCreate( $id ) {
        $this->userForm->setUserId($id);
    }

    public function createComponentGrid() : Grid {
        return $this->grid->createComponentGrid();
    }

    public function createComponentUserForm()
    {
        $form = $this->userForm->create();
        $form->onSuccess[] = array($this, 'formSuccess');
    }

    public function formSubmit()
    {
        if( $this->userForm->getUserId() ){
            $this->flashMessage( 'Uzivatel upraven.', 'success' );
        }else {
           $this->flashMessage( 'Uzivatel přidán.', 'success' );
        }
        $this->redirect( 'User:default' );
    }

    public function handleDelete($id){
        $this->userRepository->find('id', $id)->delete();
    }

}
