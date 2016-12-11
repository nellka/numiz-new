<?
require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($cfg['db']);
$details_class->migrateDetails();
$details_class->migrateKeywords();
die('end');
?>