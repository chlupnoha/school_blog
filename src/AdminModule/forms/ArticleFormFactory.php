<?php

namespace App\Forms;

use App\Model\Article_tagRepository;
use App\Model\ArticleRepository;
use App\Model\CategoryRepository;
use App\Model\TagRepository;
use App\Model\UserRepository;
use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;


class ArticleFormFactory extends Nette\Object
{
	/** @var ArticleRepository */
    public $articleRepository;

    /** @var Article_tagRepository */
    public $articleTagRepository;

    /** @var TagRepository */
    public $tagRepository;

    /** @var CategoryRepository */
    public $categoryRepository;

	/** @var  int|null */
	private $articleId;

    public function __construct(ArticleRepository $articleRepository, Article_tagRepository $articleTagRepository, TagRepository $tagRepository, CategoryRepository $categoryRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->articleTagRepository = $articleTagRepository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getArticleId() {
        return $this->articleId;
    }

    public function setArticleId($articleId) {
        $this->articleId = $articleId;
    }



	public function create() : Form
	{
        $form = new Form();
        $form->addText( 'title', 'Název' )->setRequired();
        $form->addText( 'meta_title', 'Meta title' );
        $form->addText( 'meta_description', 'Meta desctiption' );
        $form->addText( 'meta_keywords', 'Meta keywords' );
        $form->addText( 'url', 'URL' )->setRequired();
        $form->addSelect( 'category_id', 'Kategorie', $this->categoryRepository->fetchPairsSelectBox() )->setPrompt( "Žádná kategorie" );
        $form->addMultiSelect( 'tag_id', 'Tagy', $this->tagRepository->fetchPairsSelectBox() );
        $form->addUpload( 'picture_id', 'Hlavní obrázek' )
            ->setRequired(false)->addRule(Form::MAX_FILE_SIZE, 'Maximum file size is 2MB.', 2 * 1024 * 1024 /* size in Bytes */);
        $form->addUpload( 'picture_blog_id', 'Blog obrázek' )
            ->setRequired(false)->addRule(Form::MAX_FILE_SIZE, 'Maximum file size is 2MB.', 2 * 1024 * 1024 /* size in Bytes */);
        $form->addTextArea( 'description', 'Popis' );
        $form->addTextArea( 'content', 'Obsah' );
        $form->addSelect( 'most_readed', 'Nejčtenější', [0 => 'Ne', 1 => 'Ano'] );
        $form->addSelect( 'published', 'Publikovat', [0 => 'Ne', 1 => 'Ano'] );
        $form->addSelect( 'dont_publish_detail', 'Nepublikovat detail', [0 => 'Ne', 1 => 'Ano'] );

        $form->addSubmit( 'create', 'Vytvořit' )
            ->setAttribute('id', 'sumbit');

        $form->setRenderer( new Bs3FormRenderer() );

        $data = $this->articleRepository->find( 'id', $this->articleId )->fetch();
        $form->setDefaults( $data ? $data : []  );
        $form->setDefaults( ['tag_id' => array_keys( $this->articleTagRepository->find( 'article_id', $this->articleId )->fetchPairs( 'tag_id' ) )] );

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
            $this->articleRepository->updateForForm( ['id' => $this->articleId], $data, $tags );
        }else{
            $this->articleRepository->add( $data, $tags )->getPrimary();
        }

    }

}
