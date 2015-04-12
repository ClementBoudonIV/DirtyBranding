<?php 
    require_once __DIR__.'/../vendor/autoload.php';

    $app = new Silex\Application();
    $app['debug'] = true;

    $app->get('/api/v1/', function () use ($app) {
        return 'API DirtyBranding.';
    });

    $app->run();