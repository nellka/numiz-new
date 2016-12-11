<?
//ini_set('display_errors',1);
include_once $DOCUMENT_ROOT."/config.php";
set_time_limit(5*3600);
$starttime = time();
$deltatime = 4*3600;
//$deltatime = 120;
$recipient = "molotok@numizmatik.ru";
$subject = "wolmar start";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Start parcing from Wolmar at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);
//exit();
function CopyImage ($HostPicture, $PathPicture1, $SavePicture, $PathPicture2)
{
	//echo $PathPicture;
	//echo $PathPicture1."<br>";
	//echo $SavePicture."<br>";
	//echo $PathPicture2."<br>";
	$return = 2;
	
	$im1 = imagecreatefromjpeg ($HostPicture.$PathPicture1); 
	if (!$im1) 
		return 3;
	else 
	{
		$size1 = getimagesize ($HostPicture.$PathPicture1);
		$width = $size1[0];
		$height = $size1[1];
		
		if ($PathPicture2 && $width<=750 && $height<=750) {
			
			$im2 = imagecreatefromjpeg ($HostPicture.$PathPicture2); 
			
			if (!$im2) 
				$return = 4;
			else {
				
				$size2 = getimagesize ($HostPicture.$PathPicture2);
				$width = $width + $size2[0];
				if ($height < $size2[1])
					$height = $size2[1];
			}
		}
		//Header ("Content-Type: image/jpeg");
		$im = imagecreatetruecolor ($width, $height);
		imagecopy ($im, $im1, 0, 0, 0, 0, $size1[0], $size1[1]);
		if ($im2) {
			imagecopy ($im, $im2,($size1[0]+1), 0, 0, 0, $size2[0], $size2[1]);
			imagedestroy($im2);
		}
		imagejpeg ($im , $SavePicture);
		imagedestroy($im);
		imagedestroy($im1);
		return $return;
		
	}
}


$recipient = "molotok@numizmatik.ru";
$subject = "Start upload images to pricecoins";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Start upload images to pricecoins at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

$sql = "select * from priceshopcoins where `check`=1 order by priceshopcoins desc;";
$result = mysql_query($sql);
while ($rows = mysql_fetch_array($result)) {

	if ($rows['auction']==1) {
	
		if (filesize("./price//images/".$rows['priceshopcoins'].".jpg")>0)
			CopyImage ("http://www.wolmar.ru/", "images/auctions/".$rows['anumber']."/preview_".$rows['number']."_1.jpg", "./price//images/".$rows['priceshopcoins'].".jpg", "images/auctions/".$rows['anumber']."/preview_".$rows['number']."_2.jpg");
			
		if (filesize("./price/images/".$rows['priceshopcoins']."b.jpg")>0)
			CopyImage ("http://www.wolmar.ru/", "images/auctions/".$rows['anumber']."/".$rows['number']."_1.jpg", "./price/images/".$rows['priceshopcoins']."b.jpg", "images/auctions/".$rows['anumber']."/".$rows['number']."_2.jpg");
	}
	elseif ($rows['auction']==2) {
	
		if (filesize("./price//images/".$rows['priceshopcoins'].".jpg")>0)
			CopyImage ("http://auction.conros.ru/", "thumb/".$rows['anumber']."/".$rows['numberimage'].".jpg", "./price/images/".$rows['priceshopcoins'].".jpg", "thumb/".$rows['anumber']."/".$rows['numberimage']."+.jpg");
		
		if (filesize("./price/images/".$rows['priceshopcoins']."b.jpg")>0)
			CopyImage ("http://auction.conros.ru/", "img/".$rows['anumber']."/".$rows['numberimage'].".jpg", "./price/images/".$rows['priceshopcoins']."b.jpg", "img/".$rows['anumber']."/".$rows['numberimage']."+.jpg");
	}
	//mysql_query("update priceshopcoins set `check`='1'".($tmp==2?",bigimage='".$rows['priceshopcoins']."b.jpg'":"").",image='".$rows['priceshopcoins'].".jpg'  where priceshopcoins='".$rows['priceshopcoins']."';");
	
	if (time()>$starttime+$deltatime) {
	
		$recipient = "molotok@numizmatik.ru";
		$subject = "Finish upload images to pricecoins";
		$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
		$message = "Finish upload images to pricecoins at ".date('Y-m-d H:i',time());
		mail($recipient, $subject, $message, $headers);
		exit();
	}
	
}

$recipient = "molotok@numizmatik.ru";
$subject = "Finish upload all images to pricecoins";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Finish upload all images to pricecoins at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

?>

</body>
</html>
