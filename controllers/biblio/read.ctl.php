<?php
require_once $cfg['path'] . '/models/biblio.php';
$biblio_class = new model_biblio($db_class);

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Клуб Нумизмат',
	    	'href' => $cfg['site_dir'],
	    	'base_href' =>$cfg['site_dir']
);

$biblio = (int) request('biblio');

$Meta = $biblio_class->GetMetaField( "keywords", "text","biblio='".$biblio."'", "", 0,0);
$tpl['biblio']['errors'] = false;

$tpl['biblio']['data'] = $biblio_class->getItem($biblio);

$tpl['biblio']['_Description'] = 'Нумизматическая библиотека - интересные для любого коллекционера статьи, обзоры и аналитические материалы. '.$Meta[1];
$tpl['biblio']['_Title'] = "Библиотека нумизмата | Клуб Нумизмат | ".$tpl['biblio']['data']['name'];
$tpl['biblio']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";

$keywords = $tpl['biblio']['_Keywords'];
$correct_links = contentHelper::strtolower_ru($tpl['biblio']['data']["name"])."_n".$tpl['biblio']['data']["biblio"].".html";


if("/biblio/".$correct_links!=urldecode($_SERVER['REQUEST_URI'])){	
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: '.$cfg['site_dir']."biblio/".$correct_links);
	die();
}

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Библиотека нумизмата',
	    	'href' => $cfg['site_dir'].'biblio',
	    	'base_href' => $cfg['site_dir'].'biblio'
);

$tpl['breadcrumbs'][] = array(
	    	'text' => $tpl['biblio']['data']['name'],
	    	'href' => '',
	    	'base_href' =>""
);

$mytext = str_replace(chr(13), "<br>", $tpl['biblio']['data']['text']);
$mytext = str_replace("<<<", "<img src=$biblioimagesfolder/", $mytext);
$mytext = str_replace(">>>", ">", $mytext);
$tpl['biblio']['data']['text'] = str_replace("`", "'", $mytext);	

$tpl['pages'] = array();

if (!$tpl['biblio']['data']['parent']){
	//является вершиной дерева
	$sql = "select biblio,name from biblio where parent='$biblio' order by biblio;";
	$tpl['pages'] = $biblio_class->getDataSql($sql);

	foreach ($tpl['pages'] as $i=>$rows1){
		$tpl['pages'][$i]['correct_link'] = contentHelper::strtolower_ru($rows1["name"])."_n".$rows1["biblio"].".html";
	}
} else {
	//является лепестком
	$sql = "select biblio,name from biblio where biblio='".$tpl['biblio']['data']['parent']."' or parent='".$tpl['biblio']['data']['parent']."' order by parent, biblio;";
	$tpl['pages'] = $biblio_class->getDataSql($sql);

	foreach ($tpl['pages'] as $i=>$rows1){
		$tpl['pages'][$i]['correct_link'] = contentHelper::strtolower_ru($rows1["name"])."_n".$rows1["biblio"].".html";
	}
}	

$biblioByKeywords = $biblio_class->getBiblioByKeywords($keywords,$biblio);



$newsbyKeywords = $biblio_class->getNewsByKeywords($keywords,$biblio);

