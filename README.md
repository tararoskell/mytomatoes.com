mytomatoes.com
==============

mytomatoes.com helps you with the pomodoro technique by Francesco Cirillo - it's an online tomato kitchen timer and pomodoro tracker.

Setting up development environment
----------------------------------
* Install PHP and MySQL.
* Create empty database `mytomatoes`
* Create folder `/config` in mytomatoes-root
* Add `/config/db_config.php`:

    <?php
    class DBConfig {
      const HOST = 'localhost';
      const USERNAME = '';
      const PASSWORD = '';
      const DATABASE = 'mytomatoes';
    }
    ?>

* Add `/config/mail_config.php`:

    <?php
    class MailConfig {
      const HOST = 'localhost';
      const PORT = 10026;
      const OVERRIDE_ADDRESS = false; // set to your emailaddress to override all
      const ADD_TO_SUBJECT = '[dev] ';
    }

* Add `/migrater/config/local.yml`:

    host: localhost
    username: root
    password: roNNy
    database: mytomatoes

* Run the migrations to setup database:

    cd migrater && ruby -I bin:lib bin/migrate.rb local

* Point apache to `/source/www/`


Running the tests
-----------------
* Run the PHP-tests with: `cd tests && php ts_all_tests.php text`
* Run the JavaScript-tests with jsTestDriver
