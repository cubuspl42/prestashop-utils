#!/usr/bin/php
<?php
$argc == 2 or die('Usage: prestashop_set_domain <domain>');

$config_file = './app/config/parameters.php';
if (!file_exists($config_file)) {
    die('The configuration file does not exist. This script needs to be run from the Prestashop directory.');
}
$params = include $config_file;

$databaseHost = $params['parameters']['database_host'];
$databaseUser = $params['parameters']['database_user'];
$databasePassword = $params['parameters']['database_password'];
$databaseName = $params['parameters']['database_name'];
$databasePrefix = $params['parameters']['database_prefix'];

$link = new mysqli($databaseHost, $databaseUser, $databasePassword, $databaseName) or die('Could not connect: ' . $link->error);
print('Connected successfully'.PHP_EOL);

$domain = $link->real_escape_string($argv[1]);

$query = <<<SQL
UPDATE  `{$databaseName}`.`{$databasePrefix}shop_url` SET  `domain` =  '{$domain}', `domain_ssl` =  '{$domain}' WHERE  `{$databasePrefix}shop_url`.`id_shop_url` =1;
SQL;
print($query.PHP_EOL);
$result = $link->query($query) or die('Query failed: ' . $link->error . PHP_EOL);

$query = <<<SQL
UPDATE  `{$databaseName}`.`{$databasePrefix}configuration` SET  `value` =  '{$domain}' WHERE  `{$databasePrefix}configuration`.`name` ='PS_SHOP_DOMAIN';
SQL;
print($query.PHP_EOL);
$result = $link->query($query) or die('Query failed: ' . $link->error . PHP_EOL);

$query = <<<SQL
UPDATE  `{$databaseName}`.`{$databasePrefix}configuration` SET  `value` =  '{$domain}' WHERE  `{$databasePrefix}configuration`.`name` ='PS_SHOP_DOMAIN_SSL';
SQL;
print($query.PHP_EOL);
$result = $link->query($query) or die('Query failed: ' . $link->error . PHP_EOL);

$link->close();
?>
