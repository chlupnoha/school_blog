<?php

namespace Test\Selenium;

use App\Forms\UserFormFactory;
use App\Model\UserRepository;
use App\Model\TagRepository;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Nette\Environment;
use PHPUnit\Framework\TestCase;
use Test\PageObject\SignUpForm;
use Test\PageObject\TagForm;
use Test\PageObject\UserForm;

require __DIR__ . '/../../bootstrap.php';

class TagWorkflowTest extends TestCase
{
    /** @var  RemoteWebDriver */
    public $driver;

    /** @var UserRepository */
    private $tagRepository;

    private $tagData = ['id' => 1000, 'name' => 'testTag'];

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        $this->tagRepository = Environment::getContext()->getByType(TagRepository::class);
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

        $this->tagRepository->add($this->tagData);
        $this->clickUserTab();
    }

    public function testCheckMandatoryForNewTag(){
        $this->clickAddNewTag();
        $userForm = new TagForm($this->driver);
        $userForm->fillForm("", true);
        $this->assertEquals("This field is required.", $this->driver->switchTo()->alert()->getText());
    }

    public function testDeleteTag(){
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="grid-1000"]/td[2]/div/a[2]'))->click();
        $this->driver->switchTo()->alert()->accept();
        sleep(1);

        $this->assertEquals(0, $this->tagRepository->find('id', '1000')->count());
    }

    public function testEditTag(){
        $this->driver->findElement(WebDriverBy::xpath('//*//*[@id="grid-1000"]/td[2]/div/a[1]'))->click();

        $userForm = new TagForm($this->driver);
        $userForm->fillForm('Update', true);

        $updatedUser = $this->tagRepository->find('id', '1000')->fetch();
        $this->assertEquals(['testTagUpdate'], [$updatedUser->name]);
    }

    private function clickUserTab(){
        $this->driver->findElement(WebDriverBy::id('menu-tags'))->click();
    }

    private function clickAddNewTag(){
        $this->driver->findElement(WebDriverBy::id('add-tag'))->click();
    }

    public function tearDown()
    {
        $this->tagRepository->remove(['id' => 1000]);
        $this->driver->quit();
    }

}