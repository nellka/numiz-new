<?php 
$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));

if(isset($_GET['ratinguser']) and !empty($_GET['ratinguser']))
{
	$ratinguser = (int)($_GET['ratinguser']);
	
	$sql = "insert into rating (rating, time, ip, ratinguser, `check`) values (0, ".$timenow.", '".str_replace(".","", $_SERVER['REMOTE_ADDR'])."', '".$ratinguser."', 0);";
	$shopcoins_class->setQuery($sql);	
	
	$name = '/var/www/htdocs/numizmatik.ru/rating/temp/counter_'.$ratinguser.'.png'; 
	$fp = fopen($name, 'rb');

	header("Content-Type: image/png");
	header("Content-Length: " . filesize($name));
	
	fpassthru($fp);
	exit;

}

?>