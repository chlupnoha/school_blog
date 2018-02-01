<?php

namespace App\Model;

use Nette;
use Nette\Utils\Image;
use Carrooi\ImagesManager\ImagesManager;
use Nette\Utils\Random;

class PictureRepository extends Repository
{

		/**
		 * @var ImagesManager
		 */
		private $imagesManager;

		public function __construct( Nette\Database\Context $db, ImagesManager $imagesManager )
		{
				parent::__construct( $db );
				$this->imagesManager = $imagesManager;
		}

		public function uploadPicture( Image $image, $contentType, $width = null, $height = null, $namespace = "upload" )
		{
				switch($contentType)
				{
						case 'image/gif':
								$extension = 'git';
								break;
						case 'image/png':
								$extension = 'png';
								break;
						case 'image/jpeg':
								$extension = 'jpg';
								break;
						default:
								$extension = 'jpg';
				}
				$name = $namespace . '/' . Random::generate( 30 ) . ".$extension";
				if( $height && $width )
				{
						$image->resize( $width, $height, Image::STRETCH );
				}
				elseif( $height || $width )
				{
						$image->resize( $width, $height );
				}
				$this->imagesManager->upload( $image, $namespace, $name );

				return $this->getTable()->insert( array('name' => $name) )->getPrimary();
		}

		public function tryUpload( $picture, $width = null, $height = null )
		{
				if( $picture && $picture->isImage() )
				{
						$image = $picture->toImage();
						return $this->uploadPicture( $image, null, $width, $height );
				}
				return null;
		}

}
