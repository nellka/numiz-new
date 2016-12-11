<?

require_once($cfg['path'] . '/configs/config_shopcoins.php');

require($cfg['path'].'/helpers/Paginator.php');

switch ($tpl['task']){  
	  
    case 'recomended':{       
       $coinsId = $shopcoins_class->getPopular(4);         
       break;
    }
    case 'new':{
      	$coinsId = $shopcoins_class->getNew(4);      
       	break;
    }
    
    case 'sale':{
      	$coinsId = $shopcoins_class->getSale(4);         
       	break;
    }   
}
 
$tpl['catalog']['coins'] = array();	

if($coinsId){
	$d = array();
	$d_order = array();
	foreach ($coinsId as $id){

		$row = $shopcoins_class->getItem($id["shopcoins"],true);
        $row['condition'] = $tpl['conditions'][$row['condition_id']];
	    $row['metal'] = $tpl['metalls'][$row['metal_id']];
        $d_order[] =  array_merge($row, contentHelper::getRegHref($row));                
    }    
    $tpl['catalog']['coins'] = $d_order;
}
        
?>
<div class="tab_coins">  
<div class="p-10"><a href="<?=$cfg['site_dir']?>shopcoins">Магазин монет - купить (продажа) монеты, банкноты, альбомы для монет, серебрянные и золотые монеты</a> </div>
    <?
    foreach ($tpl['catalog']['coins'] as $rows_show_relation2){
        $rows_show_relation2['metal'] = $tpl['metalls'][$rows_show_relation2['metal_id']];
        $rows_show_relation2['condition'] = $tpl['conditions'][$rows_show_relation2['condition_id']];
        ?>			
        
        <div class="coin_info-mini left " id='item<?=$rows_show_relation2['shopcoins']?>'>
            <div id=show<?=$rows_show_relation2['shopcoins']?>></div>
            <?	
            $statuses = $shopcoins_class->getBuyStatus($rows_show_relation2["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
            $rows_show_relation2['buy_status'] = $statuses['buy_status'];
            $rows_show_relation2['reserved_status'] = $statuses['reserved_status'];	
            $rows_show_relation2['is_mobile'] = $tpl['is_mobile'];	
            echo contentHelper::render('shopcoins/item/itemmini-carusel',$rows_show_relation2);
            ?>				
        </div>
    <?}?>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $('.down').click(function () {
            var input = $(this).parent().find('input[type=text]');
            var id = $(input).attr('id');
            var $amountall = $(this).parent().find('input[type=hidden]');
            var count = parseInt(input.val()) - 1;
            count = count < 1 ? 1 : count;
            input.val(count);
            $('#'+id).val(count);
            input.change();
            return false;
        });
        $('.up').click(function () {
            var input = $(this).parent().find('input[type=text]');
            var $amountall = $(this).parent().find('input[type=hidden]');
            count = parseInt(input.val()) + 1;
            count = count > $amountall.val() ? $amountall.val() : count;
            var id = $(input).attr('id');
            input.val(count);
            $('#'+id).val(count);
            input.change();
            return false;
        });
    });
</script>
<?  
die();
?>
