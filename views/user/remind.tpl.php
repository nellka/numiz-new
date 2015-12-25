<div id="remind_form">

<h1 class="yell_b"> Восстановление пароля</h1>

<div class="frame-form">
<? if($tpl['user']['send_status']){?>
    <div class="error" id="addcall-error">Письмо с восстановлением пароля отправлено на Ваш email</div>	
<?} else {?>
<form action="#" method="post" class=formtxt>

<div class="center">
    <p>Ваш email при регистрации:</p>
</div>       
<div class="web-form  center">
        <input type=text name=email value='<?=$tpl['user']['email']?>' id='email'>
</div>

<div class="center">
    <input type="button" name=remind value='Напомнить пароль' onclick="RemainPwd()" class="yell_b">
</div>
</form>
</div>
<div class="error" id="addcall-error"><?=implode("<br>",$tpl['user']['errors'])?></div>


<script>
function RemainPwd() {
 
$.ajax({	
    url: '<?=$cfg['site_dir']?>user/remind.php?ajax=1', 
    type: "POST",
    data:{email: $('#email').val(), datatype:"text_html"},         
    dataType : "html",                   
    success: function (data, textStatus) {     	        
        $('#remind_form').html(data);    	      
    }
 });
}    

</script>
<?}?>
</div>