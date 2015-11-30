<div class="frame-form">
<h1 class="yell_b"> Заказать обратный звонок</h1>
<? if($tmp['addcall']['send_status']){?>
    <div class="error" id="addcall-error">Обратный звонок Вами заказан. <br> Вам перезвонит наш сотрудник.</div>
    
<?} else {?>
<p>
Для заказа обратного звонка введите свои имя и номер телефона и с Вами свяжется в ближайшее время наш сотрудник.
</p>
<div class="error" id="addcall-error"><?=implode("<br>",$tmp['addcall']['errors'])?></div>

<form action="<?=$cfg['site_dir']?>addcall.php?ajax=1" id='addcall-form' method="POST">
<div class="web-form">
    <div class="left">
        <label for="callfio">Имя:</label> 
     </div>
    <div class="right">
        <input type="text" size="40" value="<?=$tmp['addcall']['callfio']?>" name="callfio" id="callfio">
    </div>
</div>
<div class="web-form">
    <div class="left">
        <label for="callphone">Телефон:</label>
    </div>
    <div class="right">
        <input type="text" placeholder="+7(___) ___ __ __" id="callphone" name="callphone" value="<?=$tmp['addcall']['callphone']?>"/>
    </div>
</div>
<div class="web-form">
    <input type="button" onclick="AddMakeCall();" value="Мне нужна Ваша помощь" class="yell_b">
</div>
</form>

<script>
jQuery(document).ready(function() {
    jQuery("#callphone").mask("+7(999) 999-9999");
});
function AddMakeCall() {
    var error = "";
    var callfio = jQuery('#callfio').val();
    if (!callfio || callfio.length <3){
        error += 'Вы не указали имя<br>';          
    }
    var callphone = jQuery('#callphone').val();
    if (!callphone){
        error += "Введите номер телефона";          
    }    
    jQuery('#addcall-error').html(error);
    if(!error)  jQuery('#addcall-form').submit();
} 
</script>
</div>
<?}?>