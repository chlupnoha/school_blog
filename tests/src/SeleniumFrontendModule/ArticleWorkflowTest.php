<?php

namespace Test\Selenium;

use PHPUnit_Extensions_Selenium2TestCase;

require __DIR__ . '/../bootstrap.php';

//java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false

class ArticleWorkflowTest extends PHPUnit_Extensions_Selenium2TestCase
{

    protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost/school_blog/www/test');
    }

    public function testBackButton()
    {
        $this->url('http://localhost/school_blog/www/test');
        $this->clickOnElement('back-to-blog');
        sleep(1);
        $this->assertEquals('http://localhost/school_blog/www/blog', $this->url());
    }

}