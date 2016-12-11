<div id="loginForm">
<?if($tpl['user']['send_status']){?>
	<div class="error">Вы успешно авторизовались!</div>
	<script>
	//обновляем родительский блок элемента чтобы не перегружать страницу	
	parent.$('#user_top_block').html('<?=$html_for_ajax?>');
</script>	
<?} else {?>
<div class="form-block" id="auth_form">
<div class="error" id='login_errors'><?=implode("<br>",$tpl['user']['errors'])?></div>
<form action="#" method="post" class=formtxt>
<p>
        <label for="username">Логин/Email: </label>
        <input type=text name=username value='<?=$tpl['user']['username']?>' id='username' size="30">
</p>
<p>
        <label for="password">Пароль: </label>
         <input type=password name=password id=password value='<?=$tpl['user']['password']?>' size="30">
</p>
<p>    
    <label for="remember_me">&nbsp;</label>
         <input type="checkbox" name=remember_me <?=checked_box($tpl['user']['remember_me'])?> value='<?=$tpl['user']['remember_me']?>' id='remember_me'> <b>Запомнить меня?</b><br>
<p>
<p>
    <input type="button" name=login value='Войти' onclick="Login()" class="yell_b left">
    <a href="#" onclick="showWin('<?=$cfg['site_dir']?>user/remind.php?ajax=1',300);return false;" style="padding-left: 120px;" title='Восстановить пароль'><b>Напомнить пароль</b></a>
    
</p>

</form>
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
