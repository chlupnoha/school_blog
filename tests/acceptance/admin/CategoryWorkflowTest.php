<?php

namespace Test\Selenium;

use App\Forms\UserFormFactory;
use App\Model\CategoryRepository;
use App\Model\UserRepository;
use App\Model\TagRepository;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Nette\Environment;
use PHPUnit\Framework\TestCase;
use Test\PageObject\CategoryForm;
use Test\PageObject\SignUpForm;
use Test\PageObject\TagForm;
use Test\PageObject\UserForm;

require __DIR__ . '/../../bootstrap.php';

class CategoryWorkflowTest extends TestCase
{
    /** @var  RemoteWebDriver */
    public $driver;

    /** @var CategoryRepository */
    private $categoryRepository;

    private $tagData = ['id' => 1000, 'name' => 'testCategory'];

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        $this->categoryRepository = Environment::getContext()->getByType(CategoryRepository::class);
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

        $this->categoryRepository->add($this->tagData);
        $this->clickCategoryTab();
    }

    public function testUniqueCategoryName(){
        $this->clickAddNewCategory();
        $categoryForm = new CategoryForm($this->driver);
        $categoryForm->fillForm("testCategory", true);
        $errorMessage = $this->driver->findElement(WebDriverBy::xpath('//*[@id="frm-categoryForm"]/ul/li'))->getText();

        $this->assertEquals("Aktualni kategorie jiz existuje.", $errorMessage );
    }

    public function testCheckMandatoryForNewCategory(){
        $this->clickAddNewCategory();
        $categoryForm = new CategoryForm($this->driver);
        $categoryForm->fillForm("", true);

        $this->assertEquals("This field is required.", $this->driver->switchTo()->alert()->getText());
    }

    public function testDeleteTag(){
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="grid-1000"]/td[2]/div/a[2]'))->click();
        $this->driver->switchTo()->alert()->accept();
        sleep(1);

        $this->assertEquals(0, $this->categoryRepository->find('id', '1000')->count());
    }

    public function testEditTag(){
        $this->driver->findElement(WebDriverBy::xpath('//*//*[@id="grid-1000"]/td[2]/div/a[1]'))->click();

        $categoryForm = new CategoryForm($this->driver);
        $categoryForm->fillForm('Update', true);

        $updatedCategory = $this->categoryRepository->find('id', '1000')->fetch();
        $this->assertEquals(['testCategoryUpdate'], [$updatedCategory->name]);
    }

    private function clickCategoryTab(){
        $this->driver->findElement(WebDriverBy::id('menu-categories'))->click();
    }

    private function clickAddNewCategory(){
        $this->driver->findElement(WebDriverBy::id('add-category'))->click();
    }

    public function tearDown()
    {
        $this->categoryRepository->remove(['id' => 1000]);
        $this->driver->quit();
    }

}