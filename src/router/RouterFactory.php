<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

class RouterFactory
{

		/**
		 * @return Nette\Application\IRouter
             * <name> a pak presenter => Article, action => default
		 */
		public static function createRouter()
		{
				$router = new RouteList;
				$router[] = new Route( '<module admin>/<presenter>/<action>[/<id>]', 'Homepage:default' );
				$router[] = new Route( 'blog', 'Blog:default' );
				$router[] = new Route( '<url>', 'Article:default' );
				$router[] = new Route( '', 'Blog:default' );
				return $router;
		}

}
