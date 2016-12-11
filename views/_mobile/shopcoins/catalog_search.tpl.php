<h5>Результаты поиска: <?=$search?></h5>
<div id='products'>
<?

if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
?>
<div class="product-grid search-div">
<?
    $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){		
    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>";
    		include(DIR_TEMPLATE.'_mobile/shopcoins/items/item.tpl.php');
    		echo "</div>";
    	$i++;	
    }?>
</div>
<?}?>

<div id='pages' class="pages">
    	<div class="pages">
    		 <?php echo $tpl['paginator']->printPager(); ?>
    	</div>	
	</div>
</div>

<script type="text/javascript" charset="utf-8">

 jQuery(document).ready(function() {    
 	$(".blockshop").on("hover", function(e) {
	    if (e.type == "mouseenter") {
	    	if($(this).find(".qwk")) $(this).find(".qwk").show();
	    } else { // mouseleave
	        if($(this).find(".qwk")) $(this).find(".qwk").hide();
	    }
	});
	
	//showInvis('searchblock');
});
</script>