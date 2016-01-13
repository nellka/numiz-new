<b>Добрый день!</b>
<br>Вы авторизовались под логином <?=$tpl['user']['username']?>. 
<br>Это все Ваши заказы за 1 год, которые были сделаны под этим именем. Если Вы считаете, 
что их должно быть больше - возможно они были сделаны под другим Вашим логином.
<br>В любом случае можете обратиться к администратору сайта по электронной почте
<a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> или по
телефону <b>8-800-333-14-77 (бесплатный звонок по России) </b>.
		
<br><br>
<table cellpadding=3 cellspacing=1 border=0 align=center width=95%>
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
		</tr>
		<? foreach ($tpl['orders'] as $rows){
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
		?>