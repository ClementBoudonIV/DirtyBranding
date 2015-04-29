<?php
	$extensions = $app['controllers_factory'];

	$array_default_extensions = array(
        'fr',
        'com',
        'net'
    );
	
    /*
    GET /extensions/
    Retourne la liste des extensions par défaut.
    */
    $extensions->get('/',
        function (Silex\Application $app) use ($array_default_extensions) {
            return json_encode($array_default_extensions);
    });

    return $extensions;