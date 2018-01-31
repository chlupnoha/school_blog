<?php

namespace App\Forms;

use App\Model\UserRepository;
use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;


class UserFormFactory extends Nette\Object
{
	/** @var UserRepository */
	private $userManager;

	/** @var  int|null */
	private $userId;

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

	public function __construct(UserRepository $userManger)
	{
		$this->userManager = $userManger;
	}

	public function create() : Form
	{
	    $user = $this->userManager->find('id', $this->userId)->fetch();

		$form = new Form;
		$form->addText('name', 'Username:')
			->setRequired('Please enter your username.');

		if(!$user){
            $form->addPassword('password', 'Password:')
                ->setRequired('Please enter your password.');
        }

        $form->addText('description', 'Description:');

        $form->addSubmit( 'create', 'Vytvořit' )
            ->setAttribute('id', 'sumbit');

        if($user){
            $form->setDefaults($user);
        }

        $form->setRenderer( new Bs3FormRenderer() );

		$form->onSuccess[] = array($this, 'formSubmit');
		return $form;
	}

    public function formSubmit( Form $form )
    {
        try{
            if( $this->userId ){
                $this->userManager->update( ['id' => $this->userId], $form->getValues() );
            }else {
                $this->userManager->add( $form->getValues() );
            }
        }catch (Nette\Database\UniqueConstraintViolationException $e){
            $form->addError('Aktualni jmeno jiz existuje.');
        }
    }

}
