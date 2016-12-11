<h1>Мои заказы</h1>
<b>Добрый день!</b>
<br>Вы авторизовались под логином <?=$tpl['user']['username']?>. 
<br>Это все Ваши заказы за 1 год, которые были сделаны под этим именем. Если Вы считаете, 
что их должно быть больше - возможно они были сделаны под другим Вашим логином.
<br>В любом случае можете обратиться к администратору сайта по электронной почте
<a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> или по
телефону <b>8-800-333-14-77 (бесплатный звонок по России) </b>.
		
<br><br>

<? foreach ($tpl['orders'] as $rows){?>
<div id='orde-block'>
	<b>Номер заказа: <?=($rows["ParentOrder"]>0?"<img src='".$cfg['site_dir']."shopcoins/images/folderfor.gif>'":"").$rows["order"].($tpl['user']['user_id'] == 811?"<br>".$rows['userfio']:"")?></b><br><br>
	<b>Дата заказа: </b><?=date("y-m-d", $rows["date"])?><br>
	<b>Отправка: </b><?=($rows["SendPost"]?date("y-m-d", $rows["SendPost"]):"-")?><br>
	<b>Оплата: </b><?=$SumName[$rows["payment"]];?><br>
	<?if ($rows["payment"]==6) {?>
		<br><a href='kak_oplatit_kartoi_sberbanka.html' target=_blank>Как оплатить заказ картой Сбербанка</a>
	<?}
							
	if ($rows["payment"] == 6 && !$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"]){?>
		[ <a href='sbrf.php?NUMBER=<?=$rows["order"]?>&FIO=<?=urlencode($rows["userfio"])?>&ADRESS=<?=urlencode($rows["adress"])?>&SUM=<?=($rows["FinalSum"]-$row['dissert'])?>' target='_blank'>Распечатать квитанцию</a> ]
	<?}?>

				
	<form action='<?=$urlrobokassa?>/Index.aspx' method=POST>
       <input type=hidden name=MrchLogin value='numizmatikru'>
       <input id=OutSum<?=$rows['order']?> type=hidden name=OutSum value='<?=$rows['OutSum']?>'>
       <input type=hidden name=InvId value='<?=$rows['order']?>'>
       <input type=hidden name=Desc value='Оплата предметов нумизматики'>
       <input id=SignatureValue".$rows['order']." type=hidden name=SignatureValue value='<?=$rows['crcode']?>'>
       <input type=hidden name=Shp_idu value='<?=$tpl['user']['user_id']?>'>
       <input type=hidden name=IncCurrLabel value='BANKOCEAN2R'>
       <input type=hidden name=Culture value='ru'>
       <div>
       <input class=button25 type=submit value='Оплатить'>
       <img src="http://numizmatik1.ru/new/images/mobile/visa.jpg">
       <img src="http://numizmatik1.ru/new/images/mobile/mastercard.jpg">
        - <div id="info<?=$rows['order']?>" class="right" style="line-height: 30px;"><font color="Red"><?=$rows['OutSum']?> руб.</font></div> 
       </div>
       <div style="color:grey">* При оплате банковскими картами комиссия 4%</div>
    </form>

        <b>Номер посылки:</b>
	    <?
			if($rows["SendPostBanderoleNumber"]){?>
			   <?= $rows["SendPostBanderoleNumber"]?><br><center>[ <form action="http://www.russianpost.ru/resp_engine.aspx?Path=rp/servise/ru/home/postuslug/trackingpo" method=post target=_blank>}
			<input type=hidden name=searchsign value=1>
			<input type=hidden name=BarCode value=<?=$rows["SendPostBanderoleNumber"]?>>
			<input type=submit name=submit value=Отследить class=formtxt ></form> ]</center>
            <?} else {?>
            -
            <?}?>
           <BR>
			<?=($rows["weight"]>0?"Вес: ".$rows["weight"]." кг.<br>":"")?>
			<b>Сумма:</b> <?=round($rows["sum"])?><br>
			<b>К оплате:</b> <?=($rows["FinalSum"]>0 && date("Y", $rows["date"])>=2008?$rows["FinalSum"]:"-")?><br>
			<b>Получение заказа: </b><a name=order<?=$rows["order"]?> id=order<?=$rows["order"]?>></a>	
			<?if($rows["Reminder"]==3){?>
			     <b>Получен</b>						
            <?} else {?><a href=#order<?=$rows["order"]?> onclick="ShowFormPhonePostReceipt('<?=$rows["order"]?>')">Сообщить</a>  
                <?  echo contentHelper::render('_mobile/shopcoins/items/sendresponse',$rows);    
            }?><br>

			<?=($rows["ReminderComment"]?"<b>Комментарий:</b> ".$rows["ReminderComment"]."<br>":"")?>			
			<b>Предоплата получена: </b>
			<?if ($rows["ParentOrder"]==0 && ($rows["payment"]==3 || $rows["payment"]==4 || $rows["payment"]==5 || $rows["payment"]==6) ) {
				echo ($rows["ReceiptMoney"]>0?date("y-m-d",$rows["ReceiptMoney"]):"<b><font color=red>НЕТ</font></b>");
			} else {
				echo "-";
			}?>
			<br>
			
			<form action='' method=post target=_blank name=ttform>
			<input type=hidden name=order value='<?=$rows["order"]?>'>
			<input type=hidden name=action value='showorderhtml'>
			<input type=submit  class=button26 name=submit value='Отчет' class=formtxt>			
			</form>
			
</div>
<hr>
<?}?>
<br><br>