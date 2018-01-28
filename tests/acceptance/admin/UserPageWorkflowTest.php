<?php

namespace Test\Selenium;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use Test\PageObject\SignUpForm;

require __DIR__ . '/../../bootstrap.php';

class UserPageWorkflowTest extends TestCase
{
    /** @var  RemoteWebDriver */
    public $driver;

    public function setUp()
    {
        $this->driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            DesiredCapabilities::firefox()
        );
        $this->driver->get('http://localhost/school_blog/www/admin/');
        $loginForm = new SignUpForm($this->driver);
        $loginForm->login();
    }

    /** @Ignore */
    public function testNewUserButton()
    {
        $this->driver->findElement(WebDriverBy::cssSelector('#wrap > div > div.row.header-mb > div.col-sm-4.text-right > a'))->click();
        $this->assertEquals('http://localhost/school_blog/www/admin/user/create', $this->driver->getCurrentURL());
    }

    public function tearDown()
    {
        $this->driver->quit();
    }


}