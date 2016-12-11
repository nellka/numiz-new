<div class="wraper clearfix showitem" >  
<?
if($tpl['catalognew']['error']['no_coins']){?>
    <div class="error">К сожалению, указанного товара не существует</div>
<?} elseif($tpl['catalognew']['error']['text']){?>
    <div class="error"><?=$tpl['catalognew']['error']['text']?></div>
<?} else {
    include($cfg['path'].'/views/catalognew/item/item.tpl.php');   
}?>
</div>

<? if(!$tpl['catalognew']['error']['no_coins']){?>
<noindex>
<div class="wraper clearfix info_coins">
	<div style="display: inline-block;width:470px;">
		<div id=allreviews style="vertical-align:top;overflow:hidden;max-height:500px;">
			<a name=showreview></a>
			<h5 style="margin-top:0px;">Обсуждение</h5>
			<?if($tpl['catalognew']['subscribe']){?>
			    <span class="red"> Вы подписаны на изменения о данной монете (заявки, обсуждение, цены)</span><br>
			<?} else {?>
			    <a href=# onclick="showWin('<?=$cfg['site_dir']?>catalognew/subscribe.php?catalog=<?=$catalog?>&ajax=1','450');return false;">Подписаться на изменения о данной монете (заявки, обсуждение, цены)</a><br>
        	<?}?>
			<br>
			<div id=reviewsdiv >
			<? 
			if ($tpl['catalognew']['reviews']['reviewusers']) {
				foreach ($tpl['catalognew']['reviews']['reviewusers'] as $rows) {				  
				    ?>
				<div>
					<b> <?=$rows['userlogin']?> <?=$rows["catalogreviewtype"]?"- ".$ReviewTypeArray[$rows["catalogreviewtype"]]:''?></b>
				</div>
			   <div id='review<?=$value["catalog"]?>'>			   
				   <?=$rows["details"]?>
			   </div>
				<div style="text-align:right;margin-bottom:15px;">
					<span style="color:#cccccc;">Добавлено: </span><b> <?=date('d-m-Y',$rows['date'])?></b>
				</div>
			<?}
			} else {
				echo "<div id=emptyreview class=error>Комментариев пока что нет. Может, Вы будете первыми?</div>";
			}?>
				
			</div>
			
		</div>
	</div>
	<div style="display: inline-block;vertical-align:top;width:500px;margin-left:20px;">
	<h5  style="margin-top:0px;">Просмотр пользователей, кто работал над монетой:</h5>
	<? if(count($tpl['catalognew']['show']['userwork'])){
		?>
		<table>
			<?
			foreach ($tpl['catalognew']['show']['userwork'] as $rows_user){	?>
				<tr>
					<td><a href=# onclick="StartUserInfo(<?=$rows_user["user_id"]?>);return false;"><?=$rows_user["userlogin"]?></a></td>
					<td><?=($rows_user["star"]<10&&$rows_user["star"]>0?"<img src='".$cfg['site_dir']."images/star".$rows_user["star"].".gif' alt='Рейитнг пользователя ".$rows_user["star"]."'>":"<img src='".$cfg['site_dir']."images/star10.gif' alt='Рейтинг пользователя ".$rows_user["star"]."'>")?> (<?=$rows_user["star"]?>)</td>
					<td><b><?=$rows_user['action']?></b></td>
				</tr>		
			<?}?>
			</table>
			
		<?} else {
			echo "<div id=emptyreview class=error>С этой монетой пока никто не работал!</div>";
		}?>		
	
	</div>
</div>
</noindex>
<?
}
if ($tpl['catalognew']['show']['result_price'])	{?>
    <div class="wraper clearfix info_coins">
		<table border=0 cellpadding=3 cellspacing=1 align=center width=100%>
		<tr class=txt bgcolor=#ffcc66>
		<td><b>Дата</b></td>
		<td><b>Год</b></td>
		<td><b>Металл</b></td>
		<td><b>Состояние</b></td>
		<td><b>Цена</b></td>
		<td><b>Ссылка</b></td>
		</tr>
		
		<? 
		
		foreach ($tpl['catalognew']['show']['result_price'] as $i=>$rows_price) {
		    $rows_price['metal'] = $tpl['metalls'][$rows_price['metal_id']];
		    $rows_price['condition'] = $tpl['conditions'][$rows_price['condition_id']];		    
		    $rows_price = array_merge($rows_price, contentHelper::getRegHref($rows_price));
		    if(!$tpl['user']['user_id']&&$i>4){?>
		    	<tr class=txt bgcolor=#EBE4D4>
					<td align="center" colspan="6">
					<span class="red">Полный список цен доступен только <b>авторизованным</b> пользователям!</span>
					</td>
				</tr>
		    	<?break;
		    }
			?>

			<tr class=txt bgcolor=#EBE4D4>
			<td><?=date("Y-m-d", $rows_price["dateinsert"])?></td>
			<td><?=$rows_price["year"]?></td>
			<td><?=$rows_price["metal"]?></td>
			<td><?=$rows_price["condition"]?></td>
			<td><?=round($rows_price["price"])?> руб.</td>
			<td><a href="<?=$cfg['site_dir']?>shopcoins/<?=$rows_price["rehref"]?>" target=_blank>Посмотреть</b></td>
			</tr>
		<?}?>
		</table>
    </div>
<?}



if ($tpl['catalognew']['show']['offers']){?>
    <div class="wraper clearfix info_coins">
   
	<form action="<?=$cfg['site_dir']?>catalognew/show.php?catalog=<?=$catalog?>" method=post>
	<table border=0 cellpadding=3 cellspacing=1 align=center width=100%>
	<tr><td colspan=5 class=tboard bgcolor=#EBE4D4>
	<?if($tpl['catalognew']['subscribe']){?>
	    <span class="red"> Вы подписаны на изменения о данной монете (заявки, обсуждение, цены)</span><br>
	<?} else {?>
	    <a href=# onclick="showWin('<?=$cfg['site_dir']?>catalognew/subscribe.php?catalog=<?=$catalog?>&ajax=1','450');return false;">Подписаться на изменения о данной монете (заявки, обсуждение, цены)</a><br>
	<?}?>
    </td></tr>
	<input type=hidden name=catalog value='<?=$catalog?>'>	
    <tr class=tboard valign=top bgcolor=#ffcc66>
	   <td colspan=5><b>Просьба подтвердить или опровергнуть следующую информацию</b></td>
	</tr>
	<tr class=tboard valign=top bgcolor=#ffcc66>
	   <td colspan=5><span class="error"><b><?=implode("<br>",$tpl['submitcataloghistory']['error'])?></b></span></td>
	</tr> 
	<tr class=tboard valign=top bgcolor=#EBE4D4>
	   <td colspan=5 align=right><input type=submit name=submitcataloghistory value='Записать'></td>
	</tr>
	<tr class=tboard valign=top bgcolor=#EBE4D4>
	<td><b>Поле</b></td>
	<td><b>Старое значение</b></td>
	<td><b>Новое значение</b></td>
	<td><b>Добавил</b></td>
	<td nowrap><b>Согласен</b></td>
	</tr>
	<?

	foreach ($tpl['catalognew']['show']['offers'] as $rows ){
		echo  "<tr class=tboard valign=top bgcolor=#EBE4D4><td><a name=cataloghistory".$rows["cataloghistory"]."></a>";
		
	     if ($rows["field"]=="group"){?>			
			Группа (страна)</td>
			<td><?=$rows['GroupArray'][$rows["fieldoldvalue"]]?></td>
			<td><?=$rows['GroupArray'][$rows["fieldnowvalue"]]?></td>
			<td><a href="#cataloghistory<?=$rows["cataloghistory"]?>" onclick="StartUserInfo(<?=$rows["user"]?>);return false;"><?=$rows["userlogin"]?></a></td>
			<td nowrap>
			<input type=Radio name='answercataloghistory[<?=$rows["cataloghistory"]?>]' <?=(${"answercataloghistory"}[$rows["cataloghistory"]]==2?"checked":"")?> value=2> Нет
			<input type=Radio name='answercataloghistory[<?=$rows["cataloghistory"]?>]' <?=(${"answercataloghistory"}[$rows["cataloghistory"]]==1?"checked":"")?> value=1> Да
			</td>
			</tr>
			<?
			continue;
		} elseif ($rows["field"]=="name") echo  "Номинал";
		elseif ($rows["field"]=="yearstart") echo  "Периоды чеканки";
		elseif ($rows["field"]=="metal") {
			echo  "Металл</td>
			<td>".$rows['MetalArray'][$rows["fieldoldvalue"]]."</td>
			<td>".$rows['MetalArray'][$rows["fieldnowvalue"]]."</td>
			<td><a href=#cataloghistory".$rows["cataloghistory"]." onclick=\"javascript:StartUserInfo(".$rows["user"].");\">".$rows["userlogin"]."</a></td>
			<td nowrap>
			<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==2?"checked":"")." value=2> Нет
			<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==1?"checked":"")." value=1> Да
			</td>
			</tr>";
			continue;
		}
		elseif ($rows["field"]=="probe") echo  "Проба";
		elseif ($rows["field"]=="procent") echo  "Соотношение металла";
		elseif ($rows["field"]=="amount") echo  "Тираж (в тысячах штук)";
		elseif ($rows["field"]=="size") echo  "Диаметр в мм";
		elseif ($rows["field"]=="thick") echo  "Толщина в мм";
		elseif ($rows["field"]=="weight") echo  "Вес в граммах";
		elseif ($rows["field"]=="theme") {
			echo  "Тематики<td>";
			echo  $rows["shopcoinstheme"]."</td>";
			echo  "<td>";
			echo  $rows["shopcoinstheme1"]."</td>";
			echo  "<td><a href=#cataloghistory".$rows["cataloghistory"]." onclick=\"javascript:StartUserInfo(".$rows["user"].");\">".$rows["userlogin"]."</a></td>
			<td nowrap>
			<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==2?"checked":"")." value=2> Нет
			<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==1?"checked":"")." value=1> Да
			</td>
			</tr>";
			continue;
		}elseif ($rows["field"]=="condition") {
			echo  "Состояния при чеканке</td>
			<td>".$ConditionMintArray[$rows["fieldoldvalue"]]."</td>
			<td>".$ConditionMintArray[$rows["fieldnowvalue"]]."</td>
			<td><a href=#cataloghistory".$rows["cataloghistory"]." onclick=\"javascript:StartUserInfo(".$rows["user"].");\">".$rows["userlogin"]."</a></td>
			<td nowrap>
			<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==2?"checked":"")." value=2> Нет
			<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==1?"checked":"")." value=1> Да
			</td>
			</tr>";
			continue;
		}
		elseif ($rows["field"]=="herd") echo  "Тип гурта описание";
		elseif ($rows["field"]=="averslegend") echo  "Легенда аверса";
		elseif ($rows["field"]=="translateaverslegend") echo  "Перевод легенды аверса";
		elseif ($rows["field"]=="reverselegend") echo  "Легенда реверса";
		elseif ($rows["field"]=="translatereverselegend") echo  "Перевод легенды реверса";
		elseif ($rows["field"]=="mint") echo  "Монетные двора";
		elseif ($rows["field"]=="designer") echo  "Дизайнер";
		elseif ($rows["field"]=="officialdate") echo  "Официальная дата выпуска";
		elseif ($rows["field"]=="image_big_url"){
			echo  "Изображение</td>
			<td>[ <a href=# onclick=\"window.open('".$rows["fieldoldvalue"]."','_pic$catalog','width=450,height=250,toolbar=0');\">Посмотреть</a> ]</td>
			<td>[ <a href=# onclick=\"window.open('".$rows["fieldnowvalue"]."','_pic$catalog','width=450,height=250,toolbar=0');\">Посмотреть</a> ]</td>
			<td><a href=#cataloghistory".$rows["cataloghistory"]." onclick=\"javascript:StartUserInfo(".$rows["user"].");\">".$rows["userlogin"]."</a></td>
			<td nowrap>
			<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==2?"checked":"")." value=2> Нет
			<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==1?"checked":"")." value=1> Да
			</td>
			</tr>";
			continue;
		} elseif ($rows["field"]=="details") echo  "Развернутое описание";
		
		echo  "</td>
		<td>".$rows["fieldoldvalue"]."</td>
		<td>".$rows["fieldnowvalue"]."</td>
		<td><a href=#cataloghistory".$rows["cataloghistory"]." onclick=\"javascript:StartUserInfo(".$rows["user"].");\">".$rows["userlogin"]."</a></td>
		<td nowrap>
		<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==2?"checked":"")." value=2> Нет
		<input type=Radio name='answercataloghistory[".$rows["cataloghistory"]."]' ".(${"answercataloghistory"}[$rows["cataloghistory"]]==1?"checked":"")." value=1> Да
		</td>
		</tr>";
	}?>
	
	<tr class=tboard valign=top bgcolor=#EBE4D4>
	   <td colspan=5 align=right><input type=submit name=submitcataloghistory value='Записать' class=formtxt></td>
	</tr>	
	</table>
	</form>	
	</div>
<?} 

if(!$tpl['catalognew']['error']['no_coins']){

//сейчас показываем токо для аксессуаров

if(isset($tpl['show']['resultcicle'])&&$tpl['show']['resultcicle']){?>
<div class="wraper clearfix">
	<h5>Похожие позиции в магазине:</h5>
</div>
<div class="triger-carusel" style="padding: 0 50px;">	
		  <div class="d-carousel-show">
          <ul class="carousel">

			<?foreach ($tpl['show']['resultcicle'] as $rowsp){
			      $rowsp['metal'] = $tpl['metalls'][$rowsp['metal_id']];
		          $rowsp['condition'] = $tpl['conditions'][$rowsp['condition_id']];
			  ?>
			
    			<li>
    			<div class="coin_info" id='item<?=$rowsp['shopcoins']?>'>
    				<div id=show<?=$rowsp['shopcoins']?>></div>
    			<?	
    			$rowsp = array_merge($rowsp, contentHelper::getRegHref($rowsp));
    			$statuses = $shopcoins_class->getBuyStatus($rowsp["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
    			$rowsp['buy_status'] = $statuses['buy_status'];
    			$rowsp['reserved_status'] = $statuses['reserved_status'];	
    			//$rowsp['mark'] = $shopcoins_class->getMarks($rowsp["shopcoins"]);
    			echo contentHelper::render('shopcoins/item/itemmini-carusel',$rowsp);?>
    			</div>
              	</li>
    			<?}?> 

	   </ul>
	</div>
</div>
<script>
 $(document).ready(function() {    
     $('.d-carousel-show .carousel').jcarousel({
        scroll: 1,
        itemFallbackDimension: 75
     }); 
  }); 
</script>
<?}
		
echo "</center><br >";
?>

<noindex>
<div class="wraper clearfix" style="clear: both;">
<div class="bordered">
	<?
    echo $ciclelink;
	echo $tpl['catalognew']['show']['textocenka'];
	
	?>
</div><br>

</div>
</noindex>

 <div style="display:none;" class="frame-form" id="friendsLetter">
    <h1 class="yell_b">Отправить ссылку другу</h1>
	<form action="#" method=post>
	<input type=hidden name=user value="<?=$tpl["user"]['user_id']?>">
	<input type=hidden name=catalog value="<?=$catalog?>">
	<span id="errorFriendsLetter" class="error"></span>
	 <div class="web-form">
        <div class="left"> <label>Ваша ФИО:</label></div>
        <div class="right"><input type=text name=fio id="fio" value="<?=$userData['fio']?>"maxlength=150></div>
     </div>
      <div class="web-form">
        <div class="left"> <label>Ваш Email:</label></div>
        <div class="right"><input type=text name=email id="email" value="<?=$userData['email']?>" maxlength=150></div>
     </div>
     <div class="web-form">
        <div class="left"> <label>Email друга:</label></div>
        <div class="right"><input type=text name=emailfriend id="emailfriend" value="" maxlength=150></div>
     </div>
     <div class="web-form">
        <div class="left"> <label>Ссылка:</label></div>
        <div class="right">
        <input type="hidden" id="link" name="link" value="<?=$cfg['site_dir']."catalognew/".$correct_links["rehref"]?>">
        <?=$cfg['site_dir']."catalognew/".$correct_links["rehref"]?></div>
     </div>
     <div class="web-form">
        <div>
        <label>Сообщение:</label>
        </div>
     </div>
        <div class="web-form">
        <textarea rows="4" cols="40" name="messageform" id="messageform"></textarea>
        </div>
        <div class="web-form">
        <input type="button" onclick="AddMailFriend()" value="Отправить" class="yell_b">
     </div>
     </form>
</div>


<div style="display:none;" class="frame-form" id="coinchange">
    <h1 class="yell_b">Описание (макс. 255 симв.)</h1>    
    <form id="ChangeCoin" name="ChangeCoin" method="post" action="#">
        <input type="hidden" value="" name="catalognewmycatalog" id="catalognewmycatalog">
        <span id="errorChangeCoin" class="error"></span>
        <div class="web-form">
        <div class="left"> <label>Тип:</label></div>
        <div class="right">
        <select name=typechange id=typechange class=formtxt>
        <option value=0>В коллекции</option>
        <option value=1 selected>На обмен</option>
        </select>
        </div>
        </div>    	
        <div class="web-form">
        <div>
        <label>Описание (макс. 255 симв.):</label>	
        </div>
        </div>    
        <div class="web-form">
        <textarea rows="4" cols="40" class="formtxt" name="detailschange" id="detailschange"></textarea>
        </div>
        <div class="web-form">
        <input type="button" onclick="AddForChange()" value="Отправить" class="yell_b">
        </div>	 
    </form>   
</div>
<?
}?>