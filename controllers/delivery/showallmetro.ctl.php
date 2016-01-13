<?
require $cfg['path'] . '/configs/config_shopcoins.php';

$timelimit = (integer)request('timelimit');
require $cfg['path']."/controllers/calendar.ctl.php";

$TimesArray = array();
$DaysData = array();
$MetroData = array();


$result = $calendar_class->getMetro();
$n=0;
foreach ($result as $rows){
	$MetroData[$n]['val'] =$rows["metro"];
	$MetroData[$n]['text'] = trim($rows["name"]);
	$n++;
}



$startDate = mktime(0,0,0,10,29,2007);

if (!$timelimit || $timelimit>30)
	$timelimit = 30;
$n=0;
for ($i=1; $i<=$timelimit; $i++)
{
	$time = $timenow+$i*86400;
	
	if( (date("w", $time)==1 || date("w", $time)==2 || date("w", $time)==3 || date("w", $time)==4 || date("w", $time)==5) AND ( $intervals === NULL OR !is_in_interval($time, $intervals))){
		$DaysData[$n]['val'] =$time;
		$DaysData[$n]['text'] = $DaysArray[date("w",$time)].":".date("Y-m-d", $time);
		$n++;
	}
}

$n=0;
for ($i = 32400; $i <= 64800; $i = $i+900){
    $TimesArray[$n]['val'] = $i;
	$TimesArray[$n]['text'] = date("H-i", $timenow+$i);
	$n++;
}

$data_result['MetroArray'] = $MetroData;
$data_result['DaysArray'] = $DaysData;
$data_result['TimesArray'] = $TimesArray;
echo json_encode($data_result);
die();

?>