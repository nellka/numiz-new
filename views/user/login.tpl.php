<div id="loginForm">
<?if($tpl['user']['send_status']){?>
	<div class="error">Вы успешно авторизовались!</div>
	<script>
	//обновляем родительский блок элемента чтобы не перегружать страницу	
	parent.$('#user_top_block').html('<?=$html_for_ajax?>');
</script>	
<?} else {?>
<div class="frame-form" id="auth_form">
<div class="error" id='login_errors'><?=implode("<br>",$tpl['user']['errors'])?></div>
<form action="№" method="post" class=formtxt>
<div class="web-form">
    <div class="left">
        <label for="username">Логин/Email: </label>
     </div>
    <div class="right">
        <input type=text name=username value='<?=$tpl['user']['username']?>' id='username'>
    </div>
</div>
<div class="web-form">
    <div class="left">
        <label for="password">Пароль: </label>
     </div>
    <div class="right">
         <input type=password name=password id=password value='<?=$tpl['user']['password']?>'>
    </div>
</div>
<div class="web-form" class="">    
    <div class="right">
         <input type="checkbox" name=remember_me <?=checked_box($tpl['user']['remember_me'])?> value='<?=$tpl['user']['remember_me']?>' id='remember_me'> <label for="remember_me"><b>Запомнить меня?</b></label>
    </div>
</div>
<div class="web-form">
    <input type="button" name=login value='Войти' onclick="Login()" class="yell_b">
</div>
</form>
</div>
<div class="web-form">
<a href="#" onclick="showWin('<?=$cfg['site_dir']?>user/remind.php?ajax=1',300);return false;" class="remember_pwd" title='Восстановить пароль'><b>Напомнить пароль</b></a>
</div>

<script>
function Login() {
$.ajax({	
    url: '<?=$cfg['site_dir']?>user/login.php?ajax=1', 
    type: "POST",
    data:{username: $('#username').val(), 
        password: $('#password').val(),
        remember_me:$('#remember_me').prop('checked'),
        datatype:"json"},         
    dataType : "json",                   
    success: function (data, textStatus) {  
        if(data.errors.length>0){
            var error = '';
            for(i=0;i<data.errors.length;i++){
                error+=data.errors[i];
            }
            $('#login_errors').html(error);
        } else if(data.send_status){
           location.reload();
        }   	      
    }
 });
}    

</script>
<?}?>
</div>
