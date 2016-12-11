<?
$shopcoins = (integer)request('shopcoins');
$mark = (integer)request('mark');
$data_result = array();
$data_result['error'] = null;

if (!$tpl['user']['user_id']){	
	$data_result['error'] = "error1";
}


if (!$mark || !$shopcoins)
	$data_result['error'] = "error3";

if (!$data_result['error']) {	
	
	$all_marks = $shopcoins_class->getMarks($shopcoins);
	//значит пользователь уже голосовал
	if ($all_marks["markusers"]) $data_result['error'] = "error4";
}	

if (!$data_result['error']) {
    $rows = $shopcoins_class->getMarktmp($shopcoins);
	if ($rows) {	
	    $data = array('shopcoins'=>'0',
                     'catalog'   =>$rows['catalog'],
                     'mark'      =>$mark,
                     'user'      =>$tpl['user']['user_id'],
                     'dateinsert'=>time(),
                     'check'     =>1);   	
	}	else {	
	    $data = array('shopcoins'=>$shopcoins,
                     'catalog'   =>0,
                     'mark'      =>$mark,
                     'user'      =>$tpl['user']['user_id'],
                     'dateinsert'=>time(),
                     'check'     =>1);   	
	}
	
	$shopcoins_class->addShopcoinsmark($data);
	
	//mysql_query($sql_ins);
	//echo $sql_ins;
	$matkitem = $shopcoins_class->getMarks($shopcoins);
	/*$sql_marktmp = "select * from catalogshopcoinsrelation where shopcoins = '$shopcoins';";
	$result_marktmp = mysql_query($sql_marktmp);
	$rows_marktmp = mysql_fetch_array($result_marktmp);
	$sql_mark = "select shopcoinsmark.* from shopcoinsmark 
	where (".($rows_marktmp['catalog']?"(shopcoinsmark.catalog='".$rows_marktmp['catalog']."') 
	or":"")." (shopcoinsmark.shopcoins='$shopcoins')) and shopcoinsmark.check=1 and shopcoinsmark.user='$user' group by shopcoinsmark.shopcoinsmark;";
	$result_mark = mysql_query($sql_mark);
	//echo $sql_mark;
	if (@mysql_num_rows($result_mark)) {
		
		while ($rows_mark = @mysql_fetch_array($result_mark)) {
		
			$markusers ++;
			$marksum += $rows_mark['mark'];
		}
	}
	else $data_result['error'] = "error4";*/
}

        
if (!$data_result['error']){
	$data_result['markusers2'] = $matkitem['markusers'];
	$data_result['marksum2'] = round($matkitem['marksum']/$matkitem['markusers']);
}
echo json_encode($data_result);

die();
?>