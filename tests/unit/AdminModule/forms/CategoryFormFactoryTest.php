<?php

namespace Test\FrontendModule;

use App\Forms\CategoryFormFactory;
use App\Forms\TagFormFactory;
use App\Forms\UserFormFactory;
use App\Model\CategoryRepository;
use App\Model\TagRepository;
use App\Model\UserRepository;
use Nette\Environment;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../../bootstrap.php';

class CategoryFormFactoryTest extends TestCase
{

    /** @var CategoryFormFactory */
    private $categoryForm;

    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct() {
        parent::__construct();
        $this->categoryForm = Environment::getContext()->getByType(CategoryFormFactory::class);
        $this->categoryRepository = Environment::getContext()->getByType(CategoryRepository::class);
    }

    protected function setUp()
    {
        $this->categoryRepository->remove(['name' => 'test']);
    }

    public function testCreationOfNewUser(){
        $form = $this->categoryForm->create()->setDefaults([
            'name' => 'test'
        ]);
        $this->categoryForm->formSubmit($form);

        $result = $this->categoryRepository->findBy(['name' => 'test'])->fetch();
        $this->assertNotNull($result->name);
    }

}
