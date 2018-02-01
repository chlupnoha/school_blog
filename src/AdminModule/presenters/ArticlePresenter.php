<?php

namespace App\AdminModule\Presenters;

use App\Forms\ArticleFormFactory;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Mesour\DataGrid\NetteDbDataSource,
	Mesour\DataGrid\Components\Link,
	Mesour\DataGrid\Grid;

/**
 * Presenter pro praci s investicnimi prilezitostmi
 */
class ArticlePresenter extends BaseAdminPresenter
{

	  /** @var \App\Model\ArticleRepository @inject */
	  public $article;

	  /** @var \App\Model\CategoryRepository @inject */
	  public $category;

	  /** @var \App\Model\TagRepository @inject */
	  public $tag;

	  /** @var \App\Model\Article_tagRepository @inject */
	  public $articleTag;

    /** @var ArticleFormFactory @inject */
    public $articleForm;

    /** @var int */
	  public $articleId;

	  public function actionCreate( $id )
	  {
			$this->articleForm->setArticleId($id);
			$data = $this->article->find( 'id', $id )->fetch();
			$this->template->article = $data ? $data : null;
	  }

	  protected function createComponentGrid( $name )
	  {
			$res = [];
			foreach( $this->article->findAll()->order( 'sort' ) as $d )
			{
				  $r = $d->toArray();
				  $r['category'] = isset( $this->category->find( 'id', $r['category_id'] )->fetch()->name ) ? $this->category->find( 'id', $r['category_id'] )->fetch()->name : "";
				  $r['picture'] = isset( $this->picture->find( 'id', $r['picture_blog_id'] )->fetch()->name ) ? "./../../" . $this->picture->find( 'id', $r['picture_blog_id'] )->fetch()->name : "";
				  $r['published_on'] = $r['published_on'] ? $r['published_on']->format( 'd.m.Y' ) : "Nepublikováno";
				  $r['most_readed'] = $r['most_readed'] ? 'Ano' : "Ne";
				  $res[] = $r;
			}
			//dump($res);
			$source = new \Mesour\DataGrid\ArrayDataSource( $res );

			$grid = new Grid( $this, $name );

			$grid->setPrimaryKey( 'id' ); // primary key is now used always

			$grid->addText( 'title', 'Titulek' )
					->setOrdering( false);

			$grid->addImage( 'picture', 'Blog' )
					->setMaxHeight( 80 )
					->setMaxWidth( 80 );

			$grid->addText( 'category', 'Kategorie' )
					->setOrdering( false);

			$grid->addText( 'published_on', 'Publikováno' )
					->setOrdering( false);

			$grid->addText( 'most_readed', 'Nejčtenější' )
					->setOrdering( false);

//			$grid->enablePager();

			$grid->setDataSource( $source );
			
			$grid->enableSorting(); // enable sorting

			$grid->onSort[] = $this->editSort; // here set sort callback
			
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
			$this->articleTag->remove( ['article_id' => $id] );
			$this->article->remove( ['id' => $id] );
			$this->redirect( 'Article:default' );
	  }

	  // sortable callback
	  public function editSort( $data, $item_id )
	  {
			$sort = 0;
			foreach( $data as $d )
			{
				  $this->article->update(
						  ['id' => $d], ['sort' => $sort] );
				  $sort = $sort + 10;
			}
			$this->redirect('Article:default');
	  }

	  protected function createComponentArticleForm()
	  {
			$form = $this->articleForm->create();
			$form->onSuccess[] = $this->formSubmit;
			return $form;
	  }

	  public function formSubmit( Form $form )
	  {
        if( $this->articleForm->getArticleId() )
        {
              $this->flashMessage( 'Článek upraven.', 'success' );
        }else{
              $this->flashMessage( 'Článek přidán.', 'success' );
        }

        $this->redirect( 'Article:default' );
	  }

}
