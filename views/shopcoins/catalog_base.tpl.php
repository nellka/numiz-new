
<div id='products' class="products-cls m-<?=$mycoins?'mycoins':$materialtype?>">

<div class="filter-products-block">
    <?
    include(DIR_TEMPLATE.'leftmenu/filters_new.tpl.php');
    ?>
</div>

<?	
if($tpl['infotext']){?>    
    <div class="filter-products-block"><?=$tpl['infotext']?></div>
<?}

if($tpl['show_short_button']){?>
	<div class="center"><input type="button" value="<?=$tpl['show_short']?"Показать все":"Не показывать купленные ранее"?>" onclick="setSshot()"/></div>
<?}?>
<div>
  <h1 class='catalog'><?=$GroupName?></h1>
  <?include('onpage.tpl.php');?>
</div>

<?
include('nav_catalog.tpl.php');

if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
?>


<div class="product-grid">   
<?   $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){	      
    	if(in_array($materialtype,array(7,4))){
    		echo "<div class='blockshop_spisok' id='item".$rows['shopcoins']."'>";
    		include('items/item_nabor.tpl.php');
    		echo "</div>";
    	} else {
    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
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
	<div>
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

<?
    if($tpl['seo_data']){?>
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
<?

if ($tpl['shop']['OtherMaterialData']) {	?>
	<div>
	<h5>Рекомендуемые товары</h5>
	</div>
	<div class="triger-carusel">	
		  <div class="d-carousel">
          <ul class="carousel">
          
		<?
		foreach ($tpl['shop']['OtherMaterialData'] as $rowsp){
		    $rowsp['gname'] = $groupData["name"];		   
		    $rowsp['metal'] = $tpl['metalls'][$rowsp['metal_id']];		   
		    $rowsp['condition'] = $tpl['conditions'][$rowsp['condition_id']];
		    $rowsp = array_merge($rowsp, contentHelper::getRegHref($rowsp));
		    ?>			
			<li>
			<div class="coin_info" id='item<?=$rowsp['shopcoins']?>'>
				<div id=show<?=$rowsp['shopcoins']?>></div>
			<?	
			$statuses = $shopcoins_class->getBuyStatus($rowsp["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
			$rowsp['buy_status'] = $statuses['buy_status'];
			$rowsp['reserved_status'] = $statuses['reserved_status'];	
			$rowsp['mark'] = $shopcoins_class->getMarks($rowsp["shopcoins"]);
			echo contentHelper::render('shopcoins/item/itemmini-carusel',$rowsp);
            ?>				
			</div>
			</li>
		<?}?>
		</ul>
	</div>
</div>	
<br class="clear:both">
<?}?>

<script type="text/javascript">

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
 		$("#clear_filter_group"+id).remove();
 		sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>');
 	}
 	//var is_visible = jQuery('#'+filter).is(':visible')?true:false;
 	//is_visible?jQuery('#'+filter).hide():jQuery('#'+filter).show();
 	//jQuery('#'+filter+'-href').html(is_visible?"Показать":"Скрыть");
 }
function resetSliderYear() {
 
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
	
	if(filter=='groups'){
		 $("#group_name").val('');
		 $('#f-details').html('');
		 $('#f-details').removeClass('filter-groupdetails_container_1').addClass('filter-groupdetails_container_0');
		 $('#fb-groups .checkbox').each(function(i, elem) {			    	
		    $(elem).show();
		});
		mCustomScrollbars();
		//return;
	}
	
	if(filter){	
		$('input[name="'+filter+'[]"]:checked').attr('checked',false); 
	} else {  
		$('#search-params input').attr('checked',false); 
		$('#f-zone input').attr('checked',false); 
	}
	
	if(filter=='years'||!filter){
		$( "#amount-years0" ).val('<?=$tpl['filter']['yearstart']?>');
		$( "#amount-years1" ).val('<?=$tpl['filter']['yearend']?>');
	}
	if(filter=='price'||!filter){
		$( "#amount-price0" ).val('<?=$tpl['filter']['price']['min']?>');
		$( "#amount-price1" ).val('<?=$tpl['filter']['price']['max']?>');
	}

     sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>');
     return false;
 }

function mCustomScrollbars(){	
	//if($("#filter-groupgroup_container")) $("#filter-groupgroup_container").mCustomScrollbar({theme:"dark-thick"});
	//"vertical",0,"easeOutCirc",1.05,"auto","yes","yes",10);
	if($(".filter-groupgroup_container_1")) $(".filter-groupgroup_container_1").mCustomScrollbar({theme:"dark-thick"});
	
	//console.log(jQuery("#filter-grouptheme_container"));
	if($("#filter-grouptheme_container")) $("#filter-grouptheme_container").mCustomScrollbar({theme:"dark-thick"});
	if($(".filter-groupnominals_container_1")) $(".filter-groupnominals_container_1").mCustomScrollbar({theme:"dark-thick"});
	$(".filter-groupyears_p_container_1").mCustomScrollbar({theme:"dark-thick"});	
	$(".filter-groupcondition_container_1").mCustomScrollbar({theme:"dark-thick"});	
	
	if($(".filter-groupmetals_container_1")) $(".filter-groupmetals_container_1").mCustomScrollbar({theme:"dark-thick"});
	if($(".filter-groupdetails_container_1")) $(".filter-groupdetails_container_1").mCustomScrollbar({theme:"dark-thick"});
	//if($(".filter-groupnominal_container_1")) $(".filter-groupnominal_container_1").mCustomScrollbar({theme:"dark-thick"});
	//console.log(jQuery(".filter-groupnominal_container_1"));	
}




function full_filter(name,auto,hide,setcook) {

	var cookiefull = 0;	
	if($.cookie(name+'-full-show')){
		var cookiefull = $.cookie(name+'-full-show');
	}

	if(auto){
	    //console.log(".filter-group"+name+"_container_1");
	   // console.log(name);
	  //  console.log(name);
	    if(!$(".filter-group"+name+"_container_1").length) {
	        $("#"+name+"-full-show").text("");
	        return;
	    }
	   // console.log(name+','+$(".filter-group"+name+"_container_1").height());
	    // console.log(name+','+$(".filter-group"+name+"_container_1 .mCSB_container").height());
	    if($(".filter-group"+name+"_container_1").height()>$(".filter-group"+name+"_container_1 .mCSB_container").height()){
	        $("#"+name+"-full-show").text("");
	        return;
	    }
	    //console.log(name);
		if(cookiefull>0){
		   
		    $(".filter-group"+name+"_container_1").height("auto");
		    $("#"+name+"-full-show").attr("onClick","full_filter('"+name+"',false,1,1);return false;");
		    $("#"+name+"-full-show").text("Свернуть");	
		    
		    //$("#"+name+"-full-show-top").attr("onClick","full_filter('"+name+"',false,1,1);return false;");
		   // $("#"+name+"-full-show-top").text("Свернуть");	
		    	   
		} else {	
		    	
		   // $(".filter-group"+name+"_container_1").height("290px");
		    $("#"+name+"-full-show").attr("onClick","full_filter('"+name+"',false,0,1);return false;");
		    $("#"+name+"-full-show").text("Развернуть");	    
		    
		    //$("#"+name+"-full-show-top").attr("onClick","full_filter('"+name+"',false,0,1);return false;");
		   // $("#"+name+"-full-show-top").text("Развернуть");	    
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
 
    $(function(){
    	
     $('#search-params input').bind('change',function(){               	
     	if($(this).attr('id')!='group_name'){
     	    $('#search-params input').unbind("change");
     		sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>');
     	}
     });
     
     $('#f-zone input').bind('change',function(){               	
     	if($(this).attr('id')!='group_name'){
     	    $('#search-params input').unbind("change");
     		sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>');
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
        full_filter('condition',true);   
        full_filter('years_p',true);               
    });
    
    function setSshot(){
    	console.log($.cookie('sshort'));
    	if($.cookie('sshort')&&$.cookie('sshort')>0){
			$.cookie('sshort',0);
		} else {
			console.log('set sshort');
			$.cookie('sshort',1);
		}
		window.location.reload();
    }
</script>
