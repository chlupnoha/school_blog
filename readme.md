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


Testing
----------
For selenium tests the selenium server is required(http://www.seleniumhq.org/download/) and runned by following command:
    
		java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false


License
-------
- Nette: New BSD License or GPL 2.0 or 3.0 (http://nette.org/license)
- Adminer: Apache License 2.0 or GPL 2 (http://www.adminer.org)


Notes for submission
-------

Boundary Values And Equivalence
- Classes Analysis
-- Boundary values and equivalence classes are in ArticleFormPageWorkFlow in case of Image max size
- Input Data Combination 
-- Many cases from data provider
- Process Testing Design test case scenarios for 
-- //todo ??? - dunno exactly what I created
- Data Consistency Testing Design test case scenarios 
-- Every WorkflowTest
- Test Case Scenarios 
-- Workflow tests
- Selected automated tests: Automated Tests - Option 3 - Both Front-End and Unit Tests