<?php

namespace Test\PageObject;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;

class SignUpForm
{
    private $nameId = 'frm-signInForm-username';
    private $passwordId = 'frm-signInForm-password';
    private $buttonXpath = '//*[@id="sumbit"]';

    /** @var WebDriver */
    private $driver;

    public function __construct(WebDriver $driver)
    {
        $this->driver = $driver;
    }

    private function fillForm($name, $password){
        $this->driver->findElement(WebDriverBy::id($this->nameId))->sendKeys($name);
        $this->driver->findElement(WebDriverBy::id($this->passwordId))->sendKeys($password);

    }

    public function login(){
        $this->fillForm('admin', 'admin');
        $this->driver->findElement(WebDriverBy::xpath($this->buttonXpath))->click();
    }

    public function badLogin(){
        $this->fillForm('blabla', 'blabladddd');
        $this->driver->findElement(WebDriverBy::xpath($this->buttonXpath))->click();
    }

}