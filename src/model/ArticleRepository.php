<?php

namespace App\Model;

use Carrooi\ImagesManager\ImagesManager;

/**
 * Users management.
 */
class ArticleRepository extends Repository
{

      /** @var \App\Model\PictureRepository */
      private $picture;

      /** @var \App\Model\Article_tagRepository */
      private $articleTag;

      public function __construct( \Nette\Database\Context $db, ImagesManager $imagesManager )
      {
            parent::__construct( $db );
            $this->picture = new PictureRepository( $db, $imagesManager );
            $this->articleTag = new Article_tagRepository( $db );
      }

      public function add( $data, $tags = [] )
      {
            $data[ 'picture_id' ] = $this->picture->tryUpload( $data[ 'picture_id' ], 700 );
            $data[ 'picture_blog_id' ] = $this->picture->tryUpload( $data[ 'picture_blog_id' ], 240, 140 );
            $data[ 'published_on' ] = $data[ 'published' ] ? date( "Y-m-d H:i:s" ) : null;
            $data[ 'created_at' ] = date( "Y-m-d H:i:s" );
            $res = $this->getTable()->insert( $data );
            $this->addTagsToArticle( $res->getPrimary(), $tags );
            return $res;
      }
	  
	  public function updateForForm( array $by, $data, $tags = null )
      {
            if( $data[ 'picture_id' ]->name )
            {
                  $data[ 'picture_id' ] = $this->picture->tryUpload( $data[ 'picture_id' ], 700 );
            } else
            {
                  unset( $data[ 'picture_id' ] );
            }
            if( $data[ 'picture_blog_id' ]->name )
            {
                  $data[ 'picture_blog_id' ] = $this->picture->tryUpload( $data[ 'picture_blog_id' ], 240, 140 );
            } else
            {
                  unset( $data[ 'picture_blog_id' ] );
            }

            $data[ 'published_on' ] = $data[ 'published' ] ? date( "Y-m-d H:i:s" ) : null;
            $data[ 'created_at' ] = date( "Y-m-d H:i:s" );
            $res = $this->getTable()->where( $by )->update( $data );

            $this->articleTag->remove( ['article_id' => $by[ 'id' ] ] );
            $this->addTagsToArticle( $by[ 'id' ], $tags );

            return $res;
      }

      public function addTagsToArticle( $id, array $tags )
      {
            foreach( $tags as $t )
            {
                  $this->articleTag->add( ['article_id' => $id, 'tag_id' => $t ] );
            }
      }
      
      public function getArticleBlogCount($by, $countInLine = 3){
            if( $by[ 'tag_id' ] )
            {
                  $ids = array_keys( $this->getConnection()->table( 'article_tag' )->where( 'tag_id', $by[ 'tag_id' ] )->select( 'article_id' )->fetchPairs( 'article_id' ) );
                  $data = $this->getTable()->where( 'id', $ids )->where( 'published', 1 );
            } elseif( $by[ 'category_id' ] )
            {
                  $data = $this->getTable()->where( 'category_id', $by[ 'category_id' ] )->where( 'published', 1 );
            } else
            {
                  $data = $this->getTable()->where( 'published', 1 );
            }
            return ceil($data->count() / $countInLine);
      }

      public function prepareArticleForBlog( $by, $offset, $countInLine =3  )
      {
            $data = [ ];
            if( $by[ 'category_id' ] && $by[ 'tag_id' ] )
            {
                  $ids = array_keys( $this->getConnection()->table( 'article_tag' )->where( 'tag_id', $by[ 'tag_id' ] )->select( 'article_id' )->fetchPairs( 'article_id' ) );
                  $data = $this->getTable()
                          ->where( 'id', $ids )
                          ->where( 'category_id', $by[ 'category_id' ] )
                          ->where( 'published', 1 );
            } elseif( $by[ 'tag_id' ] )
            {
                  $ids = array_keys( $this->getConnection()->table( 'article_tag' )->where( 'tag_id', $by[ 'tag_id' ] )->select( 'article_id' )->fetchPairs( 'article_id' ) );
                  $data = $this->getTable()->where( 'id', $ids )->where( 'published', 1 );
            } elseif( $by[ 'category_id' ] )
            {
                  $data = $this->getTable()->where( 'category_id', $by[ 'category_id' ] )->where( 'published', 1 );
            } else
            {
                  //jen clanky bez kategorie
                  $data = $this->getTable()->where( 'published', 1 )->where( 'category_id', null);
            }
            
            $data = $data->order( 'sort')->limit(9, $offset);

            $articles = [ ];
            $ar = [ ];
            $counter = 0;
            foreach( $data as $a )
            {
                  $counter++;
                  $ar[] = $a;
                  if( $counter % $countInLine == 0 )
                  {
                        $articles[] = $ar;
                        $ar = [ ];
                  }
            }
            $articles[] = $ar;
            return $articles;
      }

      public function getBeforeArticle( $id )
      {
            $article = $this->getTable()->get( $id );
            $b = null;
            foreach( $this->getTable()->where( 'published', 1 )->order( 'published' ) as $a )
            {
                  if( $article->id == $a->id )
                  {
                        return $b;
                  } else
                  {
                        $b = $a;
                  }
            }
            return null;
      }

      public function getNextArticle( $id )
      {
            $article = $this->getTable()->get( $id );
            $f = 0;
            foreach( $this->getTable()->where( 'published', 1 )->order( 'published' ) as $a )
            {
                  if( $article->id == $a->id )
                  {
                        $f = 1;
                  } elseif( $f )
                  {
                        return $a;
                  }
            }
            return null;
      }

      public function getFavourite()
      {
            return $this->getTable()->where( 'published', 1 )->where( 'category_id', null)->order( 'click' )->limit( 3 );
      }

      public function getFromTag( $tagId )
      {
            return $this->getTable()->where( 'published', 1 )->where( 'category_id', null)->order( 'published_on, click' )->limit( 3 );
      }

}
