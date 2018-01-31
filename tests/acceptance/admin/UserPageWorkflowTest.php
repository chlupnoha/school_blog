<?php

namespace Test\Selenium;

use App\Forms\UserFormFactory;
use App\Model\UserRepository;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Nette\Environment;
use PHPUnit\Framework\TestCase;
use Test\PageObject\SignUpForm;
use Test\PageObject\UserForm;

require __DIR__ . '/../../bootstrap.php';

class UserPageWorkflowTest extends TestCase
{
    /** @var  RemoteWebDriver */
    public $driver;

    /** @var UserRepository */
    private $userManager;

    private $userData = ['id' => 1000, 'name' => 'name', 'password' => 'password'];

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        $this->userManager = Environment::getContext()->getByType(UserRepository::class);
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

        $this->userManager->add($this->userData);
        $this->clickUserTab();
    }

    /** @dataProvider invalidUsersProvider */
    public function testCheckMandatoryForNewUser($name, $password, $description, $error){
        $this->clickAddNewUser();
        $userForm = new UserForm($this->driver);
        $userForm->fillForm($name, $password, $description, true);
        $this->assertEquals($error, $this->driver->switchTo()->alert()->getText());
    }

    public function testDeleteUser(){
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="grid-1000"]/td[3]/div/a[2]'))->click();
        $this->driver->switchTo()->alert()->accept();
        sleep(1);

        $this->assertEquals(0, $this->userManager->find('id', '1000')->count());
    }

    public function testEditUser(){
        $this->driver->findElement(WebDriverBy::xpath('//*[@id="grid-1000"]/td[3]/div/a[1]'))->click();

        $userForm = new UserForm($this->driver);
        $name = $this->driver->findElement(WebDriverBy::id($userForm->getNameId()))->getText();
        $userForm->updateForm($name, 'updateDescription', true);

        $updatedUser = $this->userManager->find('id', '1000')->fetch();
        $this->assertEquals(['name', 'updateDescription'], [$updatedUser->name, $updatedUser->description]);
    }

    public function invalidUsersProvider(){
        $users = array();
        $users[] = ['name', '', 'description', 'Please enter your password.'];
        $users[] = ['', 'password', 'description', 'Please enter your username.'];
        return $users;
    }

    private function clickUserTab(){
        $this->driver->findElement(WebDriverBy::id('menu-users'))->click();
    }

    private function clickAddNewUser(){
        $this->driver->findElement(WebDriverBy::id('add-user'))->click();
    }

    public function tearDown()
    {
        $this->userManager->remove(['id' => 1000]);
        $this->driver->quit();
    }

}