<?php

namespace Test\PageObject;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;

class UserForm
{
    private $nameId = 'frm-userForm-name';
    private $passwordId = 'frm-userForm-password';
    private $descriptionId = 'frm-userForm-description';
    private $buttonId = '//*[@id="sumbit"]';

    /** @var WebDriver */
    private $driver;

    public function __construct(WebDriver $driver)
    {
        $this->driver = $driver;
    }

    public function fillForm($name, $password, $description, $submit = false){
        $this->driver->findElement(WebDriverBy::id($this->nameId))->sendKeys($name);
        $this->driver->findElement(WebDriverBy::id($this->passwordId))->sendKeys($password);
        $this->driver->findElement(WebDriverBy::id($this->descriptionId))->sendKeys($description);
        if($submit){
            $this->driver->findElement(WebDriverBy::xpath($this->buttonId))->click();
        }
    }

}