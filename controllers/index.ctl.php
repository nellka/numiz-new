<?
$tpl['banners']['main_center_1'] = '<img border="0" src="'.$cfg['site_root'].'/images/banners/main_center_1.jpg">';
$tpl['banners']['main_center_2'] = '<img border="0" src="'.$cfg['site_root'].'/images/banners/main_center_2.jpg">';

$tpl['coins']['populars'] = $shopcoins_class->getPopular(4);
$tpl['coins']['new'] = $shopcoins_class->getNew(4);
$tpl['coins']['actions'] = $shopcoins_class->getPopular(4);
$tpl['coins']['sales'] = $shopcoins_class->getPopular(4);
$tpl['coins']['populars_in_category'] = $shopcoins_class->getPopular(4);
?>