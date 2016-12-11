<?php
require_once($cfg['path'].'/helpers/urlBuilder.php');
require $cfg['path'] . '/configs/config_shopcoins.php';



$starttime = time();
$tpl['onpage'] = 18;

$for_shopcoins = true;

if($for_shopcoins){
	$sql = "delete from `sitemaps` where type='shopcoins';";
	$shopcoins_class->setQuery($sql);

	$urls = array();
	
	$materials = array(1=>"");
	foreach (urlBuild::$shopcoins_materialIDsRule as $k=>$v){
		$materials[$k] = $v;
	}
	
	$pos=0;
	
	$sql = "SELECT count(shopcoins) FROM `shopcoins_search` AS `s` WHERE (s.check=1 or s.check>=4) ";
	$count  = $shopcoins_class->getOneSql($sql);
	echo $count;
	
	while ($pos<$count){
		echo $pos."\n";
		$sql = "SELECT `s`.*, `group`.`name` AS `gname`, `sn`.`name` FROM `shopcoins_search` AS `s` 
		INNER JOIN `group` ON s.group=group.group
		INNER JOIN `shopcoins_name` AS `sn` ON s.nominal_id=sn.id 
		WHERE (s.check=1 or s.check>=4) order by novelty desc, dateinsert desc limit $pos,2000";
		$data = $shopcoins_class->getDataSql($sql);
		
		
		foreach ($data as $i=>$row){
			    $data[$i]['condition'] = isset($tpl['conditions'][$rows['condition_id']])?$tpl['conditions'][$rows['condition_id']]:'';		    
			    $data[$i]['metal'] = isset($tpl['metalls'][$rows['metal_id']])?$tpl['metalls'][$rows['metal_id']]:'';
			    $ro = contentHelper::getRegHref($data[$i]);
			    $urls[1][] = "http://www.numizmatik.ru/shopcoins/".$ro["rehref"];
			    
		}
		$pos+=5000;
	}
	
	$urls[35][] = "http://www.numizmatik.ru/shopcoins/series";
	
	require_once $cfg['path'] . '/models/shopcoinsbyseries.php';
	$shopcoinsbyseries_class = new model_shopcoinsbyseries($db_class);
	
	$tpl['series']['data'] = $shopcoinsbyseries_class->getAllSeries();
	    
	foreach ($tpl['series']['data'] as $key=>$s){
	    $urls[35][] = "http://www.numizmatik.ru/shopcoins/".($s["alias"]?($s["alias"].'-s'.$s["id"].'.html'):('series/'.$s["id"]));
	}    
	
	//$sql = "truncate table `sitemaps`;";
	//$result = mysql_query($sql);
	
	/*1 - товары
	20 - разделы
	30 - группы
	40 - года-периоды
	50 - металы без групп
	60 - состояния без групп
	70 -темы без групп
	75 - номинал+металл без групп (новинки)
	80 - цкны без групп
	85 - номинал+года без групп (новинки)
	90 - номиналы без групп
	95 - года бех всего (новинки)
	
	100 - металы c группами
	110 - состояния с группами
	120  - темы с группами
	130 - цены с группами
	140 - номиалы с группами
	150 - периоды годов с группами
	160 - группа + года отдельно
	200 - пажинатор разделы 
	*/
	
	$urlParams = array();
	$WhereParams = array();

	
	foreach ($materials as $key=>$value){
		$urlParams = array();
		$WhereParams = array();
	
		$urlParams["materialtype"] = $key;
		$urls[20][] = urlBuild::makePrettyUrl($urlParams);
	
		if ($key=='newcoins') {
	    	$shopcoins_class->setCategoryType(model_shopcoins::NEWCOINS);
	    
		} elseif ($key == 'revaluation') {
			$shopcoins_class->setCategoryType(model_shopcoins::REVALUATION);
		} else {
	    	$shopcoins_class->setMaterialtype($key);
	    	$shopcoins_class->setCategoryType(0);
		}
		//пажинаторы
		$count = $shopcoins_class->countallByParams($WhereParams);
		$pages = ceil($count/$tpl['onpage']);
		for($i=2;$i<=$pages;$i++){
			$urlParams['pagenum']=$i;
			$urls[2000][] = urlBuild::makePrettyUrl($urlParams);		
		}
		
			
		unset($urlParams['pagenum']);
		
		if(in_array($key,array(1,7,8,6,2,4,9,10,12,'revaluation'))){		
			//интервалы годов	
	     
	    	foreach ($yearsArray  as $years_p=>$value){
	    		unset($urlParams['pagenum']);
	    		if($key==2){        
			        if($years_p ==4) continue;
			        if($years_p ==5) continue;
			        if($years_p ==6) continue;            
			    }      
	    		$urlParams['years_p'] = array($years_p);	
	    		$urls[240][] = urlBuild::makePrettyUrl($urlParams);
	
	    		$WhereParams['year_p'] = array($yearsArray[$years_p]['data']);
	    		$count = $shopcoins_class->countallByParams($WhereParams);
				$pages = ceil($count/$tpl['onpage']);
				
				for($i=2;$i<=$pages;$i++){
					$urlParams['pagenum']=$i;
					$urls[24000][]  = urlBuild::makePrettyUrl($urlParams);
				}	
	    	}
		}
		unset($urlParams['years_p']);	
		unset($WhereParams['year_p']);		
		unset($urlParams['pagenum']);
		
		if(in_array($key,array(1,7,8,6,4,'newcoins'))){
	
			$tpl['filters']['metalls'] = $shopcoins_class->getMetalls(false);	 	
				 
			foreach ($tpl['filters']['metalls'] as $value){
			    unset($urlParams['pagenum']);    	
			    
	    		$urlParams['metal'] = array($value["metal_id"]=>$value["name"]);	
	    		$urls[230][] = urlBuild::makePrettyUrl($urlParams);
	
	    		$WhereParams['metal'] = array($value["metal_id"]);
	    		$count = $shopcoins_class->countallByParams($WhereParams);
				$pages = ceil($count/$tpl['onpage']);
				
				for($i=2;$i<=$pages;$i++){
					$urlParams['pagenum']=$i;
					$urls[23000][]  = urlBuild::makePrettyUrl($urlParams);
				}	
			}
		}
		
		unset($urlParams['metal']);	
		unset($WhereParams['metal']);		
		
		if(in_array($key,array(1,7,8,6,4,2))){
			$tpl['filters']['conditions'] = $shopcoins_class->getConditions(false);	
	
			foreach ($tpl['filters']['conditions'] as $value){					
				unset($urlParams['pagenum']);    	
			    
	    		$urlParams['condition'] = array($value["condition_id"]=>$value["name"]);	
	    		$urls[250][] = urlBuild::makePrettyUrl($urlParams);
	
	    		$WhereParams['condition'] = array($value["condition_id"]);
	    		$count = $shopcoins_class->countallByParams($WhereParams);
				$pages = ceil($count/$tpl['onpage']);
				
				for($i=2;$i<=$pages;$i++){
					$urlParams['pagenum']=$i;
					$urls[25000][]  = urlBuild::makePrettyUrl($urlParams);
				}	  
			}
		}
		
		unset($WhereParams['condition']);
		unset($urlParams['condition']);
		
		if(in_array($key,array(1,8,6))){		
			foreach ($ThemeArray as $theme=>$value){
				unset($urlParams['pagenum']);  		    
	    		$urlParams['theme'] = array($theme=>$value);	
	    		$urls[260][] = urlBuild::makePrettyUrl($urlParams);
	
	    		$WhereParams['theme'] = array($theme);
	    		$count = $shopcoins_class->countallByParams($WhereParams);
				$pages = ceil($count/$tpl['onpage']);
				
				for($i=2;$i<=$pages;$i++){
					$urlParams['pagenum']=$i;
					$urls[26000][]  = urlBuild::makePrettyUrl($urlParams);
				}	  
			}
		}
		
		unset($WhereParams['theme']);
		unset($urlParams['theme']);
		
		if($key==11){
			$tpl['filters']['prices'] = $shopcoins_class->getPrices();
			
			foreach ($tpl['filters']['prices'] as $value){		
				unset($urlParams['pagenum']);  		    
	    		$urlParams['price'] = array($value['price']);	
	    		$urls[270][] = urlBuild::makePrettyUrl($urlParams);
	
	    		$WhereParams['price'] = array($value['price']);
	    		$count = $shopcoins_class->countallByParams($WhereParams);
				$pages = ceil($count/$tpl['onpage']);
				
				for($i=2;$i<=$pages;$i++){
					$urlParams['pagenum']=$i;
					$urls[27000][]  = urlBuild::makePrettyUrl($urlParams);
				}	  
			}
		}
		unset($WhereParams['price']);
		unset($urlParams['price']);
		
		if(in_array($key,array("newcoins"))){
			$tpl['filters']['nominals'] = $shopcoins_class->getNominals();
		
			foreach ($tpl['filters']['nominals'] as $value){	
				unset($urlParams['pagenum']);  		    
	    		$urlParams['nominal'] = array($value['nominal_id']=>$value['name']);
	    		$urls[200][] = urlBuild::makePrettyUrl($urlParams);
	
	    		$WhereParams['nominals'] = array($value['nominal_id']);
	    		$count = $shopcoins_class->countallByParams($WhereParams);
				$pages = ceil($count/$tpl['onpage']);
				
				for($i=2;$i<=$pages;$i++){
					$urlParams['pagenum']=$i;
					$urls[20000][]  = urlBuild::makePrettyUrl($urlParams);
				}	  
				
				$tpl['filters']['metalls'] = $shopcoins_class->getMetalls(false,array(),array($value['nominal_id']));	 	
				 
				foreach ($tpl['filters']['metalls'] as $value_m){
				    unset($urlParams['pagenum']);    	
				    
		    		$urlParams['metal'] = array($value_m["metal_id"]=>$value_m["name"]);	
		    		$urls[210][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['metal'] = array($value_m["metal_id"]);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[21000][]  = urlBuild::makePrettyUrl($urlParams);
					}	
				}
				unset($urlParams['metal']);	
				unset($WhereParams['metal']);
				
				$tpl['filters']['years'] = array(2016,2015,2014);
		
				foreach ($tpl['filters']['years'] as $value_y){
				    unset($urlParams['pagenum']);  		    
		    		$urlParams['years'] = array($value_y);
		    		$urls[220][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['year'] = array($value_y);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[22000][]  = urlBuild::makePrettyUrl($urlParams);
					}	  
				}
				
				unset($WhereParams['year']);
				unset($urlParams['years']); 
			}
			
			unset($WhereParams['nominals']);
			unset($urlParams['nominal']);
		
			$tpl['filters']['years'] = array(2016,2015,2014);
		
			foreach ($tpl['filters']['years'] as $value){
			    unset($urlParams['pagenum']);  		    
	    		$urlParams['years'] = array($value);
	    		$urls[280][] = urlBuild::makePrettyUrl($urlParams);
	
	    		$WhereParams['year'] = array($value);
	    		$count = $shopcoins_class->countallByParams($WhereParams);
				$pages = ceil($count/$tpl['onpage']);
				
				for($i=2;$i<=$pages;$i++){
					$urlParams['pagenum']=$i;
					$urls[28000][]  = urlBuild::makePrettyUrl($urlParams);
				}	  
			}
			
			unset($WhereParams['year']);
			unset($urlParams['years']); 
		}
			
		//получаем страны
		$tpl['filters']['All_groups'] = $shopcoins_class->getGroups();
		
		foreach ($tpl['filters']['All_groups'] as $group){
			unset($urlParams['pagenum']);
			unset($urlParams['metal']);	
			unset($WhereParams['metal']);	
		
			$groupsData = $shopcoins_class->getGroupItem($group["group"]);				
			$urlParams['group'] = array($group["group"]=>$groupsData['name']);		
			$urls[30][] = urlBuild::makePrettyUrl($urlParams);
			$WhereParams['group'] = array($group["group"]);
			$count = $shopcoins_class->countallByParams($WhereParams);
			$pages = ceil($count/$tpl['onpage']);
			for($i=2;$i<=$pages;$i++){
				$urlParams['pagenum']=$i;
				$urls[300][]  = urlBuild::makePrettyUrl($urlParams);
			}
	
			if(in_array($key,array(1,7,8,6,2,9,10,12,'revaluation'))){		
				//интервалы годов	
		     
		    	foreach ($yearsArray  as $years_p=>$value){
		    		unset($urlParams['pagenum']);
		    		if($key==2){        
				        if($years_p ==4) continue;
				        if($years_p ==5) continue;
				        if($years_p ==6) continue;            
				    }      
		    		$urlParams['years_p'] = array($years_p);	
		    		$urls[150][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['year_p'] = array($yearsArray[$years_p]['data']);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[15000][]  = urlBuild::makePrettyUrl($urlParams);
					}	
		    	}
		    	unset($urlParams['years_p']);	
				unset($WhereParams['year_p']);	
			} elseif($key==4){
				//фильтр по годам отдельно
				 $tpl['filters']['years'] = $shopcoins_class->getYears(array(),array($group["group"]));	  
				
				foreach ($tpl['filters']['years'] as $value){
				    unset($urlParams['pagenum']);  		    
		    		$urlParams['years'] = array($value["year"]);
		    		$urls[160][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['year'] = array($value["year"]);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[16000][]  = urlBuild::makePrettyUrl($urlParams);
					}	  
				}
				
				unset($WhereParams['year']);
				unset($urlParams['years']); 
			} elseif ($key=='newcoins'){
				$tpl['filters']['years'] = array(2016,2015,2014);
		
				foreach ($tpl['filters']['years'] as $value){
				    unset($urlParams['pagenum']);  		    
		    		$urlParams['years'] = array($value);
		    		$urls[160][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['year'] = array($value);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[1600][]  = urlBuild::makePrettyUrl($urlParams);
					}	  
				}
				
				unset($WhereParams['year']);
				unset($urlParams['years']); 
			}
			
		
			if(in_array($key,array(1,7,8,6,4,'newcoins'))){
				$tpl['filters']['metalls'] = $shopcoins_class->getMetalls(false,array($group["group"]));	 	
					 
				foreach ($tpl['filters']['metalls'] as $value){
				    unset($urlParams['pagenum']);    	
				    
		    		$urlParams['metal'] = array($value["metal_id"]=>$value["name"]);	
		    		$urls[100][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['metal'] = array($value["metal_id"]);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[10000][]  = urlBuild::makePrettyUrl($urlParams);
					}	
				}
			}
			unset($urlParams['metal']);	
			unset($WhereParams['metal']);		
			
			if(in_array($key,array(1,7,8,6,4,2))){
				$tpl['filters']['conditions'] = $shopcoins_class->getConditions(false,array($group["group"]));	
					
				foreach ($tpl['filters']['conditions'] as $value){			   
					unset($urlParams['pagenum']);    	
				    
		    		$urlParams['condition'] = array($value["condition_id"]=>$value["name"]);	
		    		$urls[110][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['condition'] = array($value["condition_id"]);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[11000][]  = urlBuild::makePrettyUrl($urlParams);
					}	  
				}
			}
			unset($WhereParams['condition']);
			unset($urlParams['condition']);
			
			if(in_array($key,array(1,8,6))){		
				foreach ($ThemeArray as $theme=>$value){
					unset($urlParams['pagenum']);  		    
		    		$urlParams['theme'] = array($theme=>$value);	
		    		$urls[120][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['theme'] = array($theme);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[12000][]  = urlBuild::makePrettyUrl($urlParams);
					}	  
				}
			}
			unset($WhereParams['theme']);
			unset($urlParams['theme']);
			
			if($key==11){
				$tpl['filters']['prices'] = $shopcoins_class->getPrices(array(),array($group["group"]));
				
				foreach ($tpl['filters']['prices'] as $value){		
					unset($urlParams['pagenum']);  		    
		    		$urlParams['price'] = array($value['price']);	
		    		$urls[130][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['price'] = array($value['price']);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[13000][]  = urlBuild::makePrettyUrl($urlParams);
					}	  
				}
			}
			unset($WhereParams['price']);
			unset($urlParams['price']);
			
			if(!in_array($key,array(11))){
				$tpl['filters']['nominals'] = $shopcoins_class->getNominals(array($group["group"]));
			
				foreach ($tpl['filters']['nominals'] as $value){					
					unset($urlParams['pagenum']);  		    
		    		$urlParams['nominal'] = array($value['nominal_id']=>$value['name']);
		    		$urls[45][] = urlBuild::makePrettyUrl($urlParams);
		
		    		$WhereParams['nominals'] = array($value['nominal_id']);
		    		$count = $shopcoins_class->countallByParams($WhereParams);
					$pages = ceil($count/$tpl['onpage']);
					
					for($i=2;$i<=$pages;$i++){
						$urlParams['pagenum']=$i;
						$urls[4500][]  = urlBuild::makePrettyUrl($urlParams);
					}
					
					if(in_array($key,array(1,7,8,6,4,'newcoins'))){
						$tpl['filters']['metalls'] = $shopcoins_class->getMetalls(false,array($group["group"]),array($value['nominal_id']));	 	
							 
						foreach ($tpl['filters']['metalls'] as $value_m){
						    unset($urlParams['pagenum']);    	
						    
				    		$urlParams['metal'] = array($value_m["metal_id"]=>$value_m["name"]);	
				    		$urls[170][] = urlBuild::makePrettyUrl($urlParams);
				
				    		$WhereParams['metal'] = array($value_m["metal_id"]);
				    		$count = $shopcoins_class->countallByParams($WhereParams);
							$pages = ceil($count/$tpl['onpage']);
							
							for($i=2;$i<=$pages;$i++){
								$urlParams['pagenum']=$i;
								$urls[17000][]  = urlBuild::makePrettyUrl($urlParams);
							}	
						}
						unset($WhereParams['metal']);
						unset($urlParams['metal']);
						//фильтр по годам отдельно
						 $tpl['filters']['years'] = $shopcoins_class->getYears(array($value['nominal_id']),array($group["group"]));	  
						
						foreach ($tpl['filters']['years'] as $value_y){
						    unset($urlParams['pagenum']);  		    
				    		$urlParams['years'] = array($value_y["year"]);
				    		$urls[180][] = urlBuild::makePrettyUrl($urlParams);
				
				    		$WhereParams['year'] = array($value_y["year"]);
				    		$count = $shopcoins_class->countallByParams($WhereParams);
							$pages = ceil($count/$tpl['onpage']);
							
							for($i=2;$i<=$pages;$i++){
								$urlParams['pagenum']=$i;
								$urls[18000][]  = urlBuild::makePrettyUrl($urlParams);
							}	  
						}
						
						unset($WhereParams['year']);
						unset($urlParams['years']); 
				
						if(in_array($key,array(1,7,8,6,4,2))){
							$tpl['filters']['conditions'] = $shopcoins_class->getConditions(false,array($group["group"]),array($value['nominal_id']));	
								
							foreach ($tpl['filters']['conditions'] as $value_c){			   
								unset($urlParams['pagenum']);    	
							    
					    		$urlParams['condition'] = array($value_c["condition_id"]=>$value_c["name"]);	
					    		$urls[190][] = urlBuild::makePrettyUrl($urlParams);
					
					    		$WhereParams['condition'] = array($value_c["condition_id"]);
					    		$count = $shopcoins_class->countallByParams($WhereParams);
								$pages = ceil($count/$tpl['onpage']);
								
								for($i=2;$i<=$pages;$i++){
									$urlParams['pagenum']=$i;
									$urls[19000][]  = urlBuild::makePrettyUrl($urlParams);
								}	  
							}
						}
						unset($WhereParams['condition']);
						unset($urlParams['condition']);
					}	  
				}
			}
			unset($WhereParams['nominals']);
			unset($urlParams['nominal']);
				
		}	
		
		unset($urlParams['group']);	
		unset($WhereParams['group']);
		unset($urlParams['pagenum']);
		unset($WhereParams['condition']);
		unset($urlParams['condition']);
		unset($WhereParams['theme']);
		unset($urlParams['theme']);
			
	}
	
	
	ksort($urls);
	$priority = 0.95;
	foreach ($urls as $key=>$links){	
		//echo "<h1>".$key."</h1>";
		
		if($key==1){ $page='weekly';		
		} elseif($key==20){ $page='hourly';		
		} else $page='daily';	
		
		foreach ($links as $link){		
			try {
				$sql_ins = "insert into sitemaps (`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
							values ('".$link."', '$page','$priority','shopcoins',".time().",'".date('Y-m-d',time())."')";
							
				$shopcoins_class->setQuery($sql_ins);
			} catch (Exception $e){echo $e->getMessage();}		
		}
		
		$priority -=0.01; 
	}
}

$urls = array();

$sql = "delete from `sitemaps` where type='news';";
$shopcoins_class->setQuery($sql);
	
require_once $cfg['path'] . '/models/news.php';
require_once $cfg['path'] . '/configs/config_news.php';

$r_url = "http://www.numizmatik.ru/news";
$news_class = new model_news($db_class);

$sql = "select news, date, name, text, source from news where `check`=1 ;";
//echo $sql;
$result = $news_class->getDataSql($sql);
$newstime = '';

foreach ($result as $rows) {
	$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["news"].".html";
	//echo "http://numizmatik.ru/news/".$namehref."<br>";
	$sql_ins = "insert into sitemaps (`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
	values ('http://numizmatik.ru/news/".$namehref."', 'daily',0.5,'news',".time().",'".date('Y-m-d',$rows['date'])."')";
	$shopcoins_class->setQuery($sql_ins);	
    if ($newstime<$rows['date'])		$newstime = $rows['date'];			
}

$WhereParams = array();

$count = $news_class->countallByParams($WhereParams);

$page = ceil($count/$tpl['onpage']);

$sql_ins = "insert into sitemaps (`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
		values ('http://numizmatik.ru/news/', 'daily',0.5,'news',".time().",'".date('Y-m-d',$newstime)."')";
$shopcoins_class->setQuery($sql_ins);

if ($page>1) {
	for ($i=2;$i<=$page;$i++) {
		$urls[100][] ="http://numizmatik.ru/news/?pagenum=".$i;		
	}
}

$tpl['filters']['All_groups'] = $news_class->getCountries();

foreach ($tpl['filters']['All_groups'] as $value) {	
	$urlParams = array();   
    $urlParams['group'] = array($value["group"]=>$value["name"]);
    
    $urls[20][] = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/news");
    $WhereParams['group'] = array($value["group"]);
    
    $count = $shopcoins_class->countallByParams($WhereParams);    
	$pages = ceil($count/$tpl['onpage']);
	
	for($i=2;$i<=$pages;$i++){
		$urlParams['pagenum']=$i;
		$urls[200][] = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/news");		
	}
	unset($urlParams['pagenum']);		
}	
unset($urlParams['group']);	

foreach ($tpl['filters']['years'] as $value){
	$urlParams = array();   
	$urlParams['years'] = array(0=>$value);

	$urls[30][] = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/news");
	$WhereParams['years'] = array($value);
    
    $count = $shopcoins_class->countallByParams($WhereParams);    
	$pages = ceil($count/$tpl['onpage']);
	
	for($i=2;$i<=$pages;$i++){
		$urlParams['pagenum']=$i;
		$urls[300][] = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/news");		
	}
	unset($urlParams['pagenum']);		
			
}
unset($urlParams['years']);	

$news_by_theme = $news_class->getThemes(array());

foreach ($news_by_theme as $row){
    $urlParams = array();   
    $urlParams['theme'] = array($row['theme']=>$ThemeArray[$row['theme']]);
    
    $urls[40][] = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/news");
    $WhereParams['theme'] = array($value["group"]);
    
    $count = $shopcoins_class->countallByParams($WhereParams);    
	$pages = ceil($count/$tpl['onpage']);
	
	for($i=2;$i<=$pages;$i++){
		$urlParams['pagenum']=$i;
		$urls[400][] = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/news");		
	}
	unset($urlParams['pagenum']);			  
}

ksort($urls);
$priority = 0.5;
foreach ($urls as $key=>$links){	
	//echo "<h1>".$key."</h1>";
	if($key>100) $priority = 0.4;
	$page='daily';	
	
	foreach ($links as $link){		
		try {
			//echo $link."<br>";
			$sql_ins = "insert into sitemaps (`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
						values ('".$link."', '$page','$priority','news',".time().",'".date('Y-m-d',$newstime)."')";
						
			$shopcoins_class->setQuery($sql_ins);
		} catch (Exception $e){echo $e->getMessage();}		
	}

}
	
die('done');
/*
'http://www.numizmatik.ru/shopcoins/".$rehref1."', ,0.8,'shopcoins'
'http://www.numizmatik.ru/shopcoins/index.php?materialtype=".$materialtype."', ,0.9;
'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;materialtype=".$materialtype."', ''
http://www.numizmatik.ru/shopcoins/index.php?metal=".urlencode($key)."&amp;materialtype=".$materialtype."', 'daily'
'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;metal=".urlencode($key)."&amp;materialtype=".$materialtype."', 'daily',0.6
'http://www.numizmatik.ru/shopcoins/index.php?metal=".urlencode($key)."&amp;materialtype=".$materialtype."&amp;group=".$group."', 'daily'
'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5
'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;searchname=".urlencode($key2)."', 'daily'
'http://www.numizmatik.ru/shopcoins/index.php?theme=".$key."&amp;materialtype=".$materialtype."', 'daily'
'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;materialtype=".$materialtype."&amp;theme=".$key."', 'daily',0.5,
'http://www.numizmatik.ru/shopcoins/index.php?theme=".$key."&amp;materialtype=".$materialtype."&amp;group=".$group."', 'daily',0.5,
'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."', 'daily',0.5,
*/
$sql = "select shopcoins.*, `group`.`name` as gname from shopcoins, `group` where shopcoins.`group`=`group`.`group` and shopcoins.`check`=1 and ((shopcoins.materialtype=1 and shopcoins.amountparent>0) or (shopcoins.materialtype<>1 and shopcoins.amount>0)) order by shopcoins.materialtype, shopcoins.`group`, shopcoins.`name`, shopcoins.parent, shopcoins.shopcoins ;";
$result = mysql_query($sql);
//echo mysql_num_rows($result)."<br>";

$materialtype = 0;
$nnn = 0;

while ($rows = mysql_fetch_array($result)) {

	if ($materialtype != $rows['materialtype']) {
	
		
		if ($materialtype > 0) {
			
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?materialtype=".$materialtype."', 'hourly',0.9,'shopcoins',".time().",'".date('Y-m-d',time())."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
			$numpage = ceil($number/8);
			if ($numpage>1) {
				for ($i=1;$i<=$numpage;$i++) {
				
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',time())."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
				}
			}
			
			if (sizeof($MetalArrayAll)>0 && ($materialtype==1 || $materialtype==8 || $materialtype==4 || $materialtype==7 || $materialtype==9)) {
			
				foreach($MetalArrayAll as $key=>$value) {
				
					if ($value>0) {
						$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
						values (NULL, ',0.5,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayAllTime[$key])."')";
						$result_ins = mysql_query($sql_ins);
						//echo $sql_ins."<br>";
						$nnn++;
						
						$numpage = ceil($value/8);
						if ($numpage>1) {
							for ($i=1;$i<=$numpage;$i++) {
							
								$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
								values (NULL, ,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayAllTime[$key])."')";
								$result_ins = mysql_query($sql_ins);
								//echo $sql_ins."<br>";
								$nnn++;
							}
						}
					}
				}
			}
			
			if (sizeof($MetalArray)>0 && ($materialtype==1 || $materialtype==8 || $materialtype==4 || $materialtype==7 || $materialtype==9)) {
			
				foreach($MetalArray as $key=>$value) {
				
					if ($value>0) {
						$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
						values (NULL, ,0.5,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayTime[$key])."')";
						$result_ins = mysql_query($sql_ins);
						//echo $sql_ins."<br>";
						$nnn++;
						
						$numpage = ceil($value/8);
						if ($numpage>1) {
							for ($i=1;$i<=$numpage;$i++) {
							
								$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
								values (NULL, ,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayTime[$key])."')";
								$result_ins = mysql_query($sql_ins);
								//echo $sql_ins."<br>";
								$nnn++;
							}
						}
					}
					
					if (sizeof($ArrayMetalName[$key])) {
							
						foreach ($ArrayMetalName[$key] as $key2=>$value2) {
								
							if ($value2>0) {
								$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
								values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($key2)."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayMetalNameTime[$key][$key2])."')";
								$result_ins = mysql_query($sql_ins);
								//echo $sql_ins."<br>";
								$nnn++;
										
								$numpage = ceil($value2/8);
								if ($numpage>1) {
									for ($i=1;$i<=$numpage;$i++) {
											
										$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`priority`,`type`,`dateinsert`,`lastmod`) 
										values (NULL, ,0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayMetalNameTime[$key][$key2])."')";
										$result_ins = mysql_query($sql_ins);
										//echo $sql_ins."<br>";
										$nnn++;
									}
								}
							}
						}
					}
				}
			}
			
			unset($MetalArrayAll);
			unset($MetalArray);
			unset($ArrayMetalName);
			unset($MetalArrayAllTime);
			unset($MetalArrayTime);
			unset($ArrayMetalNameTime);
			
			if (sizeof($ArrayThemeAll)>0 && ($materialtype==1 || $materialtype==8)) {
			
				foreach($ArrayThemeAll as $key=>$value) {
				
					if ($value>0) {
						$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
						values (NULL, ,0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeAllTime[$key])."')";
						$result_ins = mysql_query($sql_ins);
						//echo $sql_ins."<br>";
						$nnn++;
						
						$numpage = ceil($value/8);
						if ($numpage>1) {
							for ($i=1;$i<=$numpage;$i++) {
							
								$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
								values (NULL, 'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeAllTime[$key])."')";
								$result_ins = mysql_query($sql_ins);
								//echo $sql_ins."<br>";
								$nnn++;
							}
						}
					}
				}
				unset($ArrayThemeAll);
				unset($ArrayThemeAllTime);
				
				if (sizeof($ArrayTheme)>0) {
					foreach($ArrayTheme as $key=>$value) {
				
						if ($value>0) {
							$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
							values (NULL, 'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeTime[$key])."')";
							$result_ins = mysql_query($sql_ins);
							//echo $sql_ins."<br>";
							$nnn++;
							
							$numpage = ceil($value/8);
							if ($numpage>1) {
								for ($i=1;$i<=$numpage;$i++) {
								
									$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
									values (NULL, 'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeTime[$key])."')";
									$result_ins = mysql_query($sql_ins);
									//echo $sql_ins."<br>";
									$nnn++;
								}
							}
						}
						
						if (sizeof($ArrayThemeName[$key])) {
							
							foreach ($ArrayThemeName[$key] as $key2=>$value2) {
							
								if ($value2>0) {
									$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
									values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($key2)."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeNameTime[$key][$key2])."')";
									$result_ins = mysql_query($sql_ins);
									//echo $sql_ins."<br>";
									$nnn++;
									
									$numpage = ceil($value2/8);
									if ($numpage>1) {
										for ($i=1;$i<=$numpage;$i++) {
										
											$sql_ins = "insert into sitemaps (`sitemaps`,`url`,,`priority`,`type`,`dateinsert`,`lastmod`) 
											values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."&amp;searchname=".urlencode($key2)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeNameTime[$key][$key2])."')";
											$result_ins = mysql_query($sql_ins);
											//echo $sql_ins."<br>";
											$nnn++;
										}
									}
								}
							}
						}
					}
				}
				
				unset($ArrayTheme);
				unset($ArrayThemeName);
				unset($ArrayThemeTime);
				unset($ArrayThemeNameTime);
			}
			
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
			$numpage = ceil($numbergroup/8);
			if ($numpage>1) {
				for ($i=1;$i<=$numpage;$i++) {
					
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
				}
			}
			
			if ($name && $numbername>0) {
				
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($name)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
				
				$numpage = ceil($numbername/8);
				if ($numpage>1) {
					for ($i=1;$i<=$numpage;$i++) {
				
						$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
						values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;searchname=".urlencode($name)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
						$result_ins = mysql_query($sql_ins);
						//echo $sql_ins."<br>";
						$nnn++;
					}
				}
			}
		}
		$group = $rows['group'];
		$name = $rows['name'];
		$materialtype = $rows['materialtype'];
		$number = 0;
		$numbergroup = 0;
		$numbername = 0;
		$realtimemain = 0;
		$realtimegroup = 0;
		$realtimename = 0;
	}
	
	if ($group != $rows['group']) {
	
		$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
		values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
		$result_ins = mysql_query($sql_ins);
		//echo $sql_ins."<br>";
		$nnn++;
		$numpage = ceil($numbergroup/8);
		if ($numpage>1) {
			for ($i=1;$i<=$numpage;$i++) {
				
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
			}
		}
			
		if (sizeof($MetalArray)>0 && ($materialtype==1 || $materialtype==8 || $materialtype==4 || $materialtype==7 || $materialtype==9)) {
				
			foreach($MetalArray as $key=>$value) {
					
				if ($value>0) {
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?metal=".urlencode($key)."&amp;materialtype=".$materialtype."&amp;group=".$group."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayTime[$key])."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
							
					$numpage = ceil($value/8);
					if ($numpage>1) {
						for ($i=1;$i<=$numpage;$i++) {
								
							$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
							values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayTime[$key])."')";
							$result_ins = mysql_query($sql_ins);
							//echo $sql_ins."<br>";
							$nnn++;
						}
					}
				}
					
				if (sizeof($ArrayMetalName[$key])) {
								
					foreach ($ArrayMetalName[$key] as $key2=>$value2) {
								
						if ($value2>0) {
							$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
							values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($key2)."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayMetalNameTime[$key][$key2])."')";
							$result_ins = mysql_query($sql_ins);
							//echo $sql_ins."<br>";
							$nnn++;
										
							$numpage = ceil($value2/8);
							if ($numpage>1) {
								for ($i=1;$i<=$numpage;$i++) {
											
									$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
									values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;searchname=".urlencode($key2)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayMetalNameTime[$key][$key2])."')";
									$result_ins = mysql_query($sql_ins);
									//echo $sql_ins."<br>";
									$nnn++;
								}
							}
						}
					}
				}
			}
		}
			
		unset($MetalArray);
		unset($ArrayMetalName);
		unset($MetalArrayTime);
		unset($ArrayMetalNameTime);
			
		if (sizeof($ArrayTheme)>0 && ($materialtype==1 || $materialtype==8))
			foreach($ArrayTheme as $key=>$value) {
					
				if ($value>0) {
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?theme=".$key."&amp;materialtype=".$materialtype."&amp;group=".$group."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeTime[$key])."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
								
					$numpage = ceil($value/8);
					if ($numpage>1) {
						for ($i=1;$i<=$numpage;$i++) {
									
							$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
							values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeTime[$key])."')";
							$result_ins = mysql_query($sql_ins);
							//echo $sql_ins."<br>";
							$nnn++;
						}
					}
				}
							
				if (sizeof($ArrayThemeName[$key])) {
							
					foreach ($ArrayThemeName[$key] as $key2=>$value2) {
								
						if ($value2>0) {
							$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
							values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($key2)."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeNameTime[$key][$key2])."')";
							$result_ins = mysql_query($sql_ins);
							//echo $sql_ins."<br>";
							$nnn++;
										
							$numpage = ceil($value2/8);
							if ($numpage>1) {
								for ($i=1;$i<=$numpage;$i++) {
											
									$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
									values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."&amp;searchname=".urlencode($key2)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeNameTime[$key][$key2])."')";
									$result_ins = mysql_query($sql_ins);
									//echo $sql_ins."<br>";
									$nnn++;
								}
							}
						}
					}
				}
			}
		unset($ArrayTheme);
		unset($ArrayThemeName);
		unset($ArrayThemeTime);
		unset($ArrayThemeNameTime);
				
		if ($name && $numbername>0) {
					
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($name)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
				
			$numpage = ceil($numbername/8);
			if ($numpage>1) {
				for ($i=1;$i<=$numpage;$i++) {
					
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;searchname=".urlencode($name)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
				}
			}
		}
		
		$group = $rows['group'];
		$name = $rows['name'];
		$numbergroup = 0;
		$numbername = 0;
		$realtimegroup = 0;
		$realtimename = 0;
	}
	
	if ($name != $rows['name']) {
	
	
		if ($name && $numbername>0) {
				
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($name)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
				
			$numpage = ceil($numbername/8);
			if ($numpage>1) {
				for ($i=1;$i<=$numpage;$i++) {
				
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;searchname=".urlencode($name)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
				}
			}
		}
		$name = $rows['name'];
		$numbername = 0;
		$realtimename = 0;
	}
	
	$realtime = ($rows['dateupdate']>0?$rows['dateupdate']:$rows['dateinsert']);
	if ($rows['datereprice']>$realtime)
		$realtime = $rows['datereprice'];
	
	if ($realtimename < $realtime)
		$realtimename = $realtime;
	
	if ($realtimegroup < $realtime)
		$realtimegroup = $realtime;
		
	if ($realtimemain < $realtime)
		$realtimemain = $realtime;
	
	$mtype = $rows['materialtype'];
			
	if ($mtype==1)
		$rehref = "Монета ";
	elseif ($mtype==8)
		$rehref = "Монета ";
	elseif ($mtype==7)
		$rehref = "Набор монет ";
	elseif ($mtype==2)
		$rehref = "Банкнота ";
	elseif ($mtype==4)
		$rehref = "Набор монет ";
	elseif ($mtype==5)
		$rehref = "Книга ";
	elseif ($mtype==9)
		$rehref = "Лоты монет ";
	else 
		$rehref = "";
				
			
	if ($rows['gname'])
		$rehref .= $rows['gname']." ";
	$rehref .= $rows['name'];
	if ($rows['metal'])
		$rehref .= " ".$rows['metal']; 
	if ($rows['year'])
		$rehref .= " ".$rows['year'];

	$rehref1 = strtolower_ru($rehref)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";
	//echo $rehref."==".$rehref1."<br>";
	$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`,`priority`,`type`,`dateinsert`,`lastmod`) 
	values (NULL, ,".time().",'".date('Y-m-d',$realtime)."')";
	$result_ins = mysql_query($sql_ins);
	//echo $sql_ins."<br>";
	$nnn++;
	
	if ($rows['parent'] && $rows['materialtype'] == 1) {
	
		$sql_tmp = "select count(*) from shopcoins where `check`=1 and parent=".$rows['parent'].";";
		$result_tmp = mysql_query($sql_tmp);
		$rows_tmp = mysql_fetch_array($result_tmp);
		if ($rows_tmp[0]>1) {
		
			$rehrefdubdle = strtolower_ru($rehref)."_c".$rows['parent']."_pc".$rows['parent']."_m".$rows['materialtype']."_pp1.html";
			
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/".$rehrefdubdle."', 'daily',0.7,'shopcoins',".time().",'".date('Y-m-d',$realtime)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
			$pagenum = ceil($rows_tmp[0]/8);
			if ($pagenum>1) {
			
				for ($i=2;$i<=$pagenum;$i++) {
				
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/".str_replace('_pp1','_pp'.$i,$rehrefdubdle)."', 'daily',0.6,'shopcoins',".time().",'".date('Y-m-d',$realtime)."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
				}
			}
		}
	}
	
	if ($rows['amount']>1 && ($rows['materialtype']==8 || $rows['materialtype']==7 || $rows['materialtype']==4 || $rows['materialtype']==2)) {
	
		$rehrefdubdle = strtolower_ru($rehref)."_c".$rows['shopcoins']."_pc".$rows['shopcoins']."_m".$rows['materialtype']."_pp1.html";
		
		$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
		values (NULL, 'http://www.numizmatik.ru/shopcoins/".$rehrefdubdle."', 'daily',0.7,'shopcoins',".time().",'".date('Y-m-d',$realtime)."')";
		$result_ins = mysql_query($sql_ins);
		//echo $sql_ins."<br>";
		$nnn++;
		$pagenum = ceil($rows['amount']/8);
		if ($pagenum>1) {
		
			for ($i=2;$i<=$pagenum;$i++) {
			
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/".str_replace('_pp1','_pp'.$i,$rehrefdubdle)."', 'daily',0.6,'shopcoins',".time().",'".date('Y-m-d',$realtime)."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
			}
		}
	}
	
	if ($rows['theme']>0) {
		
		foreach ( $ThemeArray as $key=>$value) {
		
			if (pow(2,$key) & $rows['theme']) {
				
				$ArrayTheme[$key]++;
				
				if ($ArrayThemeTime[$key] < $realtime)
					$ArrayThemeTime[$key] = $realtime;
				
				$ArrayThemeAll[$key]++;
				
				if ($ArrayThemeAllTime[$key] < $realtime)
					$ArrayThemeAllTime[$key] = $realtime;
				
				if ($ArrayThemeNameAll[$key][$rows['name']] > 0) {
				
					$ArrayThemeNameAll[$key][$rows['name']]++;
					
					if ($ArrayThemeNameAllTime[$key][$rows['name']] < $realtime)
						$ArrayThemeNameAllTime[$key][$rows['name']] = $realtime;
					
				}
				else {
					$ArrayThemeNameAll[$key][$rows['name']] = 1;
					$ArrayThemeNameAllTime[$key][$rows['name']] = $realtime;
				}
				
				if ($ArrayThemeName[$key][$rows['name']] > 0) {
				
					$ArrayThemeName[$key][$rows['name']]++;
					if ($ArrayThemeNameTime[$key][$rows['name']] < $realtime)
						$ArrayThemeNameTime[$key][$rows['name']] = $realtime;
				}
				else {
					$ArrayThemeName[$key][$rows['name']] = 1;
					$ArrayThemeNameTime[$key][$rows['name']] = $realtime;
				}
			}
		}
	}
	
	if (trim($rows['metal'])) {
	
		$tig = 0;
		if (sizeof($MetalArray)) {
			foreach ($MetalArray as $key=>$value) {
			
				if ($key == trim($rows['metal'])) {
				
					$MetalArray[$key]++;
					
					if ($MetalArrayTime[$key] < $realtime)
						$MetalArrayTime[$key] = $realtime; 
					
					$tig=1;
				}
			}
		}
		if ($tig == 0) {
			
			$MetalArray[trim($rows['metal'])] = 1;
			$MetalArrayTime[trim($rows['metal'])] = $realtime;
		}
			
		$tig = 0;
		if (sizeof($MetalArrayAll)) {
			foreach ($MetalArrayAll as $key=>$value) {
			
				if ($key == trim($rows['metal'])) {
				
					$MetalArrayAll[$key]++;
					
					if ($MetalArrayAllTime[$key] < $realtime)
						$MetalArrayAllTime[$key] = $realtime;
						
					if ($ArrayMetalName[$key][$rows['name']] > 0) {
				
						$ArrayMetalName[$key][$rows['name']]++;
						
						if ($ArrayMetalNameTime[$key][$rows['name']] < $realtime)
							$ArrayMetalNameTime[$key][$rows['name']] = $realtime;
					}
					else {
						
						$ArrayMetalName[$key][$rows['name']] = 1;
						$ArrayMetalNameTime[$key][$rows['name']] = $realtime;
					}
					
					$tig=1;
				}
			}
		}
		if ($tig == 0) {
			
			$MetalArrayAll[trim($rows['metal'])] = 1;
			$ArrayMetalName[trim($rows['metal'])][$rows['name']] = 1;
			$MetalArrayAllTime[trim($rows['metal'])] = $realtime;
			$ArrayMetalNameTime[trim($rows['metal'])][$rows['name']] = $realtime;
		}
	}
	
	$number++;
	$numbergroup++;
	$numbername++;
	
}

if ($materialtype > 0) {
			
	$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
	values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?materialtype=".$materialtype."', 'hourly',0.9,'shopcoins',".time().",'".date('Y-m-d',time())."')";
	$result_ins = mysql_query($sql_ins);
	//echo $sql_ins."<br>";
	$nnn++;
	$numpage = ceil($number/8);
	if ($numpage>1) {
		for ($i=1;$i<=$numpage;$i++) {
				
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;materialtype=".$materialtype."', 'daily',0.7,'shopcoins',".time().",'".date('Y-m-d',time())."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
		}
	}
			
	if (sizeof($MetalArrayAll)>0 && ($materialtype==1 || $materialtype==8 || $materialtype==4 || $materialtype==7 || $materialtype==9)) {
		
		foreach($MetalArrayAll as $key=>$value) {
				
			if ($value>0) {
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?metal=".urlencode($key)."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayAllTime[$key])."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
						
				$numpage = ceil($value/8);
				if ($numpage>1) {
					for ($i=1;$i<=$numpage;$i++) {
							
						$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
						values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;metal=".urlencode($key)."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayAllTime[$key])."')";
						$result_ins = mysql_query($sql_ins);
						//echo $sql_ins."<br>";
						$nnn++;
					}
				}
			}
		}
	}
			
	if (sizeof($MetalArray)>0 && ($materialtype==1 || $materialtype==8 || $materialtype==4 || $materialtype==7 || $materialtype==9)) {
			
		foreach($MetalArray as $key=>$value) {
				
			if ($value>0) {
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?metal=".urlencode($key)."&amp;materialtype=".$materialtype."&amp;group=".$group."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayTime[$key])."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
						
				$numpage = ceil($value/8);
				if ($numpage>1) {
					for ($i=1;$i<=$numpage;$i++) {
							
						$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
						values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$MetalArrayTime[$key])."')";
						$result_ins = mysql_query($sql_ins);
						//echo $sql_ins."<br>";
						$nnn++;
					}
				}
			}
					
			if (sizeof($ArrayMetalName[$key])) {
							
				foreach ($ArrayMetalName[$key] as $key2=>$value2) {
								
					if ($value2>0) {
						$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
						values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($key2)."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayMetalNameTime[$key][$key2])."')";
						$result_ins = mysql_query($sql_ins);
						//echo $sql_ins."<br>";
						$nnn++;
										
						$numpage = ceil($value2/8);
						if ($numpage>1) {
							for ($i=1;$i<=$numpage;$i++) {
											
								$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
								values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;metal=".urlencode($key)."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;searchname=".urlencode($key2)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayMetalNameTime[$key][$key2])."')";
								$result_ins = mysql_query($sql_ins);
								//echo $sql_ins."<br>";
								$nnn++;
							}
						}
					}
				}
			}
		}
	}
			
	unset($MetalArrayAll);
	unset($MetalArray);
			
	if (sizeof($ArrayThemeAll)>0 && ($materialtype==1 || $materialtype==8)) {
			
		foreach($ArrayThemeAll as $key=>$value) {
			
			if ($value>0) {
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?theme=".$key."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeAllTime[$key])."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
						
				$numpage = ceil($value/8);
				if ($numpage>1) {
					for ($i=1;$i<=$numpage;$i++) {
						
						$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
						values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;materialtype=".$materialtype."&amp;theme=".$key."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeAllTime[$key])."')";
						$result_ins = mysql_query($sql_ins);
						//echo $sql_ins."<br>";
						$nnn++;
					}
				}
			}
		}
		unset($ArrayThemeAll);
			
		if (sizeof($ArrayTheme)>0)
			foreach($ArrayTheme as $key=>$value) {
				
				if ($value>0) {
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?theme=".$key."&amp;materialtype=".$materialtype."&amp;group=".$group."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeTime[$key])."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
							
					$numpage = ceil($value/8);
					if ($numpage>1) {
						for ($i=1;$i<=$numpage;$i++) {
								
							$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
							values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeTime[$key])."')";
							$result_ins = mysql_query($sql_ins);
							//echo $sql_ins."<br>";
							$nnn++;
						}
					}
				}
						
				if (sizeof($ArrayThemeName[$key])) {
						
					foreach ($ArrayThemeName[$key] as $key2=>$value2) {
							
						if ($value2>0) {
							$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
							values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($key2)."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeNameTime[$key][$key2])."')";
							$result_ins = mysql_query($sql_ins);
							//echo $sql_ins."<br>";
							$nnn++;
									
							$numpage = ceil($value2/8);
							if ($numpage>1) {
								for ($i=1;$i<=$numpage;$i++) {
										
									$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
									values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;theme=".$key."&amp;searchname=".urlencode($key2)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$ArrayThemeNameTime[$key][$key2])."')";
									$result_ins = mysql_query($sql_ins);
									//echo $sql_ins."<br>";
									$nnn++;
								}
							}
						}
					}
				}
			}
		unset($ArrayTheme);
		unset($ArrayThemeName);
	}
			
	$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
	values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
	$result_ins = mysql_query($sql_ins);
	//echo $sql_ins."<br>";
	$nnn++;

	$numpage = ceil($numbergroup/8);
	if ($numpage>1) {
		for ($i=1;$i<=$numpage;$i++) {
			
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
		}
	}
			
	if ($name && $numbername>0) {
				
		$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
		values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($name)."&amp;group=".$group."&amp;materialtype=".$materialtype."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
		$result_ins = mysql_query($sql_ins);
		//echo $sql_ins."<br>";
		$nnn++;
				
		$numpage = ceil($numbername/8);
		if ($numpage>1) {
			for ($i=1;$i<=$numpage;$i++) {
				
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;materialtype=".$materialtype."&amp;searchname=".urlencode($name)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
			}
		}
	}
}
//================================================================================================

$group = 0;
$name = '';
$materialtype = 0;
$number = 0;
$numbergroup = 0;
$numbername = 0;
$realtimemain = 0;
$realtimegroup = 0;
$realtimename = 0;


$sql = "select shopcoins.*, `group`.`name` as gname from shopcoins, `group` where shopcoins.`group`=`group`.`group` and shopcoins.`check`=1 and ((shopcoins.materialtype=1 and shopcoins.amountparent>0) or (shopcoins.materialtype<>1 and shopcoins.amount>0)) and (shopcoins.oldprice>0 ) order by shopcoins.materialtype, shopcoins.`group`, shopcoins.`name`, shopcoins.parent, shopcoins.shopcoins ;";
$result = mysql_query($sql);
//echo mysql_num_rows($result)."<br>";

$materialtype = 0;
//$nnn = 0;

while ($rows = mysql_fetch_array($result)) {

	if ($group>0 && $group != $rows['group']) {
	
		$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
		values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?group=".$group."&amp;search=revaluation', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
		$result_ins = mysql_query($sql_ins);
		//echo $sql_ins."<br>";
		$nnn++;
		$numpage = ceil($numbergroup/8);
		if ($numpage>1) {
			for ($i=1;$i<=$numpage;$i++) {
				
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;search=revaluation', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
			}
		}
		
		if ($name && $numbername>0) {
					
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($name)."&amp;group=".$group."&amp;search=revaluation', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
				
			$numpage = ceil($numbername/8);
			if ($numpage>1) {
				for ($i=1;$i<=$numpage;$i++) {
					
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;search=revaluation&amp;searchname=".urlencode($name)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
				}
			}
		}
		
		$group = $rows['group'];
		$name = $rows['name'];
		$numbergroup = 0;
		$numbername = 0;
		$realtimegroup = 0;
		$realtimename = 0;
	}
	elseif ($group == 0 && $group != $rows['group']) {
	
		$group = $rows['group'];
		$name = $rows['name'];
	}
	
	if ($name && $name != $rows['name']) {
	
	
		if ($name && $numbername>0) {
				
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($name)."&amp;group=".$group."&amp;search=revaluation', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
				
			$numpage = ceil($numbername/8);
			if ($numpage>1) {
				for ($i=1;$i<=$numpage;$i++) {
				
					$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
					values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;search=revaluation&amp;searchname=".urlencode($name)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
					$result_ins = mysql_query($sql_ins);
					//echo $sql_ins."<br>";
					$nnn++;
				}
			}
		}
		$name = $rows['name'];
		$numbername = 0;
		$realtimename = 0;
	}
	
	$realtime = ($rows['dateupdate']>0?$rows['dateupdate']:$rows['dateinsert']);
	if ($rows['datereprice']>$realtime)
		$realtime = $rows['datereprice'];
	
	if ($realtimename < $realtime)
		$realtimename = $realtime;
	
	if ($realtimegroup < $realtime)
		$realtimegroup = $realtime;
		
	if ($realtimemain < $realtime)
		$realtimemain = $realtime;
	
	$number++;
	$numbergroup++;
	$numbername++;
	
}

if ($rows['materialtype'] > 0) {
			
	$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
	values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?search=revaluation', 'hourly',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimemain)."')";
	$result_ins = mysql_query($sql_ins);
	//echo $sql_ins."<br>";
	$nnn++;
	$numpage = ceil($number/8);
	if ($numpage>1) {
		for ($i=1;$i<=$numpage;$i++) {
				
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;search=revaluation', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimemain)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
		}
	}
			
	$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
	values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?group=".$group."&amp;search=revaluation', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
	$result_ins = mysql_query($sql_ins);
	//echo $sql_ins."<br>";
	$nnn++;

	$numpage = ceil($numbergroup/8);
	if ($numpage>1) {
		for ($i=1;$i<=$numpage;$i++) {
			
			$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
			values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;search=revaluation', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimegroup)."')";
			$result_ins = mysql_query($sql_ins);
			//echo $sql_ins."<br>";
			$nnn++;
		}
	}
			
	if ($name && $numbername>0) {
				
		$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
		values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?searchname=".urlencode($name)."&amp;group=".$group."&amp;search=revaluation', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
		$result_ins = mysql_query($sql_ins);
		//echo $sql_ins."<br>";
		$nnn++;
				
		$numpage = ceil($numbername/8);
		if ($numpage>1) {
			for ($i=1;$i<=$numpage;$i++) {
				
				$sql_ins = "insert into sitemaps (`sitemaps`,`url`,`page`,`priority`,`type`,`dateinsert`,`lastmod`) 
				values (NULL, 'http://www.numizmatik.ru/shopcoins/index.php?pagenum=".$i."&amp;group=".$group."&amp;search=revaluation&amp;searchname=".urlencode($name)."', 'daily',0.5,'shopcoins',".time().",'".date('Y-m-d',$realtimename)."')";
				$result_ins = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				$nnn++;
			}
		}
	}
}
?>