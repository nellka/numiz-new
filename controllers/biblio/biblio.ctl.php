<?php 
require_once $cfg['path'] . '/models/biblio.php';

$biblio_class = new model_biblio($db_class);

$tpl['onpage'] = 15;

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

if ($tpl['pagenum']<1) $tpl['pagenum']=1;

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Клуб Нумизмат',
	    	'href' => $cfg['site_dir'],
	    	'base_href' =>$cfg['site_dir']
);

require_once($cfg['path'].'/helpers/Paginator.php');

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Библиотека нумизмата',
	    	'href' => $cfg['site_dir'].'biblio',
	    	'base_href' => ''
);

$group = (int) request('group');
$s = request('s');
$st = request('st');

$Meta = $biblio_class->GetMetaField( "keywords", "text"," `check`=1 and parent=0 and `group`='$group' ", "date desc", 3,18);

$tpl['biblio']['_Description'] = 'Нумизматическая библиотека - интересные для любого коллекционера статьи, обзоры и аналитические материалы. '.$Meta[1];
$tpl['biblio']['_Title'] = "Библиотека нумизмата | Клуб Нумизмат | ".$name;
$tpl['biblio']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";



$tpl['groups'] = $biblio_class->getGroups();

$p_url = $cfg['site_dir']."biblio";

if (!$s){
	if ($group) {
		$sql = "Select count(*) from biblio where `check`=1 and parent=0 and `group`=$group;";
	} else	$sql = "Select count(*) from biblio where `check`=1 and parent=0;";
	
	$count = $biblio_class->getOneSql($sql);
	
	$pages=ceil($count/$tpl['onpage']);
	
	if (!$pages) $pages=1;
	if ($tpl['pagenum'] >$pages) $tpl['pagenum'] = $pages;
		
	if ($group) {		
		$sql = "select biblio, FROM_UNIXTIME(date, \"%d.%m.%Y\") as t, name, text 
		from biblio where `check`=1 and parent=0 and `group`=$group 
		order by date desc 
		limit ".($tpl['pagenum']-1)*$tpl['onpage'].",".$tpl['onpage'];
	} else {
		$sql = "select biblio, FROM_UNIXTIME(date, \"%d.%m.%Y\") as t, name, text 
		from biblio where `check`=1 and parent=0 order by date desc limit ".($tpl['pagenum']-1)*$tpl['onpage'].",".$tpl['onpage'];
	}
	$tpl['result'] = $biblio_class->getDataSql($sql);
	
	$nnn=0;
	foreach ($tpl['result'] as $n=>$rows){
		$text = mb_substr($rows['text'], 0, 250,'utf8');
		
		while(substr_count($text,"<<<")){
                    $text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
		}		
		$tpl['result'][$n]['text'] = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		$tpl['result'][$n]['namehref'] = contentHelper::strtolower_ru($rows["name"])."_n".$rows["biblio"].".html";
		
	}
} elseif ($s) {
    if (strlen($s)>3){

        $s = str_replace("'","",$s);
        if ($st==1) {
            $sql1 = "name like '%$s%'";
        } elseif ($st==2) {
            $sql1 = "keywords like '%$s%'";
        } elseif ($st==3) {
            $sql1 = "text like '%$s%'";
        } elseif ($st==4) {
            $sql1 = "(name like '%$s%' or text like '%$s%' or keywords like '%$s%')";
        }
        //отображаем страницы
        $sql = "Select count(*) from biblio where `check`=1 and $sql1;";

        $count = $biblio_class->getOneSql($sql);
	
	$pages=ceil($count/$tpl['onpage']);

        if ($tpl['pagenum']>$pages) $tpl['pagenum']=$pages;
        
        $p_url = $cfg['site_dir']."biblio/?s=$s&st=$st".($group?"&group=$group":"");    
		
        if ($count>0){

            $sql = "select biblio, FROM_UNIXTIME(date, \"%d.%m.%Y\"), 
            name, text from biblio 
            where $sql1 and `check`=1 order by date desc 
            limit ".($tpl['pagenum']-1)*$tpl['onpage'].",".$tpl['onpage'];
            $tpl['result'] = $biblio_class->getDataSql($sql);

            $nnn=0;
            foreach ($tpl['result'] as $n=>$rows){
                $text = mb_substr($rows['text'], 0, 250,'utf8');

                while(substr_count($text,"<<<")){
                    $text=substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3);
                }		
                $tpl['result'][$n]['text'] = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
                $tpl['result'][$n]['namehref'] = contentHelper::strtolower_ru($rows["name"])."_n".$rows["biblio"].".html";
            }
        } else {
            $tpl['error'] = "Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.";
        }		
    } else {
        $tpl['error'] =  "Внимание!!!</font> Cлово для поиска должно состоять более чем из трех букв";
    }	
}

$tpl['paginator'] = new Paginator(array(      
                    'url'        => $p_url,
                    'count'      => $count,
                    'per_page'   => $tpl['onpage'],
                    'page'       => $tpl['pagenum'],
                    'border'     =>3));