
<div id='products' class="products-cls m-<?=$mycoins?'mycoins':$materialtype?>">

<div class="filter-products-block">
    <?
    include(DIR_TEMPLATE.'leftmenu/filters/price/filters_new.tpl.php');
    ?>
</div>

<?	
include('onpage.tpl.php');
include('nav_catalog.tpl.php');

if($tpl['price']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['price']['errors'])?></font>
<?} else {?>
	
<div  class="bordered">
<div class="center"><a href="<?=$cfg['site_dir']?>gde-prodat-monety"><img src=http://www.numizmatik.ru/images/468-200.gif>
<br>
Клуб Нумизмат бесплатно оценивает и покупает монеты <?=($rows_main["gname"]?$rows_main["gname"]:($GroupName?$GroupName:" >>>"))?></a></div>
<br><br>

<?if(!$WhereParams) {?>

	&nbsp;&nbsp;&nbsp;&nbsp;Вас интересует стоимость монеты? Вы не можете определиться с ее ценой? Что бы помочь вам в этом вопросе мы открыли этот сервис. В данном разделе предоставлена информация о стоимости монет России и СССР. При формировании раздела "Стоимость монет" использовались материалы с популярных нумизматических аукционов. Интересующийся пользователь может увидеть изображения монет, цену их продажи и дату окончания торгов, а также посмотреть их проход на аукционе. Для полного доступа к информации раздела необходимо быть авторизованным пользователем. Надеемся что предложенный нами сервис окажется для вас полезен. <br><br>
	 При формировании раздела "Стоимость монет" использовались материалы с популярных нумизматических аукционов (<noindex><a href='http://auction.conros.ru/' rel="nofollow" target=_blank>Конрос</a></noindex>, <noindex><a rel="nofollow" href='http://www.wolmar.ru' target=_blank>Волмар</a></noindex>)
<?}?>
</div>

<div id='pager' class="pager<?=isset($prefix)?$prefix:""?>">
	<div id='order' class="orderby" style="padding-left:5px;">
		<div class="sort"><b>Сортировать по:</b>
		<?php 

		foreach ($tpl['pager']['sorts'] as $key=>$sort) { 
			echo '<span>'.$sort;
			foreach (array('asc','desc') as $v){
				$orderBy = $key.$v;
				if($orderBy==$tpl['orderby']){?>
					<img style="cursor:pointer;" alt='' onclick="sendData('orderby','<?=$orderBy?>','<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedowncolor.jpg':'priceupcolor.jpg'?>" >				  
				<?} else {?>
					<img style="cursor:pointer;" alt='' onclick="sendData('orderby','<?=$orderBy?>','<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedown.jpg':'priceup.jpg'?>">
				<?}
			}
			echo '</span>';
		} ?>       
	</div>
	<div class="right">
		 <?php echo $tpl['paginator']->printPager(); ?>
	</div>	
</div>
</div>

<div class="product-grid">   
<?   $i=1;
    foreach ($tpl['price']['MyShowArray'] as $key=>$rows){	      
    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
    		<div class='blockshop-full'>";
    		include('items/item.tpl.php'); 	
			echo "</div>
    		</div>";
    	$i++;	
    }?>
</div>

<?}?>

<div style="width: 100%; display: table;">
	<div class="right">
		 <?php echo $tpl['paginator']->printPager(); ?>
	</div>	
</div>

<?
if($tpl['seo_data']){?>
<br>
<div class="seo" class="clearfix">
<h5><?=$tpl['seo_data']['title']?></h5>
<?=$tpl['seo_data']['text']?>
</div>
<br class="clear:both">
<?} elseif(isset($tpl['GroupDescription'])&&$tpl['GroupDescription']) {?>
	<div class="seo" class="clearfix">	
	<?=$tpl['GroupDescription']?>
	</div>
	<br class="clear:both">
<?}?>

<br><p class=bordered>
<a href="<?=$cfg['site_dir']?>gde-prodat-monety">Клуб Нумизмат бесплатно оценивает и занимается скупкой монет <?=($rows_main["gname"]?$rows_main["gname"]:($GroupName?$GroupName:" >>>"))?></a>
</p>

<script type="text/javascript" charset="utf-8">

 jQuery(document).ready(function() {    
     	$(".blockshop").on("hover", function(e) {
    	    if (e.type == "mouseenter") {
    	    	if($(this).find(".qwk")) $(this).find(".qwk").show();
    	    } else { // mouseleave
    	        if($(this).find(".qwk")) $(this).find(".qwk").hide();
    	    }
    	});
    
         $('.d-carousel .carousel').jcarousel({
            scroll: 1,
            itemFallbackDimension: 75
         });

  		 mCustomScrollbars();
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
}


 function  clear_filter(filter,clname){
 	if(filter=='group_name'){
 	    if(!clname){
 		 $("#group_name").val('');
 	    }
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
	if($(".filter-groupgroup_container_1")) $(".filter-groupgroup_container_1").mCustomScrollbar({theme:"dark-thick"});
	if($("#filter-groupsimbol_container")) $("#filter-groupsimbol_container").mCustomScrollbar({theme:"dark-thick"});
	if($(".filter-groupnominals_container_1")) $(".filter-groupnominals_container_1").mCustomScrollbar({theme:"dark-thick"});
	$(".filter-groupyears_p_container_1").mCustomScrollbar({theme:"dark-thick"});	
	$(".filter-groupcondition_container_1").mCustomScrollbar({theme:"dark-thick"});	
	
	if($(".filter-groupmetals_container_1")) $(".filter-groupmetals_container_1").mCustomScrollbar({theme:"dark-thick"});	

}

function full_filter(name,auto,hide,setcook) {

	var cookiefull = 0;	
	if($.cookie(name+'-full-show')){
		var cookiefull = $.cookie(name+'-full-show');
	}

	if(auto){
	    if(!$(".filter-group"+name+"_container_1").length) {
	        $("#"+name+"-full-show").text("");
	        return;
	    }

	    if($(".filter-group"+name+"_container_1").height()>$(".filter-group"+name+"_container_1 .mCSB_container").height()){
	        $("#"+name+"-full-show").text("");
	        return;
	    }
		if(cookiefull>0){		   
		    $(".filter-group"+name+"_container_1").height("auto");
		    $("#"+name+"-full-show").attr("onClick","full_filter('"+name+"',false,1,1);return false;");
		    $("#"+name+"-full-show").text("Свернуть");			    
		   
		} else {			    	
		    $("#"+name+"-full-show").attr("onClick","full_filter('"+name+"',false,0,1);return false;");
		    $("#"+name+"-full-show").text("Развернуть");			 
		}   
	} else {			
		if(!hide){
		    $(".filter-group"+name+"_container_1").height("auto");
		    if(setcook) $.cookie(name+'-full-show', 1);
		    $("#"+name+"-full-show").attr("onClick","full_filter('"+name+"',false,1,1);return false;");
		    $("#"+name+"-full-show").text("Свернуть");		   
		} else {			
		   $(".filter-group"+name+"_container_1").removeAttr('style');
		    if(setcook)  $.cookie(name+'-full-show', 0);
		    $("#"+name+"-full-show").attr("onClick","full_filter('"+name+"',false,0,1);return false;");
		    $("#"+name+"-full-show").text("Развернуть");	
		    $('html, body').animate({
                scrollTop: $("#search-params").offset().top
            }, 1000);	    
		}        
	}
  
}
 
    $(function(){
    	
     $('#search-params input').bind('change',function(){               	
     	if($(this).attr('id')!='group_name'){
     	    $('#search-params input').unbind("change");
     		sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');
     	}
     });
     
     $('#f-zone input').bind('change',function(){               	
     	if($(this).attr('id')!='group_name'){
     	    $('#search-params input').unbind("change");
     		sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');
     	}
     });
     
     <?if($tpl['user']['showfullgrouplist']) {?>
     	full_filter('groups',false,0,0)    
     <?} else {?>     
     	full_filter('groups',true)    
     <?}?>
        full_filter('metals',true);  
        full_filter('theme',true);   
        full_filter('nominals',true); 
        full_filter('conditions',true);                 
    });
</script>
