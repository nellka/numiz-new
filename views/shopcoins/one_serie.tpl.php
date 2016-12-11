
<div id='product-in-series' class="product-in-series">
	
<div class="filter-products-block">
    <?   
    include(DIR_TEMPLATE.'leftmenu/filters/series/filters_new.tpl.php');
    ?>
</div>

<div id='serie-<?=$id?>' class="series-one">
    <div id='pager' class="pager_s">
    
		<div id='order' class="orderby" style="padding-left:5px;">
			<div style="float:left;line-height:40px">
			Товаров на странице:
			 <?foreach ($tpl['pager']['itemsOnpage'] as $k=>$v){
			 	
				if($k==$tpl['onpage']){?>
					<button type="button" class="button15active" onclick="location='<?=$base_url?>&onpage=<?=$k?>'"><?=$v?></button>
				<?} else { ?>
					<button type="button" class="button15" onclick="location='<?=$base_url?>&onpage=<?=$k?>'"><?=$v?></button>
				<?}
			 }?>   
			</div>
	    </div>
	    <div id='order' class="orderby" style="padding-left:5px;">
			<div class="sort" style="padding-left:15px;"><b>Сортировать по:</b>
			<?php 
	
			foreach ($tpl['pager']['sorts'] as $key=>$sort) { 
				echo '<span>'.$sort;
				foreach (array('asc','desc') as $v){
					$orderBy = $key.$v;
					if($orderBy==$tpl['orderby']){?>
						<img style="cursor:pointer;" onclick="location='<?=$base_url?>&orderby=<?=$orderBy?>'" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedowncolor.jpg':'priceupcolor.jpg'?>" border="">				  
					<?} else {?>
						<img style="cursor:pointer;" onclick="location='<?=$base_url?>&orderby=<?=$orderBy?>'" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedown.jpg':'priceup.jpg'?>" border="">
					<?}
				}
				echo '</span>';
			} ?>       
		</div>
	</div>
	<div id='pages' class="pages_search right">
    		 <?php echo $tpl['paginator']->printPager(); ?>
	</div>
</div>
<?if(!$id){?>
	<font color="red">Серия монет не найдена</font>
<?} elseif(!count($tpl['one_series']['data'])){?>
	<font color="red">Нет монет в данной серии</font>
<?} else {?>    

      
    <div class="product-grid search-div">

<?   foreach ($tpl['one_series']['data'] as $key=>$rows){	      
	    
		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
		<div class='blockshop-full'>";
		include('items/item.tpl.php');
		echo "</div>
		</div>";
    }?>
    </div>
    <div id='pages' class="pages_search right">
    		 <?php echo $tpl['paginator']->printPager(); ?>
	</div>
   <br style="clear: both;"> 
    <div class="seo table" class="clearfix">
    <h5><?=$tpl['one_series']['group']['name']?> -  <?=$tpl['one_series']['name']?></h5>
    <?=$tpl['one_series']['details']?>
    </div>

  
<? } ?>
<br style="clear: both;">
</div>

<script>

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
	//$(".filter-groupnominal_container").mCustomScrollbar("update");
  
}

 jQuery(document).ready(function() {    
 	$('.filter-products-block input').bind('change',function(){               	
     	if($(this).attr('id')!='group_name'){
     	    $('#search-params input').unbind("change");
     		 //$(".bg_shadow").show();
       		 //$('#search-params').submit();	
       		 sendDataSeries();		
     	}
    });
	if($(".filter-groupnominals_container_1")) $(".filter-groupnominals_container_1").mCustomScrollbar({theme:"dark-thick"});
	$(".filter-groupyears_p_container_1").mCustomScrollbar({theme:"dark-thick"});	
	$(".filter-groupcondition_container_1").mCustomScrollbar({theme:"dark-thick"});		
	if($(".filter-groupmetals_container_1")) $(".filter-groupmetals_container_1").mCustomScrollbar({theme:"dark-thick"});
		 //full_filter('metals',true);  
        //full_filter('theme',true);   
        full_filter('nominals',true); 
       // full_filter('condition',true);   
        full_filter('years_p',true); 
 });
</script>