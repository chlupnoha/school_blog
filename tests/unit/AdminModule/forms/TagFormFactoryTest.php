<?php

namespace Test\FrontendModule;

use App\Forms\TagFormFactory;
use App\Forms\UserFormFactory;
use App\Model\TagRepository;
use App\Model\UserRepository;
use Nette\Environment;
use PHPUnit\Framework\TestCase;
use Test\PageObject\TagForm;

require __DIR__ . '/../../../bootstrap.php';

class TagFormFactoryTest extends TestCase
{

    /** @var TagFormFactory */
    private $tagForm;

    /** @var TagRepository */
    private $tagRepository;

    public function __construct() {
        parent::__construct();
        $this->tagForm = Environment::getContext()->getByType(TagFormFactory::class);
        $this->tagRepository = Environment::getContext()->getByType(TagRepository::class);
    }

    protected function setUp()
    {
        $this->tagRepository->remove(['name' => 'test']);
    }

    public function testCreationOfNewUser(){
        $form = $this->tagForm->create()->setDefaults([
            'name' => 'test'
        ]);
        $this->tagForm->formSubmit($form);

        $result = $this->tagRepository->findBy(['name' => 'test'])->fetch();
        $this->assertNotNull($result->name);
    }

}
