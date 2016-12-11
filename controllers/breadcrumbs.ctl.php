<? //немного костыльный модуль, так как четкой иерархии страниц и меню пока нет

/*$tpl['breadcrumbs'][] = array(
	'text' => 'Главная',
	'href' => $cfg['site_dir'],
	'base_href' =>'/'
);
$tpl['current_page'] = '/';*/
if ($tpl['module']=='video'){
	$tpl['breadcrumbs'][] = array(
		'text' => 'Главная',
		'href' => $cfg['site_dir'],
		'base_href' =>'/'
	);

    $tpl['breadcrumbs'][] = array(
    	'text' => 'Видео для нумизматов',
    	'href' => '',
    	'base_href' =>''
    );    
    $tpl['current_page'] = '';
    
} /*elseif ($tpl['module']=='catalognew'&&$tpl['task']!='show'){
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Каталог монет',
    	'href' => '',
    	'base_href' =>''
    );    
    $tpl['current_page'] = '';
    
}*/ elseif($tpl['module']=='user'&&$tpl['task']=='registration'){    
   
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Регистрация',
    	'href' => '',
    	'base_href' =>''
    );
        
    $tpl['current_page'] = '';
} elseif ($tpl['module']=='shopcoins'&&$tpl['task']=='show'){
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	//'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype",
		'href' => urlBuild::makePrettyOfferUrl(array('materialtype'=>$materialtype),$materialIDsRule,$ThemeArray,$tpl,$shopcoins_class),
    	'base_href' =>urlBuild::makePrettyOfferUrl(array('materialtype'=>$materialtype),$materialIDsRule,$ThemeArray,$tpl,$shopcoins_class)
    );
    
    if($rows_main["gname"]&&$rows_main["group"]){
         $tpl['breadcrumbs'][] = array(
        	'text' => $rows_main["gname"],
        	//'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype&group=".$rows_main["group"],
			'href' =>urlBuild::makePrettyOfferUrl(array('materialtype'=>$materialtype,'group_id'=>$rows_main["group"]),$materialIDsRule,$ThemeArray,$tpl,$shopcoins_class),
        	'base_href' =>"shopcoins/index.php?materialtype=$materialtype&group=".$rows_main["group"]
        );
    }
    $tpl['breadcrumbs'][] = array(
    	'text' => $rows_main["name"],
    	'href' => '',
    	'base_href' =>''
    );
    
    $tpl['current_page'] = '';
    
}elseif ($tpl['module']=='shopcoins'&&$tpl['task']=='showa'){
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype",
    	'base_href' =>"shopcoins/index.php?materialtype=$materialtype"
    );
    
    if($rows_main["gname"]&&$rows_main["group"]){
         $tpl['breadcrumbs'][] = array(
        	'text' => $rows_main["gname"],
        	'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype&group=".$rows_main["group"],
        	'base_href' =>"shopcoins/index.php?materialtype=$materialtype&group=".$rows_main["group"]
        );
    }
    $tpl['breadcrumbs'][] = array(
    	'text' => $rows_main["name"],
    	'href' => '',
    	'base_href' =>''
    );
    
    $tpl['current_page'] = '';
    
}


if ($tpl['module']=='news'&&$tpl['task']=='show'){
    
    $tpl['breadcrumbs'][] = array(
		'text' => 'Новости нумизматики',
		'href' => $r_url,
		'base_href' =>$r_url
	);
	
   
    if($tpl['news']['data']['group']){
         $tpl['breadcrumbs'][] = array(
        	'text' => $tpl['news']['data']['group_title'],
        	'href' => $r_url.'?group='.$tpl['news']['data']['group'],
        	'base_href' =>$r_url.'?group='.$tpl['news']['data']['group']
        );
    }
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $tpl['news']['data']["name"],
    	'href' => '',
    	'base_href' =>''
    );
    
    $tpl['current_page'] = '';
    
} 

if ($tpl['module']=='shopcoins'&&$tpl['task']=='series'){
      $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>$cfg['site_dir'].'shopcoins'
    );
   
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Серии монет',
    	'href' => '',
    	'base_href' =>''
    );
        
    $tpl['current_page'] = '';
    
} else if($tpl['module']=='shopcoins'&&$tpl['task']=='one_serie'){
      $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
   
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Серии монет',
    	'href' => $cfg['site_dir'].'shopcoins/series',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $tpl['one_series']['name'],
    	'href' => '',
    	'base_href' =>''
    );
    
    $tpl['current_page'] = '';
}

if ($tpl['module']=='shopcoins'&&$tpl['task']=='viporder'){
      $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
   
    $tpl['breadcrumbs'][] = array(
    	'text' => "Рекомендуем заказать",
    	'href' => "",
    	'base_href' =>""    );

    $tpl['current_page'] = '';
} elseif($tpl['module']=='order'&&$tpl['task']=='showorders'){
     $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => "Мои заказы",
    	'href' => "",
    	'base_href' =>""    );

    
    $tpl['current_page'] = '';
    
} elseif($tpl['module']=='order'&&($tpl['task']=='userorder'||$tpl['task']=='submitorder')){
     $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => "Оформление заказа",
    	'href' => "",
    	'base_href' =>""    );

    
    $tpl['current_page'] = '';
    
} /*elseif ($tpl['module']=='shopcoins'&&$tpl['task']=='show'){
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    
    $tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype",
    	'base_href' =>"shopcoins/index.php?materialtype=$materialtype"
    );
    
    if($rows_main["gname"]&&$rows_main["group"]){
         $tpl['breadcrumbs'][] = array(
        	'text' => $rows_main["gname"],
        	'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype&group=".$rows_main["group"],
        	'base_href' =>"shopcoins/index.php?materialtype=$materialtype&group=".$rows_main["group"]
        );
    }
    $tpl['breadcrumbs'][] = array(
    	'text' => $rows_main["name"],
    	'href' => '',
    	'base_href' =>''
    );
    
    $tpl['current_page'] = '';
    
} elseif ($tpl['module']=='shopcoins'&&$tpl['task']=='catalog_base'){
      
} */elseif ($tpl['module']=='order'&&$tpl['task']=='orderdetails'){
      $tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
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
    	'text' => 'Магазин монет',
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

if($_SERVER['REQUEST_URI']=='/new/shopcoins/delivery.php'){
	$tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Оплата и доставка',
    	'href' => $cfg['site_dir'].'shopcoins/delivery.php',
    	'base_href' =>'delivery'
    );
    $tpl['current_page'] = 'delivery';
}

if($_SERVER['REQUEST_URI']=='/new/shopcoins/how.php'){
	$tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Как сделать заказ',
    	'href' => $cfg['site_dir'].'shopcoins/how.php',
    	'base_href' =>'how'
    );
    $tpl['current_page'] = 'how';
}

if($_SERVER['REQUEST_URI']=='/new/shopcoins/shopcoinsrules.php'){
	$tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Правила магазина',
    	'href' => $cfg['site_dir'].'shopcoins/shopcoinsrules.php',
    	'base_href' =>'shopcoinsrules'
    );
    $tpl['current_page'] = 'shopcoinsrules';
}

if($_SERVER['REQUEST_URI']=='/new/shopcoins/shopcoinshelp.php'){
	$tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    $tpl['breadcrumbs'][] = array(
    	'text' => 'ЧаВо по магазину',
    	'href' => $cfg['site_dir'].'shopcoins/shopcoinshelp.php',
    	'base_href' =>'shopcoinshelp'
    );
    $tpl['current_page'] = 'shopcoinshelp';
}

if($_SERVER['REQUEST_URI']=='/new/about.php'){
	$tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    $tpl['breadcrumbs'][] = array(
    	'text' => 'О нас',
    	'href' => $cfg['site_dir'].'about.php',
    	'base_href' =>'about'
    );
    $tpl['current_page'] = 'about';
}


if($_SERVER['REQUEST_URI']=='/new/shopinfo.php'){
	$tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Контакты',
    	'href' => $cfg['site_dir'].'shopinfo.php',
    	'base_href' =>'shopinfo'
    );
    $tpl['current_page'] = 'shopinfo';
}

if($_SERVER['REQUEST_URI']=='/new/garantii-podlinosti-monet'){
	$tpl['breadcrumbs'][] = array(
    	'text' => 'Магазин монет',
    	'href' => $cfg['site_dir'].'shopcoins',
    	'base_href' =>'shopcoins'
    );
   
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Гарантии подлинности монет',
    	'href' => $cfg['site_dir'].'garantii-podlinosti-monet',
    	'base_href' =>'garantii-podlinosti-monet'
    );
    $tpl['current_page'] = 'garantii-podlinosti-monet';
    
}

?>