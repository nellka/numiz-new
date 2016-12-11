<?
require_once $cfg['path'] . '/models/keyword_groups.php';

$keywords_class = new keyword_groups($cfg['db']);
$shopcoins_materialsRule = array(//'moneti'=>1,
                                   // 'meloch'=>8,
                                   // 'baraholka'=>11,
                                   //'nabori_monet'=>7,
                                   //'cvetnie_moneti'=>6,
                                   //  'podarochnye_nabory'=>4,
                                   //'loty_dlya_nachinayushchih'=>9,
                                  // 'knigi'=>5
                                  //'notgeldy'=>10,
                                 // 'moneti_sssr'=>12,

                      // 'banknoti'=>2,
                       //'aksessuary'=>3,
                      //'Новинки'=>100,
   						"Распродажа"=>200              
                       
                       );
                       $i = 0;
$row =    array()    ;            
foreach ($shopcoins_materialsRule as $materialtype){
	$row['materialtype'] = $materialtype;
	$data = $keywords_class->getGroups($materialtype);
	foreach ($data as $group){
		$row['group'] = $group;
		$text = $keywords_class->createText($row);
		echo $group['group']."<br>";
		echo $text[0]."<br>";
		echo $text[1]."<br><hr>";
		$res = $keywords_class->setMetaDetails($materialtype,$group['group'],$text[0],$text[1]);
		//die();
		//$i++;
		
		//if($i>100) break;
	}
	
	
}
die();
//$data = $keywords_class->getCoinsByID(1041088);

foreach ($data as $row){
	$details =  $keywords_class->getDetails($row['shopcoins']);
    $row['details'] = strip_tags(str_replace(array("<br>","\n"),"",trim($details["details"])));
	$text = $keywords_class->createText($row);
	$correct_links = contentHelper::getRegHref($row);
    	
	echo "<a href=\"".$cfg['site_dir']."shopcoins/".$correct_links["rehref"]."\" target=_blank>".$row['name']."</a> - ".$cfg['site_dir']."shopcoins/".$correct_links["rehref"]."<br>";
	echo $text[0]."<br>";
	echo $text[1]."<br><hr>";
	$res = $keywords_class->setMetaDetails($row['shopcoins'],$text[0],$text[1]);	
}
unset($keywords_class);

die('end');
?>