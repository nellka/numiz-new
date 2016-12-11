<?

$id = (int) request('id');
$tpl['series'] = array();
$tpl['one_series'] = array();

require($cfg['path'].'/helpers/Paginator.php');
require($cfg['path'].'/models/shopcoinsbyseries.php');
require_once $cfg['path'] . '/models/shopcoinsdetails.php';

$details_class = new model_shopcoins_details($db_class);
$shopcoinsbyseries_class = new model_shopcoinsbyseries($db_class);



if($id){
    require("serie_one.ctl.php");    
} else {
	$tpl['shopcoins']['_Title'] = 'Купить монеты по сериям в магазине Клуб Нумизмат в Москве и с доставкой по России';
	$tpl['shopcoins']['_Description'] = 'Самые распространенные серии монет – СССР, ГВС, квотеры США, доллар с президентом, Бородино 2012, Сочи 2014, 2 евро и другие. Поступления ежедневно.&#10152;';
    //получаем все доступные серии
    /*$tpl['series_groups'] = $shopcoinsbyseries_class->getGroups();
    foreach ($tpl['series_groups'] as $group){       
        $tpl['series'][$group['group']]['group'] = $group;
        $tpl['series'][$group['group']]['data'] = $shopcoinsbyseries_class->getSeriesByCountry($group['group']);
    }*/
    
    $tpl['series']['data'] = $shopcoinsbyseries_class->getAllSeries();
    $groups = array();
    foreach ($tpl['series']['data'] as $key=>$row){
        if(!isset($groups[$row["countrygroup"]])){
            $group = $shopcoins_class->getGroupItem($row["countrygroup"]);	
            $groups[$row["countrygroup"]] =  $group["name"];
        }        

        $tpl['series']['data'][$key]['groupname'] = $groups[$row["countrygroup"]];
    }    
}


?>