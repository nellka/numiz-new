<?
if ($materialtype==1 || $materialtype==12){
	if($rows_main['year'] == 1990 && $materialtype==12) $rows_main['year'] = '1990 ЛМД';
	if($rows_main['year'] == 1991 && $materialtype==12) $rows_main['year'] = '1991 ЛМД';
	if($rows_main['year'] == 1992 && $materialtype==12) $rows_main['year'] = '1991 ММД';
	$title = "Монет".($parent?"ы":"а")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
}
elseif ($materialtype==10 )
{
	$title = "Нотгельд".($parent?"ы":"")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
}
elseif ($materialtype==8)
{
	$title = "Дешев".($parent?"ые":"ая")." монет".($parent?"ы":"а")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
}
elseif ($materialtype==7)
{
	$title = "Набор".($parent?"ы":"")." монет ".$rows_main["gname"]." | ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
}
elseif ($materialtype==4)
{
	$title = "Подарочны".($parent?"е":"й")." набор".($parent?"ы":"")." монет ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
}
elseif ($materialtype==2)
{
	$title = "Банкнот".($parent?"ы":"а")."(бон".($parent?"ы":"а").") ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
}
elseif ($materialtype==5)
{
	$title = "Книг".($parent?"и":"а")." по нумизматике | ".$rows_main["name"];	
}
elseif ($materialtype==3)
{
	$title = " Аксессуары для коллекционеров ".$rows_main["gname"]." | ".$rows_main["name"];	
}
elseif ($materialtype==9)
{
	$title = "Лот".($parent?"ы":"")." монет ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
}
else
{
	$title = "Монет".($parent?"ы":"а")." ".$rows_main["gname"]." | ".$rows_main["name"];
}

$tpl['shopcoins']['_Keywords'] = $rows_main["gname"];
//$tpl['shopcoins']['_Description'] = $_DescriptionShopcoins; //$Meta[1];
$tpl['shopcoins']['_Title'] = $title;

?>