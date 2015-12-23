<div id="raiting_star<?=$rows['id']?>">
    <div id="raiting" class="raiting">
        <div id="raiting_blank"></div>
        <div id="raiting_hover"></div>
        <div id="raiting_votes"></div>       
    </div>
    <div id="raiting_info<?=$rows['id']?>" class="raiting_info"><?=$rows['markusers']?> оценка(ок) <span id="raiting_error<?=$rows['id']?>" class="error"></span></div>
    
</div>

<?/*if($rows['markusers']>0){?>
  (<?=contentHelper::showImage('images/star'.(round($rows['marksum']/$rows['markusers'])*2).'.gif','')?> - <?=$rows['markusers']?> оценка(ок))<br>
<?}
if (!$rows['usermarkis']) {?>
	<form name=markcoin><input type=radio name=marks class=formtxt value=5> 5 (круто)<br>
		<input type=radio name=marks class=formtxt value=4> 4 (хорошо)<br>
		<input type=radio name=marks class=formtxt value=3> 3 (пойдет)<br>
		<input type=radio name=marks class=formtxt value=2> 2 (хреново)<br>
		<input type=radio name=marks class=formtxt value=1> 1 (очень хреново)<br>
		<input type=button name=submitmark value=Оценить onclick="javascript:AddMark('<?=$tpl['user']['user_id']?>','<?=$rows['catalog']?>);">
	</form>
	<br>
<?}*/?>
<script type="text/javascript">
jQuery(document).ready(function(){
  initRaiting(<?=$rows['id']?>,<?=($rows['markusers'])?round($rows['marksum']/$rows['markusers']):0?>);
});
</script>
