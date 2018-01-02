Nette ZKS
=============

Semestral project for ZKS 


Installing
----------
Run following commands for creating 
        
		cd my-app
		mkdir tmp tmp/log tmp/temp
		chmod 644 tmp/log tmp/temp
		composer install

It is also needed to create `src/config/config.local.neon`
       
        database:
            dsn: 'mysql:host=127.0.0.1;dbname=school_blog'
            user: root
            password: root
            options:
                lazy: yes

To create database you have to run `database.sql`


It is CRITICAL that whole `app`, `log` and `temp` directories are NOT accessible
directly via a web browser! See [security warning](http://nette.org/security-warning).


License
-------
- Nette: New BSD License or GPL 2.0 or 3.0 (http://nette.org/license)
- Adminer: Apache License 2.0 or GPL 2 (http://www.adminer.org)
