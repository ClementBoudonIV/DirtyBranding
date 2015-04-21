<?php
    
    date_default_timezone_set('GMT');

    require_once __DIR__.'/../vendor/autoload.php';

    use PhpWhois\PhpWhois;

    $app = new Silex\Application();
    $app['debug'] = true;


    $app['PhpWhois'] = $app->share(function ($app) {
        return new Whois();
    });


    $app->register(new Silex\Provider\DoctrineServiceProvider(), array(
        'db.options' => array(
            'driver' => 'pdo_mysql',
            'dbhost' => 'localhost',
            'dbname' => 'DB_API',
            'user' => 'root',
            'password' => 'root',
        ),
    ));

    $app->get('/api/v1/', function () use ($app) {
        return 'API DirtyBranding.';
    });

    $app->mount('/api/v1/ideas', include __DIR__.'/../controllers/ideas.php');
    $app->mount('/api/v1/brands', include __DIR__.'/../controllers/brands.php');
    $app->mount('/api/v1/domains', include __DIR__.'/../controllers/domains.php');
    $app->mount('/api/v1/extensions', include __DIR__.'/../controllers/extensions.php');
    $app->mount('/api/v1/ipoffices', include __DIR__.'/../controllers/ipoffices.php');

    return $app;
