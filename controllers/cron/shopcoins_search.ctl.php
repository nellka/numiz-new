<?
require_once $cfg['path'] . '/models/crons.php';

$cron_class = new crons($cfg['db']);
$cron_class->createTempTable();

unset($cron_class);

die('end');
?>