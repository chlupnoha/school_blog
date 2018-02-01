<?php

namespace Test\FrontendModule;

use App\Forms\ArticleFormFactory;
use App\Forms\TagFormFactory;
use App\Forms\UserFormFactory;
use App\Model\Article_tagRepository;
use App\Model\ArticleRepository;
use App\Model\CategoryRepository;
use App\Model\TagRepository;
use App\Model\UserRepository;
use Nette\Environment;
use PHPUnit\Framework\TestCase;
use Test\PageObject\TagForm;

require __DIR__ . '/../../../bootstrap.php';

class ArticleFormFactoryTest extends TestCase
{

    /** @var ArticleFormFactory */
    private $articleForm;

    /** @var ArticleRepository */
    private $articleRepository;

    /** @var Article_tagRepository */
    private $articleTagRepository;

    /** @var TagRepository */
    private $tagRepository;

    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct() {
        parent::__construct();
        $this->articleForm = Environment::getContext()->getByType(ArticleFormFactory::class);
        $this->articleRepository = Environment::getContext()->getByType(ArticleRepository::class);
        $this->tagRepository = Environment::getContext()->getByType(TagRepository::class);
        $this->categoryRepository = Environment::getContext()->getByType(CategoryRepository::class);
        $this->articleTagRepository = Environment::getContext()->getByType(Article_tagRepository::class);
    }

    protected function setUp()
    {
        $this->articleRepository->remove(['title' => 'title']);
    }

    public function testCreationOfNewArticle(){
        $someTags = [2, 4, 8];
        $form = $this->articleForm->create()->setDefaults([
            'title' => 'title',
            'meta_title' => 'meta_title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'url' => 'url',
            'category_id' => $this->categoryRepository->findAll()->fetch()->id,
            'tag_id' => $someTags,
            'description' => 'description',
            'content' => 'content',
            'most_readed' => 0,
            'published' => 1,
            'dont_publish_detail' => 0,
        ]);

        $this->articleForm->formSubmit($form);

        $article = $this->articleRepository->findBy(['title' => 'title'])->fetch();
        $tagsToImage = $this->articleTagRepository->findBy(['article_id' => $article->id]);

        $this->assertNotNull($article->title);
        $this->assertEquals(count($someTags), $tagsToImage->count());
    }

}
