<?php
try {
/*$IsCompletePagePHP = 1;

include $_SERVER["DOCUMENT_ROOT"]."/config.php";
include "config.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/funct.php";
include "funct.php";
include_once($_SERVER["DOCUMENT_ROOT"]."/keywordsAdmin.php");
$catalog = intval($catalog);*/

require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/models/catalognew.php');
require_once($cfg['path'].'/configs/config_catalognew.php');


$tpl['task'] = 'catalognew';

$catalognew_class = new model_catalognew($db_class);

$tpl['breadcrumbs'][] = array(
    	'text' => 'Каталог монет',
    	'href' => $cfg['site_dir'].'catalognew',
    	'base_href' =>'catalognew'
);  
 
$tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	'href' => $cfg['site_dir'].'catalognew/index.php?materialtype='.$materialtype,
    	'base_href' =>$cfg['site_dir'].'catalognew/index.php?materialtype='.$materialtype );
$tpl['shop']['errors'] = array();

$sortname = request('sortname');

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(9=>9,18=>18,36=>36,48=>48,72=>72);

//сохраняем количество элементов на странице в куке
if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} elseif (isset($_COOKIE['onpage'])){
    $tpl['onpage'] =$_COOKIE['onpage'];
}	
if(!isset($tpl['onpage']))	$tpl['onpage'] = 18;

setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$domain);

//сохраняем сортировку элементов на странице в куке
if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby'])){
    $tpl['orderby'] =$_COOKIE['orderby'];
}	
if(!isset($tpl['orderby']))	$tpl['orderby'] = "dateinsertdesc";
setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$domain);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;
    	
//коллекция пользователя http://www.numizmatik.ru/catalognew/index.php?1&usershopcoinssubscribe=355338
$usershopcoinssubscribe = (int)request('usershopcoinssubscribe');
$usercatalogsubscribe = (int)request('usercatalogsubscribe');
$usermycatalog = (int)request('usermycatalog');
$usermycatalogchange = (int)request('usermycatalogchange');
$materialtype = (int) request('materialtype');

if(!$materialtype) $materialtype = 1;

$GroupNameMain = '';
$GroupNameID = '';
$GroupName = ''; 


$WhereParams = array();
$group = (int)request('group');

$theme_data = array();
$metal_data = array();
$year_data = array();
$group_data = array();
$years_data  = array(); 
$years_p_data = array(); 
$nominal_data = array(); 

$groups = (array)request('groups');


$groupHref = "";
$groupMain = 0;

if ($groups)  $group_data = $groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}

if($metals&&!is_array($metals)){
    $metals = array($metals);
}

if ($metals) $metal_data = $metals;
elseif($metal) {	
	if((int)$metal==0){
		//для старых запросов
		$metal = array_search($metal,$tpl['metalls']);
	}
	
	if($metal){
		$metal_data =  array($metal);
		$metals =  array($metal);
	}
}

if ($themes) $theme_data =$themes;
elseif($theme) {
    $theme_data =  array($theme);
    $themes =  array($theme);
}
	
if ($groups)  $group_data =$groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}

if($groups){
	if ($nominals) $nominal_data =$nominals;
	elseif($nominal) {
		$nominal_data =  array($nominal);
		$nominals =  array($nominal);
	}
}

if(($nominals&&$groups)||($groups&&$materialtype==4)){
    $years_p_data = $years_p;
} else {
    
    if($yearend==date('Y',time())){
    	$yearend='';
    }
    
    krsort($years);
    
    $i=0;
    foreach ($years as $val){       
       if($i>0){   
            //если совпадают концы интервалов
            if(($years_data[$i-1][1]+1)==$yearsArray[$val]['data'][0]) {           
                 $years_data[$i-1][1]=$yearsArray[$val]['data'][1];
            } else {
                $years_data[] = $yearsArray[$val]['data'];
                $i++;
            }
       } else {
    	   $years_data[] = $yearsArray[$val]['data'];
    	   	$i++;
       }

    }    
    //формируем массив интервалов
    if($yearstart||$yearend){    
    	$years_data[] = array($yearstart,$yearend);
    }
}

$tpl['is_Subscribe_for_group'] = false;

if(count($group_data)==1){
    $groupMain = $GroupNameID = $group_data[0];
    $data_filter['group_id'] = $groupMain;
    $groupData = $shopcoins_class->getGroupItem($group_data[0]);
	//получаем дочерние элементы    
	$childs = $catalognew_class->getParrentGroupsIds($group_data[0]);
    //Проверяем, подписаны или нет
    
    $tpl['is_Subscribe_for_group'] = $catalognew_class->getMyGroupSubscribe($tpl['user']['user_id'],$group_data[0]);
    
	$i=1;
	foreach ($childs as $child){
	    $group_data[$i] = $child;
	    $groups[$i] = $child;
	    $i++;
	}	
		
	$GroupName = $groupData["name"];
	
	$tpl['breadcrumbs'][] = array(
        	'text' => $GroupName,
        	'href' => $r_url.contentHelper::groupUrl($GroupName,$groupMain),
        	'base_href' =>$r_url.contentHelper::groupUrl($GroupName,$groupMain)
    );
        
	$groupHref = contentHelper::groupUrl($GroupName,$groupMain);
	
	//$grouphref = strtolower_ru($GroupName)."_gn".$groupData['group'];
	$arraykeyword[] = $groupData["name"];

	if (trim($groupData["description"])){
		$text = substr($groupData["description"], 0, 650);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));

		$text = iconv( "CP1251//TRANSLIT//IGNORE","UTF8", $text);

		//UTF-8
		$pic = '';
		
		if ($groupData["flagsmall"]) {
		    
			$pic = "../group/smallimages/".$groupData["flagsmall"];
		} elseif ($groupData["emblemsmall"]) {
			$pic = "../group/smallimages/".$groupData["emblemsmall"];
		} elseif ($groupData["mapsmall"]){
			$pic = "../group/smallimages/".$groupData["mapsmall"];			
		}
				
		$tpl['GroupDescription'] = "<div class='left' style='padding: 0 10px 0 0;'>".contentHelper::showImage($pic,'')."</div>
			<div>".str_replace("\n","<br>",$text)."</div>";		
		unset ($text);
		unset ($pic);
	}
	
	if ($groupData["groupparent"] != 0 && $groupData["groupparent"] != $groupData["group"]) {	
	    $groupParentData = $shopcoins_class->getGroupItem($groupData["groupparent"]); 		
		$GroupNameMain = $groupParentData['name'];
	}	
	
	$tpl['catalognew']['OtherMaterialData'] = $catalognew_class->getOtherMaterialData($group_data[0],$materialtype);		

	if ($tpl['catalognew']['OtherMaterialData']){		
	    $oldmaterialtype = 0;
		foreach ($tpl['catalognew']['OtherMaterialData'] as $key=>$rows){
		    
		    $tpl['catalognew']['OtherMaterialData'][$key]['metal'] = isset($tpl['metalls'][$rows['metal_id']])?$tpl['metalls'][$rows['metal_id']]:'';
		    $tpl['catalognew']['OtherMaterialData'][$key]['condition'] = isset($tpl['conditions'][$rows['condition_id']])?$tpl['conditions'][$rows['condition_id']]:'';
		    $tpl['catalognew']['OtherMaterialData'][$key]['gname'] = $GroupName;
		    
		    /*$tpl['catalognew']['related'][$i]['additional_title'] = '';
			if ($oldmaterialtype != $rows["materialtype"]) {
				$tpl['catalognew']['related'][$i]['additional_title'] = $MaterialTypeArray[$rows["materialtype"]];
				$oldmaterialtype = $rows["materialtype"];
			}*/
		}
	}	
}

if($groups){
	if ($nominals) $nominal_data =$nominals;
	elseif($nominal) {
		$nominal_data =  array($nominal);
		$nominals =  array($nominal);
	}
}

$WhereParams['materialtype'] = $materialtype;

$catalognew_class->setMaterialtype($materialtype);


$tpl['catalognew']['user_id'] = $tpl['user']['user_id'];

if ($usershopcoinssubscribe) {
    if ($usershopcoinssubscribe == $tpl['user']['user_id']){
        $tpl['catalognew']['user_id'] = $tpl['user']['user_id'];
   } else $tpl['catalognew']['user_id'] = $usershopcoinssubscribe;
   
   $tpl['catalognew']['href'] = $cfg["site_dir"]."catalognew/index.php?usershopcoinssubscribe=".$usershopcoinssubscribe.'&materialtype='.$materialtype;

} elseif ($usercatalogsubscribe) {
        
    if ($usercatalogsubscribe == $tpl['user']['user_id']){
        $tpl['catalognew']['user_id'] = $tpl['user']['user_id'];
    } else $tpl['catalognew']['user_id'] = $usercatalogsubscribe;
       
} elseif ($usermycatalog) {
    
    if ($usermycatalog == $tpl['user']['user_id']){
        $tpl['catalognew']['user_id'] = $tpl['user']['user_id'];
    } else $tpl['catalognew']['user_id'] = $usermycatalog;
    
    $tpl['catalognew']['href'] = $cfg["site_dir"]."catalognew/index.php?usermycatalog=".$usermycatalog.'&materialtype='.$materialtype;
    
} elseif ($usermycatalogchange) {    
    if ($usermycatalogchange == $tpl['user']['user_id']){
        $tpl['catalognew']['user_id'] = $cookiesuser;
    } else $tpl['catalognew']['user_id'] = $usermycatalogchange;
    
    $tpl['catalognew']['href'] = $cfg["site_dir"]."catalognew/index.php?usermycatalogchange=".$usermycatalogchange.'&materialtype='.$materialtype;

}

if(($tpl['user']['user_id']!=$tpl['catalognew']['user_id'])&&$tpl['catalognew']['user_id']){
    $tpl['catalognew']['user'] = $user_class->getUserDataByID($tpl['catalognew']['user_id']);
}


$arraygroupbottom = array();

if ($materialtype==3) {

    $arraygroupbottom[] = 816;
    $sql5 = "select `group` from `group` where groupparent=816;";
    $result5 = mysql_query($sql5);
    while ($rows5 = mysql_fetch_array($result5))
        $arraygroupbottom[] = $rows5[0];
}

//vbhjckfd start

//vbhjckfd stop
/*
if ($savesearch){
    include "searchindex.php";
}


//получаем поисковый запрос
if ($searchid)
{
    $searchid = intval($searchid);

    if (!$WhereSearch)
    {
        $fd = fopen ("searchnow.dat", "r");
        $content .= fread ($fd, filesize ("searchnow.dat"));
        $tmp = explode("\n", $content);
        foreach ($tmp as $key=>$value)
        {
            //теперь разделяем по \t
            $tmp1 = explode("\t", $value);
            if ($tmp1[1] == $searchid)
            {
                $WhereSearch = $tmp1[3];
                break;
            }
        }
        fclose ($fd);
    }
}*/

$ciclelink = "";
$textalbom = " <a href=".$in."shopcoins/albom_dlya_monet.html> Альбомы для монет</a><br>";

/*
if ($group){
    $group = intval($group);

    unset($arraygroupcatalog);

    $sql = "select * from `group` where `group`='$group' or groupparent='$group';";
    $result = mysql_query($sql);
    while ($rows = mysql_fetch_array($result)) {

        $arraygroupcatalog[] = $rows['group'];

        if ($group==$rows['group'])
            $GroupName = $rows["name"];

        $rows1 = $rows;
    }

    if (trim($rows1["description"]))
    {
        $text = substr($rows1["description"], 0, 450);
        $text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));

        $pic = Array();
        if ($rows1["flagsmall"])
            $pic[] = $in."group/smallimages/".$rows1["flagsmall"];

        if ($rows["emblemsmall"])
            $pic[] = $in."group/smallimages/".$rows1["emblemsmall"];

        if ($rows["mapsmall"])
            $pic[] = $in."group/smallimages/".$rows1["mapsmall"];

        if (sizeof($pic) > 1)
            $GroupDescription = "<table border=0 cellpadding=2 cellspacing=1 align=center>
			<tr class=tboard bgcolor=#EBE4D4>
			<td valign=top><img src=".$pic[0]." border=1 alt='Флаг ".$GroupName."'></td>
			<td valign=top>".str_replace("\n","<br>",$text)."
			<br><a href=# onclick=\"javascript:window.open('groupdescription.php?group=$group','groupdescription$group','width=600,height=700,top=0,left=0,status=no,menubar=no,scrollbars=yes,resizable=no');\" title='Подробнее о(об) ".$GroupName."'>Далее >>></a></td>
			<td valign=top><img src=".$pic[1]." border=1 alt='Герб ".$GroupName."'></td>
			</tr>
			</table>";
        elseif (sizeof($pic) == 1)
            $GroupDescription = "<table border=0 cellpadding=2 cellspacing=1 align=center>
			<tr class=tboard bgcolor=#EBE4D4>
			<td valign=top><img src=".$pic[0]." border=1 alt='Карта ".$GroupName."'></td>
			<td valign=top>".str_replace("\n","<br>",$text)."
			<br><a href=# onclick=\"javascript:window.open('groupdescription.php?group=$group','groupdescription$group','width=600,height=700,top=0,left=0,status=no,menubar=no,scrollbars=yes,resizable=no');\" title='Подробнее о(об) ".$GroupName."'>Далее >>></a></td>
			</tr>
			</table>";

        unset ($text);
        unset ($pic);
    }
}*/



    $wherestart = " where catalognew.agreement >= 0";
    $where = "$wherestart $where $WhereSearch";

    if ($materialtype)
        $where .= " and (catalognew.materialtype='$materialtype') ";

    if ($theme) {
        $theme = intval($theme);
        $where .= " and (theme='".pow(2,$theme)."' or theme & ".pow(2,$theme).">0)";
    }
    if ($searchname)
    {
        $searchname = str_replace("'","",$searchname);
        $searchwhere = " and catalognew.name='".$searchname."' ";
        $where .= $searchwhere;
    }
/*
    if ($group)
    {
        
        
        $wheregroup = " and catalognew.`group` in(".implode(",",$arraygroupcatalog).") ".($materialtype?" and materialtype=".$materialtype:"");
        $where .= $wheregroup;
    }*/

    if($group_data) $WhereParams['group'] = $group_data;
    if($metal_data) $WhereParams['metal'] = $metal_data;

    if($theme_data) $WhereParams['theme'] = $theme_data;
    if($years_data) $WhereParams['year'] = $years_data;
    if($years_p_data) $WhereParams['year_p'] = $years_p_data;   
    if($nominal_data)  $WhereParams['nominals'] = $nominal_data;
    
   

    if ($metal) {
        $metal = intval($metal);
        $where .= " and catalognew.metal='".$metal."'";
    }
    if ($yearstart || $yearend)
    {

        $yearstart = intval($yearstart);
        $yearend = intval($yearend);
        if ($yearstart == $yearend)
            unset ($yearend);

        if ($yearstart and !$yearend)
        {
            $sqlyear =" and catalognew.catalog=catalogyear.catalog and (catalogyear.yearstart <= '".$yearstart."' and catalogyear.yearend >= '".$yearstart."') ";
            $fromtable .= ", catalogyear";
        }
        elseif ($yearstart and $yearend and ($yearstart < $yearend))
        {
            $sqlyear =" and catalognew.catalog = catalogyear.catalog and (
			(
			(catalogyear.yearstart >= '".$yearstart."' and catalogyear.yearend <= '".$yearend."' and catalogyear.yearstart>0 and catalogyear.yearend>0)
			or (catalogyear.yearstart <= '".$yearstart."' and catalogyear.yearend >= '".$yearstart."'  and catalogyear.yearend <= '".$yearend."')
			or (catalogyear.yearstart >= '".$yearstart."' and catalogyear.yearend >= '".$yearend."' and catalogyear.yearstart <= '".$yearend."')
			) 
			or 
			(catalogyear.yearstart >= '".$yearstart."' and catalogyear.yearstart <= '".$yearend."' and catalogyear.yearend = 0)
			)
			";
            $fromtable .= ", catalogyear";
        }

        $where .= $sqlyear;

    }

    if (intval($series)) {

        $fromtable .= ", series";
        $where .= " and catalognew.series=series.series ";

    }


    if ($usershopcoinssubscribe) { //подписка пользователя
        $WhereParams['catalogshopcoinssubscribe'] = $usershopcoinssubscribe;
        
        $fromtable .= ", catalogshopcoinssubscribe ";

        $where .= " and catalogshopcoinssubscribe.user = '$usershopcoinssubscribe'
		and catalogshopcoinssubscribe.catalog = catalognew.catalog ";
        
    }  elseif ($usercatalogsubscribe) { //подписка пользователя
        $WhereParams['usercatalogsubscribe'] = $usercatalogsubscribe;
        
        $fromtable .= ", catalogsubscribe ";

        $where .= " and catalogsubscribe.user = '$usercatalogsubscribe'
		and catalogsubscribe.catalog = catalognew.catalog ";
    } elseif ($usermycatalog or $usermycatalogchange)  {
        //моя коллекция
   
        $fromtable .= ", catalognewmycatalog ";    
        
        if ($usermycatalog){
            $WhereParams['catalognewmycatalog_usermycatalog'] = $usermycatalog;
            
            $where .= " and	catalognewmycatalog.user = '$usermycatalog'
			and catalognewmycatalog.catalog = catalognew.catalog ";
        }

        if ($usermycatalogchange) {
            $WhereParams['catalognewmycatalog_usermycatalogchange'] = $usermycatalogchange;
            $where .= " and	catalognewmycatalog.user = '$usermycatalogchange'
			and catalognewmycatalog.catalog = catalognew.catalog 
			and	catalognewmycatalog.type=1";
        }
    }


$groupselect = ", if(LEFT(TRIM(catalognew.name),1) in('0','1','2','3','4','5','6','7','8','9'),CASE SUBSTRING_INDEX(trim(catalognew.name),' ',1) WHEN '1/2' THEN 1/2+0.5 WHEN '1/3' THEN 1/3+0.5 WHEN '1/4' THEN 1/4+0.5 WHEN '1/6' THEN 1/6+0.5 WHEN '1/8' THEN 1/8+0.5 WHEN '1/16' THEN 1/16+0.5 WHEN '3/4' THEN 3/4+0.5 WHEN '1/24' THEN 1/24+0.5 WHEN '5/10' THEN 5/10+0.5 WHEN '1/20' THEN 1/20+0.5 WHEN '1/5' THEN 1/5+0.5 WHEN '1/10' THEN 1/10+0.5 WHEN '1/32' THEN 1/32+0.5 WHEN '2/3' THEN 2/3+0.5 WHEN '1/24' THEN 1/24+0.5 WHEN '1/12' THEN 1/12+0.5 WHEN '1/26' THEN 1/26+0.5 WHEN '1/48' THEN 1/48+0.5 WHEN '1/13' THEN 1/13+0.5 WHEN '5/100' THEN 5/100+0.5 WHEN '1/100' THEN 1/100+0.5 WHEN '2/10' THEN 2/10+0.5 WHEN '1/25' THEN 1/25+0.5 WHEN '1/96' THEN 1/96+0.5 WHEN '1/40' THEN 1/40+0.5 WHEN '1/84' THEN 1/84+0.5 WHEN '0,05' THEN 0.05+0.5 WHEN '0,1' THEN 0.1+0.5 ELSE SUBSTRING_INDEX(trim(catalognew.name),' ',1)+0.5 END ,999999999) as param1, if(LEFT(TRIM(catalognew.name),1) in('0','1','2','3','4','5','6','7','8','9'),if(LENGTH(SUBSTRING_INDEX(TRIM(catalognew.name),' ',-1))<4,SUBSTRING_INDEX(TRIM(catalognew.name),' ',-1),if(LENGTH(SUBSTRING_INDEX(TRIM(catalognew.name),' ',-1))<=6,MID(SUBSTRING_INDEX(TRIM(catalognew.name),' ',-1),1,LENGTH(SUBSTRING_INDEX(TRIM(catalognew.name),' ',-1))-2),MID(SUBSTRING_INDEX(TRIM(catalognew.name),' ',-1),1,LENGTH(SUBSTRING_INDEX(TRIM(catalognew.name),' ',-1))-3))),CONCAT('я',ifnull(TRIM(catalognew.name),''))) as param2 ";


require($cfg['path'].'/controllers/filters/filters_catalog.ctl.php');

//счетчик
$sql = "Select count(*) from catalognew".($search?",metal,`group` ":"")." $fromtable $where ".($search?" and metal.metal=catalognew.metal and `group`.`group` = catalognew.`group`":"").";";
$sql = "select catalognew.*, group.name as gname, metal.name as mname ".($CounterSQL?",".$CounterSQL:"")." ".($group&&!$page&&$materialtype!=1&&$materialtype!=8?$groupselect:"")." 
from catalognew, `group`, metal $fromtable ".(($materialtype==1||$materialtype==8)&&$group?",catalogpositionname":"")." 
$where and catalognew.group=group.group  ".(($materialtype==1||$materialtype==8)&&$group?"and catalogpositionname.catalogpositionname=catalognew.catalogpositionname":"")."
and catalognew.metal = metal.metal";


$countpubs = $catalognew_class->countallByParams($WhereParams);

//end - потом не забыть подключить
$addhref = ($yearstart?"&yearstart=".$yearstart:"").
($yearend?"&yearend=".$yearend:"");

if($groupMain){
    $addhref .="&group=$groupMain";
} else {
    foreach ((array)$groups as $g){
        $addhref .="&groups[]=$g";
    }
}

foreach ((array)$years as $y){
    $addhref .="&years[]=$y";
}


$nominalMain = 0;
$nominalMainTitle = '';

if(count($nominal_data)==1&&$nominal_data[0]){
    $addhref .="&nominal=".$nominal_data[0];
    $nominalMain = $nominal_data[0];
    $data_filter['nominal_id'] = $nominalMain;
    $nominalMainTitle = $shopcoins_class->getNominal($nominal_data[0]);
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $nominalMainTitle,
    	'href' => $r_url.($groupHref?$groupHref:'').contentHelper::nominalUrl($nominalMainTitle,$nominalMain),
    	'base_href' =>$r_url.($groupHref?$groupHref:'').contentHelper::nominalUrl($nominalMainTitle,$nominalMain)
    );    
} else{    
    foreach ((array)$nominals as $th){
        $addhref .="&nominals[]=$th";
    }
}


if(count($years_p)==1&&$years_p[0]){    
    $data_filter['year'] = $years_p[0];
    $tpl['breadcrumbs'][] = array(
    	'text' => $years_p[0],
    	'href' => $r_url.($groupHref?$groupHref:'').'/y_ysp'.$years_p[0],
    	'base_href' =>$r_url.($groupHref?$groupHref:'').'/y_ysp'.$years_p[0]
    );
    $addhref .="&year=".$years_p[0];
} else {
    foreach ((array)$years_p as $y){
        $addhref .="&years_p[]=$y";
    }
}

$metalMain = 0;
$metalMainTitle = '';

if(count($metal_data)==1){
    $metalMain = $metal_data[0];
    $data_filter['metal_id'] = $metalMain;
    $metalMainTitle = $tpl['metalls'][$metalMain];
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $metalMainTitle,
    	'href' => $r_url.($groupHref?$groupHref:'').contentHelper::metalUrl($metalMainTitle,$metalMain),
    	'base_href' =>$r_url.($groupHref?$groupHref:'').contentHelper::metalUrl($metalMainTitle,$metalMain)
    );
    
    $addhref .="&metal=".$metalMain;
    
} else {
    foreach ((array)$metals as $m){
        $addhref .="&metals[]=".urlencode($m);       
    }
}


$themeMain = 0;
$themeMainTitle = '';

if(count($theme_data)==1){
    $themeMain = $theme_data[0]; 
    $data_filter['theme_id'] = $themeMain;   
    $themeMainTitle = $ThemeArray[$theme_data[0]];   
    $addhref .="&theme=$themeMain";
} else {
    foreach ((array)$themes as $th){
        $arraykeyword[] = $ThemeArray[$th];
        $addhref .="&themes[]=$th";
    }
}

if($tpl['user']['user_id']==352480){   
}

setcookie("chref", $cfg["site_dir"]."new/index.php?module=catalognew&materialtype=".$materialtype.$addhref.(($tpl['pagenum']>1)?'&pagenum='.$tpl['pagenum']:''), time() + 3600, "/");
/*
if ($pagenum>2*$numpages) $page_string .= "<a href='$script?pagenum=1&materialtype=$materialtype".($search?"&search=".urlencode($search):"").($searchname?"&searchname=".urlencode($searchname):"").($searchid?"&searchid=".$searchid:"").($group?"&group=$group":"").($theme?"&theme=".$theme:"").($yearstart?"&yearstart=".$yearstart:"").($yearend?"&yearend=".$yearend:"").($metal?"&metal=".$metal:"").($usershopcoinssubscribe?"&usershopcoinssubscribe=$usershopcoinssubscribe":"").($usercatalogsubscribe?"&usercatalogsubscribe=".$usercatalogsubscribe:"").($usermycatalog?"&usermycatalog=$usermycatalog":"").($usermycatalogchange?"&usermycatalogchange=$usermycatalogchange":"")."'>[в начало]</a> | ";
if ($frompage>$numpages) $page_string .= "<a href='$script?materialtype=$materialtype&pagenum=".($frompage-1).($search?"&search=".urlencode($search):"").($searchname?"&searchname=".urlencode($searchname):"").($searchid?"&searchid=".$searchid:"").($group?"&group=$group":"").($theme?"&theme=".$theme:"").($yearstart?"&yearstart=".$yearstart:"").($yearend?"&yearend=".$yearend:"").($metal?"&metal=".$metal:"").($usershopcoinssubscribe?"&usershopcoinssubscribe=$usershopcoinssubscribe":"").($usercatalogsubscribe?"&usercatalogsubscribe=".$usercatalogsubscribe:"").($usermycatalog?"&usermycatalog=$usermycatalog":"").($usermycatalogchange?"&usermycatalogchange=$usermycatalogchange":"")."'><<пред</a> | ";
for ($i=$frompage;$i<=$topage;$i++)
{
    if ($i==$pagenum) $page_string .= "<b>$i</b>";
    else $page_string .= "<a href='$script?materialtype=$materialtype&pagenum=$i".($search?"&search=".urlencode($search):"").($searchname?"&searchname=".urlencode($searchname):"").($searchid?"&searchid=".$searchid:"").($group?"&group=$group":"").($theme?"&theme=".$theme:"").($yearstart?"&yearstart=".$yearstart:"").($yearend?"&yearend=".$yearend:"").($metal?"&metal=".$metal:"").($usershopcoinssubscribe?"&usershopcoinssubscribe=$usershopcoinssubscribe":"").($usercatalogsubscribe?"&usercatalogsubscribe=".$usercatalogsubscribe:"").($usermycatalog?"&usermycatalog=$usermycatalog":"").($usermycatalogchange?"&usermycatalogchange=$usermycatalogchange":"")."'>$i</a>";
    if ($i<$topage) $page_string .= " | ";
}
if ($pages>$topage) $page_string .= " | <a href='$script?materialtype=$materialtype&pagenum=$i".($search?"&search=".urlencode($search):"").($searchname?"&searchname=".urlencode($searchname):"").($searchid?"&searchid=".$searchid:"").($group?"&group=$group":"").($theme?"&theme=".$theme:"").($yearstart?"&yearstart=".$yearstart:"").($yearend?"&yearend=".$yearend:"").($metal?"&metal=".$metal:"").($usershopcoinssubscribe?"&usershopcoinssubscribe=$usershopcoinssubscribe":"").($usercatalogsubscribe?"&usercatalogsubscribe=".$usercatalogsubscribe:"").($usermycatalog?"&usermycatalog=$usermycatalog":"").($usermycatalogchange?"&usermycatalogchange=$usermycatalogchange":"")."'>далее>></a>";
// vbhjckfd stop*/


$tpl['paginator'] = new Paginator(array(
        'url'        => $cfg["site_dir"]."new/index.php?module=catalognew&materialtype=".$materialtype.($addhref?("?".$addhref):""),
        'count'      => $countpubs,
        'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
        'page'       => $tpl['pagenum'],
        'border'     =>3));
        

$tpl['catalognew']['testaddcoins'] =0;

if ($tpl['user']['user_id']) {
    $userData = $user_class->getUserData();
    if ($userData["star"] > 10 || $clientdiscount){
        $tpl['catalognew']['testaddcoins'] =1;
    } 
}

if ($tpl['orderby']=="dateinsertdesc"){
	$OrderByArray[] = "s.dateinsert desc";
} elseif ($tpl['orderby']=="dateinsertasc"){
	$OrderByArray[] = " s.dateinsert asc";
} elseif ($tpl['orderby']=="yearasc"){
	$OrderByArray[] = "s.year asc";
	$OrderByArray[] ="s.dateinsert desc";
} elseif ($tpl['orderby']=="yeardesc"){
	$OrderByArray[] = "s.year desc";
	$OrderByArray[] = "s.dateinsert desc ";
} elseif($materialtype==3){
	$OrderByArray[] = "s.name";
} else{
    $OrderByArray[] = "s.dateinsert desc ";
}


if ($materialtype==3 and $group)
    $orderby = "order by s.name ";

if ($orderby=="dateinsertdesc")
    $orderby = "order by s.dateinsert desc";
elseif ($orderby=="dateinsertasc")
    $orderby = "order by s.dateinsert asc";


if (!$orderby)
    $orderby = "order by s.dateinsert desc";

include $_SERVER["DOCUMENT_ROOT"]."/keywords.php";

$textocenka = '';

if ($materialtype==1 ) {
    $tpl['catalognew']['_Title'] = "Каталог монет ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
} elseif ($materialtype==8) {
    $tpl['catalognew']['_Title'] = "Каталог дешевых монет ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
}  elseif ($materialtype==7)  {
    $tpl['catalognew']['_Title'] = "Каталог наборов монет ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
}  elseif ($materialtype==4)  {
    $tpl['catalognew']['_Title'] = "Каталог подарочных наборов монет ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
}  elseif ($materialtype==2)  {
    $tpl['catalognew']['_Title'] = "Каталог банкнот(бон) ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
}   else   {
    $tpl['catalognew']['_Title'] = "Каталог монет".($GroupName?" | ".$GroupName:"")." | Клуб Нумизмат";
}

$tpl['catalognew']['_Keywords'] = $_Keywords; //"Монеты, ".$Meta[0];
$tpl['catalognew']['_Description'] = $_DescriptionCatalog; //$Meta[1];


$arraytitle = explode("|", $title);
$tmptitle = $arraytitle[1]." | ".$arraytitle[2];

if (!$page){
    if (!$pagestart)
        $pagestart=0;
    else
        $pagestart = intval($pagestart);

    $onpage = 8;

    $limit = " limit ".($pagenum-1)*$onpage.",$onpage";

    if ($wheresearch)
        $where = $wheresearch;

    /*$sql_catalog = "select distinct catalognew.catalog
    from catalognew, `group` $fromtable
    $where and catalognew.group=group.group
    $orderby
    $limit;";*/

    $sql_catalog = "select catalognew.*, group.name as gname, metal.name as mname ".($CounterSQL?",".$CounterSQL:"")." ".($group&&!$page&&$materialtype!=1&&$materialtype!=8?$groupselect:"")." 
	from catalognew, `group`, metal $fromtable ".(($materialtype==1||$materialtype==8)&&$group?",catalogpositionname":"")." 
	$where and catalognew.group=group.group  ".(($materialtype==1||$materialtype==8)&&$group?"and catalogpositionname.catalogpositionname=catalognew.catalogpositionname":"")."
	and catalognew.metal = metal.metal 
	".($group&&!$page&&$materialtype!=1&&$materialtype!=8?" order by group.groupparent,catalognew.group, param2,param1,catalognew.yearstart,catalognew.dateinsert desc":(($materialtype==1||$materialtype==8)&&$group?"order by group.groupparent,catalognew.group, catalogpositionname.position,catalognew.yearstart,catalognew.dateinsert desc":$orderby))." 
	$limit;";

    //	group by catalognew.catalog


    //if ($cookiesuser == 16015)
    //echo "<br>For nommail@mail.ru: <br>".$sql_catalog;
    echo $sql_catalog."<br>";
    
    $CatalogArray = Array();
    
    $tpl['catalognew']['MyShowArray'] = Array();
    $tpl['catalognew']['CatalogArray'] = Array();
    
    /*$MyShowArray = array();
    $result_catalog = mysql_query($sql_catalog);
    while ($rows = mysql_fetch_array($result_catalog))
    {
        $MyShowArray[] = $rows;
        $CatalogArray[] = $rows["catalog"];
    }

    unset ($rows);*/

   $data = $catalognew_class->getItemsByParams($WhereParams,$tpl['pagenum'],$tpl['onpage'],$OrderByArray);	
   foreach ($data as $rows){
       $tpl['catalognew']['MyShowArray'][] = $rows;
       $tpl['catalognew']['CatalogArray'][] = $rows["catalog"];
   }

   $tpl['catalognew']['shopcoinssubbscribe'] = Array();
   $tpl['catalognew']['shopcoinssubbscribedate'] = Array();
   
   $tpl['catalognew']['catalognewmycatalog'] = Array();
   $tpl['catalognew']['detailsmycatalog'] = Array();
   $tpl['catalognew']['typemycatalog'] = Array();
        
    //выбираем на что мы хотим подписываться
    if ($tpl['catalognew']['user_id'] and sizeof($tpl['catalognew']['CatalogArray'])>0) {
        
        $result_shopcoinssubbscribe = $catalognew_class->getShopcoinssubbscribe($tpl['catalognew']['user_id'],$tpl['catalognew']['CatalogArray']);
       
        foreach ($result_shopcoinssubbscribe as $rows) {
            $tpl['catalognew']['shopcoinssubbscribe'][$rows["catalog"]] = ($rows["amount"]>1?$rows["amount"]:1);
            $tpl['catalognew']['shopcoinssubbscribedate'][$rows["catalog"]] = $rows["dateinsert"];
        }
       
        $catalognewmycatalog = Array();
        
        $result_shopcoinssubbscribe = $catalognew_class->getCatalognewmycatalog($tpl['catalognew']['user_id'],$tpl['catalognew']['CatalogArray']);
       
        foreach ($result_shopcoinssubbscribe as $rows) {
            $tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]] = $rows["catalognewmycatalog"];
            $tpl['catalognew']['detailsmycatalog'][$rows["catalog"]] = $rows["detailschange"];
            $tpl['catalognew']['typemycatalog'][$rows["catalog"]] = $rows["type"];
        }        
    }

    $tpl['catalognew']['catalogshopcoins'] = Array();

    if (sizeof($tpl['catalognew']['CatalogArray'])>0) {
        
        $result_shopcoins = $catalognew_class->getCatalogshopcoinsrelation($tpl['catalognew']['CatalogArray']);      

        foreach ($result_shopcoins as $rows_shopcoins){
           // var_dump($rows_shopcoins);
           // echo "<br>";
            $tpl['catalognew']['catalogshopcoins'][$rows_shopcoins["catalog"]] =  $rows_shopcoins["shopcoins"];
        }
    }

    if (sizeof($tpl['catalognew']['MyShowArray'])==0) {
        $tpl['catalognew']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
    } else {       

      
        foreach ($tpl['catalognew']['MyShowArray'] as $key=>$rows) {

            $tpl['catalognew']['MyShowArray'][$key]['metal'] = $rows['mname'];
            $tpl['catalognew']['MyShowArray'][$key]['shopcoins'] = $rows['catalog'];
            $tpl['catalognew']['MyShowArray'][$key]['year'] = $rows['yearstart'];
          // moneta-severnaya-koreya-1-von-alyuminii-2001_c67404_m1.html
          // moneta-severnaya-koreya-1-von-alyuminii-2001_c_pc_m1_pp1.html
            $tpl['catalognew']['MyShowArray'][$key] = array_merge($tpl['catalognew']['MyShowArray'][$key], contentHelper::getRegHref($tpl['catalognew']['MyShowArray'][$key],$materialtype));
            $tpl['catalognew']['MyShowArray'][$key]['show_in_shop'] = false;
            if (isset($tpl['catalognew']['catalogshopcoins'][$rows["catalog"]]))  {
                $tpl['catalognew']['MyShowArray'][$key]['show_in_shop'] = true; 
                $tpl['catalognew']['MyShowArray'][$key]['show_in_shop_id'] = $tpl['catalognew']['catalogshopcoins'][$rows["catalog"]];            
            }
            $shopcoinstheme = array();
            $strtheme = decbin($rows["theme"]);
            $strthemelen = strlen($strtheme);
            $chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
            for ($k=0; $k<$strthemelen; $k++)
            {
                if ($chars[$k]==1)
                    $shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
            }
            $tpl['catalognew']['MyShowArray'][$key]["theme"] = implode(',',$shopcoinstheme );
            if($rows["condition"]) $tpl['catalognew']['MyShowArray'][$key]["condition"] = $ConditionMintArray[$rows["condition"]];

            $text = '';
            if (trim($rows["details"])) {
                $text = substr($rows["details"], 0, 250);
                $text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
            }

            $tpl['catalognew']['MyShowArray'][$key]["details"] = str_replace("\n","<br>",$text);   

            
            //мои заявки
            $tpl['catalognew']['MyShowArray'][$key]["in_request"] = false;
            $tpl['catalognew']['MyShowArray'][$key]["in_collection"] = false;
            $tpl['catalognew']['MyShowArray'][$key]["can_delete_subscribe"] = false;
            $tpl['catalognew']['MyShowArray'][$key]["show_list"] = false;
            
            if ($tpl['catalognew']['shopcoinssubbscribe'][$rows["catalog"]]>=1){
                $tpl['catalognew']['MyShowArray'][$key]["in_request"] = true;
            
                if ($materialtype == 3) {    
                    $tpl['catalognew']['MyShowArray'][$key]["in_request"] = true;
                    $tpl['catalognew']['MyShowArray'][$key]["request_date"] = $tpl['catalognew']['shopcoinssubbscribedate'][$rows["catalog"]];
                    $tpl['catalognew']['MyShowArray'][$key]["shopcoins_subbscribe"] = $tpl['catalognew']['shopcoinssubbscribe'][$rows["catalog"]];
                }
            
                if ($usershopcoinssubscribe == $tpl['user']['user_id']) {            
                    $tpl['catalognew']['MyShowArray'][$key]["can_delete_subscribe"] = true;
                }
            }
            
            if ($rows['materialtype'] != 3) {
                if ($tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]] > 0) {
                    $tpl['catalognew']['MyShowArray'][$key]["in_collection"] = true;
                    
                    if ($usermycatalog == $tpl['user']['user_id'] or $usermycatalogchange == $tpl['user']['user_id']) {
                        $tpl['catalognew']['MyShowArray'][$key]["show_list"] = true;
                        $tpl['catalognew']['MyShowArray'][$key]["show_list_id"] = $tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]];
                    }
                }
            }       
        }       
    }
    
}

} catch (Exception $e){
    var_dump($e);
}
