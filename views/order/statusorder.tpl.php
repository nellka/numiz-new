<div class="bordered">

<h1>Заказы магазина</h1>
<?
if($tpl['error']){?>
	<div class="error"><?=$tpl['error']?></div>	
<?} else {		
	if (!$submit){?>
		<b>Добрый день уважаемый(ая), <?=$rows["fio"]?>.</b>
		<br>Спасибо за то что уделили нам минуту внимания. Мы хотим узнать о состоянии Вашего заказа № <b><?=$rows["order"]?></b>.
		<h4>Содержимое заказа</h4>
		<?if ($type=="shopcoins")	{?>
			<table border=0 cellpadding=3 cellspacing=1>
			<tr bgcolor=#EBE4D4>
				<td class=tboard><b>Название</b></td>
				<td class=tboard><b>Группа</b></td>
				<td class=tboard><b>Цена</b></td>
				<td class=tboard><b>Кол-во</b></td>
			</tr>
			
			<? foreach ($tpl['result_content'] as $rows_content){
				$sum += $rows_content["amount"]*$rows_content["price"];
				?>
				<tr bgcolor=#f8f6e8>
					<td class=tboard><?=$rows_content["name"]?></td>
					<td class=tboard><?=$rows_content["gname"]?></td>
					<td class=tboard><?=$rows_content["price"]?></td>
					<td class=tboard><?=$rows_content["amount"]?></td>
				</tr>				
			<?}?>
			</table>
		<?} elseif ($type == "Book") {?>
			
			<table border=0 cellpadding=3 cellspacing=1>
				<tr bgcolor=#EBE4D4>
				<td class=tboard><b>Название</b></td>
				<td class=tboard><b>Цена</b></td>
				<td class=tboard><b>Кол-во</b></td>
			</tr>
			
			<?foreach ($tpl['result_content'] as $rows_content){
				$sum += $rows_content["amount"]*$rows_content["BookPrice"];?>
				
				<tr bgcolor=#f8f6e8>
					<td class=tboard><?=$rows_content["BookName"]?></td>
					<td class=tboard><?=$rows_content["BookPrice"]?></td>
					<td class=tboard><?=$rows_content["amount"]?></td>
				</tr>				
			<?}?>
			</table>
		<?} elseif ($type == "Album") {?>
			<table border=0 cellpadding=3 cellspacing=1>
			<tr bgcolor=#EBE4D4>
			<td class=tboard><b>Название</b></td>
			<td class=tboard><b>Цена</b></td>
			<td class=tboard><b>Кол-во</b></td>
			</tr>
			<?foreach ($tpl['result_content'] as $rows_content){
				$sum += $rows_content["amount"]*$rows_content["AlbumPrice"];?>
				<tr bgcolor=#f8f6e8>
					<td class=tboard><?=$rows_content["AlbumName"]?></td>
					<td class=tboard><?=$rows_content["AlbumPrice"]?></td>
					<td class=tboard><?=$rows_content["amount"]?></td>
				</tr>				
			<?}?>
			</table>
		<?}?>
		
		<br>Просим Вас ответить на вопросы
		<form action="" method=post name=FormReminder id=FormReminder>
		<table border=0 cellpadding=3 cellspacing=1>
		<input type=hidden name=ReminderKey value='<?=$ReminderKey?>'>
		<input type=hidden name=user value='<?=$user?>'>
		<tr bgcolor=#EBE4D4><td class=tboard>Заказ</td><td class=tboard>
		<select name=Reminder class=formtxt>
		<option value=3 <?=selected($Reminder,3)?>>Доставлен</option>
		<option value=4 <?=selected($Reminder,4)?>>Не доставлен</option>
		</select>
		</td></tr>
		<tr bgcolor=#EBE4D4><td class=tboard>Комплектация</td><td class=tboard>
		<select name=complected class=formtxt>
		<option value=2 <?=selected($complected,2)?>>Согласно описи</option>
		<option value=1 <?=selected($complected,1)?>>Несоответствие описи</option>
		</select>
		</td></tr>
		<tr bgcolor=#EBE4D4><td class=tboard>Оценка за обслуживание:</td><td class=tboard>
		<select name=mark class=formtxt id=mark>
		<option value=0 <?=selected($mark,0)?>>Укажите</option>
		<option value=1 <?=selected($mark,1)?>>Хорошо</option>
		<option value=2 <?=selected($mark,2)?>>Плохо</option>
		</select>
		</td></tr>
		<tr bgcolor=#f8f6e8><td class=tboard>Ваши пожелания:</td>
		<td class=tboard><textarea name=ReminderComment class=formtxt cols=50 rows=6></textarea></td></tr>
		<tr bgcolor=#EBE4D4><td colspan=2 class=tboard align=center>
		<input type="submit" name=submit value='Ответить' onclick="if($('#FormReminder #mark').val()<1){alert('Пожалуйста оцените качество обслуживания по данному заказу.'); return false;} else {return true;}">
		</td></tr>
		</table>
		</form>
		<br>С уважением, администрация Клуба Нумизмат.
		
	<?} else {?>
	    Огромное спасибо за информацию.<br><br>
		<?
		if($mark==1&&$complected==2&&$Reminder==3){?>
			Мы будем очень признательны Вам, если Вы оставите о нас отзыв.<br><br>
			<noindex>
			<a target="_blank" href='https://yandex.ru/maps/org/klub_numizmat/1019429100' >Оставить отзыв в Яндексе</a><br><br>
			<a target="_blank" href='https://www.google.ru/maps/place/%D0%9A%D0%BB%D1%83%D0%B1+%D0%9D%D1%83%D0%BC%D0%B8%D0%B7%D0%BC%D0%B0%D1%82/@55.764044,37.6072903,17z/data=!4m7!3m6!1s0x46b54a16ae5dc859:0x37cc551d5b8f4b23!8m2!3d55.764044!4d37.609479!9m1!1b1'>Оставить отзыв в Google</a>
			</noindex>
			
			<br><br>
			
			Заранее огромное спасибо.<br>
		<?}?>
		
		<br>С уважением, администрация Клуба Нумизмат.
		<br><br>
	<?}
}
?>
</div>