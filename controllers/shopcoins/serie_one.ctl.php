<?

$id = (int) request('id');


$whereParams['groups'] = $groups = (array) request('groups');
$whereParams['nominals'] = $nominals = (array) request('nominals');
$whereParams['years'] = $years = (array) request('years');
$whereParams['metals'] = $metals = (array) request('metals');
$whereParams['conditions'] = $conditions = (array) request('conditions');
$whereParams['themes'] = $themes = (array) request('themes');

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(12=>12,24=>24,48=>48);

if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} else {
    $tpl['onpage'] = 24;
}	



//setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$cfg['domain']);

//сохраняем сортировку элементов на странице в куке
$orderby_param = '';
if(request('orderby')){
    $tpl['orderby'] = request('orderby');      
} else {
    $tpl['orderby'] ='dateinsertdesc';
}	


if(!isset($tpl['orderby']))	$tpl['orderby'] = "yeardesc";
//setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$cfg['domain']);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

$base_url = $cfg['site_dir']."shopcoins/series/$id".(($tpl['onpage']!=24)?"&onpage=".$tpl['onpage']:"");

$tpl['series'] = array();

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(12=>12,24=>24,48=>48);

if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} else {
    $tpl['onpage'] = 24;
}	

//сохраняем сортировку элементов на странице в куке
$orderby_param = '';
if(request('orderby')){
    $tpl['orderby'] = request('orderby');  
    $orderby_param = '&orderby='.  $tpl['orderby'];
} else {
    $tpl['orderby'] ='dateinsertdesc';
}	


if(!isset($tpl['orderby']))	$tpl['orderby'] = "yeardesc";
//setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$cfg['domain']);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/models/shopcoinsbyseries.php');
require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($db_class);
$filter_groups =  array();
$tpl['one_series'] =  array();

$shopcoinsbyseries_class = new model_shopcoinsbyseries($db_class);

$mycoins = array();

$OrderByArray = Array();

//$OrderByArray[] = " shopcoins.name desc";

$OrderByArray[] ="novelty desc";

if ($tpl['orderby']=="dateinsertdesc"){
	$OrderByArray[] = "s.dateinsert desc";
	$OrderByArray[] = "s.price desc";
} elseif ($tpl['orderby']=="dateinsertasc"){
	$OrderByArray[] = " s.dateinsert asc";
	$OrderByArray[] = "s.price desc";
} elseif ($tpl['orderby']=="priceasc"){
	$OrderByArray[] = "s.price asc";
	$OrderByArray[] = "s.dateinsert desc ";
} elseif ($tpl['orderby']=="pricedesc"){
	$OrderByArray[] = "s.price desc";
	$OrderByArray[] = "s.dateinsert desc";
} elseif ($tpl['orderby']=="yearasc"){
	$OrderByArray[] = "s.year asc";
	$OrderByArray[] ="s.dateinsert desc";
} elseif ($tpl['orderby']=="yeardesc"){
	$OrderByArray[] = "s.year desc";
	$OrderByArray[] = "s.dateinsert desc ";
} elseif($materialtype==12){
	$OrderByArray[] = "s.year desc";
	$OrderByArray[] = "s.name desc ";
} 

$OrderByArray[] = "s.price desc";


if (sizeof($OrderByArray)){
	//$orderby = array_merge(array("s.check ASC"),$OrderByArray);
	$orderby = $OrderByArray;
}


if($id){
    $tpl['one_series'] = $shopcoinsbyseries_class->getSeriesDataById($id);
    
    if($tpl['one_series']){
    	$tpl['one_series']['url'] = $cfg['site_dir'].'shopcoins/'.($tpl['one_series']["alias"]?($tpl['one_series']["alias"].'-s'.$tpl['one_series']["id"].'.html'):('series/'.$tpl['one_series']["id"]));
		
    	if($tpl['one_series']['pagetitle']) $tpl['shopcoins']['_Title'] = $tpl['one_series']['pagetitle'];
		if($tpl['one_series']['description']) $tpl['shopcoins']['_Description'] = $tpl['one_series']['description'];
		
		//if($tpl['user']['user_id']==352480){  		
			require($cfg['path'].'/controllers/filters/series.ctl.php');			
		//}
		
        $countpubs = $shopcoinsbyseries_class->getCountCoinsBySeries($tpl['one_series']["whereselect"],$whereParams);
        
        if($countpubs<($tpl['pagenum']-1)*$tpl['onpage']) $tpl['pagenum']=1;
        
        $separator = '?';
        $sub = "";
        
        if($tpl['onpage']!=24){
        	$sub .= $separator."onpage=".$tpl['onpage'];
        	$separator ='&';
        }
        
        if($tpl['orderby'] !='dateinsertdesc'){	    
	    	$sub .= $separator.'orderby='.  $tpl['orderby'];
	    	$separator ='&';
		} 
			
		foreach ($whereParams as $key=>$values){
			foreach ($values as $v){
				$sub .= $separator."$key"."[]=".$v;
	    		$separator ='&';
			}
		}
		
        $tpl['paginator'] = new Paginator(array(
            'url'        => $tpl['one_series']['url'].$sub,
            'count'      => $countpubs,
            'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
            'page'       => $tpl['pagenum'],
            'border'     =>4)
        );
        
       
        $tpl['one_series_data'] = $shopcoinsbyseries_class->getCoinsBySeries($tpl['one_series']["whereselect"],$tpl['pagenum'],$tpl['onpage'],$orderby,$whereParams);    
        
        $tpl['one_series']['group'] = $shopcoins_class->getGroupItem($tpl['one_series']['countrygroup']);       
        
                
        $tpl['task'] = 'one_serie';
        
        $ourcoinsorder = array();
        $ourcoinsorderamount = array();
        if ($shopcoinsorder) {
        	$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
        	foreach ( $result_ourorder as $rows_ourorder){
        		$ourcoinsorder[] = $rows_ourorder["catalog"];
        		$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
        	}
        }
        $ArrayShopcoins  = array();
        $ArrayDataShopcoins  = array();
        
        foreach ($tpl['one_series_data'] as $key=>$rows){            
            $ArrayShopcoins[] = $rows["shopcoins"];
            //$ArrayDataShopcoins[$rows["shopcoins"]] = $rows;    
            $tpl['one_series']['data'][$rows["shopcoins"]] = $rows;       
       	    
        }
        
        if($ArrayShopcoins) $itemsShopcoins = $shopcoins_class->findByIds($ArrayShopcoins);
        
        foreach ($itemsShopcoins as $item){            
            $key = $item["shopcoins"];
        	$tpl['one_series']['data'][$key] = array_merge($tpl['one_series']['data'][$key],$item);
        	
        	//$group = $shopcoins_class->getGroupItem($rows['group']);	
        	
           // $tpl['one_series']['data'][$key]['gname'] = $group['name']	   ;
            //var_dump($tpl['one_series']['data'][$key]['gname']);
    	    $tpl['one_series']['data'][$key]['metal'] = $tpl['metalls'][$rows['metal_id']];		   
    	    $tpl['one_series']['data'][$key]['condition'] = $tpl['conditions'][$rows['condition_id']];
    	    
    	    $tpl['one_series']['data'][$key] = array_merge($tpl['one_series']['data'][$key], contentHelper::getRegHref($tpl['one_series']['data'][$key])); 
    	    $statuses = $shopcoins_class->getBuyStatus($key,$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder,$item);
    	   // var_dump($tpl['one_series']['data'][$key]);
    	    //echo "<br><br>";
    		$tpl['one_series']['data'][$key]['buy_status'] = $statuses['buy_status'];
    		
    		$tpl['one_series']['data'][$key]['reserved_status'] = $statuses['reserved_status'];	
    		$tpl['one_series']['data'][$key]['mark'] = $shopcoins_class->getMarks($key);
    		
    		$details = $details_class->getItem($key);
    	    $tpl['one_series']['data'][$key]["details"] =  '';
    	    if($details) $tpl['one_series']['data'][$key]["details"] = $details["details"];
        }        
        
    }        
}



?>