<?
require $cfg['path'] . '/configs/config_shopcoins.php';

$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));
$metroid = (integer) request('metroid');
$timelimit = request('timelimit');

$TimeMetroRingDetals[1] = array (

	63000 => '17-30',
	68400 => '19-00',
	72000 => '20-00'
);

$TimeMetroRingDetals[2] = array (
	63480 => '17-38',
	68700 => '19-05'
);

$TimeMetroRingDetals[3] = array (

	63960 => '17-46',
	69000 => '19-10'
);

$TimeMetroRingDetals[4] = array (

	64440 => '17-54',
	69300 => '19-15'
);

$TimeMetroRingDetals[5] = array (

	63480 => '18-00',
	68700 => '19-20'
);

$TimeMetroRingDetals[6] = array (

	65100 => '18-05',
	69900 => '19-25'
);

$TimeMetroRingDetals[7] = array (

	65580 => '18-13',
	70200 => '19-30'
);

$TimeMetroRingDetals[8] = array (

	66060 => '18-21',
	70500 => '19-35'
);

$TimeMetroRingDetals[9] = array (

	66540 => '18-29',
	70800 => '19-40'
);

$TimeMetroRingDetals[10] = array (

	67020 => '18-37',
	71100 => '19-45'
);

$TimeMetroRingDetals[11] = array (

	67500 => '18-45',
	71400 => '19-50'
);

$TimeMetroRingDetals[12] = array (

	67980 => '18-53',
	71700 => '19-55'
);


$TimesArray = array();
$DaysData = array();
$MetroData = array();

$data_result['metroid'] = $metroid;

require $cfg['path']."/controllers/calendar.ctl.php";

if (!$metroid || intval($metroid)<1 || intval($metroid)>12) {	
	$n = 0;
	foreach ($MetroArray as $key => $value)	{
		$MetroData[$n]['val'] =$key;
		$MetroData[$n]['text'] = trim($value);
		$n++;
	}	
	
	if (!$timelimit || $timelimit>30) $timelimit = 30;	
	$n = 0;
	for ($i=1; $i<=$timelimit; $i++) {
		$time = $timenow+$i*86400;	
		if ( (date("w", $time)==2 || date("w", $time)==4) AND ($intervals === NULL OR !is_in_interval($time, $intervals)) ) {			
			$DaysData[$n]['val'] =$time;
		    $DaysData[$n]['text'] = $DaysArray[date("w",$time)].":".date("Y-m-d", $time);
			$n++;
		}		
	}
	
	
    $n = 0;
	for ($i = 64800; $i <= 72000; $i = $i+300){
		$TimesArray[$n]['text'] = date("H-i", $timenow+$i);
		$TimesArray[$n]['val'] = $i;
		$n++;		
	}
} else {
	$n=0;
	foreach ($TimeMetroRingDetals[$metroid] as $key => $value){
		$TimesArray[$n]['text'] = $value;
		$TimesArray[$n]['val'] = $key;
		$n++;
	}
}

$data_result['TimesArray'] = $TimesArray;
$data_result['DaysArray'] = $DaysData;
$data_result['MetroArray'] = $MetroData;
echo json_encode($data_result);
die();


?>