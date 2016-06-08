<div  id="oneshopcoins<?=$rows["shopcoins"]?>" style="position:relative; text-align:right;" class="oneclick_notifications">
	<a href='#coinone<?=$rows["shopcoins"]?>' onclick="ShowOneClick(<?=$rows["shopcoins"]?>,300);return false;">
	Купить в один клик</a>
	<a name='coinone<?=$rows["shopcoins"]?>' ></a>	 	
	 
	  <div class="messages" id='messages<?=$rows["shopcoins"]?>' style='display:none;'>
	  <a id="fancybox-close" style="display: inline;" onclick="HideOneClick(<?=$rows["shopcoins"]?>)"></a>
	  
	  <div class="frame-form" id='oneclick_form<?=$rows["shopcoins"]?>'>
<h1 class="yell_b">Форма быстрого заказа</h1>
<div class="error" id="oneshopcoins<?=$rows["shopcoins"]?>-error"></div>
<p>
    Вы заказываете: <b><?
    if(!isset($rows['namecoins'])) $rows['namecoins'] = $rows['name'];
    if($rows["materialtype"]==3){	
		echo $rows['namecoins'].'<center>'.contentHelper::showImage('images/'.$rows["image"],$rows['namecoins'])."</center>";
	} elseif ($rows["materialtype"]==5){
		echo $rows["name"].' '.'<center>'.contentHelper::showImage('images/'.$rows["image"],$rows["name"])."</center>";	
    } else {
        echo $rows['namecoins'].' '.'<center>'.contentHelper::showImage('images/'.$rows["image"],'')."</center>";		
    }?></b>
</p>   
    <div  class="web-form">
        <input type="text" size="32" value="" name="onefio" id="onefio" placeholder='Ваше имя'>
    </div>

<div>
        <label>Телефон:</label>
 </div>
    <div class="web-form">
       <input type="text" class="phone" placeholder="+7(___) ___ __ __" id="onephone" name="onephone" size="32" autofocus value=""/>
    </div>

<div class="web-form">
<center><a class="button24 oneclickorder" onclick="AddOneClick(<?=$rows["shopcoins"]?>);return false;" >Оформить заказ</a></center>
</div>
</div>	  
	  
	   </div>
</div>