<?if(isset($tpl['filter'])){?>

<form id='search-news' method="POST" action="<?=$cfg["site_dir"]?>news/">
<input type="hidden" id='onpage' name="onpage" value="<?=$tpl['onpage']?>">
<?
    $ahref = $cfg["site_dir"]."news/".($text?"&text=$text":'');
    
    /*$ahref_years =$ahref;
    foreach ((array)$years as $group){
        $ahref_years .='&years[]='.$group;
    }
    $ahref_sp =$ahref;
    foreach ((array)$sp_s as $s){
        $ahref_sp .='&sp_s[]='.$s;
    }*/
    
    ?>

    <div class="box-heading" id='filters-opened'>
    	<div class="left">Фильтр</div>
    	<div class="right">
    		<a  href="#" onclick="clear_filter();return false;">Очистить</a>
    	</div>
    </div>

   <?foreach ($filter_groups as $k=>$filter_group) { 
	if(!isset($filter_group['filter_group_id_full'])) continue;
	
    	
	if($filter_group['filter_group_id_full']=='sp_s'){		
		include("filters/news/filter_sp.tpl.php");
		continue;
	}   
	
	if($filter_group['filter_group_id_full']=='groups'){		
		include("filters/news/filter_country.tpl.php");
		continue;
	}
	
	if($filter_group['filter_group_id_full']=='years'){		
		include("filters/news/filter_years.tpl.php");
		continue;
	}
	
	if($filter_group['filter_group_id_full']=='themes'){		
		include("filters/news/filter_themes.tpl.php");
		continue;
	}/*
	
    ?>
	
	<div class="filter-block" id='fb-<?=$filter_group['filter_group_id_full']?>'>
		<div class="filter_heading">
			<div class="left"><?=$filter_group['name']?></div>
			<?if($filter_group['filter_group_id']=='group'){
				
		      }?>
			<div class="right">    			  
				<a class="fc" href="#" onclick="clear_filter('<?=$filter_group['filter_group_id_full']?>');return false;">Сбросить</a>
			</div>
		</div>
        <?if($filter_group['filter_group_id']=='sp'){?>
            <input type="text" value="<?=$text?>" id='text'  placeholder='Слово для поиска новости' name="text" size="30">
        		<a class="right button25" onclick="$('#search-news').submit();return false" href="#" style="min-width: 25px;">OK</a>
        <?}?>
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
	*/}?>
</form>
<?}?>


<script type="text/javascript">

	function  clear_filter(filter){
		console.log(filter);
		if(filter=='sp_s'){
			$('#filter-groupsp_container input').attr('checked',false);
			$('#text').val("");	
		} else if(filter){
			$('input[name="'+filter+'[]"]:checked').attr('checked',false);
		} else {
			$('#search-news input').attr('checked',false);
			$('#text').val("");			
		}
		console.log($('#text').val());
	    $(".bg_shadow").show();
	    $('#search-news').submit();
		return false;
	}


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
            console.log($('#search-news'));
            $('#search-news').submit();				
        });
        
        full_filter_years();
	});
</script>

