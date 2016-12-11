<?php 

$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));

$rows = $shopcoins_class->getDataSql('SELECT ratinguser FROM `ratinguser`');

$xsize = 88;
$ysize = 31;


foreach ($rows as $key => $val) {
	$ratinguser = $val['ratinguser'];
	
	$im = imagecreate ($xsize, $ysize);
	$white = imagecolorallocate($im, 255, 255, 255); //öâåò ôîíà
	$black = imagecolorallocate($im, 0, 0, 0);
	$im_in = imagecreatefrompng("/var/www/htdocs/numizmatik.ru/rating/images/knop1.png");
	imagecopyresized($im, $im_in, 0, 0, 0, 0, 88, 31, 88, 31);
	
	$sql = "select count(distinct ip) as count from rating where ratinguser='$ratinguser' and time='".$timenow."';";
	$count = $shopcoins_class->getOneSql($sql);
	if (!$count) $count = 0;

	imagestring ($im, 1, 3, 3, $count, $black);	
	imagepng($im, '/var/www/htdocs/numizmatik.ru/rating/temp/counter_'.$ratinguser.'.png');
	echo  'counter_'.$ratinguser.'.png<br>';
	imagedestroy($im);
	
}
die();
?>