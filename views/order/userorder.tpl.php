<!--Блок выбора параметров заказа-- -->
<div style="width:60%;float:left">
    <form name=resultform method=post id=resultorderform action="<?=$cfg['site_dir']?>'shopcoins/submitorder.php">
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
            Скидочный купон: <span id=coupon-result></span> 
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
        <input type="button"  class="button25" onclick="$('#user-compare-block').hide();$('#user-order').show();" value="Редактировать данные заказа">
    	<input type="button"  class="button25" onblur="SubmitOrder();" value='Подтвердить заказ и перейти к оплате' style="width:350px">
			
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
		<font color="red">*</font><input id=email type=text name=email class=formtxt value="<?=$email?>" size=50 placeholder='Email'>
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
		<input type=text name=postindex id='postindex' value="<?=$postindex?>" size=50 placeholder="Индекс">
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
		<?if($iscoupon&&$iscoup){?>
		<a href='#' onclick="showCoupon();return false;">Скидочный купон </a>
		<?}?>
		</div>
		<?if($iscoupon&&$iscoup){?>
		<div id="coupon-block" style="display: none">

		Если у Вас есть купон(ы) на скидку и Вы желаете их использовать в данном заказе, введите код в нижеприведенной форме.
		<strong>Код купона:</strong>
		<input type=text name="code1" id='code1' value="<?=$codetmp[0];?>" size=4 maxlength=4 > - 
		<input type=text name="code2" id='code2' value="<?=$codetmp[1];?>" size=4 maxlength=4 > -
		<input type=text name="code3" id='code3' value="<?=$codetmp[2];?>" size=4 maxlength=4 > - 
		<input type=text name="code4" id='code4' value="<?=$codetmp[3];?>" size=4 maxlength=4 >
		<? echo (strlen($codetmp[3])==4?"&nbsp;<b>Введен код купона на скидку полученного Вами по акции Приведи друга.</b>":"");?>
		<div id="CouponInfo" name="CouponInfo">
		<input type=button class=formtxt value="Проверить купон" onClick="сheckFormCoupon();">
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
				 <? /*if (ArrayName.length>1) {

						<tr><td bgcolor=#FFCC66 class=txt colspan=2><strong>На нижеприведенные монеты Вы уже делали заказы в нашем магазине ранее. Если Вы желаете еще раз приобрести эти позиции, то просто продолжите оформление заказа. Для корректировки заказа перейдите по ссылле:</strong> <a href=./index.php?page=orderdetails>Изменить содержимое заказа</a></tr>';
						for (i=1;i<ArrayName.length;i++){

							<tr><td bgcolor=#FFCC66 class=txt><strong>Страна: '+ArrayNameGroup[i]+'<br>Номинал: '+ArrayName[i]+'<br>Год: '+ArrayYear[i]+'</strong><td bgcolor=#FFCC66>';
							if (ArrayImages[i]!='none') {
								<iframe src=./images/'+ArrayImages[i]+' valign=top frameborder=1 height=150 width=230 scrolling=no></iframe></tr>';
							}
							else {
								<strong>Нет изображения</strong></tr>';
							}
							if (i>=5)
								break;
						}
					}CheckFormDelivery()*/?>

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
	<?foreach ($tpl['orderdetails']['ArrayShopcoinsInOrder'] as 	$rows ){?>
	<div>
		<td class=tboard id=image<?=$rows['catalog']?>>
			<div id=show<?=$rows['catalog']?>></div>
			<?
			echo contentHelper::showImage("smallimages/".$rows["image_small"],$rows["gname"]." | ".$rows["name"],array('onMouseover'=>"ShowMainCoins(\"{$rows['catalog']}\");","onMouseout"=>"NotShowMainCoina(\"{$rows['catalog']}\");"));
			?>
			<br><a href="<?=$cfg['site_dir']?>shopcoins?catalog=<?=$rows["catalog"]?>&page=show&materialtype=<?=$rows["materialtype"]?>" target=_blank><?=$rows["name"]?></a>
			<br>Количество: <?=$rows["oamount"]?> шт.
		</td>
		<td><?=$rows["price"]*$rows["oamount"]?> рублей </td>
	</div>
	<?
	}?>
	<div>
		Стоимость заказа: <?=$bascetsum?> руб.
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
</div>
<!--Конец блока информации о заказе-->
<script>
function ShowMetro(delivery){
    if (delivery == 1 || delivery == 2 || delivery == 3 || delivery == 7) {
		$('#metro-block').show();
		$("#metro").unbind("change");
		if(delivery==1) {
		    $('#delivery-m').show();		    
			$('#metro').bind("change", function(event) {
				ShowMetro(1);				
			} );
			
			$('#meeting-from').hide();
			$('#meeting-to').hide();

		} else if(delivery==3){
		    $('#delivery-m').show();
			$('#metro').bind("change", function(event){
				ShowPriceMetro();
			} );
			$('#meeting-from').show();
			$('#meeting-to').show();
		} else {
		    $('#delivery-m').hide();
			$('#meeting-from').show();
			$('#meeting-to').show();
		}
	} else {
	     $('#metro-block').hide();
	}
	var metroid = 0;
	
	if (delivery == 3) {
	    url = 'shopcoins/showallmetro.php';		    
	}
	if (delivery == 1)	{
	    url = 'shopcoins/showringmetro.php';
		metroid =$('#metro').val();
	}	
	
	if (delivery == 2 || delivery == 7)	url = 'shopcoins/showinoffice.php';

	$.ajax({	
    	    url: url, 
    	    type: "GET",
    	    data:{timelimit: <?=$timelimit?>, delivery: delivery,metroid:metroid},         
    	    dataType : "json",                   
    	    
    	    success: function (data, textStatus) {

		        var meetingfromtime = $('#meetingfromtime');
		        var meetingtotime = $('#meetingtotime');
		        meetingfromtime.empty();
		        meetingtotime.empty();
		        for (var i = 0; i < data.TimesArray.length; i++) {
			        meetingfromtime.append($("<option>").attr('value',data.TimesArray[i].val).text(data.TimesArray[i].text));
			        meetingtotime.append($("<option>").attr('value',data.TimesArray[i].val).text(data.TimesArray[i].text));
		        }
    	        if (delivery==2) {
		              // ShowInOffice();
		              var sel = $('#meetingdate');
		              for (var i = 0; i < data.DaysArray.length; i++) {
	                       sel.append($("<option>").attr('value',data.DaysArray[i].val).text(data.DaysArray[i].text));
		              }
                } else if(delivery==7){                
                	ShowInOfficeMetro();
                } else if(delivery==1||delivery==3){
                    if (metroid<1 || metroid>12) {
                        var sel = $('#metro');

        	            for (var i = 0; i < data.MetroArray.length; i++) {
                             sel.append($("<option>").attr('value',data.MetroArray[i].val).text(data.MetroArray[i].text));                    
        	            }
    	                var sel = $('#meetingdate');
    	                for (var i = 0; i < data.DaysArray.length; i++) {
                           sel.append($("<option>").attr('value',data.DaysArray[i].val).text(data.DaysArray[i].text));                    
    	               }
                  }
              } 
    	    
              if ('<?=$timelimit?>'> 0 && '<?=$timelimit?>' < 60) {
	              $("#MetroGif").html('<br><br><table width=100% cellpadding=2 cellspacing=1 border=0 align=center><tr><td class=txt bgcolor=#EBE4D4 valign=top><font color=red><b>Внимание!</b></font> Вам введен лимит по времени выкупа заказов. Для выяснения обстоятельств свяжитесь с администрацией по тел. +7-903-006-00-44 или  +7-915-002-22-23. С 10-00 до 18-00 МСК (по рабочим дням).</td></table>');
              } else {
                  $("#MetroGif").html('');
              }
    	    }
         });      
	
}

function ShowPayment(delivery){	
    $('#payment1').prop("disabled",true);
    $('#payment2').prop("disabled",true);
    $('#payment3').prop("disabled",true);
    $('#payment4').prop("disabled",true);
    $('#payment5').prop("disabled",true);
    $('#payment6').prop("disabled",true);
    $('#payment7').prop("disabled",true);
    $('#payment8').prop("disabled",true);
    
    if(delivery==4){
        $('#payment4-warning').show();
        if($('#userstatus').val()==2) $('#payment4-error').show();
    } else {
        $('#payment4-error').hide();
        $('#payment4-warning').hide(); 
    }
    
    if(delivery!=4){
	   ShowMetro(delivery);
    } else {
        $('#metro-block').hide();
    }
	
	if (delivery == 1 || delivery == 2 || delivery == 3 || delivery == 7) {		
	   $('#payment2').prop("disabled",false);		
	} 
	
	if (delivery == 10) {
		$('#payment2').prop("disabled",false);		
	}

	if (delivery == 4) {	    
		//нужно показать вес, содержимое.
		if ($('#userstatus').val() != 0) { 
			$('#payment1').prop("disabled",true);	
		} else {
			$('#payment1').prop("disabled",false);	
		}
		$('#payment3').prop("disabled",false);		
		$('#payment4').prop("disabled",false);		
		$('#payment6').prop("disabled",false);			
		<? if ($bascetsum>=3000)?> $('#payment7').prop("disabled",false);	
		$('#payment8').prop("disabled",false);		
	} 
	
	if (delivery == 5) {
		$('#payment5').prop("disabled",false);	
		<? if ($bascetsum>=3000)?>  $('#payment6').prop("disabled",false);	
	}
	
	if (delivery == 6){
		$('#payment5').prop("disabled",false);	
		<? if ($bascetsum>=3000) {?>
		   $('#payment6').prop("disabled",false);	
		   $('#payment8').prop("disabled",false);
	   <?}?>
	}
	if($('#payment'+$('[name=payment]:checked').val()).prop("disabled")){
	    $('#payment'+$('[name=payment]:checked').val()).prop("checked",false);
	    $('[name=payment]').each(function (i) {	        
	        if(!$(this).prop("disabled")){
	            $(this).prop("checked",true);
	            return false;
	        }
	    });
	
	}
}
function showCoupon(){
	if(!$('#coupon-block').is(':visible')){
		$('#coupon-block').show();
	} else {
		$('#coupon-block').hide();
	}
	return false;
}

ShowPayment(<?=$delivery?>);
ShowOther(<?=$delivery?>);

function ShowOther(delivery){
	//3 - доставка в офис
	if (delivery == 3 || delivery == 4 || delivery == 5 || delivery == 6){
		$('#adress-block').show();
		if(delivery == 3){
			$('#postindex').hide();
		} else {
			$('#postindex').show();
		}
	} else {
		$('#adress-block').hide();
	}


}
function ShowPriceMetro() {
	$("#pricemetro").html(' доставка: ' + MetroPrice[$('#metro').val()] + ' руб.');
}

function CheckCorrectFormOrher(){
	var error = "";
    var delivery = $('[name=delivery]:checked').val();
	if (!$('#fio').val()){
		error +="Введите ФИО<br>";
	}
	var userfio = $('#userfio').val();
	if (!userfio){
		error +="Введите ФИО получателя<br>";
	} else {
		var pr = 0;
		var tig = 0;
		var sp = 0;
		for (i=0; i < userfio.length; i++) {
			if ( userfio.substring(i,(i+1)) == ' ') {
				if (pr == 1) {
					pr = 0;
					sp++;
				}
			} else {
				pr = 1;
			}
		}
		if ( sp<2 || (sp==2 && pr==0)) {
			error +="Введите ФИО получателя, разделяя их пробелом<br>";
		}
	}

	if ($('#phone').val().length < 5){
		error +="Введите телефон<br>";
	}
	if ($('#email').val().length < 5){
		error +="Введите email<br>";
	}
	if (delivery == 1 || delivery == 3) {
        if (!$('#metro').val()||$('#metro').val()==0){
    		error +="Укажите метро<br>";
    	}
    }
   
	if (delivery == 1 || delivery == 2 || delivery == 3) {
        if (!$('#meetingdate').val()||$('#meetingdate').val()==0){
    		error +="Выберите дату встречи<br>";
    	}
    }
    
   
	var postindex = '';
	if (delivery == 4 || delivery == 5 || delivery == 6) {
		if (!$('#postindex').val()){
			error +="Введите индекс<br>";
		}
	}

	if (delivery == 3 || delivery == 4 || delivery == 5 || delivery == 6){
		if (!$('#adress').val()){
			error +="Введите адрес<br>";
		}
	}
	if(!$('#acsess').prop("checked")){
		error +="Вы должны согласиться с правилами<br>";
	}
	return error;
}

function CheckFormOrher(){
    var error = CheckCorrectFormOrher();
    var delivery = $('[name=delivery]:checked').val();
    if(error){
	    $("#MainBascet").html(error);
	    $("#MainBascet").dialog({
		    position: {
			    my: 'top',
			    at: 'top',
			    of: $("#CheckFormOrder")
		    },
		    modal:true
	    });
    } else {
	    $.ajax({
		    url: site_dir+'shopcoins/postcalculate.php',
		    type: "POST",
		    data: {
			    postindex: $('#postindex').val(),
			    'delivery': delivery,
			    'payment': $('[name=payment]:checked').val(),
			    'metro': $('#metro').val(),
			    'meetingdate': $('#meetingdate').val(),
			    'meetingfromtime': $('#meetingfromtime').val(),
			    'meetingtotime': $('#meetingtotime').val()
		    },
		    dataType: "json",
		    success: function (data, textStatus) {
			    $('#user-compare-block').show();
			    $('#user-order').hide();
 
	            errorvalue =data.error;		            
            	bascetamount = data.bascetamount;
            	bascetsum = data.bascetsum;
            	bascetweight = data.bascetweight;
            	discountcoupon = data.discountcoupon;
            	SumName = data.SumName;
            	payment = $('[name=payment]').val();
            	 
                $('#fio-result').text($('#fio').val());
                $('#userfio-result').text($('#userfio').val());	
	            $('#phone-result').text($('#phone').val());	
	            $('#delivery-result').text(data.DeliveryName);	
	            
	            metrovalue = data.metro;	
            	if (metrovalue){
            	    $('#metro-block-result').show();
            	    $('#metro-result').text(metrovalue);		
            	}
	
                meetingdatevalue = data.meetingdate;
                if (meetingdatevalue){                    
                	$('#meetingdate-result').text(meetingdatevalue);                		
                }
	            
                meetingfromtimevalue = data.meetingfromtime;
                if (meetingfromtimevalue) {
                    $('#meetingfromtime-block-result').show();
            	    $('#meetingfromtime-result').text(meetingfromtimevalue);
                }
                $('#payment-result').text(SumName);	
                
                if (data.postindex) {
                    $('#postindex-block-result').show();
            	    $('#postindex-result').text(data.postindex);
                }
                if (data.adress) {
                    $('#adress-block-result').show();
            	    $('#adress-result').text(data.adress);
                }
                $('#OtherInformation-result').text($('#OtherInformation').val());
                $('#bascetsum-result').text(bascetsum);
                var sum = bascetsum;
                if (data.discountcoupon){
                    $('#coupon-block-result').show();                    
                    $('#coupon-result').text($('#code1').val()+'-'+$('#code2').val()+'-'+$('#code3').val()+'-'+$('#code4').val());
            	    $('#discountcoupon-result').text(discountcoupon);
                    sum = eval(parseInt(bascetsum) + parseInt(discountcoupon));          		
            	} 
            	$('#allprice-result').text(bascetsum);
                if (data.metroprice){
                    $('#metro-price-block-result').show();
            	    $('#metro-price-result').text(data.metroprice);
            	    $('#allprice-result').text(eval(parseInt(sum)+parseInt(data.metroprice)));
                }
            	
        
                if(!data.error){
            	    $('#post-block-result').show();
            	    $('#post-zone-result').text(data.PostZoneNumber+(data.PostRegion?'('+data.PostRegion+')':'')); 
            	    $('#bascetpostweight-result').text(data.bascetpostweight);  
            	    $('#postzoneprice-result').text(data.PostZonePrice); 
            	    
            	    if (payment == 3 || payment == 4 || payment == 5 || payment == 6){
                        $('#suminsurance').val(data.bascetinsurance); 
                        $('#bascet-suminsurance-result').text(bascetsum); 
                    }
                    $('#bascetinsurance-result').text(data.bascetinsurance); 
                    $('#allprice-result').text(data.PostAllPrice);  
                }
		    }
        });
    }
}

function SubmitOrder(){
    var error = CheckCorrectFormOrher();
    if(!$('#postrulesview').prop("checked")){
		error +="Вы должны согласиться с правилами<br>";
	}
	
    if(error){
        $('#error-order').text(error);
    } else {
         $('#resultorderform').submit();
    }
}
<?
echo $arraymetrojava;
?>
</script>

