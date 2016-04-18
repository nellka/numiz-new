<div id='products'>
<?

if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
?>
<br style="clear: both;">
<div class="product-grid search-div">
<?
    $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){		
    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
    		<div class='blockshop-full'>";
    		include('items/item.tpl.php');
    		echo "</div>
    		</div>";
    	$i++;	
    }?>
</div>
<?}?>

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
});
</script>