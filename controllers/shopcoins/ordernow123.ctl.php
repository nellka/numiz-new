<?
include $DOCUMENT_ROOT."/config.php";
include $DOCUMENT_ROOT."/wadmin/password.php";
if ($action == "showip" and $order)
{
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
	<scripteval>ShowUserFioByIp</scripteval>";

	$sql = "select * from `order` where ip='$ip' and `order` <> '$order' and `check`='1' group by userfio limit 10;";
	$result = mysql_query($sql);
	
	if (mysql_num_rows($result))
	{
		$xml .= "<error>none</error>
		<order>".$order."</order>
		<amount>".mysql_num_rows($result)."</amount>";
		while ($rows = mysql_fetch_array($result))
		{
			$xml .= "<userfio>".(trim($rows["userfio"])?trim($rows["userfio"]):"none")."</userfio>
			<phone>".(trim($rows["phone"])?trim($rows["phone"]):"none")."</phone>
			<adress>".(trim($rows["adress"])?trim($rows["adress"]):"none")."</adress>";
			$k++;
		}
	}
	else
	{
		$xml .= "<error>empty</error>
		<order>".$order."</order>";
	}
	
	$xml .= "</response>";
	echo $xml;
}
elseif (!$action)
{
?>
<html>
<title>Заказы в течении 24 часов</title>
<meta http-equiv="content-Type" content="text/html; charset=windows-1251">
<link rel=stylesheet type=text/css href=../bodka.css>
<body bgcolor=#F0F0F0>
<script type="text/javascript" src="ajax.php" language="JavaScript"></script>
<script language="JavaScript">

function ShowUserFioByIp()
{
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		amount = xmlRoot.getElementsByTagName("amount").item(0).firstChild.data;
		order = xmlRoot.getElementsByTagName("order").item(0).firstChild.data;
		var str = '';
		str = '<table border=0 cellpadding=3 cellspacing=1 style="border:thin solid 1px #000000" align=center>';
		
		var i;
		for (i = 0; i < amount; i++)
		{
			userfio = xmlRoot.getElementsByTagName("userfio").item(i).firstChild.data;
			phone = xmlRoot.getElementsByTagName("phone").item(i).firstChild.data;
			adress = xmlRoot.getElementsByTagName("adress").item(i).firstChild.data;
			
			str += '<tr class=tboard bgcolor=#EBE4D4 valign=top>';
			if (userfio == 'none')
			{
				str += '<td>&nbsp;</td>';
			}
			else
			{
				str += '<td>' + userfio + '</td>';
			}
			
			if (phone == 'none')
			{
				str += '<td>&nbsp;</td>';
			}
			else
			{
				str += '<td>' + phone + '</td>';
			}
			
			if (adress == 'none')
			{
				str += '<td>&nbsp;</td>';
			}
			else
			{
				str += '<td>' + adress + '</td>';
			}
			
			str += '</tr>';
		}
		
		str += '</table>';
		
		//str = 'ordertable' + order;
		myDiv = document.getElementById('ordertable' + order);
		myDiv.innerHTML = str;
		
		//alert (str);
	}
	else
	{
		order = xmlRoot.getElementsByTagName("order").item(0).firstChild.data;
		var str = '';
		str = '<table border=0 cellpadding=3 cellspacing=1 style="border:thin solid 1px #FF0000" align=center>';
		str += '<tr class=tboard bgcolor=#EBE4D4 valign=top><td>Клиент делает впервые заказ</td></tr>';
		str += '</table>';
		
		myDiv = document.getElementById('ordertable' + order);
		myDiv.innerHTML = str;
		
		//alert ('Клиент делает впервые заказ');
	}
}

</script>
<?

include $DOCUMENT_ROOT."/config.php";

$sql = "select orderdetails.`order`, orderdetails.amount as oamount, 
orderdetails.date as odate, 
shopcoins.*, `order`.`check` as ocheck, `order`.ip, order.user, order.userfio
from orderdetails, shopcoins, `order` 
where orderdetails.date > '".(time()-3600*24)."' 
and orderdetails.catalog=shopcoins.shopcoins 
and orderdetails.`order` = `order`.`order`  and orderdetails.status=0
order by orderdetails.`order` desc , orderdetails.date;";
$result = mysql_query($sql);

$OrderDetails = Array();
while ($rows = mysql_fetch_array($result))
{
	if($rows['user']>0 && $rows['user']!=811 && !$rows['userfio']) {
		$sql2 = "select fio from `user` where `user`=".$rows['user'].";";
		$result2 = mysql_query($sql2);
		$rows2 = mysql_fetch_array($result2);
		$OrderDetails[$rows["order"]]["fio"] = $rows2['fio']." ".$rows['user'];
	}
	else 
		$OrderDetails[$rows["order"]]["fio"] = ($rows['userfio']?$rows['userfio']:"---");
	
	$OrderDetails[$rows["order"]]["sum"] += $rows["oamount"]*$rows["price"];
	$OrderDetails[$rows["order"]]["name"][] = $rows["number"]." - ".$rows["shopcoins"]." - ".$rows["name"];
	$OrderDetails[$rows["order"]]["check"] = $rows["ocheck"];
	if (!$OrderDetails[$rows["order"]]["mindate"])
		$OrderDetails[$rows["order"]]["mindate"] = $rows["odate"];
	
	$OrderDetails[$rows["order"]]["maxdate"] = $rows["odate"];
	$OrderDetails[$rows["order"]]["ip"] = $rows["ip"];
}

echo "<table cellpadding=1 cellspacing=0 border=1 bordercolor=#b4b4b4>
<tr class=tboard valign=top>
<td>Заказ</td>
<td>На сумму</td>
<td>Содержимое</td>
<td>Начало</td>
<td>Конец</td>
<td>Сделан до конца</td>
<td>ФИО user</td>
<td>ip</td>
</tr>";
foreach ($OrderDetails as $orderkey => $value)
{
	echo "<tr class=tboard valign=top ".($OrderDetails[$orderkey]["check"]==1?"bgcolor=silver":"").">
	<td><a href=setcookies.php?order=".$orderkey." target=_blank>".$orderkey."</a></td>
	<td>".$OrderDetails[$orderkey]["sum"]."</td>
	<td>".implode("<br>",$OrderDetails[$orderkey]["name"])."</td>
	<td>".date("d H:i:s",$OrderDetails[$orderkey]["mindate"])."</td>
	<td>".date("d H:i:s",$OrderDetails[$orderkey]["maxdate"])."</td>
	<td>".($OrderDetails[$orderkey]["check"]==1?"Да":"Нет")."</td>
	<td>".$OrderDetails[$orderkey]["fio"]."</td>
	<td><a href=#order".$orderkey." onclick=\"javascript:process('$script?action=showip&ip=".$OrderDetails[$orderkey]["ip"]."&order=$orderkey');\">".$OrderDetails[$orderkey]["ip"]."</a><div id=ordertable".$orderkey."></div></td>
	</tr>";
}

echo "</table>";

?>
</body>
</html>
<?
}
?>