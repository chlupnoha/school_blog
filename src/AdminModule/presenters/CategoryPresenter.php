<?php

namespace App\AdminModule\Presenters;

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
		
		/** @var int */
		public $categoryId;

		public function actionCreate( $id )
		{
				$this->categoryId = $id;
//				dump( $this->categoryId );
		}

		protected function createComponentGrid( $name )
		{
				$source = new NetteDbDataSource( $this->category->findAll() );

				$grid = new Grid( $this, $name );

				$grid->setPrimaryKey( 'id' ); // primary key is now used always

				$grid->addText( 'name', 'Název' );

				$grid->enablePager( 10 );
				
				$grid->setDataSource( $source );

				$actions = $grid->addActions( 'Akce' );

				$actions->addButton()
						->setType( 'btn-primary' )
						->setIcon( 'glyphicon-pencil' )
						->addAttribute( 'href', new Link( ['href' => 'create', 'parameters' => ['id' => '{id}']] ) );

				$actions->addButton()
						->setType( 'btn-danger' )
						->setIcon( 'glyphicon-remove' )
						->setConfirm( 'Opravdu chcete odstranit prispevek' )
						->addAttribute( 'href', new Link( ['href' => 'delete!', 'parameters' => ['id' => '{id}']] ) );

				return $grid;
		}

		public function handleDelete( $id )
		{
				$this->category->remove( ['id' => $id] );
		}

		protected function createComponentCategoryForm()
		{
				$form = new Form();
				$form->addText( 'name', 'Název' )
						->setRequired();

				$form->addSubmit( 'create', 'Vytvořit' )
						->setAttribute( 'id', 'submit' );

				$form->setRenderer( new Bs3FormRenderer() );
				
				$data = $this->category->find('id', $this->categoryId)->fetch();
				$form->setDefaults($data ? $data : []);

				$form->onSuccess[] = $this->formSubmit;

				return $form;
		}

		public function formSubmit( Form $form )
		{
				if( $this->categoryId )
				{
						$this->category->update( ['id' => $this->categoryId], $form->getValues() );
						$this->flashMessage( 'Kategorie upravena.', 'success' );
				}
				else
				{
						$this->category->add( $form->getValues() );
						$this->flashMessage( 'Kategorie přidána.', 'success' );
				}
				$this->redirect( 'Category:default' );
		}

}
