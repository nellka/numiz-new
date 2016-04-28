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

        $rows['gname'] = $tpl['one_series']['group']["name"];		   
	    $rows['metal'] = $tpl['metalls'][$rows['metal_id']];		   
	    $rows['condition'] = $tpl['conditions'][$rows['condition_id']];
	    $statuses = $shopcoins_class->getBuyStatus($rows["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
	    
		$rows['buy_status'] = $statuses['buy_status'];
		$rows['reserved_status'] = $statuses['reserved_status'];	
		$rows['mark'] = $shopcoins_class->getMarks($rows["shopcoins"]);
		
	    $rows = array_merge($rows, contentHelper::getRegHref($rows));  	
	    
	    
		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
		<div class='blockshop-full'>";
		include('items/item.tpl.php');
		echo "</div>
		</div>";
    }?>
    </div>
  
<? } ?>
    