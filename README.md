# Standalone runner for Doctrine Migrations

This project provides `vendor/bin/migrations.php` which allows running
docrine/migrations[1] without any framework integration.


[1]: http://docs.doctrine-project.org/projects/doctrine-migrations/en/latest/reference/introduction.html

## Installation

    composer require akrabat/rka-doctrine-migrations-runner


## Usage

1. Create a `migrations` folder
2. Create a `migrations.yml` file containing:

        name: Doctrine Migrations
        migrations_namespace: Migrations
        table_name: migrations
        migrations_directory: migrations

3. Create a migrations-db.php file containing your database connection details:

        <?php
        return array(
            'dbname'   => 'database',
            'user'     => 'username',
            'password' => 'mypassword',
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        );
        ?>

4. Test:

       php vendor/bin/migrations.php status


See the documentation[2] for the rest.

[2]: http://docs.doctrine-project.org/projects/doctrine-migrations/en/latest/reference/migration_classes.html
