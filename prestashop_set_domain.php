#!/usr/bin/php
<?php
$argc == 2 or die('Usage: prestashop_set_domain <domain>');

$config_file = 'config/settings.inc.php';
if (!file_exists($config_file)) {
    die('The configuration file does not exist. This script needs to be run from the Prestashop directory.');
}
include $config_file;

$link = new mysqli('localhost', _DB_USER_, _DB_PASSWD_, _DB_NAME_) or die('Could not connect: ' . $link->error);
print('Connected successfully'.PHP_EOL);

$DB_NAME = _DB_NAME_;
$domain = $link->real_escape_string($argv[1]);

$query = <<<SQL
UPDATE  `{$DB_NAME}`.`ps_shop_url` SET  `domain` =  '{$domain}', `domain_ssl` =  '{$domain}' WHERE  `ps_shop_url`.`id_shop_url` =1;
SQL;
print($query.PHP_EOL);
$result = $link->query($query) or die('Query failed: ' . $link->error . PHP_EOL);

$query = <<<SQL
UPDATE  `{$DB_NAME}`.`ps_configuration` SET  `value` =  '{$domain}' WHERE  `ps_configuration`.`name` ='PS_SHOP_DOMAIN';
SQL;
print($query.PHP_EOL);
$result = $link->query($query) or die('Query failed: ' . $link->error . PHP_EOL);

$query = <<<SQL
UPDATE  `{$DB_NAME}`.`ps_configuration` SET  `value` =  '{$domain}' WHERE  `ps_configuration`.`name` ='PS_SHOP_DOMAIN_SSL';
SQL;
print($query.PHP_EOL);
$result = $link->query($query) or die('Query failed: ' . $link->error . PHP_EOL);

$link->close();
?>
