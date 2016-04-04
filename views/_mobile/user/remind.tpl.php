<div id="remind_form">

<h1 class="yell_b" style='width:300px'> Восстановление пароля</h1>

<div class="frame-form">
<? if($tpl['user']['send_status']){?>
    <div class="error" id="addcall-error">Письмо с восстановлением пароля отправлено на Ваш email</div>	
<?} else {?>
<form action="<?=$cfg['site_dir']?>user/remind.php" method="post" class=formtxt>
<div class="error" id="addcall-error"><?=implode("<br>",$tpl['user']['errors'])?></div>
<div class="center">
    <p>Ваш email при регистрации:</p>
</div>       
<div class="web-form  center">
        <input type=text name=email value='<?=$tpl['user']['email']?>' id='email'>
</div>

<div class="center">
    <input type="submit" name=remind value='Напомнить пароль' onclick="RemainPwd()" class="yell_b">
</div>
</form>
</div>

<?}?>
</div>