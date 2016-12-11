<div class="mainItemPhoto">
    <?
    $image = (trim($rows_main["image_big_url"])?$rows_main["image_big_url"]:$rows_main["image_small_url"]);
    ?>
        
    <div id=Image_Big class="main_img_big  center">
    	<?=contentHelper::showImage($image,"Каталог монет - ".$rows_main["gname"]." - ".$rows_main["name"],array('alt'=>"Каталог монет - ".$rows_main["gname"]." ".$rows_main["name"],'folder'=>'catalognew'))?>	
    </div>	

</div>

<div  class="detailsItem">
        <h1><?=$rows_main["name"]?>
        <?if ($rows_main["agreement"]==0)  echo "<span class=red style='font-size: 14px;'>Эта монета Непроверена</span>";?>
	
        </h1>       
     <?
     if($tpl['catalognew']['show']['can_edit']){
     	echo '<div class=right style="margin-top: -35px;text-align: right;">';
		echo "<a href='".$cfg['site_dir']."catalognew/addcoins.php?catalog=".$catalog."' target=_blank><img src='".$cfg['site_dir']."catalognew/images/edit.gif' border=0 class='left'>&nbsp;&nbsp;Редактировать</a>";
		echo '</div>';
	}
	
	if ($rows_main["agreement"]==0&&$tpl['catalognew']['show']['rows_agreement_catalog'])	{?>
		
		<b>Монету добавил пользователь:</b> <br>
		  <a href=# onclick="StartUserInfo('<?=$tpl['catalognew']['show']['rows_agreement_catalog']["user"]?>');">
		      <?=$tpl['catalognew']['show']['rows_agreement_catalog']["userlogin"]?></a> 
		      <?=($tpl['catalognew']['show']['rows_agreement_catalog']["star"]<10&&$tpl['catalognew']['show']['rows_agreement_catalog']["star"]>0?"<img src=".$cfg['site_dir']."images/star".$tpl['catalognew']['show']['rows_agreement_catalog']["star"].".gif alt='Рейтbнг пользователя ".$tpl['catalognew']['show']['rows_agreement_catalog']["star"]."'>":"<img src=".$cfg['site_dir']."images/star10.gif alt='Рейтинг пользователя ".$tpl['catalognew']['show']['rows_agreement_catalog']["star"]."'>")." (".$tpl['catalognew']['show']['rows_agreement_catalog']["star"].")";?>
		<br>
		<div class="bordered">
		<form action=<?=$cfg['site_dir']?>/new/?module=catalognew&task=show&catalog=<?=$catalog?> method=post style="margin: 0;">
		<input type=hidden name=catalog value='<?=$catalog?>'>
		<b>Принять монету в каталог: </b>
		<input type=Radio name='answercataloghistory[<?=$tpl['catalognew']['show']['rows_agreement_catalog']["cataloghistory"]?>]' <?=(isset(${"answercataloghistory"}[$tpl['catalognew']['show']['rows_agreement_catalog']["cataloghistory"]])&&${"answercataloghistory"}[$tpl['catalognew']['show']['rows_agreement_catalog']["cataloghistory"]]==2?"checked":"")?> value=2> Нет
		<input type=Radio name='answercataloghistory[<?=$tpl['catalognew']['show']['rows_agreement_catalog']["cataloghistory"]?>]' <?=(isset(${"answercataloghistory"}[$tpl['catalognew']['show']['rows_agreement_catalog']["cataloghistory"]])&&${"answercataloghistory"}[$tpl['catalognew']['show']['rows_agreement_catalog']["cataloghistory"]]==1?"checked":"")?> value=1> Да
	    <input type=submit name=submitcataloghistory value='Записать' class=button25>
		</form>
		</div>	
	<?} 
	
	if ($rows_main["gname"]){?>
	   <?=in_array($rows_main["materialtype"],array(3))?"Группа":"Страна"?>: <strong><font color=blue><?=$rows_main["gname"]?></font></strong><br>
	<?}	?>
	  <?=in_array($rows_main["materialtype"],array(1,4))?"Монета":"Название"?>:<strong><?=$rows_main["name"]?></strong><br>
	<?
	
	
	if ($tpl['catalognew']['show']['yearperiod']){?>
		Периоды чеканки: <strong><?=$tpl['catalognew']['show']['yearperiod']?></strong><br>
	<?}

	if(in_array($rows_main["materialtype"],array(1,4))) {
		if (trim($rows_main["metal"])) echo "Металл: <strong>".$rows_main["metal"]."</strong><br>";
		if(trim($rows_main["condition"])) echo "Состояние: <strong><font color=blue>".$ConditionMintArray[$rows_main["condition"]]."</font></strong><br>";
		if (trim($rows_main["probe"])) echo "Проба: <strong>".$rows_main["probe"]."</strong><br>";
		if (trim($rows_main["procent"])) echo "Соотношение металла: <strong".$rows_main["procent"]."</strong><br>";
		if (trim($rows_main["weight"])>0) echo "Вес, гр: <strong>".$rows_main["weight"]."</strong><br>";
		if (trim($rows_main["amount"])>0) echo "Тираж (в тысячах штук): <strong>".$rows_main["amount"]."</strong><br>";
		if (trim($rows_main["condition"])) echo "Состояния при чеканке: <strong>".$ConditionMintArray[$rows_main["condition"]]."</strong><br>";
		if ($rows_main["size"]>0) echo "Приблизительный размер: <strong>".$rows_main["size"]." мм.</strong><br>";
		if ($rows_main["thick"]>0) echo "Толщина в мм: <strong>".$rows_main["thick"]." мм.</strong><br>";
		if (trim($rows_main["herd"])) echo "Тип гурта: </b>".$rows_main["herd"]."</strong><br>";
	}
	// vbhjckfd stop
	
	if (trim($rows_main["accessoryProducer"])) echo "Производитель: <strong>".$rows_main["accessoryProducer"]."</strong><br>";
	if (trim($rows_main["accessoryColors"])) echo "Цвета: <strong>".$rows_main["accessoryColors"]."</strong><br>";
	if (trim($rows_main["accessorySize"])) echo "Размеры: <strong>".$rows_main["accessorySize"]."</strong><br>";
	if (trim($rows_main["averslegend"])) echo "Легенда аверса: <strong>".$rows_main["averslegend"]."</strong><br>";
	if (trim($rows_main["reverselegend"])) echo "Легенда реверса: <strong>".$rows_main["reverselegend"]."</strong><br>";
	if (trim($rows_main["mint"])) echo "Монетные двора: <strong>".$rows_main["mint"]."</strong><br>";
	if (trim($rows_main["designer"])) echo "Дизайнер: <strong>".$rows_main["designer"]."</strong><br>";
	if ($rows_main["officialdate"]>0) echo "Официальная дата выпуска: <strong>".$rows_main["officialdate"]."</strong><br>";
	
	if ($rows_main["theme"]) echo "Тематики: <strong>".$rows_main["theme"]."</strong><br>";
	
	
	if (trim($rows_main["details"])) echo "Описание: <strong>".$rows_main["details"]."</strong><br>";
	if ($rows_main["dateinsert"]) echo "Дата добавления в каталог: <strong>".date("Y-m-d", $rows_main["dateinsert"])."</strong><br>";
	if ($rows_main["sourceurl"]) echo "Источник: <strong><a href=".$rows_main["sourceurl"]." target=_blank>".$rows_main["sourceurl"]."</a>.</strong><br>";
	//if ($rows_main["year"]) $details .= "<b>Год: </b>".$rows_main["year"];
	
	
	?>

	
	<div style="font-size:14px !important;font-weight:bold;margin-right:10px;margin-top:15px;">
	<? echo "<div id=mysubscribecatalog".$rows_main["catalog"]." class='mycatalog'>";
	if ($tpl['catalognew']['show']['is_shopcoinssubbscribe']) {
	    
		echo "<b><font color=silver>Заявка принята</font></b>";
		
		if ($rows_main['materialtype'] == 3) {?>
			 <div class="amount">
                <a href='#coin<?=$rows_main["catalog"]?>' onclick="addSubscribeCatalog(<?=$rows_main["catalog"]?>);" title='При появлении данного типа монеты <?=$rows_main["gname"]?> <?=$rows_main["name"]?> в магазине вам будет отправлено уведомление на email...'>Оставить заявку</a>
            	<span class="down">-</span>
                <input type=text name=amountscribecatalog<?=$rows_main["catalog"]?> id=amountscribecatalog<?=$rows_main["catalog"]?> size=1 value='0'> 
            	<span class="up">+</span>
            </div>	
			<?echo "<br>".date('d-m-Y',$tpl['catalognew']['show']['shopcoinssubbscribedate'])."
			<br> <input class=formtxt type=text name=amountscribecatalog".$rows_main["catalog"]." id=amountscribecatalog".$rows_main["catalog"]." size=3 value=".$shopcoinssubbscribe[$rows_main["catalog"]].">
			<br><a href='#coin".$rows_main["catalog"]."' onclick=\"javascript:AcsessorySubscribe(".$rows_main["catalog"].");\" title='При появлении данного типа монеты ".$rows_main["gname"]." ".$rows_main["name"]." в магазине вам будет отправлено уведомление на email...'>Изменить заявку</a>
			";
		}		
		echo " [ <a href='#coin".$rows_main["catalog"]."' onclick=\"deleteSubscribeCatalog(".$rows_main["catalog"].");\">X</a> ]";		
	} else {
		if ($rows_main['materialtype']!=3) {			
			echo "<a href='#coin".$rows_main["catalog"]."' onclick=\"addSubscribeCatalog(".$rows_main["catalog"].");\" title='При появлении данного типа монеты ".$rows_main["gname"]." ".$rows_main["name"]." в магазине вам будет отправлено уведомление на email...'>Оставить заявку</a>";
		} else {?>
            <div class="amount">
                <a href='#coin<?=$rows_main["catalog"]?>' onclick="addSubscribeCatalog(<?=$rows_main["catalog"]?>);" title='При появлении данного типа монеты <?=$rows_main["gname"]?> <?=$rows_main["name"]?> в магазине вам будет отправлено уведомление на email...'>Оставить заявку</a>
                <span class="down">-</span>
                <input type=text name=amountscribecatalog<?=$rows_main["catalog"]?> id=amountscribecatalog<?=$rows_main["catalog"]?> size=1 value='0'> 
                <span class="up">+</span>
            </div>	
		<?}				
	}
	echo "</div>";
	
	if ($rows_main['materialtype'] != 3) {
	
		echo "<div id=mycatalog".$rows_main["catalog"].">";

		if ($tpl['catalognew']['show']['is_catalognewmycatalog']) {
			echo "<b><font color=silver>В коллекции</font></b>&nbsp;&nbsp;";
			echo "[ <a href='#coin".$rows_main["catalog"]."' onclick=\"deleteMycatalog(".$rows_main["catalog"].");\" title='Удалить ".$rows_main["gname"]." ".$rows_main["name"]." из своей коллекции'>X</a> | <span id=coinchange".$tpl['catalognew']['show']['catalognewmycatalog']."></span><span id=txtcoinchange".$tpl['catalognew']['show']['catalognewmycatalog']."><a href='#coin".$rows_main["catalog"]."' onclick=\"ShowForChange(".$tpl['catalognew']['show']['catalognewmycatalog'].");\" title='Добавить монету ".$rows_main["gname"]." ".$rows_main["name"]." в список для обмена'>Обмен</a></span> ]";
		}	else {
			echo "<a href='#coin".$rows_main["catalog"]."' onclick=\"AddMyCatalog(".$rows_main["catalog"].");\" title='Означает, что у вас есть монета ".$rows_main["gname"]." ".$rows_main["name"]." в коллекции'>В коллекцию</a>";
		}
		echo "</div>";
	}	?>
	
	 <a href=# onclick="showWin('http://www.numizmatik.ru/new/?module=catalognew&task=addreviews&catalog=<?=$catalog?>&ajax=1','450');return false;">Написать отзыв</a>
	<br>
	<a href=# id="txtfriendsLetter" onclick='ShowFriendMail();'>Ссылку другу</a>

	
</div>