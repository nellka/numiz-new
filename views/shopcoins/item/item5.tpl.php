<? 
var_dump($rows['namecoins'],$rows["name"]);
if($rows["materialtype"]==3){?>
<a name=coin<?=$rows["shopcoins"]?> title='<?=$rows["name"]?>'></a><strong><?=$rows['namecoins']?></strong>
<a href='<?=$rows["rehref"]?>' title='<?=$rows['namecoins']?>'>
	<img src='<?=$cfg['site_dir']?>images/<?=$rows["image"]?>' alt='<?=$rows['namecoins']?>'>
</a>
<? } elseif ($rows["materialtype"]==5){?>
<a name=coin<?=$rows["shopcoins"]?> title='<?=$rows["name"]?>'></a><strong><?=$rows["name"]?></strong>
<a href='<?=$rows["rehref"]?>' title='Подробнее о книге <?=$rows["name"]?>'>
	<img src='<?=$cfg['site_dir']?>images/<?=$rows["image"]?>' alt='<?=$rows["name"]?>' >
</a>
<?}			
				
if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder)){
		echo "
		<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
		<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].",".$rows["materialtype"].")'>
		<div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$cfg['site_dir']."images/corz7.gif border=0 alt='Уже есть в вашей корзине'></div></a>";
} else {
	if ($tpl['user']['can_see'])
		echo "<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='0'> 
		<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")' title='Положить в корзину  ".$rows["name"]."'>
		<div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину'></div></a>";
		if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$cfg['site_dir']."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." руб.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$cfg['site_dir']."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
}

if (strlen($rows["details"])>250 or $rows["image_big"]) {
	echo "&nbsp;<a href='".$rows['rehref']." title=\"Подробнее о ".($rows["materialtype"]==5?'книге ':"").$rows['name']."\"><img src=".$cfg['site_dir']."images/corz3.gif border=0 alt='Подробнее о ".($rows["materialtype"]==5?'книге ':"").$rows['name']."'></a>";
}

echo $textoneclick;	
echo "<br>Количество:  <strong>".( !$rows["amount"]?1:$rows["amount"])."</strong>";

echo "<br>Группа:  <a href=$script?group=".$rows['group']."&materialtype=".$rows['materialtype']." title='Посмотреть все ".$rows["gname"]."'>
<strong><font color=blue>".$rows["gname"]."</font></strong></a>
		<br>Название: <strong>".$rows["name"]."</strong>
		".($rows["number"]?"<br>Номер:<strong> ".$rows["number"]."</strong>":"")."
		".($rows["accessoryProducer"]?"<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
		".($rows["accessoryColors"]?"<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>":"")."
		".($rows["accessorySize"]?"<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>":"")."
		
		<br>".($rows["oldprice"]>0?"Старая цена: <strong><s>".round($rows["oldprice"],2)." руб.</s></strong>
		<br>Новая цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>":
		"Цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>
		".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >Цена<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." руб.</font></strong></a>":""));

if ($rows['price1'] && $rows['amount1']) {
				
$tmpbody1 = "Оптовая цена: ".$rows['amount1']."<br>";
$tmpbody2 = "Цена: ".$rows['price1'];

if ($rows['price2'] && $rows['amount2']) {
	$tmpbody1 .= "<td class=tboard>".$rows['amount2'];
	$tmpbody2 .= "<td class=tboard>".$rows['price2'];
}
if ($rows['price3'] && $rows['amount3']) {

	$tmpbody1 .= "<td class=tboard>".$rows['amount3'];
	$tmpbody2 .= "<td class=tboard>".$rows['price3'];
}
if ($rows['price4'] && $rows['amount4']) {

	$tmpbody1 .= "<td class=tboard>".$rows['amount4'];
	$tmpbody2 .= "<td class=tboard>".$rows['price4'];
}
if ($rows['price5'] && $rows['amount5']) {

	$tmpbody1 .= "<td class=tboard>".$rows['amount5'];
	$tmpbody2 .= "<td class=tboard>".$rows['price5'];
}
echo $tmpbody1."<br>".$tmpbody2."<br>"; 
}			

if (trim($rows["details"])&&$rows["materialtype"]==3){
	$text = substr($rows["details"], 0, 250);
	$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
	$text = str_replace($rows['name'],"<strong>".$rows['name']."</strong>",$text);
	$text = str_replace($rows['gname'],"<strong>".$rows['gname']."</strong>",$text);
	$text = str_replace(" монет ","<strong> монет </strong>",$text);
	$text = str_replace(" монета ","<strong> монета </strong>",$text);
	$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
	$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
	echo "<br>Описание: ".str_replace("\n","<br>",$text);
}
if (trim($rows["details"])&&$rows["materialtype"]==5){
	$text = substr($rows["details"], 0, 250);
	$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
	$text = str_replace($rows['name'],"<strong>".$rows['name']."</strong>",$text);
	$text = str_replace($rows['gname'],"<strong>".$rows['gname']."</strong>",$text);
	$text = str_replace(" монет ","<strong> монет </strong>",$text);
	$text = str_replace(" монета ","<strong> монета </strong>",$text);
	$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
	$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
	$text = str_replace(" монет.","<strong> монет.</strong>",$text);
	$text = str_replace(" монета.","<strong> монета.</strong>",$text);
	$text = str_replace(" монеты.","<strong> монеты.</strong>",$text);
	$text = str_replace(" монетам.","<strong> монетам.</strong>",$text);
	echo "<br>Описание: ".str_replace("\n","<br>",$text);
} 