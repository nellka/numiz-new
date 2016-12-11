<div id="myForm">
<h1 class="yell_b">Сообщение пользователю</h1>
<?
if($tpl['collector']['no_auth']){
	echo "<p class=txt><font color=red><b>Уважаемый коллега! </b></font><br>Отправку письма с сервера могут отправлять только <a href='".$cfg['site_dir']."user/registration.php'>зарегистрированные</a> пользователи. <br>Если Вы зарегистрированный пользователь, то пройдите систему авторизации, т.е. введите свой логин и пароль в верхнем правом блоке под названием \"Пользователь\".<br><br>";
} elseif($tpl['collector']['no_user_to']){?>
	<div class="error">Не указан получатель сообщения!</div>	
<?} elseif($tpl['collector']['send_status']){?>
	<div class="error">Ваше сообщение отправлено!</div>	
<?} else {?>
<div class="main_block" id="mess_form">
<div class="error" id='login_errors'><?=implode("<br>",$tpl['collector']['errors'])?></div>
<div>Если Вы хотите позвать пользователя в чат, назначте время встречи в поле сообщение и другие условия ...</div>
<form action="№" method="post" class=formtxt>
<p>
<label for="username"><b>От кого:</b> </label>
<?=$tpl['user']['fio']?>
</p>
<p>
     <label for="username"><b>Кому:</b>  </label>
         <input type="hidden" name="user_to" id="user_to">
         <?=$tpl['collector']['user_to']['fio']?>
</p>

<p>
    <label for="topic"> Тема: (<span class="red">*</span>)</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type=text name=topic id=topic size=50 maxlength=100  value='<?=$topic?>'>
</p>

<p>
 Сообщение: (<span class="red">*</span>):<br>
        <textarea name=details id=details cols=60 rows=5 class=formtxt><?=$details?></textarea>
</p>
<div class="center">
<br>
<br>
<input class="button25" name="submit" value="Отправить" onclick="SendMess()" type="button">
</div>
</form>
</div>

<script>
function SendMess() {
	var data = {datatype:"json", topic: $('#myForm #topic').val(), details: $('#myForm #details').val()};
$.ajax({	
    url: '<?=$cfg['site_dir']?>new/?module=collector&task=message&user_to=<?=$user_to?>&ajax=1', 
    type: "POST",
    data:data,         
    dataType : "json",                   
    success: function (data, textStatus) {  
    	console.log(data);
        if(data.errors.length>0){
            var error = '';
            for(i=0;i<data.errors.length;i++){
                error+=data.errors[i]+"<br>";
            }
            $('#myForm .error').html(error);
        } else if(data.send_status){
        	$('#myForm #mess_form').html('<div class="error">Ваше сообщение отправлено!</div>');
        }   	      
    }
 });
}    

</script>
<?}?>
</div>
