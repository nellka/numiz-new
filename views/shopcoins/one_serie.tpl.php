<div id='serie-<?=$id?>' class="series-one">

<?if(!count($tpl['one_series']['data'])){?>
	<font color="red">Серия монет не найдена</font>
<?} else {?>    

        <div class="seo" class="clearfix">
        <h5><?=$tpl['one_series']['group']['name']?> -  <?=$tpl['one_series']['name']?></h5>
        <?=$tpl['one_series']['details']?>
        </div>

    <div class="product-grid">
<?   foreach ($tpl['one_series']['data'] as $key=>$rows){	      
	    
		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
		<div class='blockshop-full'>";
		include('items/item.tpl.php');
		echo "</div>
		</div>";
    }?>
    </div>
  
<? } ?>
    