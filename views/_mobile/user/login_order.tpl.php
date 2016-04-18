<div id="orderForm">
<form id="orderForm">
<?if($tpl['user']['send_status']){?>
	<div class="error">Вы успешно авторизовались!</div>
	<script>
	//обновляем родительский блок элемента чтобы не перегружать страницу	
	parent.$('#user_top_block').html('<?=$html_for_ajax?>');
</script>	
<?} else {?>

<h5>Введите E-mail, чтобы оформить заказ</h5>

<div class="error" id='orderForm-errors'><?=implode("<br>",$tpl['user']['errors'])?></div>
<div>
    <input class="auth_form left" type=text name=email value='<?=$tpl['user']['email']?>' id='email' size="40" placeholder="Введите e-mail">
</div>
<div id=password-block style="display:<?=$tpl['user']['user_exist']?'block':'none'?>">
    <input type=password name=password id=password size="40" value='<?=$tpl['user']['password']?>' placeholder="Введите пароль">
    
</div>
<div id='warn'>
    <p><img src="<?=$cfg['site_dir']?>images/warn.png"> &nbsp;Мы не рассылаем спам и не предлагаем Ваши контакты третьим лицам</p>
</div>
<div >
    <input type="button" name=newUser id='newUser' value='Я новый покупатель' onclick="Login()" class="button27 left" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name=existUser id=existUser value='Я уже заказывал ранее' onclick="Login(1)" class="button26" style="font-weight:bold;">
</div>

<div id='subscr-order'>    
    <div>
         <input type="checkbox" name=subscr <?=checked_box($tpl['user']['subscr'])?> value='<?=$tpl['user']['subscr']?>' id='subscr'> <label for="subscr"><b>Подписаться на новости нумизматики</b></label><br>
          <input type="checkbox" name=subscr_shop <?=checked_box($tpl['user']['subscr_shop'])?> value='<?=$tpl['user']['subscr_shop']?>' id='subscr_shop'> <label for="subscr_shop"><b>Подписаться на новости магазина</b></label>
    </div>
</div>
<div>
<input type="button" class="button25" value="Перейти к оформлению заказа" onclick="Order()">
</div>
<div class="auth_form" id='remind-block' style="display:<?=$tpl['user']['user_exist']?'block':'none'?>">
<a href="#" onclick="showOn('<?=$cfg['site_dir']?>user/remind.php?ajax=1')" title='Восстановить пароль'><b>Забыли пароль?</b></a>
</div>

<input type="hidden" name='user_exist' id='user_exist' value="<?=$tpl['user']['user_exist']?>">
</form>
<br><br>
<script>
function Login(on) { 
	console.log(on);
	
    if(on){
        $('form#orderForm #password-block').show();
        $('form#orderForm #remind-block').show();
        $('form#orderForm #subscr-order').hide();
        $('form#orderForm #warn').hide();        
        $('form#orderForm #user_exist').val(1);
        $('form#orderForm #newUser').removeClass('button27');
        $('form#orderForm #newUser').addClass('button26');
        $('form#orderForm #existUser').removeClass('button26');
        $('form#orderForm #existUser').addClass('button27');
    } else {
        $('form#orderForm #password-block').hide();
        $('form#orderForm #remind-block').hide();
        $('form#orderForm #subscr-order').show();
        $('form#orderForm #warn').show();   
        $('form#orderForm #user_exist').val(0);
        $('form#orderForm #newUser').removeClass('button26');
        $('form#orderForm #newUser').addClass('button27');
        $('form#orderForm #existUser').removeClass('button27');
        $('form#orderForm #existUser').addClass('button26');
    }
   /* $.ajax({	
        url: '<?=$cfg['site_dir']?>user/auth_order.php?ajax=1', 
        type: "POST",
        data:{email: $('#email').val(), datatype:"text_html"},         
        dataType : "html",                   
        success: function (data, textStatus) {     	        
            $('#orderForm').html(data);    	      
        }
     });*/
}    

function Order() {
	var datastring = JSON.parse(JSON.stringify($("form#orderForm").serializeArray()));	
    //console.log(datastring);
    datastring.push({ name:"datatype",  value:"json"});
$.ajax({	
    url: '<?=$cfg['site_dir']?>user/login_order.php?ajax=1', 
    type: "POST",
    data: datastring ,    
    dataType : "json",                   
    success: function (data, textStatus) { 
    	if(data.errors.length>0){
            var error = '';
            for(i=0;i<data.errors.length;i++){
                error+=data.errors[i];
            }
            console.log(error);
            if(error=='Пользователь с таким Email уже существует в системе'){
                error ='Введите пароль';
                Login(1);
            }
            
            $('form#orderForm #orderForm-errors').html(error);
        } else if(data.send_status){
        	console.log(parent);
            parent.location='<?=$cfg['site_dir']?>shopcoins/index.php?page=order&page2=1';
           //location.reload();
        }   	      
    }
 });
}   

</script>
<?}?>
</div>
