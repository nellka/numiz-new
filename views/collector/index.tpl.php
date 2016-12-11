<div class="main_context bordered rating table">
<h1>Клуб Нумизмат</h1>

<p>Уважаемые коллеги!</p>
<p>В данном разделе вы можете зарегистрироваться, поместив информацию о себе и о темах вашей коллекции. Надеемся, что этот раздел поможет вам установить новые контакты с единомышленниками, найти новых друзей.</p>
<p>Большая просьба не помещать информацию, не относящуюся к тематике нашего сайта. В противном случае она будет удалена без уведомления автора. Кроме того, запрещается использование нецензурной лексики, прямых или косвенных оскорблений. Будьте вежливы друг к другу.</p>
<p>Администрация сайта не несет ответственности за достоверность и качество, размещаемой в этом разделе информации.</p>

</p>
<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=#ecb34e>
<tr>
	<td colspan="3" width="100%" bgcolor="black" height="1"></td>
</tr>
<tr><td class=formtxt align=center>Город:</td>
		<td class=formtxt align=center>Интерес:</td>
		<td class=formtxt></td></tr>
<tr><form action="<?=$cfg['site_dir']?>collector" method=post class=formtxt>
		<td align=center class=formtxt>
		<select name=city class=formtxt>
		<?for ($i=0; $i<sizeof($city_array); $i++){
			echo "<option value=".$i." ".selected($i,$city).">".$city_array[$i]."</option>";			
		}?>
		</select>
		</td>
		<td align=center>
		
		<select name=interest class=formtxt>
		<option value=0>Все</option>
		<?for($i=1; $i<=sizeof($interests); $i++){
				echo "<option value=".$i." ".selected($i,$interest).">".$interests[$i][0]."</option>";				
		}?>
		</select>
		</td>
		
	<td align=center><input type=submit name=submit value='Найти' class=button25></td></form>
</tr>
<tr>
<td colspan="3" width="100%"><br> </td>
</tr>
<tr>
	<td colspan="3" width="100%" bgcolor="black" height="1"></td>
</tr>
</table>
<?
foreach ($tpl['data'] as $rows){
	//var_dump($rows);
	?>
	<br><table border=0 cellpadding=3 cellspacing=1 width=100% align=center>
		<tr bgcolor=#EBE4D4>
			<td class=tboard width=310><b>ФИО:</b> <?=$rows["fio"]?></td>
			<td width=150 class=tboard align=right>
				<a href="#" onclick="showWin('<?=$cfg['site_dir']?>collector/message.php?user_to=<?=$rows["user"]?>&ajax=1',500);return false;">
				<img src="<?=$cfg['site_dir']?>images/message.gif" alt='Отправить сообщение пользователю' border=0></a> &nbsp;&nbsp;&nbsp;
				<a href="#" onclick="showWin('<?=$cfg['site_dir']?>collector/message.php?user_to=<?=$rows["user"]?>&ajax=1&chat=1',500);return false;">
				<img src="<?=$cfg['site_dir']?>images/0.gif" alt='Назначить время в чате' border=0></a>
			</td>
		</tr>
		<tr bgcolor=#fff8e8 valign=top>
			<td class=tboard width=310><b>Рейтинг:</b></td>
			<td class=tboard><img src="<?=$cfg['site_dir']?>images/star<?=$rows['stars']?>.gif" alt='Рейтинг пользователя' border=0> (<?=$rows['stars']?>)</td>
	    </tr>	    
		<tr bgcolor=#fff8e8 valign=top>
			<td class=tboard width=310><b>Город:</b></td>
			<td class=tboard><?=$rows["city"]?></td>
		</tr>
		<?
		if($rows['photo']){?>
			<tr bgcolor=#fff8e8 valign=top>
				<td class="tboard"><b>Фотография:</b></td>
				<td class="tboard"><img src="<?=$cfg['site_dir']?>/images/<?=$rows["photo"]?>"></td>
			</tr>
		<?}?>
		<tr bgcolor=#fff8e8 valign=top>
			<td class=tboard colspan=2 align=center><b>Интересы:</b></td>
		</tr>
		<?
		foreach ($rows['intereses'] as $i){?>
			<tr bgcolor=#fff8e8 valign=top>
				<td class=tboard><b><?=$interests[$i][0]?></b></td>
				<td class=tboard><span class=red><b>Да</b></font></td>
			</tr>
		<?}?>
		<tr bgcolor=#fff8e8 valign=top>
			<td class=tboard colspan=2><?=str_replace("\n", "<br>", $rows["text"])?></td>
	    </tr>
		</table>
		
	<?
}?>
<div class="right">
	<?=$tpl['paginator']->printPager();?>
	</div>

</div>