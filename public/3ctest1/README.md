  http://gitlab.3c-e.com/recrutement/test-symfony
 
 3CTest (symfony 3.4 php 7.2)
  
 1.Cloning Project
--------------

 * run command:  `git clone https://github.com/RPetrosjan/3ctest.git`
 * change folder: `cd 3ctest/public/3ctest1/`
 * run command: `composer install`

2.Configuration BD
--------------
  
  Save your 
   - database_host
   - database_name
   - database_user
   - database_password
  
3.Install BD et Config web Site
---------------
 - For installing BD you have dump sql 3ctest.sql
   * run command : `php bin/console doctrine:database:import 3ctest.sql`
 - Config Assets
   * run command : `php bin/console assets:install -e prod`
   * run command : `php bin/console assetic:dump -e prod`
   * run command : `php bin/console translation:update fr --force`
 

4.Admin    
---------------
  - user: admin
  - password: admin
 
 
Enjoy!
