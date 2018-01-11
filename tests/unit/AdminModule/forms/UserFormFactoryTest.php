<?php

namespace Test\FrontendModule;

use App\Forms\UserFormFactory;
use App\Model\UserRepository;
use Nette\Environment;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../../bootstrap.php';

class UserFormFactoryTest extends TestCase
{

    /** @var UserFormFactory */
    private $userForm;

    /** @var UserRepository */
    private $userRepository;

    public function __construct() {
        parent::__construct();
        $this->userForm = Environment::getContext()->getByType(UserFormFactory::class);
        $this->userRepository = Environment::getContext()->getByType(UserRepository::class);
    }

    protected function setUp()
    {
        $this->userRepository->findAll()->delete();
    }

    //todo here use parametrization
    public function testCreationOfNewUser(){
        $form = $this->userForm->create()->setDefaults([
            'user' => 'test',
            'password' => 'test',
            'description' => 'description'
        ]);
        $this->userForm->formSubmit($form);

        $result = $this->userRepository->findBy(['name' => 'test'])->fetch();
        $this->assertNotNull($result);
    }

}
