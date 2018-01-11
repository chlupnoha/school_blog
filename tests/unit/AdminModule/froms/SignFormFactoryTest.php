<?php

namespace Test\FrontendModule;

use App\Forms\SignFormFactory;
use App\Model\UserManager;
use Nette\Environment;
use Nette\Security\User;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../../bootstrap.php';

class SignFormFactoryTest extends TestCase
{

    /** @var SignFormFactory */
    private $signInForm;

    /** @var UserManager */
    private $userManager;

    /** @var User */
    private $user;

    public function __construct() {
        parent::__construct();
        $this->signInForm = Environment::getContext()->getByType(SignFormFactory::class);
        $this->userManager = Environment::getContext()->getByType(UserManager::class);
        $this->user = Environment::getContext()->getByType(User::class);
    }

    public function testInvalidLogin(){
        $this->signInForm->create()->setDefaults([
            'username' => 'UNKNOWN',
            'password' => 'UNKNOWN'
        ]);

        $this->assertNull($this->user->getId());
    }

}
