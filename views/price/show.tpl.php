<div class="clearfix showitem" >  
<?
if($tpl['show']['error']['no_coins']){?>
    <div class="error">Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</div>
<?} else {
    include($cfg['path'].'/views/price/item/item.tpl.php');   
}?>
</div>

<?
/*if ($tpl['price']['metals']) {	?>	
<br>
		<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr><td style="background:#ffcc66;" class="tboard" colspan=<?=count($tpl['price']['metals'])?>><b><?=$rows_main['gname']?>:</b> </tr>
		<tr bgcolor="#ebe4d4">
		
		<?foreach ($tpl['price']['metals'] as $key=>$value) {?>
		
			<td class=tboard><b><?=$value?></b>
		<?}?>
		</tr>
		
		<tr bgcolor="#fff8e8">		
		<?
		foreach ($tpl['price']['metals'] as $key=>$value) {
			$urlParams['metal'] = array($key=>$value);?>		
			<td class="tboard" nowrap style="padding:5px" valign="top"><?=implode("<br>",$tpl['price']['nominals'][$key]['nominals'])?></td>

		<?}?>
		</tr>
		</table>
	<?
}?>

<div class="clearfix">
<br><table border=0 cellpadding=3 cellspacing=1>
	
<tr bgcolor=#ffcc66 class=tboard><td class="tboard"><strong>Года:</strong></td>



<?	foreach ($tpl['price']['years'] as $key=>$value) {?>
		<td align=center class="tboard"><strong><?=$key?></strong>		
	<?}?>
	</tr>	
	<tr bgcolor=#ebe4d4 class=tboard><td class="tboard"><strong>Буквы:</strong>	
	<?	foreach ($tpl['price']['years'] as $key=>$value) {?>		
			<td valign=top align=right class="tboard">
			<?foreach ($value as $value2) {			
				echo "<a href=\"".$value2['url']."\">".$value2['name']."</a><br>";
			}?>	
			</td>	
		<?}?>
	</tr>
	</table>
</div>	
<br>*/?>
<a href="<?=$tpl['back_url']?>"><<< <?=$rows_main['gname']." - ".$rows_main['aname']." - ".$rows_main['ametal']?></a>&nbsp;&nbsp;&nbsp;
	
<?if (!$tpl['user']['user_id']) {?>
		<h4>Стоимость на монету <?=$rows_main["gname"]." ".$rows_main["aname"]." - ".$rows_main["year"]?> доступна только после авторизации <a href="<?=$cfg['site_dir']?>user/registration.php">( Регистрация )</a></h4>
<?}	?>

<div class="wraper clearfix info_coins">
<?
if (!$pcondition) {		
		
	echo "<table><tr align=center><td class=tboard><strong>Состояние</strong>";
		
		foreach ($tpl['arraycondition'] as $key => $value) {
		
			echo "<td class=tboard><b>".$value."</b></td>";
		}
		echo "</tr>
		<tr><td class=tboard>Цена на монету";
		
		foreach ($tpl['arraycondition'] as $key => $value) {
		
			echo "<td class=tboard>".($tpl['arrayamount'][$value]?"<a href=".str_replace("_pcn0.html","_pcn".$key.".html",$tpl['rehref'])."> (".$tpl['arrayamount'][$value]." ".($tpl['user']['user_id']?"пр.":"проходов").") ".($tpl['user']['user_id']?round($tpl['arrayprice'][$value]/$tpl['arrayamount'][$value])." руб.</a>":"</a>"):"-");
		}
		echo "</tr></table>";
		?>
</div>
	<?} else {		
			if ($amount>1 && $tpl['user']['user_id']) {				
				echo "<br><b>Максимальная цена: <font color=blue>".$pricemax." руб.</font></b>";
				echo "<br><b>Минимальная цена: <font color=blue>".$pricemin." руб.</font></b>";
				echo "<br><b>Средняя цена: <font color=blue>".round($price/$amount)." руб.</font></b>";				
			}
			
			if ($tpl['user']['user_id']){
				echo "<br><br><span style=\"margin-left:20px\"><img src=\"".$cfg['site_dir']."price/coinspricegraph.php?pcondition=$pcondition&parent=$parent\" ></span><br></tr>";
			}?>
			
			<br style="clear: both;">
			<div class="product-grid search-div">
			<?
			    $i=1;
			    foreach ($tpl['price']['MyShowArray'] as $key=>$rows){		
			    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
			    		<div class='blockshop-full'>";
			    		include('items/item-auction.tpl.php');
			    		echo "</div>
			    		</div>";
			    	$i++;	
			    }?>
			</div>
			
	<?}?>
<br><br><br style="clear: both">
<div class="center"><a href="<?=$cfg['site_dir']?>gde-prodat-monety"><img src=http://www.numizmatik.ru/images/468-200.gif>
<br>
Клуб Нумизмат бесплатно оценивает и покупает монеты <?=($rows_main["gname"]?$rows_main["gname"]:($GroupName?$GroupName:" >>>"))?></a></div>
