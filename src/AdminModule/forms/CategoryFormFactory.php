<?php

namespace App\Forms;

use App\Model\CategoryRepository;
use App\Model\TagRepository;
use App\Model\UserRepository;
use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;


class CategoryFormFactory extends Nette\Object
{
	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var  int|null */
	private $categoryId;

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

	public function __construct(CategoryRepository $tagRepository)
	{
		$this->categoryRepository = $tagRepository;
	}

	public function create() : Form
	{

        $form = new Form();
        $form->addText( 'name', 'Název' )
            ->setRequired();

        $form->addSubmit( 'create', 'Vytvořit' )
            ->setAttribute('id', 'sumbit');

        $form->setRenderer( new Bs3FormRenderer() );

        $category = $this->categoryRepository->find('id', $this->categoryId)->fetch();
        if($category){
            $form->setDefaults($category);
        }

        $form->setRenderer( new Bs3FormRenderer() );

        $form->onSuccess[] = $this->formSubmit;
		return $form;
	}

    public function formSubmit( Form $form )
    {
        try{
            if( $this->categoryId ){
                $this->categoryRepository->update( ['id' => $this->categoryId], $form->getValues() );
            }else {
                $this->categoryRepository->add( $form->getValues() );
            }
        }catch (Nette\Database\UniqueConstraintViolationException $e){
            $form->addError('Aktualni kategorie jiz existuje.');
        }
    }

}
