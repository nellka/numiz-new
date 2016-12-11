<?

include($cfg['path']."/controllers/detailscoins/config.php");

$data_result  = array();
$coins = (int) request('coins');
$parent = (int) request('parent');


if (!$tpl['user']['user_id']){?>
	<div class="error">Вы не авторизованы!</div>
	
<?} else if(!in_array($tpl['user']['user_id'],$ArrayUsers)) $coins = 0;
	
if (!$coins) {?>
	<div class="error">Ошибочный запрос</div>
	<br><script>window.location.reload();</script>
	
<?
die();
} elseif ($parent) {
	
	$sql = "select * from shopcoinswrite where shopcoinswrite=$coins and idcatalog=0 and `user`='".$tpl['user']['user_id']."';";
	$row = $shopcoins_class->getRowSql($sql);
	if ($row) {
		$data = array('idcatalog'=>$parent);
		$shopcoins_class->updateTableRow('shopcoinswrite',$data,"shopcoinswrite='$coins' and user='".$tpl['user']['user_id']."'");
		$inserarray = array('user'=>$tpl['user']['user_id'],
							'sum' => $ArrayPriceWork[2],										
							'dateinsert'=>time(),
							'check'=>1,
							'type'=>2);
		$shopcoins_class->insertNewRecord('distantionuser',$inserarray);
		
	}

}

?>
<script>window.location.reload();</script>
<?
die();

?>