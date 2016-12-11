<h1 class="yell_b">Информация о пользователе</h1>

<?if ($data){?>
<div class="web-form">
   Логин: <?=$data["userlogin"]?>
</div>
<div class="web-form">
   Звезд: <?=$data["star"]?>
</div>
	
<div class="web-form">
   Звезд: <?=($data["star"]<10&&$data["star"]>0?"<img src='".$cfg['site_dir']."images/star".$data["star"].".gif' alt='Рейитнг пользователя ".$data["star"]."'>":"<img src='".$cfg['site_dir']."images/star10.gif' alt='Рейтинг пользователя ".$data["star"]."'>")?> (<?=$data["star"]?>)
					
</div>
<div class="web-form">
   Еmail: <?=$data["email"]?>
</div>
<? /*	
	$xml .= "<changeuser>".($rows["changeuser"]?$rows["changeuser"]:"empty")."</changeuser>
	<collectionuser>".($rows["collectionuser"]+$rows["changeuser"]>0?($rows["collectionuser"]+$rows["changeuser"]):"empty")."</collectionuser>";
	*/
} else {?>

<div class="web-form">
    <span class="error center">Не выбран пользователь</span>  
</div>
	
<?}?>