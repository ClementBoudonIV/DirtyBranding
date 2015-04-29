<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

    $app = require_once __DIR__.'/app.php';

    $app->run();
