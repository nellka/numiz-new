<?

include "./config.php";
//echo "ip=".getenv("REMOTE_ADDR")."<br>";
$lastday = 3;
$limitprice = 200;
function SaveMailDb ($recipient, $subject, $SendMessage, $headers)
{
	$sql_insert = "insert into shopcoinssender
	(shopcoinssender, dateinsert, email,
	subject, message, headers, 
	priority, datesend)
	values 
	(0, '".(time()+600)."', '".$recipient."',
	'$subject', '$SendMessage', '$headers', 
	'1', '0');";
	$result_insert = mysql_query($sql_insert);
	//echo $SendMessage."<br><br>";
	$sql_insert = "insert into shopcoinssender
	(shopcoinssender, dateinsert, email,
	subject, message, headers, 
	priority, datesend)
	values 
	(0, '".(time()+600)."', 'bodka@mail.ru',
	'$subject', '$SendMessage', '$headers', 
	'1', '0');";
	$result_insert = mysql_query($sql_insert);
}

$subject = "Клуб Нумизмат |  Вы хотели сделать заказ?";
$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";

$file = fopen($in."mail/top.html", "r");
while (!feof ($file)) 
	$message_top .= fgets ($file, 1024);
fclose($file);


$file = fopen($in."mail/bottom.html", "r");
while (!feof ($file)) 
	$message_bottom .= fgets ($file, 1024);
fclose($file);

function MakeMessage ($mailmessage)
{
	global $message_top, $message_bottom, $SERVER_NAME;
	$mailmessage = $message_top."<tr bgcolor=\"#006699\"><td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td>
	<td width=498><p><b><font color=white> Монетная лавка</font></b></td>
	<td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td></tr>
	<tr>
	<td width=498 bgcolor=\"#fff8e8\"><p>
	
	$mailmessage
	<br>
	С уважением, Клуб Нумизмат.
	<p><br> Монетная лавка - <a href=http://www.numizmatik.ru/shopcoins/>http://www.numizmatik.ru/shopcoins/</a>
	<br>Е-mail: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a><br></p>
	</td>
	</tr>".$message_bottom;
	
	//echo $mailmessage;
	
	return $mailmessage;
}

$sql = "select shopcoins.*,user.fio,user.email,user.user, group.name as gname from `order`, orderdetails,shopcoins,`user`,`group` 
where orderdetails.order=order.order and order.check=0 and order.user!=811 and order.user=user.user and user.userstatus=0 and user.sumlimit=0 and order.date>".($timenow-($lastday+1)*24*3600)." and order.date<".($timenow-$lastday*24*3600)." 
and (shopcoins.shopcoins=orderdetails.catalog or (shopcoins.parent=orderdetails.catalog and shopcoins.materialtype in (7,8))) and shopcoins.check=1 and shopcoins.group=group.group 
and order.user>0 and orderdetails.status=0 and shopcoins.price>=$limitprice
and shopcoins not in(select expouserid.shopcoins from expouserid,calling where calling.result=2 and calling.calling=expouserid.calling and expouserid.check!=2 and expouserid.user=order.user and expouserid.dateinsert>".($timenow - 60*24*3600).") 
and user.email like '%@%' 
 order by user.user,user.email ;";

 
$result = mysql_query($sql);
//echo $sql;

$fio = '';
$user = 0;
$email = '';
$mailmessage = '';
$arrayshopcoins = array();
while ($rows = mysql_fetch_array($result)) {

	if ($email != $rows['email']) {
	
		if ($email !='') {
		
			$href = '';
			if (sizeof($arrayshopcoins)>0) {
			
				$href = "<a href=\"http://www.numizmatik.ru/shopcoins/index.php?page=orderdetails&member=$user&recoins=".implode("d",$arrayshopcoins)."\" target=_blanck>положить все в корзину одним кликом</a>";
			}
			$mailmessage = str_replace("___hrefplace___",$href,$mailmessage)."<br><br>".$href;
			//$mailmessage = $templatetopic["news"].$Message["news"];
			$subject = "Клуб Нумизмат | Монетная лавка";
			$mailmessage = MakeMessage ( $mailmessage);
			//mail($rows["email"], $subject, $mailmessage, $headers);
			SaveMailDb ($email, $subject, $mailmessage, $headers);
			$mailmessage = '';
			$arrayshopcoins = array();
		}
		$email = $rows['email'];
		$user = $rows['user'];
		$mailmessage .= "<br><b>Добрый день, уважаемый (ая) ".$rows["fio"]."</b><br>
		<br>Вы интересовались данными позициями, но возможно что-то помешало Вам сделать заказ. Вы можете ___hrefplace___ либо перейти в магазин по нижеприведенным ссылкам, посмотреть и оформить заказ, если данные позиции Вас еще интересуют.";
	}
	
	$mailmessage .= "<br><br><font color=blue><b>".$rows["gname"]."</b></font>, <b>".$rows["name"]."</b>, <font color=red><b>".$rows["price"]." руб.</b></font> ".
			"<br><a href=http://www.numizmatik.ru/shopcoins/index.php?page=show&catalog=".$rows["shopcoins"]." target=_blank>http://www.numizmatik.ru/shopcoins/index.php?page=show&catalog=".$rows["shopcoins"]."</a>";
	$arrayshopcoins[] = $rows["shopcoins"];
}

if ($email !='') {
	
	$href = '';
	if (sizeof($arrayshopcoins)>0) {
	
		$href = "<a href='http://www.numizmatik.ru/shopcoins/index.php?page=orderdetails&member=$user&recoins=".implode("d",$arrayshopcoins)."' target=_blanck>положить все в корзину одним кликом</a>";
	}
	$mailmessage = str_replace("___hrefplace___",$href,$mailmessage)."<br>".$href;	
	//$mailmessage = $templatetopic["news"].$Message["news"];
	$subject = "Клуб Нумизмат | Монетная лавка";
	$mailmessage = MakeMessage ( $mailmessage);
	//mail($rows["email"], $subject, $mailmessage, $headers);
	SaveMailDb ($email, $subject, $mailmessage, $headers);
	$mailmessage = '';
}

?>