<div class="clearfix showitem" >  
<?
if($tpl['show']['error']['no_coins']){?>
    <div class="error">К сожалению, указанного товара не существует</div>
<?} else {
    //include($cfg['path'].'/views/shopcoins/item/item.tpl.php');   
  if ($rows_main["novelty"]){?>
    <div class="new">Новинка</div>
    <?} elseif ($rows_main["dateinsert"]>time()-86400*180){?>
    	<div class="new_red">NEW</div>
    <?}?>
    
    <div class="mainItemPhoto">
            <?include($cfg['path'].'/views/shopcoins/item/imageBig.tpl.php');?>
    </div>
    <div> 
    <h1><?=$rows_main["name"]?></h1> 
    <div>
    	<?php 	echo contentHelper::render('shopcoins/price/prices',$rows_main); 	?>
   </div>      
    <div>
		<?php
		echo contentHelper::render('shopcoins/price/buy_button',$rows_main);
		?>
		 <?if(($rows_main['buy_status']==7||$rows_main['buy_status']==6)&&($minpriceoneclick<=$rows_main['price'])) {
        	echo contentHelper::render('_mobile/shopcoins/price/oneclick',$rows_main);
        }?>
	</div>    	
</div>
   <br style="clear:both"> 
<div class="" id="h-details"">   
    <div  class="detailsItem" id='details' style="display:block">	           
       <?if ($rows_main["gname"]){?>
    	<?=in_array($rows_main["materialtype"],array(9,3,5))?"Группа":"Страна"?>: 
    	<a href=<?=$cfg['site_dir']?>/shopcoins?group=<?=$rows_main['group']?>&materialtype=<?=$rows_main["materialtype"]?> title='Посмотреть <?=contentHelper::setWordThat($rows_main["materialtype"])?> <?=$rows_main["gname"]?>'>
    	<strong><font color=blue><?=$rows_main["gname"]?></font></strong>
    	</a><br>
    	<?}?>
    	<?= ($rows_main["year"]?"Год: <strong>".$rows_main["year"]."</strong><br>":"")?>
    	<?= (trim($rows_main["metal"])?"Металл: <strong>".$rows_main["metal"]."</strong><br>":"")?>
    	<?=(trim($rows_main["condition"])?"Состояние: <strong><font color=blue>".$rows_main["condition"]."</font></strong>":"")?>	            

    Название: <strong><?=$rows_main["name"]?></strong><br>
    Номер: <strong><?=$rows_main["number"]?></strong><br>
    <?
    echo ($rows_main["width"]&&$rows_main["height"]?"Приблизительный размер: <strong>".$rows_main["width"]."*".$rows_main["height"]." мм.</strong><br>":"")."
    ".($rows_main["weight"]>0?"Вес: <strong>".$rows_main["weight"]." гр.</strong><br>":"")."
    ".($rows_main["series"]&&$group?"Серия монет: <a href=$script?series=".$rows_main["series"]."&group=".$group."&materialtype=".$materialtype.">".$series_name[$rows_main["series"]]."</a><br>":"");
    //($rows_main["materialtype"]==8&&$materialtype==1&&!$mycoins?"<br><font color=red>МОНЕТА С РАЗДЕЛА МЕЛОЧЬ, см. условия покупки в разделе</font>":"")."
    if($materialtype==5){
    	if (trim($rows_main["accessoryProducer"])) echo "<b>ISBN: </b>".$rows_main["accessoryProducer"]."<br>";
    	if ($rows_main["accessoryColors"])  echo "<b>Год выпуска: </b>".$rows_main["accessoryColors"]."<br>";
    	if ($rows_main["accessorySize"])  echo "<b>Количество страниц: <font color=blue>".$rows_main["accessorySize"]."</font></b><br>";
    } else {
    	if($rows_main["accessoryProducer"]) echo "Производитель:<strong> ".$rows_main["accessoryProducer"]."</strong><br>";
    	if($rows_main["accessoryColors"]) echo "Цвета:<strong> ".$rows_main["accessoryColors"]."</strong><br>";
    	if($rows_main["accessorySize"]) echo "Размеры:<strong> ".$rows_main["accessorySize"]."</strong><br>";
    }
    		
    if (sizeof($tpl['show']['shopcoinstheme']))	echo "Тематика: <strong>".implode(", ", $tpl['show']['shopcoinstheme'])."</strong><br>"; ?>
    <?    
    if (trim($rows_main["details"]))
    {
    	$text = $rows_main["details"];
    	$text = strip_tags($text);
    	$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
    	$text = str_replace($rows_main['name'],"<strong>".$rows_main['name']."</strong>",$text);
    	$text = str_replace($rows_main['gname'],"<strong>".$rows_main['gname']."</strong>",$text);
    	$text = str_replace(" монет ","<strong> монет </strong>",$text);
    	$text = str_replace(" монета ","<strong> монета </strong>",$text);
    	$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
    	$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
    	echo "<br>Описание: ".str_replace("\n","<br>",$text)."";
    }?>
      
    <?if ($materialtype==1||$materialtype==12||$materialtype==10 || $materialtype==11 ||$materialtype==2|| $materialtype==4 || $materialtype==8 || $materialtype==6 || $materialtype==9) {		   
        if($shopcoins_class->is_already_described($catalog)){			
            echo '<div>Монета была описана <span style="color:red;">пользователем</span> сайта.<br>Клуб Нумизмат 
        несет ответственность за изображение предмета</div>';
        }
    }?>
    </div>
    </div> 
<?}?>
</div>

<? if(!$tpl['show']['error']['no_coins']){?>
<noindex>

 <div class="har" id="h-details" onclick="showInvis('allreviews');">
    Отзывы<div class="close-h-x"></div>
    <div  class="allreviews" id='allreviews' style="display:none">	    	
		<a name=showreview></a>
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
</div>
	
	
<div class="har" id="h-details" onclick="showInvis('garantii');">
    Гарантия<div class="close-h-x"></div>
	<div id='garantii' class="garantii" style="display:none">
		<b>Гарантии на нумизматический материал (монеты, банкноты).</b>
			<br>
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

if(sizeof($tpl['shop']['MyShowArray'])){?>
<div class="wraper clearfix">
	<h5>Похожие позиции в магазине:</h5>
</div>
<br style="clear: both;">
<div class="product-grid search-div">
<?
    $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){		
    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
    		<div class='blockshop-full'>";
    		include('items/item.tpl.php');
    		echo "</div>
    		</div>";
    	$i++;	
    }?>
</div>
<?}
?>
<script>
function showInfo(hide){
	showInvis('allreviews');
	if(!hide){
		$('#h-details').hide();
		$('#h-details-hide').show();
		$('#h-details-g').hide();
		$('#h-details-g-hide').show();
	} else {
		$('#h-details-hide').hide();
		$('#h-details').show();
		$('#h-details-g-hide').hide();
		$('#h-details-g').show();
	}
}
jQuery(document).ready(function() {    
 	$(".blockshop").on("hover", function(e) {
	    if (e.type == "mouseenter") {
	    	if($(this).find(".qwk")) $(this).find(".qwk").show();
	    } else { // mouseleave
	        if($(this).find(".qwk")) $(this).find(".qwk").hide();
	    }
	});
});
</script>