<?
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require $cfg['path'] . '/configs/config_shopcoins.php';

$payment = request('payment');
$userfio = request('userfio');
$fio = request('fio');
$phone = request('phone');
$email = request('email');
$delivery = request('delivery');
$metro = request('metro');
$meetingdate = request('meetingdate');
$meetingfromtime = request('meetingfromtime');
$meetingtotime = request('meetingtotime');
$postindex = request('postindex');
$adress = request('adress');
$OtherInformation = request('OtherInformation');
$from_ubb =  request('from_ubb');

if(!$payment || !$userfio ||!$fio){    
    $tpl['submitorder']['error'] = true;
} elseif (!$tpl['user']['user_id']||!$shopcoinsorder) {
    $tpl['submitorder']['error_auth'] = true;
} else {  
    
    $order_class = new model_order($cfg['db'],$shopcoinsorder,$tpl['user']['user_id']);
    $orderdetails_class = new model_orderdetails($cfg['db'],$shopcoinsorder);

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
		echo "<p class=txt> <font color=red><b>Заказ $shopcoinsorder уже оформлен Вами. Вы можете его просомтреть в \"Ваши заказы\"</b></font></p>";
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
		
		$vipcoinssum = 0;
		$sum = 0;	
		$sumamountprice = 0;		
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
				
        $iscoupon = $user_class->getUserCouponType();
		$discountcoupon = 0;
		$ArrayCouponNull = array();
		if ($iscoupon==2) {
		    die('активируем купон');
		/*
			$sql_tmp = "select * from ordercoupon where coupon='".$rows['coupon']."' and `order`='".$shopcoinsorder."';";
			$result_tmp = mysql_query($sql_tmp);
			//echo $sql_tmp;
			if (@mysql_num_rows($result_tmp)==0) {
				
				$sql_ins = "insert into ordercoupon (`ordercoupon`,`coupon`,`order`,`dateinsert`,`check`) 
					values (NULL, '".$rows['coupon']."','$shopcoinsorder','".time()."','1');";
				$result_ins = mysql_query($sql_ins);
				
			}*/
		}
		/*
		$sql = "select coupon.* from coupon,ordercoupon where ordercoupon.order='".$shopcoinsorder."' and ordercoupon.`check`=1 and coupon.coupon=ordercoupon.coupon and coupon.check=1 group by coupon.coupon order by coupon.type desc, coupon.dateinsert desc;";
		$result = mysql_query($sql);
		$discountcoupon = 0;
		$typecoupon = 0;
		if (@mysql_num_rows($result)>0) {		
			$typec = 1;
			while ($rows = mysql_fetch_array($result)) {
			
				if ($rows['type']==2 && $typec==1) {
				
					$discountcoupon = floor(($sum-$sumamountprice-$vipcoinssum)*$rows['sum']/100);
					$typecoupon = 2;
					$typec = 2;
				}
				elseif ($rows['type']==1 && ($typec==1 || ($typec==2 && $rows['order']==0))) {
				
					if ($rows['dateend']>=time())
						$discountcoupon += $rows['sum'];
						
					if (!$typecoupon)
						$typecoupon = 1;
					
					$ArrayCouponNull[] = $rows['coupon'];
				}
			}
			
			
		}*/
		
		if ($discountcoupon<0) $discountcoupon = 0;
		
		if ( $sum < $discountcoupon) {			
			$discountcoupon = $sum;
		}
		
		if (sizeof($ArrayCouponNull)) {
		    die('ArrayCouponNull');
			$sql_up = "update coupon set `check`=0 where `coupon` in('".implode("'",$ArrayCouponNull)."') and `check`=1 and type=1;";
			mysql_query($sql_up);
		}
		
		$sum = $sum - $discountcoupon;			
		
		/*if ($paymentvalue == 1 || $paymentvalue == 5) { // для разного типа оплаты считаем сумму налога
			$_GoogleStatUser .= round($sum * 0.06,2).'|';
		} else {
			$_GoogleStatUser .= '0|';
		}*/
		
		$rows = $orderdetails_class->forBasket($clientdiscount);

        $bascetsum = $rows["mysum"];
        $amountbascetsum = $rows['mysumamount'];
        $vipcoinssum = $rows['vipcoinssum'];
		$bascetweight = $rows["myweight"];
		
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
				if (!trim($found[0][0])){
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
			var_dump($result);
			foreach ($result as $rows){
				$sql_delete = "delete from `orderdetails` 
				where `order` = '$shopcoinsorder' and catalog='".$rows["catalog"]."';";
				$result_delete = mysql_query($sql_delete);
				//echo $sql_delete;
			}
			
			//делаем update единичный товаров и изменение количества у других------------------------------------------------------
			$result = $orderdetails_class->getOrderDetails();
			$ParentArray = Array();				
			foreach ($result as $rows) {
				$ParentArray[] = $rows["parent"];
				//монеты, боны, подарочные наборы
				if ($rows["materialtype"]==1){
					
					if ($rows['relationcatalog']>0 && $rows['userreserve']>0 && $rows['userreserve'] != $tpl['user']['user_id']) {
					
						$sql_info = "select shopcoins.*, catalogshopcoinssubscribe.catalog 
						from `shopcoins`, catalogshopcoinsrelation, catalogshopcoinssubscribe  
						WHERE shopcoins.shopcoins = '".$rows['shopcoins']."' and shopcoins.materialtype = '1'
						and catalogshopcoinsrelation.shopcoins = shopcoins.shopcoins
						and catalogshopcoinssubscribe.catalog = catalogshopcoinsrelation.catalog 
						and catalogshopcoinssubscribe.user = '".$rows['userreserve']."';";
						
						$result_info = mysql_query($sql_info);
						
						if (mysql_num_rows($result_info) == 0) {
						
							$rows_info = mysql_fetch_array($result_info);
							
							$sql_ins = "insert into catalogshopcoinssubscribe 
								(user, catalog, dateinsert,
								datesend, amountdatesend, buy,amount)
								values
								('".$rows['userreserve']."', '".$rows_info['catalog']."', '".time()."',
								'0', '0', '0','1');";
							mysql_query($sql_ins);
						}
					}
					
					$sql_update = "update shopcoins 
					set 
					`check`=0, reserveorder='".$shopcoinsorder."', reserve = 0, amount = 0, doubletimereserve=0, userreserve=0, dateorder=".time()." 
					where shopcoins='".$rows["catalog"]."';";
					$result_update = mysql_query($sql_update);
					
					/* 2015-06-11 Молоток уже не работает
					$sql_m = "select * from shopcoinsmolotok where shopcoins='".$rows["catalog"]."' and `status`=1;";
					$result_m = mysql_query($sql_m);
					if (mysql_num_rows($result_m)>0) {
					
						$sql_up = "update shopcoinsmolotok set `status`=4 where shopcoins='".$rows["catalog"]."' and `status`=1;";
						mysql_query($sql_up);
					}
					*/
				}
				elseif ($rows["materialtype"]==4 || $rows["materialtype"]==7 || $rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==2 || $rows["materialtype"]==12)
				{
					
					$sql_update = "update shopcoins 
					set  amount=amount-".$rows["orderamount"]."
					".($rows["orderamount"]>=$rows["samount"]?", `check`=0":"")."
					, reserveorder='".$shopcoinsorder."', dateorder=".time()."
					where shopcoins='".$rows["catalog"]."';";
					
					/*echo $sql_update;
					echo "<br>".$rows["orderamount"];
					$sql_myinfo = "select * from shopcoins where shopcoins='".$rows["catalog"]."';";
					$result_myinfo = mysql_query($sql_myinfo);
					$rows_myinfo = mysql_fetch_array($result_myinfo);
					echo "<br>Amount = ".$rows_myinfo["amount"];*/
					
					$result_update = mysql_query($sql_update);
					
					
					mysql_query("delete from helpshopcoinsorder where shopcoins='".$rows["catalog"]."' and reserveorder='".$shopcoinsorder."'");
				}
				//аксессуары, книги
				elseif ($rows["materialtype"]==3 || $rows["materialtype"]==5)
				{
					$sql_update = "update shopcoins set amount=amount-".$rows["orderamount"]."
					".($rows["orderamount"]>=$rows["samount"]?", `check`=0":"").", dateorder=".time()."
					where shopcoins='".$rows["catalog"]."';";
					$result_update = mysql_query($sql_update);
					
					if ($rows["orderamount"]>=$rows["samount"])
					{
						$sql_amount = "insert into shopcoinsend 
						(shopcoinsend, shopcoins, insertdate, updatedate, check)
						values 
						(0, '".$rows["catalog"]."', '".$timenow."', '0', 1);";
						$result_amount = mysql_query($sql_amount);
					}
				}
				// другое
				else
				{
					$sql_update = "update shopcoins 
					set 
					`check`=0, reserveorder='".$shopcoinsorder."', dateorder=".time()."
					where shopcoins='".$rows["catalog"]."';";
					$result_update = mysql_query($sql_update);
				}
				
				if ($deletesubscribecoins && $user && $rows["catalog"]) {
				
					$sql_del = "delete from catalogshopcoinssubscribe where `user`='$user' and `catalog`='".$rows["catalog"]."';";
					$result_del = mysql_query($sql_del);
				}
			}
			
			if (sizeof($ParentArray)>0)
			{
				$sql="SELECT shopcoins.*, if((shopcoins.realization=0 or shopcoins.dateinsert>".($timenow-4*24*3600)."),0,1) as param FROM shopcoins WHERE (shopcoins.check =1 or shopcoins.`check`>3) and parent in (".implode(",", $ParentArray).") GROUP BY shopcoins.parent order by shopcoins.`check` asc, param asc, shopcoins.dateinsert desc;";
				//if ($REMOTE_ADDR == $myip)	echo $sql;
				$result = mysql_query($sql);
				while ($rows = mysql_fetch_array($result))
				{
					$sql_info = "select count(*) from shopcoins where (`check`='1' or `check`>3) and parent='".$rows["parent"]."';";
					$result_info = mysql_query($sql_info);
					$rows_info = mysql_fetch_array($result_info);
					if ($REMOTE_ADDR == $myip) echo "<br>".$sql_info;
					
					//сам update
					$sql_update = "update shopcoins set amountparent='".$rows_info[0]."' where shopcoins='".$rows["shopcoins"]."';";
					$result_update = mysql_query($sql_update);
					if ($REMOTE_ADDR == $myip) echo "<br>".$sql_update;
					//exit;
				}
			}
			
			//проставляем amountparent
			
			if ($user==811)
				$needcallingorder=3;
			
			$sql22 = "select * from comentorder where  toorder=0 and ((`user`=$user and  `user`!=811) or (`user`=$user and fio='".strip_string($userfio)."'));";
			$result22 = mysql_query($sql22);
			$CommentAdministrator = "";
			if (mysql_num_rows($result22)>0) {
			
				$rows22 = mysql_fetch_array($result22);
				$CommentAdministrator = $rows22['CommentAdministratorF'];
				$sql_up22 = "update comentorder set toorder=$shopcoinsorder where comentorder=".$rows22['comentorder'].";";
				mysql_query($sql_up22);
			}
			
			
			if($bonus_comment === TRUE)
				$CommentAdministrator = $CommentAdministrator . 'Оплата бонусными деньгами.';
				
			//подтверждаем заказ - order -------------------------------------------------------------------------------------------
			$sql = "update `order` set user='".$user."',
			userfio = '".strip_string($userfio)."', 
			phone='".strip_string($phone)."', adress='".strip_string(str_replace("|","/",$adress))."', 
			admincheck=0, sum='".$sum."', delivery='".strip_string($delivery)."', payment='".strip_string($paymentvalue)."',
			MetroMeeting = '$metro', DateMeeting = '".($meetingdate + $meetingfromtime)."', 
			MeetingFromTime='".$meetingfromtime."', 
			MeetingToTime='".$meetingtotimevalue."', 
			OtherInformation='".strip_string($OtherInformation)."', 
			CommentAdministrator = '".strip_string($CommentAdministrator)."',
			account_number='".strip_string($account_number)."', `check`=1,
			FinalSum = '$FinalSum' , NeedCall = '2', suminsurance='0', CallingOrder='$needcallingorder' ".(intval($idadmin)>0?",addidadmin=".intval($idadmin):"")."
			where `order`='".$shopcoinsorder."';";
			$result = mysql_query($sql);
			echo mysql_error();
			
			if ($cookiesuser>0 && $cookiesuser!=811 && ($delivery == 1 || $delivery == 3 || $delivery == 4 || $delivery == 6 || $delivery == 7)) {
			
				$sql9 = "select sms from `user` where `user`=".intval($cookiesuser).";";
				$result9 = mysql_query($sql9);
				$rows9 = mysql_fetch_array($result9);
				
				if ($rows9['sms']==1) {
				
					$sqls = "SHOW TABLE STATUS LIKE 'smssend';";
					$results = mysql_query($sqls);
					$rowss = mysql_fetch_array($results);
					$transactionsms = $rowss['Auto_increment'];
					
					include_once $in."smsconfig.php";
					
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
					$testingsms = sendsms2 (1,$phone,$shopcoinsorder,($FinalSum>0?$FinalSum:$sum),$textsms1);
					$sqlsms = "insert into `smssend` (`smssend`,`user`,`phone`,`text`,`status`,`order`,`type`,`dateinsert`)
								values (NULL,$cookiesuser,'".$testingsms[1]."','".$testingsms[2]."',1,$shopcoinsorder,1,".time().");";
						mysql_query($sqlsms);
				}
				
			}
			
			$sql = "update `order` set markadmincheck=3 where `user`='$cookiesuser' and `user`<>811 and `check`=1 and markadmincheck=2 and mark=2;";
			$result = mysql_query($sql);
			
			$sql = "select * from coupon where `user`='$cookiesuser' and `check`=1 and type=2;";
			$result = mysql_query($sql);
			if (mysql_num_rows($result)==0) {
				
				$couponup = 0;
				if (($sum-$sumamountprice - $vipcoinssum)>$bigsumcoupon && $typecoupon<2 && $user!=811) {
				
					$dis = ceil(($sum-$sumamountprice - $vipcoinssum)*$bigsumcoupondis/100);
					$couponup=1;
				}
				elseif (($sum-$sumamountprice - $vipcoinssum)>$smallsumcoupon && $typecoupon<2 && $user!=811) {
				
					$dis = ceil(($sum-$sumamountprice - $vipcoinssum)*$smallsumcoupondis/100);
					$couponup=1;
				}
				
				if ($couponup==1) {
				
					srand((double) microtime()*1000000);
					while ($couponup==1) {
						
						$code = '';
						for ($i=0;$i<16;$i++) {
						
							if ($i==4 || $i == 8 || $i==12)
								$code .= "-";
								
							$code .= $ArrayForCode[rand(0,9)];
						}
						
						$sql = "select count(*) from coupon where code='".$code."';";
						$result = mysql_query($sql);
						$rows = mysql_fetch_array($result);
						if ($rows[0] == 0) 
							$couponup = 2;
					}
					
					if ($delivery==1 || $delivery==2 || $delivery==3 || $delivery==7)
						$dateend = $timenow + 31*24*60*60 -1;
					elseif ($delivery==5 || $delivery==6 || ($delivery==4 && $paymentvalue!=1))
						$dateend = $timenow + (46+$PostZone2[$PostZoneNumber])*24*60*60 -1;
					elseif ($delivery==4 && $paymentvalue==1)
						$dateend = $timenow + (31+$PostZone2[$PostZoneNumber])*24*60*60 -1;
					else
						$dateend = $timenow + 31*24*60*60 -1;
					
					$sql_ins = "insert into coupon (`coupon`,`user`,`order`,`sum`,`code`,`dateend`,`type`,`dateinsert`,`check`)
							values(NULL,'".$user."','".$shopcoinsorder."','".$dis."','".$code."','".$dateend."','1','".time()."','1');";
					//echo $sql_ins;
					mysql_query($sql_ins);
				}
			}
			
			
			
			$resultsum = ($FinalSum>0?$FinalSum:$sum);
			//рассылаем письма------------------------------------------------------------------------------------------------------
			$crcode  = md5("numizmatikru:".sprintf ("%01.2f",round($resultsum*$krobokassa,2)).":".$shopcoinsorder.":$robokassapasword1:Shp_idu=$cookiesuser");
			$culture = "ru";
			$in_curr = "BANKOCEAN2R";
			
			$user_order_details2 .= "</td>
			</tr>";
			
			$user_order_details .= "</tr></table>
			<br>$AdvertiseText
			<br><br>Спасибо за покупку в нашем магазине !
			<br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a><br><br>";
			
			$user_order_details5 = '';
			
			if ($paymentvalue==8)
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
			
			
			
			$user_order_details = str_replace('____VISAPAYMENT___',$user_order_details5,$user_order_details);
			echo $user_order_details;
			$user_order_details = $user_order_details0.$user_order_details.$user_order_details2;
			
			$file = fopen($in."mail/top.html", "r");
			while (!feof ($file)) 
			{
					$message .= fgets ($file, 1024);
				}
			fclose($file);
			
			$message = $message.$user_order_details;
			
			$file = fopen($in."mail/bottom.html", "r");
			while (!feof ($file)) 
			{
					$message .= fgets ($file, 1024);
			}
			fclose($file);
			
			$recipient = "bodka@mail.ru";
			$subject = "Монетная лавка | Клуб Нумизмат";
			$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
			mail($recipient, $subject, $message.$order."\t".time()."\t".$HTTP_SERVER_VARS["HTTP_USER_AGENT"]."\n".$REMOTE_ADDR, $headers); //админу сайта
			
			$recipient = $email;
			mail($recipient, $subject, $message, $headers); //покупателю
			
			//изменение информации о пользователе
			if ($cookiesuser != 811) {
				
				$sql = "update user set 
				".(strip_string($email)?"email='".strip_string($email)."',":"")." phone='".strip_string($phone)."'".(trim($adress)?" , adress='".strip_string($adress)."' ":"")."
				where userlogin='$cookiesuserlogin' and userpassword='$cookiesuserpassword' and user='$cookiesuser';";
				
				$result = mysql_query($sql);
				echo mysql_error();
			}	
			
			//удаляем cookies пользователя------------------------------------------------------------------------------------------
			echo "<img src=deleteusercookies.php width=0 height=0>
			<img src=../deleteusercookies.php width=0 height=0>
			<img src=inleft/index.php width=0 height=0>";
		}
	}
}

/*
function PostSum ($postindex, $shopcoinsorder, $clientdiscount)
{
	//$TypePostZone - 0 - ???????, 1 ?????????
	global $shopcoinsorder, $checking, $BascetName, $REMOTE_ADDR;
	global $TypePostZone, $PostZone, $PostZone1, $PriceLatter, $PackageAddition, $PostAllPrice;
	global $bascetsum, $bascetweight, $bascetinsurance, $bascetamount, $orderstarttime;
	global $bascetreservetime, $bascetpostweightmin, $bascetpostweightmax, $WeightCoins,$bascetpostweight;
	global $WeightPostBox, $WeightPostLatter, $bascetpostweight, $PostZoneNumber, $PostRegion, $PostZonePrice, $PostCity, $suminsurance,$discountcoupon,$amountbascetsum,$vipcoinssum;
	
	//$shopcoinsorder = intval($shopcoinsorder);
	//$clientdiscount = intval($clientdiscount);
	//??????? ??????? - ??? ????????? ?? ??????? pi*d^3*10.5/80
	$sql = "select sum(orderdetails.amount*
		if
		(orderdetails.amount>=shopcoins.amount5 and shopcoins.price5>0,shopcoins.price5,
			if
			(orderdetails.amount>=shopcoins.amount4 and shopcoins.price4>0,shopcoins.price4,
				if
				(orderdetails.amount>=shopcoins.amount3 and shopcoins.price3>0,shopcoins.price3,
					if
					(orderdetails.amount>=shopcoins.amount2 and shopcoins.price2>0,shopcoins.price2,
						if
						(orderdetails.amount>=shopcoins.amount1 and shopcoins.price1>0,shopcoins.price1,
							".($clientdiscount==1?"if(shopcoins.clientprice>0,shopcoins.clientprice,shopcoins.price)":"shopcoins.price")."
						)
					)
				)
			)
		)
	) as mysum,
	sum(orderdetails.amount*
		if
		(orderdetails.amount>=shopcoins.amount5 and shopcoins.price5>0,shopcoins.price5,
			if
			(orderdetails.amount>=shopcoins.amount4 and shopcoins.price4>0,shopcoins.price4,
				if
				(orderdetails.amount>=shopcoins.amount3 and shopcoins.price3>0,shopcoins.price3,
					if
					(orderdetails.amount>=shopcoins.amount2 and shopcoins.price2>0,shopcoins.price2,
						if
						(orderdetails.amount>=shopcoins.amount1 and shopcoins.price1>0,shopcoins.price1,0)
					)
				)
			)
		)
	) as mysumamount,
	sum(orderdetails.amount*if(shopcoins.materialtype=12,shopcoins.price,0)) as vipcoinssum,
	sum(orderdetails.amount * if
			(shopcoins.weight=0,
				if
				(
				materialtype=1,round(0.412*shopcoins.width*shopcoins.width*shopcoins.width/1000),
					if 
					(
					materialtype=2||materialtype=8||materialtype=6,1,
						if
						(
							materialtype=4,100,
							if
							(
								materialtype=7,40,$WeightCoins
							)
						)
					)
				)
			,shopcoins.weight)
		) as myweight,
	sum(if(shopcoins.materialtype=2,0,1)) as mymaterialtype 
	from orderdetails, shopcoins 
	where ".(sizeof($shopcoinsorder)>1?"orderdetails.order in (".implode(",",$shopcoinsorder).")":"orderdetails.order='".$shopcoinsorder."'")."
	and orderdetails.catalog=shopcoins.shopcoins and orderdetails.status=0
	".($checking?"":"and (shopcoins.`check`='1' or shopcoins.`check`>'3')").";";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$bascetsum = $rows["mysum"];
	$amountbascetsum = $rows['mysumamount'];
	$vipcoinssum = $rows['vipcoinssum'];
	//echo $sql;
	
	$sql2 = "select coupon.* from ordercoupon, coupon where ".(sizeof($shopcoinsorder)>1?"ordercoupon.order in (".implode(",",$shopcoinsorder).")":"ordercoupon.order='".$shopcoinsorder."'")." and ordercoupon.order>0 and ordercoupon.`check`=1 and coupon.coupon=ordercoupon.coupon group by coupon.coupon order by coupon.type desc, coupon.dateinsert desc;";
	//echo $sql." = ".$checking;
	$result2 = mysql_query($sql2);
	$discountcoupon = 0;
	unset($arraycoupcode);
	if (@mysql_num_rows($result2)>0) {
	
		$typec = 1;
		while ($rows2 = mysql_fetch_array($result2)) {
		
			
			if ($rows2['type']==2 && $typec==1) {
			
				$discountcoupon = floor(($bascetsum-$amountbascetsum-$vipcoinssum)*$rows2['sum']/100);
				$typec = 2;
				$arraycoupcode[] = "VIP";
			}
			elseif ($rows2['type']==1 && ($typec==1 || ($typec==2 && $rows2['order']==0))) {
			
				$discountcoupon += $rows2['sum'];
				$arraycoupcode[] = strtoupper($rows2['code']);
			}
		}
	}
	if ($discountcoupon<0)
		$discountcoupon = 0;
	$bascetsum = $bascetsum - $discountcoupon;
	if ($bascetsum<0)
		$bascetsum = 0;
	//if ($REMOTE_ADDR=="89.175.153.189")
		//echo $bascetsum."- ".$sql;
	$bascetweight = $rows["myweight"];
	
	if ($bascetsum>0)
		$mymaterialtype = $rows["mymaterialtype"];
	else
		$mymaterialtype = 1;
	
	if ($TypePostZone==1)
		$mymaterialtype = 0;
	
	//????????? ??? ?????????
	$sql = "select sum(suminsurance) as suminsurance from `order` where ".(sizeof($shopcoinsorder)>1?"`order`.`order` in (".implode(",",$shopcoinsorder).")":"`order`.`order`='".$shopcoinsorder."'").";";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);	
	{
		$bascetinsurance = $bascetsum * 0.04;
		unset ($suminsurance);
	}
	
	
	$sql = "select count(catalog) as counter from orderdetails as o, shopcoins as s
	where ".(sizeof($shopcoinsorder)>1?"o.order in (".implode(",", $shopcoinsorder).")":"o.order='".$shopcoinsorder."'")."
	and o.catalog=s.shopcoins ".($checking?"":"and s.`check`='1'")." and o.status=0;";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$bascetamount = $rows["counter"];
	
	//if ($REMOTE_ADDR=="194.85.82.223")
		//echo $bascetsum."-".$sql;
	
	//???????? ????, ?????????
	$sql = "select count(catalog) as postcounter from orderdetails as o, shopcoins as s 
	where ".(sizeof($shopcoinsorder)>1?"o.order in (".implode(",", $shopcoinsorder).")":"o.order='".$shopcoinsorder."'")."
	and o.catalog=s.shopcoins ".($checking?"":"and s.`check`='1'")." 
	and s.materialtype<>'1' and s.materialtype<>2 and o.status=0;";
	//echo $sql;
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	if ($rows["postcounter"]>0)
		$bascetpostweight = $bascetweight + $WeightPostBox;
	else
		$bascetpostweight = $bascetweight + $WeightPostLatter;
	
	//????? ?? ???? ????????????
	$sql = "select * from Post where PostIndex='".$postindex."';";
	//select * from Post where PostIndex='600023';
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$PostZoneNumber = $rows["PostZone"];
	if (!$PostZoneNumber)
		$PostZoneNumber = 5;
	
	$PostRegion = ($rows["Region"]?$rows["Region"]:$rows["Autonom"]);
	$PostCity = ($rows["City"]?$rows["City"]:$PostRegion);
	if ($mymaterialtype!=0)
	{
		$PostZonePrice = $PostZone[$PostZoneNumber] + $PackageAddition[$PostZoneNumber]*($bascetpostweight<500?0:ceil(($bascetpostweight-500)/500));
	}
	else
	{
		$PostZonePrice = $PostZone1[$PostZoneNumber] + $PackageAddition[$PostZoneNumber]*($bascetpostweight<500?0:ceil(($bascetpostweight-500)/500));
	}
	$PostAllPrice = $PostZonePrice + $PriceLatter + $bascetinsurance + $bascetsum;
	
	if ($checking)
	{
		$sql = "select s.* from orderdetails as o, shopcoins as s 
		where ".(sizeof($shopcoinsorder)>1?"o.order in (".implode(",", $shopcoinsorder).")":"o.order='".$shopcoinsorder."'")."
		and o.catalog=s.shopcoins and o.status=0;";
		$result = mysql_query($sql);
		$BascetNameArray = Array();
		while ($rows = mysql_fetch_array($result))
			$BascetNameArray[] = $rows["name"];
		
		$BascetName = implode(", ", $BascetNameArray);
	}
	return $arraycoupcode;
}*/
