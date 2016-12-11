<div class="bordered price-coins">
<?if(!$tpl['user']['user_id']){?>
	<font color="red">Вы не авторизованы!!! Для возможности активного участия в данном серсисе необходима авторизация!</font>
<?}
 if ((int)$group) {?>
 	<h5>Ценник на <?=$GroupName?></h5>
 	<div class="center"><a class="topmenu " href="http://www.numizmatik.ru/pricecoins/index.php?group=details" title="Описание сервиса динамического ценника на монеты">[Описание]</a></div>
 	<div class="table">
 	<ul class="pricecoins">
 	<? 	
   	foreach ($tpl['leftmenu-result'] as $rows){
		if ($rows['groupparent']==0){?>
			<li><a href="<?=$cfg['site_dir']?>pricecoins/index.php?group=<?=$rows["a_group"]?>" title='Ценник на <?=$rows["name"]?>' class="<?=($group==$rows["a_group"])?'active':''?>"><?=$rows["name"]?></a></li>		
		<?} else {?>
			<li><a href="<?=$cfg['site_dir']?>pricecoins/index.php?group=<?=$rows["a_group"]?>" title='Ценник на Юбилейные монеты - <?=$rows["name"]?>' class="<?=($group==$rows["a_group"])?'active':''?>">Юбилейные монеты - <?=str_replace('"', '', $rows["name"])?></a></li>		
		<?}
	}
?>
	</ul>
	</div>
	<div class=bordered>&nbsp;&nbsp;&nbsp;<a href="<?=$cfg['site_dir']?>gde-prodat-monety">Клуб Нумизмат бесплатно оценивает и занимается скупкой монет <?=$GroupName?> >>></a></div><br><br>
	
	<?
	
	
	if ($group>=123 && $group<=126) {?>
	  <table border=0 cellpadding=3 cellspacing=1  bgcolor=#333333>
		<tr class=tboard height=30 bgcolor="#ffffff">
			<td class=tboard bgcolor="#FFCC33"><b>Год</b></td>
			<td class=tboard bgcolor="#FFCC33"><b>Номинал</b></td>
			<td class=tboard bgcolor="#FFCC33"><b>Наименование</b></td>
			<td class=tboard bgcolor="#FFCC33"><b>Металл</b></td>
			<td class=tboard bgcolor="#FFCC33"><b>VF</b></td>
			<td class=tboard bgcolor="#FFCC33"><b>UNC</b></td>
			<td class=tboard bgcolor="#FFCC33"><b>Proof</b></td>
			<td class=tboard bgcolor="#FFCC33"><b>Новодел</b></td>
	</tr>
	<?if ($result) {
		
			$year = 0;
			$name = 0;
			$details = '';
			$metal = 0;
			$parent = 0;
			$numcondition = 1;
			
			foreach ($result AS $rows){			
				if ($rows['year'] != $year || $rows['a_name'] != $name || trim($rows['details']) != $details || $rows['a_metal'] != $metal || $rows['parent'] != $parent) {				
					if ($year) {						
						for ($i=$numcondition;$i<=4;$i++) {						
							ECHO "<td class=tboard align=center > - ";
						}
						ECHO "</tr>";
						$numcondition = 1;
					}
					
					echo "<tr height=30 bgcolor=\"#ffffff\"><td class=tboard align=right ><strong>".$rows['year']."</strong><td class=tboard align=right >".$rows['aname']."<td align=center >".$rows['details']."<td class=tboard align=center >".$rows['ametal'];
					for ($i=$numcondition;$i<$rows['a_condition'];$i++) {
					
						echo "<td class=tboard align=center > - ";
						$numcondition++;
					}
					
					if ($rows['a_pricecoins'] && $rows['price']) {
							
						if ($rows['price']>0 && $cookiesuser && !in_array($rows['a_pricecoins'],$arraycheckuser)){?>
							<td align=right >
							<a name=price<?=$rows['a_pricecoins']?>></a>
							<div id=pricedelta<?=$rows['a_pricecoins']?>><?=($rows['price']<0?"<font color=red> R </font>":$rows['price'])?><br>
								<a href="#price<?=$rows['a_pricecoins']?>" onclick="PriceDelta('<?=$rows['a_pricecoins']?>','-1');" title='Уменьшить цену'> &ndash; </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href=#price<?=$rows['a_pricecoins']?> onclick="PriceDelta('<?=$rows['a_pricecoins']?>','1');" title='Увеличить цену'> + </a></div>
							</td>
						<?} else {?>
							<td align=right ><?=($rows['price']<0?"<font color=red> R </font>":$rows['price'])?></td>
						<?}
					}	else echo "<td align=center> - </td>";					
				} else {				
					if ($rows['a_pricecoins'] && $rows['price']) {								
						if ($rows['price']>0 && $tpl['user']['user_id'] && !in_array($rows['a_pricecoins'],$arraycheckuser)){?>
							<td align=right >
								<a name=price<?=$rows['a_pricecoins']?>></a>
								<div id=pricedelta<?=$rows['a_pricecoins']?>><?=($rows['price']<0?"<font color=red> R </font>":$rows['price'])?><br>
								<a href="#price<?=$rows['a_pricecoins']?>" onclick="PriceDelta('<?=$rows['a_pricecoins']?>','-1');" title='Уменьшить цену'> &ndash; </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#price<?=$rows['a_pricecoins']?>" onclick="PriceDelta('<?=$rows['a_pricecoins']?>','1');" title='Увеличить цену'> + </a></div></td>
						<?} else echo"<td align=right >".($rows['price']<0?"<font color=red> R </font>":$rows['price'])."</td>";
					} else echo "<td align=center> - </td>";
				}				
				$year = $rows['year'];
				$name = $rows['a_name'];
				$details = trim($rows['details']);
				$metal = $rows['a_metal'];
				$parent = $rows['parent'];
				$numcondition++;
				
			}
			
			if ($year) {
						
				for ($i=$numcondition;$i<=4;$i++) {
				
					echo "<td class=tboard align=center > - ";
				}
				echo "</tr>";
			}?>
			</table>
		<?} else echo "<p class=error>Извините по Вашему запросу нет данных.</p>";
} elseif($group) {		
		
		if ($arraytable>0) {?>
		
			<table border=0 cellpadding=3 cellspacing=1  bgcolor=#333333>		
			
			<?foreach ($arraytable as $key=>$value) {?>
			    <tr class=tboard height=30 bgcolor="#ffffff">
				<?if ($key==0) {				
					foreach ($value as $key2=>$value2) {
						echo "<td bgcolor=\"#FFCC33\"><b>".$value2."</b></td>";
					}
				} else {		
						
					foreach ($arraytable[0] as $key2=>$value2) {					
						if ($value[$key2]) {							
							if ($arrayid[$key][$key2] && $value[$key2]>0 && $tpl['user']['user_id'] && !in_array($arrayid[$key][$key2],$arraycheckuser))
								echo "<td align=right ><a name=price".$arrayid[$key][$key2]."></a><div id=pricedelta".$arrayid[$key][$key2].">".($key2==0?"<b>":"").($value[$key2]<0?"<font color=red> R </font>":$value[$key2]).($key2==0?"</b>":"")."<br><a href=#price".$arrayid[$key][$key2]." onclick=\"PriceDelta('".$arrayid[$key][$key2]."','-1');\" title='Уменьшить цену'> &ndash; </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=#price".$arrayid[$key][$key2]." onclick=\"PriceDelta('".$arrayid[$key][$key2]."','1');\" title='Увеличить цену'> + </a></div></td>";
							else
								echo "<td align=right >".($key2==0?"<b>":"").($value[$key2]<0?"<font color=red> R </font>":$value[$key2]).($key2==0?"</b>":"")."</td>";
						} else echo "<td align=center> - </td>";
					}
				}
				echo "</tr>";
			}?>			
			</table>
			<?
		
		} else echo "<p class=error>Извините по Вашему запросу нет данных.</p>";
	}?>
	
	<p class=txt><br> <br> <strong>Ценник монет современной России и СССР</strong>

<br><br><br> Цены на монеты всегда актуальны для нумизмата. Но в отличие от ее номинала, цена на монету величина не постоянная. 
<br>Поэтому ценники на монеты часто быстро устаревают. 
<br>Что бы сохранять актуальность цен, мы и предлагаем вам этот сервис – Динамический ценник на монеты. 
<br>Задача данного раздела – с вашей помощью собрать и постоянно обновлять сведения по ценам на монеты СССР и современной России.

<br><br><br><strong>Как принять участие в формировании актуального ценника на монеты?</strong>

<br><br><ol><li> - Для участия в данном проекте вам необходимо лишь только быть зарегистрированным и авторизованным пользователем нашего портала. 
<li> - При просмотре ценника, внизу под значением цены отображаются значки “+” и “-“. 
<br>В случае если вы не согласны со стоимостью какой-либо монеты, то кликнув по одному из этих символов вы можете поднять, либо снизить соответственно цену на монету. 
<li> - Шаг поднятия (снижения) цены при клике составляет 5 процентов от ее текущего значения. 
<li> - По каждой отдельной позиции пользователь может вносить изменения цены не более одного раза в неделю. 
<li> - Цены на монеты рассчитываются в российских рублях. 
<li> - Монеты регулярных чеканов оцениваются в состоянии VF.
<li> - Памятные и юбилейные монеты оцениваются в состояниях (Proof-идеальное-зеркальное и UNC – которые не были в обращении), а также их новоделы.

<li> - С предложениями, дополнениями и замечаниями по работе сервиса просьба оставлять сообщения через раздел «контакты» либо пишите на емайл: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a>

</ol></p>
	
	<br><br><p class=txt>&nbsp;&nbsp;&nbsp;<a href="<?=$cfg['site_dir']?>gde-prodat-monety">Клуб Нумизмат бесплатно оценивает и занимается скупкой монет <?=$GroupName?$GroupName:" >>>"?></a></p><br>
<?} else {	?>

	<h5>Динамический ценник монет СССР и современной России</h5>
	<p class=txt><br> <br> <strong>Ценник монет современной России и СССР</strong>

<br><br><br> Цены на монеты всегда актуальны для нумизмата. Но в отличие от ее номинала, цена на монету величина не постоянная. 
<br>Поэтому ценники на монеты часто быстро устаревают. 
<br>Что бы сохранять актуальность цен, мы и предлагаем вам этот сервис – Динамический ценник на монеты. 
<br>Задача данного раздела – с вашей помощью собрать и постоянно обновлять сведения по ценам на монеты СССР и современной России.

<br><br><br><strong>Как принять участие в формировании актуального ценника на монеты?</strong>

<br><br><ol><li> - Для участия в данном проекте вам необходимо лишь только быть зарегистрированным и авторизованным пользователем нашего портала. 
<li> - При просмотре ценника, внизу под значением цены отображаются значки “+” и “-“. 
<br>В случае если вы не согласны со стоимостью какой-либо монеты, то кликнув по одному из этих символов вы можете поднять, либо снизить соответственно цену на монету. 
<li> - Шаг поднятия (снижения) цены при клике составляет 5 процентов от ее текущего значения. 
<li> - По каждой отдельной позиции пользователь может вносить изменения цены не более одного раза в неделю. 
<li> - Цены на монеты рассчитываются в российских рублях. 
<li> - Монеты регулярных чеканов оцениваются в состоянии VF.
<li> - Памятные и юбилейные монеты оцениваются в состояниях (Proof-идеальное-зеркальное и UNC – которые не были в обращении), а также их новоделы.

<li> - С предложениями, дополнениями и замечаниями по работе сервиса просьба оставлять сообщения через раздел «контакты» либо пишите на емайл: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a>

</ol></p>
	
<?}?>
</div>

<script language="javascript">

function PriceDelta(number,move) {
	if (number) {
		$("#pricedelta"+number).html('<img src="'+site_dir+'images/wait.gif">');
		$.ajax({
		    url: 'pricedelta.php?number=' + number + '&delta=' + move, 
		    type: "GET",      
		    dataType : "json",                   
		    success: function (data, textStatus) {    
		        if (!data.error){  
		        	$("#pricedelta"+number).html( '<b>'+data.newprice+'</b>');
	           } else if (data.error == 'auth') {
	                $("#pricedelta"+number).html('<b><font color=silver>Вы не авторизованы</font></b>');
	            }  else if(data.error == 'error3') {
					 $("#pricedelta"+number).html('Ошибочный запрос!');
				}	else if(data.error == 'error4') {
					 $("#pricedelta"+number).html('Ошибочный запрос!');
				}	else if(data.error == 'error5') {
					 $("#pricedelta"+number).html('Вы уже не так давно изменяли цену данной позиции!');
				}	            	          
		    }
		});
	}
}
</script>