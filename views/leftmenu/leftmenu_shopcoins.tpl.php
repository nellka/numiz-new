<?
include('shopcoinsmenu.tpl.php');

IF($tpl['task']=='one_serie'){
	include(DIR_TEMPLATE.'leftmenu/filters/series/filter_country.tpl.php');
} else {
	include('stat_shopcoins.tpl.php');
   //подключаем фильтры для магазина
    if($tpl['task']=='catalog_base')  include('filter_country.tpl.php');
}


?>

