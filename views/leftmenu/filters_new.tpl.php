<? 

if(isset($filter_groups)&&$filter_groups){
    $minifed_f = isset($_COOKIE['minifed_f'])?$_COOKIE['minifed_f']:0;

    $ahref = $nocheck?"&nocheck=$nocheck":'';
    $ahref .= $search?"&search=$search":'';
    $ahref .= $bydate?"&bydate=$bydate":'';
    $ahref_groups = $ahref;
    
    $ahref_years_p ='';
    $ahref_years ='';
    $ahref_nominals ='';
    
    if($groupMain){
        $ahref_groups .=contentHelper::groupUrl($GroupName,$groupMain);
    } else {
        foreach ((array)$groups as $group){
        	$ahref_groups .='&groups[]='.$group;
        }
    }
    
    foreach ((array)$years_p as $year_p){
    	$ahref_years_p .='&years_p[]='.$year_p;
    }
    foreach ((array)$years as $year){
    	$ahref_years .='&years[]='.$year;
    }
    
    if($nominalMain){
        $ahref_nominals = contentHelper::nominalUrl($nominalMainTitle,$nominalMain);
    } else {
        foreach ((array)$nominals as $nominal){
        	$ahref_nominals .='&nominals[]='.$nominal;
        }
    }

    if($materialtype!=3||($materialtype==3&&$tpl['filter']['nominals'])){
    ?>
    
    
    <div class="box-heading" id='filters-opened' style="display:<?=$minifed_f?'none':'block'?>">
    	<div class="left">Фильтр товаров</div>
    	<div class="left"><a href="#" onclick="ShowFilterBlock(0);return false;" class="closed">Свернуть фильтр</a></div>
    	<div class="right">
    		<a  href="#" onclick="clear_filter();return false;">Очистить фильтры</a>
    	</div>
    </div> 
    <div class="box-heading" id='filters-closed' style="display:<?=$minifed_f?'block':'none'?>">
    	<div class="left">Фильтр товаров</div>
    	<div class="left"><a href="#" onclick="ShowFilterBlock(1);return false;" class="closed">Развернуть фильтр</a></div>
    </div>   
    <?}?>
<div id='f-zone' style="display:<?=$minifed_f?'none':'block'?>">
<?
$show_theme = false;
$show_metal = true;
$show_condition = true;

$rf1 = false;
$rf2 = false;
$rf3 = false;
if($materialtype==11){?>
	 <div class="rowf rf-1">
        <? 
        $rf1 = true;
        //if($tpl['user']['user_id']==352480){
			include("filter_pricelist.tpl.php");
		/*} else {
			include("filter_years.tpl.php");
        	include("filter_prices.tpl.php");
		}*/
        
        ?>
    </div>
    <div class="rf-23">
        <div class="filter-block">
       Монеты описывают пользователи сайта.<br><br>
<b>Клуб Нумизмат несет ответственность ТОЛЬКО за изображения предметов</b>.<br><br>
За каждое описание зачисляется бонус в размере 1 рубль.<br><br>
Эти бонусы (при сумме более 500 рублей) можно потратить на заказы, стоимость которых более 500 рублей. Сотрудники Клуба Нумизмат не смогут вычесть из вашего профайла эти бонусы. !!! Только !!! вы сможите потратить их на заказы, - при оформлении заказа нужно поставить галочку - Оплатить бонусными деньгами.
    	</div>
    </div>
<?} elseif($materialtype==3&&$tpl['filter']['nominals']){
    include("filter_nominals.tpl.php");
} elseif ($materialtype==3){
	
} elseif(!$groups&&$materialtype==2) {?>
    <div class="rowf rf-1">
        <? 
        $rf1 = true;
        include("filter_years.tpl.php");
        include("filter_prices.tpl.php");
        ?>
    </div>
    <div class="rowf rf-2">
        <? 
        $rf2 = true;
         include("filter_conditions.tpl.php");
        ?>
    </div>
    <div class="rowf rf-3">
        <? 
        $rf3 = true;
        include("filter_metals.tpl.php");?>
    </div>
<?} elseif (!$groups&&$materialtype!='newcoins') {?>
        <div class="rowf rf-1">
        <? 
        $rf1 = true;
        include("filter_years.tpl.php");
        include("filter_prices.tpl.php");
        ?>
    </div>
    <div class="rowf rf-2">
        <? 
        $rf2 = true;
        include("filter_metals.tpl.php");
        ?>
    </div>
    <div class="rowf rf-3">
        <? 
        $rf3 = true;
        include("filter_conditions.tpl.php");
        include("filter_thems.tpl.php");?>
    </div>
<?} else {
	$show_condition = false;
	if(!$tpl['filter']['metals']) $show_condition = true; 
	?>
	 <div class="rowf rf-1">
        <? 
        $rf1 = true;
        include("filter_nominals.tpl.php");?>      
    </div>
    <div class="rowf rf-2">
        <? 
        $rf2 = true;
        include("filter_years.tpl.php");
        include("filter_prices.tpl.php");?>
    </div>
    <div class="rowf rf-3">
    	<? 
    	$rf3 = true;
    	
    	include("filter_metals.tpl.php");
    	include("filter_conditions.tpl.php");
    	include("filter_thems.tpl.php");?>
    </div>
<?}?>

<script>
function ShowFilterB(name,on){

	var parentDiv = $('#'+name+'-header-open').closest("div.rowf");
	
	parentDiv.children(".filter-block").each(function(){
		id = $(this).attr('id').substring(3);
		if(id!=name){
			$('#'+id+'-header-open').hide();
			$('#ul-'+id).hide();
			$('#'+id+'-header-close').show();
		}		
	});

	if(on){
		$('#'+name+'-header-open').show();		
		$('#ul-'+name).show();
		full_filter(name,true); 
		$('#'+name+'-header-close').hide();		
	} else {
		$('#'+name+'-header-open').hide();
		$('#ul-'+name).hide();
		$('#'+name+'-header-close').show();
	}
}
</script>

<div id=old>
<?php
/*
foreach ($filter_groups as $k=>$filter_group) { 
        if(!isset($filter_group['filter_group_id_full'])) continue;
        if($filter_group['filter_group_id_full']=='groups') continue;

		if(in_array($filter_group['filter_group_id_full'],array('nominals','years','years_p'))){
			
		}
		if(in_array($filter_group['filter_group_id_full'],array('years','years_p'))){
			$ahref .= $ahref_nominals;
		} ?>

	<div class="filter-block" id='fb-<?=$filter_group['filter_group_id_full']?>'>
		<div class="filter_heading">
			<div style="float:left;"><?=$filter_group['name']?></div>        			
			<div style="float:right;">    			  
				<a class="fc" href="#" onclick="clear_filter('<?=$filter_group['filter_group_id_full']?>');return false;">Сбросить</a>
			</div>
		</div>    
		
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container" class="filter-group<?=$filter_group['filter_group_id']?>_container_<?=(count($filter_group['filter'])>13)?1:0?>">
				<?php 
				foreach ($filter_group['filter'] as $filter) {
					if($filter_group['filter_group_id_full']=='years'){
						//подключаем отдельный вид фильтра  ?>	
						<div class="checkbox">
							<?php  if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
								<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
								<a href="<?=$r_url?>?materialtype=<?=$materialtype?><?=$ahref?>&yearsrart=&yearend"> <?=$filter['name'];?></a>
							<?php } else { ?>
								<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
								<a href="<?=$r_url?>?materialtype=<?=$materialtype?><?=$ahref?>&years[]=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
							  <?}?>
						</div>
						
					<?} else {?>            
						<div class="checkbox">
							<?php            
							 if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
								<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
						   <a href="<?=$r_url?>?materialtype=<?=$materialtype?><?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
							<?php } else { ?>
								<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
						   <a href="<?=$r_url?>?materialtype=<?=$materialtype?><?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter['filter_id']))?>"> <?=$filter['name'];?></a>
							  <?}?>
						</div>
					<?php						
					}
				}?> 							
	
			
			<? if($filter_group['filter_group_id_full']=='years'){
		    $i=0;?>   
		    <div id='years-slider'>
				<div style="font-weight:bold;padding: 15px 0;">
					От <input type="text" id="amount-years0" name="fields_filter[amount-years0][0]" value="0" size="10" disabled/>
					до <input type="text" id="amount-years1" name="fields_filter[amount-years1][1]" value="2015" size="10" disabled/>
				</div>
				<div style="margin:0 5px;margin-top:5px;" id="slider-range-years"></div>	
			</div>		 
		<?}?>       
		</div>
	</ul> 	
	</div>
   <?php     
	if($filter_group['filter_group_id']=='years'){?>
	  <? if($tpl['filter']['price']['max']&&$tpl['filter']['price']['max']!=$tpl['filter']['price']['min']){?>
	    <div class="filter-block" id='filter-price'>		
		
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
							
		<p><div id="slider-range-price" style="margin-left:5px;margin-right:5px;"></div></p>
       </div>	
       <?}?>
	<?}
	
 	}*/?>
 	</div>
 	</div>
<?}?>

<script>

function ShowFilterBlock(on){
    if(on){
        $('#f-zone').show();
        $('#filters-opened').show();
        $('#filters-closed').hide();
        $.cookie('minifed_f', 0);
    } else {
        $('#f-zone').hide();
        $('#filters-opened').hide();
        $('#filters-closed').show();
        $.cookie('minifed_f', 1);
    }
}
</script>

