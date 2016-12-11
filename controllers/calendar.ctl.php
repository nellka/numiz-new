<?php 
require_once $cfg['path'] . '/models/calendar.php';

$calendar_class = new model_calendar($db_class);

$rows = $calendar_class->getDates();
$calendar_echo_var = '';
$max_holiday_date = NULL;

$holidays = array();

if(count($rows) > 0) {
	 $calendar_echo_var .= '<h4 style="color:red; font-family: arial, helvetica, sans-serif;">������ ������ � ����������� ���:</h4>';
	foreach ($rows as $key => $value) // dirty hack time convert, lol
	{
		$date_from_ = substr($value['date_from'], 0, strpos($value['date_from'], ' '));
		$date_to_ = substr($value['date_to'], 0, strpos($value['date_to'], ' '));

		if($date_from_ == $date_to_)
		{
			$calendar_echo_var .= '<h5 style="color:red; font-family: arial, helvetica, sans-serif;">'.substr($value['date_from'], 0, strpos($value['date_from'], ' ')).' - '.$value['msg'].'</h5>';
		}
		else
		{
			$calendar_echo_var .= '<h5 style="color:red; font-family: arial, helvetica, sans-serif;">� '.substr($value['date_from'], 0, strpos($value['date_from'], ' ')).' �� '.substr($value['date_to'], 0, strpos($value['date_to'], ' ')).' - '.$value['msg'].'</h5>';

		}			
		$holidays[] = strtotime($value['date_to']);

	}
	$calendar_echo_var .= '<hr>';
	$max_holiday_date = max($holidays);
}


$intervals = NULL;

$rows =  $calendar_class->getAllByParams();

foreach ($rows as $key => $value) 
{
	$intervals[$key]['date_from'] = strtotime($value['date_from']);
	$intervals[$key]['date_to'] = strtotime($value['date_to']);

	$intervals[$key]['date_from_'] = $value['date_from'];
	$intervals[$key]['date_to_'] = $value['date_to'];
}


function is_in_interval($time, $intervals)
{

	foreach ($intervals as $key => $value) 
	{
		if($time >= $value['date_from'] AND $time <= $value['date_to']) 
		{
			//echo 'in ' . $value['date_from_'] . '-'. $value['date_to_']. '   |' . date('Y-m-d', $time)."\n";
			return TRUE;
		}
	}

}


?>