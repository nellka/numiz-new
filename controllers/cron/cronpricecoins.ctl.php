<?php
set_time_limit(0);
ini_set('memory_limit', '512M');    
require_once 'Zend/Http/Client.php';

set_time_limit(5*3600);
$starttime = time();
$deltatime = 4*3600;

$recipient = "molotok@numizmatik.ru";
$subject = "wolmar start";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Start parcing from Wolmar at ".date('Y-m-d H:i',time());
//mail($recipient, $subject, $message, $headers);

$sql = "select number from auctionurl where `check`=1 and site='wolmar' group by number;";

$result = $shopcoins_class->getDataSql($sql);
foreach ($result as $rows){
	$arraynumberis[] = $rows['number'];
}

$NumberAuction = array();     

$thisxml = getContent("http://www.wolmar.ru");                                    

$rcolumn = $thisxml->body->table->tr[1]->td[1]->table->tr->td[1]->div[0];
    
$vips =  $rcolumn->div[0]->div->a;
foreach ($vips as $a){
	$n = str_replace("/auction/","",$a->attributes()->href[0]);
	if(!in_array($n,$arraynumberis)&&!in_array($n,$NumberAuction)){
		$NumberAuction[] = $n;
		$TypeAuction[] = 1;
	}    	
}

$standarts =  $rcolumn->div[1]->div->a;
foreach ($standarts as $a){
	$n = str_replace("/auction/","",$a->attributes()->href[0]);
	if(!in_array($n,$arraynumberis)&&!in_array($n,$NumberAuction)){
		$NumberAuction[] = $n;
		$TypeAuction[] = 1;
	}    	
}

$mounths =  $rcolumn->div[2]->div->a;
foreach ($mounths as $a){
	$n = str_replace("/auction/","",$a->attributes()->href[0]);
	if(!in_array($n,$arraynumberis)&&!in_array($n,$NumberAuction)){
		$NumberAuction[] = $n;
		$TypeAuction[] = 1;
	}    	
}

$CatTypeWolmarOld = array ( 
'monety-rossii-do-1917-med'=>7, 
'monety-rossii-do-1917-serebro'=>8,
'monety-rsfsr-sssr-rossii'=>9,
'monety-rossii-do-1917-zoloto'=>11);

$CatTypeWolmar = array ( 
'monety-rossii-do-1917-med'=>"Монеты России до 1917 года (медь)", 
'monety-rossii-do-1917-serebro'=>"Монеты России до 1917 года (серебро)",
'monety-rsfsr-sssr-rossii'=>"Монеты РСФСР, СССР, России",
'monety-rossii-do-1917-zoloto'=>"Монеты России до 1917 (золото)");

rsort($NumberAuction);


foreach ($NumberAuction as $key=>$value) {

    echo "start $value<br>\n";
	$tig = 0;
	$datestart = 0;
	$dateend = 0;

	foreach ($CatTypeWolmar as $key2=>$value2) {			
		$checkauction = 1;
		
		$url = "http://www.wolmar.ru/auction/".$value."/$key2?all=1";
				
		$thisxml = getContent($url);
		//проверяем что акуцион закрыт
		$h1 = trim($thisxml->body->table->tr[1]->td[1]->h1->span); 

		if($h1&&mb_substr($h1,0,7,"utf8")=="(Закрыт") $is_closed = 1;		
			
        if($is_closed){	
     		$sql_ins = "insert into auctionurl (`url`,`category`,`page`,`number`,`site`,`dateinsert`,`check`,`type`)
									values  ('http://www.wolmar.ru/auction/','$key2','&all=1',$value,'wolmar',".time().",'1',".$TypeAuction[$key].");";
			
     		$data = array('url'=>'http://www.wolmar.ru/auction/',
					  'category'=>$CatTypeWolmarOld[$key2],
					  'page'=>'&all=1',
					  'number'=>$value,
					  'site'=>'wolmar',
					  'dateinsert'=>time(),
					  'check'=>1,
					  'type'=>$TypeAuction[$key]);
					  
			$id_auctionurl = $shopcoins_class->addNewTableRecord('auctionurl',$data);
			$trs = $thisxml->body->table->tr[1]->td[1]->table->tr->td->form->table->tr;
			
			$i=0;
			foreach ($trs as $tr){
				$i++;	
				if($i<=3) continue;
				
				$priceexpert = 0;
				$pricestart = 1;
				$priceend = 0;
				
				$number = (int)$tr->attributes()->lot_id[0];
				
				$name = $tr->td[1]->a;
				$name = str_replace('"','',$name);
				$name = trim(str_replace("!","",$name));
                                try{            
                                    $sql_tmp = "select auctionname from auctionname where `name`='$name';";			
                                    $rowTemp = $shopcoins_class->getOneSql($sql_tmp);
                                } catch (Exception $e){
                                    continue;
                                }
				if($rowTemp){
					$name = $rowTemp;
				} else {			
					$data = array('name'=>$name);					  
					$name = $shopcoins_class->addNewTableRecord('auctionname',$data);			
				}
						
				$year = trim($tr->td[2]);
				$simbol = trim(str_replace('"','',$tr->td[3]));
				
				if($simbol){
					$sql_tmp = "select auctionsimbols from auctionsimbols where `simbols`='$simbol';";
					$rowTemp = $shopcoins_class->getOneSql($sql_tmp);
					
					if ($rowTemp) {				
						$simbol = $rowTemp;
					} else {	
						$data = array('simbols'=>$simbol);					  
						$simbol = $shopcoins_class->addNewTableRecord('auctionsimbols',$data);					
					}
				}
				
				$metal = correctmetal(trim($tr->td[4]));
				$sql_tmp = "select auctionmetal from auctionmetal where `metal`='$metal';";
				$rowTemp = $shopcoins_class->getOneSql($sql_tmp);
				if ($rowTemp) {				
					$metal = $rowTemp;
				} else {	
					$data = array('metal'=>$metal);					  
					$metal = $shopcoins_class->addNewTableRecord('auctionmetal',$data);					
				}
				
				$condition = correctcond(trim($tr->td[5]));
				//echo " condition=".$condition;
				$sql_tmp = "select auctioncondition from auctioncondition where `condition`='$condition';";
				$rowTemp = $shopcoins_class->getOneSql($sql_tmp);
				
				if ($rowTemp) {				
					$condition = $rowTemp;
				} else {			
					$data = array('condition'=>$condition);					  
					$condition = $shopcoins_class->addNewTableRecord('auctioncondition',$data);					
				}
				
				$sumstep = (int)$tr->td[7]->nobr;
				$priceend = (int)str_replace(" ","",$tr->td[8]);
				$check_value = $tr->td[9]->nobr;
				if($check_value->span) $check_value = $check_value->span;
				
				$check = trim($check_value)=="Закрыто"?($sumstep>0?1:2):0;
				//if ($check == 0) $checkauction = 0;				
					
				if ($tig == 0) {
				
					$url = "http://www.wolmar.ru/ajax/bids.php?auction_id=".$value."&lot_id=".$number;
					$thisxml = getContent($url);
					$count = count( $thisxml->body->table->tr);
					
					$str_dateend = $thisxml->body->table->tr[1]->td[3]."";			
					$str_datestart = $thisxml->body->table->tr[$count-1]->td[3]."";
					
					$tmp = explode(".",trim($str_dateend));
					$dateend = mktime(0,0,0,$tmp[1],$tmp[0],$tmp[2]);
					
					//$datestart = $dateend - 7*24*3600; - уточнить
					$tmp = explode(".",trim($str_datestart));
					$datestart = mktime(0,0,0,$tmp[1],$tmp[0],$tmp[2]);
					
					$tig = 1;		
				}			
	
				$url = "http://www.wolmar.ru/auction/".$value."/".$number;
				$thisxml = getContent($url);
				$details = trim(strip_tags($thisxml->body->table->tr[1]->td[1]->table->tr[0]->td[0]->div[1]->div[0]));

				$data = array('auctionurl'=>$id_auctionurl,
					  'number'=>$number,
					  'name'=>$name,
					  'pricestart'=>$pricestart,
					  'priceend'=>$priceend,
					  'priceexpert'=>$priceexpert,
					  'group'=>$CatTypeWolmarOld[$key2],
					  'groupname' =>$value2,
					  'year' =>$year,
					  'condition'=>$condition,
					  'metal'=>$metal,
					  'image'=>'',
					  'datestart'=>$datestart,
					  'dateend'=>$dateend,
					  'simbols'=>$simbols,
					  'details'=>$details,
					  'dateinsert'=>time(),
					  'check'=>$check,
					  );
					  
			    $id_in = $shopcoins_class->addNewTableRecord('auctionshopcoins',$data);	
			    		
				$data = array('image'=>$id_in.".jpg");
			    $shopcoins_class->updateTableRow('auctionshopcoins',$data,"auctionshopcoins='$id_in'");							
			}	

			/**	
			if ($checkauction == 0) {
		
			$sql_up = "update auctionurl set `check`='$checkauction' where auctionurl=$id_auctionurl;";
			mysql_query($sql_up);
				}
			*/
        }		
	}
	
	if (time()>$starttime+$deltatime) {
	
		$recipient = "molotok@numizmatik.ru";
		$subject = "wolmar finish";
		$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
		$message = "Finish parcing from Wolmar at ".date('Y-m-d H:i',time());
		mail($recipient, $subject, $message, $headers);
		die('wolmar finish');
	}
	echo "done $value";
}

$recipient = "molotok@numizmatik.ru";
$subject = "wolmar finish all";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Finish all parcing from Wolmar at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

$arraynumberis=array();

$recipient = "molotok@numizmatik.ru";
$subject = "conros start";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Start parcing from Conros at ".date('Y-m-d H:i',time());
//mail($recipient, $subject, $message, $headers);

$CatTypeConros = array ( 
2=>"Монеты России до 1917 года (медь)",
1=>"Монеты России до 1917 года (золото,серебро)",
6=>"Монеты РСФСР, СССР, России");
$sql = "select number from auctionurl where `check`=1 and site='conros' group by number;";

$result = $shopcoins_class->getDataSql($sql);
foreach ($result as $rows){
	$arraynumberis[] = $rows['number'];
}

$NumberAuction = array();     
  
$client = new Zend_Http_Client("http://auction.conros.ru/");
$response = $client->request("GET");
$html = $response->getBody();

        	
$html_array = explode('"clAuct/',$html);
$count = count($html_array);
unset($html_array[0]);
unset($html_array[$count-1]);

$auctionends = array();

foreach ($html_array as $url){
	$strpos = strpos($url, '/');
	$n = (int)substr($url,0, $strpos);
	if(!in_array($n,$arraynumberis)&&!in_array($n,$auctionends)){
		$auctionends[] = $n;
	}
	
	if(strlen($url)>100) break;
}

foreach ($auctionends AS $i){	
		//$n++;
	$tig = 0;
	$datestart = 0;
	$dateend = 0;		
	foreach ($CatTypeConros as $key2=>$value2) {
		$url = "http://auction.conros.ru/clAuct/".$i."/".$key2."/0/0/asc/";
		$thisxml = getContent($url,'win1251');  
		
		$a_column = $thisxml->body->div[0]->table[1]->tr[0]->td[1]->div[0]->table[0]->tr[0]->td[0]->a;
		$pages = array();
		foreach ($a_column as $a){
			if($a->span){
				$pages[] = 1;
			} else if($a=="++"){
				continue;
			} else $pages[] = $a."";			 
		}

		for ($j=0;$j<=$pages;$j++) {			
			$sql_ins = "insert into auctionurl (`auctionurl`, `url`,`category`,`page`,`number`,`site`,`dateinsert`,`check`,`type`)
										values  (NULL,'http://auction.conros.ru/clAuct/','$key2','$j',$i,'conros',".time().",'1',0);";
			$result_ins = mysql_query($sql_ins);
			$id_auctionurl = mysql_insert_id();
			
			$data = array('url'=>'http://auction.conros.ru/clAuct/',
					  'category'=>$key2,
					  'page'=>$j,
					  'number'=>$i,
					  'site'=>'conros',
					  'dateinsert'=>time(),
					  'check'=>1,
					  'type'=>0);
					  
			//$id_auctionurl = $shopcoins_class->addNewTableRecord('auctionurl',$data);
			
			$checkauction = 1;

			$url = "http://auction.conros.ru/clAuct/".$i."/".$key2."/".$j."/0/asc/";

			$thisxml = getContent($url,'win1251');  
		
			$tr_column = $thisxml->body->div[0]->table[1]->tr[0]->td[1]->div[0]->table[1]->tr;
			$ku=0;
			foreach ($tr_column as $tr){	
				IF($ku==0) {
					$ku++;
					continue;
				}
				
				$priceexpert = 0;
				$pricestart = 1;
				$priceend = 0;				
				
				$onclick = $tr->attributes()->onclick;
				
				$strpos = strpos($str, 'align="center">');
				$str = substr($str, $strpos+15);
				$strpos = strpos($str, '<');
				$numberimage = $tr->td[0]."";
				$onclick_array =  explode("/",$onclick);
				$number = $onclick_array[2];
				
				$name = str_replace('"','',$tr->td[1]->a);
				$name = trim(str_replace("!","",$name));

				$sql_tmp = "select auctionname from auctionname where `name`='$name';";			
				$rowTemp = $shopcoins_class->getOneSql($sql_tmp);
				
				if($rowTemp){
					$name = $rowTemp;
				} else {			
					$data = array('name'=>$name);					  
					$name = $shopcoins_class->addNewTableRecord('auctionname',$data);			
				}
				$year = $tr->td[2];
				
				if ((int)$year)	{
					$details = '';
					$year = (int)$year;
				}	else {				
					$details = $year."  ";
					$year = 0;
				}	
				
				$simbol = trim(str_replace('"','',$tr->td[3]));
				
				if($simbol){
					$sql_tmp = "select auctionsimbols from auctionsimbols where `simbols`='$simbol';";
					$rowTemp = $shopcoins_class->getOneSql($sql_tmp);
					
					if ($rowTemp) {				
						$simbol = $rowTemp;
					} else {	
						$data = array('simbols'=>$simbol);					  
						$simbol = $shopcoins_class->addNewTableRecord('auctionsimbols',$data);					
					}
				}
				
				$metal = correctmetal(trim($tr->td[4]));
				
				$metal = correctmetal(trim($tr->td[4]));
				$sql_tmp = "select auctionmetal from auctionmetal where `metal`='$metal';";
				$rowTemp = $shopcoins_class->getOneSql($sql_tmp);
				if ($rowTemp) {				
					$metal = $rowTemp;
				} else {	
					$data = array('metal'=>$metal);					  
					$metal = $shopcoins_class->addNewTableRecord('auctionmetal',$data);					
				}
				
				$condition = correctcond(trim($tr->td[5]));
				
				$sql_tmp = "select auctioncondition from auctioncondition where `condition`='$condition';";
				$rowTemp = $shopcoins_class->getOneSql($sql_tmp);
				
				if ($rowTemp) {				
					$condition = $rowTemp;
				} else {			
					$data = array('condition'=>$condition);					  
					$condition = $shopcoins_class->addNewTableRecord('auctioncondition',$data);					
				}
				$sumstep = (int)$tr->td[6]->span;
				$priceend = (int)$tr->td[8]->span;
				
				$url = "http://auction.conros.ru/lot/".$number."/".$i."/1/";
				$thisxml = getContent($url,'win1251');  
			
			    $osobenn = $thisxml->body->div[0]->table[1]->tr[0]->td[1]->div[0]->div[0]->div[0]->p[2];
				
													
								
				$strpos = strpos($buftmp, 'Особенности:');
				if ($osobenn) {
					$strtmp = str_replace('Особенности:',"",$osobenn);
					$details .= str_replace("&nbsp;","",$strtmp);
				}
				
				if ($tig == 0) {
					$_ar = explode('Время закрытия:',$thisxml->body->div[0]->table[1]->tr[0]->td[1]->div[0]->div[0]->div[0]->p[3]);
					
					$dateend = strtotime(trim($_ar[1]));
					$datestart = $dateend - 7*24*3600;
					$tig = 1;
				}
				var_dump($numberimage,$number,$name,$year,$details,$simbol,$metal,$condition,$sumstep,$priceend,$dateend,$datestart,$url);
				die();	
				$data = array('auctionurl'=>$id_auctionurl,
					  'number'=>$number,
					  'name'=>$name,
					  'pricestart'=>$pricestart,
					  'priceend'=>$priceend,
					  'priceexpert'=>$priceexpert,
					  'group'=>$CatTypeWolmarOld[$key2],
					  'groupname' =>$value2,
					  'year' =>$year,
					  'condition'=>$condition,
					  'metal'=>$metal,
					  'image'=>'',
					  'datestart'=>$datestart,
					  'dateend'=>$dateend,
					  'simbols'=>$simbols,
					  'details'=>$details,
					  'dateinsert'=>time(),
					  'check'=>1,
					  );
					  
			    $id_in = $shopcoins_class->addNewTableRecord('auctionshopcoins',$data);	

				$data = array('image'=>$id_in.".jpg");
			    $shopcoins_class->updateTableRow('auctionshopcoins',$data,"auctionshopcoins='$id_in'");					
			}
			
		}
		
		if (time()>$starttime+$deltatime) {

			$recipient = "molotok@numizmatik.ru";
			$subject = "conros finish";
			$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
			$message = "Finish parcing from Conros at ".date('Y-m-d H:i',time());
			mail($recipient, $subject, $message, $headers);
			die();
		}
	}
}

$recipient = "molotok@numizmatik.ru";
$subject = "conros finish all";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Finish all parcing from Conros at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

unset($ArrayYear);
for ($i=1689;$i<1726;$i++) {
	$ArrayYear[$i] = 561;
}

$ArrayYear[1726] = 562;
$ArrayYear[1727] = 562;

for ($i=1728;$i<1730;$i++) {
	$ArrayYear[$i] = 563;
}

for ($i=1730;$i<1742;$i++) {
	$ArrayYear[$i] = 564;
}

for ($i=1742;$i<1762;$i++) {

	$ArrayYear[$i] = 566;
}
$ArrayYear[1762] = 567;
for ($i=1763;$i<1797;$i++) {

	$ArrayYear[$i] = 568;
}
for ($i=1797;$i<1802;$i++) {

	$ArrayYear[$i] = 569;
}
for ($i=1802;$i<1826;$i++) {

	$ArrayYear[$i] = 570;
}
for ($i=1826;$i<1856;$i++) {

	$ArrayYear[$i] = 571;
}
for ($i=1856;$i<1882;$i++) {

	$ArrayYear[$i] = 572;
}
for ($i=1882;$i<1895;$i++) {

	$ArrayYear[$i] = 573;
}
for ($i=1895;$i<1918;$i++) {

	$ArrayYear[$i] = 574;
}
for ($i=1918;$i<1924;$i++) {

	$ArrayYear[$i] = 823;
}
for ($i=1924;$i<1961;$i++) {

	$ArrayYear[$i] = 1534;
}
for ($i=1961;$i<1992;$i++) {

	$ArrayYear[$i] = 1535;
}
for ($i=1992;$i<=date('Y');$i++) {

	$ArrayYear[$i] = 1011;
}

foreach ($ArrayYear as $key=>$value) {

	//$sql = "update auctionshopcoins set `group`='$value' where year='$key';";
	$data = array('group'=>$value);
	$shopcoins_class->updateTableRow('auctionshopcoins',$data,"year='$key'");		
}

$recipient = "molotok@numizmatik.ru";
$subject = "migration to pricecoins start";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Start migraition coins to pricecoins at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

$sql = "select `auctionshopcoins`.*, `auctionurl`.site, `auctionurl`.number as anumber, `auctionname`.name as aname, `auctionmetal`.metal as ametal, auctionsimbols.simbols as asimbols, `auctioncondition`.`condition` as acondition 
from `auctionshopcoins`,`auctionurl`,`auctionname`,auctionmetal, auctionsimbols, auctioncondition 
where `auctionurl`.`auctionurl`=`auctionshopcoins`.`auctionurl` and `auctionshopcoins`.`name`=`auctionname`.`auctionname` and `auctionurl`.`check`=1 and auctionshopcoins.metal=auctionmetal.auctionmetal and auctionshopcoins.simbols=auctionsimbols.auctionsimbols and auctionshopcoins.condition=auctioncondition.auctioncondition and auctionshopcoins.write=0;";
$result = $shopcoins_class->getDataSql($sql);
$i=0;
$n=0;
foreach ($result as $rows){
	$n++;
	$details = trim($rows['aname'])." ".trim($rows['asimbols']);
	if ($rows['site'] == 'wolmar')
		$atype = 1;
	elseif ($rows['site'] == 'conros')
		$atype = 2;
	else
		$atype = 0;
	
	if ($rows['year']<1682) {
	
		//$sql_up = "update auctionshopcoins set `write`='3' where auctionshopcoins='".$rows['auctionshopcoins']."';";
		$data = array('write'=>3);
		$shopcoins_class->updateTableRow('auctionshopcoins',$data,"auctionshopcoins='".$rows['auctionshopcoins']."'");	
		
		continue;
	}
	
	$sql_n = "select pricename.* from pricename,pricenametmp where pricenametmp.pricename=pricename.pricename and pricenametmp.nametmp='".trim($rows['aname'])."';";
	$rows_n = $shopcoins_class->getRowSql($sql_n);
	if ($rows_n) {	
		if (!trim($rows_n['name'])) {		
			//$sql_up = "update auctionshopcoins set `write`='2' where auctionshopcoins='".$rows['auctionshopcoins']."';";
			$data = array('write'=>2);
			$shopcoins_class->updateTableRow('auctionshopcoins',$data,"auctionshopcoins='".$rows['auctionshopcoins']."'");	
			continue;
		} else {		
			$name = $rows_n['pricename'];
		}
	} else {	
		//$sql_up = "update auctionshopcoins set `write`='2' where auctionshopcoins='".$rows['auctionshopcoins']."';";
		$data = array('write'=>2);
		$shopcoins_class->updateTableRow('auctionshopcoins',$data,"auctionshopcoins='".$rows['auctionshopcoins']."'");	
		continue;
	}
	
	$sql_n = "select pricemetal.* from pricemetal,pricemetaltmp where pricemetaltmp.pricemetal=pricemetal.pricemetal and pricemetaltmp.metaltmp='".trim($rows['ametal'])."';";
	$rows_n = $shopcoins_class->getRowSql($sql_n);
	if ($rows_n) {
		if (!trim($rows_n['metal'])) {		
			$metal = 0;
		} else {		
			$metal = $rows_n['pricemetal'];
		}
	}
	
	$sql_n = "select pricesimbols.* from pricesimbols,pricesimbolstmp where pricesimbolstmp.pricesimbols=pricesimbols.pricesimbols and pricesimbolstmp.simbolstmp='".trim($rows['asimbols'])."';";
	$rows_n = $shopcoins_class->getRowSql($sql_n);
	if ($rows_n) {		
		if (!trim($rows_n['simbols'])) {		
			$simbols = 0;
		} else {		
			$simbols = $rows_n['pricesimbols'];
		}
	}
	
	$sql_n = "select pricecondition.* from pricecondition,priceconditiontmp where priceconditiontmp.pricecondition=pricecondition.pricecondition and priceconditiontmp.conditiontmp='".trim($rows['acondition'])."';";
	$rows_n = $shopcoins_class->getRowSql($sql_n);
	if ($rows_n) {			
		if (!trim($rows_n['condition'])) {		
			$condition = 0;
		} else {		
			$condition = $rows_n['pricecondition'];
		}
	}
	
	/*$sql_ins = "insert into `priceshopcoins` (`priceshopcoins`,`number`,`anumber`,`name`,`pricestart`,`priceend`,`group`,`groupname`,`year`,`condition`,`metal`,
	`image`,`datestart`,`dateend`,`simbols`,`details`,`dateinsert`,`parent`,`amountparent`,`auction`,`check`,`numberimage`,`bigimage`,`auctionshopcoins`)
	values (NULL,'".$rows['number']."','".$rows['anumber']."','$name','".$rows['pricestart']."','".$rows['priceend']."','".$rows['group']."','".$rows['groupname']."','".$rows['year']."','$condition','$metal',
	'','".$rows['datestart']."','".$rows['dateend']."','$simbols','".trim(strip_tags($rows['details']." ".$details))."',".time().",0,0,'$atype',10,'".$rows['numberimage']."','','".$rows['auctionshopcoins']."');";*/
	$data = array(	'number'=>$rows['number'],
	                'anumber'=>$rows['anumber'],
					  'name'=>$name,
					  'pricestart'=>$rows['pricestart'],
					  'priceend'=>$rows['priceend'],
					  'group'=>$rows['group'],
					  'groupname' =>$rows['groupname'],
					  'year' =>$rows['year'],
					  'condition'=>$condition,
					  'metal'=>$metal,
					  'image'=>'',
					  'datestart'=>$rows['datestart'],
					  'dateend'=>$rows['dateend'],
					  'simbols'=>$simbols,
					  'details'=>trim(strip_tags($rows['details']." ".$details)),
					  'dateinsert'=>time(),
					 'parent'=>0,
					 'amountparent'=>0,
					 'auction'=>$atype,
					 'check'=>10,
					 'numberimage'=>$rows['numberimage'],
					 'bigimage'=>'',
					 'auctionshopcoins'=>$rows['auctionshopcoins']
					  );
	$idprice = $shopcoins_class->addNewTableRecord('priceshopcoins',$data);	
	
	$sql_upd = "update auctionshopcoins set `write`='1' where auctionshopcoins='".$rows['auctionshopcoins']."';";
	$data = array('write'=>1);
	$shopcoins_class->updateTableRow('auctionshopcoins',$data,"auctionshopcoins='".$rows['auctionshopcoins']."'");	
	
	$i++;
	
	if (time()>$starttime+$deltatime) {
	
		$recipient = "molotok@numizmatik.ru";
		$subject = "Finish migration coins to pricecoins";
		$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
		$message = "Finish migration coins to pricecoins at ".date('Y-m-d H:i',time());
		mail($recipient, $subject, $message, $headers);
		exit();
	}	
}

$recipient = "molotok@numizmatik.ru";
$subject = "Finish migration all coins to pricecoins";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Finish migration all coins to pricecoins at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

$recipient = "molotok@numizmatik.ru";
$subject = "Start upload images to pricecoins";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Start upload images to pricecoins at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

$sql = "select * from priceshopcoins where `check`=10;";
$result = $shopcoins_class->getDataSql($sql);

$base_image_path = "/var/www/htdocs/numizmatik.ru";

foreach ($result as $rows) {
	if ($rows['auction']==1) {	
		$tmp = CopyImage ("http://www.wolmar.ru/", "images/auctions/".$rows['anumber']."/preview_".$rows['number']."_1.jpg", "$base_image_path/price/images/".$rows['priceshopcoins'].".jpg", "images/auctions/".$rows['anumber']."/preview_".$rows['number']."_2.jpg");
		$tmp = CopyImage ("http://www.wolmar.ru/", "images/auctions/".$rows['anumber']."/".$rows['number']."_1.jpg", "$base_image_path/price/images/".$rows['priceshopcoins']."b.jpg", "images/auctions/".$rows['anumber']."/".$rows['number']."_2.jpg");
	} elseif ($rows['auction']==2) {	
		$tmp = CopyImage ("http://auction.conros.ru/", "thumb/".$rows['anumber']."/".$rows['numberimage'].".jpg", "$base_image_path/price/images/".$rows['priceshopcoins'].".jpg", "thumb/".$rows['anumber']."/".$rows['numberimage']."+.jpg");
		$tmp = CopyImage ("http://auction.conros.ru/", "img/".$rows['anumber']."/".$rows['numberimage'].".jpg", "$base_image_path/price/images/".$rows['priceshopcoins']."b.jpg", "img/".$rows['anumber']."/".$rows['numberimage']."+.jpg");
	}
	
	$data = array('check'=>1,
				  'image'=>$rows['priceshopcoins']);
	if($tmp==2) $data['bigimage']=$rows['priceshopcoins']."b.jpg";
	$shopcoins_class->updateTableRow('priceshopcoins',$data,"priceshopcoins='".$rows['priceshopcoins']."'");	
	
	//mysql_query("update priceshopcoins set `check`='1'".($tmp==2?",bigimage='".$rows['priceshopcoins']."b.jpg'":"").",image='".$rows['priceshopcoins'].".jpg'  where priceshopcoins='".$rows['priceshopcoins']."';");
	
	if (time()>$starttime+$deltatime) {
	
		$recipient = "molotok@numizmatik.ru";
		$subject = "Finish upload images to pricecoins";
		$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
		$message = "Finish upload images to pricecoins at ".date('Y-m-d H:i',time());
		mail($recipient, $subject, $message, $headers);
		exit();
	}
	
}

$recipient = "molotok@numizmatik.ru";
$subject = "Finish upload all images to pricecoins";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Finish upload all images to pricecoins at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

$recipient = "molotok@numizmatik.ru";
$subject = "Start set amountparent to pricecoins";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Start set amountparent to pricecoins at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);

$sql = "select * from priceshopcoins where `check`=1 and `parent`=0 and `checkuser`=0 order by year, metal,name,simbols,`condition`;";
$result = $shopcoins_class->getDataSql($sql);
foreach ($result as $rows) {
	$sql2 = "select * from priceshopcoins where parent>0 and year='".$rows['year']."' and metal='".$rows['metal']."' and name='".$rows['name']."' and simbols='".$rows['simbols']."' limit 1;";
	$rows2 = $shopcoins_class->getRowSql($sql2);
	if ($rows2)
		$parent = $rows2['parent'];
	else
		$parent = $rows['priceshopcoins'];
		
	//$sql_up = "update priceshopcoins set parent='$parent' where priceshopcoins=".$rows['priceshopcoins'].";";
	$data = array('parent'=>$parent);	
	$shopcoins_class->updateTableRow('priceshopcoins',$data,"priceshopcoins='".$rows['priceshopcoins']."'");	
		
}

$sql="SELECT priceshopcoins.parent, priceshopcoins FROM priceshopcoins WHERE priceshopcoins.check =1 and priceshopcoins.parent>0 and priceshopcoins.amountparent>0 ;";
//echo $sql;
$result = $shopcoins_class->getDataSql($sql);
foreach ($result as $rows) {
	$arrayparent[] = $rows['parent'];
	
	$sql_info = "select count(*) from priceshopcoins where `check`='1' and parent='".$rows["parent"]."';";
	$rows_info = $shopcoins_class->getOneSql($sql_info);
	//echo "<br> 2 = ".$sql_info;
	$idparent = $rows['priceshopcoins'];

	//$sql_update = "update priceshopcoins set amountparent='".$rows_info."' where priceshopcoins='".$idparent."';";
	$data = array('amountparent'=>$rows_info);	
	$shopcoins_class->updateTableRow('priceshopcoins',$data,"priceshopcoins='".$idparent."'");	
	if (time()>$starttime+$deltatime) {
	
		$recipient = "molotok@numizmatik.ru";
		$subject = "Finish set amountparent to pricecoins";
		$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
		$message = "Finish set amountparent to pricecoins at ".date('Y-m-d H:i',time());
		mail($recipient, $subject, $message, $headers);
		exit();
	}
	
}

$sql="SELECT priceshopcoins.* FROM priceshopcoins,pricecondition WHERE priceshopcoins.check =1 and priceshopcoins.parent>0 and priceshopcoins.condition=pricecondition.pricecondition GROUP BY priceshopcoins.parent order by pricecondition.position asc, priceshopcoins.dateend desc;";
//echo $sql;
$result = $shopcoins_class->getDataSql($sql);
foreach ($result as $rows) {
	$idparent = $rows['priceshopcoins'];	
	if (in_array($idparent,$arrayparent))	continue;
	
	$sql_info = "select count(*) from priceshopcoins where `check`='1' and parent='".$rows["parent"]."';";
	$rows_info = $shopcoins_class->getOneSql($sql_info);
	//echo "<br> 2 = ".$sql_info;
	
	if (!file_exists("$base_image_path/price/images/".$rows['image']) && $rows_info[0]>1) {
	
		$sql_tmp = "SELECT priceshopcoins.* FROM priceshopcoins,pricecondition 
		WHERE priceshopcoins.check =1 and priceshopcoins.parent='".$rows["parent"]."' and priceshopcoins.condition=pricecondition.pricecondition 
		order by pricecondition.position asc, priceshopcoins.dateend desc;";
		$result_tmp = $shopcoins_class->getDataSql($sql_tmp);
		
		foreach ($result_tmp as $rows_tmp) {		
			if (file_exists("$base_image_path/price/images/".$rows_tmp['image'])) {			
				$idparent = $rows_tmp['priceshopcoins'];
				break;
			}
		}
	}
	//сам update
	//$sql_update = "update priceshopcoins set amountparent='".$rows_info[0]."' where priceshopcoins='".$idparent."';";
	$data = array('amountparent'=>$rows_info);	
	$shopcoins_class->updateTableRow('priceshopcoins',$data,"priceshopcoins='".$idparent."'");		
	
	if (time()>$starttime+$deltatime) {
	
		$recipient = "molotok@numizmatik.ru";
		$subject = "Finish set amountparent to pricecoins";
		$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
		$message = "Finish set amountparent to pricecoins at ".date('Y-m-d H:i',time());
		mail($recipient, $subject, $message, $headers);
		exit();
	}
}

$recipient = "molotok@numizmatik.ru";
$subject = "Finish set all amountparent to pricecoins";
$headers = "From: Numizmatik.Ru<server@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
$message = "Finish set all amountparent to pricecoins at ".date('Y-m-d H:i',time());
mail($recipient, $subject, $message, $headers);
function CopyImage ($HostPicture, $PathPicture1, $SavePicture, $PathPicture2)
{
	//echo $PathPicture;
	//echo $PathPicture1."<br>";
	//echo $SavePicture."<br>";
	//echo $PathPicture2."<br>";
	$return = 2;
	
	$im1 = imagecreatefromjpeg ($HostPicture.$PathPicture1); 
	if (!$im1) 
		return 3;
	else 
	{
		$size1 = getimagesize ($HostPicture.$PathPicture1);
		$width = $size1[0];
		$height = $size1[1];
		
		if ($PathPicture2 && $width<=750 && $height<=750) {
			
			$im2 = imagecreatefromjpeg ($HostPicture.$PathPicture2); 
			
			if (!$im2) 
				$return = 4;
			else {
				
				$size2 = getimagesize ($HostPicture.$PathPicture2);
				$width = $width + $size2[0];
				if ($height < $size2[1])
					$height = $size2[1];
			}
		}
		//Header ("Content-Type: image/jpeg");
		$im = imagecreatetruecolor ($width, $height);
		imagecopy ($im, $im1, 0, 0, 0, 0, $size1[0], $size1[1]);
		if ($im2) {
			imagecopy ($im, $im2,($size1[0]+1), 0, 0, 0, $size2[0], $size2[1]);
			imagedestroy($im2);
		}
		imagejpeg ($im , $SavePicture);
		imagedestroy($im);
		imagedestroy($im1);
		return $return;
		
	}
}

function correctmetal($text) {

	$result = '';
	$tmp = array();
	$text = strip_tags($text);
	$text = trim(str_replace('"',"",$text));
	$ruskey = Array('А','а', 'В', 'С', 'с', 'Е', 'е', 'К', 'к', 'М', 'Н', 'О', 'о', 'Р', 'р', 'Т', 'Х', 'х', ',', '.', ':', '/','?','+','-');
	$engkey = Array ("A","a", "B", "C", "c", "E", "e", "K", "k", "M", "H", "O", "o", "P", "p", "T", "X", "x", " ", " ", " ", " ", " "," "," ");
	$text = str_replace($ruskey,$engkey,$text);
	$string = '';
	for ($i=0;$i<strlen($text);$i++) {
		
		if (intval($text[$i])) {
		
			break;
		}
		else 
			$string .= $text[$i];
	}
	$tmp = explode(' ',$string);
	for ($i=0;$i<sizeof($tmp);$i++) {
	
		$tig = 0;
		if ($tmp[$i] != '' && $tmp[$i] != ' ' && $tig == 0) {
			
			if (intval($tmp[$i]))
				$tig = 1;
			else 
				$result .= ' '.$tmp[$i];
		}
	}
		
	return trim($result);
}

function correctcond($text) {

	$result = '';
	$tmp = array();
	$text = strip_tags($text);
	$text = trim(str_replace('"',"",$text));
	$ruskey = Array('А','а', 'В', 'С', 'с', 'Е', 'е', 'К', 'к', 'М', 'Н', 'О', 'о', 'Р', 'р', 'Т', 'Х', 'х', ',', '.', ':', '/','?','+','-');
	$engkey = Array ("A","a", "B", "C", "c", "E", "e", "K", "k", "M", "H", "O", "o", "P", "p", "T", "X", "x", " ", " ", " ", " ", " "," "," ");
	$text = str_replace($ruskey,$engkey,$text);
	$tmp = explode(' ',$text);
	for ($i=0;$i<sizeof($tmp);$i++) {
	
		if ($tmp[$i] != '' && $tmp[$i] != ' ')
			$result .= ' '.$tmp[$i];
	}
		
	return trim($result);
}

function getContent($url,$encording='utf8'){
	
	$config = array('output-xhtml'  => true,
                                     'quote-nbsp'    => false,
                                     'indent'        => false,
                                     'wrap'          => 800,
                                     'char-encoding' =>'raw');
	$client = new Zend_Http_Client($url);
	$response = $client->request("GET");
	
	if ($response->isSuccessful()) {
	    $html = $response->getBody();
        if($encording!='utf8'){
        	$html = iconv("windows-1251", "utf-8", $html);
        }
		$tidy = new Tidy();
	    $tidy->parseString($html,$config,'utf8');
	    $tidy = tidy_get_html($tidy);
	    $thisxml = new SimpleXMLElement($tidy);
	}
	
	return $thisxml ;
}
?>

</body>
</html>
