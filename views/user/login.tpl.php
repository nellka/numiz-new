<?
if($tpl['user']['send_status']){?>
	<div class="error">Вы успешно авторизовались!</div>
	<script>
	//обновляем родительский блок элемента чтобы не перегружать страницу	
	parent.jQuery('#user_top_block').html('<?=$html_for_ajax?>');
	parent.jQuery.fancybox.close();
</script>	
<?} else {?>
<div id="auth_form" class="frame-form">
<div class="error"><?=implode("<br>",$tpl['user']['errors'])?></div>
<form action="<?=$cfg['site_dir']?>user/login.php?ajax=1" method="post" class=formtxt>
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
         <input type=password name=password value='<?=$tpl['user']['password']?>'>
    </div>
</div>
<div class="web-form" class="">    
    <div class="right">
         <input type="checkbox" name=remember_me <?=checked_box($tpl['user']['remember_me'])?> value='<?=$tpl['user']['remember_me']?>' id='remember_me'> <label for="remember_me"><b>Запомнить меня?</b></label>
    </div>
</div>
<div class="web-form">
    <input type="submit" name=login value='Войти' class="yell_b">
</div>
</form>
</div>
<div class="web-form">
<a href="<?=$cfg['site_dir']?>user/remind.php?ajax=1" class="remember_pwd" title='Восстановить пароль'><b>Напомнить пароль</b></a>
</div>
<?}?>
