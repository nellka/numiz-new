<div id="registration_form" class="frame-form" style="padding: 0 10px;">
<? if($tpl['user']['send_status']){?>
    <div class="error" id="addcall-error">Пользователь успешно зарегистрирован</div>
	<script>
	//обновляем родительский блок элемента чтобы не перегружать страницу	
	parent.jQuery('#user_top_block').html('<?=$html_for_ajax?>');
	parent.jQuery.fancybox.close();
	</script>
<?} else {?>
<div class="error" id="addcall-error"><?=implode("<br>",$tpl['user']['errors'])?></div>
<form action="<?=$cfg['site_dir']?>user/registration.php?ajax=1" method="post" class=formtxt>
<div class="web-form">
    <div class="left">
        <label for="username">Email: </label>
     </div>
    <div class="right">
        <input type=text name=email value='<?=$tpl['user']['email']?>' id='email'>
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
<div class="web-form">
    <div class="left">
        <label for="password">Пароль еще раз: </label>
     </div>
    <div class="right">
         <input type=password_repeat name=password_repeat value='<?=$tpl['user']['password_repeat']?>'>
    </div>
</div>
<div class="web-form">
        <label for="subscr">Подписаться на новости: </label>
         <input type="checkbox" id=subscr name=subscr <?=checked_box($tpl['user']['subscr'])?> value='1'>
</div>
<div class="web-form" style='line-height:30px;padding:0 0 20px '>
        <label for="subscr">Введите цифрами число <font style='background:#ffcc66'><?=$tpl['user']['inttostring']?></font>: </label>
        
        <div class="inttostring">
        <input type=text name=inttostring value='' class=formtxt size=4 maxlength=3>
        </div>
</div>
<input type=hidden name=inttostringm value='<?=$tpl['user']['inttostringm']?>'>

<div>
    <input type="submit" name=register value='Зарегистрироваться' class="yell_b" style="float: right">
</div>
</form>
</div>
<?}?>

