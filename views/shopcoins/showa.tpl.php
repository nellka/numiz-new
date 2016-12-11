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
<div class="table sha">
	<div class="box-heading table-row">
		<div class="table-cell"><h5>Отзывы покупателей</h5> 
		<a id="h-details" onclick="showInfo();return false" href="#">Показать</a>
		<a id="h-details-hide" onclick="showInfo(1);return false" href="#" style="display:none">Скрыть</a>
		</div>
	    <div class="table-cell"><h5>Гарантии (подлинности монет)</h5>
	    <a id="h-details-g" onclick="showInfo();return false" href="#">Показать</a>	
	    <a id="h-details-g-hide" onclick="showInfo(1);return false" href="#" style="display:none">Скрыть</a>  
	    </div>  
	</div>
	<div style="display:none;" id='allreviews' class="table-row">
		<div class="table-cell">
				<a name=showreview></a>
				
				<div id=reviewsdiv class="reviewsdiv">
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
		
	    <div class="table-cell">
	    	<div>
	
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