# messageboard

  * Confirguration
    * Software
      * PHP 5.6  
      * MySQL 5.7.13
      * Twig
        * Install composer and run `composer require twig/twig:~1.0` in `/var/www/html`
    * Database
      * Import MySQL schema from `database/dbschema.sql`
    * Passwords
      * `/var/www/pw.php` file with passwords stored as follows. Change sample session salt.
```
<?php
$pass = array(
    'sql'       => 'YourDatabasePassword',
    'captcha'   => 'YourCaptchaSecretCode',
    'sesSalt'   => '$2y$10$SzYpHOTbLRSIr0kUgRS4q.'
);
```
