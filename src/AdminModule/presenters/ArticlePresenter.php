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

	  /** @var int */
	  public $articleId;

	  public function actionCreate( $id )
	  {
			$this->articleId = $id;
			$data = $this->article->find( 'id', $this->articleId )->fetch();
			$this->template->article = $data ? $data : null;
	  }

	  protected function createComponentGrid( $name )
	  {
//				$source = new NetteDbDataSource( $this->article->findAll() );
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
			$form = new Form();
			$form->addText( 'title', 'Název' )->setRequired();
			$form->addText( 'meta_title', 'Meta title' );
			$form->addText( 'meta_description', 'Meta desctiption' );
			$form->addText( 'meta_keywords', 'Meta keywords' );
			$form->addText( 'url', 'URL' )->setRequired();
			$form->addSelect( 'category_id', 'Kategorie', $this->category->fetchPairsSelectBox() )->setPrompt( "Žádná kategorie" );
			$form->addMultiSelect( 'tag_id', 'Tagy', $this->tag->fetchPairsSelectBox() );
			$form->addUpload( 'picture_id', 'Hlavní obrázek' );
			$form->addUpload( 'picture_blog_id', 'Blog obrázek' );
			$form->addTextArea( 'description', 'Popis' );
			$form->addTextArea( 'content', 'Obsah' );
			$form->addSelect( 'most_readed', 'Nejčtenější', [0 => 'Ne', 1 => 'Ano'] );
			$form->addSelect( 'published', 'Publikovat', [0 => 'Ne', 1 => 'Ano'] );
			$form->addSelect( 'dont_publish_detail', 'Nepublikovat detail', [0 => 'Ne', 1 => 'Ano'] );

			$form->addSubmit( 'create', 'Vytvořit' );

			$form->setRenderer( new Bs3FormRenderer() );

			$data = $this->article->find( 'id', $this->articleId )->fetch();
			$form->setDefaults( $data ? $data : []  );
			$form->setDefaults( ['tag_id' => array_keys( $this->articleTag->find( 'article_id', $this->articleId )->fetchPairs( 'tag_id' ) )] );

			$form->onSuccess[] = $this->formSubmit;

			return $form;
	  }

	  public function formSubmit( Form $form )
	  {
			$data = $form->getValues();
			$tags = $data['tag_id'];
			unset( $data['tag_id'] );

			if( $this->articleId )
			{
				  $this->article->updateForForm( ['id' => $this->articleId], $data, $tags );
				  $this->flashMessage( 'Článek upraven.', 'success' );
			}
			else
			{
				  $this->article->add( $data, $tags )->getPrimary();
				  $this->flashMessage( 'Článek přidán.', 'success' );
			}
			
			$this->scrapePageForFacebook($this->getHttpRequest()->url->baseUrl . $data['url']);
			
			$this->redirect( 'Article:default' );
	  }

	  public function scrapePageForFacebook($url){
						$vars = array('id' =>  $url, 'scrape' => 'true');
						$body = http_build_query($vars);
			
						$fp = fsockopen('ssl://graph.facebook.com', 443);
						fwrite($fp, "POST / HTTP/1.1\r\n");
						fwrite($fp, "Host: graph.facebook.com\r\n");
						fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
						fwrite($fp, "Content-Length: ".strlen($body)."\r\n");
						fwrite($fp, "Connection: close\r\n");
						fwrite($fp, "\r\n");
						fwrite($fp, $body);
						fclose($fp);			
	  }
	  
}
