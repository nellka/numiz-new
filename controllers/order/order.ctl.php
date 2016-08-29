<?
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require $cfg['path'] . '/configs/config_shopcoins.php';

$order = request("order");
$action = request("action");
$page2 = request("page2");

$type = Array ("rusichbank"=>'Монеты',
				"shopcoins"=>'Монеты',
				"Album"=>'Аксессуары',
				"Book"=>'Книги',
				"programs"=>'Программы');
				


if (!$tpl['user']['user_id']){
    $tpl['shop']['errors'][] = "Авторизуйтесь!";
    
    header("location: ".$cfg['site_dir']."shopcoins?page=orderdetails");
    /*else {
		echo "Для просмотра заказов необходима авторизация!";
		echo form_login($error);
	}*/
    die();	
} else {
    $order_class = new model_order($db_class,$shopcoinsorder,$tpl['user']['user_id']);
    $orderdetails_class = new model_orderdetails($db_class);
	if (!$action) $action = "showorders";
	
	if ($action=="postreceipt" && $parent) {
	   
		$mark = (int) request('mark');
		$parent = (int) request('parent');
		$Reminder = (int) request('Reminder');
		$ReminderComment = request('ReminderComment');
		
		if ($Reminder==3) {
			$complected = (int) request('complected');
		} else	$complected = 0;
		
		$data_update = array('Reminder' => $Reminder, 
		                     'ReminderComment' => $ReminderComment, 
		                     'ReminderCommentDate' => time(), 
		                     'mark'=>$mark,
		                     'complected'=>$complected);
		                     
	    if($Reminder==3) $data_update['PhonePostReceipt'] = 1;
		$order_class->updateRow($data_update,"`order`=".$parent);  		
		$action = "showorders";
	}
	
	$tpl['task'] = $action;
	
	if ($action=="showorders")	{		
		$tpl['orders'] = $order_class->getLastOrders();		
		$i = 0;
		foreach ($tpl['orders'] as $rows) {				    	
			$dissert = 0;
						
			if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"]) {
				$result9 = $order_class->getOrdergiftcertificate($rows["order"]);
				foreach ((array)$result9 as $rows9)
					$dissert += $rows9['sum'];
				
			}
			$tpl['orders'][$i]['dissert'] = $dissert;
			//if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"] && $rows["ParentOrder"]==0 && (($rows['payment'] !=1 && $rows['payment'] !=2 && ($rows['delivery']==4 || $rows['delivery']==6)) || (($rows['delivery']==10 || $rows['delivery']==2) && ($ipmyshop==$_SERVER['REMOTE_ADDR'] || $_SERVER['REMOTE_ADDR']=="127.0.0.1"))))
			{
			
				$resultsum = ($rows['SumAll']>0?$rows['SumAll']:($rows['FinalSum']>0?$rows['FinalSum']:$rows['sum']))-$dissert;
				
				if ($rows["delivery"] == 4 || $rows["delivery"] == 6 || $rows['delivery']==10 || $rows['delivery']==2) {
	
					$shopcoinsorders = array();
					
					$shopcoinsorders[] = $rows['order'];
					$result3 = $order_class->getAllByParams(array('ParentOrder'=>$rows['order']));

					foreach ($result3 as $rows3) {					
						$shopcoinsorders[] = $rows3['order'];
					}
					
					/*if (sizeof($shopcoinsorders)<2)
						$shopcoinsorders = $shopcoinsorders[0];*/
					
					$clientdiscount = $orderdetails_class->getClientdiscount($tpl['user']['user_id']);
					$postindex = 0;
					
					preg_match_all('/\d{6}/', $rows["adress"], $found);		
	
    				if ($found&&isset($found[0])&&isset($found[0][0])){
    					$postindex = trim($found[0][0]);
    				}			
					
					$checking = 1;
					
					//unset ($PostAllPrice);
					//unset ($suminsurance);
					
					if (!$postindex) $postindex = "690000";
					
					//if ($postindex){
						$bastet_details = $orderdetails_class->PostSum($postindex, $clientdiscount, $shopcoinsorder,$shopcoinsorders);
						$bascetsum = $bastet_details['bascetsum'];
						//var_dump($bastet_details);
				//echo "<br>";
						$bascetpostweight = $bastet_details['bascetpostweight'];	
						$PostAllPrice = $bastet_details['PostAllPrice'];				
					//}
				
					if ($rows["delivery"] == 6) {
					
						if ($bascetpostweight < 1000) 
							$sumEMC = 650;
						else {
						
							$sumEMC = 650;
							$sumEMC += ceil(($bascetpostweight - 1000)/500)*50;
						}
						
						$resultsum = ($bascetsum+$PriceLatter+10+$sumEMC);
					} elseif($rows['delivery']==10 || $rows['delivery']==2)
						$resultsum = $bascetsum;
					else	
						$resultsum = $PostAllPrice;
				}

				
				$tpl['orders'][$i]['crcode']  = md5("numizmatikru:".sprintf ("%01.2f",round($resultsum*$krobokassa-$dissert,2)).":".$rows['order'].":$robokassapasword1:Shp_idu=".$tpl['user']['user_id']);				
				
			//	var_dump($resultsum);
				//echo "<br>";
				$tpl['orders'][$i]['OutSum'] = sprintf ("%01.2f",round($resultsum*$krobokassa-$dissert,2));	
				if($tpl['user']['user_id']==811){
					
					//var_dump($tpl['orders'][$i]["FinalSum"],$tpl['orders'][$i]['dissert'],$tpl['orders'][$i]['OutSum'],$resultsum);
					//echo "<br><br>";
				}			
			}		
			$i++;			
		}

	} elseif ($action=="showorderhtml" and $order) {
		//формируем отчет в html
		//монеты
			
		$tpl['order'] = $order_class->getAdminOrderDetails($order);
		if($tpl['order']['user']!=$tpl['user']['user_id']){
			$tpl['error'] = "Информация о заказе недоступна";
		} else {
			//if (preg_match ('/^[a-zA-Z0-9 \\._\\-]/',$names))
			if (!preg_match("/^\-{0,1}[0-9]{1,}$/", $tpl['order']["city"])) {
			    $city = $tpl['order']["city"];
			} else {
			    $city = $city_array[$tpl['order']["city"]];
			}
			
			$admincheck = $tpl['order']["admincheck"];
			$adress_recipient = $city.", ".str_replace("\n", " ", $tpl['order']["adress"]);
			$fio_recipient = $tpl['order']["fio"];
			$type = $tpl['order']["type"];
			//$postindex = 
			$checking = 1;
			//if (!$postindex) 
				$postindex = intval(substr(trim($tpl['order']['adress']),0,6));
			$shopcoinsorder = $tpl['order']['order'];
			//PostSum ($postindex, $shopcoinsorder,$clientdiscount);
			$clientdiscount = $orderdetails_class->getClientdiscount($tpl['user']['user_id']);
	        $bastet_details = $orderdetails_class->PostSum($postindex, $clientdiscount, $order);
			$bascetsum = $bastet_details['bascetsum'];
			$bascetpostweight = $bastet_details['bascetpostweight'];	
			$PostAllPrice = $bastet_details['PostAllPrice'];	
			//получаем скидку по купону
	        $discountcoupon = 0;	
			//монеты
			if ($type =="shopcoins"){	
				
				$tpl['order_results'] = $order_class->OrderShopcoinsDetails($clientdiscount,$order);
				
				$sum = 0;
				$what = "";
				$k=0;
				$oldmaterialtype = 0;		
					
				foreach ($tpl['order_results'] as $rows){		
				   $tpl['order_results'][$k]['title'] = ''	;
				   $tpl['order_results'][$k]['condition'] = $tpl['conditions'][$rows['condition_id']];
		           $tpl['order_results'][$k]['metal'] = $tpl['metalls'][$rows['metal_id']];
		    
					if ($oldmaterialtype != $rows["materialtype"]) {				
						$tpl['order_results'][$k]['title'] = $MaterialTypeArray[$rows["materialtype"]];					
					}
					 $oldmaterialtype = 	$rows["materialtype"];
					
					$what .= $rows["name"].", ";
					//$temp = GetImageSize($DOCUMENT_ROOT."/shopcoins/images/".$rows["image"]);
					//$imagewidth = $temp[0];				
					$sum += $rows["amount"]*$rows["price"];
					$k++;
				}
				$tpl['order']['sum'] = $sum;	 
				$what = substr($what, 0, -2);			
			} elseif ($type=="Book") {
				$BookImagesFolder = $in."book/images";
				//сначала о пользователе			
				//содержимое заказа
				echo "<br><b class=txt>Содержимое заказа:</b>";
				echo "<table border=0 cellpadding=3 cellspacing=1>
				<tr bgcolor=#ffcc66>
				<td class=tboard><b>Изображение</b></td>
				<td class=tboard><b>Описание</b></td>
				<td class=tboard><b>Цена</b></td>
				<td class=tboard><b>Количество</b></td>
				</tr>";
				$sql = "select o.*, c.*
				 from `orderdetails` as o left join Book as c 
				on o.catalog = c.BookID where o.order='".$order."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
				$result = mysql_query($sql);
				$sum = 0;
				$what = "";
				while (	$rows = mysql_fetch_array($result))
				{
					echo "<tr bgcolor=#EBE4D4 valign=top>
					<td class=tboard width=300><a href=".$server_name."/book/index.php?catalog=".$rows["Book"]."&PaymentOrder=$PaymentOrder&type=$type target=_blank>".$rows["BookName"]."</a>
					<br><img src=$BookImagesFolder/".$rows["BookImage"].">
					</td><td class=tboard valign=top>";
					if ($rows["BookDetails"]) echo "<br><b>Подробнее: </b>".str_replace("\n", "<br>", $rows["BookDetails"]);
					echo "</td><td class=tboard>".round($rows["BookPrice"], 2)." руб.</td>
					<td class=tboard align=center>".$rows["amount"]."</td>";
					$sum += $rows["amount"]*$rows["BookPrice"];
					echo "</tr>";
					$what .= $rows["BookName"];
					if ($rows["amount"]>1)
						$what .= "(".$rows["amount"]." шт.)";
					$what .= ", ";
				}
				echo "<tr><td>&nbsp;</td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".round((1-$discount)*$sum, 2)." руб.</td><td class=tboard>&nbsp;</td></tr>";
				echo "</table>";
			} elseif ($type=="Album") {
				$AlbumImagesFolder = $in."album/images";
				
				//содержимое заказа
				echo "<br><b class=txt>Содержимое заказа:</b>";
				echo "<table border=0 cellpadding=3 cellspacing=1>
				<tr bgcolor=#ffcc66>
				<td class=tboard><b>Изображение</b></td>
				<td class=tboard><b>Описание</b></td>
				<td class=tboard><b>Цена</b></td>
				<td class=tboard><b>Количество</b></td>
				</tr>";
				$sql = "select o.*, 
				c.*
				 from `orderdetails` as o left join Album as c 
				on o.catalog = c.AlbumID where o.order='".$order."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
				$result = mysql_query($sql);
				$sum = 0;
				$what = "";
				while (	$rows = mysql_fetch_array($result))
				{
					echo "<tr bgcolor=#EBE4D4 valign=top>
					<td class=tboard width=300><a href=".$server_name."/album/index.php?catalog=".$rows["Album"]."&PaymentOrder=$PaymentOrder&type=$type target=_blank>".$rows["AlbumName"]."</a>
					<br><img src=$AlbumImagesFolder/".$rows["AlbumImage"].">
					</td><td class=tboard valign=top>";
					if ($rows["AlbumDetails"]) echo "<br><b>Подробнее: </b>".str_replace("\n", "<br>", $rows["AlbumDetails"]);
					echo "</td><td class=tboard>".round($rows["AlbumPrice"], 2)." руб.</td>
					<td class=tboard align=center>".$rows["amount"]."</td>";
					$sum += $rows["amount"]*$rows["AlbumPrice"];
					echo "</tr>";
					$what .= $rows["AlbumName"];
					if ($rows["amount"]>1)
						$what .= "(".$rows["amount"]." шт.)";
					$what .= ", ";
				}
				echo "<tr><td>&nbsp;</td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".round((1-$discount)*$sum, 2)." руб.</td><td class=tboard>&nbsp;</td></tr>";
				echo "</table>";
			}
		}
		$tpl["datatype"]='text_html';
		//$tpl['module'] = $tpl['module'].'/'.$tpl['task'];

	} 
}


?>
