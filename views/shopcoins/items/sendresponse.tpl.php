 <div id=PhonePostReceipt<?=$rows["order"]?> class="frame-form" style="display:none;">
                <h1 class="yell_b">Просим Вас ответить на вопросы.</h1>
                
    	<form action='' method=post name=FormReminder>
    	<input type=hidden name=parent value='<?=$rows["order"]?>'>
    	<input type=hidden name=action value='postreceipt'>
    	<div class="web-form">
            <div class="left">
                <label>Заказ</label>	
            </div>
            <div class="right">
                <select name=Reminder class=formtxt>
            	<option value=3 <?=selected(3,$rows["Reminder"])?>>Получен</option>
            	<option value=4 <?=selected(4,$rows["Reminder"])?>>Не получен</option>
            	</select>
    	   </div>
    	 </div>
    	 <div class="web-form">
            <div class="left">
                <label>Оценка за обслуживание:</label>	
            </div>
            <div class="right">
            <select name=mark class=formtxt>
        	<option value=0	<?=selected(0,$rows["mark"])?>>Выберите</option>
        	<option value=1 <?=selected(1,$rows["mark"])?>>Хорошо</option>
        	<option value=2  <?=selected(2,$rows["mark"])?> >Плохо</option>
        	</select>
        	 </div>
    	 </div>
    	 <div class="web-form">
            <div class="left">
                <label>Комплектация заказа:</label>	
            </div>
            <div class="right">
            <select name=complected class=formtxt>
    	       <option value=0 <?=selected(0,$rows["complected"])?>>Выберите</option>
    	       <option value=2 <?=selected(2,$rows["complected"])?>>Нет отличий от описи</option>
    	       <option value=1 <?=selected(1,$rows["complected"])?>>Есть отличия от описи</option>
    	   </select>
    	   </div>
    	 </div>
    	 <div class="web-form">
    	    <div class="left">
                <label>Ваши пожелания:</label>	
            </div>
    	 </div>    
    	 <div class="web-form">
    	    <textarea name=ReminderComment class=formtxt cols=40 rows=4></textarea>
    	 </div>
    	 <div class="web-form">
            <input class="yell_b" type="submit" onclick="javascript:if (document.FormReminder.mark.value<1){alert ('Пожалуйста оцените качество обслуживания по данному заказу.'); return false;} else {return true;}" value="Ответить">
        </div>	 
    	</form>   
	</div>  