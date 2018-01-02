<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

use Nette;

/**
 * Provádí operace nad databázovou tabulkou.
 */
abstract class Repository extends Nette\Object
{

		/** @var Nette\Database\Context */
		protected $connection;

		public function __construct( Nette\Database\Context $db )
		{
				$this->connection = $db;
		}

		public function getConnection()
		{
				return $this->connection;
		}

		/**
		 * Vrací objekt reprezentující databázovou tabulku.
		 * @return Nette\Database\Table\Selection
		 */
		protected function getTable()
		{
				// název tabulky odvodíme z názvu třídy
				preg_match( '#(\w+)Repository$#', get_class( $this ), $m ); //this is main method when u get name form table!
				return $this->connection->table( lcfirst( $m[1] ) );
		}

		public function add( $data )
		{
				return $this->getTable()->insert( $data );
		}

		public function update( array $by, $data )
		{
				return $this->getTable()->where( $by )->update( $data );
		}

		public function remove( array $by )
		{
				return $this->getTable()->where( $by )->delete();
		}

		/**
		 * Vrací všechny řádky z tabulky.
		 * @return Nette\Database\Table\Selection
		 */
		public function findAll()
		{
				return $this->getTable();
		}

		/**
		 * Vrací řádky podle filtru, např. array('name' => 'John').
		 * @return Nette\Database\Table\Selection
		 */
		public function findBy( array $by )
		{
				return $this->getTable()->where( $by );
		}

		/**
		 * Vrací řádky podle filtru, např. array('name' => 'John').
		 * @return Nette\Database\Table\Selection
		 */
		public function findLike( $key, $string, $before = '%', $after = '%' )
		{
				return $this->getTable()->where( "$key LIKE ?", $before . $string . $after );
		}

		/**
		 * Vrací řádky podle filtru
		 * @return Nette\Database\Table\Selection
		 */
		public function find( $key, $value, $limit = null, $offset = null, $order = null )
		{
				if( $limit != null )
				{
						if( $order != null )
						{
								return $this->getTable()->where( $key, $value )->order( $order )->limit( $limit, $offset );
						}
						else
						{
								return $this->getTable()->where( $key, $value )->limit( $limit, $offset );
						}
				}
				else
				{
						return $this->getTable()->where( $key, $value );
				}
		}

		public function fetchPairsSelectBox( $key = 'id', $value = 'name', $orderBy = 'name' )
		{
				return $this->getTable()->order( $orderBy )->fetchPairs( $key, $value );
		}

}
