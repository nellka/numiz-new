<div class="main_context bordered rating table">
<h1>Рейтинг сайтов</h1>

<?
echo "<ul class=sub-top-menu>";
foreach($tpl['groups'] as $rows){?>	
		<li><a href="<?=$cfg['site_dir']?>rating/?group=<?=$rows['group']?>"><?=$rows['name']?></a></li>
<?}
echo "</ul>";

if (!count($users) ){
	echo "<br><center><b>Не отмечено ниодного пункта для построения графиков!!!</b></center><br>";
	
} else {?>
	<div class="center"><img src="<?=$cfg['site_dir']?>rating/dinamicsgraph.php?gr=1<?=$user_string?>"></center>
	<?
	$i=1;
	foreach ($tpl['result'] as $rows){
		switch ($rows["ratinguser"])
		{
		case $users[0]: echo "<p align=left>$i. <img src='".$cfg['site_dir']."rating/images/color1.gif'> - "; break;
		case $users[1]: echo "<p align=left>$i. <img src='".$cfg['site_dir']."rating/images/color2.gif'> - "; break;
		case $users[2]: echo "<p align=left>$i. <img src='".$cfg['site_dir']."rating/images/color3.gif'> - "; break;
		case $users[3]: echo "<p align=left>$i. <img src='".$cfg['site_dir']."rating/images/color4.gif'> - "; break;
		}
		echo "<noindex><a href=".$rows["url"]." target=_blank rel='nofollow'>".$rows["name"]."</a></noindex>";
		echo "<br>".$rows["description"];
		$i++;
	}
	
	echo "<br><br>";
}

?>