<?php

namespace Test\PageObject;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;

class CategoryForm
{
    private $nameId = 'frm-categoryForm-name';
    private $buttonId = 'sumbit';

    /** @return string */
    public function getNameId()
    {
        return $this->nameId;
    }

    /** @var WebDriver */
    private $driver;

    public function __construct(WebDriver $driver)
    {
        $this->driver = $driver;
    }

    public function fillForm($name, $submit = false){
        $this->driver->findElement(WebDriverBy::id($this->nameId))->sendKeys($name);
        if($submit){
            $this->driver->findElement(WebDriverBy::id($this->buttonId))->click();
        }
    }

}