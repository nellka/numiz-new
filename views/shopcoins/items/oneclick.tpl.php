<div  id="oneshopcoins<?=$rows["shopcoins"]?>" style="position:relative" class="oneclick_notifications">
	<a href='#coinone<?=$rows["shopcoins"]?>' onclick="ShowOneClick(<?=$rows["shopcoins"]?>);return false;">
	Купить в один клик</a>
	<a name='coinone<?=$rows["shopcoins"]?>' ></a>	 	
	 
	  <div class="messages" id='messages<?=$rows["shopcoins"]?>' style='display:none;'>
	  <a id="fancybox-close" style="display: inline;" onclick="HideOneClick(<?=$rows["shopcoins"]?>)"></a>
	  <table width=450 cellpadding=0 cellspacing=0>
	  	<tr class=tboard height=22>
	  		<td colspan=2 align=center>Форма быстрого заказа</td>
	  	</tr>
	  	<tr class=tboard>
	  		<td colspan=3>Чтобы оформить заказ, укажите только свое имя и номер телефона.<br> Все остальные данные Вы можете сообщить менеджеру. <br>Форма \"Быстрый заказ\" предназначена для заказа одного вида товаров. <br> Если вы хотите продолжать выбор товаров, то используйте обычную функцию заказа - \"В корзину\"<br>&nbsp;</td>
	    </tr>
		<tr class=tboard>
			<td width=30% valign=top>Вы заказываете:</td>
			<td colspan=2><?=$rows["name"]?></td>
		</tr>
		<tr class=tboard height=20>
			<td>Ваше имя:</td>
			<td colspan=2><input class=edit id=onefio type=text name=onefio value='' size=40></td>
	    </tr>
		<tr class=tboard height=20>
			<td>Телефон:</td>
			<td colspan=2><input class=edit id=onephone type=text name=onephone value='' size=15></td></tr>
		<tr class=tboard align=center height=20>
			<td align=center colspan=3><input class=edit type=button value='Оформить заказ' onclick="AddOneClick(<?=$rows["shopcoins"]?>)"></td>
	   </tr>
	</table>
	   </div>
</div>  