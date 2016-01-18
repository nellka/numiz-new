<? if(isset($filter_groups)&&$filter_groups){?>
<form id='search-params' method="POST" action="<?=$_SERVER['REQUEST_URI']?>" style="float:left;">
<input type="hidden" id='orderby' name='orderby' value='<?=$tpl['orderby']?>'>
<input type="hidden" id='onpage' name='onpage' value='<?=$tpl['onpage']?>'>
<input type="hidden" id='pagenum' name='pagenum' value='<?=$tpl['pagenum']?>'>
<input type="hidden" id='materialtype' name='materialtype' value='<?=$materialtype?>'>
<input type="hidden" id='pricestart' name='pricestart' value='<?=$pricestart?>'>
<input type="hidden" id='priceend' name='priceend' value='<?=$priceend?>'>
<input type="hidden" id='yearstart' name='yearstart' value='<?=$yearstart?>'>
<input type="hidden" id='yearend' name='yearend' value='<?=$yearend?>'>

<div style="width:280px;">
	
	<div class="box-heading "  style="line-height: 30px;margin-top: 15px;padding: 0 10px;;">
		<div style="float:left;">Фильтр товаров</div>
		<div style="float:right;">
			<a  href="#" onclick="clear_filter();return false;">Очистить</a>
		</div>
	</div>
	<br style="clear: both;">

	<?php   
	//вводим фильтр для стран
	 	foreach ($filter_groups as $filter_group) {?>
	
		<div class="filter-block" id='fb-<?=$filter_group['filter_group_id_full']?>'>
    		<div class="filter_heading">
    			<div style="float:left;"><?=$filter_group['name']?></div>
    			<div style="float:right;">
    				<a style="color:#247bbd;font-size:12px;line-height:12px;text-decoration:underline;"
    				href="#" onclick="clear_filter('<?=$filter_group['filter_group_id_full']?>');return false;">Сбросить</a>
    			</div>
    		</div>
<?
		if($filter_group['filter_group_id']=='group'){?> 		
    		
    		<input type="text" value="" id='group_name' placeholder='Название страны' name="group_name" size="30"><input type="button" value="X" onclick="clear_filter('group_name');return false;" style="float:right">

		<?}
		?>
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container">
				<div class="customScrollBox">
					<div class="container">
						<div class="content">
							<?php 
							foreach ($filter_group['filter'] as $filter) {
								if($filter_group['filter_group_id_full']=='years'){
									//подключаем отдельный вид фильтра  ?>	
									<div class="checkbox">
										<?php  if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
											<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
											<a href="?materialtype=<?=$materialtype?>&yearsrart=&yearend"> <?=$filter['name'];?></a>
										<?php } else { ?>
											<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
											<a href="?materialtype=<?=$materialtype?>&yearsrart=&yearend"> <?=$filter['name'];?></a>
										  <?}?>
									</div>
									
								<?} else {?>            
									<div class="checkbox">
										<?php            
										 if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
											<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
									   <a href="?materialtype=<?=$materialtype?>&<?=$filter_group['filter_group_id']?>=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
										<?php } else { ?>
											<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
									   <a href="?materialtype=<?=$materialtype?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter['filter_id']))?>"> <?=$filter['name'];?></a>
										  <?}?>
									</div>
								<?php
									if(isset($filter['child'])){?>
										<div style='margin:5px 20px;'>
										<? foreach ($filter['child'] as $filter_child) {?>
											<div class="checkbox">
												<?php             
												 if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter_child['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
												<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter_child['filter_id']?>" checked="checked" />
												<a href="?materialtype=<?=$materialtype?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter_child['filter_id']))?>"> <?=$filter_child['name'];?></a>
												<?php } else { ?>
												<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?php echo $filter_child['filter_id']; ?>" />
												  <a href="?materialtype=<?=$materialtype?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter_child['filter_id']))?>"> <?=$filter_child['name'];?></a>

												<?php } ?>
											</div>
											<?php     
										   }?>
										</div>
									
									<?}
								}
							}?>  
							
						</div>
					</div>
					<div class="dragger_container">
    					<div class="dragger"></div>
    				</div>
			 </div>
			 
			<a href="#" class="scrollUpBtn"></a> <a href="#" class="scrollDownBtn"></a>
						

			<? if($filter_group['filter_group_id_full']=='years'){$i=0;?>   
				<div style="font-weight:bold;padding: 15px 0;">
					От <input type="text" id="amount-years0" name="fields_filter[amount-years0][0]" value="0" size="10" disabled/>
					до <input type="text" id="amount-years1" name="fields_filter[amount-years1][1]" value="2015" size="10" disabled/>
				</div>
				<div style="margin:0 5px;margin-top:5px;" id="slider-range-years"></div>			 
			<?}?>       
			</div>
		</ul> 
		</div>
	<?php 
		//break;
	 	}
	?>
    <div class="filter-block">		
			<? if($tpl['filter']['price']['max']){?>
				<div style="float:left;"><b>Цена</b></div> 	
				<div style="float:right;">
				<a style="color:#247bbd;font-size:12px;line-height:12px;text-decoration:underline;" href="">
				Сбросить
				</a>
				</div> 				
				<br><br>
				<span>
				От <input type="text" id="amount-price0" name="fields_filter[amount-price0][0]" value="0" size="10" disabled/>
				до <input type="text" id="amount-price1" name="fields_filter[amount-price1][1]" value="1000" size="10" disabled/> руб.
				</span>			
			<?}?>					
			<p><div id="slider-range-price" style="margin-left:5px;margin-right:5px;"></div></p>
	</div>	
</form>

<script type="text/javascript" charset="utf-8">
 jQuery(document).ready(function() {     	
     jQuery( "#slider-range-price" ).slider(
         {
           range: true,
           min: <?=$tpl['filter']['price']['min']?>,                            
           max: <?=$tpl['filter']['price']['max']?>,
           values: [ <?=(integer)$pricestart?>,  <?=$priceend?$priceend:$tpl['filter']['price']['max']?>], 
           slide: function( event, ui ) {
                $( "#amount-price0" ).val(ui.values[ 0 ]);
                $( "#amount-price1" ).val(ui.values[ 1 ]);
             },
             stop: function(event, ui) {
               sendData();
	         }
         });
     jQuery( "#amount-price0" ).val($( "#slider-range-price" ).slider( "values", 0 ));
     jQuery( "#amount-price1" ).val($( "#slider-range-price" ).slider( "values", 1 ));
    <? if(isset($tpl['filter']['yearend'])){?>
  		jQuery( "#slider-range-years" ).slider({
           range: true,
           min: 0,                            
           max: 2015,
           values: [ <?=(integer)$yearstart?>,  <?=$yearend?$yearend:$tpl['filter']['yearend']?>], 
           slide: function( event, ui ) {
                $( "#amount-years0" ).val(ui.values[0]);
                $( "#amount-years1" ).val(ui.values[1 ]);
           },
           stop: function(event, ui) {
               sendData();
           }
         });
         jQuery( "#amount-years0" ).val($( "#slider-range-years" ).slider( "values", 0 ));
     	 jQuery( "#amount-years1" ).val($( "#slider-range-years" ).slider( "values", 1 ));
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
     sendData();
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
</script>
<?}?>
</div>

   <script type="text/javascript">  
        $(function(){
        	
         $('#search-params input').on('change',function(){  
         	console.log($(this).attr('id'));       	
         	if($(this).attr('id')!='group_name'){
         		sendData();
         	}
         });

         if($('#group_name')){
        	$('#group_name').on('keyup', function() {
    		    var query = this.value.toLowerCase();		    
    			if(query.length>1){
    			    $('#fb-groups .checkbox').each(function(i, elem) {			    	
    			          if ($(elem).find('a').text().toLowerCase().indexOf(query) != -1) {
    			              $(elem).show();
    			          }else{
    			              $(elem).hide();
    			          }
    			          mCustomScrollbars();
    			    });
    			} else {
    				$('#fb-groups .checkbox').each(function(i, elem) {			    	
    			        $(elem).show();
    			    });
    			}
    		});
        }        
        });
        

    </script>