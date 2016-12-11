<div class="menu-heading"  id='hidden-shopcoins-menu'>
	<a style="color:#ffffff;text-decoration:none;" href="#s" >
		<span style="padding-left:17px;" id='hidden-shopcoins-span'>Периоды ценника на монеты</span>
	</a>

<ul id='left_menu_shop' class="menu-sidebar_static">
   <li><a href='<?=$cfg['site_dir']?>pricecoins/index.php?group=details' title='Описание сервиса динамического ценника на монеты' class="topmenu <?=($group=='details')?'active':''?>">[Описание]</a>   </li>
   
   <?
   foreach ($tpl['leftmenu-result'] as $rows){
	if ($rows['groupparent']==0){?>
		<li><a href="<?=$cfg['site_dir']?>pricecoins/index.php?group=<?=$rows["a_group"]?>" title='Ценник на <?=$rows["name"]?>' class="<?=($group==$rows["a_group"])?'active':''?>"><?=$rows["name"]?></a></li>		
	<?} else {?>
		<li><a href="<?=$cfg['site_dir']?>pricecoins/index.php?group=<?=$rows["a_group"]?>" title='Ценник на Юбилейные монеты - <?=$rows["name"]?>' class="<?=($group==$rows["a_group"])?'active':''?>">Юбилейные монеты - <?=str_replace('"', '', $rows["name"])?></a></li>		
	<?}
	// vbhjckfd stop
}
?>
</ul> 
</div>
<?  
echo "<p class=table>&nbsp;</p>";
    foreach ($tpl['pricecoins']['seo_left'] as $rows ) {
		$text = mb_substr(trim(strip_tags($rows['text'])),0,600,'utf-8');
		$strpos = mb_strpos(strrev($text),".",'utf-8');
		echo "<p class=bordered><b> ".$rows['name']."</b><br>".mb_substr($text,0,600-($strpos<200?$strpos:0),'utf-8')."</p>";


	}?>

