<?
//работаем только с хостами

//разбор переменных
$users = (array) request('users');

foreach ($users AS $i=>$user){	
	$user_string .='&users[]='.$user;
	//сразу строим запрос на максимум
	if ($i>0) {
		$max_q  .= " or ratinguser=".$user." ";
	} else {
		$max_q = "ratinguser=".$user." ";
	}
}

if (count($users)) {
	$timenow = time() - 31*86400;
	$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));
	//ищем максимум
	$sql = "Select max(host) from ratingbydate where ".$max_q." and date>".($timenow-86400*31)." and  date<".$timenow.";";
	//echo $sql;
	$max = $shopcoins_class->getOneSql($sql);
	//echo "<br>Max: ".$max;
	//die();
	switch (strlen($max)){
		case 2: $max = round($max, -1) + 10; $max_len = 10; break;
		case 3: $max = round($max, -2) + 100; $max_len = 100; break;
		case 4: $max = round($max, -3) + 1000; $max_len = 1000; break;
		case 5: $max = round($max, -4) + 10000; $max_len = 10000; break;
	}
	
	$xsize = 420;
	$ysize = 400;
	$dx = 24;
	$dy = 45;
	$xstep = 12;
	$ystep = 30;
	
	//строим изображение
	Header ("Content-Type: image/png");
	$im = ImageCreate ($xsize, $ysize);
	$white = ImageColorAllocate($im, 255, 255, 255); //цвет фона
	$black = ImageColorAllocate($im, 0, 0, 0);
	$silver = ImageColorAllocate($im, 205, 205, 205);
	$gray = ImageColorAllocate($im, 135, 135, 135);
	ImageColorTransparent($im, $white);
	
	//ImageTTFText($im, 16, 0, 40, 50, $black, "arialbd.ttf", $max);
	
	//делаем разметку по ox
	for ($i=0; $i<32; $i++)
	{
		imageline($im, $i*$xstep + $dx, $dy + 10, $i*$xstep + $dx, $ysize-$dy, $silver);
		if (($i % 5) == 0)
		{
			imageline($im, $i*$xstep + $dx, $dy + 10, $i*$xstep + $dx, $ysize-$dy, $gray);
			$mydate = ($timenow - 86400*31) + $i * 86400;
			$mydate = date("m.d", $mydate);
			imageString($im, 1, $i*$xstep + $dx, $ysize - $dy + 12, $mydate, $black);
		}
	}
	
	//далаем разметку по oy
	for ($i=0; $i<=10; $i++)
	{
		imageline($im, $dx, $ysize - $dy - $i*$ystep, $xsize-$dx, $ysize - $dy - $i*$ystep, $silver);
		imageString($im, 1, 0, $ysize - $dy - $i*$ystep, $i*$max/10, $black);
	}
	
	//сами оси
	imageline($im, $dx, $dy, $dx, $ysize-$dy, $black);
	imageline($im, $dx, $ysize-$dy, $xsize-$dx, $ysize-$dy, $black);
	
	$i=1;
	foreach ($users as $user){		
		user_data($user, $i,$shopcoins_class);
		$i++;
	}
		
	
	ImagePNG($im);
	ImageDestroy($im);
	
}
die();
function user_data($user, $num,$shopcoins_class){
	global $timenow, $max, $ysize, $dx, $dy, $im;
	$color[1] = ImageColorAllocate($im, 0, 0, 204);
	$color[2] = ImageColorAllocate($im, 255, 0, 0);
	$color[3] = ImageColorAllocate($im, 0, 204, 102);
	$color[4] = ImageColorAllocate($im, 204, 204, 0);
	$factor = 300/$max;
	$sql = "select FROM_UNIXTIME(date, \"%d.%m.%y\") as d, 
		round((date - ($timenow -86400*31))/ 86400) as number, 
		round($ysize - (host*$factor+$dy)) as rd from ratingbydate 
		where ratinguser=$user and date>".($timenow-86400*31)." and  date<".$timenow."
		order by date;";
	//echo $sql;
	$result = $shopcoins_class->getDataSql($sql);

	foreach ($result as $rows){
		//строим график
		if ($yold)
		{
			ImageLine($im, $xold*12 + $dx, $yold-2, $rows['number']*12 + $dx, $rows['rd']-2, $color[$num]);
			ImageLine($im, $xold*12 + $dx, $yold-1, $rows['number']*12 + $dx, $rows['rd']-1, $color[$num]);
			ImageLine($im, $xold*12 + $dx, $yold, $rows['number']*12 + $dx, $rows['rd'], $color[$num]);
			$xold = $rows['number'];
			$yold = $rows['rd'];
		} else {
			$xold = $rows['number'];
			$yold = $rows['rd'];
		}
//		echo "<br> ---".$rows[0]."---".$rows[1]."---".$rows[2];
	}
	
}

?>