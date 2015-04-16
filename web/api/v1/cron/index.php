<?php
    date_default_timezone_set('GMT');

    //Old PHP Style

    $link = new PDO("mysql:dbname=DB_API;host=localhost", "root", "root");

    $xml_inpi_folder  = __DIR__.'/../../../../Ressources/INPI/XML/';

    //listing des fichiers XML dispo
    $list_files = scandir($xml_inpi_folder);
    $list_files = array_diff($list_files, array('.', '..'));

    //on parcours chacun des fichiers
    foreach ($list_files as $files) {
        echo 'Traitement : '.$xml_inpi_folder.$files.' ...';

        $xml = simplexml_load_file($xml_inpi_folder.$files);

        //on récupère simplement le nom de a marque, et son code
        foreach($xml->TradeMarkTransactionBody->TransactionContentDetails->TransactionData->TradeMarkDetails->TradeMark as $TradeMark){

            $brand_name = $TradeMark->WordMarkSpecification->MarkVerbalElementText;

            $sql_ins = "INSERT
            INTO BrandsINPI
            (name, dt_caching)
            VALUES
            (?,?)";
            $stmt_ins = $link->prepare($sql_ins);
            $stmt_ins->bindValue(1, $brand_name);
            $stmt_ins->bindValue(2, date('Y-m-d H:i:s'));
            $stmt_ins->execute();

            $id_brand = $link->lastInsertId();

            $ClassDescriptionDetails = $TradeMark->GoodsServicesDetails->GoodsServices->ClassDescriptionDetails->ClassDescription;
            $array_class = array();
            foreach ($ClassDescriptionDetails as $xml_class) {

                $sql_ins = "INSERT
                INTO BrandsClassesINPI
                (id_brand, classe)
                VALUES
                (?,?)";
                $stmt_ins = $link->prepare($sql_ins);
                $stmt_ins->bindValue(1, $id_brand);
                $stmt_ins->bindValue(2, $xml_class->ClassNumber);
                $stmt_ins->execute();

            }
        }

        echo ' [OK]
';


    }





    $link = null;
