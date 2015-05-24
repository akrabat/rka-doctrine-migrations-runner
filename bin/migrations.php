<?php
/**
 * Command line script to run Migrations
 * Inspired by phar-cli-stup.php
 */
use Symfony\Component\Console;
use Doctrine\DBAL\Migrations\MigrationsVersion;
use Doctrine\DBAL\Migrations\Tools\Console\Command as MigrationsCommand;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

require ('vendor/autoload.php');

// Create the database connection and set up bit for boolean mapping
if (file_exists('migrations-db.php')) {
    $params = include 'migrations-db.php';
    if (!is_array($params)) {
        throw new \InvalidArgumentException(
            'The connection file has to return an array with database configuration parameters.'
        );
    }
    $connection = \Doctrine\DBAL\DriverManager::getConnection($params);
} else {
    throw new \InvalidArgumentException(
        'You have to specify a --db-configuration file or pass a Database Connection as a dependency to the Migrations.'
    );
}

// Instantiate console application
$cli = new Console\Application('Doctrine Migrations', MigrationsVersion::VERSION());
$cli->setCatchExceptions(true);

$helperSet = new Console\Helper\HelperSet();
$helperSet->set(new Console\Helper\DialogHelper(), 'dialog');
$helperSet->set(new ConnectionHelper($connection), 'connection');
$cli->setHelperSet($helperSet);

// Add Migrations commands
$commands = array();
$commands[] = new MigrationsCommand\ExecuteCommand();
$commands[] = new MigrationsCommand\GenerateCommand();
$commands[] = new MigrationsCommand\LatestCommand();
$commands[] = new MigrationsCommand\MigrateCommand();
$commands[] = new MigrationsCommand\StatusCommand();
$commands[] = new MigrationsCommand\VersionCommand();

// remove the "migrations:" prefix on each command name
foreach ($commands as $command) {
    $command->setName(str_replace('migrations:', '', $command->getName()));
}
$cli->addCommands($commands);


// Run!
$cli->run();

if (!function_exists('substring_in_array')) {
    /**
     * A version of in_array() that does a sub string match on $needle
     *
     * @param  mixed   $needle    The searched value
     * @param  array   $haystack  The array to search in
     * @return boolean
     */
    function substring_in_array($needle, array $haystack)
    {
        $filtered = array_filter($haystack, function ($item) use ($needle) {
            return false !== strpos($item, $needle);
        });

        return !empty($filtered);
    }
}
