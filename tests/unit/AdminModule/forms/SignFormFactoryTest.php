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

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        $this->signInForm = Environment::getContext()->getByType(SignFormFactory::class);
        $this->userManager = Environment::getContext()->getByType(UserRepository::class);
        $this->user = Environment::getContext()->getByType(User::class);
        parent::__construct(...func_get_args());
    }

    /** @dataProvider invalidLoginProvider */
    public function testInvalidLogins($name, $password){
        $this->signInForm->create()->setDefaults([
            'username' => $name,
            'password' => $password
        ]);

        $this->assertNull($this->user->getId());
    }

    public static function invalidLoginProvider(){
        $result = array();
        $logins = file_get_contents(__DIR__ . "/../../allpairs/logins.txt");
        foreach (explode("\n", $logins) as $line){
            $l = explode("\t", $line);
            $result[] = [$l[0], $l[1]];
        }
        return $result;
    }

}
