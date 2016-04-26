<div class="clearfix order-cart">
	<br>
    <h5 class="left">Корзина</h5>
	
    <div class="right">
    <?
    if($tpl['can_order']){?>
      <?if(!$tpl['user']['user_id']){?>
    	    <a class="button25 right" onclick="showWin('<?=$cfg['site_dir']?>user/login_order.php?ajax=1',400);return false;" href="#" style="width:150px" id='of-1'>Оформить заказ</a>
    	<?} else {?>
        	<form action="<?=$cfg['site_dir']?>shopcoins?page=order&page2=1" method="post">
        	<input type=submit  class="button25 right" name=submit value='Оформить заказ' style="width:150px">
        	</form>
    	<?}?>
    <?} elseif($tpl['orderdetails']['ArrayShopcoinsInOrder']) {?>
        <input type="button"  class="button26 right" value='Оформить заказ'>
    <?}?>
    </div>
</div>
<br>
<?
if($tpl['orderdetails']['ArrayShopcoinsInOrder']){?>
<form action=<?=$cfg['site_dir']?>shopcoins?page=orderdetails method=post id=order-form>
<div class="cart-info">
<table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #cccccc; border-collapse: collapse;">
<thead>
<tr>
    <td class="tboardtop" nowrap>Фото товара</td>
    <td  class="tboardtop" width="200">Наименование</td>
    <td class="tboardtop" >Группа(страна)</td>
    <td class="tboardtop" >Год</td>
    <td class="tboardtop" >Номер</td>
    <td class="tboardtop" >Цена</td>
    <td class="tboardtop" >Количество</td>
    <td class="tboardtop" ><b>Сумма</b></td>
	<td class="tboardtop" ></td>
</tr>
</thead>
<?
$i=0;
foreach ($tpl['orderdetails']['ArrayShopcoinsInOrder'] as 	$rows ){	
	if ($rows["title_materialtype"]) {?>
		<tr><td colspan=9 class=h-cat><?=$rows["title_materialtype"]?></td></tr>		
	<?}?>	
	<tr>
	   <td class=tboard id=image<?=$rows['catalog']?>>
	       <div id=show<?=$rows['catalog']?>></div>
	       <div class='image_block'>
			<?if($rows['image_big']){?>
				<div id="image<?=$rows['catalog']?>" class='imageBig' style="display:none;position: absolute;">
            		<img class="img_hover" src="<?=contentHelper::urlImage($rows['image_big'])?>" />
                </div>
            <?}?>
			<? echo contentHelper::showImage("smallimages/".$rows["image_small"],$rows["gname"]." | ".$rows["name"]);?>
		  </div>
	       <?	      
	       /*if ($rows["gname"]){?>
        <?=in_array($rows["materialtype"],array(9,3,5))?"Группа":"Страна"?>: <?=$rows["gname"]?><br>
        <?}?>
        Название: <strong><?=$rows["name"]?></strong><br>
        Номер: <strong><?=$rows["number"]?></strong><br>
        
        <?=($rows["year"]?"Год: <strong>".$rows["year"]."</strong><br>":"")?>
        <?=(isset($rows["metal"])?"Металл: <strong>".$rows["metal"]."</strong><br>":"")?>
        <?=($rows["condition"]?"Состояние: <strong><font color=blue>".$rows["condition"]."</font></strong><br>":"")?>
        <? $price_text = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"Цена":"Стоимость";?>
        <?= $price_text?>: <strong><font color=red><?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?></font></strong>
        <?= ($rows["width"]&&$rows["height"]?"<br>Приблизительный размер: <strong>".$rows["width"]."*".$rows["height"]." мм.</strong><br>":"")."
        ".($rows["weight"]>0?"<br>Вес: <strong>".$rows["weight"]." гр.</strong><br>":"")."
        ".(isset($rows["series"])&&$rows["series"]&&$group?"<br>Серия монет: ".$series_name[$rows["series"]]."":"")."
        ".($rows["materialtype"]==8&&$materialtype==1&&!$mycoins?"<br><font color=red>МОНЕТА С РАЗДЕЛА МЕЛОЧЬ, см. условия покупки в разделе</font>":"")."
        ".($rows["accessoryProducer"]?"<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
        ".($rows["accessoryColors"]?"<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>":"")."
        ".($rows["accessorySize"]?"<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>":"");
        
        if ($rows["details"]){
        	echo "<br>Описание: ".$rows["details"];
        }*/?>
	   </td>
	   <td class=tboard>
	       <a href="<?=$cfg['site_dir']?>shopcoins?catalog=<?=$rows["catalog"]?>&page=show&materialtype=<?=$rows["materialtype"]?>" target=_blank><?=$rows["name"]?></a>
	   </td>
	   <td class=tboard>
	       <a href="<?=$cfg['site_dir']?>shopcoins?group=<?=$rows["group"]?>&materialtype=<?=$rows["materialtype"]?>">
	       <?=$rows["gname"]?>
	       </a>
	   </td>
	<td class=tboard><?=$rows["year"]?></td>
	<td class=tboard><?=$rows["number"]?></td>
	<td class=tboard><?=($rows["price"]==0)?"бесплатно":round($rows["price"])." руб."?></td>
	<td class=tboard align=center>
	
	<input type=hidden name=shopcoins<?=$i?> value='<?=$rows["catalog"]?>'>
	<!--<input type=hidden name=sqlamount<?=$i?> value='<?=$rows["amount"]?>'>
	<input type="<?=($rows["oamount"]?"text":"hidden")?>" name=amount_<?=$i?> value='<?=$rows["oamount"]?>' size=2 maxlength=5 class=formtxt>
	<?($rows["oamount"]?$rows["oamount"]:"")?>
	-->

<? 

if($rows["amountAll"]>1){?>
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
	<td class=tboard><?=$rows["price"]*$rows["oamount"]?> рублей</td>
	<td class=tboard><a href=<?=$cfg['site_dir']?>shopcoins?page=orderdetails&pageinfo=delete&shopcoins=<?=$rows["catalog"]?> title='Удалить из корзины'><img src="<?=$cfg['site_dir']?>images/delete-item.png"></a></td>
	</tr>
	<?
	$i++;
}?>
</table>

</div>

<input type=hidden name=amount value='<?=$i?>'>
</form>
<div class="clearfix"> 
<?
if($sum<500){?>

<p><b>Доставка</b> осуществляется на сумму <b><font color=red>не менее 500 руб.</font></b> и <b><font color=red>не более <?=$stopsummax?> руб.</font></b> по территории РФ. </p>
<p style="border: 1px solid #cccccc;margin: 0;  padding: 10px; width: 630px;" class="left">Заказ на сумму менее 500 руб. могут сделать авторизованые пользователи, у которых есть ранее сделанный заказ, но еще не отправленный покупателю. В таком случае новый заказ будет объединен с предыдущим не отправленным.
К стоимости Вашего заказа будет добавлена стоимость почтовых услуг по упаковке, страховке и доставке его Вам, которая зависит от пункта назначения, массы и стоимости товара.</p>
<?
}?>

<div class="right marg-10">Итого(без суммы доставки): <b><?=$sum?> рублей</b> <br>

<? if($tpl['user']['user_id']&&$tpl['user']['user_data']['vip_discoint']){?>
    Ваша скидка как VIP-клиента: <b><?=$tpl['user']['user_data']['vip_discoint']?> %</b> <br>
    Итого c учетом скидки (без суммы доставки): <b><?=($sum-floor($sum*$tpl['user']['user_data']['vip_discoint']/100))?> рублей</b> <br>
<?}?>

<!--<a class="button25 right" style="width:100px" onclick="$('#order-form').submit()">Пересчитать</a>-->
</div>
</div>
<?} else {?>
<div class="error">Ваша корзина пуста</div>
<?}?>

<?if($viporder_id){?>
	<div class="error">
	Предзаказ создан. Номер предзаказа:<?=$viporder_id?><br>
	Посмотреть предзаказа:<a href="<?=$cfg['site_dir']?>shopcoins?page=viporder&id=<?=$viporder_id?>"><?=$viporder_id?></a>
	</div>
<?}?>

<div class="clearfix">
<a href='<?=$cfg['site_dir']?>shopcoins' class="left c-b">Продолжить покупки </a>
 <div class="right">
    <?
    if($tpl['can_order']){?>
        
    	<?if(!$tpl['user']['user_id']){?>
    	    <a class="button25 right" onclick="showWin('<?=$cfg['site_dir']?>user/login_order.php?ajax=1',400);return false;" href="#" style="width:150px" id='of-1'>Оформить заказ</a>
	<?} else {?>
        	<form action="<?=$cfg['site_dir']?>shopcoins?page=order&page2=1" method="post">
        	<input type=submit  class="button25 right" name=submit value='Оформить заказ' style="width:150px">       	
        	</form>
        	
        	<? if($tpl['user']['user_id']==811){?>
        		<br><br><form action="<?=$cfg['site_dir']?>shopcoins?page=orderdetails" method="post">
        		<input type="hidden" id='viporder' name='viporder' value="1">
        		<input type=submit  class="button25 right" name=submit value='Создать Предзаказ' style="width:150px">       	
        		</form>
        	<?}?>
    	<?}?>
    <?} elseif($tpl['orderdetails']['ArrayShopcoinsInOrder']) { ?>
        <input type="button"  class="button26 right" value='Оформить заказ'>
        <div style="width:300px; clear: both; color: red;text-align: right;">
       <?  if ($tpl['order_status']==1) {?>
        	<div class="error">Сумма заказа должна быть не более <?=$stopsummax?> руб</div>
        <?} elseif ($tpl['order_status']==2) {?>
        	Сумма заказа должна быть больше 500 рублей.
        	</div>
        <?} else {?>
        	<form action=<?=$cfg['site_dir']?>shopcoins?page=order&page2=2 method=post>
        	<input type=submit name=submit  class="button25 right"  value='Перейти к добавлению к предыдущему заказу' class=formtxt>
        	</div>
        <?}?>
        </div>
    <?}?>
    </div>
</div>

<?

if ($tpl['orderdetails']['related']) {?>

<div class="wraper clearfix">
	<h5>Рекомендуем приобрести:</h5>
</div>
<div class="triger">	
	<div class="wraper clearfix recomended">
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
