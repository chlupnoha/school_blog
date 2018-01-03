<?php

namespace App\Forms;

use App\Model\EmailRepository;
use Nette\Application\UI\Form;
use Nette\Object;

class NewsletterForm extends Object
{

    /** @var EmailRepository */
    public $email;

    public function __construct(EmailRepository $email)
    {
        $this->email = $email;
    }

    public function createComponentNewsletter() : Form
    {
        $form = new Form();
        $form->addText( 'email', 'Email' )
            ->addRule(Form::EMAIL)
            ->setRequired();

        $form->addSubmit( 'send', 'Odeslat' )->setAttribute( 'id', 'submit' );

        $form->onSuccess[] = $this->submitForm;

        return $form;
    }

    public function submitForm( Form $form )
    {
        $data = $form->getValues();
        $this->email->add( $data );
    }

}
