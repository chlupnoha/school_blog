<?php

namespace App\Presenters;

class ArticlePresenter extends BasePresenter
{

      /** @var \App\Model\ArticleRepository @inject */
      public $article;

      /** @var \App\Model\PictureRepository @inject */
      public $picture;

      public function renderDefault( $url )
      {
            if( !$url )
            {
                  $this->redirect( "Blog:default" );
            }

            $a = $this->article->find( 'url', $url )->fetch();
            $this->article->find( 'url', $url )->update( ['click' => $a->click + 1 ] );

            $this->template->article = $a;
            $this->setSEO( $a );

            $this->template->next = $this->article->getNextArticle( $a->id );
            $this->template->before = $this->article->getBeforeArticle( $a->id );
            $this->template->favourite = $this->article->getFavourite();
            $this->template->category = $this->article->getFromTag( $a->category_id );
      }

      public function setSEO( $article )
      {
            $this->template->meta_title = $article->meta_title;
            $this->template->meta_description = $article->meta_description;
            $this->template->meta_keywords = $article->meta_keywords;
      }

}
