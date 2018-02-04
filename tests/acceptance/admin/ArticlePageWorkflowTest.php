<?php

namespace Test\Selenium;

use App\Forms\UserFormFactory;
use App\Model\ArticleRepository;
use App\Model\PictureRepository;
use App\Model\UserRepository;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Nette\Environment;
use PHPUnit\Framework\TestCase;
use Test\PageObject\ArticleForm;
use Test\PageObject\SignUpForm;
use Test\PageObject\UserForm;

require __DIR__ . '/../../bootstrap.php';

class ArticlePageWorkflowTest extends TestCase
{
    /** @var  RemoteWebDriver */
    public $driver;

    /** @var ArticleRepository */
    private $articleRepository;

    private $articleTestData = [
        'id' => 1000,
        'title' => 'title',
        'picture_id' => null,
        'picture_blog_id' => null,
        'published' => null,
    ];

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        $this->articleRepository = Environment::getContext()->getByType(ArticleRepository::class);
        parent::__construct(...func_get_args());
    }

    public function setUp()
    {
        $this->driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            DesiredCapabilities::firefox()
        );

        $this->driver->get('http://localhost/school_blog/www/admin/');
        $loginForm = new SignUpForm($this->driver);
        $loginForm->login();

        $this->articleRepository->add($this->articleTestData);
        $this->clickArticleTab();
    }

    /** @dataProvider invalidArticlesProvider */
    public function testBoundaryValues(
        $title,
        $meta,
        $metaDescription,
        $metaKeywords,
        $url,
        $categoryId,
        $tagIds,
        $picture,
        $pictureBlog,
        $description,
        $content,
        $mostReaded,
        $published,
        $notPublished,
        $submit,
        $error
    ){
        $this->clickAddNewArticle();
        $articleForm = new ArticleForm($this->driver);
        $articleForm->fillForm(
            $title,
            $meta,
            $metaDescription,
            $metaKeywords,
            $url,
            $categoryId,
            $tagIds,
            $picture,
            $pictureBlog,
            $description,
            $content,
            $mostReaded,
            $published,
            $notPublished,
            $submit
        );
        $this->assertEquals($error, $this->driver->switchTo()->alert()->getText());
    }

    public function testEditArticle(){
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="grid-1000"]/td[7]/div/a[1]'))->click();

        $userForm = new ArticleForm($this->driver);
        $userForm->fillForm(
            'Updated',
            '',
            '',
            '',
            'updatedUrl',
            '',
            '2',
            '',
            '',
            'updatedDescription',
            '',
            '',
            '',
            '',
            true
        );

        $updatedArticle = $this->articleRepository->find('id', '1000')->fetch();
        $this->assertEquals(
            ['titleUpdated', 'updatedUrl', 'updatedDescription'],
            [$updatedArticle->title, $updatedArticle->url, $updatedArticle->description]
        );
    }

    public function testDeleteArticle(){
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="grid-1000"]/td[7]/div/a[2]'))->click();
        $this->driver->switchTo()->alert()->accept();
        sleep(1);

        $this->assertEquals(0, $this->articleRepository->find('id', '1000')->count());
    }

    public function invalidArticlesProvider(){
        $articles = array();
        //Boundary and EC, however image could not be uploaded
        $images = [
            'title', '', '', '', '', '', '', __DIR__ . '../images/tooBig.png', '', '', '', '', '', '', true, 'This field is required.'
        ];
        $images = [
            'title', '', '', '', '', '', '', '', __DIR__ . '../images/small.jpg', '', '', '', '', '', true, 'This field is required.'
        ];
        $mandatoryField = [
            'title', '', '', '', '', '', '', '', '', '', '', '', '', '', true, 'This field is required.'
        ];
        $onlyTagField = [
            'title', '', '', '', '', '2', '', '', '', '', '', '', '', '', true, 'This field is required.'
        ];
        $onlyCategoryField = [
            'title', '', '', '', '', '', '2', '', '', '', '', '', '', '', true, 'This field is required.'
        ];
        $articles[] = $mandatoryField;
        $articles[] = $onlyTagField;
        $articles[] = $onlyCategoryField;

        return $articles;
    }

    private function clickArticleTab(){
        $this->driver->findElement(WebDriverBy::id('menu-articles'))->click();
    }

    private function clickAddNewArticle(){
        $this->driver->findElement(WebDriverBy::id('add-article'))->click();
    }

    public function tearDown()
    {
        $this->articleRepository->remove(['id' => 1000]);
        $this->driver->quit();
    }

}