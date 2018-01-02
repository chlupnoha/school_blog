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
class TagPresenter extends BaseAdminPresenter
{

		/** @var \App\Model\TagRepository @inject */
		public $tag;
		
		/**  @var int */
		public $tagId;

		public function actionCreate( $id )
		{
				$this->tagId = $id;
		}

		protected function createComponentGrid( $name )
		{
				$source = new NetteDbDataSource( $this->tag->findAll() );

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
				$this->tag->remove( ['id' => $id] );
		}

		protected function createComponentTagForm()
		{
				$form = new Form();
				$form->addText( 'name', 'Název' )
						->setRequired();

				$form->addSubmit( 'create', 'Vytvořit' );

				$form->setRenderer( new Bs3FormRenderer() );
				
				$data = $this->tag->find('id', $this->tagId)->fetch();
				$form->setDefaults($data ? $data : []);

				$form->onSuccess[] = $this->formSubmit;

				return $form;
		}

		public function formSubmit( Form $form )
		{
				if( $this->tagId )
				{
						$this->tag->update( ['id' => $this->tagId], $form->getValues() );
						$this->flashMessage( 'Tag upraven.', 'success' );
				}
				else
				{
						$this->tag->add( $form->getValues() );
						$this->flashMessage( 'Tag přidán.', 'success' );
				}
				$this->redirect( 'Tag:default' );
		}

}
