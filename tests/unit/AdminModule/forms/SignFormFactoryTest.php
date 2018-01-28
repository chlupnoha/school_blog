<?php

namespace Test\FrontendModule;

use App\Forms\SignFormFactory;
use App\Model\UserRepository;
use Nette\Environment;
use Nette\Security\User;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../../bootstrap.php';

class SignFormFactoryTest extends TestCase
{

    /** @var SignFormFactory */
    private $signInForm;

    /** @var UserRepository */
    private $userManager;

    /** @var User */
    private $user;

    public function __construct() {
        parent::__construct();
        $this->signInForm = Environment::getContext()->getByType(SignFormFactory::class);
        $this->userManager = Environment::getContext()->getByType(UserRepository::class);
        $this->user = Environment::getContext()->getByType(User::class);
    }

    /** @dataProvider invalidLoginProvider */
    public function testInvalidLogin($name, $password){
        $this->signInForm->create()->setDefaults([
            'username' => $name,
            'password' => $password
        ]);

        $this->assertNull($this->user->getId());
    }

    public static function invalidLoginProvider(){
        $result = array();
        $logins = file_get_contents(__DIR__ . "/../logins.txt");
        foreach (explode("  ", $logins) as $l){
            $result[] = [$l[0], $l[1]];
        }
        dump($result);
        return $result;
    }

}
