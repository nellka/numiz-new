<div class="wraper clearfix showitem" >  
<?
if($tpl['show']['error']['no_coins']){?>
    <div class="error">К сожалению, указанного товара не существует</div>
<?} else {
    include($cfg['path'].'/views/shopcoins/item/item.tpl.php');   
}?>
</div>

<? if(!$tpl['show']['error']['no_coins']){?>
<noindex>
<div class="wraper clearfix info_coins">
	<div style="display: inline-block;width:400px;">
		<div id=allreviews style="vertical-align:top;width:400px;margin-top:20px;overflow:hidden;height:500px;">
			<a name=showreview></a>
			<h5 style="margin-top:0px;">Отзывы покупателей</h5>
			<div id=reviewsdiv >
			<? if (sizeof($tpl['show']['reviews']['reviewusers'])) {
				foreach ($tpl['show']['reviews']['reviewusers'] as $key=>$value) {?>
				<div>
					<b> <?=$value['fio']?></b>
				</div>
			   <div id='review<?=(isset($value["catalog"])&&$value["catalog"])?$value["catalog"]:$value["shopcoins"]?>'>			   
				   <?=$value['review']?>
			   </div>
				<div style="text-align:right;margin-bottom:15px;">
					<span style="color:#cccccc;">Добавлено: </span><b> <?=date('d-m-Y',$value['dateinsert'])?></b>
				</div>
			<?}
			} else {
				echo "<div id=emptyreview class=error>Отзывы отсутствуют</div>";
			}?>
				
			</div>
			
		</div>
		<br>
		<center><a href="">Показать еще</a></center>
	</div>
	<div style="display: inline-block;vertical-align:top;width:500px;margin-left:100px;margin-top:20px;">
	<h5  style="margin-top:0px;">Гарантия:</h5>
		<b>Гарантии на нумизматический материал (монеты, банкноты).</b>
			<br><br>
			После поступления материала в наш офис, каждая монета просматривается мной  для выяснения ее подлинности.<br>
			Как правило, монеты и банкноты поступают к нам от известных мировых дилеров, нумизматических магазинов и т.п.
			Если у меня возникают какие-то сомнения, я пытаюсь узнать о подлинности монеты или банкноты от известных
			нумизматических дилеров города Москвы.<br>
		   По своей практике могу сказать следующее, фальшивые монеты существовали, существуют и будут существовать.
		   Были случаи (5-10) когда клиенты, у которых возникали вопросы о подлинности, приносили монету назад –
		   все инциденты решались в положительную сторону покупателя. Господа, все иногда ошибаются и я тоже.
		   
		   <b>Наши гарантии на монеты и банкноты. </b>
		<br><br>
			1. Если монета вызывает у вас подозрения о подлинности и у вас есть на это веские основания, вы имеете 
			право вернуть монету в течение 1 календарного года после приобретения ее в нашем клубе.<br>
			2. Если в описании монеты не было упоминания, что данная монета является фальшивой, вы имеете право воспользоваться пунктом 1.<br>
			3. При возврате монеты она должна иметь тот же товарный вид, как и при ее получении. (Нам монету тоже нужно назад вернуть дилеру).<br>
			4. Все накладные расходы, связанные с пересылкой монет несет Клуб Нумизмат.<br>
			5. Возвращается полная стоимость за данную монету (банкноту), либо компенсируется другим материалом.<br>

		 <br><br>

		В дополнение: Клуб Нумизмат существует с 2001 года и сохраняет свое имя на протяжении этого времени. 
		Ни одна монета, представленная в магазине, не стоит больше чем наше название. И мы надеемся, что бренд 
		“Клуб Нумизмат” будет существовать еще долго.
	
	</div>
</div>
</noindex>
<?
}

if ($tpl['show']['rowscicle']) {?>
<div class="triger">	
	<div class="wraper clearfix">
		<h5>Похожие позиции в магазине:</h5>
		<div class="coin_info">
			<a href="./<?=$tpl['show']['rowscicle']['reff1']?>" title='<?=$tpl['show']['rowscicle']['title1']?>'>
			<?=$tpl['show']['rowscicle']['title1']?>"
		</a> 
		</div>
		<div class="coin_info">
			<a href='./<?=$tpl['show']['rowscicle']['reff2']?>' title='<?=$tpl['show']['rowscicle']['title2']?>'>
				<?=$tpl['show']['rowscicle']['title2']?>
			</a> 
		</div>
		<div class="coin_info">
			<a href='./<?=$tpl['show']['rowscicle']['reff3']?>' title='<?=$tpl['show']['rowscicle']['title3']?>'>
				<?=$tpl['show']['rowscicle']['title3']?>
			</a>   
		</div>
	</div>	
</div>	
<?}	


if(!$tpl['show']['error']['no_coins']){

//сейчас показываем токо для аксессуаров
if ($tpl['shop']['related']){?>
<div class="wraper clearfix">
	<h5>СОПУТСТВУЮЩИЕ ТОВАРЫ:</h5>
</div>
<div class="wraper clearfix" style="height:350px;padding-top:15px;">
	
<?
//сейчас показываем токо для аксессуаров
if ($tpl['shop']['related']){?>	
	<? foreach ($tpl['shop']['related'] as $rowsp){
	    $rowsp['metal'] = $tpl['metalls'][$rowsp['metal_id']];
		$rowsp['condition'] = $tpl['conditions'][$rowsp['condition_id']];
	    ?>
		<div class="coin_info" id='item<?=$rowsp['shopcoins']?>'>
			<div id=show<?=$rowsp['shopcoins']?>></div>
			<?	
			$statuses = $shopcoins_class->getBuyStatus($rowsp["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
			$rowsp['buy_status'] = $statuses['buy_status'];
			$rowsp['reserved_status'] = $statuses['reserved_status'];	
			$rowsp['mark'] = $shopcoins_class->getMarks($rowsp["shopcoins"]);
			echo contentHelper::render('shopcoins/item/itemmini',$rowsp);
            ?>				
		</div>
	<?}?>
	</div>
<?}?>
</div>
<?}

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
		
if ($tpl['shop']['resultp']) {	?>
	<div class="wraper clearfix">
	<h5>Вместе с этим товаром просматривали также:</h5>
	</div>
	<div class="triger">	
		<div class="wraper clearfix" style="height:270px;padding-top:15px;">
		    <form action=# method=post>
			<?$kn = 100;
			$sumseecoins = 0;
			foreach ($tpl['shop']['resultp']  as $rowsp ){
			    $rowsp['metal'] = $tpl['metalls'][$rowsp['metal_id']];
		        $rowsp['condition'] = $tpl['conditions'][$rowsp['condition_id']];
				if(isset($rowsp["shopcoins"])){?>
					<?=($kn>100?"<div style='float:left;margin-top:130px;'><font size=+2>+</font></div>":"")?>
					
					<div id=imagem<?=$kn?>  class="coin_info" style="margin-left:8px;margin-right:8px;">
						<div id=lastcatalogis<?=$rowsp["shopcoins"]?> style="float:left;"> 
							<input type=checkbox id=shopcoinslast<?=$kn?> name=shopcoinslast<?=$kn?> checked=checked
							value=<?=$rowsp["shopcoins"]?> onclick="ChangeSumSeeCoins('<?=$rowsp['price']?>',<?=$kn?>)">
						</div>
						<div class="coin_info_small_img">
						<div id=showm<?=$kn?>  style="float:left;"></div>
						<?$statuses = $shopcoins_class->getBuyStatus($rowsp["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
						//$rowsp['buy_status'] = $statuses['buy_status'];
						//$rowsp['reserved_status'] = $statuses['reserved_status'];	
						$rowsp['mark'] = $shopcoins_class->getMarks($rowsp["shopcoins"]);
						echo contentHelper::render('shopcoins/item/itemmini',$rowsp);?>
						</div>
					</div>
						<?
						$kn++;
						$sumseecoins += $rowsp['price'];
						
				}	
			}?>
			<input type=hidden value='<?=$sumseecoins?>' id='sumseecoins_val' name='sumseecoins_val'>
			<div id=sumseecoins style="float:left; margin-left:20px;margin-top: 130px;">	
				<font size=+2> =	<?=$sumseecoins?>  руб.</font>
			</div>
			<div id='bascetshopcoins0'  style="float:left;margin-left:50px;margin-top:10px;">
			 <a class="button25" href="#" onclick="AddBascetLast2(<?=$kn?>);return false;" title="Положить все отмеченные монеты из списка в корзину">Купить</a>			
			</div>
	</form>
	</div>
</div>
<?}

	
if( $tpl['shop']['result_show_relation2']) {	?>
<div class="wraper clearfix">
	<h5>Подобные позиции в магазине:</h5>
	</div>
	<div class="triger-carusel" style="padding: 0 50px;">	
		  <div class="d-carousel-show">
          <ul class="carousel">
			<?
			foreach ($tpl['shop']['result_show_relation2'] as $rows_show_relation2){
				$rows_show_relation2['metal'] = $tpl['metalls'][$rows_show_relation2['metal_id']];
				$rows_show_relation2['condition'] = $tpl['conditions'][$rows_show_relation2['condition_id']];
				?>			
				<li>
    			 <div class="coin_info" id='item<?=$rows_show_relation2['shopcoins']?>'>
					<div id=show<?=$rows_show_relation2['shopcoins']?>></div>
				<?	
				$rows_show_relation2 = array_merge($rows_show_relation2, contentHelper::getRegHref($rows_show_relation2));
				$statuses = $shopcoins_class->getBuyStatus($rows_show_relation2["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
				$rows_show_relation2['buy_status'] = $statuses['buy_status'];
				$rows_show_relation2['reserved_status'] = $statuses['reserved_status'];	
				$rows_show_relation2['mark'] = $shopcoins_class->getMarks($rows_show_relation2["shopcoins"]);
				echo contentHelper::render('shopcoins/item/itemmini-carusel',$rows_show_relation2);
				?>	
			 </div>
			</li>
		<?}?>
		</ul>
	</div>
</div>	
<?}


if ($tpl['shop']['result_show_relation3']) {
	
	$RelationText = "
	<table border=0 cellpadding=3 cellspacing=1 align=center width=98%>
	<tr bgcolor=#EBE4D4 valign=top>
	<td colspan=2 class=tboard bgcolor=#99CCFF><b>Тот кто смотрел товар потом купил:</b></td></tr>";
	$k = 0;
	$oldmaterialtype = 0;
	foreach ($tpl['shop']['result_show_relation3'] as $rows_show_relation2){	
	    $rows_show_relation2['metal'] = $tpl['metalls'][$rows_show_relation2['metal_id']];
		$rows_show_relation2['condition'] = $tpl['conditions'][$rows_show_relation2['condition_id']];			
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


echo "</center><br >";
?>
	
<?	if($tpl['show']['described'])	{ ?>

<div class="wraper clearfix" style="clear: both;">
	<h5>Опишите монету и получите 1 рубль на бонус-счет</h5>
	
	<form action=/detailcoins/addcomment.php name=mainform id="send_descr" method=post style="width:500px;">
	  <script>
		 $(function() {    
    var availableTags = <?=json_encode($groupselect_v2)?>;
        $('#group2').autocomplete({
          source: availableTags,
          select: function (event, ui) {
            $('#id_group2').val(ui.item.id);            
            AddNominal();
            return ui.item.label;
        }
        });
    });
  </script>

 
<div class="ui-widget">
  <label for="group2"><p class="formitem"><b>Страна:</b></p> </label>
  <input id="group2" name=group2 size=40>
  <input type="hidden" id="id_group2" name=id_group2 size=80>
</div>

		<input type=hidden id=coins name=coin value="<?=$catalog?>">		

		<div class="ui-widget" >
  <label for="name2""><p class="formitem"><b>Номинал:</b></p> </label>
  <input id="name2"" size=40 name="name2">
</div>
		
		<br>
		<a name=year></a><p class="formitem"><b>Год: </b></p><input class=formtxt id="year2" name="year" required size=4/> <br>
		<p class="formitem"><b>Металл: </b></p>
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
		<a name=metal></a><p class="formitem"><b>Состояние: </b></p>
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
		<input type="button" name=submit onclick="CheckSubmitPrice(0);" value="Сохранить описание" class=formtxt >		
</form>
</div>
<?}

//отзывы
?>
<noindex>
<div class="wraper clearfix" style="clear: both;">
<div style="float:left;width:980px;border:1px solid #cccccc;margin-top:50px;padding:20px;">
	<div style="float:left;width:45%;padding:10px;">
		<h5  style="margin-top:0px;">Доставка:</h5>
		<b>В салоне продаж м.Тверская, Чеховская, Пушкинская</b>
		<ul>
			<li>Без предварительного заказа </li>
			<li>ул. Тверская 12 стр. 8 (2-3 минуты пешком)</li>
			<li><a href="https://www.google.ru/maps/place/%D0%A2%D0%B2%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F+%D1%83%D0%BB.,+12%D1%818,+%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0,+125009/@55.7638328,37.6088217,18z/data=!4m2!3m1!1s0x46b54a46b695b08f:0x5e895e0d8de444fe?hl=ru">Смотрите схему прохода</a></li>
			<li> Просмотр материала и его покупка, если он Вас устраивает.</li>
		</ul>
		<b>В магазине м.Тверская, Чеховская, Пушкинская (самовывоз)</b>
		<ul>
			<li>Сумма заказа должна быть не менее 500 руб.</li>
			<li>Время встречи нужно заранее согласовать по нашим контактным телефонам.</li>
			<li>Метро: налево после выхода из метро - до ул. Тверская 12 стр. 8 (2-3 минуты пешком)</li>
			<li><a href="https://www.google.ru/maps/place/%D0%A2%D0%B2%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F+%D1%83%D0%BB.,+12%D1%818,+%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0,+125009/@55.7638328,37.6088217,18z/data=!4m2!3m1!1s0x46b54a46b695b08f:0x5e895e0d8de444fe?hl=ru">Смотрите схему прохода</a></li>
			<li>Заказ должен быть выкуплен в течении 1 месяца.</li>
		</ul>
		<b>Доставка в офис/на дом по Москве (сумма заказа от 1000 до 15000 руб.)</b>
		<ul>
			<li>Сумма заказа должна быть не менее 1 000 руб.</li>
			<li>Только в пределах МКАД</li>
			<li>Доставка в офис/на дом в пределах станций кольцевой линии Метрополитена «внутри кольца» – 200 руб.<br>
				+ стоимость проезда от ближайшей станции метро в оба конца, если расстояние от метро до вас более 1 км.</li> 
			<li>Доставка в офис/на дом по всем остальным районам г. Москвы от 300-500 руб. в зависимости от расстояния, объема заказа, загруженности работников офиса и пр.
				<br>+ стоимость проезда от ближайшей станции метро в оба конца, если расстояние от метро до вас более 1 км.</li> 
			<li>Внимание! Администрация сайта самостоятельно определяет тарифы на доставку заказа в каждом отдельном случае!</li>
			<li>Доставка происходит только после того, как с администратором по телефону обговорены все детали (время доставки, стоимость)</li>
			<li>Доставка осуществляется в течении 2-3 рабочих дней (если нужно быстрей - звоните по телефону +7 (903) 006-00-44 или +7-915-002-22-23)</li>
			<li>Заказ должен быть выкуплен в течении 1 месяца.</li>
		</ul>
		<b>Доставка в офис/на дом по Москве в пределах МКАД (сумма заказа от 15000 руб.)</b>
		<ul>
			<li>Доставка осуществляется бесплатно при суммах более 15000 руб.</li>
			<li>Время и место обговариваются по телефону</li>
			<li>Доставка происходит только после того, как с администратором по телефону обговорены все детали (время доставки)</li>
			<li>Вес заказа не более 5 кг., при весе более 5 кг. доставка расчитывается как доставка в офис за каждые неполные 5 кг.</li>
			<li>Заказ должен быть выкуплен в течении 1 месяца.</li>
		</ul>
		<b>Личная встреча в Москве</b>
			<ul>
				<li>Доставка осуществляется бесплатно</li>
				<li>Место встречи: кольцевые станции метрополитена</li>
				<li>Время встречи: с 18-00 до 20-00 в будние дни 2-3 раза в неделю</li>
				<li>Встречи происходят без опозданий с Вашей стороны (4 минуты считается опозданием)</li>
				<li>Встреча происходит только после того, как с администратором по телефону обговорены все детали</li>
				<li>Обзвон клиентов производится с 12.00 до 15.00 в указанный Вами день при оформлении заказа,
				если звонка не поступило - просьба связаться с администратором по телефону +7 (903) 006-00-44 или +7(915) 002-22-23 до 16.00</li>
				<li>Сумма заказа должна быть не менее 500 руб.</li>
				<li>Вес заказа не более 5 кг., при весе более 5 кг. доставка расчитывается как в пределах станций кольцевой 
				линии Метрополитена «внутри кольца» за каждые неполные 5 кг.</li>
				<li>По телефону в день встречи с Вами свяжется наш сотрудник, который опишет себя и место встречи</li>
				<li>Заказ должен быть выкуплен в течении 1 месяца.</li>
			</ul>
		<b>Экспресс доставка</b>
			<ul>
				<li>Доставка осуществляется на следующий день с момента оформления заказа</li>
				<li>Время встречи обговаривается по телефону</li>
				<li>Стоимость экспресс доставки - 500 рублей</li>
			</ul>
		<b>По почте</b>
			<ul>
				<li>Доставка почтой осуществляется только по территории РФ</li>
				<li>Сумма заказа должна быть не менее 800 руб. и не более 40 000 руб.</li>
				<li>Заказы отправляються через центр магистральных перевозок с г. Москвы</li>
				<li>К стоимости Вашего заказа будет добавлена стоимость почтовых услуг по упаковке, страховке и
				доставке его Вам, которая зависит от пункта назначения, массы и стоимости товара. 
				Почтовые услуги ~ от 5 до 10 %. В связи с участившимися случаями пропаж и вскрытия отправлений на Почте России во всех почтовыех отправлениях страхование производится на полную их стоимость.</li>
			</ul>
	</div>
	<div style="float:right;width:45%;padding:10px;">
		<h5  style="margin-top:0px;">Оплата:</h5>
		
   <b>  Любой банк (в том числе Сбербанк) - экономия времени и стоимости ( Как оплатить заказ картой Сбербанка )</b>
   <ul>
        <li><img src="<?=$cfg['site_dir']?>images/static_images/sberbank.gif" border="">Распечатать квитанцию</li>
        <li>Индивидуальный Предприниматель Мандра Богдан Михайлович</li>
        <li>ИНН: 504908219824</li>
        <li>ОГРН: 309504726100042</li>
       <li> Расчетный счет 40802810538050005372 в Московский банк ОАО Сбербанк России 3805/01702</li>
        <li>БИК 044525225</li>
        <li>Номер кор./сч. банка получателя платежа 30101810400000000225</li>
        <li>Внимание: Для ускорения процесса выполнение заказа, после перевода денег,
		просьба направить письмо на адрес administrator@numizmatik.ru с темой \"Оплата заказа | Сбербанк | Клуб Нумизмат\".
		В теле сообщения укажите номер заказа и сумму перевода.</li>
       <li> В случае отсутствия письма с информацией о переводе денег, возможны задержки с отправкой заказа</li>
	</ul>
   <b> Банковскими картами VISA, MasterCard 4% - быстро и удобно</b>
   <ul>	
		<li><img src="<?=$cfg['site_dir']?>images/static_images/visa.gif" border=""></li>
       <li> Вы оплачиваете заказ в любое удобное для Вас время - сразу после совершения заказа либо зайдя в "Ваши заказы".</li>
        <li>Вы платите дополнительно 4% от суммы заказа за услуги по переводу денег.</li>
        <li>Моментальная оплата заказа производится через систему электронных платежей ROBOKASSA.</li>
	</ul>
    <b>Наложенный платеж 4-8%</b>
	<ul>
		<li><img src="<?=$cfg['site_dir']?>images/static_images/po4ta1.jpg" border=""></li>
        <li>Вы оплачиваете заказ на почте при его получении.</li>
       <li> Вы платите дополнительно 4 - 7 % от суммы наложенного платежа за услуги по пересылке денег.</li>
        <li>Если заказ не выкуплен по причине клиента, Клуб Нумизмат оставляет за собой право и на свое
		усмотрение добавить клиента в Черный список либо работать по системе предоплаты.</li>
	</ul>
   <b>  Наличный расчет</b>
   <ul>
        <li>Соответственно осуществляется при личной встрече</li>
	</ul>
   <b>  WebMoney 0,8%</b>
   <ul>
		<li><img src="<?=$cfg['site_dir']?>images/static_images/wm1.gif" border=""></li>
       <li> Вы можете осуществить предоплату, воспользовавшись платежной системой WebMoney. Вы платите дополнительно 0.8 % от суммы перевода</li>
       <li> Номер счета в рублях: R576689304959</li>
       <li> Номер счета в USD: Z570568313069</li>
        <li>Номер счета в EURO: E320477577247</li>
        <li>ФИО: Мандра Богдан Михайлович</li>
        <li>WMID: 455446320323</li>
        <li>Псевдоним: Numizmatik.Ru</li>
        <li>Передача денег должна происходить без протекции.</li>
        <li>Внимание: Для ускорения процесса выполнение заказа, после 
		перевода денег, просьба направить письмо на адрес administrator@numizmatik.ru 
		с темой "Оплата заказа | WebMoney | Клуб Нумизмат". В теле сообщения укажите 
		номер заказа и сумму перевода. В случае отсутствия письма с информацией о переводе денег, возможны задержки с отправкой заказа.</li>
        <li>Вся информация доступна по адресу http://www.WebMoney.ru</li>
	</ul>
    <b>YandexMoney 1%</b>
	<ul>
		<li><img src="<?=$cfg['site_dir']?>images/static_images/yandex-money1.jpg" border=""></li>
      <li>  Вы можете осуществить предоплату, воспользовавшись платежной системой YandexMoney. Вы платите дополнительно 1 % от суммы перевода</li>
        <li>Номер счета: 4100163425137</li>
       <li> ФИО: Мандра Богдан Михайлович</li>
        <li>Внимание: Для ускорения процесса выполнение заказа, 
		после перевода денег, просьба направить письмо на адрес administrator@numizmatik.ru с
		темой "Оплата заказа | YandexMoney | Клуб Нумизмат". В теле сообщения укажите номер заказа 
		и сумму перевода. В случае отсутствия письма с информацией о переводе денег, возможны задержки с отправкой заказа</li>
        <li>Вся информация доступна по адресу http://money.yandex.ru</li>

	</ul>
	</div>
</div>
</div>
</noindex>
<?
}?>