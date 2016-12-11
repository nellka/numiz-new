<?
set_time_limit(0);
ini_set('memory_limit', '512M');  
require_once $cfg['path'] . '/models/crons.php';

$cron_class = new crons($cfg['db']);
$start = 0;
$limit = 5000;
$data_result = array();
while($data = $cron_class->getDataForMigrate($start,$limit)){
	
	foreach ($data as $row){
		$data_result[]=$row;
	}
	$start++;
	if($start>10) break;
}

$json =   json_encode($data_result);
$f = fopen("/var/www/htdocs/numizmatik.ru/json/m.json", "w");
$input = fwrite($f, $json);
fclose($f);
//header('Content-type: application/json');
//echo $json;
die('done');
