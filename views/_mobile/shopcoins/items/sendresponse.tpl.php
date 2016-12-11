<div id=PhonePostReceipt<?=$rows["order"]?> class="frame-form" style="display:none;">
   <h1 class="yell_b">Просим Вас ответить на вопросы.</h1>
   <form action='' method=post name=FormReminder id=FormReminder>
	<input type=hidden name=parent value='<?=$rows["order"]?>'>
	<input type=hidden name=action value='postreceipt'>
	<span class="error" id='errorPostReceipt'></span>
	<div class="web-form">
        <div>
            <label>Заказ<br></label>	
        </div>            
        <select name=Reminder class=formtxt>
    	<option value=3 <?=selected(3,$rows["Reminder"])?>>Получен</option>
    	<option value=4 <?=selected(4,$rows["Reminder"])?>>Не получен</option>
    	</select>
	</div>
	 <div class="web-form">
        <div>
            <label>Оценка за обслуживание:<br></label>	
        </div>
        <div>
        <select name=mark  id=mark class=formtxt>
    	<option value=0	<?=selected(0,$rows["mark"])?>>Выберите</option>
    	<option value=1 <?=selected(1,$rows["mark"])?>>Хорошо</option>
    	<option value=2  <?=selected(2,$rows["mark"])?> >Плохо</option>
    	</select>
    	 </div>
	 </div>
	 <div class="web-form">
        <div>
            <label>Комплектация заказа:</label>	
        </div>
        <div>
        <select name=complected class=formtxt>
	       <option value=0 <?=selected(0,$rows["complected"])?>>Выберите</option>
	       <option value=2 <?=selected(2,$rows["complected"])?>>Нет отличий от описи</option>
	       <option value=1 <?=selected(1,$rows["complected"])?>>Есть отличия от описи</option>
	   </select>
	   </div>
	 </div>	
	 <div>
	    <div>
            <label>Ваши пожелания:</label>	
        </div>    	 
	    <textarea name=ReminderComment class=formtxt cols=30 rows=3></textarea><br>
	 </div> 
	 <div>
        <center><input class="yell_b" type="submit" onclick="if($('form#FormReminder #mark').val()<1){$('#errorPostReceipt').text('Пожалуйста оцените качество обслуживания по данному заказу.'); return false;} else {return true;}" value="Ответить"></center>
    </div>  
	</form> 
</div>  