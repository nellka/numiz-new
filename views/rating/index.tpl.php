<div class="main_context bordered rating table">
<h1>Рейтинг сайтов</h1>

<span style="font-size:12px; font-family:Arial, Helvetica, sans-serif;  font-weight:normal; margin-left:10px"><strong>Уважаемые посетители!</strong>
<br>Вашему вниманию предлагаем рейтинг сайтов, которые связанны с тематикой портала. Если у Вас есть своя страница в интернете в данном направлении, Вы также можете добавить ее в наш рейтинг
</span>
<p class=txt><b>Для работы с рейтингом рекомендуем воспользоваться системой поиска:</b></p>
<?php

echo "<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=#ecb34e>
		<tr><td colspan=3 bgcolor=black height=1 width=100%></td></tr>
		<tr><td class=formtxt align=center>Слово:</td><td class=formtxt>Искать в:</td><td>&nbsp;</td></tr>
		<tr><form action=\"".$cfg['site_dir']."rating\" method=post class=formtxt>
		<td align=center><input type=text name=s size=20  value='$s'></td>
		<td class=formtxt><select name=search_type>
		<option value=1 ".  selected(1, $search_type).">названии</option>
		<option value=2 ".  selected(2, $search_type).">ключевом слове</option>
		<option value=3 ".  selected(3, $search_type).">описании</option>
		<option value=4 ".  selected(4, $search_type).">во всех</option>
		</select></td>
		<td><input type=submit name=submit value='Искать сайт' class=formtxt></td></form></tr>
		<tr><td colspan=3 width=100%>&nbsp;</td></tr>
		<tr><td colspan=3 bgcolor=black height=1 width=615></td></tr>
		</table>";
echo "<div class=table style='height: 35px;width:100%'><p class='tboard right'><a href=\"".$cfg['site_dir']."rating/registration.php\">Добавить сайт</a></p>";

echo "</div><ul class='sub-top-menu'>";
foreach($tpl['groups'] as $rows){?>
	
		<li class="<?=($group==$rows['group'])?"active":""?>">		
		<? if ($group==$rows['group']) {?>
			<?=$rows['name']?>
		<?} else {?>
			<a href="<?=$cfg['site_dir']?>rating/?group=<?=$rows['group']?>"><?=$rows['name']?></a>
		<?}?>
		</li>
<?}
echo "</ul>";


//конец рисования таблицы групп
$ratinguser = intval($ratinguser);

//отображаем Top10 рейтинг
if ($tpl['ratings']){
	$i = 1;?>
	<form action="<?=$cfg['site_dir']?>rating/dinamics.php" method=post>
	<table width=98% border=0 cellspacing=0 cellpadding=3 align=center>
	<tr bgcolor=#ffcc66>
		<td class=tboard><b>№</b></td>
		<td width=60% class=tboard><b>Название/ описание</b></td>
		<td class=tboard><b>посетители</b></td>
		<td class=tboard><b>Визиты</b></td>
		<td class=tboard><input type=image name=submit src="<?=$cfg['site_dir']?>/rating/images/graph.gif" alt="Построить график по отмеченным сайтам"></td>
	</tr>
	<?foreach ($tpl['ratings'] as $rows){?>			
		<tr valign=top <?=((($i % 2)==1)?"":"bgcolor=#ebe4d4")?> class=tboard>			
		<td class=tboard><?=$i?> <a href="<?=$cfg['site_dir']?>rating/details.php?ratinguser=<?=$rows["ratinguser"]?>">
		<img src="<?=$cfg['site_dir']?>rating/images/static.gif" alt="Посмотреть статистику"></a></td>
		<td class=tboard><noindex><a href="<?=$rows["url"]?>" rel="nofollow" target=_blank><?=$rows["name"]?></a></noindex><br><?=$rows["description"]?></td>
		<td class=tboard><?=(int)$rows["host"]?></td>
		<td class=tboard><?=(int)$rows["hit"]?></td>
		<td class=tboard><input type=checkbox name=ratinguser<?=$rows["ratinguser"]?>></td>
		</tr>
		<?$i++;
	}?>
	</table></form><br>
	<div class="right">
	<?=$tpl['paginator']->printPager();?>
	</div>

<? } else {
//конец отображения Top10 рейтинга
	echo "<br><p class=txt><b><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></b><br><br>";
}	
?>

</div>