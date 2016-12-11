<?php 
exit;
$tpl['breadcrumbs'][] = array(
	    	'text' => 'Клуб Нумизмат',
	    	'href' => $cfg['site_dir'],
	    	'base_href' =>$cfg['site_dir']
);

require_once($cfg['path'].'/helpers/Paginator.php');

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Рейтинг сайтов',
	    	'href' => $cfg['site_dir'].'rating',
	    	'base_href' => ''
);
$group = (int) request('group');

$pagenum = (int) request('pagenum');

$s = request('s');
$ratinguser = (int) request('ratinguser');
$search_type = (int) request('search_type');

if (!$pagenum ) $pagenum  = 1;

$onpage = 10;

$keywords = $_Keywords; //$Meta[0];
$description = $_DescriptionRating;//$Meta[1];
$tpl['rating']['_Title'] = "Рейтинг сайтов | Клуб Нумизмат";
$tpl['rating']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";
$tpl['rating']['_Description'] = "Рейтинг сайтов рунета, посвященных нумизматике, бонистике и коллекционированию.";

$sql = "select `group`, name from `group` where `type`='rating';";

$tpl['groups'] = $shopcoins_class->getDataSql($sql);

//конец рисования таблицы групп
$ratinguser = intval($ratinguser);

if (!$s){
    if ($ratinguser){
            //находим к какой группе отоносится ratinguser
            // 
            $sql = "select `group` from ratinguser where ratinguser=".$ratinguser." and `check`=1;";
            $group = $shopcoins_class->getOneSql($sql);

            if ($group) {
                    $sql = "Select ru.ratinguser,  
                    rd.host, rd.hit from ratinguser as ru, ratingbydate as rd 
                    where ru.group=$group and  ru.`check`=1 and from_unixtime(rd.date)='".date('Y-m-d',time())." 00:00:00' 
                    and rd.ratinguser=ru.ratinguser 
                    group by ru.ratinguser order by rd.host desc, rd.hit desc;";
                    //echo $sql;

                    $tpl['ratings'] = $shopcoins_class->getDataSql($sql);
                    $i=0;
                    foreach ($tpl['ratings'] as $rows1) {
                            // and $check==0
                            if ($rows1["ratinguser"]!=$ratinguser)	{
                                    $i++;
                            } else {
                                    $check=1;
                                    $i++;
                                    break;
                            }
                    }

                    $pagenum = ceil($i/10);			 
            }
            //находим к какой странице относится ratinguser
    }

    if (!$group) {
            //отображаем Top10 рейтинг
            $sql = "Select ru.name, ru.url, ru.description, ru.ratinguser,  
            rd.host, rd.hit from ratinguser as ru, ratingbydate as rd 
            where  ru.`check`=1 and from_unixtime(rd.date)='".date('Y-m-d',time())." 00:00:00' and rd.ratinguser=ru.ratinguser 
            group by ru.ratinguser order by rd.host desc, rd.hit desc limit 10;";

            $tpl['ratings'] = $shopcoins_class->getDataSql($sql);

            $count=10;
            $p_url = $cfg['site_dir']."rating";        
            //конец отображения Top10 рейтинга
    } else {
        //отображаем рейтинг в категории
                        //счетчик
        $sql = "Select count(ratinguser) from ratinguser where `group`=$group and `check`=1;";
        $count = $shopcoins_class->getOneSql($sql);

        if ($count>0){			
                $pagestart = ($pagenum-1)*$onpage; 
                if($pagestart<0) $pagestart=0;
                $sql = "Select ru.name, ru.url, ru.description, ru.ratinguser,  
                rd.host, rd.hit from ratinguser as ru, ratingbydate as rd 
                where ru.group=$group and  ru.`check`=1 and from_unixtime(rd.date)='".date('Y-m-d',time())." 00:00:00' and rd.ratinguser=ru.ratinguser 
                group by ru.ratinguser order by rd.host desc, rd.hit desc limit $pagestart, 10;";

                $tpl['ratings'] = $shopcoins_class->getDataSql($sql);	

                $p_url = $cfg['site_dir']."rating/?group=$group";        
                //конец отображения рейтинга в категории
        }
    }
} else {	
	$s = str_replace("'","",$s);	
	//поиск
	if ($search_type==1) {
		$sql1 = "name like '%$s%'";
	} elseif ($search_type==2) {
		$sql1 = "keywords like '%$s%'";
	} elseif ($search_type==3) {
		$sql1 = "description like '%$s%'";
	} elseif ($search_type==4) {
		$sql1 = "(name like '%$s%' or description like '%$s%' or keywords like '%$s%')";
	}
	//отображаем рейтинг в категории

	$pagestart = ($pagenum-1)*$onpage; 
	//счетчик
	$sql = "Select count(ratinguser) from ratinguser where `check`=1 and $sql1;";
	$count = $shopcoins_class->getOneSql($sql);
	
	if ($count)	{	
		if($pagestart<0) $pagestart=0;	
		$p_url = $cfg['site_dir']."rating/?s=$s&search_type=$search_type".($group?"&group=$group":"");    
		
		$sql = "Select ru.name, ru.url, ru.description, ru.ratinguser,  
		rd.host, rd.hit from ratinguser as ru, ratingbydate as rd 
		where ru.`check`=1 and from_unixtime(rd.date)='".date('Y-m-d',time())." 00:00:00' and $sql1 
		and rd.ratinguser=ru.ratinguser 
		group by ru.ratinguser	order by rd.host desc, rd.hit desc limit $pagestart, $onpage;";
		$tpl['ratings'] = $shopcoins_class->getDataSql($sql);				
		//конец отображения рейтинга в категории
	}
	//конец поиска	
}

$tpl['paginator'] = new Paginator(array(      
				        'url'        => $p_url,
				        'count'      => $count,
				        'per_page'   => $onpage,
				        'page'       => $pagenum,
				        'border'     =>3));