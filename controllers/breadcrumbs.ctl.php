<? //немного костыльный модуль, так как четкой иерархии страниц и меню пока нет
$tpl['breadcrumbs'][] = array(
	'text' => 'Главная',
	'href' => $cfg['site_dir'],
	'base_href' =>'/'
);
$tpl['current_page'] = '/';

if($tpl['module']=='order'&&$tpl['task']=='showorders'){
     $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => "Мои заказы",
    	'href' => "",
    	'base_href' =>""    );

    
    $tpl['current_page'] = '';
    
} elseif($tpl['module']=='order'&&$tpl['task']=='userorder'){
     $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => "Оформление заказа",
    	'href' => "",
    	'base_href' =>""    );

    
    $tpl['current_page'] = '';
    
}elseif ($tpl['module']=='shopcoins'&&$tpl['task']=='show'){
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype",
    	'base_href' =>"shopcoins/index.php?materialtype=$materialtype"
    );
    $tpl['breadcrumbs'][] = array(
    	'text' => $rows_main["name"],
    	'href' => '',
    	'base_href' =>''
    );
    
    $tpl['current_page'] = '';
    
} elseif ($tpl['module']=='shopcoins'&&$tpl['task']=='catalog_base'){
      $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	'href' => "",
    	'base_href' =>""    );

    
    $tpl['current_page'] = '';
} elseif ($tpl['module']=='order'&&$tpl['task']=='orderdetails'){
      $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => "Корзина",
    	'href' => "",
    	'base_href' =>""    );

    
    $tpl['current_page'] = '';
} elseif ($tpl['module']=='shopcoins'&&$tpl['task']=='catalog_search'){
      $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => "Поиск",
    	'href' => "",
    	'base_href' =>""    );

    
    $tpl['current_page'] = '';
}

if($_SERVER['REQUEST_URI']=='/new/ocenka-stoimost-monet'){
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Оценка стоимости монет',
    	'href' => $cfg['site_dir'].'ocenka-stoimost-monet',
    	'base_href' =>'ocenka-stoimost-monet'
    );
    $tpl['current_page'] = 'ocenka-stoimost-monet';
}


if($_SERVER['REQUEST_URI']=='/new/gde-prodat-monety'){
   
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Покупка/скупка монет',
    	'href' => $cfg['site_dir'].'gde-prodat-monety',
    	'base_href' =>'gde-prodat-monety'
    );
    $tpl['current_page'] = 'gde-prodat-monety';
    
}


?>