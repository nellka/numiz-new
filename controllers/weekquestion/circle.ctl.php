<?

Header ("Content-Type: image/png");
$xsize = 150;
$ysize = 150;
$im = ImageCreate($xsize, $ysize);

$white = ImageColorAllocate($im, 255, 255, 255);
$black = ImageColorAllocate($im, 0, 0, 0);
$color[0] = ImageColorAllocate($im, 255, 204, 102); //оранжевый
$color[1] = ImageColorAllocate($im, 51, 204, 0); // Зеленый
$color[2] = ImageColorAllocate($im, 0, 102, 152); // темно синий
$color[3] = ImageColorAllocate($im, 255, 0, 0); // красный
$color[4] = ImageColorAllocate($im, 255, 255, 255); // белый
ImageColorTransparent($im, $white);

$sum = $vote1 + $vote2 + $vote3 + $vote4 + $vote5;
$vote1 = ($vote1/$sum)*360;
$vote2 = (($vote2/$sum)*360) + $vote1;
$vote3 = ($vote3/$sum)*360 + $vote2;
$vote4 = ($vote4/$sum)*360 + $vote3;
$vote5 = ($vote5/$sum)*360 + $vote4;

ImageArc($im, $xsize/2, $ysize/2, $xsize, $ysize, 0, 360, $black);


function linealfa ($alfa, $alfaold, $numcolor)
{
	global $xsize, $ysize, $im, $black, $color;
	$gradus = ($alfa + $alfaold)/2;
	$alfa = ($alfa * pi())/180;
	$x = $xsize/2 + ($xsize/2) * sin($alfa);
	$y = $ysize/2 - ($xsize/2) * cos($alfa);
	ImageLine($im, $xsize/2, $ysize/2, $x, $y, $black);

	$gradus = ($gradus * pi())/180;
	$x = $xsize/2 + ($xsize/3) * sin($gradus);
	$y = $ysize/2 - ($ysize/3) * cos($gradus);
	if ($gradus!=0) ImageFill($im, $x, $y, $color[$numcolor]);
}

linealfa (0, 0, 4);
if ($vote1) linealfa($vote1, 0, 0);
if ($vote2!=0) linealfa($vote2, $vote1, 1);
if ($vote3!=0) linealfa($vote3, $vote2, 2);
if ($vote4!=0) linealfa($vote4, $vote3, 3);
if ($vote5!=0) linealfa($vote5, $vote4, 4);

/*linealfa (50, 0, 0);
linealfa (150, 50, 0);
linealfa (250, 150, 1);
linealfa (350, 250, 2);
linealfa (360, 350, 3);
*/
ImagePNG($im);
ImageDestroy($im);

?>