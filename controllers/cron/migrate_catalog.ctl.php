<?
require_once($cfg['path'].'/models/catalognew.php');
$catalognew_class = new model_catalognew($db_class);
//$shopcoins_class->migrateMetal();
//$shopcoins_class->migrateCondition();
$catalognew_class->migrateNominals();
//$shopcoins_class->migrateTheme();
die('end');
?>