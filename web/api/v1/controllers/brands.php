<?php

	$brands = $app['controllers_factory'];

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

    $brands->get('{brand}/alternatives',
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

                $brand_replaced = str_replace(
                    ' ',
                    $separator,
                    $brand_escaped
                );

                if(!in_array($brand_replaced,$array_alternatives))
                {
                    $array_alternatives[] = $brand_replaced;
                }


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
    $brands->get('{brand}/domains',
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
    GET /brands/{brand}/available?ipoffices[]=
    Retourne true pour la propriété "available" si la marque est disponible
    pour le bureau de propriété intellectuel choisi
    TODO
    */
    $brands->get('{brand}/available',
        function (Silex\Application $app, $brand) {

            $brand_escaped = $app->escape(urldecode($brand));

            $available = true;

            $array_ipoffices = array();

            if(null !== $app['request']->get('ipoffices'))
            {
                foreach($app['request']->get('ipoffices') as $ipoffices_src)
                {
                    $array_ipoffices[]=$app->escape($ipoffices_src);
                }
            }

            foreach($array_ipoffices as $ipoffices_req)
            {
                if($ipoffices_req == 'inpi')
                {
                    $sql = "SELECT id
                    FROM BrandsINPI
                    WHERE name LIKE :brand";
                    $stmt = $app['db']->prepare($sql);
                    $stmt->bindValue("brand", '%'.$brand_escaped.'%');
                    $stmt->execute();
                    if($row = $stmt->fetch())
                    {
                        $available = false;
                    }
                }
            }

            return json_encode($available);
    });

    return $brands;
