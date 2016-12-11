<div class="bordered">
<h5> Вопрос недели </h5>
<p><b>Уважаемые коллеги!</b>
<br>Для нас очень важно Ваше мнение о проблемах нумизматического мира. Для этого мы ввели на сайте новый раздел "Вопрос недели". Отвечая на вопросы, Вы не только увеличиваете свой рейтинг, но и вносите большой вклад в выяснение общего мнения той или иной поблемы.
<br>Вы можете также предложить свой вопрос.
<br>С результатами голосования Вы можете ознакомиться ниже.</p>

<?

if($tpl['weekquestion']['error']){?>
	<div class="error"><?=$tpl['weekquestion']['error']?></div>
<?}
if ($weekquestion&&$tpl['rows']['sum']){?>
	<table width=98% cellpadding=0 cellspacing=0 border=0 align=center class="bordered">
	<tr><td class=txt valign=top>
		<b><?=$tpl['rows']['question']?></b><br>
		<b>Дата начала:</b> <?=$tpl['rows']['d1']?><br>
		<b>Дата конца:</b> <?=$tpl['rows']['d2']?>
		<?if ($tpl['rows']['answer1']!="") echo "<br>№1. <img src=".$cfg['site_dir']."images/weekquestion/vote0.gif alt='Условный цвет вопроса'> ".$tpl['rows']['answer1'].": ".$tpl['rows']['vote1'];
		if ($tpl['rows']['answer2']!="") echo "<br>№2. <img src=".$cfg['site_dir']."images/weekquestion/vote1.gif alt='Условный цвет вопроса'> ".$tpl['rows']['answer2'].": ".$tpl['rows']['vote2'];
		if ($tpl['rows']['answer3']!="") echo "<br>№3. <img src=".$cfg['site_dir']."images/weekquestion/vote2.gif alt='Условный цвет вопроса'> ".$tpl['rows']['answer3'].": ".$tpl['rows']['vote3'];
		if ($tpl['rows']['answer4']!="") echo "<br>№4. <img src=".$cfg['site_dir']."images/weekquestion/vote3.gif alt='Условный цвет вопроса'> ".$tpl['rows']['answer4'].": ".$tpl['rows']['vote4'];
		if ($tpl['rows']['answer5']!="") echo "<br>№5. <img src=".$cfg['site_dir']."images/weekquestion/vote4.gif alt='Условный цвет вопроса'> ".$tpl['rows']['answer5'].": ".$tpl['rows']['vote5'];
		?>
		<br><b>Число проголосовавших:</b> <?=$tpl['rows']['sum']?></td>
		<td><img src="<?=$cfg['site_dir']?>weekquestion/circle.php?vote1=<?=$tpl['rows']['vote1']?>&vote2=<?=$tpl['rows']['vote2']?>&vote3=<?=$tpl['rows']['vote3']?>&vote4=<?=$tpl['rows']['vote4']?>&vote5=<?=$tpl['rows']['vote5']?>" alt='<?=$tpl['rows']['question']?>'></td>
 
	</tr></table>
<?}?>

<div class=center><b>Завершенные опросы:</b></div>
<div class="right">
	<?=$tpl['paginator']->printPager();?>
</div>

<table width=98% border=0 cellpadding=2 cellspacing=1 class=txt>
			<tr bgcolor=#ffcc66>
			<td width=55% class=tboard><b>Тема</b></td>
			<td nowrap class=tboard><b>дата начала</b></td>
			<td nowrap class=tboard ><b>дата окончания</b></td>
			</tr>
<?

foreach ($tpl['data']  as $i=>$rows){?>
	
	<tr <?=((($i % 2)==1)?"":"bgcolor=#ebe4d4")?>><td  class=tboard><a href="<?=contentHelper::strtolower_ru($rows['question'])."_n".$rows["weekquestion"]."_p".$pagenum."_s1.html"?>"><?=$rows['question']?></a></td>
	<td class=tboard><?=$rows['d1']?></td>
	<td class=tboard><?=$rows['d2']?></td>
	</tr>
<?}?>
</table>
<div class="right">
	<?=$tpl['paginator']->printPager();?>
	</div>
</div>
