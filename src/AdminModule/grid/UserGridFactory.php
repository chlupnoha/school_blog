<?php

namespace App\Grids;

use App\Model\UserRepository;
use Mesour\DataGrid\Components\Link;
use Mesour\DataGrid\Grid;
use Mesour\DataGrid\NetteDbDataSource;
use Nette;


class UserGridFactory extends Nette\Object
{
	/** @var UserRepository */
	private $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

    public function createComponentGrid() : Grid{
        $source = new NetteDbDataSource( $this->userRepository->findAll() );

        $grid = new Grid();

        $grid->setPrimaryKey( 'id' );

        $grid->addText( 'name', 'Jmeno' );

        $grid->addText( 'description', 'Popis' );

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
            ->setConfirm( 'Opravdu chcete odstranit uzivatele' )
            ->addAttribute( 'href', new Link( ['href' => 'delete!', 'parameters' => ['id' => '{id}']] ) );

        return $grid;
    }

}
