<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<!--Блок выбора параметров заказа-- -->
<form name=resultform id=resultform method=post action="<?=$cfg['site_dir']?>shopcoins/submitorder.php">

<div style="width:60%;float:left;font-weight:400;">
	
		<input type="hidden" id="userstatus" name="userstatus" value="<?=$userstatus?>">
		<div id='user-compare-block' style="display:none;font-weight:400;">
			<h3>Проверить заказ</h3>
			<p style="color:red;font-weight:bold;">Уважаемый пользователь! Проверьте внимательно данные о вашем заказе</p>
			<div class="error" id='error-order'></div>
			<br>
			<div style="font-size:14px;font-weight:bold;">Заказ № <?=$shopcoinsorder?></div>
			<br>
			<div>ФИО: <span id='fio-result'></span></div><br>
			<div>ФИО получателя: <span id='userfio-result'></span></div><br>
			<div>Телефон: <span id=phone-result></span></div><br>
			<div>Способ доставки: <span id=delivery-result></span></div><br>
			<div id='metro-block-result' style="display:none">
				Метро: <span id=metro-result></span>
			</div>
			<div id='metro-price-block-result' style="display:none">
				Цена доставки: <span id=metro-price-result></span>  р.<br>
			</div>
			<div id='meetingdate-block-result' style="display:none">
				Дата: <span id=meetingdate-result></span><br>
			</div>

			<div id='meetingfromtime-block-result' style="display:none">
				Время: с <span id='meetingfromtime-result'></span> по <span id='meetingtotime-result'></span>         </div><br>

			<div>Способ оплаты: <span id='payment-result'></span></div><br>
			<div id='postindex-block-result' style="display:none">
				Индекс: <span id=postindex-result></span><br>
			</div>

			<div id='adress-block-result' style="display:none">
				Адрес доставки: <span id=adress-result></span><br>
			</div>
			
			<div id='coupon-block-result' style="display:none">
			<?
			if($tpl['user']['vip_discoint']){?>
			    Скидка как VIP- клиента: <font color="red"> <?=$tpl['user']['vip_discoint']?> %<span id=coupon-result style="display:none"></font></span><br>
			    Размер скидки: <font color="red"><span id=discountcoupon-result></span> р.</font><br>
			<?} else {?>
			    Скидочный купон: <font color="red"> <span id=coupon-result></font></span><br>
				Скидка по купону: <font color="red"> <span id=discountcoupon-result></span> р.</font><br>
			<?}?>				
			</div>
			<div>
				Комментарий к заказу: <span id=OtherInformation-result></span><br>
			</div>
			<div>Сумма заказа сучетом скидок: <font color="red"><b> <span id=bascetsum-result></span> р.</b></font></div><br>
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
				<br>
			</div>
			<div>Итого: <font color="red"><b><span id='allprice-result'></span> руб.</b></font></span></div><br>
			<?php if($can_pay_from_balance){?>
				<div> <input type="checkbox" checked id="from_ubb" name="from_ubb" value="1">Оплатить из бонус-счета(<?=$tpl['user']['balance']?> рубл.)</div>
			<?} else {?>
				<input type="hidden" id="from_ubb" name="from_ubb" value="0">
			<?}?>
			<br>
			<div>Если Вы оставляли заявки на монеты в каталоге и они присутствуют в этом заказе, то Вы можете автоматически убрать их из рассылки(телефонного уведомления) о новых поступлениях этих монет
				<br><br>
				<select name=deletesubscribecoins id=deletesubscribecoins>
					<option value=0>Оставить заявки</option>
					<option value=1>Убрать заявки</option>
				</select>
			</div>
			<br>
			<?
		if($tpl['orderdetails']['admins']){	?>
			<select name=idadmin id=idadmin >
			<option value=0> Принял заказ</option>
			<?
			foreach ($tpl['orderdetails']['admins'] as $rowst) {?>			
				<option value="<?=$rowst['TimeTableUser']?>"><?=$rowst['Fio']?></option>
			<?}?>
			</select>	<br><br>		
		<?}	else echo "<input type=hidden name=idadmin value='".$idadmin."'>";		
		?>
			
			<input type="submit"  class="button25" value='Подтвердить заказ и перейти к оплате' style="width:350px" onclick="$('.bg_shadow').show();">
			<input type="button"  class="button25" onclick="$('#user-compare-block').hide();$('#user-order').show();" value="Редактировать данные заказа">
		</div>
		<div id='user-order'>
			<div id='user-order-block'>
				<div class="bordered_oder">
    				<h5>Заказчик</h5>
    				<div class="web-form">
    					<font color="red">* </font> <input type=text id=fio name=fio placeholder='ФИО' value="<?=$fio?>" size=50>
    				</div>
    				<div class="web-form">
    					<font color="red">* </font><input id="userfio" type="text" name="userfio" value="<?=$userfio?>" size="40" placeholder='ФИО получателя'>
    				</div>
    				<div class="web-form">
    					<font color="red">* </font><input id="phone" type="text" name="phone" value="<?=$phone?>" size="40" placeholder='Телефон с указанием кода'>
    				</div>
    				<div class="web-form">
    					Данные требуются для подтверждения заказа
    				</div>
                </div>
				<div id='delivery-block' class="bordered_oder">
					<h5>Способ доставки</h5>
					<?

					foreach ($DeliveryName as $key=>$value){?>
						<div>
							<input type=Radio name=delivery id="delivery<?=$key?>" <?=(isset($DeliveryNameDisabled[$key])&&$DeliveryNameDisabled[$key]==1)?"disabled":""?> value=<?=$key?> <?=checked_radio($delivery,$key)?>
							onclick="ShowPayment(<?=$key?>);ShowOther(<?=$key?>);"> <img src="<?=$cfg['site_dir']?>images/delivery<?=$key?>.jpg"> <label for="delivery<?=$key?>"><?=$value?></label>
							<?if($key==6){?> 
							<br> <span style="font-size:11px;">Стоимость доставки пожалуйста узнавайте на сайте<br> 
								<a href=http://www.emspost.ru/ target=_blank>http://www.emspost.ru/</a> <br>
								в разделе Тарифы и сроки и добавляйте к сумме заказа при его оплате</span>
							<?}
							if($key!=2){?> [<a href=#top onclick="window.open('<?=$cfg['site_dir']?>shopcoins/deliverydescription.php?delivery=<?=$key?>','_description','width=500,height=350,scrollbars=yes,top=250,left=450');return false;"><font color=red>?</font></a>]
							<?}?>
						</div>
						<?
					}
					if ($tpl['user']['user_id'] == 811 || $user_remote_address == "94.79.50.94") {?>
						<div>
							<input type=Radio name="delivery" id="delivery10" value=10 onclick="ShowPayment(10);"> <label for="delivery10">Покупка в салоне продаж</label>
						</div>
					<?}?>
				
    				<div id='metro-block' style="display:none">
    				    <br>			
    				    <div id=metro-error></div>	
    					<div id=delivery-m>
    						<b>Выбор метро</b>
    						<select name=metro id='metro'><option value=0>Выбор метро</select>
    						<div id=pricemetro></div>
    					</div>
    					<b>Дата и время</b>
    					<select name=meetingdate id='meetingdate'><option value=0>Дата</select>
    					<span id="meeting-from">c </span><select name=meetingfromtime id=meetingfromtime></select>
    					<span id="meeting-to">по <select name=meetingtotime id=meetingtotime></select> </span>
    
    					<div id=MetroGif><img src=<?=$cfg['site_dir']?>images/wait.gif border=0></div>
    
    					<br><br>Более подробно смотрите раздел <a href=http://www.numizmatik.ru/shopcoins/delivery target=_blank>Оплата и доставка</a>
    				</div>
    
    				<div id="adress-block" style="display: none">
    					<h5>Адрес доставки</h5>
    					<input type=text name=postindex id='postindex' value="<?=$postindex?>" size=50 placeholder="Индекс" onblur="calculateOrder()">
    					<textarea name=adress id=adress cols=50 rows=5><?=$adress?></textarea>
    				</div>
                </div>
                
				<div id=payment-block class="bordered_oder">
					<h5>Способ оплаты</h5>
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
								<img src="<?=$cfg['site_dir']?>images/payment<?=$key?>.jpg"> <input type=Radio name=payment id="payment<?=$key?>" value="<?=$key?>"  <?=checked_radio($payment,$key)?> disabled><label for="payment<?=$key?>"><?=$value?></label>
								[<a href=#top onclick="window.open('<?=$cfg['site_dir']?>shopcoins/paymentdescription.php?payment=<?=$key?>','_payment','width=500,height=350,scrollbars=yes,top=250,left=450');"><font color=red>?</font></a>]								
							</div>
						<?}
					}
					?>
				</div>

				<div class="clearfix">
					<a href=http://www.numizmatik.ru/shopcoins/aboutdiscont.php target=_blank>Система купонов на скидку >>></a>				
				</div>
				<?if($tpl['orderdetails']['coupons']){?>
					<div id="coupon-block">
						
						Если у Вас есть купон(ы) на скидку и Вы желаете их использовать в данном заказе, введите код в нижеприведенной форме.<br>
						<div>
						<? foreach ($tpl['orderdetails']['coupons'][1] as $i=>$coupon){?>
							<div>
    							<div class="error" id="<?=$i?>_coupon-error" style="text-align:left"></div>
        						<strong>Код купона:</strong>
        						<input type=text name="<?=$i?>_code1" id='<?=$i?>_code1' value="" size=4 maxlength=4 > -
        						<input type=text name="<?=$i?>_code2" id='<?=$i?>_code2' value="" size=4 maxlength=4 > -
        						<input type=text name="<?=$i?>_code3" id='<?=$i?>_code3' value="" size=4 maxlength=4 > -
        						<input type=text name="<?=$i?>_code4" id='<?=$i?>_code4' value="" size=4 maxlength=4 >         						
        						<input type="hidden" value="" name="<?=$i?>_dis" id="<?=$i?>_dis">
    						</div>
    						
    						<?}?>  
    						<input type="hidden" value="<?=count($tpl['orderdetails']['coupons'][1])?>" name="coupon_count" id="coupon_count">  	
    											
    						<? if($tpl['orderdetails']['coupons']['friends']){
    						 $codetmp = $tpl['orderdetails']['coupons']['friends'];    						?>
    						
    						<br><b>Вам доступен купон на скидку по акции Приведи друга:<?=$codetmp[0]?>-<?=$codetmp[1]?>-<?=$codetmp[2]?>-<?=$codetmp[3]?></b>
    						<?}?>&nbsp;&nbsp;
                        </div>
						<div id="CouponInfo" name="CouponInfo">
							<input type=button class=formtxt value="Проверить купон(ы)" onClick="checkFormCoupon(<?=count($tpl['orderdetails']['coupons'][1])?>);">
						</div>

					</div>
				<?}?>
				<h5>Комментарий к заказу</h5>
				<textarea name=OtherInformation id=OtherInformation cols=50 rows=5><?=$Otheinfo?></textarea>
				<hr>
				<div> 
				<br>
				Сумма заказа:<font color="red"> <b><?=$bascetsum?></b></font><br>
				
				<? if($tpl['user']['vip_discoint']){?>
               <br> Ваша скидка как VIP-клиента: <b><?=$tpl['user']['vip_discoint']?> %</b> <br>
                <br>Итого c учетом скидки (без суммы доставки): <b><?=($bascetsum-floor($tpl['user']['vip_discoint']*$bascetsum/100))?> рублей</b> <br>
                <?}?>
				<br><font color="red">Итоговая цена с учетом скидок и доставки: <b><span id="price-sum"><?=($bascetsum-floor($bascetsum*$tpl['user']['vip_discoint']/100))?></span> руб.</b></font></div>
				<br>
				<div>
					<input type="checkbox" id="acsess" name=acsess><label for="acsess">Нажимая кнопку "Проверить заказ" я подтверждаю свою дееспособность, даю согласие на обработку своих персональных данных.</label>
					<div id=acsess-error name=acsess-error></div>
				</div>
				<br>
				<div class="order-cart">

					<? if ($userstatus != 2) {
						if ($sumlimit>=$sumallorder|| $sumlimit== 0) {?>
							<input type="submit"  class="button25" value='Проверить заказ' id="CheckFormOrder">
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
				<br>
				<div class="clearfix">
					<a href='<?=$cfg['site_dir']?>shopcoins' class="left c-b">Продолжить покупки </a>
				</div>
			</div>
		</div>	
</div>
</form>
<!--Конец выбора параметров заказа-->
<!--Блок информации о заказе-->
<div style="float:right;width:40%;font-weight:400;">
	<h5>Мой заказ</h5>
	<?foreach ($tpl['orderdetails']['ArrayShopcoinsInOrder'] as 	$rows ){ ?>
		<div style="border:1px solid #cccccc; margin:15px;padding:10px;margin-left:0px;">
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
	<br>
		Стоимость заказа: <font color="red"><b><?=$bascetsum?></b></font> руб.
		<input type="hidden" id="bascetsum" name="bascetsum" value="<?=$bascetsum?>">
		<?if ($userstatus != 2 &&$mysumclient) {?>
			<br> Для постоянных клиентов : <?=$mysumclient?> руб.<br>
			Прим.: Постоянным клиентом считается пользователь, сделавший не менее 3-х заказов за календарный год под своим логином
		<?}?>
		<br>
		<!--Стоимость доставки: <span id="price-delivery"> 0 </span>руб.-->
		
	</div>
	<!--<div>Итого: <b><span id="price-sum"><?=floor($bascetsum)?></span> руб.</b></div>-->
	<br>
	<div> <b>Быстро! Удобно! Безопасно!</b></div><br>
	<div style="border:1px solid #cccccc; padding:10px;"> 
		<table>
			<tr>
				<td><img src="<?=$cfg['site_dir']?>images/p1.jpg"></td>
				<td>Не сомневайтесь, все платежи проходят через защищенное 128-bit SSL соединение.</td>
			</tr>
		</table>
	
	</div>
	<br>
	<div> Нет желаемого способа оплаты или доставки? Возникли вопросы? <br><br>
		Звоните бесплатно на 8-800-123-45-67 и мы поможем Вам!</div>
		
		<? if ($tpl['orderdetails']['alreadyBye']) {?>
		<div>
			<h5>На нижеприведенные монеты Вы уже делали заказы в нашем магазине ранее.</h5> <br>
			Если Вы желаете еще раз приобрести эти позиции, то просто продолжите оформление заказа. <br><br>
			Для корректировки заказа перейдите по ссылке:<br></strong> <a href='<?=$cfg['site_dir']?>shopcoins/?page=orderdetails'>Изменить содержимое заказа</a></strong>
			<br><br>
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
		$("#phone").mask("+9(999) 999-9999");
		ShowPayment(<?=$delivery?>);   

		$("#resultform").validate({
         // Specify the validation rules
        rules: {
            fio: "required",
            userfio: "required",          
            phone: "required", 
            acsess: "required",
            postindex:{
                required: function(element) {                   
                    var d = $('[name=delivery]:checked').val();
                    if (d == 4 || d == 5 || d == 6) return true; 
                    return false;            
		        }
		    },
		    adress:{
                required: function(element) {                   
                    var d = $('[name=delivery]:checked').val();
                    if (d == 3 || d == 4 || d == 5 || d == 6) return true; 
                    return false;            
		        }
		    },
            meetingdate:{
                required: function(element) {                   
                    var d = $('[name=delivery]:checked').val();
                    if (d == 1 || d == 2 || d == 3) return true; 
                    return false;            
		        },
		        min:{
		            param: 1
		        }
            },
            metro: {
                required: function(element) {
                    var d = $('[name=delivery]:checked').val();
                    if (d== 1 || d == 3) return true;                     
                    return false;            
		        }
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "acsess" ){
                error.insertAfter("#acsess-error");
            } else if (element.attr("name") == "meetingdate" ){
                error.insertBefore("#metro-error");
            } else
                error.insertAfter(element);
            /*else if  (element.attr("name") == "phone" )
                error.insertAfter(".some-other-class");
            */
        },
        // Specify the validation error messages
        messages: {
            fio: " Введите ФИО",
            userfio: "Введите ФИО получателя",
            phone: " Укажите телефон",            
            acsess: " Вы должны согласится с правилами",
            meetingdate:" Выберите дату встречи",
            metro:" Укажите метро",
            adress: " Укажите адрес",
            postindex: " Укажите корректный индекс",
        },        
      submitHandler: function(form) {
      	  if($('#user-compare-block').is(':visible')){
      	  	 SubmitOrder();
      	  } else {
          	CheckFormOrher();         
      	  }
          return false;
          //form.submit();
      }
     }); 
	});	
	<?
	echo $arraymetrojava;
	?>
</script>