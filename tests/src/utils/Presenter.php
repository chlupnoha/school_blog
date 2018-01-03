<?php

namespace Test;

use Nette\Application\Request;
use Nette\DI\Container;
use Nette\Http\Response;
use Nette\Object;

class Presenter extends Object {

    private $container;
    private $presenter;
    private $presName;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * @param $presName string
     */
    public function init($presName) {
        $presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');
        $this->presenter = $presenterFactory->createPresenter($presName);
        $this->presenter->autoCanonicalize = FALSE;
        $this->presName = $presName;
    }

    /**
     * @param string $action
     * @param string $method
     * @param array $params
     * @param array $post
     * @return Response
     */
    public function test($action, $method = 'GET', $params = array(), $post = array()) {
        $params['action'] = $action;
        $request = new Request($this->presName, $method, $params, $post);
        $response = $this->presenter->run($request);
        return $response;
    }

//    public function testForm($action, $method = 'POST', $post = array()) {
//        $response = $this->test($action, $method, $post);
//
//        Assert::true($response instanceof RedirectResponse);
//
//        return $response;
//    }

}