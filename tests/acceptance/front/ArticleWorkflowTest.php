<?php

namespace Test\Selenium;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../bootstrap.php';

class ArticleWorkflowTest extends TestCase
{
    /** @var  RemoteWebDriver */
    public $driver;

    public function setUp()
    {
        $this->driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            DesiredCapabilities::firefox()
        );
        $this->driver->get('http://localhost/school_blog/www/test');
    }

    public function testBackButton()
    {
        $this->driver->findElement(WebDriverBy::id('back-to-blog'))->click();
        $this->assertEquals('http://localhost/school_blog/www/blog', $this->driver->getCurrentURL());
    }

    public function tearDown()
    {
        $this->driver->quit();
    }

}