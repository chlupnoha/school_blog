<?php

namespace Test\FrontendModule;

require __DIR__ . '/../../../bootstrap.php';

use Nette;
use Nette\Application\Responses\RedirectResponse;
use Nette\Application\Responses\TextResponse;
use PHPUnit\Framework\TestCase;
use Test\Presenter;

class ArticlePresenterTest extends TestCase
{
    /** @var Presenter */
    private $presenter;

    public function __construct() {
        parent::__construct();
        $this->presenter = new Presenter(Nette\Environment::getContext());
    }

    public function setUp() {
        $this->presenter->init('Article');
    }

    public function testRenderDefaultWithoutURL() {
        $res = $this->presenter->test('default');

        $this->assertEquals(new RedirectResponse("http:///blog"), $res);
    }

    public function testRenderDefault(){
        $res = $this->presenter->test('default', 'GET', ['url' => 'test']);

        $this->assertTrue($res instanceof TextResponse);
    }

}