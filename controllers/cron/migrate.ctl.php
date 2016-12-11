<?
require_once $cfg['path'] . '/models/crons.php';
$cron_class = new crons($cfg['db']);
//$shopcoins_class->migrateMetal();
//$shopcoins_class->migrateCondition();
$cron_class->migrateShopcoinsViewOld();
//$shopcoins_class->migrateTheme();
die('end');
?>