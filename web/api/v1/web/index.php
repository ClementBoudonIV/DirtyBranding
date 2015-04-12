<?php
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

    /*
    GET /ideas/{idea}/brands?suffixes[]=&prefixes[]=
    A partir d'une idée et optionnellement de prefixes / suffixes,
    nous retournons une liste de marque possible :
    - l'idée de base
    - + toutes les possibilités d'associations préfixes / suffixes
    Exemples :
        Idee 1 : Idee 1, Idee 1 Suf1, Pref1 Idee 1, Pref1 Idee 1 Suf 1, etc.
    */
    $app->get('/api/v1/ideas/{idea}/brands',
        function (Silex\Application $app, $idea) {

            $idea_escaped = $app->escape(urldecode($idea));

            $array_brands = array($idea_escaped);
            $array_prefixes = array();
            $array_suffixes = array();

            $default_separator = ' ';

            // Traitement des paramètres
            if(null !== $app['request']->get('prefixes'))
            {
                foreach($app['request']->get('prefixes') as $prefixes_src)
                {
                    $array_prefixes[]=$app->escape($prefixes_src);
                }
            }

            if(null !== $app['request']->get('suffixes'))
            {
                foreach($app['request']->get('suffixes') as $suffixes_src)
                {
                    $array_suffixes[]=$app->escape($suffixes_src);
                }
            }

            //Génération des associations
            foreach($array_prefixes as $prefix)
            {
                //Seulement préfixe
                $array_brands[] = $prefix
                .$default_separator
                .$idea_escaped;

                //Préfixe ET suffixe
                foreach($array_suffixes as $suffix)
                {
                    $array_brands[] = $prefix
                    .$default_separator
                    .$idea_escaped
                    .$default_separator
                    .$suffix;
                }
            }

            //Seulement suffixe
            foreach($array_suffixes as $suffix)
            {
                $array_brands[] = $idea_escaped
                .$default_separator
                .$suffix;
            }

            //Génération de toutes les possibilités
            //TODO

            return json_encode($array_brands);
    });

    /*
    GET /brands/{brand}/alternatives?separators[]=
    A partir d'un nom de marque et optionnelement de séparateur,
    nous retournons la liste des marques alternatives,
    où les éventuels espaces sont remplacé par chacun des séparateurs.
    Les séparateurs standards sont : tiret, vide.
    Nombre d'alternatives : 1 + ((Nombre de separateur+1)^(Nombre d'espace))
    Exemples :
        Idee 1 Suf1 = Idee 1 Suf1, Idee-1-Suf1, Idee1Suf1, Idee1 Suf1, etc.
    */
    $array_default_separators = array(
        '',
        '-'
    );

    $app->get('/api/v1/brands/{brand}/alternatives',
        function (Silex\Application $app, $brand)
        use ($array_default_separators){

            $brand_escaped = $app->escape(urldecode($brand));

            $array_alternatives = array($brand_escaped);
            $array_separators = array();

            if(null !== $app['request']->get('separators'))
            {
                foreach($app['request']->get('separators') as $separators_src)
                {
                    $array_separators[]=$app->escape($separators_src);
                }
            }

            $array_separators = array_merge(
                $array_separators,
                $array_default_separators
            );

            //Gestion des remplacements
            foreach ($array_separators as $separator) {
                $array_alternatives[] = str_replace(
                    ' ',
                    $separator,
                    $brand_escaped
                );
            }

            //Gestion de tous les remplacements
            //TODO

            return json_encode($array_alternatives);
    });

    /*
    GET /brands/{brand}/domains?extensions[]=
    A partir d'un nom de marque, nous retournons les domaines liés.
    Si il y a un caractère espae dans la marque, génération d'une erreur.
    */
    $app->get('/api/v1/brands/{brand}/domains',
        function (Silex\Application $app, $brand) {

            $brand_escaped = $app->escape(urldecode($brand));

            $array_domains = array();
            $array_extensions = array();

            if(null !== $app['request']->get('extensions'))
            {
                foreach($app['request']->get('extensions') as $extensions_src)
                {
                    $array_extensions[]=$app->escape($extensions_src);
                }
            }

            if(strpos($brand_escaped, ' ') !== false)
            {
                //Au moins un espace est présent dans le nom de la marque,
                //donc pas de domaine possible
                //TODO
                return json_encode(null);
            }
            else
            {
                foreach ($array_extensions as $extension) {
                    $array_domains[] = strtolower($brand_escaped).'.'.$extension;
                }
                return json_encode($array_domains);
            }


    });

    /*
    GET /domains/{domain}/availabe
    Retourne true pour la propriété "available" si le domaine est disponible.
    Utilisation du provider phpWhois https://github.com/phpWhois/phpWhois
    */

    $app->get('/api/v1/domains/{domain}/availabe',
        function (Silex\Application $app, $domain) {

            $domain_escaped = $app->escape(urldecode($domain));

            $availabe = false;

            $app['PhpWhois']->deepWhois = false;
            $result = $app['PhpWhois']->lookup($domain_escaped,false);

            if($result['regrinfo']['registered'] == 'no')
            {
                $availabe = true;
            }

            return json_encode($availabe);
    });

    /*
    GET /brands/{brand}/availabe?ipoffices[]=
    Retourne true pour la propriété "available" si la marque est disponible
    pour le bureau de propriété intellectuel choisi
    TODO
    */
    $app->get('/api/v1/brands/{brand}/availabe',
        function (Silex\Application $app, $brand) {

            $brand_escaped = $app->escape(urldecode($brand));

            $availabe = false;

            $array_ipoffices = array();

            if(null !== $app['request']->get('ipoffices'))
            {
                foreach($app['request']->get('ipoffices') as $ipoffices_src)
                {
                    $array_ipoffices[]=$app->escape($ipoffices_src);
                }
            }

            return json_encode($availabe);
    });

    /*
    GET /extensions/
    Retourne la liste des extensions par défautÒ.
    */
    $array_default_extensions = array(
        'fr',
        'com',
        'net'
    );

    $app->get('/api/v1/extensions',
        function (Silex\Application $app) use ($array_default_extensions) {

            return json_encode($array_default_extensions);
    });

    /*
    GET /ipoffices/
    Retourne la liste des Bureaux de Propriété Intelectuelle supporté
    */
    $available_ipoffices = array(
        'inpi'
    );

    $app->get('/api/v1/ipoffices',
        function (Silex\Application $app) use ($available_ipoffices) {
            return json_encode($available_ipoffices);
    });

    $app->run();
