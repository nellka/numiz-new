<?php
include($cfg['path']."/controllers/detailscoins/config.php");

if (!in_array($tpl['user']['user_id'],$ArrayUsers)) {
	echo "<h1>Not found</h1>";
	exit;	
}

require_once($cfg['path'].'/helpers/Paginator.php');
$p_url = $cfg['site_dir'].'detailscoins/showcoins.php';


$user = $tpl['user']['user_id'];

$pagenum = (int) request('pagenum')?(int) request('pagenum'):1;
$group = (int)request('group');
$arraygroupauction = array();

if ($group){	
	$sql = "select * from `group` where `group`='$group' or groupparent='$group';";
	$result = $shopcoins_class->getDataSql($sql);
	
	foreach ($result as $rows) {		
		$arraygroupauction[] = $rows['group'];
		
		if ($group==$rows['group'])
			$GroupName = $rows["name"];
	}
}


$AmountCoinsRedaction = 0;
$sql = "select count(*) from shopcoinswriteusernum where `user`='".$tpl['user']['user_id']."' and `check`=1;";
$AmountCoinsRedaction = $shopcoins_class->getOneSql($sql);

$sql_t = "select * from shopcoinswriteusernum where `user`='".$tpl['user']['user_id']."' and `check`=0;";

$ArrayTemp = array();

foreach ($result_t as $rows_t) {
	$ArrayTemp[] = $rows_t['shopcoinswrite'];	
}

/*
$sql_t = "select * from shopcoinswriteusernum where `user`='".$tpl['user']['user_id']."' and `check` in(0,1);";

$result_t = $shopcoins_class->getDataSql($sql_t);

$ArrayTemp = array();

foreach ($result_t as $rows_t) {
	if ($rows_t['check']==0)
		$ArrayTemp[] = $rows_t['shopcoinswrite'];
	else
		$AmountCoinsRedaction ++;
}*/

$sql = "Select count(*) from shopcoinswrite where `check`=1 and (reservetime<'".time()."' or `user`='$user') ".(sizeof($ArrayTemp)?"and shopcoinswrite not in(".implode(",",$ArrayTemp).")":"").";";

$countpubs = $shopcoins_class->getOneSql($sql);

$onpage=16;

	
//=================================================
	
$orderby = "order by shopcoinswrite desc ";
$sort = 1;

$tpl['detailscoins']['_Title'] = "Клуб Нумизмат | Описание монет ";
$tpl['detailscoins']['_Keywords'] ="";
$tpl['detailscoins']['_Descriptionauction'] = ""; 


$limit = " limit ".($pagenum-1)*$onpage.",$onpage";

$sql_main = "select * from shopcoinswrite where `check`=1 and (reservetime<'".time()."' or `user`='$user') ".(sizeof($ArrayTemp)?"and shopcoinswrite not in(".implode(",",$ArrayTemp).")":"")." $orderby $limit;";

$result_main = $shopcoins_class->getDataSql($sql_main);

$tpl['paginator'] = new Paginator(array(      
				        'url'        => $p_url,
				        'count'      => $countpubs,
				        'per_page'   => $onpage,
				        'page'       => $pagenum,
				        'border'     =>5));