<div id="registration_form">
<div class="frame-form" style="padding: 0 10px;">
<h1 class="yell_b">Регистрация</h1>

<? if($tpl['user']['send_status']){?>
    <div class="error" id="addcall-error">Пользователь успешно зарегистрирован</div>
	<script>
	//обновляем родительский блок элемента чтобы не перегружать страницу	
	parent.jQuery('#user_top_block').html('<?=$html_for_ajax?>');
	parent.jQuery.fancybox.close();
	</script>
<?} else {?>
<div class="error" id="addcall-error"><?=implode("<br>",$tpl['user']['errors'])?></div>
<form action="#" method="post" class=formtxt>
<div class="web-form">
    <div class="left">
        <label for="email">Email: </label>
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
         <input type=password id=password name=password value='<?=$tpl['user']['password']?>'>
    </div>
</div>
<div class="web-form">
    <div class="left">
        <label for="password">Пароль еще раз: </label>
     </div>
    <div class="right">
         <input type=password id=password_repeat name=password_repeat value='<?=$tpl['user']['password_repeat']?>'>
    </div>
</div>
<div class="web-form">
        <label for="subscr">Подписаться на новости: </label>
         <input type="checkbox" id=subscr name=subscr <?=checked_box($tpl['user']['subscr'])?> value='1'>
</div>
<div class="web-form" style='line-height:30px;padding:0 0 20px '>
        <label for="subscr">Введите цифрами число <font style='background:#ffcc66' id='inttostring_text'><?=$tpl['user']['inttostring']?></font>: </label>
        
        <div class="inttostring">
        <input type=text name=inttostring value='' id=inttostring class=formtxt size=4 maxlength=3>
        </div>
</div>
<input type=hidden id=inttostringm name=inttostringm value='<?=$tpl['user']['inttostringm']?>'>

<div>
    <input type="button" onclick="Register()"name=register value='Зарегистрироваться' class="yell_b" style="float: right">
</div>
</form>
</div>

<script>
function Register() {
    
$.ajax({	
    url: '<?=$cfg['site_dir']?>user/registration.php?ajax=1', 
    type: "POST",
    data:{email: $('#email').val(), 
        password: $('#password').val(),
        password_repeat:$('#password_repeat').val(),
        subscr:$('#subscr').val(),
        inttostring:$('#inttostring').val(),
        inttostringm:$('#inttostringm').val(),
        datatype:"json"},         
    dataType : "json",                   
    success: function (data, textStatus) { 
        $('#inttostring_text').text(data.inttostring); 
        $('#inttostringm').val(data.inttostringm); 
        if(data.errors.length>0){
            var error = '';
            for(i=0;i<data.errors.length;i++){
                error+=data.errors[i];
            }
            $('#addcall-error').html(error);
        } else if(data.send_status){
           ga("send", "event", "user", "register");
           location.reload();
        }   	      
    }
 });
}    

</script>

<?}?>

</div>