<div class="amount">

<?

//кнопки в корзину, резервирует и тд
if($rows['buy_status']==2){?>
	<span class="extdown"></span><span class="extup"></span><br>
	<img src='<?=$cfg['site_dir']?>images/corz7.gif' alt='Уже в вашей корзине'>
<?} else if($rows['buy_status']==3){?>
	<span class="extdown"></span><span class="extup"></span><br>
	<img src='<?=$cfg['site_dir']?>images/corz6.gif' alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>		
<?} elseif ($rows['buy_status']==4){?>
	<span class="extdown"></span><span class="extup"></span><br>
	<img src='<?=$cfg['site_dir']?>images/corz6.gif' alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>
	<img src='<?=$cfg['site_dir']?>images/corz77.gif' alt='Вы в очереди на покупку <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>		
<?} elseif ($rows['buy_status']==5){?>
	<span class="extdown"></span><span class="extup"></span><br>
	<img src='<?=$cfg['site_dir']?>images/corz6.gif' alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>
	<div id=bascetshop<?=$rows["shopcoins"]?>>
	  <a href='#coin<?=$rows["shopcoins"]?>' onclick="javascript:AddNext('<?=$rows["shopcoins"]?>','1');" rel="nofollow" title='Стать в очередь на <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>
	      <img src='<?=$cfg['site_dir']?>images/corz11.gif' alt='<?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>
	  </a>
	</div>
<?} elseif ($rows['buy_status']==8){?>
	<input type="hidden" value="<?=$rows['amountall']?>" id='amountall<?=$rows["shopcoins"]?>'>
	<span class="down">-</span>
    <input type=text name=amount<?=$rows["shopcoins"]?> id=amount<?=$rows["shopcoins"]?> size=1 value='<?=$ourcoinsorderamount[$rows["shopcoins"]]?>'> 
	<span class="up">+</span><br>
    <a href='#coin<?=$rows["shopcoins"]?>' onclick='javascript:AddAccessory'(<?=$rows["shopcoins"]?>,<?=$rows["materialtype"]?>)' title='<?=$rows["name"]?>'>
	  <div id=bascetshopcoins<?=$rows["shopcoins"]?>><img src=<?=$cfg['site_dir']?>images/corz7.gif alt='Уже в корзине'></div>
	 </a>
<?} else if ($rows['buy_status']==6){?>			
	<div id=bascetshopcoins<?=$rows["shopcoins"]?>>		
		<input type="hidden" value="<?=$rows['amountall']?>" id='amountall<?=$rows["shopcoins"]?>'>			
    	<span class="down">-</span>
    	<input type=text name=amount<?=$rows["shopcoins"]?> id=amount<?=$rows["shopcoins"]?> size=1 value='1'> 
		<span class="up">+</span><br>
    	<a class="button25" href='#coin<?=$rows["shopcoins"]?>' onclick='javascript:AddAccessory(<?=$rows["shopcoins"]?>)' title='Положить в корзину <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["name"]?>'>Купить</a>
	</div>	
<?} elseif ($rows['buy_status']==7) {?>
<span class="extdown"></span><span class="extup"></span><br>
    <div id=bascetshopcoins<?=$rows["shopcoins"]?>>
   		<a class="button25" href='#coin<?=$rows["shopcoins"]?>' onclick='javascript:AddAccessory(<?=$rows["shopcoins"]?>)' title='Положить в корзину <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["name"]?>'>Купить</a>
    </div>
	</a>
	  
<?} elseif ($rows['buy_status']==9) {
	echo "Нет в наличии";
}?>
</div>