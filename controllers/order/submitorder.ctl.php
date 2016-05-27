<?
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require_once $cfg['path'] . '/models/catalogshopcoinsrelation.php';
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once($cfg['path'] . '/models/mails.php');


$payment = request('payment');
$userfio = request('userfio');
$fio = request('fio');
$phone = request('phone');
$delivery = request('delivery');
$metro = request('metro');
$meetingdate = request('meetingdate');
$meetingfromtime = request('meetingfromtime');
$meetingtotime = request('meetingtotime');
$postindex = request('postindex');
$adress = request('adress');
$OtherInformation = request('OtherInformation');
$from_ubb =  request('from_ubb');
$deletesubscribecoins =  request('deletesubscribecoins');
$idadmin = request('idadmin');

$coupon_count = (integer)request('coupon_count');
$coupons = array();

$timenow = mktime(0, 0, 0, date("m", time()), date("d", time()), date("Y", time()));

if ($delivery==2){$DeliveryName[$delivery] = "В офисе (возможность посмотреть материал до выставления)";}

if($tpl['user']['user_id']==352480){
	//var_dump($meetingdate,$meetingfromtime,$meetingtotime,date('d.m.Y H:i',$meetingdate),date('d.m.Y H:i',$meetingdate+$meetingfromtime),date('d.m.Y H:i',($meetingdate+$meetingfromtime)));
}

$bonus_comment = false;

if(!$payment || !$userfio ||!$fio){    
    $tpl['submitorder']['error'] = true;
} elseif (!$tpl['user']['user_id']||!$shopcoinsorder) {
    $tpl['submitorder']['error_auth'] = true;
} else {  
    
    $order_class = new model_order($cfg['db'],$shopcoinsorder,$tpl['user']['user_id']);
    $orderdetails_class = new model_orderdetails($cfg['db'],$shopcoinsorder);
    $catalogshopcoinsrelation_class = new model_catalogshopcoinsrelation($cfg['db']);

	$user_data =  $user_class->getUserData();
					
	$userstatus = (integer) $user_data['userstatus'];
	
	$userstatus = $user_data['userstatus'];
    $sumlimit = $user_data['sumlimit'];
    $timelimit = $user_data['timelimit'];
    
    $rows90 = $order_class->getOrder();
    
    if($tpl['user']['user_id']==352480){
    	echo time()." 1<br>";
    }

	if ($userstatus==2) {
		$tpl['submitorder']['error_userstatus'] = true;
	} elseif (!$rows90||$rows90['check']==1) {
	   $tpl['submitorder']['error_already_buy'] = true;
	} else {
	    //полное количество заказанных товаров
		$rows_w = $order_class->countFullAmount();
				
		if ($rows_w>=100) $tpl['submitorder']['compare'] = true;			
		$clientdiscount = 0;
		$needcallingorder = 0;
		$needcallingorder1 = 0;
		$needcallingorder2 = 0;
		
		$clientdiscount = $orderdetails_class->getClientdiscount($tpl['user']['user_id']);

		if (!$clientdiscount) $needcallingorder1 = 1;
		
		$basket_data = $orderdetails_class->PostSum($postindex,$clientdiscount);

		if($tpl['user']['user_id']==352480){
        	echo time()." 2<br>";
        }

        $tpl['submitorder']['bascetsum'] = $bascetsum = $basket_data["bascetsum"];
		$tpl['submitorder']['amountbascetsum'] = $amountbascetsum = $basket_data['amountbascetsum'];
		$tpl['submitorder']['suminsurance'] = $suminsurance = $basket_data["suminsurance"];
		$tpl['submitorder']['PostAllPrice'] = $PostAllPrice  = $basket_data["PostAllPrice"];
		$tpl['submitorder']['PostZonePrice'] = $PostZonePrice  = $basket_data["PostZonePrice"];
		$tpl['submitorder']['bascetpostweight'] = $bascetpostweight  = $basket_data["bascetpostweight"];
		$tpl['submitorder']['PostZoneNumber'] = $PostZoneNumber  = $basket_data["PostZoneNumber"];
		
		//делаем проверку на все товары из магазина и показ отчета 		
		$tpl['submitorder']['result'] = $order_class->OrderSumDetails($clientdiscount);

		$vipcoinssum = 0;
		$sum = 0;	
		$sumamountprice = 0;
		$discountcoupon = 0;
		$i = 0;
		$oldmaterialtype = 0;
		if($tpl['user']['user_id']==352480){
        	echo time()." 3<br>";
        }
		foreach ($tpl['submitorder']['result'] as $rows ){	
		    $tpl['submitorder']['result'][$i]['title_materialtype'] = '';	
	
        	if ($oldmaterialtype != $rows["materialtype"]) {		
        		$oldmaterialtype = $rows["materialtype"];
        		$tpl['submitorder']['result'][$i]['title_materialtype'] = $MaterialTypeArray[$rows["materialtype"]];
        	}	
			
			$sum += ($rows["oamount"]?$rows["oamount"]:1)*round($rows['price'],2);
			$sumamountprice += ($rows["oamount"]?$rows["oamount"]:1)*round($rows['priceamount'],2);
			if ($rows['materialtype']==12)
				$vipcoinssum += $rows['oamount']*round($rows['price'],2);
		   $i++;
		}
		
		if ($sum>=1000) $needcallingorder2 = 1;
		$tpl['submitorder']['vip_discoint'] =0;
		
	    if($user_data['vip_discoint']) {
            $discountcoupon = floor(($bascetsum-$amountbascetsum-$vipcoinssum)*$user_data['vip_discoint']/100);  
            $tpl['submitorder']['vip_discoint'] =  $user_data['vip_discoint'];  
        } elseif ($coupon_count && $tpl['user']['user_id'] && $tpl['user']['user_id']<>811 && $shopcoinsorder) {
			//получаем данные о введенном купоне
			for ($i=0;$i<$coupon_count;$i++){			   
			     $code1 = request($i.'_code1');
			     $code2 = request($i.'_code2');
			     $code3 = request($i.'_code3');
			     $code4 = request($i.'_code4');
			     $code = $code1."-".$code2."-".$code3."-".$code4;
			     
    		     if ($code&&!preg_match("/[^-0-9a-zA-Z]{19}/",$code)){
    				$couponData = $user_class->getUserCoupon(array('code'=>$code,'type'=>1));
    				//$friendCoupon = $user_class->getFriendCouponCode();
    				
    				if($couponData&&$couponData[0]['check'] != 0&&$couponData[0]['dateend']>time()) {
    					$data = array(
    						'coupon'=>$couponData[0]['coupon'],
    						'order' => $shopcoinsorder,
    						'dateinsert'=>time(),
    						'check'=>1
    					);
    					$shopcoins_class->addNew('ordercoupon',$data);
    					$discountcoupon += $couponData[0]['sum'];	
    					$coupons[] = $couponData[0]['coupon'];			
    					$data = array('check'=>0,'order'=>$shopcoinsorder);
    					$shopcoins_class->updateTableRow('coupon',$data,"`check`=1 and type=1 and coupon='".$couponData[0]['coupon']."'");
    				}
    			}
			}
		}
		if($tpl['user']['user_id']==352480){
		    var_dump($coupons);
        	echo time()." 5<br>";
        }
       
		if ($discountcoupon<0) $discountcoupon = 0;
		
		if ( $sum < $discountcoupon) {			
			$discountcoupon = $sum;
		}

		$tpl['submitorder']['discountcoupon'] = $discountcoupon;

		$sum = $sum - $discountcoupon;
		$tpl['submitorder']['sum'] = $sum;
		
		$FinalSum = $sum;
		$sumEMC = 0;
			
		//вычисляем для страховок
        $suminsurance = $order_class->getSuminsurance();
        if ($suminsurance>0){
        	$bascetinsurance = $suminsurance * 0.04;
        } else {
        	$bascetinsurance = $bascetsum * 0.04;
        }

		if ($delivery==6) {		
			if ($bascetpostweight < 1000) 
				$sumEMC = 650;
			else {
			
				$sumEMC = 650;
				$sumEMC += ceil(($bascetpostweight - 1000)/500)*50;
			}
			
			$FinalSum = ($sum+$PriceLatter+10+$sumEMC);

			if($from_ubb && $sum <= 3000 AND $user_class->is_user_has_premissons() AND $tpl['user']['balance'] >= $sum){
				$payment = 2;
				$FinalSum = $FinalSum - $sum;
				$user_class->decrement_user_balance($sum);
				$user_class->addOrderBonusLog($shopcoinsorder,$sum);
				$bonus_comment = TRUE;
			}			
		} elseif ($delivery==4 && $postindex) {
			
			$FinalSum = ($PostZonePrice+$sum+$PriceLatter+($suminsurance>0?$suminsurance*0.04:$bascetinsurance));
			
			if($from_ubb && $sum <= 3000 AND $user_class->is_user_has_premissons() AND $tpl['user']['balance'] >= $sum){
				$payment = 2;
				$FinalSum = $FinalSum - $sum;
				$user_class->decrement_user_balance($sum);
				$user_class->addOrderBonusLog($shopcoinsorder,$sum);
				$bonus_comment = TRUE;
			}		
		}	else {
			if($from_ubb && $sum <= 3000 AND $user_class->is_user_has_premissons() AND $tpl['user']['balance'] >= $sum){			
				$payment = 2;
				$FinalSum = $FinalSum - $sum;
				decrement_user_balance($sum);
				user_order_bonus_log($shopcoinsorder,$sum);
				$bonus_comment = TRUE;
			}			
		}
		
		if ($payment != 2 && $needcallingorder1) $needcallingorder = 1;
		if($tpl['user']['user_id']==352480){
        	echo time()." 6<br>";
        }
		if (($sum <= 0.00 || !$sum || $FinalSum <=0.00 || !$FinalSum) && $discountcoupon<=0) {
			$tpl['submitorder']['error_little'] = true;			
		} else {				

			$codeforfrend = $user_data['codeforfrend'];
			if (!$codeforfrend) {
			    $codeforfrend = $user_class->createFreandCoupon();			    
			}
						
			
			if ($postindex && $adress){
				preg_match_all('/\d{6}/', $adress, $found);
				if (!$found||!$found[0]||!trim($found[0][0])){
					$adress = $postindex.", ".$adress;
				}
			}
			
			$MetroName = "";
			
			if ($metro and $delivery == 1)	{
				$MetroName = $MetroArray[$metro];
			} elseif ($metro and ($delivery == 3)) {
				$rows = $order_class->getMetroName($metro) ;				
				$MetroName = $rows["name"];
			}			
						
			//делаем delete с orderdetails, если уже материал был заказан или он отсутствует			
			$result = $orderdetails_class->getDeleted() ;
			
			foreach ($result as $rows){
			    $orderdetails_class->deleteItem($rows["catalog"]);			
			}
			
			//делаем update единичный товаров и изменение количества у других------------------------------------------------------
			$result = $orderdetails_class->getOrderDetails();						
    		if($tpl['user']['user_id']==352480){
            	echo time()." 7<br>";            	
            }
            //$shopcoins_class->lockTablesForOrder();
	    
			$ParentArray = Array();				
			foreach ($result as $rows) {
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
    		                          'check'=>1);
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
				
				if($tpl['user']['user_id']==352480){
                	var_dump($data_update,$rows["orderamount"],$rows["samount"],$data_update['check']);    	
                }
                
				$shopcoins_class->updateRow($data_update,"shopcoins='".$rows["catalog"]."'");  
				
				if($data_update&&$data_update['check']===0){					
					$shopcoins_class->deteteFromTemp($rows["catalog"]);
				}
				
				if ($deletesubscribecoins && $tpl['user']['user_id'] && $rows["catalog"]) {
				    $shopcoins_class->deleteRow('catalogshopcoinssubscribe',"user='".$tpl['user']['user_id']."' and catalog='".$rows["catalog"]."'");			
				}
			}
			
    		if($tpl['user']['user_id']==352480){
            	//echo time()." 8<br>";            	
            }
            
           // $shopcoins_class->unlockTable();
            
			if (sizeof($ParentArray)>0) {				
				$result = $shopcoins_class->coinsParents($ParentArray);				
				foreach ($result as $rows) {
					$count  = $shopcoins_class->countChilds($rows["parent"]);
					
					$data_update = array('amountparent'=>$count);
					
					//сам update
					$shopcoins_class->updateRow($data_update,"shopcoins='".$rows["shopcoins"]."'"); 
				}
			}
			
			//проставляем amountparent			
			if ($tpl['user']['user_id']==811)	$needcallingorder=3;
			
			$CommentAdministrator = $order_class->getComentorder($userfio);
			
			if($bonus_comment === TRUE)
				$CommentAdministrator = $CommentAdministrator . ' Оплата бонусными деньгами.';
				
			$account_number = "";

			if(in_array($delivery,array(4,6,10) )){
				$meetingdate =  $meetingfromtime = $meetingtotime = 0;
			}

			$data_order = array( 
			                 'user'=>$tpl['user']['user_id'],
			                 'userfio' => $userfio, 
                    		 'phone'=>$phone, 
                    		 'adress'=>str_replace("|","/",$adress),
                    		 'admincheck'=>0, 
                    		 'sum'=>$sum, 
                    		 'delivery'=>$delivery, 
                    		 'payment'=>$payment,
                    		 'MetroMeeting'=>$metro, 
                    		 'DateMeeting' => $meetingdate + $meetingfromtime, 
                    		 'MeetingFromTime'=>$meetingfromtime, 
                			 'MeetingToTime'=>$meetingtotime, 
                			 'OtherInformation'=>$OtherInformation,
                			 'CommentAdministrator' => $CommentAdministrator,
                			 'account_number'=>$account_number, 
                			 'check'=>1,
                			 'FinalSum' => $FinalSum, 
                			 'NeedCall' => 2, 
                			 'suminsurance'=>'0', 
                			 'CallingOrder'=>$needcallingorder);
			$tpl['submitorder']['FinalSum'] = $FinalSum;
			$tpl['submitorder']['sumEMC'] = $sumEMC;
            if((int)$idadmin>0) $data_order['addidadmin'] = (int) $idadmin;

			$order_class->updateRow($data_order,"`order`='".$shopcoinsorder."'");
    		if($tpl['user']['user_id']==352480){
            	//echo time()." 9<br>";
            	//var_dump($data_order);
            }
			//подтверждаем заказ - order -------------------------------------------------------------------------------------------
						
			if($user_data['sms']==1&&in_array($delivery,array(1,3,4,6,7))) {
		        require_once $cfg['path'] . '/models/smssend.php';
            	$sms_class = new model_smssend($cfg['db']);
				
				$textsms1 = $sms_class->create_textsms($delivery,$payment,$meetingdate,$meetingdate,$MetroName);			
							
				$testingsms = $sms_class->sendsms2(1,$phone,$shopcoinsorder,($FinalSum>0?$FinalSum:$sum),$textsms1);
				$sms_class->addNewSms($tpl['user']['user_id'],$testingsms[1],$testingsms[2],$shopcoinsorder);
			}
		
			$order_class->updateRow(array('markadmincheck'=>3),"user='".$tpl['user']['user_id']."'  and `user`<>811 and `check`=1 and markadmincheck=2 and mark=2"); 	
						
			$countCoupon = $user_class->getUserCouponCount(array('`check`'=>1, 'type'=>2));
			
			if($tpl['user']['user_id']==352480){
            	var_dump($countCoupon);
            }
            
			if ($countCoupon==0) {				
				$couponup = 0;
				if (($sum-$sumamountprice - $vipcoinssum)>$bigsumcoupon && !$user_data['vip_discoint'] && $tpl['user']['user_id']!=811) {				
					$dis = ceil(($sum-$sumamountprice - $vipcoinssum)*$bigsumcoupondis/100);
					$couponup=1;
				} elseif (($sum-$sumamountprice - $vipcoinssum)>$smallsumcoupon && !$user_data['vip_discoint'] && $tpl['user']['user_id']!=811) {				
					$dis = ceil(($sum-$sumamountprice - $vipcoinssum)*$smallsumcoupondis/100);
					$couponup=1;
				}
				
				if ($couponup==1) {	
				    if ($delivery==1 || $delivery==2 || $delivery==3 || $delivery==7)
            			$dateend = time() + 31*24*60*60 -1;
            		elseif ($delivery==5 || $delivery==6 || ($delivery==4 && $paymentvalue!=1))
            			$dateend = time() + (46+$PostZone2[$PostZoneNumber])*24*60*60 -1;
            		elseif ($delivery==4 && $paymentvalue==1)
            			$dateend = time() + (31+$PostZone2[$PostZoneNumber])*24*60*60 -1;
            		else
            			$dateend = time() + 31*24*60*60 -1;	
				    $user_class->addCoupon($shopcoinsorder,$dis,$dateend);					
				}
			}
			
			if($tpl['user']['user_id']==352480){
				var_dump($couponup,$dis);
            	echo time()." 10<br>";
            }
			
			$resultsum = ($FinalSum>0?$FinalSum:$sum);
			//рассылаем письма------------------------------------------------------------------------------------------------------
			$crcode  = md5("numizmatikru:".sprintf ("%01.2f",round($resultsum*$krobokassa,2)).":".$shopcoinsorder.":$robokassapasword1:Shp_idu=".$tpl['user']['user_id']);
			$culture = "ru";
			$in_curr = "BANKOCEAN2R";

			//получаем информацию о метро
			$tpl['submitorder']['MetroName'] ='';
			if ($metro and $delivery == 1) {
				$tpl['submitorder']['MetroName'] = $MetroArray[$metro];
			} elseif ($metro and ($delivery == 3)) {
				$metro_data = $order_class->getMetroName($metro);
				$tpl['submitorder']['MetroName'] = $metro_data['name'];
			}

			$mail_class = new mails();		
			include $cfg['path']."/views/mails/ordermail.tpl.php";
			$mail_class->orderLetter($user_data,$mail_text);
			//$mail_class->orderLetter("bodka@mail.ru",array()); 
			//изменение информации о пользователе
			if ($tpl['user']['user_id'] != 811) {
			    $data = array('phone'=>$phone,
			                  'fio'=>$fio,
			                  'adress'=>$adress);
				$user_class->updateRow($data,"user='".$tpl['user']['user_id']."'"); 				
			}	
			if($tpl['user']['user_id']==352480){
            	echo time()." 11<br>";
            }
			//удаляем cookies пользователя------------------------------------------------------------------------------------------			
            setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/", $domain);
            setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/");
            setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/", ".shopcoins.numizmatik.ru");
            setcookie("shopcoinsorder", 0, time() + 2, "/");
            
            setcookie("order", 0, time() + 2, "/shopcoins/", $domain);
            setcookie("order", 0, time() + 2, "/shopcoins/");
            setcookie("order", 0, time() + 2, "/shopcoins/", ".shopcoins.numizmatik.ru");
            setcookie("order", 0, time() + 2, "/");            
            
            $tpl['submitorder']['shopcoinsorder'] = $shopcoinsorder;
            
            unset($_SESSION['shopcoinsorder']);
            unset($_SESSION['order']);            
                            
            session_destroy();
            $shopcoinsorder = 0;
            $tpl['user']['product_amount'] = 0;
            $tpl['user']['summ']= 0;
		}
	}
}

if($tpl['user']['user_id']==352480){
	echo time()." end<br>";
}
