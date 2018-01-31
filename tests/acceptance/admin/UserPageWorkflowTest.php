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

        $this->clickUserTab();
        $this->userManager->add($this->userData);
    }

    /** @dataProvider invalidUsersProvider */
    public function testCheckMandatoryForNewUser($name, $password, $description, $error){
        $this->clickAddNewUser();
        $userForm = new UserForm($this->driver);
        $userForm->fillForm($name, $password, $description, true);
        $this->assertEquals($error, $this->driver->switchTo()->alert()->getText());
    }

//    public function testDeleteUser(){
//
//        //todo create user
//        //
//        //$this->driver->get('http://localhost/school_blog/www/admin/');
//
//    }
//
//    public function testEditUser(){
//        //todo create user
//        //
//        //$this->driver->get('http://localhost/school_blog/www/admin/');
//
//    }

    public function invalidUsersProvider(){
        $users = array();
        $users[] = ['name', '', 'description', 'Please enter your password.'];
        $users[] = ['', 'password', 'description', 'Please enter your username.'];
        dump($users);
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