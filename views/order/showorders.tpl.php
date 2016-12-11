<b>Добрый день!</b>
<br>Вы авторизовались под логином <?=$tpl['user']['username']?>. 
<br>Это все Ваши заказы за 1 год, которые были сделаны под этим именем. Если Вы считаете, 
что их должно быть больше - возможно они были сделаны под другим Вашим логином.
<br>В любом случае можете обратиться к администратору сайта по электронной почте
<a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> или по
телефону <b>8-800-333-14-77 (бесплатный звонок по России) </b>.
		
<br><br>

	
<table cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
		<tr>
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
		<? foreach ($tpl['orders'] as $rows){?>
			<tr valign=top >
			<td class=tboard nowrap><?=($rows["ParentOrder"]>0?"<img src='".$cfg['site_dir']."images/folderfor.gif'>":"").$rows["order"].($tpl['user']['user_id'] == 811?"<br>".$rows['userfio']:"")?></td>
			<td class=tboard><?=date("y-m-d", $rows["date"])?></td>
			<td class=tboard align=center><?=($rows["SendPost"]?date("y-m-d", $rows["SendPost"]):"-")?></td>
			<td class=tboard><?=$SumName[$rows["payment"]];
						
			if ($rows["payment"]==6) {?>
			    <br><a href='kak_oplatit_kartoi_sberbanka.html' target=_blank>Как оплатить заказ картой Сбербанка</a>
			<?}
			
						
			if ($rows["payment"] == 6 && !$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"]){?>
				<!--[ <a href='sbrf.php?NUMBER=<?=$rows["order"]?>&FIO=<?=urlencode($rows["userfio"])?>&ADRESS=<?=urlencode($rows["adress"])?>&SUM=<?=($rows["FinalSum"]-$row['dissert'])?>' target='_blank'>Распечатать квитанцию</a> ]-->
				[ <a href='sbrf.php?NUMBER=<?=$rows["order"]?>&FIO=<?=urlencode($rows["userfio"])?>&ADRESS=<?=urlencode($rows["adress"])?>&SUM=<?=($rows["resultsum"]-$row['dissert'])?>' target='_blank'>Распечатать квитанцию</a> ]
				
			<?}
			
			if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"] && $rows["ParentOrder"]==0 && (($rows['payment'] !=1 && $rows['payment'] !=2 && ($rows['delivery']==4 || $rows['delivery']==6)) || (($rows['delivery']==10 || $rows['delivery']==2) && ($ipmyshop==$_SERVER['REMOTE_ADDR'])))) 
			{	?>	
				
				<form action='<?=$urlrobokassa?>/Index.aspx' method=POST>
               <input type=hidden name=MrchLogin value='numizmatikru'>
               <input id=OutSum<?=$rows['order']?> type=hidden name=OutSum value='<?=$rows['OutSum']?>'>
               <input type=hidden name=InvId value='<?=$rows['order']?>'>
               <input type=hidden name=Desc value='Оплата предметов нумизматики'>
               <input id=SignatureValue<?=$rows['order']?> type=hidden name=SignatureValue value='<?=$rows['crcode']?>'>
               <input type=hidden name=Shp_idu value='<?=$tpl['user']['user_id']?>'>
               <input type=hidden name=IncCurrLabel value='BANKOCEAN2R'>
               <input type=hidden name=Culture value='ru'>
               <input class=button25 type=submit value='Оплатить VISA, MasterCard'> - <div id=info<?=$rows['order']?>><?=$rows['OutSum']?> руб.</div> 
               (При оплате банковскими картами комиссия 4%)
               </form>
			<?}?>		
			
			</td>
			<td class=tboard><?
			if($rows["SendPostBanderoleNumber"]){?>
			   <?= $rows["SendPostBanderoleNumber"]?><br><center>[ <form action="http://www.russianpost.ru/resp_engine.aspx?Path=rp/servise/ru/home/postuslug/trackingpo" method=post target=_blank>}
			<input type=hidden name=searchsign value=1>
			<input type=hidden name=BarCode value=<?=$rows["SendPostBanderoleNumber"]?>>
			<input type=submit name=submit value=Отследить class=formtxt ></form> ]</center>
            <?} else {?>
            -
            <?}?>
            </td>
			<td class=tboard><?=($rows["weight"]>0?$rows["weight"]." кг.":"")?></td>
			<td class=tboard><?=round($rows["sum"])?></td>
			<!--<td class=tboard><?=($rows["FinalSum"]>0 && date("Y", $rows["date"])>=2008?$rows["FinalSum"]:"-")?></td>-->
			<td class=tboard><?=($rows["resultsum"]>0&&!$rows["ParentOrder"]? $rows["resultsum"]:"-")?></td>
			
			<td class=tboard><a name=order<?=$rows["order"]?> id=order<?=$rows["order"]?>></a>			
			<?if($rows["Reminder"]==3){?>
			<b>Получен</b>						
            <?} else {?><a href=#order<?=$rows["order"]?> onclick="ShowFormPhonePostReceipt('<?=$rows["order"]?>')">Сообщить</a>  
            <?  echo contentHelper::render('shopcoins/items/sendresponse',$rows);    
            }?>
            </div>
			<?=($rows["ReminderComment"]?"<br>".$rows["ReminderComment"]:"")?>			
			</td>
			<td class=tboard align=center>
			<?if ($rows["ParentOrder"]==0 && ($rows["payment"]==3 || $rows["payment"]==4 || $rows["payment"]==5 || $rows["payment"]==6) ) {
				echo ($rows["ReceiptMoney"]>0?date("y-m-d",$rows["ReceiptMoney"]):"<b><font color=red>НЕТ</font></b>");
			} else {
				echo "-";
			}?>
			</td>
			<td class=tboard>
			<!--<form action='' method=post target=_blank name=ttform>
			<input type=hidden name=order value='<?=$rows["order"]?>'>
			<input type=hidden name=action value='showorderhtml'>
			<input type=submit  class=button26 name=submit value='Отчет' class=formtxt>			
			</form>-->
			<a href="#" class="ohr <?=($l_order==$rows["order"])?"bordered":""?>" onclick="$.cookie('l_order', <?=$rows["order"]?>); window.open('http://www.numizmatik.ru/shopcoins/order.php?action=showorderhtml&order=<?=$rows["order"]?>');">Отчет</a>
			</td>
			</tr>
		<?}?>
		</table><br><br>