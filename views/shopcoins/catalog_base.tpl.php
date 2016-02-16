<div id='products' class="products-cls">

<?	
$filter_layaut =  contentHelper::render('leftmenu/filters',array('filter_groups'=>$filter_groups,'search'=>$search,'groups'=>$groups,'nominals'=>$nominals,'tpl'=>$tpl,'years'=>$years,'years_p'=>$years_p,'metals'=>$metals,'conditions'=>$conditions,'themes'=>$themes,'materialtype'=>$materialtype,'pricestart'=>$pricestart,'priceend'=>$priceend,'yearstart'=>$yearstart,'yearend'=>$yearend,'seriess'=>$seriess));   
 
 if($filter_layaut){?>
     <script>
        if($('#search-params')) $('#search-params').remove();
    	$('<?=escapeJavaScriptText($filter_layaut)?>').insertAfter('#left_menu_shop');
      </script>
     
 <?}
  
include('onpage.tpl.php');
include('nav_catalog.tpl.php');

if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
?>
<div class="product-grid">
<?
    $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){	
    	if(in_array($materialtype,array(7,4))){
    		echo "<div class='blockshop_spisok'>";
    		include('items/item_nabor.tpl.php');
    		echo "</div>";
    	} else {
    		echo "<div class='blockshop'>
    		<div class='blockshop-full'>
    		";
    		include('items/item.tpl.php');
    		echo "</div>
    		</div>";
    	}	
    	$i++;	
    }?>
</div>

<?}?>
<div style="width: 100%; display: table;">
<?include('pager.tpl.php');?>
</div>

<?if ($tpl['catalog']['lastViews']) {	?>
	<div class="wraper">
	<h5>10 последних просматриваемых товаров</h5>
	</div>
	<div class="triger-carusel">	
		  <div class="d-carousel">
          <ul class="carousel">
          
		<?
		foreach ($tpl['catalog']['lastViews'] as $rows_show_relation2){
		    $rows_show_relation2['metal'] = $tpl['metalls'][$rows_show_relation2['metal_id']];
		    $rows_show_relation2['condition'] = $tpl['conditions'][$rows_show_relation2['condition_id']];
		    ?>			
			<li>
			<div class="coin_info">
				<div id=show<?=$rows_show_relation2['shopcoins']?>></div>
			<?	
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
<?}?>
<br class="clear:both">
<?if($tpl['seo_data']){?>
<div class="seo" class="clearfix">
<h5><?=$tpl['seo_data']['title']?></h5>
<?=$tpl['seo_data']['text']?>
</div>
<?}?>

<script type="text/javascript" charset="utf-8">

 jQuery(document).ready(function() {    
     $('.d-carousel .carousel').jcarousel({
        scroll: 1,
        itemFallbackDimension: 75
     }); 	
     $("#slider-range-price").slider("destroy");
     $( "#slider-range-price" ).slider({
           range: true,
           min: <?=$tpl['filter']['price']['min']?>,                            
           max: <?=$tpl['filter']['price']['max']?>,
           values: [ <?=(integer)$pricestart?>,  <?=$priceend?$priceend:$tpl['filter']['price']['max']?>], 
           slide: function( event, ui ) {
                $( "#amount-price0" ).val(ui.values[ 0 ]);
                $( "#amount-price1" ).val(ui.values[ 1 ]);
             },
             stop: function(event, ui) {
               sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');
	         }
         });
     $( "#amount-price0" ).val($( "#slider-range-price" ).slider( "values", 0 ));
     $( "#amount-price1" ).val($( "#slider-range-price" ).slider( "values", 1 ));
    <? if(isset($tpl['filter']['yearend'])){?>
  		$( "#slider-range-years" ).slider({
           range: true,
           min: <?=$tpl['filter']['yearstart']?>,                            
           max: <?=date("Y",time())?>,
           values: [ <?=(integer)$yearstart?>,  <?=$yearend?$yearend:$tpl['filter']['yearend']?>], 
           slide: function( event, ui ) {
                $( "#amount-years0" ).val(ui.values[0]);
                $( "#amount-years1" ).val(ui.values[1 ]);
           },
           stop: function(event, ui) {
               sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');
           }
         });
         $( "#amount-years0" ).val($( "#slider-range-years" ).slider( "values", 0 ));
     	 $( "#amount-years1" ).val($( "#slider-range-years" ).slider( "values", 1 ));
     	 <?}?>
  		 //jQuery("#filter-groupgroup").mCustomScrollbar("vertical",400,"easeOutCirc",1.05,"auto","yes","yes",10);
  		 mCustomScrollbars()

     }
 );
     
 function showFilter(filter){
 	var is_visible = jQuery('#'+filter).is(':visible')?true:false;
 	is_visible?jQuery('#'+filter).hide():jQuery('#'+filter).show();
 	jQuery('#'+filter+'-href').html(is_visible?"Показать":"Скрыть");
 }
 function clear_filter_group(id){
 	if($("#fb-groups :checkbox[value="+id+"]").prop("checked")){
 		$("#fb-groups :checkbox[value="+id+"]").prop("checked",false);
 		sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');
 	}
 	//var is_visible = jQuery('#'+filter).is(':visible')?true:false;
 	//is_visible?jQuery('#'+filter).hide():jQuery('#'+filter).show();
 	//jQuery('#'+filter+'-href').html(is_visible?"Показать":"Скрыть");
 }
function resetSliderYear() {
 
}

 function  clear_filter(filter){
 	if(filter=='group_name'){
 		$("#group_name").val('');
 		$('#fb-groups .checkbox').each(function(i, elem) {			    	
	        $(elem).show();
	    });
	    mCustomScrollbars();
	    return;
 	}
    if(filter){
        jQuery('input[name="'+filter+'[]"]:checked').attr('checked',false); 
    } else {  
         jQuery('#search-params input').attr('checked',false); 
    }
    
    if(filter=='years'||filter=='price'){
          var $slider = $("#slider-range-"+filter);
          $slider.slider('option', 'values', [$slider.slider("option", "min"),$slider.slider("option", "max")]);
           $( "#amount-"+filter+"0" ).val($slider.slider("option", "min"));
           $( "#amount-"+filter+"1" ).val($slider.slider("option", "max"));
     }
     sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');
     return false;
 }

function mCustomScrollbars(){
	/* 
	Параметры плагина CustomScrollbar: 
	1) Тип прокрутки (значение: "vertical" или "horizontal")
	2) Величина перемещения со сглаживанием (0 - сглаживание не используется) 
	3) Тип сглаживания перемещений 
	4) Дополнительное место снизу, только для вертикального типа прокрутки (минимальное значение: 1)
	5) Настройка высоты/ширины панели прокрутки (значение: "auto" или "fixed")
	6) Поддержка прокрутки колесиком мыши (значение: "yes" или "no")
	7) Прокрутка с помощью клавиш (значения: "yes" или "no")
	8) Скорость прокрутки (значение: 1-20, 1 соответствует самой медленной скорости)
	*/
	if($("#filter-groupgroup_container")) $("#filter-groupgroup_container").mCustomScrollbar("vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
	
	//console.log(jQuery("#filter-grouptheme_container"));
	if($("#filter-grouptheme_container")) $("#filter-grouptheme_container").mCustomScrollbar("vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
	if($(".filter-groupnominal_container_1")) $(".filter-groupnominal_container_1").mCustomScrollbar("vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
	//console.log(jQuery(".filter-groupnominal_container_1"));
	/*$("#mcs2_container").mCustomScrollbar(); 
	$("#mcs3_container").mCustomScrollbar("vertical",900,"easeOutCirc",1.05,"auto","no","no",0); 
	$("#mcs4_container").mCustomScrollbar("vertical",200,"easeOutCirc",1.25,"fixed","yes","no",0); 
	$("#mcs5_container").mCustomScrollbar("horizontal",500,"easeOutCirc",1,"fixed","yes","yes",20); */
}

/* Функция для обхода ошибки с 10000 px для jquery.animate */
$.fx.prototype.cur = function(){
    if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) ) {
      return this.elem[ this.prop ];
    }
    var r = parseFloat( jQuery.css( this.elem, this.prop ) );
    return typeof r == 'undefined' ? 0 : r;
}

/* Функция для динамической загрузки содержания 
function LoadNewContent(id,file){
	$("#"+id+" .customScrollBox .content").load(file,function(){
		mCustomScrollbars();
	});
}*/

    $(function(){
    	
     $('#search-params input').bind('change',function(){               	
     	if($(this).attr('id')!='group_name'){
     	    $('#search-params input').unbind("change");
     		sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');
     	}
     });     
    });
</script>