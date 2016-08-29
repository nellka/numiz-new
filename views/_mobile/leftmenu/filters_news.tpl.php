<?if(isset($tpl['filter'])){?>

<form id='search-news' method="POST" action="<?=$r_url?>">
<input type="hidden" id='onpage' name="onpage" value="<?=$tpl['onpage']?>">
<?
    $ahref = $r_url.($text?"&text=$text":'');
    
    $ahref_years =$ahref;
    foreach ((array)$years as $group){
        $ahref_years .='&years[]='.$group;
    }
    $ahref_sp =$ahref;
    foreach ((array)$sp_s as $s){
        $ahref_sp .='&sp_s[]='.$s;
    }
    
    ?>

    <div  class="box-heading ">
    	<div class='left'>Фильтр новостей</div>
    	<div class='clearf'>
			<a  href="#" onclick="clear_filter();return false;">Очистить</a>
		</div>
		
    	<div class='right'>
    		<a onclick="$('#search-params-place').hide();return false;" href="#" class="closef"></a>
    	</div>
    	
    </div>
    <br style="clear: both;">

   <?foreach ($filter_groups as $k=>$filter_group) { 
	if(!isset($filter_group['filter_group_id_full'])) continue;
    ?>
	
	<div class="filter-block" id='fb-<?=$filter_group['filter_group_id_full']?>'>
		<div class="filter_heading">
			<div class="left"><?=$filter_group['name']?></div>			
			<div class="right">    			  
				<a class="fc" href="#" onclick="clear_filter('<?=$filter_group['filter_group_id_full']?>');return false;">Сбросить</a>
			</div>
		</div>
        <?if($filter_group['filter_group_id']=='sp'){?>
            <ul class="filter_heading_ul">
            	<input type="text" value="<?=$text?>" id='text'  placeholder='Слово для поиска новости' name="text" size="30">
        		<a class="right button25" onclick="$('#search-news').submit();return false" href="#" style="min-width: 25px;">OK</a>
        	</ul>
        <?}		
		?>
		<!--class="filter-group<?=$filter_group['filter_group_id']?>_container_<?=(count($filter_group['filter'])>13)?1:0?>"  style=" height: auto;"-->
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container">			
			<?php 
			foreach ($filter_group['filter'] as $filter) {?>            
				<div class="checkbox">
				<?php            
				if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
					<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
				   <a href="<?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
				<? } else { ?>
				   <input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
				   <a href="<?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter['filter_id']))?>"> <?=$filter['name'];?></a>
				<?}?>
				</div>
			<?php				
			}?>  
			</div>
			
		</ul> 
		  <?if($filter_group['filter_group_id']=='years'){?> 
		      <div class="center">    	
    			    <a class="fc" id='years-full-show' href="#" onclick="full_filter_years(1);return false;"></a></div>    		
    	   <?}?>
		</div>
	<?php 	
	}?>
</form>
<?}?>


<script type="text/javascript">

function  clear_filter(filter){	
	if(filter){
		$('input[name="'+filter+'[]"]:checked').attr('checked',false);
	} else {
		$('#search-news input').attr('checked',false);
		$('#text').val();
	}
    $(".bg_shadow").show();
    $('#search-news').submit();
	return false;
}
		
/*jQuery(document).ready(function() {    
   mCustomScrollbars();
});

function mCustomScrollbars(){	
	if($(".filter-groupgroup_container_1")) $(".filter-groupgroup_container_1").mCustomScrollbar({theme:"dark-thick"});    	
	if($("#filter-grouptheme_container")) $("#filter-grouptheme_container").mCustomScrollbar({theme:"dark-thick"});    	
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


*/

		function full_filter_years(click) {
		    name = 'years';
			if(click){
			     if($('#filter-groupyears_container').height()==55){
			        $("#filter-groupyears_container").height("auto");
    				$("#"+name+"-full-show").text("Свернуть");
    			} else {			    	   
    				$("#"+name+"-full-show").text("Развернуть");
    				$("#filter-groupyears_container").height("55");
    			}
    			
			} else {
			    if($('#filter-groupyears_container').height()==55){			  
    				$("#"+name+"-full-show").text("Развернуть");
    			} else {			    	   
    				$("#"+name+"-full-show").text("Свернуть");
    			}
			}
		}

		$(function(){
            $('#search-news input').bind('change',function(){
                $(".bg_shadow").show();
                $('#search-news').submit();				
            });
            
            full_filter_years();
		});
	</script>

