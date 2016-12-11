<div  id="oneshopcoins<?=$rows["shopcoins"]?>" style="position:relative;" class="oneclick_notifications right ">
	<a href='#coinone<?=$rows["shopcoins"]?>' onclick="ShowOneClick(<?=$rows["shopcoins"]?>);return false;">
	Купить <?=(!isset($rows["is_mini"])&&$rows["materialtype"]==1)?"монету ":""?>в один клик</a>
	<!--<a name='coinone<?=$rows["shopcoins"]?>' ></a>-->	 	
	 
	  <div class="messages" id='messages<?=$rows["shopcoins"]?>' style='display:none;'>
	  <a style="display: inline;" onclick="HideOneClick(<?=$rows["shopcoins"]?>)"></a>
	  
	  <div class="frame-form oneclick_form" id='oneclick_form<?=$rows["shopcoins"]?>'>
<div class="yell_b">Форма быстрого заказа</div>

<div>Чтобы оформить заказ, укажите только свое имя и номер телефона.<br> Все остальные данные Вы можете сообщить менеджеру. <br>Форма "Быстрый заказ" предназначена для заказа одного вида товаров. <br> Если вы хотите продолжать выбор товаров, то используйте обычную функцию заказа - "В корзину"<br>&nbsp;</div>

<div class="error" id="oneshopcoins<?=$rows["shopcoins"]?>-error"></div>

    <b>Вы заказываете:</b>
    <div class="center">
    <div class="qw-form">
    <?
    
    if($rows["materialtype"]==3){	
		echo $rows['namecoins'].''.contentHelper::showImage('images/'.$rows["image"],$rows['namecoins'],array('alt'=>contentHelper::getAlt($rows)));
	} elseif ($rows["materialtype"]==5){
		echo $rows["name"].' '.contentHelper::showImage('images/'.$rows["image"],$rows["name"],array('alt'=>contentHelper::getAlt($rows)));	
    } else {
        echo $rows['namecoins'].' '.contentHelper::showImage('images/'.$rows["image"],'',array('alt'=>contentHelper::getAlt($rows)));		
    }?>
    </div>
    </div>

<div class="web-form">
    <div class="left">
        <label>Ваше имя:</label> 
     </div>
    <div class="right">
        <!--id="onefio" -->
        <input type="text" size="40" value="" name="onefio" >
    </div>
</div>
<div class="web-form">
    <div class="left">
        <label>Телефон:</label>
    </div>
    <div class="right">
        <!--id="onephone" -->
       <input type="text" class="phone" placeholder="+7(___) ___ __ __" name="onephone" value=""/>
    </div>
</div>
<div class="web-form">
<div class="center"><a class="button24 oneclickorder" onclick="AddOneClick(<?=$rows["shopcoins"]?>);return false;" >Оформить заказ</a></div>
</div>
</div>
	  
	  
	  
	  
	   </div>
</div>  

