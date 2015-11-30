<? //немного костыльный модуль, так как четкой иерархии страниц и меню пока нет
$tpl['breadcrumbs'][] = array(
	'text' => 'Главная',
	'href' => $cfg['site_dir'],
	'base_href' =>'/'
);
$tpl['current_page'] = '/';
if($_SERVER['REQUEST_URI']=='/ocenka-stoimost-monet'){
    $tpl['breadcrumbs'][] = array(
    	'text' => 'Оценка стоимости монет',
    	'href' => $cfg['site_dir'].'ocenka-stoimost-monet',
    	'base_href' =>'ocenka-stoimost-monet'
    );
    $tpl['current_page'] = 'ocenka-stoimost-monet';
}



?>