<div id='products' class="products-cls m-<?=$materialtype?>">
<div class=ftl>
<a href="#" onclick="showInvis('search-params-place');return false;" class="left">Фильтры</a>
<a href="#" onclick="showInvis('sorting-place');return false;" class="right">Сортировка</a>
</div>
<div id='search-params-place'  class="search-params-place"></div>
<div id='sorting-place' class="sorting-place">
<?include(DIR_TEMPLATE.'_mobile/shopcoins/nav_catalog.tpl.php');?>
</div>
<?	
$filter_layaut =  contentHelper::render('_mobile/leftmenu/filters',array('filter_groups'=>$filter_groups,'search'=>$search,'groups'=>$groups,'nominals'=>$nominals,'tpl'=>$tpl,'years'=>$years,'years_p'=>$years_p,'metals'=>$metals,'conditions'=>$conditions,'themes'=>$themes,'materialtype'=>$materialtype,'pricestart'=>$pricestart,'priceend'=>$priceend,'yearstart'=>$yearstart,'yearend'=>$yearend,'seriess'=>$seriess));   
 
 if($filter_layaut){?>
     <script>
       if(!$('#search-params').length){
        	$('#search-params-place').html('<?=escapeJavaScriptText($filter_layaut)?>');
        } else {
        	var last_node = '#yearend';
        	if($('#fb-groups').length) var last_node = '#fb-groups';
        //console.log($('#search-params'));
        //if($('#search-params')) $('#search-params').remove();    
	        var fform = $('<?=escapeJavaScriptText($filter_layaut)?>');
	        if(fform.find('#fb-groups #f-details').html()){
	        	$('#f-details').html(fform.find('#fb-groups #f-details').html());
	        }
	        if($('#fb-nominals')) $('#fb-nominals').remove();   	
	    	var nominals = fform.find('#fb-nominals');
	    	if(nominals.html()){
	    		$('<div id="fb-nominals" class="filter-block">'+nominals.html()+'</div>').insertAfter(last_node);
	    		last_node = '#fb-nominals';
	    	}
	    	
	    	if($('#fb-seriess')) $('#fb-seriess').remove();
	    	var seriess = fform.find('#fb-seriess');
	    	if(seriess.html()){
	    		$('<div id="fb-seriess" class="filter-block">'+seriess.html()+'</div>').insertAfter(last_node);
	    		last_node = '#fb-seriess';
	    	}
	    	if($('#fb-years_p')) $('#fb-years_p').remove();
	    	if($('#fb-years')) $('#fb-years').remove();
	    	
	    	var years_p = fform.find('#fb-years_p');
	    	var years = fform.find('#fb-years');	    	
	    	if(years_p.html()){
	    		$('<div id="fb-years_p" class="filter-block">'+years_p.html()+'</div>').insertAfter(last_node);
	    		last_node = '#fb-years_p';
	    	} else if(years.html()){
	    		$('<div id="fb-years" class="filter-block">'+years.html()+'</div>').insertAfter(last_node);
	    		last_node = '#fb-years';
	    	}       
            if($('#filter-price')) $('#filter-price').remove();
	    	var filter_price = fform.find('#filter-price');
	    	if(filter_price.html()){
	    		$('<div id="filter-price" class="filter-block">'+filter_price.html()+'</div>').insertAfter(last_node);
	    		last_node = '#filter-price';
	    	}
         }    
      </script>     
 <?}
  
//include(DIR_TEMPLATE.'shopcoins/onpage.tpl.php');


if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
?>
<div class="product-grid">
<?
    $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){	      
    	if(in_array($materialtype,array(7,4))){
    		echo "<div class='blockshop_spisok' id='item".$rows['shopcoins']."'>";
    		include(DIR_TEMPLATE.'_mobile/shopcoins/items/item_nabor.tpl.php');
    		echo "</div>";
    	} else {
    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
    		";
    		include(DIR_TEMPLATE.'_mobile/shopcoins/items/item.tpl.php');
    		echo "
    		</div>";
    	}	
    	$i++;	
    }?>
</div>

<?}?>
<div style="width: 100%; display: table;">
<?include(DIR_TEMPLATE.'shopcoins/pager.tpl.php');?>
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
			<div class="coin_info" id='item<?=$rows_show_relation2['shopcoins']?>'>
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
	//if($("#filter-groupgroup_container")) $("#filter-groupgroup_container").mCustomScrollbar({theme:"dark-thick"});
	//"vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
	if($(".filter-groupgroup_container_1")) $(".filter-groupgroup_container_1").mCustomScrollbar({theme:"dark-thick"});
	
	//console.log(jQuery("#filter-grouptheme_container"));
	if($("#filter-grouptheme_container")) $("#filter-grouptheme_container").mCustomScrollbar({theme:"dark-thick"});
	if($(".filter-groupnominal_container_1")) $(".filter-groupnominal_container_1").mCustomScrollbar({theme:"dark-thick"});
	//console.log(jQuery(".filter-groupnominal_container_1"));
	/*$("#mcs2_container").mCustomScrollbar(); 
	$("#mcs3_container").mCustomScrollbar("vertical",900,"easeOutCirc",1.05,"auto","no","no",0); 
	$("#mcs4_container").mCustomScrollbar("vertical",200,"easeOutCirc",1.25,"fixed","yes","no",0); 
	$("#mcs5_container").mCustomScrollbar("horizontal",500,"easeOutCirc",1,"fixed","yes","yes",20); */
}

/* Функция для обхода ошибки с 10000 px для jquery.animate 
$.fx.prototype.cur = function(){
    if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) ) {
      return this.elem[ this.prop ];
    }
    var r = parseFloat( jQuery.css( this.elem, this.prop ) );
    return typeof r == 'undefined' ? 0 : r;
}*/

/* Функция для динамической загрузки содержания 
function LoadNewContent(id,file){
	$("#"+id+" .customScrollBox .content").load(file,function(){
		mCustomScrollbars();
	});
}*/


function full_filter(name,auto,hide) {
    console.log($.cookie(name+'-full-show'));
	var cookiefull = 0;	
	if($.cookie(name+'-full-show')){
		var cookiefull = $.cookie(name+'-full-show');
	}

	if(auto){
	    
	    if(!$(".filter-groupgroup_container_1").length) {
	        $('#group-full-show').remove();
	        return;
	    }
		if(cookiefull>0){
		    $(".filter-groupgroup_container_1").height("auto");
		    $('#group-full-show').attr("onClick","full_filter('"+name+"',false,1);return false;");
		    $('#group-full-show').text("Свернуть");	
		    
		    $('#group-full-show-top').attr("onClick","full_filter('"+name+"',false,1);return false;");
		    $('#group-full-show-top').text("Свернуть");	
		    	   
		} else {			
		    $(".filter-groupgroup_container_1").height("290px");
		    $('#group-full-show').attr("onClick","full_filter('"+name+"',false,0);return false;");
		    $('#group-full-show').text("Развернуть");	    
		    
		    $('#group-full-show-top').attr("onClick","full_filter('"+name+"',false,0);return false;");
		    $('#group-full-show-top').text("Развернуть");	    
		}   
	} else {			
		if(!hide){
		    $(".filter-groupgroup_container_1").height("auto");
		    $.cookie(name+'-full-show', 1);
		    $('#group-full-show').attr("onClick","full_filter('"+name+"',false,1);return false;");
		    $('#group-full-show').text("Свернуть");	
		    
		    $('#group-full-show-top').attr("onClick","full_filter('"+name+"',false,1);return false;");
		    $('#group-full-show-top').text("Свернуть");		   
		} else {			
		    $(".filter-groupgroup_container_1").height("290px");
		    $.cookie(name+'-full-show', 0);
		    $('#group-full-show').attr("onClick","full_filter('"+name+"',false,0);return false;");
		    $('#group-full-show').text("");	
		    
		    $('#group-full-show-top').attr("onClick","full_filter('"+name+"',false,0);return false;");
		    $('#group-full-show-top').text("Развернуть");	
		    $('html, body').animate({
                scrollTop: $("#search-params").offset().top
            }, 1000);	    
		}        
	}
	$(".filter-groupnominal_container").mCustomScrollbar("update");
  
}
 
    $(function(){
    	
     $('#search-params input').bind('change',function(){               	
     	if($(this).attr('id')!='group_name'){
     	    $('#search-params input').unbind("change");
     		sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');
     	}
     }); 
     full_filter('groups',true)    
    });
</script>