<?
include(START_PATH."/config.php");

$arraytypedetector = array (
1=>"Начальный",
2=>"Средний",
3=>"(Полу-) профессиональный"
);
?>
<div class="center">
	<a href='<?=$cfg['site_dir']?>catalognew/<?=$rows['rehref']?>' title='Каталог металлоискателей - металлодетектор <?=$rows["gname"]?> <?=$rows["name"]?>' class="borderimage primage">
		<?=contentHelper::showImage('/imagesd/'.$rows["image"],"Каталог металлоискателей - металлодетектор ".$rows["gname"]." ".$rows["name"],array('alt'=>"Каталог металлоискателей - металлодетектор  ".$rows["gname"]." ".$rows["name"],'folder'=>'catalognew'))?>			
	</a>
	
	</div>		
	
	<div class="coinname">    
	  <a href='<?=$rows['rehref']?>' title='Металлодетектор <?=$rows["gname"]?> <?=$rows["name"]?>'><h2><?=$rows['namecoins']?></h2></a>
	</div>
	Группа:<a class="group_href" href="<?=$cfg['site_dir']?>catalognew/?group=<?=$rows['group']?>&materialtype=<?=$rows['materialtype']?>" title='Посмотреть все металлоискатели <?=$rows["gname"]?>' alt='Посмотреть все металлоискатели <?=$rows["gname"]?>'>
	<?=$rows["gname"]?></a>	<br>	
	Тип:<b><?=$arraytypedetector[$rows["type"]]?></b><br>
			<?/*
echo ($rows["amountfrequencies"]?"<br>Kол-во частот:<strong> ".$rows["amountfrequencies"]."</strong>":"")."
			".($rows["sensitivity"]?"<br>Чувствительность:<strong> ".$rows["sensitivity"]."</strong>":"")."
			".($rows["deselectionground"]?"<br>Oтстройка от грунта:<strong> ".$rows["deselectionground"]."</strong>":"")."
			".($rows["selectivity"]?"<br>Дискриминация:<strong> ".$rows["selectivity"]."</strong>":"")."
			".($rows["frequency"]?"<br>Частота:<strong> ".$rows["frequency"]."</strong>":"")."
			".($rows["technology"]?"<br>Технология:<strong> ".$rows["technology"]."</strong>":"")."
			".($rows["electrichindrances"]?"<br>Отстройка от электрических помех:<strong> Есть</strong>":"")."
			".($rows["modepinpoint"]?"<br>Pежим точного обнаружения цели:<strong> Есть</strong>":"")."
			".($rows["polyphony"]?"<br>Полифония:<strong> Есть</strong>":"");?>


<?*/
//echo "</div>";
if ($rows["details"]){	
	echo "<br><b>Описание:</b> ".$rows["details"];
}
?>