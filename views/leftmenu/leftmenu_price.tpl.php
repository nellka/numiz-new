<? 
    include(DIR_TEMPLATE.'leftmenu/filters/price/filter_country.tpl.php');
    echo "<p class=table>&nbsp;</p>";
    foreach ($tpl['price']['seo_left'] as $rows ) {
		$text = mb_substr(trim(strip_tags($rows['text'])),0,600,'utf-8');
		$strpos = mb_strpos(strrev($text),".",'utf-8');
		echo "<p class=bordered><b> ".$rows['name']."</b><br>".mb_substr($text,0,600-($strpos<200?$strpos:0),'utf-8')."</p>";


	}
?>   