<?php

namespace Test\Model;

require __DIR__ . '/../../bootstrap.php';

use App\Model\UserRepository;
use Nette\Environment;
use Nette\Security\Identity;
use PHPUnit\Framework\TestCase;
use Test\DatabaseInitiator;

class UserRepositoryTest extends TestCase
{

    /** @var UserRepository */
    private $userManager;

    public function __construct() {
        parent::__construct();
        $this->userManager = Environment::getContext()->getByType(UserRepository::class);
    }

    public function setUp()
    {
        $this->userManager->findAll()->delete();
        $this->userManager->add([
            'id' => 7,
            'name' => 'admin',
            'password' => 'admin'
        ]);
    }

    public function testValidAuthorization(){
        $identity = $this->userManager->authenticate(['admin', 'admin']);
        $expectedIdentity = new Identity(7, null, ['id' => 7, 'name' => 'admin', 'description' => '']);

        $this->assertEquals($expectedIdentity, $identity);
    }

    /** @expectedException \Nette\Security\AuthenticationException */
    public function testInvalidAuthorization(){
        $this->userManager->authenticate(['unknown', 'unknown']);
    }

//    /** @expectedException \Nette\Database\UniqueConstraintViolationException */
//    public function testUniqueName(){
//        $this->userManager->add(['name' => 'admin', 'password' => 'admin']);
//    }

}

