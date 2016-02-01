<div id='products'>

<?	
if(!$childen_data_years){?>
     <script>
        if($('#fb-years')) $('#fb-years').remove();
    	if($('#fb-years_p')) $('#fb-years_p').remove();
      </script>
<?} 
//if($groups){
   // var_dump(var_dump($tpl['filter']['price']));
    ?>
      <script>
     
         // $slider.slider('option', 'values', [$slider.slider("option", "min"),$slider.slider("option", "max")]);
          
     <?if($tpl['filter']['price']['max']==$tpl['filter']['price']['min']){       
         ?>
         $('#filter-price').hide();
     <?} else {?>
      $('#filter-price').show();
       // if($('#fb-years')) $('#fb-years').remove();
    	//if($('#fb-years_p')) $('#fb-years_p').remove();
     <?}?>
      </script>
<?//}
if($groups&&$nominals){
    $filter_years_content =  contentHelper::render('leftmenu/filter_block',array('name'=>'Год','filter_group_id'=>'year','filter_group_id_full'=>'years_p','filter'=>$childen_data_years,'materialtype'=>$materialtype,'checked'=>$years_p,'groups'=>$groups));   

    ?>
    <script>
    $(function(){    	 
    	if($('#fb-years')) $('#fb-years').remove();
    	if($('#fb-years_p')) $('#fb-years_p').remove();
    	$('<?=escapeJavaScriptText($filter_years_content)?>').insertBefore('#fb-metals');
         $('#search-params input').unbind("change");
         $('#search-params input').bind("change");    
         
        if($('#years-slider')){$('#years-slider').show();} 
           
    });   
   </script>
<? 
} else {
     $filter_years_content =  contentHelper::render('leftmenu/filter_block',array('name'=>'Год','filter_group_id'=>'year','filter_group_id_full'=>'years','filter'=>$childen_data_years,'materialtype'=>$materialtype,'checked'=>$years,'groups'=>$groups));?>
     <script>
    $(function(){    	 
    	if($('#fb-years')) $('#fb-years').remove();
    	if($('#fb-years_p')) $('#fb-years_p').remove();
    	$('<?=escapeJavaScriptText($filter_years_content)?>').insertBefore('#fb-metals');
        $('#search-params input').unbind("change");
         $('#search-params input').bind("change");    
         if($('#years-slider')){$('#years-slider').show();} 
           
    });   
   </script>
     
<?}
if( $childen_data_nominals) {
	$filter_nominal_content =  contentHelper::render('leftmenu/filter_block',array('name'=>'Номинал','filter_group_id'=>'nominal','filter_group_id_full'=>'nominals','filter'=>$childen_data_nominals,'materialtype'=>$materialtype,'checked'=>$nominals,'groups'=>$groups));
	//var_dump(count($childen_data_nominals)>10,count($childen_data_nominals));
	
	?>   
   <script>
    $(function(){    	 
    	$('#fb-nominals').remove();
    	$('<?=escapeJavaScriptText($filter_nominal_content)?>').insertAfter('#fb-groups');
        $('#search-params #fb-nominals input').on('change',function(){sendData();});   
        <?// if(count($childen_data_nominals)>10){?>
         if($("#filter-groupnominal_container")) {
         	$("#filter-groupnominal_container").mCustomScrollbar("vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
         }
        <?//}?>
	
    });   
   </script>
<?} elseif (!$childen_data_nominals){?>
 <script>
	$('#fb-nominals').remove();
	 </script>
<?}



include('pager.tpl.php');

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

<script type="text/javascript" charset="utf-8">
 jQuery(document).ready(function() {     	
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
	if(jQuery("#filter-groupgroup_container")) jQuery("#filter-groupgroup_container").mCustomScrollbar("vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
	
	//console.log(jQuery("#filter-grouptheme_container"));
	if(jQuery("#filter-grouptheme_container")) jQuery("#filter-grouptheme_container").mCustomScrollbar("vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
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