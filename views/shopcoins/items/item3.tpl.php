<a name='coin<?=$rows["shopcoins"]?>' id='coin<?=$rows["shopcoins"]?>' title='<?=$rows["name"]?>'></a>
		<strong><?=$namecoins?></strong>		
		<a href='<?=$rehref?>' title='<?=$namecoins?>'>
		  <img src="images/<?=$rows["image"]?>" alt='<?=$namecoins?>'>
		</a>
		<?		
		if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder)){?>			
			<input type=text name=amount<?=$rows["shopcoins"]?> size=4 class=formtxt value='<?=$ourcoinsorderamount[$rows["shopcoins"]]?>'> 
			<a href='#coin<?=$rows["shopcoins"]?>' onclick='javascript:AddAccessory_main(<?=$rows["shopcoins"]?>,<?=$rows["materialtype"]?>)'>
			<div id=bascetshopcoins<?=$rows["shopcoins"]?>>
			<img src=<?=$cfg['site_dir']?>/images/corz7.gif border=0 alt='Уже есть в вашей корзине'></div>
			</a>
		<? 
		}
		  //подробнее - возможно сейчас не надо.
		if (strlen($rows["details"])>250 or $rows["image_big"]) {?>		  
			<a href='<?=$rehref?>' title="Подробнее о <?=$rows['name']?>"><img src=<?=$cfg['site_dir']?>images/corz3.gif border=0 alt='Подробнее о <?=$rows['name']?>'></a>
		<?}
				
		echo $textoneclick;	
		?>
		<br>
		Количество:  <strong><?=!$rows["amount"]?1:$rows["amount"]?></strong><br>
		Группа:  <a href=$script?group=<?=$rows['group']?>&materialtype=<?=$rows['materialtype']?> title='Посмотреть все <?=$rows["gname"]?>'><strong><font color=blue><?=$rows["gname"]?></font></strong></a><br>
		Название: <strong><?=$rows["name"]?></strong>
		<?=($rows["number"]?"<br>Номер:<strong> ".$rows["number"]."</strong>":"")?>
		<?=($rows["accessoryProducer"]?"<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>":"")?>
		<?=($rows["accessoryColors"]?"<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>":"")?>
		<?=($rows["accessorySize"]?"<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>":"")?><br>
		<?=($rows["oldprice"]>0?"Старая цена: <strong><s>".round($rows["oldprice"],2)." руб.</s></strong>
		<br>Новая цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>":
		"Цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >Цена<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." руб.</font></strong></a>":""))?>
		<?
		if ($rows['price1'] && $rows['amount1']) {		
			$tmpbody1 = "Оптовая цена:<br>
			Кол-во:".$rows['amount1']; "";
			$tmpbody2 = "Цена:".$rows['price1'];
			if ($rows['price2'] && $rows['amount2']) {			
				$tmpbody1 .= $rows['amount2'];
				$tmpbody2 .= $rows['price2'];
			}
			if ($rows['price3'] && $rows['amount3']) {
			
				$tmpbody1 .= $rows['amount3'];
				$tmpbody2 .= $rows['price3'];
			}
			if ($rows['price4'] && $rows['amount4']) {
			
				$tmpbody1 .= $rows['amount4'];
				$tmpbody2 .= $rows['price4'];
			}
			if ($rows['price5'] && $rows['amount5']) {
			
				$tmpbody1 .= $rows['amount5'];
				$tmpbody2 .= $rows['price5'];
			}
			echo $tmpbody1."<br>".$tmpbody2; 
		}

		if (trim($rows["details"]))
		{
			$text = substr($rows["details"], 0, 250);
			$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
			$text = str_replace($rows['name'],"<strong>".$rows['name']."</strong>",$text);
			$text = str_replace($rows['gname'],"<strong>".$rows['gname']."</strong>",$text);
			$text = str_replace(" монет ","<strong> монет </strong>",$text);
			$text = str_replace(" монета ","<strong> монета </strong>",$text);
			$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
			$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
			echo "<br>Описание: ".str_replace("\n","<br>",$text);
		}?>