site_dir = 'http://www.numizmatik.ru/';


function CheckUser(id) {
   if (id) {		
		$.ajax({
		    //url: site_dir+'price/checkuser.php', 
		    url: site_dir+'new/?module=price&task=checkuser', 
		    type: "POST",
		    data:{'number':id,'datatype':'json'},         
		    dataType : "json",                   
		    success: function (data, textStatus) { 
		    	if (!data.error) {                 	
	                $('#usercheck'+id).html('<font color=green><b>Отправлено</b></font>');  
            	} else if (data.error == 'error1'||data.error == 'error2'){
            		$('#usercheck'+id).text('Вы неавторизованный пользователь! Авторизуйтесь на главной странице.');         
            	} else if (data.error == 'error3') {
            		$('#usercheck'+id).text('Ошибочный запрос!');
            	} else if (data.error == 'error4') {
            		$('#usercheck'+id).text('Ошибочный запрос!');
            	}            	
		    }
		});
	}	  
	return;	
}

