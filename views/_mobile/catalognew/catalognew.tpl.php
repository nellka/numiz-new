<div id='products' class="products-cls m-<?=$materialtype?>">
<?if($tpl['c_hi']){?>
<div class="bordered">Вы находитесь в каталоге монет Клуба Нумизмат (не в <a href=shopcoins target=_blank>Магазине монет</a> )
<br><br>
Здесь вы сможете увидеть актуальную информацию
Цен на монеты, которые продавались в нашем
Магазине монет
<br><br>
Также оставить заявки на монеты, которые вам понравились.
<br><br>
Если Вы хотите купить монеты - тогда ждем Вас в магазине.</div>
<?}?>
<?
if($tpl['catalognew']['user_id']) {?>
    <table width=100% cellpadding=3 cellspacing=0 border=0 bgcolor=#ebe4d4>
	<tr><td bgcolor="#ecb34e" height=1 colspan=2></td></tr> 
	<?       

	if ($tpl['user']['user_id'] == $tpl['catalognew']['user_id']) {
	    
	  if ($usershopcoinssubscribe || $usermycatalog || $usermycatalogchange){?>
          <tr><td colspan=2 class=tboard><a href="<?=$tpl['catalognew']['href']?>" title='Постоянная ссылка на Вашу коллекцию'><?=$tpl['catalognew']['href']?></a></td></tr>
        <?}?>        
      <tr>
		<td class=tboard valign=top>Здравствуйте, <b><?=$tpl['user']['fio']?>!</b>
		<br>Вы зашли под логином: <b><font color=red><?=$tpl['user']["username"]?></font>
		</td>		
		<td valign=top class=tboard><a href="<?=$cfg["site_dir"]?>catalognew/">Каталог</a><br>
		<a href="<?=$cfg["site_dir"]?>catalognew/index.php?usershopcoinssubscribe=<?=$tpl['user']['user_id']?>&materialtype=<?=$materialtype?>">
	         <span  <?=($usershopcoinssubscribe == $tpl['user']['user_id'])?"class='black'":""?>>Мои заявки</font>
	    </a><br>
	    
	    <a href="<?=$cfg["site_dir"]?>catalognew/index.php?usercatalogsubscribe=<?=$tpl['user']['user_id']?>&materialtype=<?=$materialtype?>">
	       <span  <?=($usercatalogsubscribe == $tpl['user']['user_id'])?"class='black'":""?>>Моя подписка</font>
	    <font color=black></font></a><br>
	    
	   <a href="<?=$cfg["site_dir"]?>catalognew/index.php?usermycatalog=<?=$tpl['user']['user_id']?>&materialtype=<?=$materialtype?>">
	       <span  <?=($usermycatalog == $tpl['user']['user_id'])?"class='black'":""?>>Моя коллекция</font>
	    <font color=black></font></a>| 
	    <a href="<?=$cfg["site_dir"]?>catalognew/excelmycatalog.php" target=_blank><img src="<?=$cfg["site_dir"]?>images/excel.gif" alt='Экспорт своей коллекции в Excel' border=0></a>
	    <br>
	    
	    <a href="<?=$cfg["site_dir"]?>catalognew/index.php?usermycatalogchange=<?=$tpl['user']['user_id']?>&materialtype=<?=$materialtype?>">
	       <span  <?=($usermycatalogchange == $tpl['user']['user_id'])?"class='black'":""?>>На обмен</font>
	    <font color=black></font></a><br>
	    <?	   
    }   else  {
        if ($usershopcoinssubscribe || $usermycatalog || $usermycatalogchange){?>
          <tr><td colspan=2 class=tboard><a href="<?=$tpl['catalognew']['href']?>" title='Постоянная ссылка на Вашу коллекцию'><?=$tpl['catalognew']['href']?></a></td></tr>
        <?} ?>       	
		<tr>
		  <td class=tboard valign=top>Коллекция: <b><font color=#3366CC size=+1><?=$tpl['catalognew']['user']["userlogin"]?></font><br>Вы всегда можете создать свою коллекцию</td>
		 <td valign=top class=tboard>
         <a href="<?=$cfg["site_dir"]?>catalognew/">Каталог Клуба</a><br>	

         <a href="<?=$cfg["site_dir"]?>catalognew/index.php?usershopcoinssubscribe=<?=$tpl['catalognew']['user_id']?>&materialtype=<?=$materialtype?>">
	         <span  <?=($usershopcoinssubscribe == $tpl['catalognew']['user_id'])?"class='black'":""?>>Заявки пользователя</font>
	    </a><br>	    
	 
	   <a href="<?=$cfg["site_dir"]?>catalognew/index.php?usermycatalog=<?=$tpl['catalognew']['user_id']?>&materialtype=<?=$materialtype?>">
	       <span  <?=($usermycatalog == $tpl['catalognew']['user_id'])?"class='black'":""?>>Коллекция пользователя</font>
	    <font color=black></font></a><br>
	    
	    <a href="<?=$cfg["site_dir"]?>catalognew/index.php?usermycatalogchange=<?=$tpl['catalognew']['user_id']?>&materialtype=<?=$materialtype?>">
	       <span  <?=($usermycatalogchange == $tpl['catalognew']['user_id'])?"class='black'":""?>>На обмен</font>
	    <font color=black></font></a><br>
	    
	    
    <?}?>   
    </td>
	</tr>
		
	<tr><td bgcolor="#ecb34e" height=1 colspan=2></td></tr>
	</table><br>
	

<?
if($tpl['catalognew']['testaddcoins']){?>
    <a href="<?=$cfg["site_dir"]?>catalognew/addcoins.php" target=_blank>Добавить монету</a><br><br>
<?}


}
?>

<div class="table">    
        <div class='left marg-10'><a href=<?=$cfg['site_dir']?>catalognew/about.php title='Подробно о каталоге монет, банкнот'>О каталоге</a></div>
        <div class='left marg-10'> | </div>
        <div class='left marg-10'><a href=<?=$cfg['site_dir']?>catalognew/how.php title='Как добавить монету, банкноту в каталог'>Как добавить</a></div>
        <div class='left marg-10 '> | </div>
        <div class='left marg-10'><a href=<?=$cfg['site_dir']?>catalognew/faq.php title='Вопросы по работе каталога монет, банкнот'>Вопрос-ответ</a></div>
</div>
<div style="position:relative">
<div class=ftl>
<a href="#" onclick="showInvis('search-params-place');return false;" class="left">Фильтры <span class="l-f">(страны, номиналы, года)</span></a>
<a href="#" onclick="showInvis('sorting-place');return false;" class="right">Сортировка</a>
</div>

	<div id='search-params-place'  class="search-params-place">
	
	<?include(DIR_TEMPLATE.'_mobile/leftmenu/filters.tpl.php');?>
	
	</div>
	<div id='sorting-place' class="sorting-place">
	<?include(DIR_TEMPLATE.'_mobile/catalognew/nav_catalog.tpl.php');/*?>
	
	<div id='order' class="orderby" style="padding-left:5px;">
			<div class="sort"><b>Сортировать по:</b>
			<?php 
	
			foreach ($tpl['pager']['sorts'] as $key=>$sort) { 
			    if($key=='price') continue;
				echo '<span>'.$sort;
				foreach (array('asc','desc') as $v){			    
					$orderBy = $key.$v;
					if($orderBy==$tpl['orderby']){?>
						<img style="cursor:pointer;" alt='' onclick="sendDataCatalog('orderby','<?=$orderBy?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedowncolor.jpg':'priceupcolor.jpg'?>" >				  
					<?} else {?>
						<img style="cursor:pointer;" alt='' onclick="sendDataCatalog('orderby','<?=$orderBy?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedown.jpg':'priceup.jpg'?>">
					<?}
				}
				echo '</span>';
			} ?>       
		</div>
	</div>*/?>
	</div>
</div>

<div class="w-100 table">
<? if($GroupName){?>
  <h1 class='catalog'><?=$GroupName?></h1> 
  <div class=right> 
  <?if($tpl['is_Subscribe_for_group']){?> 
        <a class="gsubs"  href="<?=$cfg["site_dir"]?>new/?module=catalognew&usercatalogsubscribe=<?=$tpl['user']['user_id']?>&materialtype=<?=$materialtype?>">Вы подписаны на группу <?=$GroupName?></a>
  <?} elseif($tpl['user']['user_id']) {?>
      <a href=# class="gsubs" onclick="ga('send', 'event', 'catalog', 'groubsubscribe');showWin('http://www.numizmatik.ru/new/?module=catalognew&task=subscribe&group=<?=$group?>&ajax=1','450');return false;">Подписаться на группу <?=$GroupName?></a>
 <?}?>
  </div>
<?}?>
</div>

  <?include('onpage.tpl.php');?>


<div class="pager">	
	<div class="right">
    	 <?php echo $tpl['paginator']->printPager(); ?>
    </div>	
</div>

<?
if($tpl['catalognew']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['catalognew']['errors'])?></font>
<?} else {
?>


<div class="product-grid catalognew">   
<?  
    foreach ($tpl['catalognew']['MyShowArray'] as $key=>$rows){	
    	if(in_array($materialtype,array(7,4))){
    		echo "<div class='blockshop_spisok' id='item".$rows['shopcoins']."'>";
    		include(DIR_TEMPLATE.'_mobile/catalognew/items/item_nabor.tpl.php');
    	} else {
    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
    		<div class='blockshop-full'>";
    		include(DIR_TEMPLATE.'_mobile/catalognew/items/item.tpl.php');
    		echo "</div>";
    		
    	}	
    	if ($usermycatalog || $usermycatalogchange){
            if (($tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]]>0 && 
                $tpl['catalognew']['detailsmycatalog'][$rows["catalog"]]) &&
                 ($usermycatalog==$tpl['catalognew']['user_id'] or $usermycatalogchange==$tpl['catalognew']['user_id'])) {
                echo "<div id=detailschange".$tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]].">";
                if ($tpl['catalognew']['typemycatalog'][$rows["catalog"]]>0){
                    echo "<br><table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #FF0000' width=100%>".
                    "<tr class=tboard><td bgcolor=#EBE4D4><b><font color=red>Для обмена </font></b>".
                    ($tpl['catalognew']['detailsmycatalog'][$rows["catalog"]]?"<br>".$tpl['catalognew']['detailsmycatalog'][$rows["catalog"]]:"")."</td></tr></table>";
                }
                echo "</div>";

            }
        }  
        echo "</div>";
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
	<div class="seo">
	<h2><?=$tpl['seo_data']['title']?></h2>
	<?=$tpl['seo_data']['text']?>
	</div>
	<br class="clear:both">
<?} elseif(isset($tpl['GroupDescription'])&&$tpl['GroupDescription']) {?>
	<div class="seo" class="clearfix">	
	<?=$tpl['GroupDescription']?>
	</div>
	<br class="clear:both">
<?}?>

<br class="clear:both">

<?

if ($tpl['catalognew']['OtherMaterialData']) {	?>
	<div>
	<h5>Рекомендуемые товары</h5>
	</div>
	<div class="triger-carusel">	
		  <div class="d-carousel">
          <ul class="carousel">
          
		<?
		
		foreach ($tpl['catalognew']['OtherMaterialData'] as $rowsp){
		    //$rowsp['gname'] = $groupData["name"];		   
		    $rowsp['metal'] = $tpl['metalls'][$rowsp['metal_id']];		   
		    $rowsp['condition'] = $tpl['conditions'][$rowsp['condition_id']];
		    $rowsp = array_merge($rowsp, contentHelper::getRegHref($rowsp));
		    
		     //$rowsp["rehrefdubdle"]= "?materialtype=".$rows["materialtype"]."&group=$group&search=".urlencode($rows["name"]);
		     //$rowsp["rehref"] = "?materialtype=".$rows["materialtype"]."&group=$group&search=".urlencode($rows["name"]);
		    //var_dump(contentHelper::getRegHref($rowsp));
		    //http://www.numizmatik.ru/shopcoins/?materialtype=".$rows["materialtype"]."&group=$group&search=".urlencode($rows["name"])
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
<div style="display:none;" class="frame-form" id="coinchange">
    <h1 class="yell_b">Описание (макс. 255 симв.)</h1>    
    <form id="ChangeCoin" name="ChangeCoin" method="post" action="#">
        <input type="hidden" value="" name="catalognewmycatalog" id="catalognewmycatalog">
        <span id="errorChangeCoin" class="error"></span>
        <div class="web-form">
        <div class="left"> <label>Тип:</label></div>
        <div class="right">
        <select name=typechange id=typechange class=formtxt>
        <option value=0>В коллекции</option>
        <option value=1 selected>На обмен</option>
        </select>
        </div>
        </div>    	
        <div class="web-form">
        <div>
        <label>Описание (макс. 255 симв.):</label>	
        </div>
        </div>    
        <div class="web-form">
        <textarea rows="4" cols="40" class="formtxt" name="detailschange" id="detailschange"></textarea>
        </div>
        <div class="web-form">
        <input type="button" onclick="AddForChange()" value="Отправить" class="yell_b">
        </div>	 
    </form>   
</div>
<script type="text/javascript">

 jQuery(document).ready(function() {       	
    
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
 		sendDataCatalog(null,null,'<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>');
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

     sendDataCatalog(null,null,'<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>');
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
     		sendDataCatalog(null,null,'<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>');
     	}
     });
     
     $('#f-zone input').bind('change',function(){               	
     	if($(this).attr('id')!='group_name'){
     	    $('#search-params input').unbind("change");
     		sendDataCatalog(null,null,'<?=$tpl['filter']['yearstart']?>','<?=$tpl['filter']['yearend']?>');
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