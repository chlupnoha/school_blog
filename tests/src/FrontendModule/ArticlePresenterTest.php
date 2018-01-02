<?php

namespace Test\FrontendModule;

require __DIR__ . '/../bootstrap.php';

use Nette;
use Nette\Application\Responses\RedirectResponse;
use Nette\Application\Responses\TextResponse;
use PHPUnit\Framework\TestCase;
use Test\Presenter;

class ArticlePresenterTest extends TestCase
{
    /** @var Presenter */
    private $tester;

    public function __construct() {
        parent::__construct();
        $this->tester = new Presenter(Nette\Environment::getContext());
    }

    public function setUp() {
        $this->tester->init('Article');
    }

    public function testRenderDefaultWithoutURL() {
        $res = $this->tester->test('default');

        $this->assertEquals(new RedirectResponse("http:///blog"), $res);

    }

    public function testRenderDefault(){
        $res = $this->tester->test('default', 'GET', ['url' => 'test']);

        $this->assertTrue($res instanceof TextResponse);
    }

}