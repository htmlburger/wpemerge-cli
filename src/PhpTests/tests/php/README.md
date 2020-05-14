# WP Emerge Unit Tests

## Initial Setup

Install WordPress and the WP Unit Test lib using the `install.sh` script. Change to the theme or plugin root directory and run:

    $ ./tests/php/bin/install.sh <db-name> <db-user> <db-password> [db-host] [wp-version]

Sample usage:

    $ ./tests/php/bin/install.sh my_app_tests root root localhost 4.8

**Important**: Make sure that the `<db-name>` database has been created. All data in that database will be removed during testing.

## Running Tests

Make sure `phpunit` is installed, change to the project root directory and type:

    $ phpunit

Refer to the [phpunit command line test runner reference](https://phpunit.de/manual/current/en/phpunit-book.html#textui) for more information and command line options.
