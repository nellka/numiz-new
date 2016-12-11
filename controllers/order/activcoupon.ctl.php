<?

require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/order.php';
$code1 = request('code1');
$code2 = request('code2');
$code3 = request('code3');
$code4 = request('code4');
$number = request('number');

$dateuse = '';
$dateout = '';

$order_class = new model_order($db_class);

$data_result = array();
$data_result['error'] = null;

$iscoupon = 0;
$iscoup = 0;

$dissum = 0;
$orderin = 0;

if ($code1 && $code2 && $code3 && $code4 && $tpl['user']['user_id'] && $tpl['user']['user_id']<>811 && $shopcoinsorder) {

    $user_data =  $user_class->getUserData();
    if($user_data['vip_discoint']&&(!$user_data['vip_discoint_date_end']||$user_data['vip_discoint_date_end']>time())){
        $data_result['error'] = "error3";        
    } else {
    	//получаем данные о введенном купоне
    	$code = strtolower($code1."-".$code2."-".$code3."-".$code4);
    
    	if (preg_match("/[^-0-9a-zA-Z]{19}/",$code)) {
    		$error = "error1";
    	} else {
    		$couponData = $user_class->getUserCoupon(array('code'=>$code,'type'=>1));
    
    		$friendCoupon = $user_class->getFriendCouponCode();

    		if(!$couponData){
    			$data_result['error'] = 'error1';
    		} else {
    		    $couponData = $couponData[0];
				
        		if($couponData['check'] == 0) {
        			//купон уже использован
        			$data_result['error'] = 'error5';
        			$result_tmp = $order_class->getUseCoupon($couponData['coupon']);
        			if ($result_tmp) {
        				$orderin = $result_tmp['order'];
        				$dateuse = $result_tmp['dateinsert'];
        			}    
        		} elseif ($couponData['dateend']<time()) {
					$data_result['error'] = "error6";
        			$dateout = $couponData['dateend'];
        		} elseif($friendCoupon==$code){
        			//введен купон приведи друга
        			$dissum = $couponData['sum'];
        		} else {
        			$dissum = $couponData['sum'];    			
        		} 
    		}
    	} 
    } 
} else $data_result['error'] = "error1";

$dissum_text =  "";

if ($dissum) {
	$dissum_text = $dissum.".00, ";
}


$data_result['dissum'] = $dissum;
$data_result['dissumtext'] = $dissum_text;
$data_result['orderin'] = $orderin;
$data_result['dateuse'] = $dateuse?date('d-m-Y',$dateuse):"";
$data_result['dateout'] = $dateout?date('d-m-Y',$dateout):"";
$data_result['number'] = $number;

echo json_encode($data_result);
/*
if ($iscoupon && $code1 && $code2 && $code3 && $code4 && $user && $user<>811 && $shopcoinsorder) {

	$iscoupon = intval($iscoupon);
	$shopcoinsorder = intval($shopcoinsorder);
	$user = intval($user);

	$code = strtolower($code1."-".$code2."-".$code3."-".$code4);
	//echo $code;

	if (preg_match("/[^-0-9a-zA-Z]{19}/",$code))
		$error = "error2";
	elseif ($iscoupon == 1) {

		$sql = "select * from coupon where `user`='$user' and `code`='$code' and type='$iscoupon';";
		$result = mysql_query($sql);
		//echo $sql;
		if (mysql_num_rows($result)==1) {

			$rows = mysql_fetch_array($result);

			if ($rows['check'] == 0) {
				$error = "error5";
				$sql_tmp = "select * from ordercoupon where coupon='".$rows['coupon']."';";
				$result_tmp = mysql_query($sql_tmp);
				if (@mysql_num_rows($result_tmp)>0) {

					$rows_tmp = mysql_fetch_array($result_tmp);
					$orderin = $rows_tmp['order'];
					$dateuse = $rows_tmp['dateinsert'];
				}
			}
			elseif ($rows['dateend']<time()) {

				$error = "error6";
				$dateout = $rows['dateend'];
			}
			else {

				$dissum = $rows['sum'];
				$sql_tmp = "select * from ordercoupon where coupon='".$rows['coupon']."';";
				$result_tmp = mysql_query($sql_tmp);
				//echo $sql_tmp;
				if (@mysql_num_rows($result_tmp)==0) {

					PostSum ($postindex, $shopcoinsorder, $clientdiscount);
					if ($bascetsum >0) {

						$sql_ins = "insert into ordercoupon (`ordercoupon`,`coupon`,`order`,`dateinsert`,`check`)
								values (NULL, '".$rows['coupon']."','$shopcoinsorder','".time()."','1');";
						$result_ins = mysql_query($sql_ins);
					}
					else
						$error = "error7";
				}
				else {

					$error = "error8";
					$rows_tmp = mysql_fetch_array($result_tmp);
					$orderin = $rows_tmp['order'];
					$dateuse = $rows_tmp['dateinsert'];
				}
			}
		}
		else
			$error = "error4";
	}
	elseif ($iscoupon == 2) {

		$sql = "select * from coupon where `user`='$user' and `code`='$code' and type='1' and `order`='0';";
		$result = mysql_query($sql);
		//echo $sql;
		if (mysql_num_rows($result)==1) {

			$rows = mysql_fetch_array($result);

			if ($rows['check'] == 0) {
				$error = "error5";
				$sql_tmp = "select * from ordercoupon where coupon='".$rows['coupon']."';";
				$result_tmp = mysql_query($sql_tmp);
				if (@mysql_num_rows($result_tmp)>0) {

					$rows_tmp = mysql_fetch_array($result_tmp);
					$orderin = $rows_tmp['order'];
					$dateuse = $rows_tmp['dateinsert'];
				}
			}
			elseif ($rows['dateend']<time()) {

				$error = "error6";
				$dateout = $rows['dateend'];
			}
			else {

				$dissum = $rows['sum'];
				$sql_tmp = "select * from ordercoupon where coupon='".$rows['coupon']."';";
				$result_tmp = mysql_query($sql_tmp);
				//echo $sql_tmp;
				if (@mysql_num_rows($result_tmp)==0) {

					PostSum ($postindex, $shopcoinsorder, $clientdiscount);
					if ($bascetsum >0) {

						$sql_ins = "insert into ordercoupon (`ordercoupon`,`coupon`,`order`,`dateinsert`,`check`)
								values (NULL, '".$rows['coupon']."','$shopcoinsorder','".time()."','1');";
						$result_ins = mysql_query($sql_ins);
					}
					else
						$error = "error7";
				}
				else {

					$error = "error8";
					$rows_tmp = mysql_fetch_array($result_tmp);
					$orderin = $rows_tmp['order'];
					$dateuse = $rows_tmp['dateinsert'];
				}
			}
		}
		else
			$error = "error3";
	}
	else
		$error = "error1";
}
else
	$error = "error1";

if ($dissum) {

	$sql = "select coupon.* from ordercoupon, coupon where ordercoupon.order='".$shopcoinsorder."' and ordercoupon.`check`=1 and coupon.coupon=ordercoupon.coupon group by coupon.coupon order by coupon.type desc;";
	$result = mysql_query($sql);
	$dissum = '';
	while ($rows = mysql_fetch_array($result)) {

		if ($rows['type']==2)
			$dissum .= "VIP ".$rows['sum']."%, ";
		else
			$dissum .= $rows['sum'].".00, ";
	}
}*/
die();
?>