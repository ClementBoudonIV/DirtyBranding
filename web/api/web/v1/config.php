<?php
//Configuration spécifique Codeship / Vagrant de dev
$mysql_host = (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV']=='test') ? 'localhost' : ((isset($_ENV['APP_ENV']) && $_ENV['APP_ENV']=='dev') ? 'localhost' : 'localhost');
$mysql_user = isset($_ENV['MYSQL_USER']) ? $_ENV['MYSQL_USER'] : 'root';
$mysql_pass = isset($_ENV['MYSQL_PASSWORD']) ? $_ENV['MYSQL_PASSWORD'] : 'root';
$mysql_database = isset($_ENV['TEST_ENV_NUMBER']) ? 'test'.$_ENV['TEST_ENV_NUMBER'] : 'DB_API';

$ftp_inpi_server = '';
$ftp_inpi_user_name = '';
$ftp_inpi_user_pass = '';
