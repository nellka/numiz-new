<?
require_once $cfg['path'] . '/models/keywords.php';

$keywords_class = new keywords($cfg['db']);

$data = $keywords_class->getCoins();
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