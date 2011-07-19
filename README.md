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

1. Fork it.
2. Create a branch (`git checkout -b branch_name`)
3. Make your changes. Remember to add tests for your changes, so I won't inadvertently break it later.
4. Commit your changes (`git commit -am "Fixed stuff"`)
5. Push to the branch (`git push origin branch_name`)
6. Create an [Issue][1] with a link to your branch
7. Enjoy a refreshing sugarfilled Coke and wait

[1]: https://github.com/magnars/mytomatoes.com/issues