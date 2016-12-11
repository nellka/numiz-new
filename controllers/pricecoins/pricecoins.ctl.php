<?
$catalog = (int) request('catalog');
$parent = (int) request('parent');
$group = request('group');
$pcondition = (int) request('pcondition');

$arraykeyword[] = array();

if (!$group) $group=1;


$tpl['pricecoins']['_Title'] = "Ценник на монеты России и СССР | Клуб Нумизмат";

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Главная',
	    	'href' => $cfg['site_dir'],
	    	'base_href' =>$cfg['site_dir']
);


if ($group){	
	$sql = "select * from `a_group` where `a_group`='$group' or groupparent='$group';";
	$result = $shopcoins_class->getDataSql($sql);
	foreach ($result as $rows) {
		
		$arraygroup[] = $rows['a_group'];
		
		if ($group==$rows['a_group']) {
			$GroupName = $rows["name"];
			if ($rows['groupparent']==123)
				$GroupName = "Юбилейные монеты - ".$GroupName;
			//$arraykeyword[] = $rows['name'];
			$temp = explode(" ",$rows['name']);
			foreach ($temp as $key=>$value) {				
				if (trim($value) && trim($value)!='-' && trim($value)!='до')
					$arraykeyword[] = $value;
			}
		}	
		$rows1 = $rows;
	}

	
	$tpl['pricecoins']['_Title'] = "Ценник на монеты - ".$GroupName." | Клуб Нумизмат";
}

$sql = "select * from `a_group`	where (groupparent=0 or groupparent=123) and a_group<>123;";

$tpl['leftmenu-result'] = $shopcoins_class->getDataSql($sql);

$arraykeyword[] = "россия";

if (sizeof($arraykeyword)){	
	$sql = "select *,match(`keywords`,`name`,`text`) against('".implode(" ",$arraykeyword)."' in boolean mode) as `coefficient` from shopcoinsbiblio where match(`keywords`,`name`,`text`) against('".implode(" ",$arraykeyword)."' in boolean mode) order by `coefficient` desc, shopcoinsbiblio asc limit 5;";
	//echo $sql;
	$tpl['pricecoins']['seo_left']  = $shopcoins_class->getDataSql($sql);
}
?>

<? if ((int)$group) {
	$tpl['breadcrumbs'][] = array(
	    	'text' => "Ценник на ".$GroupName,
	    	'href' => $cfg['site_dir'].'pricecoins/index.php?group='.$group,
	    	'base_href' =>''
	);
	$arraycheckuser = array();

	if ($tpl['user']['user_id']) {
		$sql = "select a_pricecoins from a_priceclick where dateinsert>".(time()-7*24*3600)." and `user`='".$tpl['user']['user_id']."';";
		$result = $shopcoins_class->getDataSql($sql);
		foreach ($result as $rows) {		
			$arraycheckuser[] = $rows['a_pricecoins'];
		}
	}

	if ($group>=123 && $group<=126) {	
		
		$sql = "select a_pricecoins.*,a_group.name as gname,a_condition.condition as acondition,a_metal.metal as ametal, a_name.name as aname from a_pricecoins, a_group, a_name,a_condition,a_metal 
			where a_pricecoins.a_group=a_group.a_group and a_pricecoins.a_name=a_name.a_name ".(sizeof($arraygroup)?"and a_pricecoins.a_group in(".implode(",",$arraygroup).")":"")." and a_pricecoins.`check`=1 
			and a_pricecoins.a_condition=a_condition.a_condition and a_pricecoins.a_metal=a_metal.a_metal  and a_pricecoins.`price`<>0 and a_pricecoins.a_name=a_name.a_name	order by a_pricecoins.year, a_name.position,a_pricecoins.details,a_metal.position,a_pricecoins.parent,a_condition.position;";
		$result = $shopcoins_class->getDataSql($sql);
			
	} elseif($group) {
		
		$sql = "select a_name.name as nname ,a_name.a_name as aname from a_pricecoins, a_group, a_name 
		where a_pricecoins.a_group=a_group.a_group and a_pricecoins.a_name=a_name.a_name ".(sizeof($arraygroup)?"and a_pricecoins.a_group in(".implode(",",$arraygroup).")":"")." and `check`=1 group by a_name.a_name order by a_name.position;";
		$result = $shopcoins_class->getDataSql($sql);
		//echo $sql;
		if ($result) {

			$arraytable = array();
			
			$arraytable[0][0] = 'Года чеканки';
			
			foreach ($result as $rows){
				$arraytable[0][$rows['aname']] = $rows['nname'];
			}
			
			$sql_main = "select a_pricecoins.*,a_group.name as gname from a_pricecoins, a_group, a_name 
			where a_pricecoins.a_group=a_group.a_group and a_pricecoins.a_name=a_name.a_name ".(sizeof($arraygroup)?"and a_pricecoins.a_group in(".implode(",",$arraygroup).")":"")." and `check`=1 order by a_group.position, a_name.position;";
			$result_main = $shopcoins_class->getDataSql($sql_main);

			foreach ($result_main as $rows_main) {			
				$arraytable[$rows_main['a_group']][0] = $rows_main['gname'];
				$arraytable[$rows_main['a_group']][$rows_main['a_name']] = $rows_main['price'];
				$arrayid[$rows_main['a_group']][$rows_main['a_name']] = $rows_main['a_pricecoins'];
			}	
		}
	}	
} else {
	$tpl['breadcrumbs'][] = array(
	    	'text' => "Периоды ценника на монеты - Описание ",
	    	'href' => $cfg['site_dir'].'pricecoins/index.php?group=details',
	    	'base_href' =>''
	);
} 

