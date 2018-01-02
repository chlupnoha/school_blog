<?php

namespace Test\FrontendModule;

use Nette;
use Nette\Environment;
use PHPUnit\Framework\TestCase;
use Test\Presenter;

require __DIR__ . '/../../bootstrap.php';

class BlogPresenterTest extends TestCase
{

    /** @var Presenter */
    private $tester;

    public function __construct() {
        parent::__construct();
        $this->tester = new Presenter(Environment::getContext());
    }

    public function setUp() {
        $this->tester->init('Blog');
    }

    public function testRenderDefault() {
        $res = $this->tester->test('default');

        $this->assertTrue($res instanceof Nette\Application\Responses\TextResponse);
    }

    public function testRenderDefaultNotExistingTagAndCategory() {
        $res = $this->tester->test('default', 'GET', ['category_id' => 9999,'tag_id' => 9999]);

        $this->assertTrue($res instanceof Nette\Application\Responses\TextResponse);
    }

}
