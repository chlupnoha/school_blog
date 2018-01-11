<?php

namespace App\Presenters;

use App\Forms\NewsletterForm;
use App\Model\ArticleRepository;
use App\Model\CategoryRepository;
use App\Model\TagRepository;
use App\Model\UserRepository;
use Nette\Application\UI\Form;

class BlogPresenter extends BasePresenter
{

	  /** @var TagRepository @inject */
	  public $tag;

	  /** @var CategoryRepository @inject */
	  public $category;

	  /** @var ArticleRepository @inject */
	  public $article;

      /** @var NewsletterForm @inject */
      public $newsletterForm;

      /** @var UserRepository @inject */
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

			$this->checkAttributes( $category_id, $tag_id );

			$articles = $this->article->prepareArticleForBlog( ['category_id' => $this->category_id, 'tag_id' => $this->tag_id], $offset * 9 );

			$this->template->articles = $articles;
			$this->template->favourite = $this->article->getFavourite();

			$this->template->offset = $offset;
			$this->template->count = ceil( $this->article->getArticleBlogCount( ['category_id' => $this->category_id, 'tag_id' => $this->tag_id] ) / 3 );
	  }

	  public function createComponentNewsletter() : Form
	  {
	      return $this->newsletterForm->createComponentNewsletter();
	  }

	  public function checkAttributes($category_id, $tag_id )
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
