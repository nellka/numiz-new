<?
$shopcoins = (integer)request('shopcoins');
$review = request('review');

$data_result = array();
$data_result['error'] = null;

if (!$tpl['user']['user_id']){	
	$data_result['error'] = "error1";
}

$review = iconv("UTF-8", "windows-1251//TRANSLIT", $review);

if (!trim($review) || !$shopcoins)
	$data_result['error'] = "error3";

if (!$data_result['error']) {	
	$result = $shopcoins_class->getReviews($shopcoins);		
	if ($result['userreviewis']>0) $data_result['error'] = "error4";
}	

if (!$data_result['error']) {	

    $rows_marktmp = $shopcoins_class->getMarktmp($shopcoins);   
    
	if ($rows_marktmp) {
	    $data = array('shopcoins'=>'0',
                     'catalog'   =>$rows_marktmp['catalog'],
                     'review'      =>$review,
                     'user'      =>$tpl['user']['user_id'],
                     'dateinsert'=>time(),
                     'check'     =>1);   	
	}	else {	
	    $data = array('shopcoins'=>$shopcoins,
                     'catalog'   =>0,
                     'review'      =>$review,
                     'user'      =>$tpl['user']['user_id'],
                     'dateinsert'=>time(),
                     'check'     =>1);   	
	}
	
    $shopcoins_class->addShopcoinsreview($data); 	
    $myReview = $shopcoins_class->getReviews($shopcoins,$tpl['user']['user_id']);
}

if (!$data_result['error']){
    $data_result['review'] = htmlspecialchars($myReview["reviewusers"][0]['review']);
    $data_result['dateinsert'] = date('d-m-Y',$myReview["reviewusers"][0]['dateinsert']);	
    $data_result['fio'] = $myReview["reviewusers"][0]['fio'];	
}

echo json_encode($data_result);
die();
?>