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

	/** @var  int */
	private $userId;

    public function getUserId(): int {
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
		$form = new Form;
		$form->addText('name', 'Username:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');

        $form->addText('description', 'Description:');

        $form->addSubmit( 'create', 'Vytvořit' );

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
