mytomatoes.com
==============

mytomatoes.com helps you with the pomodoro technique by Francesco Cirillo - it's an online tomato kitchen timer and pomodoro tracker.

Setting up development environment
----------------------------------
* Install PHP and MySQL.
* Create empty database `mytomatoes`
* Create folder `/config` in mytomatoes-root
* Add database configuration file:

**`/config/db_config.php`**

    <?php
      class DBConfig {
        const HOST = 'localhost';
        const USERNAME = '';
        const PASSWORD = '';
        const DATABASE = 'mytomatoes';
      }
    ?>

* Add mail configuration file:

**`/config/mail_config.php`:**

    <?php
      class MailConfig {
        const HOST = 'localhost';
        const PORT = 10026;
        const OVERRIDE_ADDRESS = false; // set to your emailaddress to override all
        const ADD_TO_SUBJECT = '[dev] ';
      }
    ?>

* Add another database configuration file :-P

**`/migrater/config/local.yml`:**

    host: localhost
    username: 
    password: 
    database: mytomatoes

* Run the migrations to setup database:

    `cd migrater && ruby -I bin:lib bin/migrate.rb local`

* Point apache to `/source/www/`


Running the tests
-----------------
* Run the PHP-tests with: `cd source/www/tests && php ts_all_tests.php text`
* Run the JavaScript-tests with jsTestDriver from mytomatoes-root


Contributing
------------

If you want to help out with features or bug fixes, that's awesome.
Check out `backlog.txt` for inspiration.

* Fork the project.
* Make your feature addition or bug fix.
* Don't forget tests. This is important so I don't break it in a
  future version unintentionally.
* Commit and send me a merge request.
