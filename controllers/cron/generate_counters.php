<?php 

$handle =  mysql_connect('localhost', 'numizmatik', 'numizmatik'  ) or die( 'Could not open connection to server' );
mysql_select_db( 'numizmatik', $handle ) or die( 'Could not select database '. $handle );

$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));

$result = mysql_query('SELECT ratinguser FROM `ratinguser`', $handle ) or die(mysql_error());
$rows = mysql_fetch_all($result);

$xsize = 88;
$ysize = 31;


foreach ($rows as $key => $val) 
{
	$ratinguser = $val['ratinguser'];
	
	$im = imagecreate ($xsize, $ysize);
	$white = imagecolorallocate($im, 255, 255, 255); //öâåò ôîíà
	$black = imagecolorallocate($im, 0, 0, 0);
	$im_in = imagecreatefrompng("./images/knop1.png");
	imagecopyresized($im, $im_in, 0, 0, 0, 0, 88, 31, 88, 31);
	
	$sql = "select count(distinct ip) as count from rating where ratinguser='$ratinguser' and time='".$timenow."';";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$count = $rows[0];
	if (!$count) $count = 0;

	imagestring ($im, 1, 3, 3, $count, $black);
	
	imagepng($im, './temp/counter_'.$ratinguser.'.png');
	imagedestroy($im);
	
}

function mysql_fetch_all($result) // accumulation
{
	$all = array();
	while ($all[] = mysql_fetch_assoc($result)) {}
	if(end($all) == '') array_pop($all);
	return $all;
}

?>