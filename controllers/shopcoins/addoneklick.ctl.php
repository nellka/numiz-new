<?php
require_once $cfg['path'] . '/models/helpshopcoinsorder.php';
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';

require_once($cfg['path'] . '/models/mails.php');

$shopcoins = (integer)request('shopcoins');
$onefio = request('onefio');
$onephone = request('onephone');
$amount = request('amount');

$helpshopcoinsorder_class = new model_helpshopcoinsorder($db_class);
$order_class = new model_order($db_class);
$orderdetails_class = new model_orderdetails($db_class,$shopcoinsorder);

$data_result = array();
$data_result['error'] = null;

if (!$shopcoins || !$onefio || !$onephone){
	$data_result['error'] = "noshopcoins";
}

$amount = (integer)$amount?(integer)$amount:1;

$erroramount = "";

$rows = $shopcoins_class->getRowByParams(array('shopcoins'=>$shopcoins));

if(!$rows){
    $data_result['error'] = "noshopcoins";
} else  {
    $oldamount = $rows['amount'];
    $price = $rows['price'];    
    $ShopcoinsMaterialtype = $rows["materialtype"];
    
    if ($price< $minpriceoneclick) 	$data_result['error'] = "stopsummin";
    
    if ($rows['group'] == 1604)	$data_result['error'] = "notavailable";
    elseif ($ShopcoinsMaterialtype==8 || $ShopcoinsMaterialtype==7 || $ShopcoinsMaterialtype==4 || $ShopcoinsMaterialtype==2) {
    	$result_amount = $helpshopcoinsorder_class->getAllByParams(array('shopcoins'=>$shopcoins));    	
    	$amountreserve = 0;
    	foreach ($result_amount as $rows_amount) {    	
    		if (time()-$rows_amount["reserve"] < $reservetime ) 
    			$amountreserve++;
    	}    	
    	if (!$rows["amount"]) $rows["amount"] = 1;
    	
    	if ($rows["amount"] <= $amountreserve || $amount > ($rows["amount"] - $amountreserve)) {    	
    		$data_result['error'] = "reserved"; 
    		$erroramount = $rows["amount"];
    	}
    }  else {    
    	if ($rows["reserve"]>0 || $rows['doubletimereserve']>time()) {
    		if ((time()-$rows["reserve"] < $reservetime and $rows["reserveorder"] != $shopcoinsorder) || ($rows['doubletimereserve']>time() && $rows['userreserve']!=$cookiesuser && $cookiesuser>0))
    		$data_result['error'] = "reserved";
    	}
    }

    if (($ShopcoinsMaterialtype==3 || $ShopcoinsMaterialtype==5) and $rows["amount"] < $amount) {
    	$data_result['error'] = "amount";
    	$erroramount = $rows["amount"];
    }
    
    if ($rows["check"] == 0) $data_result['error'] = "notavailable";    
    
    if (!$data_result['error']) {
        $data_order = array('user'=>811,
                             'date'=>time(),
                             'type'=>'shopcoins',
                             'check'=>1,
                             'ip'=>$user_remote_address,
                             'delivery'=>12,
                             'payment'=>2,
                             'sum'=>$price,
                             'userfio'=>strip_tags($onefio),
                             'phone'=>strip_tags($onephone),
                             'FinalSum'=>$price);  
        $shopcoinsorder = $order_class->addNewRecord($data_order);
        
        $data_orderdetails = array('order'=>$shopcoinsorder,
                             'date'=>time(),
                             'catalog'=>$shopcoins,
                             'amount'=>$amount,
                             'typeorder'=>1);  
       
        $orderdetails_class->addNewRecord($data_orderdetails);
    	if (in_array($ShopcoinsMaterialtype,array(1,10,11,9,12))) {
    		$data_update = array('check'=>0, 
    		                     'reserveorder'=>$shopcoinsorder, 
    		                     'reserve' => 0, 
    		                     'amount' => 0, 
    		                     'doubletimereserve'=>0, 
    		                     'userreserve'=>0, 
    		                     'dateorder'=>time());    		
    	} elseif (in_array($ShopcoinsMaterialtype,array(4,7,8,2))){  
    		$data_update = array('reserveorder'=>$shopcoinsorder,     		                     
    		                     'amount' => $oldamount - $amount,     		                     
    		                     'dateorder'=>time());   
    		if($amount>=$oldamount) $data_update['check'] = 0;
              	
			/*$sql_update = "update shopcoins 
						set  amount=amount-".$amount."
						".($amount>=$oldamount?", `check`=0":"")."
						, reserveorder='".$shopcoinsorder.", dateorder=".time()."'
						where shopcoins='".$shopcoins."';";*/
    		
    	} elseif ($ShopcoinsMaterialtype == 3 || $ShopcoinsMaterialtype == 3 ) {
    	   $data_update = array('amount' => $oldamount - $amount,     		                     
    		                     'dateorder'=>time());   
    		if($amount>=$oldamount) $data_update['check'] = 0;
                 	
    	}  else {
    	      $data_update = array('reserveorder'=>$shopcoinsorder,   		                     
    		                     'dateorder'=>time(),
    		                     'check' => 0);      		
        }
        $shopcoins_class->updateRow($data_update,"shopcoins='$shopcoins'");       

    	$item = $shopcoins_class->getItem($shopcoins,true);
    	
    	$mail_class = new mails();		
		$mail_text = '<table border="0" cellpadding="0" cellspacing="0" width="650">';
		$mail_text .='<tr><td colspan="2" style="font-size:14px;font-weight:bold;"><br>';
		$mail_text .='Благодарим Вас за то, что воспользовались услугами нашего магазина.<br>';
		$mail_text .='Вами сделан заказ '.$shopcoinsorder.' на сумму '.$price.' руб. В ближайшее рабочее время с Вами свяжется наш сотрудник.<br>';
		$mail_text .='<br>ФИО: '.$onefio.'<br>';
		$mail_text .='Телефон: '.$onephone.'<br>';		
		$mail_text .= '<br><br>Вы можете связаться с менеджером по адресу <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в рабочие дни или по телефону 8-800-333-14-77 (бесплатный звонок по России) (<b><font color=red>+3 GMT MSK</font></b>).';
		$mail_text .= '</td></tr></table>';
		$mail_text .= '<table border="0" cellpadding="0" cellspacing="0" width="650" style="border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;">';
		$mail_text .= '<tr><td  style="border:1px solid #cccccc;padding:10px;"></td><td  style="border:1px solid #cccccc;padding:10px;">Наименование</td><td style="border:1px solid #cccccc;padding:10px;">Группа (страна)</td><td style="border:1px solid #cccccc;padding:10px;">Год</td><td style="border:1px solid #cccccc;padding:10px;">Номер</td><td  style="border:1px solid #cccccc;padding:10px;">Цена</td></tr>' ;
		
		$mail_text .= '<tr>';
		$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;"><img style="max-width:100px;border:1px solid #cccccc;" src="http://www.numizmatik.ru/shopcoins/images/'.$item["image_small"].'"/></td>';
		$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;"><a href='.$cfg['site_dir'].'shopcoins/show.php?catalog='.$item["catalog"].' target=_blank>'.$item["name"].'</a></td>';
		$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$item["gname"].'</td>';
		$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$item["year"].'</td>';
		$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$item["number"].'</td>';
	    $mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.round($item['price'],2).' руб.</td>';
	    $mail_text .= '</tr>';				
		$mail_text .= '</table>';
		$mail_text .='</div><br><br>Спасибо за покупку в нашем магазине!';
		$mail_class->oneClickLetter($mail_text);

        
        /*
    	$messageusers = " Numizmatik.Ru";
	
    	$sqls = "SHOW TABLE STATUS LIKE 'smssend';";
    	$results = mysql_query($sqls);
    	$rowss = mysql_fetch_array($results);
    	$transactionsms = $rowss['Auto_increment'];
    	
    	include $in."smsconfig.php";
    
    	
    	$testingsms = sendsms2 (0,strip_string($onephone),$messageusers);
    	//echo " nnn=".$testing[0]." === ".$testing[1]." === ".$testing[2];
    	if ($testingsms !== false && sizeof($testingsms)>0 && ($testingsms[0] == 11 || $testingsms[0] == 0)) {
    	
    		$sqlsms = "insert into `smssend` (`smssend`,`user`,`phone`,`text`,`status`,`order`,`type`,`dateinsert`)
    				values (NULL,811,'".$testingsms[1]."','".$testingsms[2]."',1,0,0,".time().");";
    		mysql_query($sqlsms);
    	}*/
    }
    
    if (!$data_result['error']) {  
        $data_result['shopcoinsorder'] = $shopcoinsorder;
        $data_result['bascetshopcoins'] = $shopcoins;
        $data_result['bascetsum'] = $price;
        
    	$xml = "<error>none</error>
    	<shopcoinsorder>".$shopcoinsorder."</shopcoinsorder>
    	<bascetshopcoins>".$shopcoins."</bascetshopcoins>
    	<bascetsum>".$price."</bascetsum>";
    }   else    {
        $data_result['erroramount'] = ($erroramount?$erroramount:"");
        $data_result['bascetshopcoins'] = ($shopcoins?$shopcoins:"none");
    }
}
echo json_encode($data_result);
die();
?>