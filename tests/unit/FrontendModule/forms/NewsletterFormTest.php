<?php

namespace Test\FrontendModule;

use App\Forms\NewsletterForm;
use App\Model\EmailRepository;
use Nette\Environment;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../../bootstrap.php';

class NewsletterFormTest extends TestCase
{

    /** @var NewsletterForm */
    private $newsletterForm;

    /** @var EmailRepository */
    private $emailRepo;

    public function __construct() {
        parent::__construct();
        $this->newsletterForm = Environment::getContext()->getByType(NewsletterForm::class);
        $this->emailRepo = Environment::getContext()->getByType(EmailRepository::class);
    }

    public function setUp()
    {
        $this->emailRepo->remove([]);
    }

    public function testAddEmail(){
        $form = $this->newsletterForm->createComponentNewsletter()->setDefaults([
            'email' => 'test@test.cz'
        ]);
        $this->newsletterForm->submitForm($form);

        $this->assertEquals('test@test.cz', $this->emailRepo->findAll()->fetch()['email']);
    }

    public function tearDown()
    {
        $this->emailRepo->remove([]);
    }

}
