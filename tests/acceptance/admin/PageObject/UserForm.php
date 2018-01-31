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

    /**
     * @return string
     */
    public function getNameId()
    {
        return $this->nameId;
    }

    /**
     * @return string
     */
    public function getDescriptionId()
    {
        return $this->descriptionId;
    }

    /** @var WebDriver */
    private $driver;

    public function __construct(WebDriver $driver)
    {
        $this->driver = $driver;
    }

    public function updateForm($name, $description, $submit = false){
        $this->fillForm($name, null, $description, $submit);
    }

    public function fillForm($name, $password, $description, $submit = false){
        $this->driver->findElement(WebDriverBy::id($this->nameId))->sendKeys($name);
        if($password){
            $this->driver->findElement(WebDriverBy::id($this->passwordId))->sendKeys($password);
        }
        $this->driver->findElement(WebDriverBy::id($this->descriptionId))->sendKeys($description);
        if($submit){
            $this->driver->findElement(WebDriverBy::xpath($this->buttonId))->click();
        }
    }

}