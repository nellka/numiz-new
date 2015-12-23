<?

$order = intval($_POST["order"]);

include $_SERVER["DOCUMENT_ROOT"]."/config.php";
include "config.php";
include "funct.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/funct.php";

if ($action!="showorderhtml" and $action!="showorderword")
{
	include "./top.php"; 
	table_top ("100%", 0, "Работа с заказами", 1);
}

$type = Array ("rusichbank"=>'Монеты',
				"shopcoins"=>'Монеты',
				"Album"=>'Аксессуары',
				"Book"=>'Книги',
				"programs"=>'Программы');

function Authorization ($login, $password)
{
	global $fio;
	if ($login and $password)
	{
		$login = str_replace("'","",$login);
		$password = str_replace("'","",$password);
		
		$sql = "select * from user where userlogin='$login' and userpassword='$password';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		//echo $sql;
		if ($rows[0])
		{
			$authorization = $rows["user"];
			$fio = $rows["fio"];
			//echo "<br>".$rows["fio"]." ".$authorization."<br>";
		}
		else
			$authorization = false;
	} else 
		$authorization = false;
	return $authorization;
}

function form_login($error)
{
	global $script, $aukcion, $aukcionquestion, $user;
	$form .= "<form action=$script method=post>
	<table cellpadding=3 cellspacing=1 border=0 width=50% align=center>
	<tr bgcolor=#EBE4D4><td class=tboard colspan=2><b>Введите Ваш логин и пароль</b></td></tr>
	<tr bgcolor=#EBE4D4><td class=tboard colspan=2>$error</tr>
	<tr bgcolor=#EBE4D4><td class=tboard><b>Логин:</b></td><td><input type=text name=login class=formtxt size=10 maxlength=20></td></tr>
	<tr bgcolor=#EBE4D4><td class=tboard><b>Пароль:</b></td><td><input type=password name=password class=formtxt size=10 maxlength=20></td></tr>
	<tr bgcolor=#EBE4D4><td colspan=2 class=tboard align=center><input type=submit name=submitauthorization value='Далее >>>' class=formtxt></td></tr>
	</table></form>";
	return $form;
}

$authorization = false;
$login = trim($login);
$password = trim($password);
if ($login and $password)
	$authorization = Authorization ($login, $password);

if ($authorization===false)
{
	if ($submit)
		$error = "<img src=".$in."images/attention.gif alt='Внимание'> <b>Несовпадение логина и пароля</b>";
	
	echo form_login($error);
} else {
	if (!$action)
		$action = "showorders";
	
	if ($action=="postreceipt" && $parent) {
		
		$mark = intval($mark);
		$parent = intval($parent);
		$Reminder = intval($Reminder);
		if ($Reminder==3) {
			$complected = intval($complected);
		}
		else
			$complected = 0;
		
		$sql_up = "UPDATE `order` SET ".($Reminder==3?"`PhonePostReceipt`='1',":"")." Reminder='$Reminder', ReminderComment='".strip_string($ReminderComment)."', 
	ReminderCommentDate = '".time()."', mark='".$mark."',complected=$complected WHERE `order`='".$parent."'";
		$result_up = mysql_query($sql_up);
		//echo $sql_up;
		$action = "showorders";
	}
	
	if ($action=="showorders")
	{
		
?>
<script type="text/javascript" src="ajax.php" language="JavaScript"></script>
<script>

function ShowFormPhonePostReceipt(order,Reminder,password,login, mark, complected)
{
	var divstr = "";
	divstr = "PhonePostReceipt" + order;
	
	var myform = "";
	
	myform = "Просим Вас ответить на вопросы.";
	myform += "<form action=<?=$script;?> method=post name=FormReminder>";
	myform += "<input type=hidden name=parent value='"+order+"'>";
	myform += "<input type=hidden name=action value='postreceipt'>";
	myform += "<input type=hidden name=login value='"+login+"'>";
	myform += "<input type=hidden name=password value='"+password+"'>";
	myform += "<table border=0 cellpadding=1 cellspacing=1 align=center>";
	myform += "<tr bgcolor=#EBE4D4><td class=tboard>Заказ</td><td class=tboard>";
	myform += "<select name=Reminder class=formtxt>";
	myform += "<option value=3 "; 
	if (Reminder==3) {
		myform += "selected";
	}
	myform += " >Получен</option>";
	myform += "<option value=4 "; 
	if (Reminder==4) {
		myform += "selected"; 
	}
	myform += " >Не получен</option>";
	myform += "</select>";
	myform += "</td></tr>";
	myform += "<tr bgcolor=#EBE4D4><td class=tboard>Оценка за обслуживание:</td><td class=tboard>";
	myform += "<select name=mark class=formtxt>";
	myform += "<option value=0 "; 
	if (mark==0) {
		myform += "selected";
	}
	myform += " >Выберите</option>";
	myform += "<option value=1 "; 
	if (mark==1) {
		myform += "selected";
	}
	myform += " >Хорошо</option>";
	myform += "<option value=2 "; 
	if (mark==2) {
		myform += "selected"; 
	}
	myform += " >Плохо</option>";
	myform += "</select>";
	myform += "</td></tr>";
	myform += "<tr bgcolor=#EBE4D4><td class=tboard>Комплектация заказа:</td><td class=tboard>";
	myform += "<select name=complected class=formtxt>";
	myform += "<option value=0 "; 
	if (complected==0) {
		myform += "selected";
	}
	myform += " >Выберите</option>";
	myform += "<option value=2 "; 
	if (complected==2) {
		myform += "selected";
	}
	myform += " >Нет отличий от описи</option>";
	myform += "<option value=1 "; 
	if (complected==1) {
		myform += "selected"; 
	}
	myform += " >Есть отличия от описи</option>";
	myform += "</select>";
	myform += "</td></tr>";
	myform += "<tr bgcolor=#EBE4D4><td class=tboard>Ваши пожелания:</td>";
	myform += "<td class=tboard><textarea name=ReminderComment class=formtxt cols=20 rows=3></textarea></td></tr>";
	myform += "<tr bgcolor=#EBE4D4><td colspan=2 class=tboard align=center><input type=submit name=submit value='Ответить' class=formtxt onclick=\"javascript:if (document.FormReminder.mark.value<1){alert ('Пожалуйста оцените качество обслуживания по данному заказу.'); return false;} else {return true;}\"></td></tr>";
	myform += "</table>";
	myform += "</form>";
//	myform += "<input type=button class=formtxt value='Записать' onclick=\"javascript:SavePhonePostReceipt('"+order+"');\">";
//	myform += "</td></tr>";
//	myform += "</form>";
	
	myDiv = document.getElementById(divstr);
	myDiv.innerHTML = myform;
}
				
function ShowSertificate(paymenttype,orderthis) {

	var str = '<form class=formtxt name=FormCoupon><table width=90% cellpadding=2 cellspacing=1 border=0 align=center><input type=hidden name=orderthis value='+orderthis+'>';
	str += '<tr><td class=txt bgcolor=#EBE4D4 colspan=2> Если у Вас есть подарочные сертификаты и Вы желаете их использовать для оплаты данного заказа, введите номер и код в нижеприведенной форме. В ином случае оставьте поле пустым.</td></tr>';
	str += '<tr><td class=txt width=40% bgcolor=#EBE4D4 align=right><strong>Номер сертификата (полностью):</strong><td class=txt bgcolor=#EBE4D4><input type=text name="numbersert" value="" size=6 maxlength=6 class=formtxt></td></tr>';
	str += '<tr><td class=txt width=40% bgcolor=#EBE4D4 align=right><strong>Код сертификата:</strong><td class=txt bgcolor=#EBE4D4><input type=text name="code1" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code2" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code3" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code4" value="" size=4 maxlength=4 class=formtxt></td></tr>';
	str += '<tr><td class=txt width=40% bgcolor=#EBE4D4> <div id="CouponInfo" name="CouponInfo">&nbsp;</div></td><td class=txt bgcolor=#EBE4D4><input type=button class=formtxt value="Воспользоваться подарочным сертификатом" onClick="javascript:CheckFormCoupon('+paymenttype+');"></td></tr>';
	str += '</table></form>';
	myDiv = document.getElementById("sertificate"+orderthis);
	myDiv.innerHTML = str;
}
				
function CheckFormCoupon(cardis)
{
	var numbersert = document.FormCoupon.numbersert.value;
	var code1 = document.FormCoupon.code1.value;
	var code2 = document.FormCoupon.code2.value;
	var code3 = document.FormCoupon.code3.value;
	var code4 = document.FormCoupon.code4.value;
	var orderthis = document.FormCoupon.orderthis.value;
	
	if (numbersert.length != 6) {
	
		alert ('Неверно введен номер сертификата');
		return 0;
	}

	if (code1.length != 4 || code2.length != 4 || code3.length != 4 || code4.length != 4 ) {
	
		alert ('Неверно введен код сертификата');
		return 0;
	}
	
	myDiv2 = document.getElementById('CouponInfo');
	myDiv2.innerHTML = '<img src=<?echo $in;?>images/wait.gif>';
	
	process ('activsert.php?shopcoinsorder99='+orderthis+'&sert='+numbersert+'&code1=' + code1 + '&code2=' + code2 + '&code3=' + code3 + '&code4=' + code4 + '&cardis=' + cardis);
	
}

function ShowSert() {

	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		
		var orderin = xmlRoot.getElementsByTagName("orderin").item(0).firstChild.data;
		
		if (orderin == "none") {
		
			alert ('нет заказа либо он уже отправлен или оплачен');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>нет заказа либо он уже отправлен или оплачен</font>';
		
		}
		else {
		
			var dissum = xmlRoot.getElementsByTagName("dissum").item(0).firstChild.data;
			var deltasum = xmlRoot.getElementsByTagName("deltasum").item(0).firstChild.data;
			var crcode = xmlRoot.getElementsByTagName("crcode").item(0).firstChild.data;
			var outsum = xmlRoot.getElementsByTagName("outsum").item(0).firstChild.data;
			var cardis = xmlRoot.getElementsByTagName("cardis").item(0).firstChild.data;
			
			alert ('Сумма '+dissum+' руб. использована в оплате заказа, остаток  на сертификате ' + (deltasum=="none"? "0.00": deltasum) + ' руб.');
			
			document.FormCoupon.code1.value = '';
			document.FormCoupon.code2.value = '';
			document.FormCoupon.code3.value = '';
			document.FormCoupon.code4.value = '';
			
			myDiv2 = document.getElementById('CouponInfo');
			
			myDiv2.innerHTML = 'Сумма '+dissum+' руб. использована в оплате заказа, остаток  на сертификате ' + (deltasum=="none"? "0.00": deltasum) + ' руб.';
			
			if (cardis != "none") {
			
				if (crcode != "none" && outsum != "none") {
				
					document.getElementById('SignatureValue'+orderin).value = crcode;
					document.getElementById('OutSum'+orderin).value = outsum;
					
					myDiv5 = document.getElementById('info'+orderin);
			
					myDiv5.innerHTML = outsum + ' руб.';
				
				}
				else {
				
					myDiv5 = document.getElementById('info'+orderin);
			
					myDiv5.innerHTML = 'Произошел сбой в системе - оплату картами VISA и MaterCard Вы можете произвести в "Ваши заказы"';
				}
				
			}
		}
	}
	else 
	{
		//alert('3');
		if (errorvalue == "error0") {
		
			alert ('Неверно введен код либо номер сертификата');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен код либо номер сертификата</font>';
		}
		else if (errorvalue == "error1") {
		
			alert ('Неверно введен номер сертификата');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен номер сертификата</font>';
		}
		else if (errorvalue == "error2") {
		
			alert ('неверные символы в коде');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>неверные символы в коде</font>';
		}
		else if (errorvalue == "error3") {
		
			alert ('нет заказа либо он уже отправлен или оплачен');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>нет заказа либо он уже отправлен или оплачен</font>';
		}
		else if (errorvalue == "error4") {
		
			alert ('Неверно введен код');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен код</font>';
		}
		else if (errorvalue == "error5") {
		
			alert ('Сертификат уже был полностью использован.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Сертификат уже был полностью использован.</font>';
		}
		else if (errorvalue == "error6") {
		
			alert ('Время действия купона истекло.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Время действия купона истекло.</font>';
		}
		else if (errorvalue == "error7") {
		
			alert ('Сумма заказа равна 0 руб.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Купон не принят, так как сумма заказа уже равна 0 руб.</font>';
		}
		else if (errorvalue == "error8") {
		
			alert ('Сертификат уже был полностью использован.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Сертификат уже был полностью использован.</font>';
		}
		
	}
}
				
				
</script>
<?php
		echo "<b>Добрый день, ".$fio."</b>
		<br>Вы авторизовались под логином $login . 
		<br>Это все Ваши заказы за 1 год, которые были сделаны под этим именем. Если Вы считаете, 
		что их должно быть больше - возможно они были сделаны под другим Вашим логином.
		<br>В любом случае можете обратиться к администратору сайта по электронной почте
		<a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> или по
		телефону <b>8-800-333-14-77 (бесплатный звонок по России) </b>.";
		
		if ( $authorization == 245796) {
		
			$sql = "select `order`.*,
			if (`order`.`order`>`order`.ParentOrder, `order`.`order`, `order`.ParentOrder) as OrderOrder 
			from `order` where `user`='$authorization' and `check`=1 and date>'".(time()-86400*365*2)."' and payment<2 order 
			by OrderOrder desc, date desc;";
		}
		elseif ( $authorization == 811) {
		
			$sql = "select `order`.*,
			if (`order`.`order`>`order`.ParentOrder, `order`.`order`, `order`.ParentOrder) as OrderOrder 
			from `order` where `user`='$authorization' and `check`=1 and date>'".(time()-86400*100*2)."'  order 
			by OrderOrder desc, date desc;";
		}
		else {
		
			$sql = "select `order`.*,
			if (`order`.`order`>`order`.ParentOrder, `order`.`order`, `order`.ParentOrder) as OrderOrder 
			from `order` where `user`='$authorization' and `check`=1 ".($authorization!=279931?"and date>'".(time()-86400*365*2)."'":"")." order 
			by OrderOrder desc, date desc;";
		}
		$result = mysql_query($sql);
		
		echo "<br><br><table cellpadding=3 cellspacing=1 border=0 align=center width=95%>
		<tr bgcolor=#EBE4D4>
		<td class=tboard><b>№</b></td>
		<td class=tboard><b>Заказан</b></td>
		<td class=tboard><b>Отправка</b></td>
		<td class=tboard><b>Оплата</b></td>
		<td class=tboard><b>Номер посылки</b></td>
		<td class=tboard><b>Вес</b></td>
		<td class=tboard><b>Сумма</b></td>
		<td class=tboard><b>К оплате</b></td>
		<td class=tboard><b>Получение заказа</b></td>
		<td class=tboard><b>Предоплата получена</b></td>
		<td class=tboard><b>Отчет</b></td>
		</tr>";
		while ($rows = mysql_fetch_array($result))
		{
			echo "<tr ".($rows["ParentOrder"]>0?"bgcolor=#EBE4D4":"bgcolor=#EBE4D4")." valign=top >
			<td class=tboard nowrap>".($rows["ParentOrder"]>0?"<img src=".$in."images/folderfor.gif> ":"").$rows["order"].($authorization == 811?"<br>".$rows['userfio']:"")."</td>
			<td class=tboard>".date("y-m-d", $rows["date"])."</td>
			<td class=tboard align=center>".($rows["SendPost"]?date("y-m-d", $rows["SendPost"]):"-")."</td>
			<td class=tboard><nobr>".$SumName[$rows["payment"]];
			
			if ($rows["payment"]==6) echo "<br><a href=kak_oplatit_kartoi_sberbanka.html target=_blank>Как оплатить заказ картой Сбербанка</a> ";
			
			$dissert = 0;
			
			if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"]) {
			
				$sql9 = "select * from ordergiftcertificate where `order`=".$rows["order"]." and `check`=1;";
				$result9 = mysql_query($sql9);
				while ($rows9 = mysql_fetch_array($result9) )
					$dissert += $rows9['sum'];
				
			}
			
			if ($rows["payment"] == 6 && !$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"])
			{
				echo " [ <a href='sbrf.php?NUMBER=".$rows["order"]."&FIO=".urlencode(strip_string($rows["userfio"]))."&ADRESS=".urlencode($rows["adress"])."&SUM=".($rows["FinalSum"]-$dissert)."' target='_blank'>Распечатать квитанцию</a> ]";
			}
			
			if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"] && $rows["ParentOrder"]==0 && (($rows['payment'] !=1 && $rows['payment'] !=2 && ($rows['delivery']==4 || $rows['delivery']==6)) || (($rows['delivery']==10 || $rows['delivery']==2) && ($ipmyshop==$_SERVER['REMOTE_ADDR'] || $_SERVER['REMOTE_ADDR']=="127.0.0.1")))) {
			
				$resultsum = ($rows['SumAll']>0?$rows['SumAll']:($rows['FinalSum']>0?$rows['FinalSum']:$rows['sum']))-$dissert;
				
				if ($rows["delivery"] == 4 || $rows["delivery"] == 6 || $rows['delivery']==10 || $rows['delivery']==2) {
	
					unset($shopcoinsorder);
					
					$shopcoinsorder[] = $rows['order'];
					
					$sql3 = "SELECT * FROM `order` WHERE `ParentOrder`='".$rows['order']."'";
					$result3 = mysql_query($sql3);
					while($rows3 = mysql_fetch_array($result3)) {
					
						$shopcoinsorder[] = $rows3['order'];
					}
					
					if (sizeof($shopcoinsorder)<2)
						$shopcoinsorder = $shopcoinsorder[0];
					
					$sql_tmp = "select count(*) from `order` where `user`='".$authorization."' and `user`<>811 and `check`=1 and `order`<>'".$rows['order']."' and `date`>(".$rows['date']."-365*24*60*60);";
					$result_tmp = mysql_query($sql_tmp);
					$rows_tmp = mysql_fetch_array($result_tmp);
					if ($rows_tmp[0]>=3)
						$clientdiscount = 1;
					else
						$clientdiscount = 0;
					
					preg_match_all('/\d{6}/', $rows["adress"], $found);
					$postindex = trim($found[0][0]);
					$checking = 1;
					
					unset ($PostAllPrice);
					unset ($suminsurance);
					
					if (!$postindex)
						$postindex = "690000";
					
					if ($postindex)
						PostSum ($postindex, $shopcoinsorder, $clientdiscount);
					
					if ($rows["delivery"] == 6) {
					
						if ($bascetpostweight < 1000) 
							$sumEMC = 650;
						else {
						
							$sumEMC = 650;
							$sumEMC += ceil(($bascetpostweight - 1000)/500)*50;
						}
						
						$resultsum = ($bascetsum+$PriceLatter+10+$sumEMC);
					}
					elseif($rows['delivery']==10 || $rows['delivery']==2)
						$resultsum = $bascetsum;
					else	
						$resultsum = $PostAllPrice;
				}
				
				
				$crcode  = md5("numizmatikru:".sprintf ("%01.2f",round($resultsum*$krobokassa-$dissert,2)).":".$rows['order'].":$robokassapasword1:Shp_idu=$cookiesuser");
				$culture = "ru";
				$in_curr = "BANKOCEAN2R";
				
				echo "<form action='".$urlrobokassa."/Index.aspx' method=POST>".
   "<input type=hidden name=MrchLogin value='numizmatikru'>".
   "<input id=OutSum".$rows['order']." type=hidden name=OutSum value='".sprintf ("%01.2f",round($resultsum*$krobokassa-$dissert,2))."'>".
   "<input type=hidden name=InvId value='".$rows['order']."'>".
   "<input type=hidden name=Desc value='Оплата предметов нумизматики'>".
   "<input id=SignatureValue".$rows['order']." type=hidden name=SignatureValue value='$crcode'>".
   "<input type=hidden name=Shp_idu value='$cookiesuser'>".
   "<input type=hidden name=IncCurrLabel value='$in_curr'>".
   "<input type=hidden name=Culture value='$culture'>".
   "<input class=tboard type=submit value='Оплатить VISA, MasterCard'> - <div id=info".$rows['order'].">".sprintf ("%01.2f",round($resultsum*$krobokassa-$dissert,2))." руб.</div> (При оплате банковскими картами комиссия 4%)".
   "</form>";
			}
			
			if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"] && $rows["ParentOrder"]==0 && $rows['payment'] !=1 && 1==2) {
			
				echo "<div id=sertificate".$rows["order"]."><input type=button value='Использовать подарочный сертификат' onclick=\"javascript:ShowSertificate(".($rows["delivery"] == 4 || $rows["delivery"] == 6?1:0).",".$rows["order"].");\"></div>";
			}
			
			echo "</nobr></td>
			<td class=tboard>".($rows["SendPostBanderoleNumber"]?$rows["SendPostBanderoleNumber"]."<br><center>[ <form action=\"http://www.russianpost.ru/resp_engine.aspx?Path=rp/servise/ru/home/postuslug/trackingpo\" method=post target=_blank>
			<input type=hidden name=searchsign value=1>
			<input type=hidden name=BarCode value=".$rows["SendPostBanderoleNumber"].">
			<input type=submit name=submit value=Отследить class=formtxt ></form> ]</center>":"-")."</td>
			<td class=tboard>".($rows["weight"]>0?$rows["weight"]." кг.":"")."</td>
			<td class=tboard>".round($rows["sum"])."</td>
			<td class=tboard>".($rows["FinalSum"]>0 && date("Y", $rows["date"])>=2008?$rows["FinalSum"]:"-")."</td>
			<td class=tboard><a name=order".$rows["order"]."></a><div id=PhonePostReceipt".$rows["order"].">".($rows["Reminder"]==3?"<b>Получен</b>":"<a href=#order".$rows["order"]." onclick=\"javascript:ShowFormPhonePostReceipt('".$rows["order"]."','".$rows["Reminder"]."','".$password."','".$login."','".$rows['mark']."','".$rows['complected']."');\">Сообщить</a></div>")." ".($rows["ReminderComment"]?"<br>".$rows["ReminderComment"]:"")."</td>
			<td class=tboard align=center>";
			if ($rows["ParentOrder"]==0 && ($rows["payment"]==3 || $rows["payment"]==4 || $rows["payment"]==5 || $rows["payment"]==6) )
			{
				echo ($rows["ReceiptMoney"]>0?date("y-m-d",$rows["ReceiptMoney"]):"<b><font color=red>НЕТ</font></b>");
			}
			else
			{
				echo "-";
			}
			echo "</td>
			<td class=tboard>
			<form action=$script method=post target=_blank name=ttform>
			<input type=hidden name=login value='$login'>
			<input type=hidden name=password value='$password'>
			<input type=hidden name=order value='".$rows["order"]."'>
			<input type=hidden name=action value='showorderhtml'>
			<input type=submit name=submit value='Отчет' class=formtxt>
			</td>
			</form>
			</tr>";
		}
		echo "</table><br><br>";
	} elseif ($action=="showorderhtml" and $order) {
		//формируем отчет в html
		//монеты
		$order = intval($order);
		echo "<html>
		<head>
		<title>Отчет</title>
		<meta name=\"keywords\">
		<meta name=\"description\">
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
		</head>
		<link rel=stylesheet type=text/css href=".$in."bodka.css>
		<link rel=\"SHORTCUT ICON\" href=\"".$in."favicon.ico\"> 
		<body bgcolor=white bottommargin=10 leftmargin=10 rightmargin=10 marginheight=10 topmargin=10 marginwidth=10>";
		
		echo "<br><b class=txt>Номер заказа $order</b>";
		$sql = "select o.*, o.admincheck, u.fio, u.email from `order` as o left join user as u on o.user=u.user
		where o.order='".$order."';";
		$result = mysql_query($sql);
		echo "<table border=1 cellpadding=1 cellspacing=0>
		<tr><td class=tboard bgcolor=silver colspan=2><b>Информация о покупателе</b> [<a href=# onclick=\"javascitp:window.open('shopcoinsuseredit.php?order=$order','shopcoinsuseredit".$order."','width=400,height=300, scrollbars=1');\">Редактировать</a>]</td></tr>";
		while ($rows = mysql_fetch_array($result))
		{
			echo "<tr><td class=tboard>ФИО:</td><td class=tboard>&nbsp;".$rows["fio"]."</td></tr>";
			echo "<tr><td class=tboard>Город:</td><td class=tboard>&nbsp;";
			if (!ereg("^\-{0,1}[0-9]{1,}$", $rows["city"])) {$city = $rows["city"];} else {$city = $city_array[$rows["city"]];}
			echo "$city</td></tr>";
			echo "<tr><td class=tboard>Контактный телефон:</td><td class=tboard>".$rows["phone"]."</td></tr>";
			echo "<tr><td class=tboard>E-mail:</td><td class=tboard>&nbsp;<a href=mailto:".$rows["email"].">".$rows["email"]."</a></td></tr>";
			if ($rows["adress"])
				echo "<tr><td class=tboard>Адрес доставки:</td><td class=tboard>".str_replace("\n", "<br>", $rows["adress"])."</td></tr>";
			echo "<tr><td class=tboard>Тип оплаты:</td><td class=tboard>".$SumName[$rows["payment"]]."</td></tr>";
			
			if ($rows["payment"] == 2)
			{
				if ($rows["MetroMeeting"])
					echo "<tr><td class=tboard>Метро:</td><td class=tboard>".$MetroArray[$rows["MetroMeeting"]]."</td></tr>";
				if ($rows["DateMeeting"])
					echo "<tr><td class=tboard>Время встречи:</td><td class=tboard>".date("Y-m-d H:i", $rows["DateMeeting"])."</td></tr>";
			}
			if ($rows["OtherInformation"])
				echo "<tr><td class=tboard>Другая информация:</td><td class=tboard>".str_replace("\n", "<br>", $rows["OtherInformation"])."</td></tr>";
			
			$admincheck = $rows["admincheck"];
			$adress_recipient = $city.", ".str_replace("\n", " ", $rows["adress"]);
			$fio_recipient = $rows["fio"];
			$type = $rows["type"];
			$checking = 1;
			if (!$postindex) 
				$postindex = intval(substr(trim($rows['adress']),0,6));
			$shopcoinsorder = $rows['order'];
			PostSum ($postindex, $shopcoinsorder,$clientdiscount);
		}
		echo "</table>";
		
		//echo $type;
		
		//монеты
		if ($type =="shopcoins")
		{
			
			$sql_tmp = "select count(*) from `order` where `user`='".$authorization."' and `user`<>811 and `check`=1 and `order`<'".$order."' and `date`>(".time()."-365*24*60*60);";
			$result_tmp = mysql_query($sql_tmp);
			$rows_tmp = mysql_fetch_array($result_tmp);
			if ($rows_tmp[0]>=3)
				$clientdiscount = 1;
			else
				$clientdiscount = 0;
			//echo "<br>".$sql_tmp."<br>";
			//содержимое заказа
			$order_doc .= "<b class=txt>Содержимое заказа:</b>";
			$order_doc .= "<table border=0 cellpadding=3 cellspacing=1 align=center width=100% >
			<tr bgcolor=#ffcc66>
			<td class=tboard><b>Изображение</b></td>
			<td class=tboard><b>Описание</b></td>
			<td class=tboard><b>Изображение</b></td>
			<td class=tboard><b>Описание</b></td>
			</tr>";
			$sql = "select o.*, c.name, if(o.amount>=c.amount5 and c.price5>0,c.price5,
				if 
				(o.amount>=c.amount4 and c.price4>0,c.price4,
					if
					(o.amount>=c.amount3 and c.price3>0,c.price3,
						if
						(o.amount>=c.amount2 and c.price2>0,c.price2,
							if
							(o.amount>=c.amount1 and c.price1>0,c.price1,".($clientdiscount==1?"if(c.clientprice>0, c.clientprice, c.price)":"c.price").")
						)
					)
				)
			) as price, c.image, c.metal, c.year, 
			c.condition, c.number, c.shopcoins, g.name as gname, c.materialtype, c.details
			 from `orderdetails` as o left join shopcoins as c 
			on o.catalog = c.shopcoins 
			left join `group` as g on c.group=g.group 
			where o.order='".$order."' and o.typeorder=1 and o.status=0 order by c.materialtype, c.number;";
			$result = mysql_query($sql);
			$sum = 0;
			$what = "";
			$k=0;
			$oldmaterialtype = 0;
			
			while (	$rows = mysql_fetch_array($result))
			{
				
				if ($oldmaterialtype != $rows["materialtype"])
				{
					
					if ($k%2 == 0 and $k!= 0)
						$order_doc .= "</tr>";
					elseif ($k%2 == 0 and $k==0)
					{}
					else
						$order_doc .= "<td>&nbsp;</td><td>&nbsp;</td></tr>";
					
					if ($k!=0)
						$order_doc .= "<tr><td colspan=4 class=tboard><b>".$MaterialTypeArray[$oldmaterialtype].": $k</b></td></tr>";
					
					$order_doc .= "<tr><td colspan=4 class=tboard bgcolor=#99CCFF><b>".$MaterialTypeArray[$rows["materialtype"]]."</b></td></tr>";
					$oldmaterialtype = $rows["materialtype"];
					$k = 0;
				}
				
				if ($k%2==0)
				{
					if ($k!=0)
						$order_doc .= "</tr><tr bgcolor=#EBE4D4 valign=top>";
					else
						$order_doc .= "<tr bgcolor=#EBE4D4 valign=top>";
				}
				$what .= $rows["name"].", ";
				$temp = GetImageSize($DOCUMENT_ROOT."/shopcoins/images/".$rows["image"]);
				$imagewidth = $temp[0];
				$order_doc .= "<td class=tboard width=220>
				<img src=../shopcoins/images/".$rows["image"]." border=1 width=\"$imagewidth\" WIDTH='$imagewidth' width=$imagewidth WIDTH=$imagewidth>
				</td>";
				
				if ($rows["materialtype"]==1 || $rows["materialtype"]==10 || $rows["materialtype"]==12)
				{
				
					$order_doc .= "<td class=tboard valign=top width=200>
					<a name=s".$rows["shopcoins"]."></a>";
					$order_doc .= "<br>".$rows["name"];
					if ($rows["year"]) $order_doc .= "<br><b>Год: </b>".$rows["year"];
					if (trim($rows["metal"])) $order_doc .= "<br><b>Метал: </b>".$rows["metal"];
					if ($rows["gname"]) $order_doc .=  "<br><b>Страна: </b>".$rows["gname"];
					if ($rows["number"]) $order_doc .=  "<br><b>Каталог: </b>".$rows["number"];
					if ($rows["condition"]) $order_doc .=  "<br><b>Состояние: </b>".$rows["condition"];
					if ($rows["details"]) $order_doc .=  "<br><b>Описание: </b>".str_replace("\n","<br>",$rows["details"]);
					$order_doc .= "<br><b>Цена: </b> ".round($rows["price"],2)." руб.
					</td>";
				}
				elseif ($rows["materialtype"]==8  or $rows["materialtype"]==7 or $rows["materialtype"]==4 or $rows["materialtype"]==2)
				{
					$order_doc .= "<td class=tboard valign=top width=200>
					<a name=s".$rows["shopcoins"]."></a>";
					$order_doc .= "<br>".$rows["name"];
					$order_doc .= "<br><b>Количество: <font color=blue>".($rows["amount"]?$rows["amount"]:"1")."</font></b>";
					if ($rows["year"]) $order_doc .= "<br><b>Год: </b>".$rows["year"];
					if (trim($rows["metal"])) $order_doc .= "<br><b>Метал: </b>".$rows["metal"];
					if ($rows["gname"]) $order_doc .=  "<br><b>Страна: </b>".$rows["gname"];
					if ($rows["number"]) $order_doc .=  "<br><b>Каталог: </b>".$rows["number"];
					if ($rows["condition"]) $order_doc .=  "<br><b>Состояние: </b>".$rows["condition"];
					if ($rows["details"]) $order_doc .=  "<br><b>Описание: </b>".str_replace("\n","<br>",$rows["details"]);
					$order_doc .= "<br><b>Цена: </b> ".round($rows["price"],2)." руб.
					</td>";
				}
				elseif ($rows["materialtype"]==3)
				{
					$order_doc .= "<td class=tboard valign=top width=200>
					<a name=s".$rows["shopcoins"]."></a>";
					$order_doc .= "<br>".$rows["name"];
					$order_doc .= "<br><b>Количество: <font color=blue>".$rows["amount"]."</font></b>";
					if ($rows["gname"]) $order_doc .=  "<br><b>Группа: </b>".$rows["gname"];
					if ($rows["accessoryProducer"]) $order_doc .= "<br><b>Изготовитель: </b>".$rows["accessoryProducer"];
					if (trim($rows["accessoryColors"])) $order_doc .= "<br><b>Цвета: </b>".$rows["accessoryColors"];
					if ($rows["accessorySize"]) $order_doc .=  "<br><b>Размеры: </b>".$rows["accessorySize"];
					if ($rows["details"]) $order_doc .=  "<br><b>Описание: </b>".str_replace("\n","<br>",$rows["details"]);
					$order_doc .= "<br><b>Цена: </b> ".round($rows["price"],2)." руб.
					</td>";
				}
				$shopcoins_string .= "#".$rows["shopcoins"];
				//$order_doc .=  "</td><td class=tboard>".$rows["price"]." руб.</td>";
				$sum += $rows["amount"]*$rows["price"];
				$k++;
			}
			if ($k%2 == 0)
				$order_doc .= "</tr>";
			else
				$order_doc .= "<td>&nbsp;</td><td>&nbsp;</td></tr>";
			$what = substr($what, 0, -2);
			
			if ($discountcoupon>0) {
			
				$order_doc .=  "<tr><td colspan=2 class=tboard><b>".$MaterialTypeArray[$oldmaterialtype].": $k</b></td><td class=tboard align=right><b>Сумма заказа:</b></td><td class=tboard>".$sum." руб.</td></tr>";
				$order_doc .=  "<tr bgcolor=#ddaaee><td colspan=2 class=tboard><b>&nbsp;</b></td><td class=tboard align=right><b>Скидка по купону(ам):</b></td><td class=tboard>".$discountcoupon." руб.</td></tr>";
				$order_doc .=  "<tr><td colspan=2 class=tboard><b>&nbsp;</b></td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".(($sum-$discountcoupon<0)?0:$sum-$discountcoupon)." руб.</td></tr>";
			}
			else
				$order_doc .=  "<tr><td colspan=2 class=tboard><b>".$MaterialTypeArray[$oldmaterialtype].": $k</b></td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".$sum." руб.</td></tr>";
			
			$order_doc .=  "</table>";
			
			echo $order_doc;
			
		} elseif ($type=="Book") {
			$BookImagesFolder = $in."book/images";
			//сначала о пользователе
			/*
			echo "<a href=$script?frame=$frame&pagenum=$pagenum&search=$search&sendposttype=$sendposttype&PaymentOrder=$PaymentOrder&type=".$_GET["type"]."><img src=".$in."images/back.gif alt='Назад' border=0></a>";
			echo "<br><b class=txt>Номер заказа $order</b>";
			$sql = "select o.*, o.admincheck, u.fio, u.email from `order` as o left join user as u on o.user=u.user
			where o.order='".$order."' and type='$type';";
			$result = mysql_query($sql);
			echo "<table border=1 cellpadding=3 cellspacing=0>
			<tr><td class=tboard bgcolor=silver colspan=2><b>Информация о покупателе</b></td></tr>";
			while ($rows = mysql_fetch_array($result))
			{
				echo "<tr><td class=tboard>ФИО:</td><td class=tboard>&nbsp;".$rows["fio"]."</td></tr>";
				echo "<tr><td class=tboard>Город:</td><td class=tboard>";
				if (!ereg("^\-{0,1}[0-9]{1,}$", $rows["city"])) {$city = $rows["city"];} else {$city = $city_array[$rows["city"]];}
				echo "$city</td></tr>";
				echo "<tr><td class=tboard>Контактный телефон:</td><td class=tboard>".$rows["phone"]."</td></tr>";
				echo "<tr><td class=tboard>E-mail:</td><td class=tboard>&nbsp;<a href=mailto:".$rows["email"].">".$rows["email"]."</a></td></tr>";
				echo "<tr><td class=tboard>Адрес доставки:</td><td class=tboard>".str_replace("\n", "<br>", $rows["adress"])."</td></tr>";
				echo "<tr><td class=tboard>Тип оплаты:</td><td class=tboard>";
				if ($rows["payment"]=="webmoney") {
					echo "Webmoney";
					$discount = 0.1;
				} elseif ($rows["payment"]=="yandexmoney") {
					echo "Yandex.Деньги";
					$discount = 0.1;
				} else {
					echo "Наложенный платеж";
					$discount = 0;
				}
				echo "</td></tr>";
				$admincheck = $rows["admincheck"];
				$adress_recipient = $city.", ".str_replace("\n", " ", $rows["adress"]);
				$fio_recipient = $rows["fio"];
			}
			echo "</table>";
			*/
			//содержимое заказа
			echo "<br><b class=txt>Содержимое заказа:</b>";
			echo "<table border=0 cellpadding=3 cellspacing=1>
			<tr bgcolor=#ffcc66>
			<td class=tboard><b>Изображение</b></td>
			<td class=tboard><b>Описание</b></td>
			<td class=tboard><b>Цена</b></td>
			<td class=tboard><b>Количество</b></td>
			</tr>";
			$sql = "select o.*, c.*
			 from `orderdetails` as o left join Book as c 
			on o.catalog = c.BookID where o.order='".$order."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
			$result = mysql_query($sql);
			$sum = 0;
			$what = "";
			while (	$rows = mysql_fetch_array($result))
			{
				echo "<tr bgcolor=#EBE4D4 valign=top>
				<td class=tboard width=300><a href=".$server_name."/book/index.php?catalog=".$rows["Book"]."&PaymentOrder=$PaymentOrder&type=$type target=_blank>".$rows["BookName"]."</a>
				<br><img src=$BookImagesFolder/".$rows["BookImage"].">
				</td><td class=tboard valign=top>";
				if ($rows["BookDetails"]) echo "<br><b>Подробнее: </b>".str_replace("\n", "<br>", $rows["BookDetails"]);
				echo "</td><td class=tboard>".round($rows["BookPrice"], 2)." руб.</td>
				<td class=tboard align=center>".$rows["amount"]."</td>";
				$sum += $rows["amount"]*$rows["BookPrice"];
				echo "</tr>";
				$what .= $rows["BookName"];
				if ($rows["amount"]>1)
					$what .= "(".$rows["amount"]." шт.)";
				$what .= ", ";
			}
			echo "<tr><td>&nbsp;</td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".round((1-$discount)*$sum, 2)." руб.</td><td class=tboard>&nbsp;</td></tr>";
			echo "</table>";
		} elseif ($type=="Album") {
			$AlbumImagesFolder = $in."album/images";
			/*
			//сначала о пользователе
			echo "<a href=$script?frame=$frame&pagenum=$pagenum&search=$search&PaymentOrder=$PaymentOrder&type=".$_GET["type"]."><img src=".$in."images/back.gif alt='Назад' border=0></a>";
			echo "<br><b class=txt>Номер заказа $order</b>";
			$sql = "select o.*, o.admincheck, u.fio, u.email from `order` as o left join user as u on o.user=u.user
			where o.order='".$order."' and type='$type';";
			$result = mysql_query($sql);
			echo "<table border=1 cellpadding=3 cellspacing=0>
			<tr><td class=tboard bgcolor=silver colspan=2><b>Информация о покупателе</b></td></tr>";
			while ($rows = mysql_fetch_array($result))
			{
				echo "<tr><td class=tboard>ФИО:</td><td class=tboard>&nbsp;".$rows["fio"]."</td></tr>";
				echo "<tr><td class=tboard>Город:</td><td class=tboard>";
				if (!ereg("^\-{0,1}[0-9]{1,}$", $rows["city"])) {$city = $rows["city"];} else {$city = $city_array[$rows["city"]];}
				echo "$city</td></tr>";
				echo "<tr><td class=tboard>Контактный телефон:</td><td class=tboard>".$rows["phone"]."</td></tr>";
				echo "<tr><td class=tboard>E-mail:</td><td class=tboard>&nbsp;<a href=mailto:".$rows["email"].">".$rows["email"]."</a></td></tr>";
				echo "<tr><td class=tboard>Адрес доставки:</td><td class=tboard>".str_replace("\n", "<br>", $rows["adress"])."</td></tr>";
				echo "<tr><td class=tboard>Тип оплаты:</td><td class=tboard>";
				if ($rows["payment"]=="webmoney") {
					echo "Webmoney";
					$discount = 0.1;
				} elseif ($rows["payment"]=="yandexmoney") {
					echo "Yandex.Деньги";
					$discount = 0.1;
				} else {
					echo "Наложенный платеж";
					$discount = 0;
				}
				echo "</td></tr>";
				$admincheck = $rows["admincheck"];
				$adress_recipient = $city.", ".str_replace("\n", " ", $rows["adress"]);
				$fio_recipient = $rows["fio"];
			}
			echo "</table>";
			*/
			//содержимое заказа
			echo "<br><b class=txt>Содержимое заказа:</b>";
			echo "<table border=0 cellpadding=3 cellspacing=1>
			<tr bgcolor=#ffcc66>
			<td class=tboard><b>Изображение</b></td>
			<td class=tboard><b>Описание</b></td>
			<td class=tboard><b>Цена</b></td>
			<td class=tboard><b>Количество</b></td>
			</tr>";
			$sql = "select o.*, 
			c.*
			 from `orderdetails` as o left join Album as c 
			on o.catalog = c.AlbumID where o.order='".$order."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
			$result = mysql_query($sql);
			$sum = 0;
			$what = "";
			while (	$rows = mysql_fetch_array($result))
			{
				echo "<tr bgcolor=#EBE4D4 valign=top>
				<td class=tboard width=300><a href=".$server_name."/album/index.php?catalog=".$rows["Album"]."&PaymentOrder=$PaymentOrder&type=$type target=_blank>".$rows["AlbumName"]."</a>
				<br><img src=$AlbumImagesFolder/".$rows["AlbumImage"].">
				</td><td class=tboard valign=top>";
				if ($rows["AlbumDetails"]) echo "<br><b>Подробнее: </b>".str_replace("\n", "<br>", $rows["AlbumDetails"]);
				echo "</td><td class=tboard>".round($rows["AlbumPrice"], 2)." руб.</td>
				<td class=tboard align=center>".$rows["amount"]."</td>";
				$sum += $rows["amount"]*$rows["AlbumPrice"];
				echo "</tr>";
				$what .= $rows["AlbumName"];
				if ($rows["amount"]>1)
					$what .= "(".$rows["amount"]." шт.)";
				$what .= ", ";
			}
			echo "<tr><td>&nbsp;</td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".round((1-$discount)*$sum, 2)." руб.</td><td class=tboard>&nbsp;</td></tr>";
			echo "</table>";
		}
		
		
		echo "<center>[<img src=../images/printer.gif> <a href=# onclick=\"window.print();\">Распечатать</a>]</center>";
		echo "</body>
		</html>";
	} else {
		echo "Для просмотра заказов необходима авторизация!";
		echo form_login($error);
	}
}

if ($action!="showorderhtml" and $action!="showorderword")
{
	table_down ("1", "100%");
	include $_SERVER["DOCUMENT_ROOT"]."/bottomsmall.php";
	//echo ""
}
?>
