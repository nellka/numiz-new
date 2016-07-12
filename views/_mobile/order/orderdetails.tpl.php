<h1 class="left">Корзина</h1>
    <?
    if($tpl['can_order']){?>
        
    <? if(!$tpl['user']['user_id']){?>
    	    <a class="button28 marg-10" onclick="showWin('<?=$cfg['site_dir']?>user/login_order.php?ajax=1',340);return false;" href="#" id='of-1' style="float:right">Оформить заказ</a>
	<?} else {?>
        	<form action="<?=$cfg['site_dir']?>shopcoins?page=order&page2=1" method="post">
        	 <input type=submit  class="button28 marg-10" name=submit value='Оформить заказ' style="float:right;margin: 0 10px 20px;">
        	</form>
    	<?}?>
    <?}

if($tpl['orderdetails']['ArrayShopcoinsInOrder']){?>
<form action=<?=$cfg['site_dir']?>shopcoins?page=orderdetails method=post id=order-form>
<div class="cart-info">
<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">

<?
$i=0;
foreach ($tpl['orderdetails']['ArrayShopcoinsInOrder'] as 	$rows ){	
	if ($rows["title_materialtype"]) {?>
		<tr><td class=h-cat-title colspan="2"><?=$rows["title_materialtype"]?></td></tr>		
	<?}?>	
	<tr>
	   <td id="image<?=$rows['catalog']?>" width="35%" align="center" >
	   
	       <div id=show<?=$rows['catalog']?>></div>
	       <div class='image_block'>
			<?if($rows['image_big']){?>
				<div id="image<?=$rows['catalog']?>" class='imageBig' style="display:none;position: absolute;">
            		<img class="img_hover" src="<?=contentHelper::urlImage($rows['image_big'])?>" />
                </div>
            <?}?>
			<? echo contentHelper::showImage("smallimages/".$rows["image_small"],$rows["gname"]." | ".$rows["name"]);?>
		  </div>     
	   </td>
	   <td>
	       <a href="<?=$cfg['site_dir']?>shopcoins?catalog=<?=$rows["catalog"]?>&page=show&materialtype=<?=$rows["materialtype"]?>" target=_blank><?=$rows["name"]?></a><br>	 
	       <a href="<?=$cfg['site_dir']?>shopcoins?group=<?=$rows["group"]?>&materialtype=<?=$rows["materialtype"]?>">
	       <?=$rows["gname"]?>
	       </a><br>
	  <?=$rows["year"]?>
	  <?=$rows["number"]?>
    	</td>
    	</tr>
    	<tr>
    	<td  class="h-cat" align=center width="35%" >
	
	<input type=hidden name=shopcoins<?=$i?> value='<?=$rows["catalog"]?>'>   
    <? if($rows["amountAll"]>1){?>
    <div class="amount">
    <input id="amountall<?=$rows["catalog"]?>" type="hidden" value="<?=$rows["amountAll"]?>">
    <span class="down">-</span>
    <input id="amount<?=$rows["catalog"]?>" type="text" value="<?=$rows["oamount"]?>" size="1" name="amount_<?=$i?>">
    <span class="up">+</span>
    </div>
    <?} else {?>
    	<?=$rows["oamount"]?>
    <?}
    ?>
    	</td>    	
    	<td  class="h-cat"><?=$rows["price"]*$rows["oamount"]?> рублей 
    	<a href=<?=$cfg['site_dir']?>shopcoins?page=orderdetails&pageinfo=delete&shopcoins=<?=$rows["catalog"]?> title='Удалить из корзины'><img src="<?=$cfg['site_dir']?>images/delete-item.png"></a></td>
    	</tr>
    	<?
    	$i++;
    }?>
    </table>

</div>

<input type=hidden name=amount value='<?=$i?>'>
</form>
<div class="clearfix"> 
<p><b>Доставка</b> осуществляется на сумму <b><font color=red>не менее 500 руб.</font></b> и <b><font color=red>не более <?=$stopsummax?> руб.</font></b> по территории РФ. </p>
<? if($sum<500){?>
<p style="border: 1px solid #cccccc;margin: 0;  padding: 10px;" class="left">Заказ на сумму менее 500 руб. могут сделать авторизованые пользователи, у которых есть ранее сделанный заказ, но еще не отправленный покупателю. В таком случае новый заказ будет объединен с предыдущим не отправленным.
К стоимости Вашего заказа будет добавлена стоимость почтовых услуг по упаковке, страховке и доставке его Вам, которая зависит от пункта назначения, массы и стоимости товара.</p>
<?}?>
<div class="right"><br>Итого(без суммы доставки): <b><?=$sum?> рублей</b> <br>
<? if($tpl['user']['vip_discoint']){?>
    Ваша скидка как VIP-клиента: <b><?=$tpl['user']['vip_discoint']?> %</b> <br>
    Итого c учетом скидки (без суммы доставки): <b><?=($sum-floor($sum*$tpl['user']['vip_discoint']/100))?> рублей</b> <br>
<?}?>

<!--<a class="button25 right" style="width:100px" onclick="$('#order-form').submit()">Пересчитать</a>-->
</div>
</div>
<?} else {?>
<div class="error" style="clear: both;">Ваша корзина пуста</div>
<?}?>
<div class="clearfix">
<div class="center">
    <?
    if($tpl['can_order']){?>
        
    <? if(!$tpl['user']['user_id']){?>
    	    <br><a class="button28 marg-10" onclick="showWin('<?=$cfg['site_dir']?>user/login_order.php?ajax=1',340);return false;" href="#"  id='of-1'>Оформить заказ</a>
	<?} else {?>
        	<form action="<?=$cfg['site_dir']?>shopcoins?page=order&page2=1" method="post">
        	 <br> <input type=submit  class="button28 marg-10" name=submit value='Оформить заказ' >
        	</form>
    	<?}?>
    <?} elseif($tpl['orderdetails']['ArrayShopcoinsInOrder']) { ?>
       <br> <input type="button"  class="button26 marg-10" value='Оформить заказ' >
        <div style="clear: both; color: red;text-align: right;">
       <?  if ($tpl['order_status']==1) {?>
        	<div class="error">Сумма заказа должна быть не более <?=$stopsummax?> руб</div>
        <?} elseif ($tpl['order_status']==2) {?>
        	Сумма заказа должна быть больше 500 рублей.
        	</div>
        <?} else {?>
        	<form action=<?=$cfg['site_dir']?>shopcoins?page=order&page2=2 method=post>
        	<br><input type=submit name=submit  class="button25"  value='Перейти к добавлению к предыдущему заказу' class=formtxt>
        	</div>
        <?}?>
        </div>
    <?}?>
    </div>
</div>

<?

if ($tpl['orderdetails']['related']) {?>
<br style="clear:both">
<div class="clearfix">
	<h5>Рекомендуем приобрести:</h5>
</div>
<div class="triger">	
	<div class="clearfix recomended">
		<div>
			<?foreach ($tpl['orderdetails']['related'] as $rowsp){
			    $rowsp['metal'] = $tpl['metalls'][$rowsp['metal_id']];
		        $rowsp['condition'] = $tpl['conditions'][$rowsp['condition_id']];
			    ?>
				<div class="coin_info">
					<div id=show<?=$rowsp['shopcoins']?>></div>
				<?	
				$statuses = $shopcoins_class->getBuyStatus($rowsp["shopcoins"],$tpl['user']['can_see'],array(),array());
				$rowsp['buy_status'] = $statuses['buy_status'];
				$rowsp['reserved_status'] = $statuses['reserved_status'];	
				//$rowsp['mark'] = $shopcoins_class->getMarks($rowsp["shopcoins"]);
				echo contentHelper::render('shopcoins/item/itemmini',$rowsp);
	            ?>				
				</div>
			<?}?>
		</div>
	</div>
</div>

<?}?>

<script type="text/javascript">  
$(function(){        	
     $('#order-form input').on('change',function(){  
        $('#order-form').submit();
     });          
});
</script>
