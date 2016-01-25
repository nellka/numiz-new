<!--Блок выбора параметров заказа-- -->
<div style="width:60%;float:left">
	<form name=resultform method=post id=resultorderform action="<?=$cfg['site_dir']?>shopcoins/submitorder.php">
		<input type="hidden" id="userstatus" name="userstatus" value="<?=$userstatus?>">
		<div id='user-compare-block' style="display:none">
			<h3>Проверить заказ</h3>
			<p style="color:red;"><b>Уважаемый пользователь! Проверьте внимательно данные о вашем заказе</b></p>
			<div class="error" id='error-order'></div>
			<div>Заказ № <?=$shopcoinsorder?></div>

			<div>ФИО: <span id='fio-result'></span></div>
			<div>ФИО Получателя: <span id='userfio-result'></span></div>
			<div>Телефон: <span id=phone-result></span></div>
			<div>Способ доставки:<span id=delivery-result></span></div>
			<div id='metro-block-result' style="display:none">
				Метро:<span id=metro-result></span>
			</div>
			<div id='metro-price-block-result' style="display:none">
				Цена доставки:<span id=metro-price-result></span>  р.
			</div>
			<div id='meetingdate-block-result' style="display:none">
				Дата:<span id=meetingdate-result></span>
			</div>

			<div id='meetingfromtime-block-result' style="display:none">
				Время: с <span id='meetingfromtime-result'></span> по <span id='meetingtotime-result'></span>         </div>

			<div>Способ оплаты: <span id='payment-result'></span></div>
			<div id='postindex-block-result' style="display:none">
				Индекс: <span id=postindex-result></span>
			</div>

			<div id='adress-block-result' style="display:none">
				Адрес отправления: <span id=adress-result></span>
			</div>
			<div id='coupon-block-result' style="display:none">
				Скидочный купон: <span id=coupon-result></span><br>
				Скидка по купону: <span id=discountcoupon-result></span>
			</div>
			<div>
				Комментарий к заказу: <span id=OtherInformation-result></span>
			</div>
			<div>Сумма заказа: <span id=bascetsum-result></span> р.</div>
			<div id='post-block-result' style="display:none">
				<h5>Отправка по почте</h5>
				<div>Почтовая тариф зона<span id='post-zone-result'></span></div>
				<div>Вес посылки (с упаковкой) <span id=bascetpostweight-result></span> гр.</span></div>
				<div>Почтовый тариф для посылки
					<span id=postzoneprice-result></span> р.
					(<a href=http://www.russianpost.ru/ target=_blank>http://www.russianpost.ru/</a>)
				</div>
				<div id=post-notify id=suminsurance-block-result style="display:none">
					<input type=hidden name=suminsurance id=suminsurance value="" >
					Застрахован на сумму <span id="bascet-suminsurance-result"></span> р. (В связи с участившимися случаями пропаж и вскрытия отправлений на Почте России во всех почтовыех отправлениях страхование производится на полную их стоимость)
				</div>
				<div>Страховка 4%: <span id=bascetinsurance-result></span></div>
				<div>Конверт или упаковка: <?=$PriceLatter; ?> р.</span></div>
				<div><input type="checkbox" checked name=postrulesview id=postrulesview> С указанными почтовыми тарифами ознакомлен. Заказ обязуюсь выкупить.</div>
			</div>
			<div><b>Итого:</b> <span id='allprice-result'></span> руб.</span></div>
			<?php if($can_pay_from_balance){?>
				<div> <input type="checkbox" checked onclick="form_user_bb();" id="from_ub" name="from_ub">Оплатить из бонус-счета(<?=$tpl['user']['balance']?> рубл.)</b></div>
			<?} else {?>
				<input type="hidden" id="from_ub" name="from_ub" value="0">
			<?}?>
			<div>Если Вы оставляли заявки на монеты в каталоге и они присутствуют в этом заказе, то Вы можете автоматически убрать их из рассылки(телефонного уведомления) о новых поступлениях этих монет
				<select name=deletesubscribecoins id=deletesubscribecoins>
					<option value=0>Оставить заявки</option>
					<option value=1>Убрать заявки</option>
				</select>
			</div>
			<input type="button"  class="button25" onclick="SubmitOrder();" value='Подтвердить заказ и перейти к оплате' style="width:350px">
			<input type="button"  class="button25" onclick="$('#user-compare-block').hide();$('#user-order').show();" value="Редактировать данные заказа">
		</div>
		<div id='user-order'>
			<div id='user-order-block'>
				<input type=hidden name=idadmin value=0>

				<h5>Заказчик</h5>
				<div class="web-form">
					<font color="red">*</font> <input type=text id=fio name=fio placeholder='ФИО' value="<?=$fio?>" size=50>
				</div>
				<div class="web-form">
					<font color="red">*</font><input id="userfio" type="text" name="userfio" value="<?=$userfio?>" size="40" placeholder='ФИО получателя'>
				</div>
				<div class="web-form">
					<font color="red">*</font><input id="phone" type="text" name="phone" value="<?=$phone?>" size="40" placeholder='Телефон с указанием кода'>
				</div>
				<div class="web-form">
					Данные требуются для подтверждения заказа
				</div>

				<div id='delivery-block'>
					<h5>Способ доставки</b></h5>
					<?

					foreach ($DeliveryName as $key=>$value){?>
						<div>
							<input type=Radio name=delivery id=delivery <?=(isset($DeliveryNameDisabled[$key])&&$DeliveryNameDisabled[$key]==1)?"disabled":""?> value=<?=$key?> <?=checked_radio($delivery,$key)?>
							onclick="ShowPayment(<?=$key?>);ShowOther(<?=$key?>);"><img src="<?=$cfg['site_dir']?>images/delivery<?=$key?>.jpg"><?=$value?>
							<?if($key==6){?> <br> <font color=red>Стоимость доставки пожалуйста узнавайте на сайте <a href=http://www.emspost.ru/ target=_blank>http://www.emspost.ru/</a> в разделе Тарифы и сроки и добавляйте к сумме заказа при его оплате</font>
							<?}
							if($key!=2){?> [<a href=#top onclick="window.open('<?=$cfg['site_dir']?>shopcoins/deliverydescription.php?delivery=<?=$key?>','_description','width=500,height=350,scrollbars=yes,top=250,left=450');return false;"><font color=red>?</font></a>]
							<?}?>
						</div>
						<?
					}
					if ($tpl['user']['user_id'] == 811 || $user_remote_address == "94.79.50.94") {?>
						<div>
							<input type=Radio name=delivery value=10 onclick="ShowPayment(10);"> Покупка в салоне продаж
						</div>
					<?}?>
				</div>
				<div id='metro-block' style="display:none">
					<div id=delivery-m>
						<b>Выбор метро</b>
						<select name=metro id='metro'><option value=0>Выбор метро</select>
						<div id=pricemetro></div>
					</div>
					<b>Дата и время</b>
					<select name=meetingdate id='meetingdate'><option value=0>Дата</select>
					<span id="meeting-from">c</span><select name=meetingfromtime id=meetingfromtime></select>
					<span id="meeting-to">по <select name=meetingtotime id=meetingtotime></select> </span>

					<div id=MetroGif><img src=<?=$cfg['site_dir']?>images/wait.gif border=0></div>

					<br><br>Более подробно смотрите раздел <a href=http://www.numizmatik.ru/shopcoins/delivery target=_blank>Оплата и доставка</a>
				</div>

				<div id="adress-block" style="display: none">
					<h5>Адрес доставки</h5>
					<input type=text name=postindex id='postindex' value="<?=$postindex?>" size=50 placeholder="Индекс" onblur="calculateOrder()">
					<textarea name=adress id=adress cols=50 rows=5><?=$adress?></textarea>
				</div>

				<div id=payment-block>
					<h5>Способ оплаты</b></h5>
					<div class="error" id='payment4-error' style="display:none">
						<b>Уважаемый пользователь!</b><br>
						К сожалению, способ оплаты “наложенный платеж” для вас был заблокирован администратором.<br>
						Возможно, это связано с тем, что от вас были возвраты наших посылок, отправляемых в ваш адрес наложенным платежом.<br>
						Если вы считаете, что это ошибка, свяжитесь с нашими сотрудниками по указанным контактам.
					</div>

					<div class="error" id='payment4-warning' style="display:none"> Уважаемые покупатели.<br>
						В последнее время участились случаи воровства на Почте РФ. В связи с этим просьба проверять содержимое наших почтовых отправлений при их получении на Почте РФ.<br>
						Наши почтовые отправления запечатаны фирменным скотчем следующего вида.<img src=../images/scotch.jpg border=0>
					</div>

					<!--if (delivery == 4){}-->
					<?
					foreach ($SumName as $key=>$value){
						if ($key!=5) {?>
							<div>
								<img src="<?=$cfg['site_dir']?>images/payment<?=$key?>.jpg"> <input type=Radio name=payment id=payment<?=$key?> value="<?=$key?>"  <?=checked_radio($payment,$key)?> disabled><?=$value?>
								[<a href=#top onclick="window.open('<?=$cfg['site_dir']?>shopcoins/paymentdescription.php?payment=<?=$key?>','_payment','width=500,height=350,scrollbars=yes,top=250,left=450');"><font color=red>?</font></a>]

								<? if ($key==6){?>	<font color=red><b>!!! У нас новые <a href=http://www.numizmatik.ru/shopcoins/delivery.php target=_blank>реквизиты</a>!!! Банка Пушкино больше не существует!</b></font>
								<?}?>
							</div>
						<?}
					}
					?>
				</div>

				<div class="clearfix">
					<a href=http://www.numizmatik.ru/shopcoins/aboutdiscont.php target=_blank>Система купонов на скидку >>></a><br>
					<?if($tpl['orderdetails']['coupons']){?>
						<a href='#' onclick="showCoupon();return false;">Скидочный купон </a>
					<?}?>
				</div>
				<?if($tpl['orderdetails']['coupons']){?>
					<div id="coupon-block" style="display: none">
						<div class="error" id="coupon-error"></div>
						Если у Вас есть купон(ы) на скидку и Вы желаете их использовать в данном заказе, введите код в нижеприведенной форме.
						<strong>Код купона:</strong>
						<input type=text name="code1" id='code1' value="" size=4 maxlength=4 > -
						<input type=text name="code2" id='code2' value="" size=4 maxlength=4 > -
						<input type=text name="code3" id='code3' value="" size=4 maxlength=4 > -
						<input type=text name="code4" id='code4' value="" size=4 maxlength=4 >
						<input type="hidden" value="" name="dis" id="dis">
						<? if($tpl['orderdetails']['coupons']['friends']){
						 $codetmp = $tpl['orderdetails']['coupons']['friends'];
						?>
						<br><b>Вам доступен купон на скидку по акции Приведи друга:<?=$codetmp[0]?>-<?=$codetmp[1]?>-<?=$codetmp[2]?>-<?=$codetmp[3]?>
						<?}?>

						<div id="CouponInfo" name="CouponInfo">
							<input type=button class=formtxt value="Проверить купон" onClick="checkFormCoupon();">
						</div>

					</div>
				<?}?>
				<h5>Комментарий к заказу</h5>
				<textarea name=OtherInformation id=OtherInformation cols=50 rows=5><?=$Otheinfo?></textarea>
				<hr>
				<div> Итоговая цена с учетом скидок и доставки: <b><span id="price-sum"><?=floor($sumallorder)?></span> руб.</b></div>
				<div>
					<input type="checkbox" id="acsess">Нажимая кнопку "Проверить заказ" я подтверждаю свою дееспособность, даю согласие на обработку своих персональных данных.
				</div>
				<div>

					<? if ($userstatus != 2) {
						if ($sumlimit>=$sumallorder|| $sumlimit== 0) {?>

							<input type="button"  class="button25" onclick="CheckFormOrher();" value='Проверить заказ' style="width:150px" id="CheckFormOrder">
						<?} else {?>
							<div class="error"><b>Внимание!</b> Вы привысили лимит своих невыкупленных заказов по общей сумме.<br>
								Для выяснения обстоятельств свяжитесь с администрацией по тел. +7-903-006-00-44 или  +7-915-002-22-23. С 10-00 до 18-00 МСК (по рабочим дням).
							</div>
						<?}
					}	else { ?>
						<div class="error"> <b>Внимание!</b> Ваш аккаунт был заблокирован администрацией.<br>
							Для выяснения обстоятельств свяжитесь с администрацией по тел. +7-903-006-00-44 или  +7-915-002-22-23. С 10-00 до 18-00 МСК (по рабочим дням).
						</div>
					<?}?>
				</div>
				<div class="clearfix">
					<a href='<?=$cfg['site_dir']?>shopcoins' class="left c-b">Продолжить покупки </a>
				</div>
			</div>
		</div>
	</form>
</div>
<!--Конец выбора параметров заказа-->
<!--Блок информации о заказе-->
<div style="float:right;width:40%;">
	<h5>Мой заказ</h5>
	<?foreach ($tpl['orderdetails']['ArrayShopcoinsInOrder'] as 	$rows ){ ?>
		<div>
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
				<br><a href="<?=$cfg['site_dir']?>shopcoins?catalog=<?=$rows["catalog"]?>&page=show&materialtype=<?=$rows["materialtype"]?>" target=_blank><?=$rows["name"]?></a>
				<br>Количество: <?=$rows["oamount"]?> шт.
			</td>
			<td><?=$rows["price"]*$rows["oamount"]?> рублей </td>
		</div>
		<?
	}?>

	
	<div>
		Стоимость заказа: <?=$bascetsum?> руб.
		<input type="hidden" id="bascetsum" name="bascetsum" value="<?=$bascetsum?>">
		<?if ($userstatus != 2 &&$mysumclient) {?>
			<br> Для постоянных клиентов : <?=$mysumclient?> руб.<br>
			Прим.: Постоянным клиентом считается пользователь, сделавший не менее 3-х заказов за календарный год под своим логином
		<?}?>
		<br>
		Стоимость доставки: <span id="price-delivery"> 0 </span>руб.
		</span>
	</div>
	<div>Итого: <b><span id="price-sum"><?=floor($sumallorder)?></span> руб.</b></div>
	<div> <b>Быстро! Удобно! Безопасно!</b></div>
	<div> <img src="<?=$cfg['site_dir']?>images/p1.jpg">Не сомневайтесь, все платежи проходят через защищенное 128-bit SSL соединение.</div>
	<div> Нет желаемого способа оплаты или доставки? Возникли вопросы? <br><br>
		Звоните бесплатно на 8-800-123-45-67 и мы поможем Вам!</div>
		
		<? if ($tpl['orderdetails']['alreadyBye']) {?>
		<div>
			<h5>На нижеприведенные монеты Вы уже делали заказы в нашем магазине ранее.</h5> 
			Если Вы желаете еще раз приобрести эти позиции, то просто продолжите оформление заказа. <br>
			Для корректировки заказа перейдите по ссылле:</strong> <a href='<?=$cfg['site_dir']?>shopcoins/?page=orderdetails'>Изменить содержимое заказа</a></strong>
			<?
			$a = array();
			foreach ($tpl['orderdetails']['alreadyBye'] as 	$rows ){
			   if(in_array($rows["shopcoins"],$a)) continue; ?>
				<div>
					<div class=tboard id=image<?=$rows["shopcoins"]?>>
						<div id=show<?=$rows["shopcoins"]?>></div>
						<div class='image_block'>
							<?if($rows['image_big']){?>
								<div id="image<?=$rows['catalog']?>" class='imageBig' style="display:none;position: absolute;">
									<img class="img_hover" src="<?=contentHelper::urlImage($rows['image_big'])?>" />
								</div>
							<?}?>
							<? echo contentHelper::showImage("smallimages/".$rows["image_small"],$rows["gname"]." | ".$rows["name"]);?>
						</div>
						<br><a href="<?=$cfg['site_dir']?>shopcoins?catalog=<?=$rows["shopcoins"]?>&page=show&materialtype=<?=$rows["materialtype"]?>" target=_blank><?=$rows["name"]?></a>
					</div>
				</div>
				<?
			$a[]=$rows["shopcoins"];
			}?>
		</div>
	<?}
	/*CheckFormDelivery()*/
	?>
</div>
<input type="hidden" id="timelimit" name="timelimit" value="<?=$timelimit?>">
<!--Конец блока информации о заказе-->
<script>
	$(document).ready(function() {
		$("#phone").mask("+7(999) 999-9999");
		ShowPayment(<?=$delivery?>);     
	});	
	<?
	echo $arraymetrojava;
	?>
</script>