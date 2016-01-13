<?
include $DOCUMENT_ROOT."/config.php";
include "config.php";
include "funct.php";

header('Expires: Wed, 23 Dec 1980 00:30:00 GMT'); // time in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header("Content-type: text/xml; charset=windows-1251");

$xml = "<?xml version=\"1.0\" encoding=\"windows-1251\" standalone=\"yes\"?>
<response>
<scripteval>ShowOrderList</scripteval>";

if ($user)
	$user = intval($user);
	
if ($order)
	$order = intval($order);
	
if ($postindex)
	$postindex = str_replace("'","",$postindex);
	
if ($shopcoinsorder)
	$shopcoinsorder = intval($shopcoinsorder);
//$sql_od = "select `user` from `order` where `order`='$shopcoinsorder' ;";
//$result_od = mysql_query($sql_od);
//$rows_od = mysql_fetch_array($result_od);

$sql_tmp = "select count(*) from `order` where `user`='".$user."' and `user`<>811 and `check`=1 and `order`<'".$order."' and `date`>(".time()."-365*24*60*60);";
$result_tmp = mysql_query($sql_tmp);
$rows_tmp = mysql_fetch_array($result_tmp);
if ($rows_tmp[0]>=3)
	$clientdiscount = 1;
else
	$clientdiscount = 0;

PostSum ($postindex, $shopcoinsorder, $clientdiscount);
//echo $discountcoupon;
//почта и индекс
if ($delivery==4 and $postindex)
{
	$xml .= "<error>none</error>
	<shopcoinsorder>".$shopcoinsorder."</shopcoinsorder>
	<bascetamount>".($bascetamount?$bascetamount:"none")."</bascetamount>
	<bascetsum>".($bascetsum?$bascetsum:"none")."</bascetsum>
	<bascetweight>".($bascetweight?$bascetweight:"none")."</bascetweight>
	<bascetinsurance>".($bascetinsurance?$bascetinsurance:"none")."</bascetinsurance>
	<bascetpostindex>".($postindex?$postindex:"none")."</bascetpostindex>
	<bascetpostweight>".($bascetpostweight?$bascetpostweight:"none")."</bascetpostweight>
	<PostZoneNumber>".($PostZoneNumber?$PostZoneNumber:"none")."</PostZoneNumber>
	<PostRegion>".(trim($PostRegion)?trim($PostRegion):"none")."</PostRegion>
	<PostZonePrice>".($PostZonePrice?$PostZonePrice:"none")."</PostZonePrice>
	<PostZoneLatter>".($PriceLatter?$PriceLatter:"none")."</PostZoneLatter>
	<PostAllPrice>".($PostAllPrice?$PostAllPrice:"none")."</PostAllPrice>
	";
}
else
{
	$xml .= "<error>NotPost</error>
	<shopcoinsorder>".$shopcoinsorder."</shopcoinsorder>
	<bascetamount>".($bascetamount?$bascetamount:"none")."</bascetamount>
	<bascetsum>".($bascetsum?$bascetsum:"none")."</bascetsum>
	<bascetweight>".($bascetweight?$bascetweight:"none")."</bascetweight>";
}

if ($delivery==2)
{
	$DeliveryName[$delivery] = "¬ офисе (возможность посмотреть материал до выставлени€)";
}

$xml .= "<DeliveryName>".($DeliveryName[$delivery]?$DeliveryName[$delivery]:"none")."</DeliveryName>
<SumName>".($SumName[$payment]?$SumName[$payment]:"none")."</SumName>
<discountcoupon>".($discountcoupon?$discountcoupon:"none")."</discountcoupon>";

if ($metrovalue and $delivery == 1)
{
	$xml .= "<metrovalue>".$MetroArray[$metrovalue]."</metrovalue>";
}
elseif ($metrovalue and ($delivery == 3))
{
	$sql = "select * from metro where metro='$metrovalue';";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	if ($rows["name"])
		$xml .= "<metrovalue>".$rows["name"]."</metrovalue>";
	else
		$xml .= "<metrovalue>none</metrovalue>";
}
else
{
	$xml .= "<metrovalue>none</metrovalue>";
}


//дата
if ($meetingdatevalue and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7))
	$xml .= "<meetingdatevalue>".$DaysArray[date("w",$meetingdatevalue)].":".date("d-m-Y", $meetingdatevalue)."</meetingdatevalue>";
else
	$xml .= "<meetingdatevalue>none</meetingdatevalue>";


if ($meetingfromtimevalue and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7))
	$xml .= "<meetingfromtimevalue>".date("H-i", $timenow + $meetingfromtimevalue)."</meetingfromtimevalue>";
else
	$xml .= "<meetingfromtimevalue>none</meetingfromtimevalue>";

if ($meetingtotimevalue and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7))
	$xml .= "<meetingtotimevalue>".date("H-i", $timenow + $meetingtotimevalue)."</meetingtotimevalue>";
else
	$xml .= "<meetingtotimevalue>none</meetingtotimevalue>";


$xml .= "</response>";
echo $xml;
?>