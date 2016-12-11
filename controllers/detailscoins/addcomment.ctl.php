<?
include($cfg['path']."/controllers/detailscoins/config.php");

$ajax =1;
$tig = 0;
$next =  request('next');
$submit =  request('submit');

$group_id = (int) request('group_id');
$copycoins = (int) request('copycoins');
if(!$copycoins) $copycoins = isset($_COOKIE['copycoins'])?(int)$_COOKIE['copycoins']:0;

$nominal_id = (int) request('nominal_id');
$nominal_temp = request('nominal_id');
if($tpl['user']['user_id']==811){
	var_dump($nominal_id,$nominal_temp);
}
$condition_id = request('condition_id');
$condition = "";
$metal = "";
$year = (int) request('year');
$metal_id = request('metal_id');				
$details = request('details');

$pagenum = (int) request('pagenum')?(int) request('pagenum'):1;	
	
$error = array();

$tpl['error'] = "";
$showlinks = false;
if (!$tpl['user']['user_id']){
	$tpl['error'] = "Вы не авторизованы!";
} else {
	$coins = (int) request('coins');

	if(!in_array($tpl['user']['user_id'],$ArrayUsers))	$coins = 0;
	
	$user = $tpl['user']['user_id'];
	
	$sql_t = "select count(*) from shopcoinswriteusernum where `user`='$user' and `check`=0 and shopcoinswrite='$coins';";

	$rows_t = $shopcoins_class->getOneSql($sql_t);

	if ($rows_t) {
		$tpl['error'] = "Данную монету Вы отметили как неизвестную Вам.<br>";
		$coins = 0;
	}

	if (!$coins) {	
		$tpl['error'] =  "Ошибочный запрос";
	} elseif($next) {	
				
		$inserarray = array('user'=>$user,
							'amount'=>$tig,
							'dateinsert'=>time(),
							'check'=>0,
							'shopcoinswrite'=>$coins);
		$shopcoins_class->insertNewRecord('shopcoinswriteusernum',$inserarray);
		$data = array('user'=>0, 'reservetime'=>0);
		$shopcoins_class->updateTableRow('shopcoinswrite',$data,"shopcoinswrite='$coins'");
		$tpl['error'] = "Пропущено.<br><input type=\"button\" onclick=\"window.location.reload();\" value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Закрыть и обновить&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'>";
	} else {
		
		$sql_main = "select * from shopcoinswrite where shopcoinswrite='".$coins."' and `check`=1 and (reservetime<'".time()."' or `user`='$user');";

		$rows_main = $shopcoins_class->getRowSql($sql_main);
	
		if ($rows_main) {		
			if ($user && $user!=811) {	
				$data = array('user'=>$user, 'reservetime'=>time()+$reserve);
				$shopcoins_class->updateTableRow('shopcoinswrite',$data,"shopcoinswrite='$coins'");				
			}
			
			if ($submit){				
				$materialtype = $rows_main['materialtype'];	
				
				//проверка на заполнение поле				
				$tig = 0;
								
				if ($group_id) {
					$sql5 = "select name from `group` where group.group='".$group_id."' and `type`='shopcoins';";
					$group =$shopcoins_class->getOneSql($sql5);
					if ($materialtype>1&&!$group){
							$error[] = "Укажите страну из предложенных Вам в подсказке";
					}
				} else {				
					$error[] = "Не указана страна";
				}	
								
				if ($nominal_id) {
					$sql = "select name from shopcoins_name where id=$nominal_id";    	    
    	    		$name = $shopcoins_class->getOneSql($sql);   
				} else {				
					$error[] = "Не указан номинал";
				}
					
				
				if (!$year&&$materialtype==4){
						$error[] = "Не указан год";
				} else $tig++;
					
				if ($metal_id) {
					$tig++;
					$metal = $MetalArray[$metal_id];
				}
				
				if ($condition_id) {
					$tig++;
					$condition = $ConditionArray[$condition_id];
				} elseif($materialtype==4) $error[] = "Не указано состояние";
					
	
				$details = htmlspecialchars(trim($details), ENT_COMPAT,'ISO-8859-1', true);
				$details = str_replace("'"," ",$details);
					
				if ($details) {
					$tig++;
					if ($materialtype==4 && mb_strlen($details,'utf-8')<200)
						$error[] = "Описание короче 200 символов";
					if ($materialtype==7 && mb_strlen($details,'utf-8')<25)
						$error[] = "Описание короче 25 символов";
				} elseif ($materialtype==4 || $materialtype==7)	$error[] = "Нет описания";
				
				
				if ($tig>=1 && !sizeof($error)){
					$showlinks = true;
					
					$typework=1;
					
					if ($copycoins)	setcookie("copycoins", intval($_POST['copycoins']), time() + 2*3600, "/");
					
					if ($materialtype==4 || $materialtype==7) $typework=8;
					
					$data = array('name'=>$name,
								'group'=>$group,
								'year'=>$year,
								'metal'=>$metal,
								'details'=>$details,
								'condition'=>$condition,
								'user'=>$user,
								'datewrite'=>time(),
								'check'=>2);
					$shopcoins_class->updateTableRow('shopcoinswrite',$data,"shopcoinswrite='$coins'");
		
					$inserarray = array('user'=>$user,
										'amount' => $tig,										
										'dateinsert'=>time(),
										'check'=>1,
										'shopcoinswrite'=>$coins);
					$shopcoins_class->insertNewRecord('shopcoinswriteusernum',$inserarray);
		
					$sql = "delete from shopcoinswriteusernum where shopcoinswrite='$coins' and `check`=0;";
					$shopcoins_class->setQuery($sql);
						
					$inserarray = array('user'=>$user,
										'sum' => $ArrayPriceWork[$typework],										
										'dateinsert'=>time(),
										'check'=>1,
										'type'=>$typework);
					$shopcoins_class->insertNewRecord('distantionuser',$inserarray);
					
					$arraydetails = array();
					
					if ($group and $name) {
						  if ($details) {					  
							
							$details = str_replace(","," ",$details);
							$details = str_replace("."," ",$details);
							
							$tmpd = explode(" ",$details);							
							
							foreach ($tmpd as $key=>$value) {						
								if (strlen($value)>5)
									$arraydetails[] = substr($value,0,strlen($value)-2);
								elseif(strlen($value)>3)
									$arraydetails[] = substr($value,0,strlen($value)-1);
								else 
									$arraydetails[] = $value;
							}					
					  }
					  
					  $CounterSQL = (sizeof($arraydetails)>0?", MATCH(catalognew.`details`) AGAINST('".implode("* ",$arraydetails)."* ' IN BOOLEAN MODE) as coefficient":", 0 as coefficient");
					  //echo "detailsvalues=".$detailsvalues;
					  $k = 0;
					  $coinsexist = Array();
					  //где все совпадает + странаноминал			
					
					  if ($materialtype==8)	{
					  	 $materialtype_admin=1;
					  }	else $materialtype_admin = $materialtype;

					
					 if ($metal_id and $year){
							$sql = "select catalognew.*, metal.name as mname, catalogyear.yearstart as cyearstart, catalogyear.yearend $CounterSQL, if (catalognew.metal='$metal_id',1,0) as pmetal
							from catalogyear, catalognew, shopcoins_metal as metal
							where
							catalognew.group = '$group_id'
							and catalognew.name = '$name'
							and agreement > 0
							".($materialtype_admin?"and catalognew.materialtype='".$materialtype_admin."'":"")."
							and catalognew.metal = '$metal'
							and catalognew.metal = metal.id
							and catalognew.catalog = catalogyear.catalog
							and (catalogyear.yearstart='$year' or (catalogyear.yearstart <= '$year'
							and catalogyear.yearend >= '$year'))
							order by ".($year>0?"abs(".$year."-catalogyear.yearstart) asc, pmetal desc, coefficient desc,":" pmetal desc, coefficient desc,catalognew.yearstart desc,")." catalog desc, LENGTH(details) desc, dateinsert desc limit 100;";
							//echo "<br>".$sql;
						  	$result = $shopcoins_class->getDataSql($sql);
						  
						 	foreach ($result as $rows ) {
								if (!in_array($rows["catalog"], $coinsexist)) {
								  //echo "<br>".$k.$rows["image_small_url"];
								  $coinsexist[] = $rows["catalog"];
								  //$CoinsArray[$k] = Array();
								  $CoinsArray[$k]["image_small_url"] = $rows["image_small_url"];
								  $CoinsArray[$k]["image_big_url"] = $rows["image_big_url"];
								  $CoinsArray[$k]["name"] = $rows["name"];
								  $CoinsArray[$k]["catalog"] = $rows["catalog"];
								  $CoinsArray[$k]["mname"] = $rows["mname"];
								  $CoinsArray[$k]["yearstart"] = $rows["cyearstart"];
								  $CoinsArray[$k]["yearend"] = $rows["yearend"];
								  $CoinsArray[$k]["condition"] = $rows["condition"];
								  $CoinsArray[$k]["details"] = $rows["details"];
								  //echo $rows["coefficient"].",";
								  $k++;
								}
						 	}
						}
					
					if ($metal_id){
						  $sql = "select catalognew.*, metal.name as mname $CounterSQL, if (catalognew.metal='$metal_id',1,0) as pmetal
						  from catalognew, shopcoins_metal as metal
						  where
						  catalognew.group = '$group_id'
						  and catalognew.name = '$name'
						  and agreement > 0
						  ".($materialtype_admin?"and catalognew.materialtype='".$materialtype_admin."'":"")."
						  and catalognew.metal = '$metal_id'
						  and catalognew.metal = metal.id
						  order by  ".($year>0?"abs(".$year."-catalognew.yearstart) asc, pmetal desc, coefficient desc,":" pmetal desc, coefficient desc,catalognew.yearstart desc,")."
						  catalog desc, LENGTH(details) desc, dateinsert desc limit 100;";
					//echo "<br>".$sql;
						  $result = $shopcoins_class->getDataSql($sql);
						  foreach ($result as $rows) {
								if (!in_array($rows["catalog"], $coinsexist)){
								  $coinsexist[] = $rows["catalog"];
								  $CoinsArray[$k]["image_small_url"] = $rows["image_small_url"];
								  $CoinsArray[$k]["image_big_url"] = $rows["image_big_url"];
								  $CoinsArray[$k]["name"] = $rows["name"];
								  $CoinsArray[$k]["catalog"] = $rows["catalog"];
								  $CoinsArray[$k]["mname"] = $rows["mname"];
								  $CoinsArray[$k]["yearstart"] = $rows["yearstart"];
								  $CoinsArray[$k]["yearend"] = 0;
								  $CoinsArray[$k]["condition"] = $rows["condition"];
								  $CoinsArray[$k]["details"] = $rows["details"];
								  //echo $rows["coefficient"].",";
								  $k++;
								}
						  }
					  }
					
					  if ($year) {
						$sql = "select catalognew.*, metal.name as mname, catalogyear.yearstart as cyearstart, catalogyear.yearend $CounterSQL
						from catalogyear, catalognew, shopcoins_metal as metal
						where
						catalognew.group = '$group_id'
						and catalognew.name = '$name'
						and agreement > 0
						".($materialtype_admin?"and catalognew.materialtype='".$materialtype_admin."'":"")."
						and catalognew.metal = metal.id
						and catalognew.catalog = catalogyear.catalog
						and (catalogyear.yearstart='$year' or (catalogyear.yearstart <= '$year'
						and catalogyear.yearend >= '$year'))
						order by  ".($year>0?"abs(".$year."-catalognew.yearstart) asc,":"catalognew.yearstart desc,")." coefficient desc,
						catalog desc, LENGTH(details) desc, dateinsert desc limit 100;";
						//echo "<br>-".$sql;
						$result = $shopcoins_class->getDataSql($sql);
						foreach ($result as $rows) {
						  if (!in_array($rows["catalog"], $coinsexist)){
							$coinsexist[] = $rows["catalog"];
							$CoinsArray[$k]["image_small_url"] = $rows["image_small_url"];
							$CoinsArray[$k]["image_big_url"] = $rows["image_big_url"];
							$CoinsArray[$k]["name"] = $rows["name"];
							$CoinsArray[$k]["catalog"] = $rows["catalog"];
							$CoinsArray[$k]["mname"] = $rows["mname"];
							$CoinsArray[$k]["yearstart"] = $rows["cyearstart"];
							$CoinsArray[$k]["yearend"] = $rows["yearend"];
							$CoinsArray[$k]["condition"] = $rows["condition"];
							$CoinsArray[$k]["details"] = $rows["details"];
							//echo $rows["coefficient"].",";
							$k++;
						  }
						}
					  }
					
					  //где только странаноминал
					
					  $sql = "select catalognew.*, m.name as mname $CounterSQL
					  from catalognew, shopcoins_metal as m
					  where catalognew.metal = m.id
					  and catalognew.group = '$group_id'
					  and catalognew.name = '$name' and agreement > 0
					  ".($materialtype_admin?"and catalognew.materialtype='".$materialtype_admin."'":"")."
					  order by  ".($year>0?"abs(".$year."-catalognew.yearstart) asc,":"catalognew.yearstart desc,")."coefficient desc,
					  catalog desc, LENGTH(details) desc, dateinsert desc limit 100;";
					  //echo $sql;
					  $result = $shopcoins_class->getDataSql($sql);
					  foreach ($result as $rows) {
						if (!in_array($rows["catalog"], $coinsexist)){
						  $coinsexist[] = $rows["catalog"];
						  $CoinsArray[$k]["image_small_url"] = $rows["image_small_url"];
						  $CoinsArray[$k]["image_big_url"] = $rows["image_big_url"];
						  $CoinsArray[$k]["name"] = $rows["name"];
						  $CoinsArray[$k]["catalog"] = $rows["catalog"];
						  $CoinsArray[$k]["mname"] = $rows["mname"];
						  $CoinsArray[$k]["yearstart"] = $rows["yearstart"];
						  $CoinsArray[$k]["yearend"] = 0;
						  $CoinsArray[$k]["condition"] = $rows["condition"];
						  $CoinsArray[$k]["details"] = $rows["details"];
						  //echo $rows["coefficient"].",";
						  $k++;
						}
					  }
					}					
				}	elseif($tig<2) $error[] = "Вы указали мало параметров.";
			}
						
			if (!$rows_main['group']) {
			
				$sql = "select * from `group` where type='shopcoins' and `group` not in (667,937,983,997,1014,1015,1062,1063,1097,1106) group by name;";				
				$Countries = $shopcoins_class->getDataSql($sql);	
			} 			
		}	else {
			$tpl['error'] = "Ошибочный запроc! Возможно данной монете уже сделал(делает) описание другой пользователь.";
		}
	}
}