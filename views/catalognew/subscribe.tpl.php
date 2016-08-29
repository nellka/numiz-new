<div id="subscribe_form" class="frame-form">

<input type=hidden name=catalog id='catalog' value='<?=$catalog?>'>
<input type=hidden name=group id='group' value='<?=$group?>'>
	
<h1 class="yell_b"><?=$tpl['subscribe']['title']?></h1>

<div class="frame-form">
<? if($tpl['subscribe']['send_status']){?>
    <div class="error" id="subscribe-error">Подписка успешно добавлена!</div>	
<?} elseif($tpl['catalognew']['subscribe']['error']) {?>
     <div class="error" id="subscribe-error"><?=$tpl['catalognew']['subscribe']['error']?></div>	
<?} else {?>
<form action="#" method="post" class=formtxt>

<div class="web-form">
    <div class="left">
       <label for="userlogin">Пользователь:</lable>
    </div>       
    <div class="right">
            <input type=text name=userlogin value='<?=$tpl['subscribe']["userlogin"]?>' id='email' readonly>
    </div>
</div>
<div class="web-form">
    <div class="left">
        <label for="email">Email:</lable>
    </div>       
    <div class="right">
            <input type=text name=email value='<?=$tpl['subscribe']["email"]?>' id='email' readonly>
    </div>
</div>
<div class="web-form">
    <input type="button" name=save value='Записать' onclick="SaveSubscribe()" class="yell_b">
</div>


</form>
</div>
<div class="error" id="subscribe-error"><?=implode("<br>",$tpl['subscribe']['error'])?></div>


<script>

function SaveSubscribe() {
console.log($("#catalog").val());
console.log($("#group").val());
$.ajax({	
    url: '<?=$cfg['site_dir']?>new/?module=catalognew&task=subscribe&ajax=1', 
    type: "POST",
    data:{datatype:"text_html",catalog:$("#catalog").val(),group:$("#group").val()},         
    dataType : "html",                   
    success: function (data, textStatus) {    
        $('#subscribe_form').html(data);    	      
    }
 });
}   

</script>
<?}?>
</div>