<?
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require_once $cfg['path'] . '/models/catalogshopcoinsrelation.php';
require_once($cfg['path'] . '/models/mails.php');

$order_class = new model_order($db_class,$shopcoinsorder,$tpl['user']['user_id']);
$orderdetails_class = new model_orderdetails($db_class,$shopcoinsorder);
$catalogshopcoinsrelation_class = new model_catalogshopcoinsrelation($db_class);

$addinordersubmit = request('addinordersubmit');

$MetroName ='';

$tpl['addinorder']['error'] = '';
if ($blockend > time()) {
	$tpl['addinorder']['error'] = "Возможность добавления к предыдущим заказам приостановлена до " . date('H:i', $blockend);

} else if (!$tpl['user']['user_id']) {
	$tpl['addinorder']['error']  = "Нужна авторизация на сайте для оформления этого заказа.";
} else if (!$tpl['user']['orderusernow']) {
	$tpl['addinorder']['error']  = "У вас нет заказов для объединения с этим, поэтому минимальная сумма заказа должна быть 500 руб.";
} else {
	$rows90 = $order_class->getOrder();
	if ($rows90['check']==1)
		$tpl['addinorder']['error'] = "Заказ $shopcoinsorder уже оформлен Вами. Вы можете его просомтреть в \"Ваши заказы\"";
}



if (!$tpl['addinorder']['error']) {
	$rows_temp = $order_class->getPreviosOrder();

	$phone = $rows_temp['phone'];
	$fio = $rows_temp['userfio'];
	$adress = $rows_temp['adress'];
	
    $delivery = $rows_temp['delivery'];
    $payment = $rows_temp['payment'];
    
    $meetingdate = $rows_temp['DateMeeting'];
    $meetingfromtime = $rows_temp['MeetingFromTime'];
    $meetingtotime = $rows_temp['MeetingToTime'];
    
	$timenow = mktime(0, 0, 0, date("m", time()), date("d", time()), date("Y", time()));
	
	$user_data =  $user_class->getUserData();

	$userstatus = (integer) $user_data['userstatus'];
	$userstatus = $user_data['userstatus'];
	$sumlimit = $user_data['sumlimit'];
	$timelimit = $user_data['timelimit'];

	$email = $user_data['email'];

	//$sql = "select sum(orderdetails.amount*shopcoins.price) as sumallorder from `order`,orderdetails,shopcoins where ((`order`.`user`='$cookiesuser' and `order`.`user`<>811 and `order`.`check`=1 and `order`.ReceiptMoney=0) or (`order`.`order`=".$shopcoinsorder.")) and orderdetails.`order`=`order`.`order` and orderdetails.catalog=shopcoins.shopcoins and orderdetails.status=0;";
	$sum_allorder = $order_class->getSumOfOrder();
	$sumallorder = 1;

	if($sum_allorder&&intval($sum_allorder["sumallorder"])>0){
		$sumallorder = intval($sum_allorder["sumallorder"]);
	}

	if ($sumallorder > $sumlimit && $sumlimit>0) $tpl['addinorder']['error'] =  "Вы превысили общую сумму невыкупленных заказов.";

	//проверка по сумме заказа
	if ($order_class->sumOrders($rows_temp['order']) > $stopsummax)
		$tpl['addinorder']['error'] = "Вы превысили максимальную сумму для одного заказа.";


	if (!$addinordersubmit) {
	    $data_order = array( 
             'user'=>$tpl['user']['user_id'],
             'userfio' => $rows_temp['userfio'], 
    		 'phone'=>$rows_temp['phone'], 
    		 'adress'=>$rows_temp['adress'], 
    		 'admincheck'=>0, 
    		 //'sum'=>$sum, 
    		 'delivery'=>$rows_temp['delivery'], 
    		 'payment'=>$rows_temp['payment'],
    		 'MetroMeeting'=>$rows_temp['MetroMeeting'], 
    		 'DateMeeting' => $rows_temp['DateMeeting'], 
    		 'MeetingFromTime'=>$rows_temp['MeetingFromTime'], 
			 'MeetingToTime'=>$rows_temp['MeetingToTime'], 
			 'OtherInformation'=>$rows_temp['OtherInformation'], 
			// 'CommentAdministrator' => strip_tags($CommentAdministrator),
			 'account_number'=>$rows_temp['account_number'], 
			// 'check'=>1,
			 //'FinalSum' => $FinalSum, 
			 'NeedCall' => 2, 
			// 'suminsurance'=>'0', 
			// 'CallingOrder'=>$needcallingorder
			);
        $order_class->updateRow($data_order,"`order`='".$shopcoinsorder."'");		
	}
	
	$clientdiscount = $orderdetails_class->getClientdiscount($tpl['user']['user_id']);

	//делаем проверку на все товары из магазина и показ отчета --------------------------------------------------------------
	$tpl['submitorder']['result'] = $orderdetails_class->getOrderDetails($clientdiscount);

	if($tpl['user']['user_id']==352480){
		//var_dump($rows_temp);
		//die();
		// $tpl['is_mobile'] = true;
	}
	$i=0;
	$oldmaterialtype = "";
	$sum = 0;
	$sumamountprice = 0;
	foreach ($tpl['submitorder']['result'] as $rows ){	

	    $tpl['submitorder']['result'][$i]['title_materialtype'] = '';	

    	if ($oldmaterialtype != $rows["materialtype"]) {		
    		$oldmaterialtype = $rows["materialtype"];
    		$tpl['submitorder']['result'][$i]['title_materialtype'] = $MaterialTypeArray[$rows["materialtype"]];
    	}	
		
		$sum += ($rows["orderamount"]?$rows["orderamount"]:1)*($clientdiscount==1 && $rows['clientprice']>0?round($rows['clientprice'],2):round($rows['price'],2));
	}
		
	
    $postindex = 0;
	preg_match_all('/\d{6}/', $rows_temp['adress'], $found);
	if ($found&&$found[0]&&$found[0][0]){
		$postindex = trim($found[0][0]);
	}

	$data_order = $orderdetails_class->PostSum($postindex, $clientdiscount);
	$bascetsum = $data_order['bascetsum'];
	$bascetpostweight = $data_order['bascetpostweight'];	
	$PostAllPrice = $data_order['PostAllPrice'];		

	if ($rows_temp['delivery']==4 and $postindex)
	{
		$FinalSum = ($PostZonePrice+$sum+$PriceLatter+($suminsurance?$suminsurance*0.04:$bascetinsurance));
	} else {
		$FinalSum = $sum;
	}

	if ($sum <= 0.00 || !$sum || $FinalSum <=0.00 || !$FinalSum){
		$tpl['addinorder']['error'] = "<p class=txt> <font color=red><b>Сумма заказа менее 500.00 руб. </b></font></p>";
	} else {		
		
		if ($rows_temp['MetroMeeting'] and $rows_temp['delivery'] == 1){
			$MetroName = $MetroArray[$rows_temp['MetroMeeting']];
		}	elseif ($rows_temp['MetroMeeting'] and ($rows_temp['delivery'] == 3)) {
			$rows = $order_class->getMetroName($rows_temp['MetroMeeting']) ;
			$MetroName = $rows["name"];
		}
		
		if ($addinordersubmit) {
			//делаем delete с orderdetails, если уже материал был заказан или он отсутствует
			$result = $orderdetails_class->getDeleted() ;
			
			foreach ($result as $rows){
			    $orderdetails_class->deleteItem($rows["catalog"]);			
			}
			
			$tpl['submitorder']['result'] = $orderdetails_class->getOrderDetails($clientdiscount);
            $ParentArray = Array();
            
			//делаем update единичный товаров и изменение количества у других------------------------------------------------------
			foreach ($tpl['submitorder']['result']  as $rows){
				$shopcoinsItem =  $shopcoins_class->getItem($rows["catalog"]);
				
			    $ParentArray[] = $rows["parent"];
			    //монеты, боны, подарочные наборы
				if ($rows["materialtype"]==1){		
    			    if ($rows['relationcatalog']>0 && $rows['userreserve']>0 && $rows['userreserve'] != $tpl['user']['user_id']) {
    						$rows_info_catalog = $catalogshopcoinsrelation_class->getReserved($rows['shopcoins'],$rows['userreserve']);
    						if($rows_info_catalog){
    							 $data_insert = array(
    							                 'user'=>$rows['userreserve'], 
                        		                 'catalog' => $rows_info_catalog, 
                        		                 'dateinsert'=>time(), 
                        		                 'datesend'=>0,
                        		                 'amountdatesend'=>0,
                        		                 'buy'=>0,
                        		                 'amount'=>1
                        		                 );  	
                    		      $shopcoins_class->addNew('catalogshopcoinssubscribe',$data_insert);		
    						}
    					}
    					$data_update = array(
                                        'check'=>0,
                                        'reserveorder'=>$shopcoinsorder, 
                                        'reserve' => 0, 
                                        'amount' => 0,
        		                        'doubletimereserve'=>0,
        		                        'userreserve'=>0,
        		                        'dateorder' =>time()
        		                        );  
			    } elseif (in_array($rows["materialtype"],array(4,7,8,6,2,12))) {
					$data_update = array(                     
                                    'amount' => ($shopcoinsItem["amount"]-$rows["orderamount"]),                       
    		                        'reserveorder'=>$shopcoinsorder, 
    		                        'dateorder' =>time()
    		                        );  
    		       if($rows["orderamount"]>=$rows["samount"]){
    		          $data_update['check'] = 0;
    		       }					
				   $shopcoins_class->deleteRow('helpshopcoinsorder',"reserveorder='".$shopcoinsorder."' and shopcoins='".$rows["catalog"]."'");		
					
				} elseif ($rows["materialtype"]==3 || $rows["materialtype"]==5) {  
				     //аксессуары, книги
				     $data_update = array(                     
                                    'amount' => ($shopcoinsItem["amount"]-$rows["orderamount"]),	                        
    		                        'dateorder' =>time()
    		                        );  
    		       if($rows["orderamount"]>=$rows["samount"]){
    		          $data_update['check'] = 0;
    		       }		
    		       if ($rows["orderamount"]>=$rows["samount"]) {
    		           $data_insert = array(
    		                          'shopcoins'=>$rows["catalog"], 
    		                          'insertdate'=>time(), 
    		                          'updatedate'=>0,
    		                          'check'=>1
    		                          );
    		           $shopcoins_class->addNew('shopcoinsend',$data_insert);						
    		       }
				} else {
				    // другое
				    $data_update = array(                     
                                    'check' => 0,                       
    		                        'reserveorder'=>$shopcoinsorder, 
    		                        'dateorder' =>time()
    		                        );				
				}
				
				$shopcoins_class->updateRow($data_update,"shopcoins='".$rows["catalog"]."'");  	
				if($data_update&&$data_update['check']===0){	
					$writer_coins = new Zend_Log_Writer_Stream($log_order);
					$logger_coins    = new Zend_Log($writer_coins);		
					$logger_coins->log($_SERVER['REMOTE_ADDR']. ' '.$_SERVER['HTTP_USER_AGENT']. ' '.' viporder:'.$viporder.', bye (adinorder) '.$rows["catalog"].', user:'.$tpl['user']['user_id'].', order:'.$shopcoinsorder,Zend_Log::INFO); 		
					$shopcoins_class->deteteFromTemp($rows["catalog"]);
				}		
			}
		
			if (sizeof($ParentArray)){
			    $result = $shopcoins_class->coinsParents($ParentArray);				
				foreach ($result as $rows) {
					$count  = $shopcoins_class->countChilds($rows["parent"]);
					$data_update = array('amountparent'=>$count);
					//сам update
					$shopcoins_class->updateRow($data_update,"shopcoins='".$rows["catalog"]."'"); 
				}				
			}
//
			//проставляем amountparent
			//подтверждаем заказ - order -------------------------------------------------------------------------------------------
			$data_order = array( 
                    		 'sum'=>$sum, 
                			 'check'=>1,
                			 'FinalSum' => $FinalSum, 
                			 'NeedCall' => 2, 
                			 'suminsurance'=>'0');

			$order_class->updateRow($data_order,"`order`='".$shopcoinsorder."'");
						
			$tpl['submitorder']['sum'] = $sum;
			$tpl['submitorder']['FinalSum'] = $FinalSum;
			
			$mail_class = new mails();		
			include $cfg['path']."/views/mails/ordermail.tpl.php";
								
			$mail_class->orderLetter($user_data,$mail_text);   
			$mail_class = new mails();
			$mail_class->orderLetter(array('email'=>'bodka@mail.ru','userlogin'=>'bodka@mail.ru'),$mail_text);
			//удаляем cookies пользователя------------------------------------------------------------------------------------------
			//удаляем cookies пользователя------------------------------------------------------------------------------------------			
            setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/", $domain);
            setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/");
            setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/", ".shopcoins.numizmatik.ru");
            setcookie("shopcoinsorder", 0, time() + 2, "/");
            
            setcookie("order", 0, time() + 2, "/shopcoins/", $domain);
            setcookie("order", 0, time() + 2, "/shopcoins/");
            setcookie("order", 0, time() + 2, "/shopcoins/", ".shopcoins.numizmatik.ru");
            setcookie("order", 0, time() + 2, "/");            
            
            unset($_SESSION['shopcoinsorder']);
            unset($_SESSION['order']);                   
            session_destroy();
            $shopcoinsorder = 0;
            $tpl['user']['product_amount'] = 0;
            $tpl['user']['summ']= 0;
		}

	}
}
?>