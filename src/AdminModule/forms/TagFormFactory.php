<?php

namespace App\Forms;

use App\Model\TagRepository;
use App\Model\UserRepository;
use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;


class TagFormFactory extends Nette\Object
{
	/** @var TagRepository */
	private $tagRepository;

	/** @var  int|null */
	private $tagId;

    public function getTagId() {
        return $this->tagId;
    }

    public function setTagId($tagId) {
        $this->tagId = $tagId;
    }

	public function __construct(TagRepository $tagRepository)
	{
		$this->tagRepository = $tagRepository;
	}

	public function create() : Form
	{

        $form = new Form();
        $form->addText( 'name', 'Název' )
            ->setRequired();

        $form->addSubmit( 'create', 'Vytvořit' )
            ->setAttribute('id', 'sumbit');

        $form->setRenderer( new Bs3FormRenderer() );

        $tag = $this->tagRepository->find('id', $this->tagId)->fetch();
        if($tag){
            $form->setDefaults($tag);
        }

        $form->setRenderer( new Bs3FormRenderer() );

        $form->onSuccess[] = $this->formSubmit;
		return $form;
	}

    public function formSubmit( Form $form )
    {
        try{
            if( $this->tagId ){
                $this->tagRepository->update( ['id' => $this->tagId], $form->getValues() );
            }else {
                $this->tagRepository->add( $form->getValues() );
            }
        }catch (Nette\Database\UniqueConstraintViolationException $e){
            $form->addError('Aktualni tag jiz existuje.');
        }
    }

}
