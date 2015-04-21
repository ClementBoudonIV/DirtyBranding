<?php

	$ipoffices = $app['controllers_factory'];

    /*
    GET /ipoffices/
    Retourne la liste des Bureaux de Propriété Intelectuelle supporté
    */
    $available_ipoffices = array(
        'inpi'
    );

    $ipoffices->get('/',
        function (Silex\Application $app) use ($available_ipoffices) {
            return json_encode($available_ipoffices);
    });

    return $ipoffices;