<div id="raiting_star<?=$rows['id']?>">
    <div id="raiting<?=$rows['id']?>" class="raiting">
        <div id="raiting_blank<?=$rows['id']?>" class='raiting_blank'></div>
        <div id="raiting_hover<?=$rows['id']?>" class="raiting_hover"></div>
        <div id="raiting_votes<?=$rows['id']?>" class="raiting_votes"></div>       
    </div>
	<!-- вывод кол-ва оценок
    <div id="raiting_info<?=$rows['id']?>" class="raiting_info">
		<?=$rows['markusers']?> оценка(ок) 
		<span id="raiting_error<?=$rows['id']?>" class="error">
		</span>
	</div>
  -->  
	<div id="raiting_error<?=$rows['id']?>"></div>
</div>

<?/*if($rows['markusers']>0){?>
  (<?=contentHelper::showImage('images/star'.(round($rows['marksum']/$rows['markusers'])*2).'.gif','')?> - <?=$rows['markusers']?> оценка(ок))<br>
<?}
if (!$rows['usermarkis']) {?>
	<form name=markcoin><input type=radio name=marks class=formtxt value=5> 5 (круто)<br>
		<input type=radio name=marks class=formtxt value=4> 4 (хорошо)<br>
		<input type=radio name=marks class=formtxt value=3> 3 (пойдет)<br>
		<input type=radio name=marks class=formtxt value=2> 2 (хреново)<br>
		<input type=radio name=marks class=formtxt value=1> 1 (очень хреново)<br>
		<input type=button name=submitmark value=Оценить onclick="javascript:AddMark('<?=$tpl['user']['user_id']?>','<?=$rows['catalog']?>);">
	</form>
	<br>
<?}*/?>
<script type="text/javascript">
$(document).ready(function(){
	var total_reiting = '<?=($rows['markusers'])?round($rows['marksum']/$rows['markusers']):0?>'; // итоговый ретинг
	var star_widht = total_reiting*17 + Math.ceil(total_reiting);
	$('#raiting_votes<?=$rows['id']?>').width(star_widht);
	
	$('#raiting<?=$rows['id']?>').hover(function() {
	      $('#raiting_votes<?=$rows['id']?>, #raiting_hover<?=$rows['id']?>').toggle();
		  },
		  function() {
	      $('#raiting_votes<?=$rows['id']?>, #raiting_hover<?=$rows['id']?>').toggle();
	});
	
	var margin_doc = $("#raiting<?=$rows['id']?>").offset();
	$("#raiting<?=$rows['id']?>").mousemove(function(e){
		var widht_votes = e.pageX - margin_doc.left;
		user_votes = Math.ceil(widht_votes/17);  
		// обратите внимание переменная  user_votes должна задаваться без var, т.к. в этом случае она будет глобальной и мы сможем к ней обратиться когда юзер кликнет по оценке.
		$('#raiting_hover<?=$rows['id']?>').width(user_votes*17);
	});
	
	$('#raiting<?=$rows['id']?>').click(function(){
         $.ajax({	
    	    url: 'addmark.php', 
    	    type: "GET",
    	    data:{shopcoins: <?=$rows['id']?>, mark: user_votes},         
    	    dataType : "json",                   
    	    success: function (data, textStatus) { 	
    	         if (!data.error) {    	          
                	$('#raiting_votes<?=$rows['id']?>').width((data.marksum2)*17);       	
	                $('#raiting_info<?=$rows['id']?>').text(data.markusers2+' оценка(ок)');  
                	$('#raiting_hover<?=$rows['id']?>').hide();
            	} else if (data.error == 'error1'){
            		$('#raiting_error<?=$rows['id']?>').text('Вы не авторизованы.');            		
            	} else if (data.error == 'error2'){            		
            		$('#raiting_error<?=$rows['id']?>').text('Вы не авторизованы.');            
            	} else if (data.error == 'error3') {
            		$('#raiting_error<?=$rows['id']?>').text('Неверные параметры запроса');
            	}else if (data.error == 'error4') {
            		$('#raiting_error<?=$rows['id']?>').text('Вы уже голосовали!');
            	}
    	    }
         });      
    });
});

/*jQuery(document).ready(function(){
  initRaiting(<?=$rows['id']?>,<?=($rows['markusers'])?round($rows['marksum']/$rows['markusers']):0?>);
});*/
</script>
