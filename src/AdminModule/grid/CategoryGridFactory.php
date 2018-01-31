<?php

namespace App\Grids;

use App\Model\CategoryRepository;
use App\Model\UserRepository;
use Mesour\DataGrid\Components\Link;
use Mesour\DataGrid\Grid;
use Mesour\DataGrid\NetteDbDataSource;
use Nette;


class CategoryGridFactory extends Nette\Object
{
	/** @var CategoryRepository */
	private $categoryRepository;

	public function __construct(CategoryRepository $categoryRepository)
	{
		$this->categoryRepository = $categoryRepository;
	}

    public function createComponentGrid() : Grid{
        $source = new NetteDbDataSource( $this->categoryRepository->findAll() );

        $grid = new Grid( );

        $grid->setPrimaryKey( 'id' ); // primary key is now used always

        $grid->addText( 'name', 'Název' );

        $grid->enablePager( 10 );

        $grid->setDataSource( $source );

        $actions = $grid->addActions( 'Akce' );

        $actions->addButton()
            ->setType( 'btn-primary' )
            ->setIcon( 'glyphicon-pencil' )
            ->addAttribute( 'href', new Link( ['href' => 'create', 'parameters' => ['id' => '{id}']] ) );

        $actions->addButton()
            ->setType( 'btn-danger' )
            ->setIcon( 'glyphicon-remove' )
            ->setConfirm( 'Opravdu chcete odstranit kategorii' )
            ->addAttribute( 'href', new Link( ['href' => 'delete!', 'parameters' => ['id' => '{id}']] ) );

        return $grid;
    }

}
