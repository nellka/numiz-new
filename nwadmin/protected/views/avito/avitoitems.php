<?php 
if($sections){?>
<h3>Записанные секции (Нужно скопировать в Exel и отправить в программу)</h3>
<table border="1" cellpadding="0" cellspacing="0">
<tr style="border: 1px solid black;">
	<td  style="border: 1px solid black;">Страна</td>
	<td  style="border: 1px solid black;">Название</td>
	<td  style="border: 1px solid black;">Металл</td>
	<td  style="border: 1px solid black;">Год от</td>
	<td  style="border: 1px solid black;">Год до</td>
</tr>
<?
foreach ($sections as $section){?>
	<tr>
		<td  style="border: 1px solid black;"><?=$section->group->name?></td>
		<td  style="border: 1px solid black;"><?=$section->nominal->name?></td>
		<td  style="border: 1px solid black;"><?=$section->metal->name?></td>
		<td  style="border: 1px solid black;"><?=$section->year_from?></td>
		<td  style="border: 1px solid black;"><?=$section->year_to?></td>
	</tr>
<?}?>
</table>
<?}?>