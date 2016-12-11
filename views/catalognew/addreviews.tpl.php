<div id="addreviews_form" class="frame-form">

<input type=hidden name=catalog id='catalog' value='<?=$catalog?>'>
<input type=hidden name=group id='group' value='<?=$group?>'>

<h1 class="yell_b">Написать отзыв</h1>
<div class="error" id="addreviews-error"><?=implode("<br>",$tpl['addreviews']['error'])?></div>
<div class="frame-form">
<? if($tpl['subscribe']['send_status']){?>
    <div class="error" id="addreviews-error"> Сообщение успешно добавлено!</div>	
<?} elseif($tpl['catalognew']['addreviews']['error']) {?>
     <div class="error" id="addreview-error"><?=$tpl['catalognew']['addreviews']['error']?></div>	
<?} else {?>
<form action="#" method="post" class=formtxt>


<div class="web-form">
    <div class="left">
       <label for="userlogin">Пользователь:</lable>
    </div>       
    <div class="right">
            <input type=text name=userlogin value='<?=$tpl['user']["fio"]?>' readonly>
    </div>
</div>
<div class="web-form">
    <div >
        <label for="email">Отзыв:</lable>
<br>
        <textarea type=text name=details id='details'cols="60" rows="3"><?=$tpl['addreviews']["details"]?></textarea>
    </div>
</div>
<div class="web-form">
    <input type="button" name=save value='Записать' onclick="SaveReviews()" class="yell_b">
</div>

</form>
</div>

<div class="web-form">Для всех объявлений прямые ссылки на любые сайты будут удаляться. Пользуйтесь разделом цены, указывая точную цену и ссылку</div>


<script>

function SaveReviews() {

$.ajax({	
    url: '<?=$cfg['site_dir']?>new/?module=catalognew&task=addreviews&ajax=1', 
    type: "POST",
    data:{datatype:"text_html",catalog:$("#MainBascet #catalog").val(),details:$("#MainBascet #details").val()},         
    dataType : "html",                   
    success: function (data, textStatus) {
    	$('#addreviews_form').html(data);    	      
    }
 });
}   

</script>
<?}?>
</div>