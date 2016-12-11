<?
//include_once $DOCUMENT_ROOT."/config.php";

Header("Content-type: image/png");
//строим график
$pcondition = (int) request('pcondition');
$parent = (int) request('parent');

$showindex=0;
$resize=1;
$verticallinebit=0;
$textsize=1;
$maxminbit=0;
$linecolor="red";
$copyrights=0;

$xsize=550;
$ysize=450;

$linecolor="blue";
$textsize=1;
$maxminbit=0;
$verticallinebit=1;
$copyrights=1;

$viewarray=array();
$dataarray=array(); // [металл][номер значения (в обратном порядке от даты)]
$dataarrayindex=array();
$dataarrayc=array(); // первоначальные цены для подсчёта индекса
$datearray=array();

$steparray=array(100000,50000,20000,10000,5000,2000,1000,500,200,100,50,10,5,2,1);
//$indexarray=array(33,31,6,17,2,11);

$showprice=1;
$sql_g = "select * from `priceshopcoins` where parent='$parent' and `condition`='$pcondition' and `check`=1 order by dateend asc, priceend asc;";

$result_g = $shopcoins_class->getDataSql($sql_g);

$dateminimum = 0;
$datemaximum = 0;
$priceminimum = 0;
$pricemaximum = 0;

foreach ($result_g as $rows_g){

	$arraydate[] = $rows_g['dateend'];
	$arrayprice[] = $rows_g['priceend'];
	
	if ($rows_g['dateend'] < $dateminimum || !$dateminimum)
		$dateminimum = $rows_g['dateend'];
		
	if ($rows_g['dateend'] > $datemaximum)
		$datemaximum = $rows_g['dateend'];
		
	if ($rows_g['priceend'] < $priceminimum || !$priceminimum)
		$priceminimum = $rows_g['priceend'];
		
	if ($rows_g['priceend'] > $pricemaximum)
		$pricemaximum = $rows_g['priceend'];
}

$pricestart = floor(($priceminimum - $pricemaximum/10)/100)*100;
if ($pricestart<0)
	$pricestart = 0;
	
$priceend = ceil(($pricemaximum + $priceminimum/10)/100)*100;

$startdate = mktime(1,0,0,date('m',$dateminimum-31*24*3600),1,date('y',$dateminimum-31*24*3600));
$startmonth = date('m',$dateminimum-31*24*3600);
$startyear = date('Y',$dateminimum-31*24*3600);

$finishdate = mktime(1,0,0,date('m',$datemaximum+31*24*3600),28,date('y',$datemaximum+31*24*3600));
$finishmonth = date('m',$datemaximum+31*24*3600);
$finishyear = date('Y',$datemaximum+31*24*3600);



$amountmonth = 0;

for ($i=$startyear;$i<=$finishyear;$i++) {

	if ($i<$finishyear && $i==$startyear) {
	
		for ($j=$startmonth;$j<=12;$j++) {
		
			$arraymonth[] = mktime(1,0,0,$j,1,$i);
			$amountmonth++;
		}
	}
	elseif ($i==$finishyear && $i>$startyear) {
	
		for ($j=1;$j<=$finishmonth;$j++) {
		
			$arraymonth[] = mktime(1,0,0,$j,1,$i);
			$amountmonth++;
		}
	}
	else {
		
		for ($j=1;$j<=12;$j++) {
		
			$arraymonth[] = mktime(1,0,0,$j,1,$i);
			$amountmonth++;
		}
	}
}

$tmpprice = $priceend-$pricestart;

if ($tmpprice<=10) {
	$steppricegrad = 1;
}
elseif ($tmpprice<=30) {
	$steppricegrad = 2;
}
elseif ($tmpprice<=50) {
	$steppricegrad = 5;
}
elseif ($tmpprice<=100) {
	$steppricegrad = 10;
}
elseif ($tmpprice<=300) {
	$steppricegrad = 20;
}
elseif ($tmpprice<=500) {
	$steppricegrad = 50;
}
elseif ($tmpprice<=1000) {
	$steppricegrad = 100;
}
elseif ($tmpprice<=3000) {
	$steppricegrad = 200;
}
elseif ($tmpprice<=5000) {
	$steppricegrad = 500;
}
elseif ($tmpprice<=10000) {
	$steppricegrad = 1000;
}
elseif ($tmpprice<=30000) {
	$steppricegrad = 2000;
}
elseif ($tmpprice<=50000) {
	$steppricegrad = 5000;
}
elseif ($tmpprice<=100000) {
	$steppricegrad = 10000;
}
elseif ($tmpprice<=300000) {
	$steppricegrad = 20000;
}
elseif ($tmpprice<=500000) {
	$steppricegrad = 50000;
}
elseif ($tmpprice<=1000000) {
	$steppricegrad = 100000;
}
else
	$steppricegrad = 200000;

if ($amountmonth<=12) 
	$steptimegrad = 1;
elseif ($amountmonth<=24) 
	$steptimegrad = 2;
elseif ($amountmonth<=36) 
	$steptimegrad = 3;
elseif ($amountmonth<=48) 
	$steptimegrad = 4;
else 
	$steptimegrad = 6;

//echo " ".$pricestart." ".$priceend." ".$startmonth." ".$startyear." ".$finishmonth." ".$finishyear." ".$steppricegrad;

$sql_g = "select * from `priceshopcoins` where parent='$parent' and `condition`='$pcondition' and `check`=1 order by dateend asc, priceend asc;";
$result_g = $shopcoins_class->getDataSql($sql_g);

//Инициализируем массивы $datearray - дата и $dataarray - данные
$i=0;
$key = 0;
foreach ($result_g as $row_g){
	$datearray[$i]=$row_g["dateend"];
	$dataarray[$key][$i]=$row_g["priceend"];
	$sumall += $row_g["priceend"];
	$i++;
}

$datasize=sizeof($datearray);
$minvalue=0;$maxvalue=0;
$minvalue2=0;
$maxvalue2=0;
$xmin=30;
if ($pricemaximum>99999)
	$xmin=35;
if ($pricemaximum>999999)
	$xmin=40;

$xmax=$xsize-5;
$ymin=8;
$ymax=$ysize-45;
$ystart=$ymin;
$yend=$ymax;
$pointradius=6;

$xdelta = ($xmax-$xmin)/($finishdate - $startdate);

if ($showprice)// and !$showindex)
{
	$l=0;
	//for($k=0;$k<sizeof($viewarray);$k++)

	for($i=0;$i<$datasize;$i++){
		if ($dataarray[$key][$i])
		{
			if ($dataarray[$key][$i]>$maxvalue2 or !$l)
				$maxvalue2=$dataarray[$key][$i];
			
			if ($dataarray[$key][$i]<$minvalue2 or !$l)
				$minvalue2=$dataarray[$key][$i];
			
			$l=1;
		}
	}

	if ($minvalue2==$maxvalue2)
	{
		$maxvalue2=1.5*$minvalue2;
		$minvalue2=0.5*$minvalue2;
	}
	
	$ym2=($yend-$ystart)/($maxvalue2-$minvalue2);
	
	for ($i=0;$i<sizeof($steparray);$i++)
	{
		$j=$ym2*$steparray[$i];
		if ($j<=50) break;
	}
	
	if ($i==sizeof($steparray)) 
		$i=sizeof($steparray)-1;
	
	$step2=$steparray[$i];

	$minvalue2=floor($minvalue2/$step2)*$step2;
	$maxvalue2=ceil($maxvalue2/$step2)*$step2;
		
	if (!$step2) 
		$step2=$steparray[sizeof($steparray)-1];
}

$namecolor=array();

$im = imagecreate($xsize,$ysize);

$colorb[0] =imagecolorallocate($im,241,244,245);$namecolor[0]="#F1F4F5";
$colorb[1] =imagecolorallocate($im,  0,  0,  0);$namecolor[1]="#000000";
$colorb[2] =imagecolorallocate($im,  0,  0,255);$namecolor[2]="#0000FF";
$colorb[3] =imagecolorallocate($im,255,  0,  0);$namecolor[3]="#FF0000";
$colorb[4] =imagecolorallocate($im,  0,128,  0);$namecolor[4]="#008000";
$colorb[5] =imagecolorallocate($im,  0,128,128);$namecolor[5]="#008080";
$colorb[6] =imagecolorallocate($im,255,128,  0);$namecolor[6]="#FF8000";
$colorb[7] =imagecolorallocate($im,128,  0,128);$namecolor[7]="#800080";
$colorb[8] =imagecolorallocate($im,128,128,  0);$namecolor[8]="#808000";
$colorb[9] =imagecolorallocate($im,  0,  0,128);$namecolor[9]="#000080";
$colorb[10]=imagecolorallocate($im,  0,  0,  0);$namecolor[10]="#000000";
$colorb[11]=imagecolorallocate($im,128,128,128);$namecolor[11]="#808080";
$colorb[12]=imagecolorallocate($im,  0,255,255);$namecolor[12]="#00FFFF";
$colorb[13]=imagecolorallocate($im,255,128,255);$namecolor[13]="#FF80FF";
$colorb[14]=imagecolorallocate($im,255,255,  0);$namecolor[14]="#FFFF00";
$colorb[15]=imagecolorallocate($im,174,174,174);$namecolor[15]="#CCCCCC";
$colorb[16]=imagecolorallocate($im,230,230,230);$namecolor[16]="#E6E6E6";

//Кисточки
for ($i=2;$i<=14;$i++)
{
	$brush1="brush_".$i."_1";
	$brush2="brush_".$i."_2";
	$$brush1=ImageCreate(2,1);
	$$brush2=ImageCreate(1,2);
	$colorarray=imagecolorsforindex($im,$colorb[$i]);
	imagecolorallocate($$brush1,floor($colorarray["red"]),floor($colorarray["green"]),floor($colorarray["blue"]));
	imagecolorallocate($$brush2,floor($colorarray["red"]),floor($colorarray["green"]),floor($colorarray["blue"]));
}

$color=array();
$color[0] =imagecolorallocate($im,241,244,245);$namecolor[0]="#F1F4F5";
$color[1] =imagecolorallocate($im,  0,  0,  0);$namecolor[1]="#000000";
$color[2] =imagecolorallocate($im,  0,  0,255);$namecolor[2]="#0000FF";
$color[3] =imagecolorallocate($im,255,  0,  0);$namecolor[3]="#FF0000";
$color[4] =imagecolorallocate($im,  0,128,  0);$namecolor[4]="#008000";
$color[5] =imagecolorallocate($im,  0,128,128);$namecolor[5]="#008080";
$color[6] =imagecolorallocate($im,255,128,  0);$namecolor[6]="#FF8000";
$color[7] =imagecolorallocate($im,128,  0,128);$namecolor[7]="#800080";
$color[8] =imagecolorallocate($im,128,128,  0);$namecolor[8]="#808000";
$color[9] =imagecolorallocate($im,  0,  0,128);$namecolor[9]="#000080";
$color[10]=imagecolorallocate($im,  0,  0,  0);$namecolor[10]="#000000";
$color[11]=imagecolorallocate($im,128,128,128);$namecolor[11]="#808080";
$color[12]=imagecolorallocate($im,  0,255,255);$namecolor[12]="#00FFFF";
$color[13]=imagecolorallocate($im,174,174,174);$namecolor[13]="#CCCCCC";
$color[14]=imagecolorallocate($im,255,238,255);$namecolor[14]="#F0F0F0";

$watermarkimage="mark.png";
if ($watermarkimage and file_exists($watermarkimage))
{
	$watermark=imagecreatefrompng($watermarkimage);
	$wmx=imagesx($watermark);
	$wmy=imagesy($watermark);

	if ($wmx<$xsize and $wmy<$ysize)
		imagecopymerge($im,$watermark,($xmax+$xmin)/2-$wmx/2,($ymax+$ymin)/2-$wmy/2,0,0,$wmx,$wmy,6);
}

if ($showindex) 
	$ym=($yend-$ystart)/($maxvalue-$minvalue);
if ($showprice) 
	$ym2=($yend-$ystart)/($maxvalue2-$minvalue2);

//
//echo $showprice."!".$showindex;
//if ($showprice and !$showindex)
//	{
	$fromy=$fromy2;
	$toy=$toy2;
	$step=$step2;
	$minvalue=$minvalue2;
	$maxvalue=$maxvalue2;
	$ym=$ym2;
//	}
$fromy=round(($maxvalue+$minvalue)/2);$i=0;
$fromy=round($fromy/$step)*$step;

while($yend-($fromy-$minvalue)*$ym<=$yend) 
{
	$fromy-=$step;$i++;
}

$fromy+=$step;
$i--;
$toy=round(($maxvalue+$minvalue)/2);$j=0;
$toy=round($toy/$step)*$step;
while($yend-($toy-$minvalue)*$ym>=$ystart) 
{
	$toy+=$step;$j++;
}

// Алгоритм подбора шага для совпадения горизонтальных линий на графиках
if ($showindex and $showprice)
{
	$l=$i+$j;
	$ns=($maxvalue2-$minvalue2)/($l-2);
	for($i=0;$i<sizeof($steparray);$i++)
		if ($steparray[$i]<=$ns)
		{
			$m=$steparray[$i];
			$n=floor($ns/$m);
			$ns2=$n*$m;
			$j=$ns2*($l-1)-($maxvalue2-$minvalue2);
			if ($j>=0) break;
		}
	
	if ($j>0)
	{
		$j=$j/2;
		$minvalue2-=$j;
		$maxvalue2+=$j;
		if ($minvalue2<0) {$minvalue2+=$j;$maxvalue2+=$j;}
		$ym2=($yend-$ystart)/($maxvalue2-$minvalue2);
	}
	$step2=$m;
}

$style=array($color[13],$color[13],$color[13],$color[13],$color[13],$color[13],$color[13],$color[13]);
imagesetstyle($im,$style);

// Горизонтальные линии
for($i=$fromy;$i<$toy;$i+=$step)
{
	$y=$yend-($i-$minvalue)*$ym;
	imageline($im,$xmin,$y,$xmax,$y,IMG_COLOR_STYLED);

	if ($showindex)
	{
		imagestring($im,$textsize,$xmin-strlen($i)*(4+$textsize)-2,$y-2-2*$textsize,$i,$color[1]);
	}

	if ($showprice)// and !$showindex)
	{
		if ($showindex) 
			$y2=($yend-$y)/$ym2+$minvalue2;
		else 
			$y2=$i;
		
		imagestring($im,$textsize,$xmin-strlen((int)(round($y2/$step2)*$step2))*(4+$textsize)-2,$y-2-2*$textsize,(int)(round($y2/$step2)*$step2),$color[1]);
	}
}

// Вертикальные линии
for($i=0;$i<$amountmonth;$i+=$steptimegrad)
{
	$x=$xmin+round(($arraymonth[$i]-$startdate)*$xdelta);
	imageline($im,$x,$ymin,$x,$ymax,IMG_COLOR_STYLED);

	imagestringup($im,$textsize,$x-10,$ysize -3,date('M Y',$arraymonth[$i]),$color[1]);
}

//imagettftext($im,8,0,$xmin-10,$ymin-8,$color[1],"/var/www/htdocs/www.metaltorg.ru/arial.ttf",iso2uni(convert_cyr_string("$/Т".$measure[$namesarray[$parentid][2]][1],"w","i")));

if ($showprice and !$showindex)
	imageline($im,$xmin,$ymin,$xmin,$ymax,IMG_COLOR_STYLED);

//Графики
$l=0;
//for($k=0;$k<sizeof($viewarray);$k++)
{
	$xl=$xmin;
	$yp=0;
	$xp=0;
	
	for($i=0;$i<$datasize;$i++)
	{
		$x=$xmin+round(($datearray[$i]-$startdate)*$xdelta);
		if (!$l and $verticallinebit and $x-$xl>60)
		{
			$xl=$x;
			//imageline($im,$xl,$ymax,$xl,$ymin,IMG_COLOR_STYLED);
			//imagestringup($im,1,$xl-5,$ysize-10,date("d.m.y",$datearray[$i]),$color[1]);
			//imagestring($im,1,$xl-30,$ysize-10,date("d.m.y",$datearray[$i]),$color[1]);
		}
		if ($dataarray[$key][$i])
		{
			$y=$yend-($dataarray[$key][$i]-$minvalue2)*$ym2;
			
			$brush1="brush_".($k+3)."_1";
			$brush2="brush_".($k+3)."_2";
			if ($xp and $yp)
			{
			//	$tan=($y-$yp)/($x-$xp);
			//	if (abs($tan)>=1) 
					imagesetbrush($im,$$brush1);
			//	else 
			//		imagesetbrush($im,$$brush2);
				
				imageline($im,$xp,$yp,$x,$y,IMG_COLOR_BRUSHED);
				
			}
			imagesetbrush($im,$$brush1);
			imagefilledellipse ($im, $x, $y, 5, 5, IMG_COLOR_BRUSHED);
			$yp=$y;
			$xp=$x;
		}
	}
	//imagefilledellipse ($im, $xp, $yp, 6, 6, IMG_COLOR_BRUSHED);
	$l++;
}

//imagestring($im,1,$xmax-40,$ysize-12,date("d.m.y",$datearray[($datasize-1)]),$color[1]);
//imagestring($im,1,$xmin-2,$ysize-12,date("d.m.y",$datearray[0]),$color[1]);

imageline($im,$xmin,$ymax,$xmax,$ymax,$color[1]);
if ($showindex) 
	imageline($im,$xmin,$ymin,$xmin,$ymax,$color[1]);
if ($showprice) 
	imageline($im,$xmin,$ymin,$xmin,$ymax,$color[1]);

imagerectangle ($im, 0, 0, $xsize-1, $ysize-1, $color[1]);

//ImagePng($im, "$DOCUMENT_ROOT/cources/lme2/images/lme".$MetallType.".png");
imagepng($im);
die();
?>