<?
require $cfg['path'] . '/configs/config_shopcoins.php';
require $cfg['path']."/controllers/calendar.ctl.php";

$delivery = (integer)request('delivery');
$timelimit = (integer)request('timelimit');

$data_result = array();
$data_result['error'] = null;

$timenow = time();

$startDate = mktime(0,0,0,10,29,2007);

if (!$timelimit || $timelimit>30)
	$timelimit = 30;

$DaysData = array();
$n = 0;
for ($i=1; $i<=$timelimit; $i++) {
	$time = $timenow+$i*86400;
	if (date("w", $time) != 0){	
		$DaysData[$n]['val'] =$time;
		$DaysData[$n]['text'] = $DaysArray[date("w",$time)].":".date("Y-m-d", $time);
		$n++;
	}	
}

$data_result['DaysArray'] = $DaysData;

$TimesArray = array();
$n = 0;
for ($i = 36000; $i <= 64800; $i = $i+90){
	$TimesArray[$n]['val'] = $i;
	$TimesArray[$n]['text'] = date("H-i", $timenow+$i);
	$n++;
}

$data_result['TimesArray'] = $TimesArray;
echo json_encode($data_result);

die();

?>