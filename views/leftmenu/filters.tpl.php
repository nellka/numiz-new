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
<div style="float:left;width:280px;">
	<ul class="menu-sidebar_static" style="padding-left:0px;margin-top:15px;float:left;">
		<div class="box-heading ">
			<div style="float:left;">Фильтр товаров</div>
			<div style="float:right;padding-right:17px;padding-top:3px;">
				<a  href="#" onclick="clear_filter();return false;">Очистить</a>
			</div>
		</div>
				

			<link href="<?=$cfg['site_dir']?>css/jquery.mCustomScrollbar.css" rel="stylesheet" type= "text/css"/>
			<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type= "text/javascript"></script>
			<script src="<?=$cfg['site_dir']?>js/jquery.easing.1.3.js" type= "text/javascript"></script>
			<script src= "<?=$cfg['site_dir']?>js/jquery.mousewheel.min.js" type= "text/javascript"></script> 
			<script src="<?=$cfg['site_dir']?>js/jquery.mCustomScrollbar.js" type="text/javascript"></script>
			<li style="float:left;width:95%;">
			<? if($tpl['filter']['price']['max']){?>
				
				<br>
				<div style="margin-left:10px;float:left;">
					<b>Цена
				</div> 	
				<div style="float:right;">
				<a style="color:#247bbd;font-size:12px;line-height:12px;text-decoration:underline;" href="">Сбросить</a>
				</div> 
				
				<br><br>
				<span style="margin-left:10px;">
				От	<input type="text" id="amount1" name="fields_filter[amount1][0]" value="0" size="10" disabled/>
				до <input type="text" id="amount2" name="fields_filter[amount2][1]" value="1000" size="10" disabled/> руб.
				</span>
				</b>
			
			<?}?>

					
			<p><div id="slider-range-price" style="margin-left:15px;margin-right:15px;"></div></p>
		</li>
	</ul>


	<?php     
		foreach ($filter_groups as $filter_group) {      
		?>
		<div class="filter_heading">
			<div style="float:left;"><?=$filter_group['name']?></div>
			<div style="float:right;padding-right:17px;padding-top:3px;">
				<a style="color:#247bbd;font-size:12px;line-height:12px;text-decoration:underline;"
				href="#" onclick="clear_filter('<?=$filter_group['filter_group_id']?>');return false;">Сбросить</a>
			</div>
		</div>
		
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container">
				<div class="customScrollBox" style="padding-left:10px;padding-top:5px;">
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
				<div style="float:left;margin-left:15px;margin-top:10px;font-weight:bold;">
					От	<input type="text" id="amount3" name="fields_filter[amount3][0]" value="0" size="10" disabled/>
					до <input type="text" id="amount4" name="fields_filter[amount4][1]" value="2015" size="10" disabled/>
				</div>
				<div style="float: left;width:85%;margin-left:15px;margin-top:10px;" id="slider-range-year"></div>			 
			<?}?>       
			</div>
		</ul> 
	<?php 
		}
	?>

	
</div>
<div style="padding:20px;float:left;">
	<a href="#" onclick="sendData()" class="iframe button24" >Найти</a>		
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
                $( "#amount1" ).val(ui.values[ 0 ]);
                $( "#amount2" ).val(ui.values[ 1 ]);
             }
         });
     jQuery( "#amount1" ).val($( "#slider-range-price" ).slider( "values", 0 ));
     jQuery( "#amount2" ).val($( "#slider-range-price" ).slider( "values", 1 ));
  
  		jQuery( "#slider-range-year" ).slider({
           range: true,
           min: 0,                            
           max: 2015,
           values: [ <?=(integer)$yearstart?>,  <?=$yearend?$yearend:$tpl['filter']['yearend']?>], 
           slide: function( event, ui ) {
                $( "#amount3" ).val(ui.values[0]);
                $( "#amount4" ).val(ui.values[1 ]);
             }
         });
         jQuery( "#amount3" ).val($( "#slider-range-year" ).slider( "values", 0 ));
     	 jQuery( "#amount4" ).val($( "#slider-range-year" ).slider( "values", 1 ));
  		 //jQuery("#filter-groupgroup").mCustomScrollbar("vertical",400,"easeOutCirc",1.05,"auto","yes","yes",10);
  		 mCustomScrollbars()

     }
 );
     
 function showFilter(filter){
 	var is_visible = jQuery('#'+filter).is(':visible')?true:false;
 	is_visible?jQuery('#'+filter).hide():jQuery('#'+filter).show();
 	jQuery('#'+filter+'-href').html(is_visible?"Показать":"Скрыть");
 }
 function  clear_filter(filter){
     if(filter){
      jQuery('input[name="'+filter+'[]"]:checked').attr('checked',false); 
     } else {  
         jQuery('.filterbox input').attr('checked',false); 
     }
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
	jQuery("#filter-groupgroup_container").mCustomScrollbar("vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
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