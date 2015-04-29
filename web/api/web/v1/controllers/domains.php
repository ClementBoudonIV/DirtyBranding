<?php

	$domains = $app['controllers_factory'];
    /*
    GET /domains/{domain}/available
    Retourne true pour la propriété "available" si le domaine est disponible.
    Utilisation du provider phpWhois https://github.com/phpWhois/phpWhois
    */

    $domains->get('{domain}/available',
        function (Silex\Application $app, $domain) {

            $domain_escaped = $app->escape(urldecode($domain));

            $available = false;

            //On contrôle en Base si nous avons l'info depuis moins d'un jour
            $available_cache = null;
            $sql = "SELECT id, available, dt_caching
            FROM Domains
            WHERE name = ?";
            $stmt = $app['db']->prepare($sql);
            $stmt->bindValue(1, $domain_escaped);
            $stmt->execute();
            if($row = $stmt->fetch())
            {
                if($row['dt_caching'] < date("Y-m-d H:i:s",(time() + (1*24*60*60))))
                {
                    //Le cache est toujours valide
                    $available_cache = (bool) $row['available'];
                }
                else
                {
                    //Le cache est expiré, on le supprime
                    $sql_del = "DELETE
                    FROM Domains
                    WHERE name = ?";
                    $stmt_del = $app['db']->prepare($sql_del);
                    $stmt_del->bindValue(1, $domain_escaped);
                    $stmt_del->execute();
                }
            }
            if($available_cache !== null)
            {
                //S'il y avait un cache valide
                $available = $available_cache;
            }
            else
            {
                //S'il n'y avait pas de cache valide
                $app['PhpWhois']->deepWhois = false;
                $result = $app['PhpWhois']->lookup($domain_escaped,false);

                if(!isset($result['regrinfo']['registered']) || $result['regrinfo']['registered'] == 'no')
                {
                    $available = true;
                }

                //On enregistre l'info en cache
                $sql_ins = "INSERT
                INTO Domains
                (name, available, dt_caching)
                VALUES
                (?,?,?)";
                $stmt_ins = $app['db']->prepare($sql_ins);
                $stmt_ins->bindValue(1, $domain_escaped);
                $stmt_ins->bindValue(2, $available);
                $stmt_ins->bindValue(3, date('Y-m-d H:i:s'));
                $stmt_ins->execute();

            }



            return json_encode($available);
    });

    return $domains;