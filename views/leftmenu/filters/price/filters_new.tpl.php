<?php
if(isset($filter_groups)&&$filter_groups){
    ?>    

<div id='f-zone' style="display:<?=$minifed_f?'none':'block'?>">
<?php
$show_simbols = false;
$show_metal = true;
$show_condition = true;

$rf1 = false;
$rf2 = false;
$rf3 = false;

if (!$groups) {/*?>
        <div class="rowf rf-1">
        <?php 
        $rf1 = true;
        include("filter_years.tpl.php");
        //include("filter_prices.tpl.php");
        ?>
    </div>
    <div class="rowf rf-2">
        <?php 
        $rf2 = true;
        include("filter_metals.tpl.php");
        ?>
    </div>
    <div class="rowf rf-3">
        <?php 
        //$rf3 = true;
        $show_simbols = true;
        include("filter_conditions.tpl.php");
        include("filter_simbols.tpl.php");?>
    </div>
<?php */} else {/*
	//$show_condition = false;
	//if(!$tpl['filter']['metals']) $show_condition = true; 
	?>
	 <div>
        <?php 
        $rf1 = true;
        include("filter_nominals.tpl.php");?>      
    </div>
    <div class="rowf rf-2">
        <?php 
        $rf2 = true;
        include("filter_years.tpl.php");
        //include("filter_prices.tpl.php");
        ?>
    </div>*/?>
    <div>
    	<?php    	
    	include("filter_metals.tpl.php");
    	include("filter_years.tpl.php");?>
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

