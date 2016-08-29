<?
require_once $cfg['path'] . '/models/crons.php';

$cron_class = new crons($cfg['db']);

$sql = "SELECT subscribe.*,email FROM `subscribe`,user WHERE subscribe.user = user.user AND email NOT LIKE '%@%' and (subscribe.news = '1'	or tboard = '1' 	or blacklist = '1'	or buycoins = '1'	or biblio = '1' 	or advertise = '1' or shopcoins='0')";

$result = $cron_class->getDataSql($sql);
foreach ($result as $row){
	var_dump($row);
	die();
}
die();

?>