<div id=MarkCoinsDiv><strong>Оценить товар:</strong>
<?if($matkitem['markusers']>0){?>
  (<img src='<?=$cfg['site_dir']?>/images/star<?=round($matkitem['marksum']/$matkitem['markusers'])*2?>.gif' border=0> - <?=$matkitem['markusers']?> оценка(ок))<br>
<?}
if (!$matkitem['usermarkis']) {?>
	<form name=markcoin><input type=radio name=marks class=formtxt value=5> 5 (круто)<br>
		<input type=radio name=marks class=formtxt value=4> 4 (хорошо)<br>
		<input type=radio name=marks class=formtxt value=3> 3 (пойдет)<br>
		<input type=radio name=marks class=formtxt value=2> 2 (хреново)<br>
		<input type=radio name=marks class=formtxt value=1> 1 (очень хреново)<br>
		<input type=button name=submitmark value=Оценить onclick="javascript:AddMark('<?=$tpl['user']['user_id']?>','<?=$matkitem['catalog']?>);">
	</form>
	<br>
<?}?>
	
</div>
<a href='#addreview'>Написать отзыв</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href=#showreview>Почитать отзывы</a>
