<h5>Ваш заказ</h5>
<p>Вы можете еще добавить / удалить товар в корзине, пересчитать сумму заказа и т.д.
<br>Когда Вы захотите оформить заказ нажмите пожайлуста на кнопку <b>Приступить к оформлению заказа</b></p>
<p><b>Доставка</b> осуществляется на сумму <b><font color=red>не менее 500 руб.</font></b> и <b><font color=red>не более <?=$stopsummax?> руб.</font></b> по территории РФ.<br> 
<p>Заказ на сумму менее 500 руб. могут сделать авторизованые пользователи, у которых есть ранее сделанный заказ, но еще не отправленный покупателю. В таком случае новый заказ будет объединен с предыдущим не отправленным.
К стоимости Вашего заказа будет добавлена стоимость почтовых услуг по упаковке, страховке и доставке его Вам, которая зависит от пункта назначения, массы и стоимости товара. </p>

<br><center><a href='<?=$cfg['site_dir']?>/shopcoins'>В начало магазина</a></center><br>
<table border=0 cellpadding=3 cellspacing=1 align=center width=90%>
<tr bgcolor=#ffcc66>
<td class=tboard><b>Изображение </b></td>
<td class=tboard><b>Название</b></td>
<td class=tboard><b>Группа (страны)</b></td>
<td class=tboard><b>Год</b></td>
<td class=tboard><b>Номер</b></td>
<td class=tboard><b>Цена</b></td>
<td class=tboard><b>Кол - во</b></td>
<td class=tboard>&nbsp;</td></tr>
<form action=<?=$cfg['site_dir']?>/shopcoins?page=orderdetails method=post class=formtxt>
<?
$i=0;
foreach ($tpl['orderdetails']['ArrayShopcoinsInOrder'] as 	$rows ){	
	if ($rows["title_materialtype"]) {?>
		<tr bgcolor=#EBE4D4 valign=top><td colspan=8 class=tboard bgcolor=#99CCFF><b><?=$rows["title_materialtype"]?></b></td></tr>		
	<?} /*?>
	*/?>
	
	<tr bgcolor=#EBE4D4 valign=top>
	   <td class=tboard id=image<?=$rows['catalog']?>>
	       <div id=show<?=$rows['catalog']?>></div>
	       <?
	       //$rows['image_big']
	       echo contentHelper::showImage("smallimages/".$rows["image_small"],$rows["gname"]." | ".$rows["name"],array('onMouseover'=>"ShowMainCoins(\"{$rows['catalog']}\");","onMouseout"=>"NotShowMainCoina(\"{$rows['catalog']}\");"));
	       if ($rows["gname"]){?>
        <?=in_array($rows["materialtype"],array(9,3,5))?"Группа":"Страна"?>: <?=$rows["gname"]?><br>
        <?}?>
        Название: <strong><?=$rows["name"]?></strong><br>
        Номер: <strong><?=$rows["number"]?></strong><br>
        
        <?=($rows["year"]?"Год: <strong>".$rows["year"]."</strong><br>":"")?>
        <?=(isset($rows["metal"])?"Металл: <strong>".$rows["metal"]."</strong><br>":"")?>
        <?=($rows["condition"]?"Состояние: <strong><font color=blue>".$rows["condition"]."</font></strong><br>":"")?>
        <? $price_text = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"Цена":"Стоимость";?>
        <?= $price_text?>: <strong><font color=red><?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?></font></strong>
        <?= ($rows["width"]&&$rows["height"]?"<br>Приблизительный размер: <strong>".$rows["width"]."*".$rows["height"]." мм.</strong><br>":"")."
        ".($rows["weight"]>0?"<br>Вес: <strong>".$rows["weight"]." гр.</strong><br>":"")."
        ".(isset($rows["series"])&&$rows["series"]&&$group?"<br>Серия монет: ".$series_name[$rows["series"]]."":"")."
        ".($rows["materialtype"]==8&&$materialtype==1&&!$mycoins?"<br><font color=red>МОНЕТА С РАЗДЕЛА МЕЛОЧЬ, см. условия покупки в разделе</font>":"")."
        ".($rows["accessoryProducer"]?"<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
        ".($rows["accessoryColors"]?"<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>":"")."
        ".($rows["accessorySize"]?"<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>":"");
        
        if ($rows["details"]){
        	echo "<br>Описание: ".$rows["details"];
        }?>
	   </td>
	   <td class=tboard>
	       <a href="<?=$cfg['site_dir']?>/shopcoins?catalog=<?=$rows["catalog"]?>&page=show&materialtype=<?=$rows["materialtype"]?>" target=_blank><?=$rows["name"]?></a>
	   </td>
	   <td class=tboard>
	       <a href="<?=$cfg['site_dir']?>/shopcoins?group=<?=$rows["group"]?>&materialtype=<?=$rows["materialtype"]?>">
	       <?=$rows["gname"]?>
	       </a
	   </td>
	<td class=tboard><?=$rows["year"]?></td>
	<td class=tboard><?=$rows["number"]?></td>
	<td class=tboard>"<?=($rows["price"]==0)?"бесплатно":round($rows["price"])." руб."?></td>
	<td class=tboard align=center>
	
	<input type=hidden name=shopcoins<?=$i?> value='<?=$rows["catalog"]?>'>
	<!--<input type=hidden name=sqlamount<?=$i?> value='<?=$rows["amount"]?>'>
	<input type="<?=($rows["oamount"]?"text":"hidden")?>" name=amount_<?=$i?> value='<?=$rows["oamount"]?>' size=2 maxlength=5 class=formtxt>
	<?($rows["oamount"]?$rows["oamount"]:"")?>
	-->

<? 

if($rows["amountAll"]>1){?>
<input id="amountall<?=$rows["catalog"]?>" type="hidden" value="<?=$rows["amountAll"]?>">
<span class="down">-</span>
<input id="amount<?=$rows["catalog"]?>" type="text" value="<?=$rows["oamount"]?>" size="1" name="amount_<?=$i?>">
<span class="up">+</span>
<?} else {?>
	<?=$rows["oamount"]?>
<?}?>

<br>
	</td>
	<td class=tboard width=25><a href=<?=$cfg['site_dir']?>/shopcoins?page=orderdetails&pageinfo=delete&shopcoins=<?=$rows["catalog"]?> title='Удалить из корзины'>Удалить</a></td>
	</tr>
	<?
	$i++;
}?>
</table>
<input type=hidden name=amount value='<?=$i?>'>
<div><b>Итого:</b> <?=$sum?> руб.<br>

<input type=submit name=submit value='Пересчитать' class=formtxt>

</form>
<?

if ($sum>$stopsummax) {?>
	<div><input type=button name=submit value='Сумма заказа должна быть менее чем на <?=$stopsummax?> руб.' style="font-weight:bold; COLOR: #FF0000;">
	</div>
<?} elseif ((($sum<500 && ($tpl['user']['orderusernow'] == 0 || $blockend > time())) || $sum<=0) && $tpl['user']['user_id'] != 811) {?>
	<div><input type=button name=submit value='Сумма заказа должна быть более чем на 500 руб.' style="font-weight:bold; COLOR: #FF0000;"><br>
	Заказ на сумму менее 500 руб. могут сделать <strong>авторизованые</strong> пользователи, у которых <strong>есть ранее сделанный заказ</strong>, но еще не отправленный покупателю.</div>
<?} elseif ($sum >= 500 || $tpl['user']['user_id'] == 811) {?>
	<form action=<?=$cfg['site_dir']?>/shopcoins?page=order method=post>
	<input type=hidden name=page2 value=1>
	<div>
	<input type=submit name=submit value='Приступить к оформлению заказа'>
	</form>
	</div>
<?} else {?>
	<form action=<?=$cfg['site_dir']?>/shopcoins?page=order method=post>
	<input type=hidden name=page2 value=2>
	<input type=submit name=submit value='Перейти к добавлению к предыдущему заказу' class=formtxt>
	</div>
<?}?>

<?
if ($tpl['orderdetails']['related']) {?>

<div class="wraper clearfix">
	<h5>СОВЕТУЕМ ОБРАТИТЬ ВНИМАНИЕ ТАКЖЕ НА:</h5>
</div>
<div class="triger">	
	<div class="wraper clearfix" style="height:350px;padding-top:15px;">
		<div>
			<?foreach ($tpl['orderdetails']['related'] as $rowsp){?>
				<div class="coin_info">
					<div id=show<?=$rowsp['shopcoins']?>></div>
				<?	
				$statuses = $shopcoins_class->getBuyStatus($rowsp["shopcoins"],$tpl['user']['can_see'],array(),array());
				$rowsp['buy_status'] = $statuses['buy_status'];
				$rowsp['reserved_status'] = $statuses['reserved_status'];	
				$rowsp['mark'] = $shopcoins_class->getMarks($rowsp["shopcoins"]);
				echo contentHelper::render('shopcoins/item/itemmini',$rowsp);
	            ?>				
				</div>
			<?}?>
		</div>
	</div>
</div>

<!--
	<table border=0 cellpadding=3 cellspacing=1 align=center width=180>
	<tr bgcolor=#EBE4D4 valign=top>
	<td class=tboard bgcolor=#99CCFF><b></b></td></tr>";
	
	 foreach ($tpl['orderdetails']['related'] as $rows) {?>
		<tr bgcolor=#EBE4D4 valign=top onMouseover=\"javascript:ShowMainCoins('".$rows['shopcoins']."','<img border=1 bordercolor=black src=images/".$rows['image_big'].">');\" onMouseout=\"javascript:NotShowMainCoina('".$rows['shopcoins']."');\">
		<td class=tboard id=image".$rows['shopcoins']."><div id=show".$rows['shopcoins']."></div><img src=smallimages/".$rows["image_small"]." border=1 alt='".$rows["gname"]." | ".$rows["name"]."' width=80 align=left >
		<a href=index.php?catalog=".$rows["shopcoins"]."&page=show&materialtype=".$rows["materialtype"].">".$rows["name"]."</a><br>
		".$rows["gname"]."<br>
		<font color=red><b>";
		if ($rows["price"]==0)
			$RelationText .= "бесплатно";
		else
			$RelationText .= round($rows["price"],2)." руб.";
		$RelationText .= "</b></font>
		</td></tr><tr bgcolor=#EBE4D4 ><td class=tboard>";
		
		if ($rows["materialtype"]==3)
		{
			if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
				$RelationText .= "
				<input type=text id=amount".$rows["shopcoins"]." name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
				<a href='#' onclick='javascript:AddAccessory(".$rows["shopcoins"].")'><div id=bascetshopcoins".$rows["shopcoins"].">
				<img src=".$in."images/corz7.gif border=0 alt='Уже в корзине'></div>
				</a>";
			else
			{
				if ($REMOTE_ADDR!="213.180.194.162" 
				and $REMOTE_ADDR!="213.180.194.133" 
				and $REMOTE_ADDR!="213.180.194.164" 
				and $REMOTE_ADDR!="213.180.210.2" 
				and $REMOTE_ADDR!="83.149.237.18"
				and $REMOTE_ADDR!="83.237.234.171"
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"ia_archiver")
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"coona")
				)
				{
					$RelationText .= "
					<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='0'> 
					<a href='#' onclick='javascript:AddAccessory(".$rows["shopcoins"].");'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='В корзину'></div></a>
					
					";
				}
			}
		}
		elseif ($rows["materialtype"]==1||$rows["materialtype"]==2|| $rows['materialtype']==8|| $rows['materialtype']==4|| $rows['materialtype']==7|| $rows['materialtype']==9|| $rows['materialtype']==10)
		{
			
			if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
				$RelationText .=  "<img src=".$in."images/corz7.gif border=0 alt='Уже в корзине'>";
			elseif (sizeof($CoinsNow) and in_array($rows["shopcoins"], $CoinsNow))
				$RelationText .=  "<img src=".$in."images/corz6.gif border=0 alt='Покупает другой посетитель'><br><font color=gray size=-2>Бронь до ".date("H:i", $rows["reserve"]+$reservetime)."</font>";
			else
			{
				if ($REMOTE_ADDR!="213.180.194.162" 
				and $REMOTE_ADDR!="213.180.194.133" 
				and $REMOTE_ADDR!="213.180.194.164" 
				and $REMOTE_ADDR!="213.180.210.2" 
				and $REMOTE_ADDR!="83.149.237.18"
				and $REMOTE_ADDR!="83.237.234.171"
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"ia_archiver")
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"coona")
				)
					
					if ($rows["materialtype"]==1|| $rows['materialtype']==9|| $rows['materialtype']==10)
						$RelationText .=  "<div id=bascetshopcoins".$rows["shopcoins"]." ><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddBascet('".$rows["shopcoins"]."','1');\" rel=\"nofollow\"><img src=".$in."images/corz1.gif border=0 alt='В корзину'></a></div>";
					else
						$RelationText .=  "<div id=bascetshopcoin".($i+1)."><div id=bascetshopcoins".$rows["shopcoins"]." ><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddBascetTwo('".$rows["shopcoins"]."','1','".($i+1)."');\" rel=\"nofollow\"><img src=".$in."images/corz1.gif border=0 alt='В корзину'></a></div></div>";
			}
			
		}
	
		$RelationText .= "</td>
		</tr>";
		$i++;
	}
	$RelationText .= "</table>";
	
	//echo $RelationText;
}-->
<?}?>