<?
include(START_PATH."/config.php");

$amountall = $rows['amount'];
if (in_array($rows["materialtype"],array(8,6,7,2,4))) {		
	$amountall = ( !$rows["amount"])?1:$rows["amount"];				
}		
	
$rows['amountall'] = $amountall;			
			
?>
<div class="amount">

<?
//var_dump($rows['buy_status']);
//кнопки в корзину, резервирует и тд
if($rows['buy_status']==2){?>
	<a class="button7" href="#" onclick="return false;" alt='Уже в вашей корзине'>Корзина</a>
<?} else if($rows['buy_status']==3){?>
	<a class="button6" href="#" onclick="return false;" alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>Корзина</a>		
<?} elseif ($rows['buy_status']==4){?>	
	<a class="button6" href="#" onclick="return false;" alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>Корзина</a>		
	
	<a class="button7" href="#" onclick="return false;" alt='Вы в очереди на покупку <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>В очереди</a>	
<?} elseif ($rows['buy_status']==5){?>
	<a class="button6" href="#" onclick="return false;" alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>Корзина</a>		
	<div id=bascetshop<?=$rows["shopcoins"]?>>
	  <a href='#' onclick="AddNext('<?=$rows["shopcoins"]?>','1');return false;" rel="nofollow" title='Стать в очередь на <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>' class="button11">Стать в очередь</a>
	</div>
<?} elseif ($rows['buy_status']==8){?>
	<input type="hidden" value="<?=$rows['amountall']?>" id='amountall<?=$rows["shopcoins"]?>'>
	<span class="down">-</span>
    <input type=text name=amount<?=$rows["shopcoins"]?> id=amount<?=$rows["shopcoins"]?> size=1 value='<?=$ourcoinsorderamount[$rows["shopcoins"]]?>'> 
	<span class="up">+</span>
    <a href='#' onclick='AddAccessory(<?=$rows["shopcoins"]?>,<?=$rows["materialtype"]?>);return false;' title='<?=$rows["name"]?>'>
	  <div id=bascetshopcoins<?=$rows["shopcoins"]?>><a class="button7" href="#" onclick="return false;" alt='Уже в вашей корзине'>Корзина</a></div>
	 </a>
<?} else if ($rows['buy_status']==6){?>			
	<div id=bascetshopcoins<?=$rows["shopcoins"]?>>		
		<input type="hidden" value="<?=$rows['amountall']?>" id='amountall<?=$rows["shopcoins"]?>'>			
    	<span class="down">-</span>
    	<input type=text name=amount<?=$rows["shopcoins"]?> id=amount<?=$rows["shopcoins"]?> size=1 value='1'> 
		<span class="up">+</span>
		<div class="buy-div">
			<a class="button25" href='#' onclick='AddAccessory(<?=$rows["shopcoins"]?>);return false;' title='Положить в корзину <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["name"]?>'>Купить</a>
		</div>
	</div>	
<?} elseif ($rows['buy_status']==7) {?>

    <div id=bascetshopcoins<?=$rows["shopcoins"]?> >
   		<a class="button25" href='#' onclick='AddAccessory(<?=$rows["shopcoins"]?>);return false;' title='Положить в корзину <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["name"]?>'>Купить</a>
    </div>
	</a>
	  
<?} elseif ($rows['buy_status']==9) {
	echo "Нет в наличии";
}?>
</div>