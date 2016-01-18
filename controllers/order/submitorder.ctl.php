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

$code1 = request('code1');
$code2 = request('code2');
$code3 = request('code3');
$code4 = request('code4');

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
		
		//делаем проверку на все товары из магазина и показ отчета 		
		$tpl['submitorder']['result'] = $order_class->OrderSumDetails($clientdiscount);
		var_dump($tpl['submitorder']['result']);
		$vipcoinssum = 0;
		$sum = 0;	
		$sumamountprice = 0;
		$discountcoupon = 0;
		$i = 0;
		$oldmaterialtype = 0;
		
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
		}
		
		if ($sum>=1000) $needcallingorder2 = 1;

		if ($code1 && $code2 && $code3 && $code4 && $tpl['user']['user_id'] && $tpl['user']['user_id']<>811 && $shopcoinsorder) {
			//получаем данные о введенном купоне
			$code = strtolower($code1."-".$code2."-".$code3."-".$code4);
			if (!preg_match("/[^-0-9a-zA-Z]{19}/",$code)){
				$couponData = $user_class->getUserCoupon(array('code'=>$code ));
				$friendCoupon = $user_class->getFriendCouponCode();
				if($couponData&&$couponData['check'] !== 0&&$couponData['dateend']>time()) {

					$data = array(
						'coupon'=>$couponData['coupon'],
						'order' => $shopcoinsorder,
						'dateinsert'=>time(),
						'check'=>1
					);
					$shopcoins_class->addNew('ordercoupon',$data);

					if ($couponData['type']==2) {
						$discountcoupon = floor(($sum-$sumamountprice-$vipcoinssum)*$couponData['sum']/100);
					} elseif ($couponData['type']==1) {
						$discountcoupon = $couponData['sum'];
					}
					$data = array('check'=>0,'order'=>$shopcoinsorder);
					$shopcoins_class->updateTableRow('coupon',$data,"`check`=1 and type=1 and coupon='".$couponData['coupon']."'");
				}
			}
		}

		if ($discountcoupon<0) $discountcoupon = 0;
		
		if ( $sum < $discountcoupon) {			
			$discountcoupon = $sum;
		}
		

		
		$sum = $sum - $discountcoupon;			

		$rows = $orderdetails_class->forBasket($clientdiscount);

        $bascetsum = $rows["mysum"];
        $amountbascetsum = $rows['mysumamount'];
        $vipcoinssum = $rows['vipcoinssum'];
		$bascetweight = $rows["myweight"];
		$bascetamount = $orderdetails_class->getCounter();
        $postcounter = $orderdetails_class->getPaking();
        if ($postcounter)
        	$bascetpostweight = $bascetweight + $WeightPostBox;
        else
        	$bascetpostweight = $bascetweight + $WeightPostLatter;
        //вычисляем для страховок
        $suminsurance = $order_class->getSuminsurance();
        if ($suminsurance>0){
        	$bascetinsurance = $suminsurance * 0.04;
        } else {
        	$bascetinsurance = $bascetsum * 0.04;
        }
		$mymaterialtype = ($bascetsum>0)?$rows["mymaterialtype"]:1;
		
        $PostZoneNumber = 0;
        $rows = $orderdetails_class->getPost($postindex);

		if($postindex){
        	//тариф по зоне обслуживания
        	//select * from Post where PostIndex='600023';
        	$PostZoneNumber = $rows["PostZone"];
        	$PostRegion = ($rows["Region"]?$rows["Region"]:$rows["Autonom"]);
        	$PostCity = ($rows["City"]?$rows["City"]:$PostRegion);
        }
    
        if (!$PostZoneNumber)	$PostZoneNumber = 5;
        if ($mymaterialtype!=0){
        	$PostZonePrice = $orderdetails_class::$PostZone[$PostZoneNumber] + $orderdetails_class::$PackageAddition[$PostZoneNumber]*($bascetpostweight<500?0:ceil(($bascetpostweight-500)/500));
        }else {
        	$PostZonePrice = $orderdetails_class::$PostZone1[$PostZoneNumber] + $orderdetails_class::$PackageAddition[$PostZoneNumber]*($bascetpostweight<500?0:ceil(($bascetpostweight-500)/500));
        }
        $PostAllPrice = $PostZonePrice + $PriceLatter + $bascetinsurance + $bascetsum;

		$FinalSum = $sum;
		
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

			$ParentArray = Array();				
			foreach ($result as $rows) {
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
                                    'amount' => "amount-".$rows["orderamount"],                       
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
                                    'amount' => "amount-".$rows["orderamount"],	                        
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
				
				if ($deletesubscribecoins && $tpl['user']['user_id'] && $rows["catalog"]) {
				    $shopcoins_class->deleteRow('catalogshopcoinssubscribe',"user='".$tpl['user']['user_id']."' and catalog='".$rows["catalog"]."'");			
				}
			}
			

			if (sizeof($ParentArray)>0) {				
				$result = $shopcoins_class->coinsParents($ParentArray);				
				foreach ($result as $rows) {
					var_dump($rows);
					$count  = $shopcoins_class->countChilds($rows["parent"]);
					
					$data_update = array('amountparent'=>$count);
					//сам update
					$shopcoins_class->updateRow($data_update,"shopcoins='".$rows["catalog"]."'"); 
				}
			}
			
			//проставляем amountparent			
			if ($tpl['user']['user_id']==811)	$needcallingorder=3;
			
			$CommentAdministrator = "";
			
			/*$sql22 = "select * from comentorder where  toorder=0 and ((`user`=".$tpl['user']['user_id']." and  `user`!=811) or (`user`=".$tpl['user']['user_id']." and fio='".strip_string($userfio)."'));";
			$result22 = mysql_query($sql22);
			
			if (mysql_num_rows($result22)>0) {
			
				$rows22 = mysql_fetch_array($result22);
				$CommentAdministrator = $rows22['CommentAdministratorF'];
				$sql_up22 = "update comentorder set toorder=$shopcoinsorder where comentorder=".$rows22['comentorder'].";";
				mysql_query($sql_up22);
			}*/
			
			
			if($bonus_comment === TRUE)
				$CommentAdministrator = $CommentAdministrator . 'Оплата бонусными деньгами.';
				
			$account_number = "";
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
                			 'OtherInformation'=>strip_tags($OtherInformation), 
                			 'CommentAdministrator' => strip_tags($CommentAdministrator),
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

			//подтверждаем заказ - order -------------------------------------------------------------------------------------------
						
			if($user_data['sms']==1&&in_array($delivery,array(1,3,4,6,7))) {
		        require_once $cfg['path'] . '/models/smssend.php';
            	$sms_class = new model_sms($cfg['db']);
				
				$textsms1 = "";
				
				switch ($delivery) {				
					case 1:
						$textsms1 = $MetroName." ".date('d.m.Y',$meetingdate);
						break;
					case 3:
						$textsms1 = $MetroName." ".date('d.m.Y',$meetingdate);
						break;
					case 4:
						$textsms1 = $MetroName." ".$paymentsms[$paymentvalue];
						break;
					case 7:
						$textsms1 = date('d.m.Y',$meetingdate);
						break;					
				}
				
				$textsms1 = str_replace("___1___",$textsms1,$arraytextsms2[$delivery]);				
				$testingsms = $sms_class->sendsms2 (1,$phone,$shopcoinsorder,($FinalSum>0?$FinalSum:$sum),$textsms1);
				$sms_class->addNewSms($tpl['user']['user_id'],$testingsms[1],$testingsms[2],$shopcoinsorder);
			}
			
			$order_class->updateRow(array('markadmincheck'=>3),"user='".$tpl['user']['user_id']."'  and `user`<>811 and `check`=1 and markadmincheck=2 and mark=2"); 	
						
			$countCoupon = $user_class->getUserCouponCount(array('`check`'=>1, 'type'=>2));
			if ($countCoupon==0) {				
				$couponup = 0;
				if (($sum-$sumamountprice - $vipcoinssum)>$bigsumcoupon && $typecoupon<2 && $tpl['user']['user_id']!=811) {				
					$dis = ceil(($sum-$sumamountprice - $vipcoinssum)*$bigsumcoupondis/100);
					$couponup=1;
				} elseif (($sum-$sumamountprice - $vipcoinssum)>$smallsumcoupon && $typecoupon<2 && $tpl['user']['user_id']!=811) {				
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
			
			
			
			$resultsum = ($FinalSum>0?$FinalSum:$sum);
			//рассылаем письма------------------------------------------------------------------------------------------------------
			$crcode  = md5("numizmatikru:".sprintf ("%01.2f",round($resultsum*$krobokassa,2)).":".$shopcoinsorder.":$robokassapasword1:Shp_idu=$cookiesuser");
			$culture = "ru";
			$in_curr = "BANKOCEAN2R";			
			
			if ($paymen==8)
				$user_order_details5 = "<tr><td colspan=6><form action='".$urlrobokassa."/Index.aspx' method=POST>".
   "<input type=hidden name=MrchLogin value='numizmatikru'>".
   "<input id=OutSum".$shopcoinsorder." type=hidden name=OutSum value='".sprintf ("%01.2f",round($resultsum*$krobokassa,2))."'>".
   "<input type=hidden name=InvId value='".$shopcoinsorder."'>".
   "<input type=hidden name=Desc value='Оплата предметов нумизматики'>".
   "<input id=SignatureValue".$shopcoinsorder." type=hidden name=SignatureValue value='$crcode'>".
   "<input type=hidden name=Shp_idu value='$cookiesuser'>".
   "<input type=hidden name=IncCurrLabel value='$in_curr'>".
   "<input type=hidden name=Culture value='$culture'>".
   "<input type=submit value='Оплатить VISA, MasterCard'> - <div id=info".$shopcoinsorder.">".sprintf ("%01.2f",round($resultsum*$krobokassa,2))." руб.</div> (При оплате банковскими картами комиссия 4%).".
   "</form></tr>";
			//получаем информацию о метро
			$tpl['submitorder']['MetroName'] ='';
			if ($metro and $delivery == 1) {
				$tpl['submitorder']['MetroName'] = $MetroArray[$metro];
			} elseif ($metro and ($delivery == 3)) {
				$metro_data = $order_class->getMetroName($metro);
				$tpl['submitorder']['MetroName'] = $metro_data['name'];
			}

			$mail_class = new mails();						
			$mail_class->orderLetter($user_data['email'],array());     
			//изменение информации о пользователе
			if ($tpl['user']['user_id'] != 811) {
			    $data = array('phone'=>$phone,
			                  'fio'=>$fio,
			                  'adress'=>$adress);
				$user_class->updateRow($data,"user='".$tpl['user']['user_id']."'"); 				
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
            
            unset($_SESSION['shopcoinsorder']);
            unset($_SESSION['order']);            
            
            session_start();
            session_destroy();
		}
	}
}