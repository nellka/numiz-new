<?
$r_url = $cfg['site_dir']."new/?module=catalognew&materialtype=$materialtype";

if(isset($filter_groups)&&$filter_groups){
    $minifed_f = isset($_COOKIE['minifed_f'])?$_COOKIE['minifed_f']:0;

    $ahref .= $search?"&search=$search":'';

    $ahref_groups = $ahref;
    
    $ahref_years_p ='';
    $ahref_years ='';
    $ahref_nominals ='';
    
    if($groupMain){
        //$ahref_groups .=contentHelper::groupUrl($GroupName,$groupMain);
        $ahref_groups .='&group='.$groupMain;
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
    	<div class="left">Фильтр каталога</div>
    	<div class="left"><a href="#" onclick="ShowFilterBlock(0);return false;" class="closed">Свернуть фильтр</a></div>
    	<div class="right">
    		<a  href="#" onclick="clear_filter();return false;">Очистить фильтры</a>
    	</div>
    </div> 
    <div class="box-heading" id='filters-closed' style="display:<?=$minifed_f?'block':'none'?>">
    	<div class="left">Фильтр каталога</div>
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

if($materialtype==3&&$tpl['filter']['nominals']){
    include("filter_nominals.tpl.php");
} elseif ($materialtype==3){
} elseif(!$groups&&$materialtype==2) {?>
    <div class="rowf rf-1">
        <? 
        $rf1 = true;
        include("filter_years.tpl.php");
        ?>
    </div>
    <div class="rowf rf-2">
        <? 
        $rf2 = true;
         //include("filter_conditions.tpl.php");
        ?>
    </div>
    <div class="rowf rf-3">
        <? 
        $rf3 = true;
        include("filter_metals.tpl.php");?>
    </div>
<?} elseif (!$groups) {?>
        <div class="rowf rf-1">
        <? 
        $rf1 = true;
        include("filter_years.tpl.php");
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
        //$rf3 = true;
        $show_theme = true;
       // include("filter_conditions.tpl.php");
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
        ?>
    </div>
    <div class="rowf rf-3">
    	<? 
    	$rf3 = true;
    	
    	include("filter_metals.tpl.php");
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

