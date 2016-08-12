# messageboard

  * Confirguration
    * Software
      * PHP 5.6  
      * MySQL 5.7.13
      * Twig
        * Install composer and run `composer require twig/twig:~1.0` in `/var/www/html`
    * Database
      * Import MySQL schema from `database/dbschema.sql`
      * The code currently expects a schema named 'board' with a user named 'board'
    * Apache config
     * Enable rewrite with command `a2enmod rewrite`
     * Add following to apache config file usually located in `/etc/apache2/sites-available`
     ```
RewriteEngine On
RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-f
RewriteCond $1 !index\.
RewriteCond %{DOCUMENT_ROOT}/board/$1/index.php !-f
RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-d
RewriteRule ^/board/(.*?)/(.*) /board/index.php?$1/$2 [L]

RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-f
RewriteCond $1 !index\.
RewriteCond %{DOCUMENT_ROOT}/board/user/$1/index.php !-f
RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-d
RewriteRule ^/board/user/([a-zA-Z0-9\_\-\.]*) /board/user/index.php?$1 [L]

SetEnv HTTPS         0
SetEnv BOARD_PATH    /board/
SetEnv SQL_PASS      MysqlPassword
SetEnv CAPTCHA_KEY   CaptchaSecretKey
SetEnv SESSION_SALT  $2y$10$SzYpHOTbLRSIr0kUgRS4q.

     ```
     * Set HTTPS to 1 if using HTTPS
     * Generate a random salt and replace example, replace passwords and board path if needed.
     * restart apache `service apache2 restart`
