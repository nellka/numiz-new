<?

require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/models/catalogshopcoinsrelation.php');
require_once $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($cfg['db']);

$catalogshopcoinsrelation_class = new model_catalogshopcoinsrelation($cfg['db']);
//var_dump($_SERVER);

$page = 'show';
$tpl['show']['lhreg'] = isset($_COOKIE['lhref'])?trim($_COOKIE['lhref']):(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/new/shopcoins');
$arraynewcoins = Array(1=>date('Y')-2,2=>date('Y')-1,3=>date('Y'));
$show50 = 0;

$catalog=(integer)request('catalog');
$materialtype=(integer)request('materialtype');
$parent=(integer)request('parent');

if (!$materialtype) 	$materialtype = 1;
if ($parent && !$catalog) 	$catalog = $parent;

$orderusernow = 0;
$ciclelink = "";

$num = 0;
$checkuser = 0;

$arraykeyword = array();

$tpl['show']['error'] = false;
//показываем количество страниц

$ourcoinsorder = array();
    	
if ($shopcoinsorder > 1) {
	$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
	foreach ( $result_ourorder as $rows_ourorder){
		$ourcoinsorder[] = $rows_ourorder["catalog"];
		$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
	}
}
		
if ($catalog){	
    //стартовая инфа о монете независимо от родитея
    $rows_main = $shopcoins_class->getItem($catalog,true);
    $statuses = array('buy_status'=>0,"reserved_status"=>0);
    
    if($rows_main['check']==1){
        $statuses = $shopcoins_class->getBuyStatus($catalog,$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
    }
   
    if($rows_main['check']!=1||!in_array($statuses['buy_status'],array(2,6,7,8))){      
        $is_found = false;   
        $CompareItemWithYear = $shopcoins_class->getCompareItem(array($catalog),$rows_main);
        foreach ($CompareItemWithYear as $row){
            $statuses = $shopcoins_class->getBuyStatus($row['shopcoins'],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
            if(in_array($statuses['buy_status'],array(2,6,7,8))){
                $rows_main = $row;
                $catalog = $row['shopcoins'];
                $is_found = true;
                break;
            }             
        }
        
        if(!$is_found){            
            $CompareItemWithoutYear = $shopcoins_class->getCompareItem($catalog,$rows_main,false);
            foreach ($CompareItemWithoutYear as $row){
                $statuses = $shopcoins_class->getBuyStatus($row['shopcoins'],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
                if(in_array($statuses['buy_status'],array(2,6,7,8))){
                    $rows_main = $row;
                    $catalog = $row['shopcoins'];
                    $is_found = true;
                    break;
                }             
            }
        }      
    }
    
    if($rows_main){
        $statuses = $shopcoins_class->getBuyStatus($catalog,$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
    	$rows_main['buy_status'] = $statuses['buy_status'];
    	$rows_main['reserved_status'] = $statuses['reserved_status'];	
    	
    	
    	$rows_main['name'] = contentHelper::nominalFormat($rows_main['name']);
    	$details = $details_class->getItem($catalog);
    	$rows_main['details'] =  '';
    	if($details) $rows_main['details'] = $details["details"];
    	$rows_main['metal'] = $tpl['metalls'][$rows_main['metal_id']];
    	$rows_main['condition'] = $tpl['conditions'][$rows_main['condition_id']];
    	     	
    	//подключаем ключевые слова для страницы товаров
        include($cfg['path'].'/configs/show_keywords.php');	
    	
    	$tpl['show']['described'] = false;
    	$groupselect_v2 = array();
    	//кто описывал монету
    	if ($catalog && $tpl['user']['user_id'] ) {  
    		if($materialtype == 11 &&$tpl['user']['user_id'] &&$user_class->is_user_has_premissons() && !$shopcoins_class->is_already_described($catalog)){    		    
    	        $tpl['show']['described'] = true;			
    			$result = $shopcoins_class->getGroupsForDescribe();
               
                $i=0;
    
    			foreach ($result as $rows){
    			     $currval = array();
                      $currval['label'] =  $rows["name"];
                      $currval['id'] = $rows['group'];
                      array_push($groupselect_v2, $currval);    			    
    			}			
    		}
    	}	    	
    	
    	
    	$rows_main['mark'] = $shopcoins_class->getMarks($rows_main["shopcoins"]);
    	//$tpl['show']['reviews'] = $shopcoins_class->getReviews($rows_main["shopcoins"]);
    	if(!$tpl['show']['reviews']['reviewusers'] = $cache->load("Reviews_$catalog")) {	   
    	    $tpl['show']['reviews']['reviewusers'] =  $shopcoins_class->getAdditionalReviews();	 
    	    $cache->save($tpl['show']['reviews']['reviewusers'], "Reviews_$catalog");	 
    	} 	
    
    	$tpl['show']['rows_main'] = $rows_main;
    	
    	
    	//тематика
    	$shopcoinstheme = array();
    	$ShopcoinsThemeArray = array();
    	$strtheme = decbin($rows_main["theme"]);
    	$strthemelen = strlen($strtheme);
    	$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
    	for ($k=0; $k<$strthemelen; $k++)
    	{
    		if ($chars[$k]==1)
    		{
    			$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
    			if (!in_array(($strthemelen-1-$k), $ShopcoinsThemeArray))
    				$ShopcoinsThemeArray[] = ($strthemelen-1-$k);
    		}
    	}
    	$tpl['show']['shopcoinstheme']  = $shopcoinstheme;
    	//серия
    	if ($rows_main["series"]){
    		$rows_main["series_title"] = $shopcoins_class->getSeries($rows_main["series"]);
    	}
    	
    	$rows_main['year'] = contentHelper::setYearText($rows_main['year'],$materialtype);    	    
    	
	} else $tpl['show']['error']['no_coins'] = true;	
} else {
	$tpl['show']['error']['no_coins'] = true;
}

require_once("show-search.ctl.php");