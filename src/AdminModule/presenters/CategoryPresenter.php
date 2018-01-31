<?php

namespace App\AdminModule\Presenters;

use App\Forms\CategoryFormFactory;
use App\Grids\CategoryGridFactory;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Mesour\DataGrid\NetteDbDataSource,
	Mesour\DataGrid\Components\Link,
	Mesour\DataGrid\Grid;

/**
 * Presenter pro praci s investicnimi prilezitostmi
 */
class CategoryPresenter extends BaseAdminPresenter
{

    /** @var \App\Model\CategoryRepository @inject */
    public $category;

    /** @var CategoryGridFactory @inject */
    public $grid;

    /** @var CategoryFormFactory @inject */
    public $categoryForm;

    public function actionCreate( $id )
    {
        $this->categoryForm->setCategoryId($id);
    }

    protected function createComponentGrid( )
    {
        return $this->grid->createComponentGrid();
    }

    public function handleDelete( $id )
    {
        $this->category->remove( ['id' => $id] );
    }

    protected function createComponentCategoryForm()
    {
        $form = $this->categoryForm->create();
        $form->onSuccess[] = $this->formSubmit;
        return $form;
    }


    public function formSubmit( Form $form )
    {
        if( $this->categoryForm->getCategoryId() ){
            $this->flashMessage( 'Kategorie upravena.', 'success' );
        }else{
            $this->flashMessage( 'Kategorie pridana.', 'success' );
        }
    }

}
