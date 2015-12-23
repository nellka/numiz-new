<?
//показываем количество страниц

if($tpl['show']['error']['no_coins']){?>
    <div class="error">К сожалению, указанного товара не существует</div>
<?} else {
    include($cfg['path'].'/views/shopcoins/item/item.tpl.php');   
}

 
if ($tpl['show']['rowscicle']) {?>	
		<p class=txt> <strong>Похожие позиции в магазине:</strong><br>
		<a href="./<?=$tpl['show']['rowscicle']['reff1']?>" title='<?=$tpl['show']['rowscicle']['title1']?>'><?=$tpl['show']['rowscicle']['title1']?>"</a> <br>
		<a href='./<?=$tpl['show']['rowscicle']['reff2']?>' title='<?=$tpl['show']['rowscicle']['title2']?>'><?=$tpl['show']['rowscicle']['title2']?></a> <br>
		<a href='./<?=$tpl['show']['rowscicle']['reff3']?>' title='<?=$tpl['show']['rowscicle']['title3']?>'><?=$tpl['show']['rowscicle']['title3']?></a>   
		</p>	
<?}	


if(!$tpl['show']['error']['no_coins']){

//сейчас показываем токо для аксессуаров
if ($tpl['shop']['related']){?>
	<table border=0 cellpadding=3 cellspacing=1 align=center width=98%>
	<tr bgcolor=#EBE4D4 valign=top>
	<td colspan=6 class=tboard bgcolor=#99CCFF><b>СОПУТСТВУЮЩИЕ ТОВАРЫ</b></td></tr>
	<tr bgcolor=#ffcc66>
	<td class=tboard><b>Изображение</b></td>
	<td class=tboard><b>Название</b></td>
	<td class=tboard><b>Группа (страны)</b></td>
	<td class=tboard><b>Номер</b></td>
	<td class=tboard><b>Цена</b></td>
	<td class=tboard><b>Заказать</b></td>
	</tr>
	
	<? foreach ($tpl['shop']['related'] as $rows){
		if ($tpl['shop']['related'][$i]['additional_title']) {?>
			<tr bgcolor=#EBE4D4 valign=top><td colspan=6 class=tboard bgcolor=#99CCFF><b><?=$tpl['shop']['related'][$i]['additional_title']?></b></td></tr>
		<?}?>
		
		<tr bgcolor=#EBE4D4 valign=top>
		<td class=tboard><?=contentHelper::showImage("smallimages/".$rows["image_small"],$rows["gname"]." | ".$rows["name"])?></td>
		<td class=tboard><a href=index?catalog=<?=$rows["shopcoins"]."&page=show&materialtype=".$rows["materialtype"]?>&catalog='<?=$rows["shopcoins"]?>'><?=$rows["name"]?></a></td>
		<td class=tboard><a href=index.php?group=<?=$rows["group"]?>&materialtype=<?=$rows["materialtype"]?>><?=$rows["gname"]?></a></td>
		<td class=tboard><?=$rows["number"]?></td>
		<td class=tboard><?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?></td>
		<td class=tboard nowrap> БЛОК КУПИТЬ</td>
		</tr>
	<?}?>
	</table>
<?}

if(isset($tpl['show']['resultcicle'])&&$tpl['show']['resultcicle']){?>
	<br><strong>Похожие позиции в магазине:</strong><br>
	<?foreach ($tpl['show']['resultcicle'] as $rowsp){?>
		<a href=index.php?page=show&group=<?=$rowsp['group']?>&materialtype=<?=$rowsp['materialtype']?>&catalog=<?=$rowsp['shopcoins']?>>Монета – <?=$rows_main["gname"]?> – <?=$rowsp['name'].($rowsp['metal']? " – ".$rowsp['metal'] : '')." – ".$rowsp['year']?> год</a><br>
	
	<?}?>
<?}
		
if ($tpl['shop']['resultp']) {	?>
	<h5>ВМЕСТЕ С ЭТИМ ТОВАРОМ ПРОСМАТРИВАЛИ ТАКЖЕ:</h5>
	<form action=# method=post>
	
	<?$kn = 100;
	$sumseecoins = 0;
	foreach ($tpl['shop']['resultp']  as $rowsp ){
		if(isset($rowsp["shopcoins"])){?>
			<?=($kn>100?"<font size=+3>+</font>":"")?>
			
			<div id=imagem<?=$kn?>><div id=lastcatalogis<?=$rowsp["shopcoins"]?>> 
			<input type=checkbox id=shopcoinslast<?=$kn?> name=shopcoinslast<?=$kn?> checked=checked value=<?=$rowsp["shopcoins"]?> onclick="ChangeSumSeeCoins('<?=$rowsp['price']?>',<?=$kn?>)">
			</div>
			<div id=showm<?=$kn?>></div>
			<?=contentHelper::showImage("smallimages/".$rowsp['image_small'],"Монета ".$rowsp['name']." ".$rowsp['gname']." стоимость ".intval($rowsp['price'])." р.")?>
			<a href=index.php?page=show&group=<?=$rowsp['group']?>&materialtype=<?=$rowsp['materialtype']?>&catalog=<?=$rowsp['shopcoins']?> title='Монета <?=$rowsp['name']?> <?=$rowsp['pgroup']?> цена <?=intval($rowsp['price'])?> р. найти'><?=$rowsp['gname']?><br>
			<?=$rowsp['name']?><br><font color=red><?=intval($rowsp['price'])?> р.</font></a>
			<?
			$kn++;
			$sumseecoins += $rowsp['price'];
		}	
	}?>
	<input type=hidden value='<?=$sumseecoins?>' id='sumseecoins_val' name='sumseecoins_val'>
	<font size=+3>=</font><font size=+3><div id=sumseecoins><?=$sumseecoins?></div> руб.</font>
	<div id='bascetshopcoins0'><img src=../images/corz1.gif border=0 onclick="AddBascetLast2(<?=$kn?>);" title="Положить все отмеченные монеты из списка в корзину"></div>
	</form>
<?}

	
if( $tpl['shop']['result_show_relation2']) {	?>
	<b>ПОДОБНЫЕ ПОЗИЦИИ В МАГАЗИНЕ:</b>
<?
	foreach ($tpl['shop']['result_show_relation2'] as $rows_show_relation2){?>	
			
		<div id=show<?=$rows_show_relation2['shopcoins']?>></div>
		<?

		echo contentHelper::showImage("smallimages/".$rows_show_relation2["image_small"],$rows_show_relation2["gname"]." | ".$rows_show_relation2["name"]);
		echo "<a href=index.php?catalog=".$rows_show_relation2["shopcoins"]."&page=show&materialtype=".$rows_show_relation2["materialtype"].">".$rows_show_relation2["name"]."</a>";
		echo $rows_show_relation2["gname"];
		echo "<font color=red><b>".($rows_show_relation2["price"]==0)?"бесплатно":round($rows_show_relation2["price"],2)." руб.";
		echo "</b></font><br>";
		
		$statuses = $shopcoins_class->getBuyStatus($rows_show_relation2["price"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
		$rows_show_relation2['buy_status'] = $statuses['buy_status'];
		$rows_show_relation2['reserved_status'] = $statuses['reserved_status'];	
		echo contentHelper::render('shopcoins/price/buy_button',$rows_show_relation2);	
	}?>		
<?}


if ($tpl['shop']['result_show_relation3']) {
	
	$RelationText = "
	<table border=0 cellpadding=3 cellspacing=1 align=center width=98%>
	<tr bgcolor=#EBE4D4 valign=top>
	<td colspan=2 class=tboard bgcolor=#99CCFF><b>Тот кто смотрел товар потом купил:</b></td></tr>";
	$k = 0;
	$oldmaterialtype = 0;
	foreach ($tpl['shop']['result_show_relation3'] as $rows_show_relation2){				
		if ($k%2==0)
			$RelationText .= "<tr bgcolor=#EBE4D4 valign=top>
		<td class=tboard width=50%><div id=show".$rows_show_relation2['shopcoins']."></div>";
		else
			$RelationText .= "<td class=tboard width=50%><div id=show".$rows_show_relation2['shopcoins']."></div>";
			
		$RelationText .= "<img src=smallimages/".$rows_show_relation2["image_small"]." border=1 alt='".$rows_show_relation2["gname"]." | ".$rows_show_relation2["name"]."' width=80 align=left ".($materialtype==1||$materialtype==2 || $materialtype==4 || $materialtype==8||$materialtype==6?"onMouseover=\"javascript:ShowLot('".$rows_show_relation2['shopcoins']."','".($i-1)."','<img border=1 bordercolor=black src=images/".$rows_show_relation2['image_big'].">');\" onMouseout=\"javascript:NotShowLot('".$rows_show_relation2['shopcoins']."');\"":"").">
		<a href=index.php?catalog=".$rows_show_relation2["shopcoins"]."&page=show&materialtype=".$rows_show_relation2["materialtype"].">".$rows_show_relation2["name"]."</a><br>
		".$rows_show_relation2["gname"]."<br>
		<font color=red><b>";
		if ($rows_show_relation2["price"]==0)
			$RelationText .= "бесплатно";
		else
			$RelationText .= round($rows_show_relation2["price"],2)." руб.";
		$RelationText .= "</b></font><br> 
		";
		
		if ($rows_show_relation2["materialtype"]==3)
		{
			if (sizeof($ourcoinsorder) and in_array($rows_show_relation2["shopcoins"], $ourcoinsorder))
				$RelationText .= "
				<input type=text id=amount".$rows_show_relation2["shopcoins"]." name=amount".$rows_show_relation2["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows_show_relation2["shopcoins"]]."'> 
				<a href='#' onclick='javascript:AddAccessory(".$rows_show_relation2["shopcoins"].")'><div id=bascetshopcoins".$rows_show_relation2["shopcoins"].">
				<img src=".$cfg['site_dir']."images/corz7.gif border=0 alt='Уже в корзине'></div>
				</a>";
			else
			{
				if ($tpl['user']['can_see'])
				{
					$RelationText .= "
					<input type=text name=amount".$rows_show_relation2["shopcoins"]." id=amount".$rows_show_relation2["shopcoins"]." size=4 class=formtxt value='0'> 
					<a href='#' onclick='javascript:AddAccessory(".$rows_show_relation2["shopcoins"].");'><div id=bascetshopcoins".$rows_show_relation2["shopcoins"]."><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину'></div></a>
					
					";
				}
			}
		}
		elseif ($rows_show_relation2["materialtype"]==1||$rows_show_relation2["materialtype"]==2|| $rows_show_relation2['materialtype']==8||$rows_show_relation2['materialtype']==6|| $rows_show_relation2['materialtype']==4|| $rows_show_relation2['materialtype']==7|| $rows_show_relation2['materialtype']==9|| $rows_show_relation2['materialtype']==10)
		{
			
			if (sizeof($ourcoinsorder) and in_array($rows_show_relation2["shopcoins"], $ourcoinsorder))
				$RelationText .=  "<img src=".$cfg['site_dir']."images/corz7.gif border=0 alt='Уже в корзине'>";
			elseif (sizeof($CoinsNow) and in_array($rows_show_relation2["shopcoins"], $CoinsNow))
				$RelationText .=  "<img src=".$cfg['site_dir']."images/corz6.gif border=0 alt='Покупает другой посетитель'><br><font color=gray size=-2>Бронь до ".date("H:i", $rows_show_relation2["reserve"]+$reservetime)."</font>";
			else
			{
				if ($tpl['user']['can_see'])
					
					if ($rows_show_relation2["materialtype"]==1|| $rows_show_relation2['materialtype']==9|| $rows_show_relation2['materialtype']==10)
						$RelationText .=  "<div id=bascetshopcoins".$rows_show_relation2["shopcoins"]." ><a href='#coin".$rows_show_relation2["shopcoins"]."' onclick=\"javascript:AddBascet('".$rows_show_relation2["shopcoins"]."','1');\" rel=\"nofollow\"><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину'></a></div>";
					else
						$RelationText .=  "<div id=bascetshopcoin".($i+1)."><div id=bascetshopcoins".$rows_show_relation2["shopcoins"]." ><a href='#coin".$rows_show_relation2["shopcoins"]."' onclick=\"javascript:AddBascetTwo('".$rows_show_relation2["shopcoins"]."','1','".($i+1)."');\" rel=\"nofollow\"><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину'></a></div></div>";
			}
			
		}
		
		if ($k%2==0)
			$RelationText .= "</td>";
		else
			$RelationText .= "</td>
		</tr>";
		$i++;
		$k++;
	}
	if ($k%2)
		$RelationText .= "</td><td>&nbsp;</td>
		</tr>";
	$RelationText .= "</table>";

	echo $RelationText;
}


echo "</center><br>";
?>
	
	<div id=reviewcoindiv>
<?	if($tpl['show']['described'])	{ ?>			
<form action=/detailcoins/addcomment.php name=mainform id="send_descr" method=post>
		<h5>Опишите монету и получите 1 рубль на бонус-счет</b></h5>
		<input type=hidden id=coins name=coin value="<?=$catalog?>">		
		<b>Страна: </b><input type=text class=formtxt id="group2" name="group" required size=40 onfocus="AddGroup2();"><br>
		<b>Номинал: </b><input class=formtxt id="name2" name="name" type=text required size=40 onfocus="AddNominal();">  <br>
		<a name=year></a><b>Год: </b><input class=formtxt id="year2" name="year" required size=4/> <br>
		<b>Металл: </b>
		<select name=metal id="metal2" class=formtxt >
		  <option value=0>Выберите</option>
		  <option value=1>Алюминий</option>
		  <option value=2>Биллон</option>
		  <option value=3>Биметалл</option>
		  <option value=4>Железо</option>
		  <option value=5>Золото</option>
		  <option value=6>Керамика</option>
		  <option value=7>Медно-никель</option>
		  <option value=8>Медь</option>
		  <option value=9>Позолота</option>
		  <option value=10>Посеребрение</option>
		  <option value=11>Серебро</option>
		  <option value=12>Титан</option>
		  <option value=13>Фарфор</option>
		  <option value=14>Цинк</option>
		  <option value=15>Неопределено</option>
		  </select>
		<br>
		<a name=metal></a><b>Состояние: </b>
		<select name=condition id=condition2 class=formtxt >
		  <option value=0>Выберите</option>
		  <option value=1>VF</option>
		  <option value=2>XF</option>
		  <option value=3>UNC-</option>
		  <option value=4>UNC</option>
		  <option value=5>Proof-</option>
		  <option value=6>Proof</option>
		 </select>
		<br>
		<a name=details></a><b>Описание монеты:</b>
		<textarea name=details id=details2 class=formtxt cols=60 rows=6></textarea> 
		<input type=submit name=submit onclick="CheckSubmitPrice(0);" value="Сохранить описание" class=formtxt >		
</form>
<?}

//отзывы

if ($tpl['user']['user_id']) {   ?> 
    <br><a name=addreview></a>
    <form name=reviewcoin>
    <h5>Написать отзыв:</h5>
    <div id='error-review' class="error"></div>
    <div><b>Пользователь: <input type=text name=fio value='<?=$tpl['user']['username']?>' size=40 maxlength=150 disabled></b></div>
    <div><b>Отзыв:</b><br>
    <textarea name=reviewcointext id=reviewcointext cols=60 rows=5 ></textarea></div>
    <div><input type=button value='Оставить отзыв' onclick="AddReview('<?=$catalog?>');"></div>
    </form>
<?}?>
    <div id=allreviews>
    	<a name=showreview></a>
    	<h5>Оставленные отзывы:</h4>
    	<div id=reviewsdiv>
    	<? if (sizeof($tpl['show']['reviews']['reviewusers'])) {
    	    foreach ($tpl['show']['reviews']['reviewusers'] as $key=>$value) {     
    	        ?>
    	   <div id='review<?=(isset($value["catalog"])&&$value["catalog"])?$value["catalog"]:$value["shopcoins"]?>'>
        	   <b><?=date('d-m-Y',$value['dateinsert'])?> <?=$value['fio']?></b>
        	   <?=$value['review']?>
    	   </div>
    	<?}
    	} else {
    	    echo "<div id=emptyreview class=error>Отзывы отсутствуют</div>";
    	}?>
    	</div>
	</div>
<?
}?>