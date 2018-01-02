<?php

namespace App\Presenters;

use Nette\Application\UI\Form;

class BlogPresenter extends BasePresenter
{

	  /** @var \App\Model\TagRepository @inject */
	  public $tag;

	  /** @var \App\Model\CategoryRepository @inject */
	  public $category;

	  /** @var \App\Model\ArticleRepository @inject */
	  public $article;

	  /** @var \App\Model\EmailRepository @inject */
	  public $email;

    /** @var \App\Model\UserManager @inject */
    public $user;

	  /** @persistent */
	  public $category_id;

	  /** @persistent */
	  public $tag_id;

	  public function renderDefault( $category_id, $tag_id, $offset )
	  {
			foreach( $this->article->findAll() as $a )

			$this->template->tags = $this->tag->findAll();
			$this->template->categories = $this->category->findAll();

			$this->checkAtributes( $category_id, $tag_id );

			$articles = $this->article->prepareArticleForBlog( ['category_id' => $this->category_id, 'tag_id' => $this->tag_id], $offset * 9 );

			$this->template->articles = $articles;
			$this->template->favourite = $this->article->getFavourite();

			$this->template->offset = $offset;
			$this->template->count = ceil( $this->article->getArticleBlogCount( ['category_id' => $this->category_id, 'tag_id' => $this->tag_id] ) / 3 );
	  }

	  protected function createComponentNewstler()
	  {
			$form = new Form();
			$form->addText( 'email', 'Email' )->setRequired();

			$form->addSubmit( 'send', 'Odeslat' )->setAttribute( 'id', 'submit' );

			$form->onSuccess[] = $this->regionCreated;

			return $form;
	  }

	  public function regionCreated( Form $form )
	  {
			$data = $form->getValues();
			$this->email->add( $data );
	  }

	  public function checkAtributes( $category_id, $tag_id )
	  {
			$this->category_id = $category_id;
			$this->template->category_id = $category_id;

			$this->tag_id = $tag_id;
			$this->template->tag_id = $tag_id;
	  }

	  public function handleFilter( $category_id, $tag_id, $offset )
	  {
			$this->renderDefault( $category_id, $tag_id, $offset );
			$this->redrawControl( 'content' );
	  }

}
