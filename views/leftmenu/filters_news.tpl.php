<?if(isset($tpl['filter'])){?>

<form id='search-news' method="POST" action="<?=$cfg['site_dir']?>news">
<?
    $ahref = $cfg['site_dir'].'news'.($text?"&text=$text":'');
    
    $ahref_years =$ahref;
    foreach ((array)$years as $group){
        $ahref_years .='&years[]='.$group;
    }
    $ahref_sp =$ahref;
    foreach ((array)$sp_s as $s){
        $ahref_sp .='&sp_s[]='.$s;
    }
    
    ?>

    <div class="box-heading" id='filters-opened'>
    	<div class="left">Фильтр</div>
    	<div class="right">
    		<a  href="#" onclick="clear_filter();return false;">Очистить</a>
    	</div>
    </div>

   <?foreach ($filter_groups as $k=>$filter_group) { 
	if(!isset($filter_group['filter_group_id_full'])) continue;
    ?>
	
	<div class="filter-block" id='fb-<?=$filter_group['filter_group_id_full']?>'>
		<div class="filter_heading">
			<div class="left"><?=$filter_group['name']?></div>
			<?if($filter_group['filter_group_id']=='group'){/*?> 
		      <div class="left" style="padding: 0 40px;">    	
    		   <a class="fc" id='group-full-show-top' href="#" onclick="full_filter('<?=$filter_group['filter_group_id_full']?>');return false;"></a>
    		  </div>    		
    	   <?*/}?>
			<div class="right">    			  
				<a class="fc" href="#" onclick="clear_filter('<?=$filter_group['filter_group_id_full']?>');return false;">Сбросить</a>
			</div>
		</div>
        <?if($filter_group['filter_group_id']=='sp'){?>
            <input type="text" value="<?=$text?>" id='text'  placeholder='Слово для поиска' name="text" size="30">
        		<a class="right button25" onclick="$('#search-news').submit();return false" href="#" style="min-width: 25px;">OK</a>
        <?}

		if($filter_group['filter_group_id']=='group'){/*?> 
			<div id='f-details'>		
    		<?
    		foreach ($filter_groups['group_details'] as $group_id=>$group_name){?>
    			<a href="#" class="filtr-g-d" onclick="clear_filter_group('<?=$group_id?>');return false;"><?=$group_name?> - X</a>     
    		<?}?>
    		</div>
    		<input type="text" value="" id='group_name' placeholder='Название страны' name="group_name" size="30">

		<?*/}//
		?>
		<!--class="filter-group<?=$filter_group['filter_group_id']?>_container_<?=(count($filter_group['filter'])>13)?1:0?>"-->
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container" style=" height: auto;">			
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
		  <?if($filter_group['filter_group_id']=='group'){?> 
		      <div style="text-align:center;padding: 10px 0 0;">    	
    			    <a class="fc" id='group-full-show' href="#" onclick="full_filter('<?=$filter_group['filter_group_id_full']?>');return false;"></a></div>    		
    	   <?}?>
		</div>
	<?php 	
	}?>
   <? /*if(isset($tpl['filter']['years'])&&$tpl['filter']['years']){?>
    <div id='fb-<?=$tpl['filter']['years']['filter_group_id_full']?>' class="filter-block">
        <div class="filter_heading">
            <div class="left">Года</div>
            <div class="right"><a class="fc" href="#" onclick="clear_filter('<?=$tpl['filter']['years']['filter_group_id_full']?>');return false;">Сбросить</a></div>
        </div>

        <ul class="filter_heading_ul">
            <div id="filter-group<?=$tpl['filter']['years']['filter_group_id']?>_container">
                <?php
                foreach ($tpl['filter']['years']['filter'] as $filter) {?>
                        <div class="checkbox">
                            <input type="checkbox" name="years[]" value="<?=$filter['filter_id']?>" <?=(is_array($years)&&in_array($filter['filter_id'], $years))?"checked":""?> />
                            <a href="<?=$cfg['site_dir']?>/news/<?=$ahref_sp?>&year=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
                        </div>
                <?}?>

            </div>
        </ul>
    </div>
    <?}?>
    
     <div class="filter-block" id='fb-groups'>
		<div class="filter_heading">
			<div class="left">Поиск</div>            		
			<div class="right">    			  
				<a class="fc" href="#" onclick="clear_filter('search');return false;">Сбросить</a>
			</div>
		</div>
				
		<input type="text" value="<?=$text?>" id='text'  placeholder='Слово для поиска' name="text" size="30">
		<a class="right button25" onclick="$('#search-news').submit();return false" href="#" style="min-width: 25px;">OK</a>

		<ul class="filter_heading_ul">
			<div id="filter-groupgroup_container" >
				<?php 
				foreach ($tpl['filter']['search']['filter'] as $filter) {?>            
					<div class="checkbox">
						<?php            
						 if (in_array($filter['filter_id'], $sp_s)) { ?>
							<input type="checkbox" name="sp_s[]" value="<?=$filter['filter_id']?>" checked="checked" />
					       <a href="<?=$cfg['site_dir']?>/news/<?=$ahref_years?>&sp=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
						<?php } else { ?>
							<input type="checkbox" name="sp_s[]" value="<?=$filter['filter_id']?>" />
					       <a href="<?=$cfg['site_dir']?>/news/<?=$ahref_years?>&sp=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
						  <?}?>
					</div>
				    <?php
					
				}		
			</div>	
        </ul>    
    </div> */?>   
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

		}*/

		$(function(){
            $('#search-news input').bind('change',function(){
                $(".bg_shadow").show();
                $('#search-news').submit();				
            });
		});
	</script>

