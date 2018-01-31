<?php

namespace App\AdminModule\Presenters;

use App\Forms\TagFormFactory;
use App\Grids\TagGridFactory;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Mesour\DataGrid\NetteDbDataSource,
	Mesour\DataGrid\Components\Link,
	Mesour\DataGrid\Grid;

/**
 * Presenter pro praci s investicnimi prilezitostmi
 */
class TagPresenter extends BaseAdminPresenter
{

    /** @var \App\Model\TagRepository @inject */
    public $tag;

    /** @var TagFormFactory @inject */
    public $tagForm;

    /** @var TagGridFactory @inject */
    public $grid;
		
    /**  @var int */
    public $tagId;

    public function actionCreate( $id )
    {
        $this->tagForm->setTagId($id);
    }

    protected function createComponentGrid( )
    {
        return $this->grid->createComponentGrid();
    }

    public function handleDelete( $id )
    {
        $this->tag->remove( ['id' => $id] );
    }

    protected function createComponentTagForm()
    {
        $form = $this->tagForm->create();
        $form->onSuccess[] = $this->formSubmit;
        return $form;
    }

    public function formSubmit( Form $form )
    {
        if( $this->tagId ){
            $this->flashMessage( 'Tag upraven.', 'success' );
        }
        else{
            $this->flashMessage( 'Tag pridan.', 'success' );
        }
    }

}
