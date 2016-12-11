<div class="main_context bordered rating table">
<h1>Рейтинг сайтов</h1>
<?
echo "<ul class=sub-top-menu>";
foreach($tpl['groups'] as $rows){?>	
		<li><a href="<?=$cfg['site_dir']?>rating/?group=<?=$rows['group']?>"><?=$rows['name']?></a></li>
<?}
echo "</ul>";
if($tpl['rating']['error']){
	echo "<div class=error>Рейтинг отсутствует</div>";
} else {
	echo "<br><p class=txt><b>Категория: <a href=\"".$cfg['site_dir']."rating?group=".$tpl['data']['group']."\">".$tpl['data']['gname']."</a></b>";
	echo "<p class=txt><b>Название: </b><noindex><a href=".$tpl['data']['url']." target=_blank  rel='nofollow'>".$tpl['data']['name']."</a></noindex>";
	echo "<p class=txt><b>Описание: </b>".$tpl['data']['description'];
	
	echo "<br><br><table width=460 border=0 cellspacing=0 cellpadding=3>";
	echo "<tr class=tboard bgcolor=#ffcc66><td class=tboard><b>Дата:</b></td><td class=tboard><b>Посетители</b></td><td class=tboard><b>Визиты</b></td><td width=300 class=tboard><b>График</b></td></tr>";
	
	
	foreach ($tpl['stat'] as $rows){
		echo "<tr>";
		echo "<td class=tboard>".$rows["n"]."</td>";
		echo "<td align=center class=tboard>".$rows["host"]."</td>";
		echo "<td align=center class=tboard>".$rows["hit"]."</td>";//далее рисуем таблицу отображения
		echo "<td>
		<table width=250 border=0 cellpadding=0 cellspacing=0>
		<tr>
		<td width=".$rows["size1"]." bgcolor=#849EAD height=10></td>
		<td width=".$rows["size2"]." bgcolor=#CED7DE height=10></td>
		<td width=".$rows["size3"]." height=10></td>
		</tr>
		</table>
		</td>";
		echo "</tr>";
	}
	echo "</table><br>";
}
echo "</div>";
?>

