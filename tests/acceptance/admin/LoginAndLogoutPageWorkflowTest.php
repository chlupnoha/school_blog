<?php

namespace Test\Selenium;

require __DIR__ . '/../../bootstrap.php';

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestCase;
use Test\PageObject\SignUpForm;

class LoginAndLogoutPageWorkflowTest extends TestCase
{
    /** @var  RemoteWebDriver */
    public $driver;

    /** @var  SignUpForm */
    public $loginForm;

    public function setUp()
    {
        $this->driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            DesiredCapabilities::firefox()
        );
        $this->driver->get('http://localhost/school_blog/www/admin');
        $this->loginForm = new SignUpForm($this->driver);
    }

    public function testLogIn(){
        $this->loginForm->login();
        $this->assertEquals('http://localhost/school_blog/www/admin/article/', $this->driver->getCurrentURL());
    }

    public function testBadLogin()
    {
        $this->loginForm->badLogin();
        $this->assertEquals('http://localhost/school_blog/www/admin/sign/in', $this->driver->getCurrentURL());
    }

    public function tearDown()
    {
        $this->driver->quit();
    }


}