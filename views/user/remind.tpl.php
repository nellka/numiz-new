<div id="remind_form" class="frame-form">
<? if($tpl['user']['send_status']){?>
    <div class="error" id="addcall-error">Письмо с восстановлением пароля отправлено на Ваш email</div>	
<?} else {?>
<form action="<?=$cfg['site_dir']?>user/remind.php<?=($tpl['ajax'])?"?ajax=1":""?>" method="post" class=formtxt>

<div class="center">
        <p>Ваш email при регистрации:</p>
</div>       
<div class="web-form  center">
        <input type=text name=email value='<?=$tpl['user']['email']?>' id='email'>
</div>

<div class="center">
    <input type="submit" name=remind value='Напомнить пароль' class="yell_b">
</div>
</form>
</div>
<div class="error" id="addcall-error"><?=implode("<br>",$tpl['user']['errors'])?></div>
<?}?>