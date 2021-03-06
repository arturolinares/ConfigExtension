ConfigExtension
===============

Silex extension to read php .ini files used for configurations.

## Installation

Add the following to composer.json.

    "autoload" : {
        "psr-0" : {
            "ConfigExtension": "ConfigExtension/src/ConfigExtension/"
        }
    }

This assumes the ConfigExtension folder is in the same directory as composer.json

Run: "composer dumpautoload"

## Usage

Here is an example of its usage.

We have this .ini file:

    [db]
    options.driver  = "pdo_pgsql"
    options.dbname  = "name"
    options.host    = "localhost"
    options.user    = "myuser"
    options.password = "mysecretpass"
    dbal.class_path = "%basepath%/vendor/doctrine/lib/vendor/doctrine-dbal/lib/"
    common.class_path = "%basepath%/vendor/doctrine/lib/vendor/doctrine-common/lib/"
    orm.class_path = "%basepath%/vendor/doctrine/lib/"

And the index.php:

    <?php

    require_once __DIR__ . '/../vendor/autoload.php';

    $app = new Silex\Application();

    $app->register(new \ConfigExtension\Extension\ConfigExtension(), array(
        // specify the .ini file to read
        'config.path' =>  __DIR__ . '/../config/app.ini',
        // and the var replacements
        'config.replacements' => array('basepath' => __DIR__ )
    ));

    // retrieve just one value form teh config file
    $db_name = $app['config']->get('db.name');

    // adds all the specified section to the silex application
    $app['config']->registerSection($app, 'db');

    // $app['db.options.driver'] now has pdo_pgsql

    $app->get('/', function () use ($app)
    {
        return var_export($app['config']->getSection('db'), true);
    });
    
    $app->run();

Visiting '/' shows:

    array (
      'db.options.driver' => 'pdo_pgsql',
      'db.options.dbname' => 'name',
      'db.options.host' => 'localhost',
      'db.options.user' => 'myuser',
      'db.options.password' => 'mysecredpass',
      'db.dbal.class_path' => '/Users/alinares/Sites/test/vendor/doctrine-dbal/lib/',
      'db.common.class_path' => '/Users/alinares/Sites/test/vendor/doctrine/lib/vendor/doctrine-common/lib/',
      'db.orm.class_path' => '/Users/alinares/Sites/test/vendor/doctrine/lib/',
    )
