Nette ZKS
=============

Semestral project for ZKS 


Installing
----------
Run following commands for creating 
        
		cd my-app
		mkdir log
		composer create-project nette/sandbox my-app
		cd my-app

Make directories `temp` and `log` writable. Navigate your browser
to the `www` directory and you will see a welcome page. PHP 5.4 allows
you run `php -S localhost:8888 -t www` to start the web server and
then visit `http://localhost:8888` in your browser.

It is CRITICAL that whole `app`, `log` and `temp` directories are NOT accessible
directly via a web browser! See [security warning](http://nette.org/security-warning).


License
-------
- Nette: New BSD License or GPL 2.0 or 3.0 (http://nette.org/license)
- Adminer: Apache License 2.0 or GPL 2 (http://www.adminer.org)
