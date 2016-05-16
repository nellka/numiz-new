function showOn(href,id){
     $('#MainBascet').dialog({
        modal: true,
        position: { 
           /* my: 'top',
            at: 'top',
            of: $("#"+id)*/
              my: "center center",
        at: "center center",
        of: window
        },
        close: function(event, ui){
            //console.log(this);
           // $(this).dialog("close");
            $('#MainBascet').html('');
           // $(this).remove();
        },
        open: function (){
            //$('div.ui-widget-overlay').hide();
           // $("div.ui-dialog").not(':first').remove();
            // console.log(href);
             $(this).load(href);
             //$('.ui-dialog-titlebar-close').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only');
//$('.ui-dialog-titlebar-close').append('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">close</span>');
        },
       // height: 330, 
        width: 400
    });
     return false;   
} 
function showMenuDescription(id){      
    if($('#menudiscription-m'+id)){
        $('.menuDescription').hide();
        $('#menudiscription-m'+id).show();
    }
}

function setMini(on,onscroll){    
    //console.log(on+','+onscroll);
    if(!on&&onscroll){      
        if($.cookie('mini')==0){
           //console.log('идем дальше надо развернуть');          
        } else {
            return;
        }
    }
    
	if(on){
		//console.log('setmini');
		$('#header').hide();
		$('#header-mini').show();
		$('#small-logo').hide();
		//$('#shop-logo').css("height",'0');	
		$('#shop-logo').hide();
		if(!onscroll) $.cookie('mini', 1);
	} else {
	    
		console.log('setful');
		$('#header-mini').hide();
		$('#header').show();
		$('#small-logo').show();
		//$('#shop-logo').css("height",'20px');
		$('#shop-logo').show();
		if(!onscroll) $.cookie('mini', 0);
	}
	//console.log(('shop-logo'));
	//console.log($('#shop-logo').height());
	//console.log('end');
}
function fgroup(){    
    var query = $("#group_name").val().toLowerCase();			    
	if(query.length>1){
	    $('#fb-groups .checkbox').each(function(i, elem) {			    	
	          if ($(elem).find('a').text().toLowerCase().indexOf(query) != -1) {
	              $(elem).show();
	          }else{
	              $(elem).hide();
	          }
	          mCustomScrollbars();
	    });
	} else {
		$('#fb-groups .checkbox').each(function(i, elem) {			    	
	        $(elem).show();
	    });
	}     
}

function showReviewForm(){
    $("#MainBascet").html($('div#reviewcoin').html());
    $("#MainBascet").dialog({
        	position: { 
                my: 'top',
                at: 'top',
                of: $("#review-block")
            },
     		 modal:true,
    		 buttons:{
    		   // Ok: function(){
    		      //$(this).dialog( "close" );
    		   // }
    		  }
    	});
}
function showWin(href,width){
    $(".ui-icon.ui-icon-closethick").trigger("click");
   //console.log(href);
   //$('#MainBascet').dialog('close');
	if(!width) width = 400;
	//console.log($('#MainBascet'));
     $('#MainBascet').dialog({
        modal: true,
        position: { 
              my: "center center",
        at: "center center",
        of: window
        },
        close: function(event, ui){
            //$(this).dialog("close");
            //$('#MainBascet').html('');
            //$(this).remove();
        },
        open: function (){   
             $(this).load(href);
             //$('.ui-dialog-titlebar-close').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only');
			 //$('.ui-dialog-titlebar-close').append('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">close</span>');
        },
       // height: 330, 
        width: width
    });
     return false;   
} 
//рейтинг товара
function initRaiting(id,total_reiting){
    var star_widht = total_reiting*17 ;
    $('#raiting_star'+id+' #raiting').hover(function() {
            $('#raiting_star'+id+' #raiting_votes,#raiting_star'+id+' #raiting_hover').toggle();
        },
        function() {
        $('#raiting_star'+id+' #raiting_votes,#raiting_star'+id+' #raiting_hover').toggle();
    });
    var margin_doc = $('#raiting_star'+id+' #raiting').offset();
    $('#raiting_star'+id+' #raiting').mousemove(function(e){
        var widht_votes = e.pageX - margin_doc.left;
        if (widht_votes == 0) widht_votes =1 ;
        user_votes = Math.ceil(widht_votes/17);  
        //user_votes должна задаваться без var, т.к. в этом случае она будет глобальной и мы сможем к ней обратиться из другой ф-ции (нужна будет при клике на оценке.
        $('#raiting_star'+id+' #raiting #raiting_hover').width(user_votes*17);
    });
    // отправка
    $('#raiting_star'+id+' #raiting').click(function(){
         $.ajax({	
    	    url: 'addmark.php', 
    	    type: "GET",
    	    data:{shopcoins: id, mark: user_votes},         
    	    dataType : "json",                   
    	    success: function (data, textStatus) { 	
    	         if (!data.error) {    	          
                	$('#raiting_star'+id+' #raiting_votes').width((data.marksum2)*17);       	
	                $('#raiting_info'+id).text(data.markusers2+' оценка(ок)');  
                	$('#raiting_hover').hide();
            	} else if (data.error == 'error1'){
            		$('#raiting_error'+id).text('Вы не авторизованы. Пройдите авторизацию.');            		
            	} else if (data.error == 'error2'){            		
            		$('#raiting_error'+id).text('Вы не авторизованы. Пройдите авторизацию.');            
            	} else if (data.error == 'error3') {
            		$('#raiting_error'+id).text('Неверные параметры запроса');
            	}else if (data.error == 'error4') {
            		$('#raiting_error'+id).text('Вы уже голосовали за эту монету!');
            	}
    	    }
         });      
    });
}
	
function ShowOneClick(id,width) {	
    if(!width) width = 400; 
	$('#MainBascet').html($('#oneshopcoins'+id+' .messages').html());
	$("#MainBascet input[name='onephone']").mask("+9(999) 999-9999");
	
	$("#MainBascet").dialog({
        	position: { 
                my: 'top',
                at: 'top',
                of: $("#bascetshopcoins"+id)
            },
     		modal:true,
     		width:width,
    		open: function(event, ui){
            }
     });  

	
	
}

function HideOneClick(id) {
	//$('#oneshopcoins'+id+' .messages').fadeOut("fast");
	//$(".bg_shadow").fadeOut("fast");
	//$(document).unbind("click.myEvent");
}

function ValidPhone(phone) {
    var re = /^\d[\d\(\)\ -]{8,14}\d$/;
    var myPhone = phone;
    var valid = re.test(myPhone);
    if (valid) return true;
    return false;
}  

function AddOneClick(id) {
 
   var onefio = $("#MainBascet  input[name='onefio']").val();
   var onephone = $("#MainBascet input[name='onephone']").val();     

   if (!onefio || onefio.length <3){
		$('#MainBascet #oneshopcoins'+id+'-error').text('Вы не указали имя');
		return;
   }

   if (!onephone){
		$('#MainBascet #oneshopcoins'+id+'-error').text('Укажите номер телефона в полном формате!');
		return;
   }
  
   
   $.ajax({
	    url: site_dir+'shopcoins/addoneklick.php', 
	    type: "POST",
	    data:{'shopcoins':id,'onefio':onefio,'onephone':onephone,'datatype':'json'},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 
	        ShowOneClickResult(id,data)
	    }
	});
	return;	
}

function ShowOneClickResult(id,data) {		
	errorvalue = data.error;
	var bascetshopcoins = data.bascetshopcoins;	
	var str = '';
	if (!errorvalue){
		var shopcoinsorder = data.shopcoinsorder;	
		var bascetsum = data.bascetsum;			
		
		str += '<h1 class="yell_b">Ваш заказ №</strong> ' + shopcoinsorder + ' </h1>';
		str += '<p><strong>Товаров:</strong> 1 <br><strong>На сумму:</strong> ' + bascetsum + ' р.';
		str += '<br>Заказ сделан, Для уточнения деталей платежа и доставки с Вами свяжется наш менеджер.<br><br></p>';
		//$('#oneclick_form'+id).html(str);
	} else if (errorvalue == 'reserved') {
		//$('#oneshopcoins'+id+'-error').text('Товар зарезервирован одновременно с другим пользователем');	
		str = 'Товар зарезервирован одновременно с другим пользователем';
	} else if (errorvalue == 'notavailable') {		
	    str = 'Товар уже продан';
		//$('#oneshopcoins'+id+'-error').text('Товар уже продан');	
	} else if (errorvalue == 'stopsummax') {
		//$('#oneshopcoins'+id+'-error').text('Минимальная сумма заказа <? echo $minpriceoneclick;?> руб.');
		str = 'Минимальная сумма заказа <? echo $minpriceoneclick;?> руб.';		
	}	else if (errorvalue == 'amount') {
		erroramountvalue = data.erroramount;		
		//$('#oneshopcoins'+id+'-error').text('На складе всего лишь ' + erroramountvalue + ' штук');
		str = 	'На складе всего лишь ' + erroramountvalue + ' штук';	
	}
	$('#oneshopcoins'+id+' .messages').hide();
	$(".ui-dialog-content").dialog().dialog("close");
	$("#MainBascet").html(str);	
	$("#MainBascet").dialog({
        	position: { 
                my: 'top',
                at: 'top',
                of: $("#bascetshopcoins"+id)
            },
     		modal:true,
    		open: function(event, ui){
    		   var $this = $(this); 
    		   setTimeout(function(){$this.dialog('close');}, 4000);
            }
    	});    
}	
	
function sendData(name,val,p0,p1,y0,y1){  
   
    if(name){
        $('#'+name).val(val);
    }
    console.log($('#amount-years1').val()+','+y1+','+((+$('#amount-years1').val()==+p1) ));
    
    if(+$('#amount-price0').val()!=+p0) $('#pricestart').val($('#amount-price0').val());
    if(+$('#amount-price1').val()!=+p1) $('#priceend').val($('#amount-price1').val());

    if(+$('#amount-years0').val()!=+y0) $('#yearstart').val($('#amount-years0').val());
    if(+$('#amount-years1').val()!=+y1) $('#yearend').val($('#amount-years1').val());

    $(".bg_shadow").show();
    if($("#f-zone")){
        var datastring = JSON.parse(JSON.stringify($("form#search-params").serializeArray().concat($("#f-zone input").serializeArray())));	
    } else {
        var datastring = JSON.parse(JSON.stringify($("form#search-params").serializeArray()));	
    }
    datastring.push({ name:"datatype",  value:"text_html"});
    datastring.push({ name:"pagenum",  value:1});

	$.ajax({
        type: "POST",
        url: $('form#search-params').attr('action'),
        data: datastring,
         dataType : "html",
        success: function(data) { 
            //console.log(data); 
             $('#products').html(data);  
                          	
              $(".bg_shadow").hide();
          
        }
    });		
    //$('form#search-params').submit();
}

function AddAccessory(id,materialtype){		
	var str;
	var amount = $('#amount'+id).val();
	if(amount <=0) amount = 1;
    $.ajax({	
	    url: site_dir+'shopcoins/addbascet.php', 
	    type: "POST",
	    data:{'shopcoinsorder':"<?=$shopcoinsorder?>",'shopcoins':id,'amount':amount,'materialtype':materialtype,'datatype':'json'},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 	    	
	        ShowSmallBascet(id,data);	        
	    }
	});
	
	return false;		
}

var klicklast = 0;
var klicklastn = 0;

function AddBascetLast2(kn) {	
	
	if (klicklastn == 1) {
		alert('Вы нажали на кнопку ранее, для активации кнопки обновите страницу');
		return false;
	}
	var shopcoinslast = '';
	var numislast2 = 0;
	for (i=100;i<kn;i++) {	
		if ($("#shopcoinslast"+i).prop("checked")) {			
			shopcoinslast += $("#shopcoinslast"+i).val()+"d";
			numislast2++;
		}
	}				
	if (numislast2==0) {	
		alert('Вы не указали позиции для добавления в корзину');
	}	else {		
		if (confirm("Вы желаете положить все отмеченные монеты из списка просмотренных в корзину?")) {
			 $.ajax({	
			    url: site_dir+'shopcoins/addbascetlast.php?', 
			    type: "POST",
			    data:{'shopcoinslast':shopcoinslast},         
			    dataType : "json",                   
			    success: function (data, textStatus) { 	    	
			        ShowSmallBascet(0,data);
			        var arrlast = data.bascetshopcoins.split('d');
					for (i=0;i<arrlast.length;i++) {
						$("#lastcatalogis"+arrlast[i]).html("<input type=checkbox disabled=disabled value=0>");
					}
					return;				
			    }
			});			
			klicklastn = 1;
		}
	}
}
//var 
		
function ChangeSumSeeCoins (sumcoins,place) {
	sumseecoins = $("#sumseecoins_val").val();
	
	if($("#shopcoinslast"+place).prop("checked")){
		sumseecoins = parseInt(sumseecoins) + parseInt(sumcoins);
	}	else {
		sumseecoins = parseInt(sumseecoins) - parseInt(sumcoins);
	}
	$("#sumseecoins").text(sumseecoins);	
}

function ShowSmallBascet (id,data) {
	var bascetshopcoins = data.bascetshopcoins;	
	if (!data.error){
		var shopcoinsorder = data.shopcoinsorder;
		var bascetamount = data.bascetamount;
		var bascetsum = data.bascetsum;
		var bascetweight = data.bascetsumclient;
		var bascetreservetime = data.bascetreservetime;
		var bascetpostweightmin = data.bascetpostweightmin;
		var bascetpostweightmax = data.bascetpostweightmax;
		var bascetinsurance = data.bascetinsurance;
		var textbascet2 = data.textbascet2;		
		
		if(!$("#basket-info-ne").is(':visible')){
			$("#basket-info-ne").show();
			$("#basket-info").hide();
		}
		
		button = $('#bascetshopcoins'+id);
    	
        var o1 = button.offset();
    
    	var o2 = $('#inorderamount').offset();
    	var dx = o1.left - o2.left;
    	var dy = o1.top - o2.top;
    	var distance = Math.sqrt(dx * dx + dy * dy);
    	if($('#header-mini').is( ":visible" )){    	    
    	   $('#item'+id).find('.primage img').effect("transfer", { to: $("#header-mini #inorderamount"), className: "transfer_class" }, 1000);	
    	} else {
    	    $('#item'+id).find('.primage img').effect("transfer", { to: $("#header #inorderamount"), className: "transfer_class" }, 1000);	
    	}
    
    	$('.transfer_class').css({'z-index':'6000'});
    	$('.transfer_class').html($('#item'+id).find('.primage').html());
    	$('.transfer_class').html($('#item'+id).find('.primage').html());
    	$('.transfer_class').find('img').css('height', '100%');
	
	
		$("#header-mini #inorderamount").html("<a href='"+site_dir+"shopcoins/index.php?page=orderdetails'>"+bascetamount+" товаров</a>");	
		$("#header #inorderamount").html("<a href='"+site_dir+"shopcoins/index.php?page=orderdetails'>"+bascetamount+" товаров</a>");	
	
		$("#header-mini #basket-order").html(' №'+shopcoinsorder);	
		$("#header #basket-order").html(' №'+shopcoinsorder);	
		
		$("#header-mini #inordersum").html(bascetsum);	
		$("#header #inordersum").html(bascetsum);	
		
	
		//$("#bascetshopcoins" + bascetshopcoins).html('<img src="'+site_dir+'images/corz7.gif" title="Уже в корзине" alt="Уже в корзине">');
		$("#bascetshopcoins" + bascetshopcoins).html('<a class="button7" alt="Уже в вашей корзине" onclick="return false;" href="#">Корзина</a>');
			
		return false;
    	/*var str = '';
    		str = '<h1 class="yell_b">Корзина</h1>';
    		str += '<p><b>Заказ №</b> ' + shopcoinsorder + '</p>';
    		str += '<p><strong>Товаров:</strong> ' + bascetamount + ' <br><b>На сумму:</b> ' + bascetsum + ' р.';
    		str += '<br><b>Вес ~ </b> ' + bascetweight + ' гр. <br><b>Бронь на:</b> ' + bascetreservetime + '</p>'
    		str += '<p><center><a href=?page=orderdetails><img src='+site_dir+'images/basket.gif border=0></a></center></p>';
    		str += '<h1 class="yell_b">Доставка:</font></h1>';
    		str += '<p><b>Москва:</b><br><b>Кольцевые станции:</b> бесплатно<br><b>В офис:</b> от 150 р.';
    		str += '<br><br><b>Почта России</b><br><b>Сбор по весу:</b> от ' + bascetpostweightmin + ' до ' + bascetpostweightmax + ' р.';
    		str += '<br><b>Страховка 4%:</b> ' + bascetinsurance + ' р. <br><b>Упаковка:</b> 10 р. за конверт / ящик.</<p>';
    		
    		$("#MainBascet").html(str);		*/		
    			
    	} else if (data.error == 'reserved'){
    		$("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>Товар зарезервирован одновременно с другим пользователем</p>');	
    		//$("#bascetshopcoins" + bascetshopcoins).html('<img src="'+site_dir+'images/corz6.gif" title="Уже в корзине" alt="Уже в корзине">');
    		$("#bascetshopcoins" + bascetshopcoins).html('<a class="button6" alt="Уже в корзине" onclick="return false;" href="#">Корзина</a>');    		
    	}	else if (data.error == 'notavailable'){	
    		$("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>Товар уже продан</p>');	
    		//$("#bascetshopcoins" + bascetshopcoins).html('<img src="'+site_dir+'images/corz6.gif" title="Уже в корзине" alt="Уже в корзине">');
    		$("#bascetshopcoins" + bascetshopcoins).html('<a class="button6" alt="Уже в корзине" onclick="return false;" href="#">Корзина</a>');
    		
    	} else if (data.error == 'stopsummax') {
    		$("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>Максимальная сумма заказа <? echo $stopsummax;?> руб. <br>Если вы не все сложили в корзину, проделайте следующим заказом.</p>');	
    	} else if (data.error == 'amount') {
    		erroramountvalue = data.erroramount;
    		$("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>На складе всего лишь ' + erroramountvalue + ' штук</p>');
    	}
    	
    	$("#MainBascet").dialog({
        	position: { 
                my: 'top',
                at: 'top',
                of: $("#bascetshopcoins"+id)
            },
     		modal:true,
    		open: function(event, ui){
    		   var $this = $(this); 
    		   setTimeout(function(){$this.dialog('close');}, 2000);
            }
    	});    
    }
    

function AddReview(id) {
	if(!id)	return;

	var review = $('#MainBascet #reviewcointext').val();

	if (!review) {	
	    $("#MainBascet #error-review").text('Заполните отзыв.');
		return;
	}	
	$.ajax({
	    url: site_dir+'shopcoins/addreview.php', 
	    type: "POST",
	    data:{'shopcoins':id,'review':review},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 
	        if (!data.error){
        		$('#emptyreview').hide();
        		//var old_review = $("#MainBascet#reviewsdiv").html();        		
        		//$("#reviewsdiv").html("<div id='review"+id+"'><b>"+data.dateinsert+" "+data.fio+"</b>"+ data.review+"</div>"+old_review);
        		$("#MainBascet #error-review").text('Спасибо за Ваш отзыв!');    
        		$("#MainBascet input[type=button]").remove();
	        } else if (data.error == 'error1') {
        		$("#MainBascet #error-review").text('Вы не авторизованы. Пройдите авторизацию.');        		
        	} else if (data.error == 'error3') {
        		$("#MainBascet #error-review").text('Неверные параметры запроса');
        	} else if (data.error == 'error4'){
        		$("#MainBascet #error-review").text('Вы уже оставляли отзыв на данную позицию');
        	}        	       
       }
	});	
}

function AddNext (id, amount) {
     $('#bascetshop' + id).html('<img src="'+site_dir+'images/wait.gif">');
	 $.ajax({
	    url: site_dir+'shopcoins/addnext.php', 
	    type: "POST",
	    data:{'shopcoins':id,'amount':amount},         
	    dataType : "json",                   
	    success: function (data, textStatus) {                
        	if (!data.error) {
        		$("#MainBascet").html('Вы в очереди на покупку монеты, в случае отказа от покупки предыдущего покупателя монета будет забронирована за вами в течении 5 часов. Ваша бронь будет действительна до '+data.datereserve);        		
        	    $('#bascetshop' + id).html('<img src="'+site_dir+'images/corz77.gif" alt="Вы в очереди на монету">');
        	} else if (data.error == 'noauth'){
        		$("#MainBascet").html('Чтобы встать в очередь на товар пожалуйста, авторизуйтесь');   		    	} else if (data.error == 'reserved'){
        		$("#MainBascet").html('Товар зарезервирован одновременно с другим пользователем');        
        		//$('#bascetshop' + id).html('<img src="'+site_dir+'images/corz6.gif" alt="Уже в корзине">');  
        		$('#bascetshop' + id).html('<a class="button6" alt="Уже в корзине" onclick="return false;" href="#">Корзина</a>');         		    		
        	} else if (data.error == 'notavailable') {        		
        		$("#MainBascet").html('Товар уже продан');        		
        		//$('#bascetshop' + id).html('<img src="'+site_dir+'images/corz6.gif" alt="Уже в корзине">');    
        		$('#bascetshop' + id).html('<a class="button6" alt="Уже в корзине" onclick="return false;" href="#">Корзина</a>');       		     
        	} else if (data.error == 'stopsummax') {
        		$("#MainBascet").html('Максимальная сумма заказа <? echo $stopsummax;?> руб.<br>Если вы не все сложили в корзину, проделайте следующим заказом."');
        	}  
        	              	
            $("#MainBascet").dialog({
            	position: { 
                    my: 'top',
                    at: 'top',
                    of: $("#bascetshopcoins"+id)
                },
         		 modal:true,
        		 open: function(event, ui){
        		   var $this = $(this); 
        		   setTimeout(function(){$this.dialog('close');}, 2000);
                }
            });	       
        }
	});	
}
function WaitSubscribeCatalog(id){
	$("#mysubscribecatalog"+id).html('<img src="'+site_dir+'images/wait.gif">');
	$.ajax({
	    url: site_dir+'shopcoins/addsubscribecatalog.php', 
	    type: "POST",
	    data:{'catalog':id},         
	    dataType : "json",                   
	    success: function (data, textStatus) {          
            if (!data.error){            
                if (data.valueid){
                  $("#mysubscribecatalog" + data.valueid).html('<b><font color=silver>Заявка принята</font></b>');
                }
           } else if (data.error == 'auth') {
                $("#mysubscribecatalog" + data.valueid).html('<b><font color=silver>Вы не авторизованы</font></b>');
            }  else {
                $("#mysubscribecatalog" + data.valueid).html('<font color=red><b>'+data.error+ '</b></font>');
            }
	    }
	});
}


function AddNominal(){	
   $.ajax({
	    url: site_dir+'shopcoins/detailscoins/addname.php', 
	    type: "POST",
	    data:{'id':$('#id_group2').val()},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 
            $('#name2').autocomplete({
              source: data.arrayresult
            });
       }
	});
}	

function CheckSubmitPrice (num) {
	if (confirm("Все правильно заполнили ?")) {		
		var datastring = $("#send_descr").serialize();		
		$.ajax({
            type: "POST",
            url: site_dir+"/shopcoins/change_coin.php",
            data: datastring,
            dataType: "json",
            success: function(data) {               	
               if(data.success){
               		alert("Спасибо, на ваш бонус-счет зачислен 1 рубль.");
               		location.reload();
               }
               if(data.error){
               		alert(data.error);
               }
            }
        });		
	}
	return false;
}
function checkFormCoupon() {
	var code1 = $('#code1').val();
	var code2 = $('#code2').val();
	var code3 = $('#code3').val();
	var code4 = $('#code4').val();

	if (code1.length != 4 || code2.length != 4 || code3.length != 4 || code4.length != 4 ) {
		$('#coupon-error').text('Неверно введен код купона');
		return;
	}

	$('#CouponInfo').html('<img src="'+site_dir+'images/wait.gif">');
	$.ajax({
		type: "POST",
		url: site_dir+"/shopcoins/activcoupon.php",
		data: {code1:code1,code2:code2,code3:code3,code4:code4},
		dataType: "json",
		success: function(data) {
			if (!data.error) {
				dissum = data.dissum;
				$('#dissum').val(dissum);
				$('#coupon-error').text('Купон активен. Скидка '+dissum+' руб. будет добавлена к заказу');
				calculateOrder();
			} else {
				if (data.error == "error1") {
					$('#coupon-error').text('Неверно введен код');
				}  else if (data.error == "error3") {
					$('#coupon-error').text('Купон не действителен, поскольку вы являетесь уже VIP-клиентом');
				}	else if (data.error == "error5") {
					orderin =data.orderin?data.orderin:0;
					dateuse = data.dateuse?data.dateuse: "---";
					$('#coupon-error').html('Купон уже был погашен. Дата погашения '+dateuse+', Заказ '+orderin+'');
				} else if (data.error == "error6") {
					dateout = data.dateout?data.dateout:'---';
					$('#coupon-error').html('Время действия купона истекло. Дата истечения '+dateout);
				}
			}
			$('#CouponInfo').html('<input type=\'button\'  value="Проверить купон" onclick=\'checkFormCoupon()\'>');
		}
	});
}

function ShowMetro(delivery){
	var timelimit = $('#timelimit').val();
	if (delivery == 1 || delivery == 2 || delivery == 3 || delivery == 7) {
		$('#metro-block').show();
		$("#metro").unbind("change");
		if(delivery==1) {
			$('#delivery-m').show();
			$('#metro').bind("change", function(event) {
				ShowMetro(1);
			} );

			$('#meeting-from').hide();
			$('#meeting-to').hide();

		} else if(delivery==3){
			$('#delivery-m').show();
			$('#metro').bind("change", function(event){
				ShowPriceMetro();
			} );
			$('#meeting-from').show();
			$('#meeting-to').show();
		} else {
			$('#delivery-m').hide();
			$('#meeting-from').show();
			$('#meeting-to').show();
		}
	} else {
		$('#metro-block').hide();
	}
	var metroid = 0;

	if (delivery == 3) {
		url = site_dir+'shopcoins/showallmetro.php';
	}
	if (delivery == 1)	{
		url = site_dir+'shopcoins/showringmetro.php';
		metroid =$('#metro').val();
	}

	if (delivery == 2 || delivery == 7)	url = site_dir+'shopcoins/showinoffice.php';

	$.ajax({
		url: url,
		type: "GET",
		data:{timelimit: timelimit, delivery: delivery,metroid:metroid},
		dataType : "json",
		success: function (data, textStatus) {
			var meetingfromtime = $('#meetingfromtime');
			var meetingtotime = $('#meetingtotime');			
			meetingfromtime.empty();
			meetingtotime.empty();			
			
			for (var i = 0; i < data.TimesArray.length; i++) {
				meetingfromtime.append($("<option>").attr('value',data.TimesArray[i].val).text(data.TimesArray[i].text));
				meetingtotime.append($("<option>").attr('value',data.TimesArray[i].val).text(data.TimesArray[i].text));
			}
			if (delivery==2) {
				// ShowInOffice();
				var sel = $('#meetingdate');
				for (var i = 0; i < data.DaysArray.length; i++) {
					sel.append($("<option>").attr('value',data.DaysArray[i].val).text(data.DaysArray[i].text));
				}
			} else if(delivery==7){
				ShowInOfficeMetro();
			} else if(delivery==1||delivery==3){
				$("#pricemetro").html('');
				if (metroid<1 || metroid>12) {
					$('#metro').empty();
					$('#meetingdate').empty();
					var sel = $('#metro');
					sel.append($("<option>").attr('value',0).text("Выбор метро"));
					for (var i = 0; i < data.MetroArray.length; i++) {
						sel.append($("<option>").attr('value',data.MetroArray[i].val).text(data.MetroArray[i].text));
					}
					var sel = $('#meetingdate');
					sel.append($("<option>").attr('value',0).text("Дата"));
					for (var i = 0; i < data.DaysArray.length; i++) {
						sel.append($("<option>").attr('value',data.DaysArray[i].val).text(data.DaysArray[i].text));
					}
				}
			}
	
			if ('<?=$timelimit?>'> 0 && '<?=$timelimit?>' < 60) {
				$("#MetroGif").html('<br><br><table width=100% cellpadding=2 cellspacing=1 border=0 align=center><tr><td class=txt bgcolor=#EBE4D4 valign=top><font color=red><b>Внимание!</b></font> Вам введен лимит по времени выкупа заказов. Для выяснения обстоятельств свяжитесь с администрацией по тел. +7-903-006-00-44 или  +7-915-002-22-23. С 10-00 до 18-00 МСК (по рабочим дням).</td></table>');
			} else {
				$("#MetroGif").html('');
			}
		}
	});

}

function ShowPayment(delivery){
	bascetsum = $('#bascetsum').val();
	$('#payment1').prop("disabled",true);
	$('#payment2').prop("disabled",true);
	$('#payment3').prop("disabled",true);
	$('#payment4').prop("disabled",true);
	$('#payment5').prop("disabled",true);
	$('#payment6').prop("disabled",true);
	$('#payment7').prop("disabled",true);
	$('#payment8').prop("disabled",true);

	if(delivery==4){
		$('#payment4-warning').show();
		if($('#userstatus').val()==2) $('#payment4-error').show();
	} else {
		$('#payment4-error').hide();
		$('#payment4-warning').hide();
	}

	if(delivery!=4){
		ShowMetro(delivery);
	} else {
		$('#metro-block').hide();
	}

	if (delivery == 1 || delivery == 2 || delivery == 3 || delivery == 7) {
		$('#payment2').prop("disabled",false);
	}

	if (delivery == 10) {
		$('#payment2').prop("disabled",false);
	}

	if (delivery == 4) {
		//нужно показать вес, содержимое.
		if ($('#userstatus').val() != 0) {
			$('#payment1').prop("disabled",true);
		} else {
			$('#payment1').prop("disabled",false);
		}
		$('#payment3').prop("disabled",false);
		$('#payment4').prop("disabled",false);
		$('#payment6').prop("disabled",false);
	 	if (bascetsum>=3000) $('#payment7').prop("disabled",false);
		$('#payment8').prop("disabled",false);
	}

	if (delivery == 5) {
		$('#payment5').prop("disabled",false);
		if (bascetsum>=3000)  $('#payment6').prop("disabled",false);
	}

	if (delivery == 6){
		$('#payment5').prop("disabled",false);
		if (bascetsum>=3000) {
		    $('#payment3').prop("disabled",false);
		    $('#payment4').prop("disabled",false);
			$('#payment6').prop("disabled",false);
			$('#payment8').prop("disabled",false);
		}
	}
	if($('#payment'+$('[name=payment]:checked').val()).prop("disabled")){
		$('#payment'+$('[name=payment]:checked').val()).prop("checked",false);
		$('[name=payment]').each(function (i) {
			if(!$(this).prop("disabled")){
				$(this).prop("checked",true);
				return false;
			}
		});
	}
	calculateOrder();
}
function showCoupon(){
	if(!$('#coupon-block').is(':visible')){
		$('#coupon-block').show();
	} else {
		$('#coupon-block').hide();
	}
	return false;
}


function ShowOther(delivery){
	//3 - доставка в офис
	if (delivery == 3 || delivery == 4 || delivery == 5 || delivery == 6){
		$('#adress-block').show();
		if(delivery == 3){
			$('#postindex').hide();
		} else {
			$('#postindex').show();
		}
	} else {
		$('#adress-block').hide();
	}
}

function ShowPriceMetro() {
	calculateOrder();
	$("#pricemetro").html(' доставка: ' + MetroPrice[$('#metro').val()] + ' руб.');
}

function CheckCorrectFormOrher(){
	var error = "";
	var delivery = $('[name=delivery]:checked').val();
	if (!$('#fio').val()){
		error +="Введите ФИО<br>";
	}
	var userfio = $('#userfio').val();
	if (!userfio){
		error +="Введите ФИО получателя<br>";
	} else {
		var pr = 0;
		var tig = 0;
		var sp = 0;
		for (i=0; i < userfio.length; i++) {
			if ( userfio.substring(i,(i+1)) == ' ') {
				if (pr == 1) {
					pr = 0;
					sp++;
				}
			} else {
				pr = 1;
			}
		}
		if ( sp<2 || (sp==2 && pr==0)) {
			error +="Введите ФИО получателя, разделяя их пробелом<br>";
		}
	}

	if ($('#phone').val().length < 5){
		error +="Введите телефон<br>";
	}

	if (delivery == 1 || delivery == 3) {
		if (!$('#metro').val()||$('#metro').val()==0){
			error +="Укажите метро<br>";
		}
	}

	if (delivery == 1 || delivery == 2 || delivery == 3) {
		if (!$('#meetingdate').val()||$('#meetingdate').val()==0){
			error +="Выберите дату встречи<br>";
		}
	}


	var postindex = '';
	if (delivery == 4 || delivery == 5 || delivery == 6) {
		if (!$('#postindex').val()){
			error +="Введите индекс<br>";
		}
	}

	if (delivery == 3 || delivery == 4 || delivery == 5 || delivery == 6){
		if (!$('#adress').val()){
			error +="Введите адрес<br>";
		}
	}
	if(!$('#acsess').prop("checked")){
		error +="Вы должны согласиться с правилами<br>";
	}
	return error;
}

function CheckFormOrher(){     
	var error = CheckCorrectFormOrher();
	var delivery = $('[name=delivery]:checked').val();
	if(error){
		$("#MainBascet").html(error);
		$("#MainBascet").dialog({
			position: {
				my: 'top',
				at: 'top',
				of: $("#CheckFormOrder")
			},
			modal:true
		});
	} else {
		calculateOrder(1);
	}
}

function calculateOrder(on){
	var delivery = $('[name=delivery]:checked').val();
	var code1 = $('#code1').val();
	var code2 = $('#code2').val();
	var code3 = $('#code3').val();
	var code4 = $('#code4').val();

	$.ajax({
		url: site_dir+'shopcoins/postcalculate.php',
		type: "POST",
		data: {
			postindex: $('#postindex').val(),
			'delivery': delivery,
			'payment': $('[name=payment]:checked').val(),
			'metro': $('#metro').val(),
			'meetingdate': $('#meetingdate').val(),
			'meetingfromtime': $('#meetingfromtime').val(),
			'meetingtotime': $('#meetingtotime').val(),
		    code1:code1,
			code2:code2,
			code3:code3,
			code4:code4,
		},
		dataType: "json",
		success: function (data, textStatus) {
			FinalSum = data.FinalSum;
            
			if(!on){
				$('#price-sum').text(FinalSum);
				return;
			}

			$('#user-compare-block').show();
			$('#user-order').hide();

			errorvalue =data.error;
			bascetamount = data.bascetamount;
			bascetsum = data.bascetsum;
			bascetweight = data.bascetweight;
			discountcoupon = data.discountcoupon;
			SumName = data.SumName;


			payment = $('[name=payment]').val();

			$('#fio-result').text($('#fio').val());
			$('#userfio-result').text($('#userfio').val());
			$('#phone-result').text($('#phone').val());
			$('#delivery-result').text(data.DeliveryName);

			metrovalue = data.metro;
			if (metrovalue){
				$('#metro-block-result').show();
				$('#metro-result').text(metrovalue);
			}

			meetingdatevalue = data.meetingdate;
			if (meetingdatevalue){
			    $('#meetingdate-block-result').show();
				$('#meetingdate-result').text(meetingdatevalue);
			} else {
			     $('#meetingdate-block-result').hide();
			}

			meetingfromtimevalue = data.meetingfromtime;
			if (meetingfromtimevalue) {
				$('#meetingfromtime-block-result').show();
				$('#meetingfromtime-result').text(meetingfromtimevalue);
			}
			meetingtotimevalue = data.meetingtotime;
			if (meetingtotimevalue) {
				$('#meetingfromtime-block-result').show();
				$('#meetingtotime-result').text(meetingtotimevalue);
			}
			
			
			$('#payment-result').text(SumName);

			if (data.postindex) {
				$('#postindex-block-result').show();
				$('#postindex-result').text(data.postindex);
			}
			if ((delivery==4||delivery==3||delivery==6)&&$('#adress').val()) {
				$('#adress-block-result').show();
				$('#adress-result').text($('#adress').val());
			}
			$('#OtherInformation-result').text($('#OtherInformation').val());
			$('#bascetsum-result').text(bascetsum);
			var sum = bascetsum;
			if (data.discountcoupon){
				$('#coupon-block-result').show();
				$('#coupon-result').text($('#code1').val()+'-'+$('#code2').val()+'-'+$('#code3').val()+'-'+$('#code4').val());
				$('#discountcoupon-result').text(discountcoupon);
				//sum = eval(parseInt(bascetsum) + parseInt(discountcoupon));
			}
			$('#allprice-result').text(data.FinalSum);
			if (data.metroprice){
				$('#metro-price-block-result').show();
				$('#metro-price-result').text(data.metroprice);
				//$('#allprice-result').text(eval(parseInt(sum)+parseInt(data.metroprice)));
			}

			if(!data.error){
				$('#post-block-result').show();
				$('#post-zone-result').text(data.PostZoneNumber+(data.PostRegion?'('+data.PostRegion+')':''));
				$('#bascetpostweight-result').text(data.bascetpostweight);
				$('#postzoneprice-result').text(data.PostZonePrice);

				if (payment == 3 || payment == 4 || payment == 5 || payment == 6){
					$('#suminsurance').val(data.bascetinsurance);
					$('#bascet-suminsurance-result').text(bascetsum);
				}
				$('#bascetinsurance-result').text(data.bascetinsurance);
				//$('#allprice-result').text(data.PostAllPrice);
			}
		}
	});
}
function SubmitOrder(){

	var error = CheckCorrectFormOrher();
	if(!$('#postrulesview').prop("checked")){
		error +="Вы должны согласиться с правилами<br>";
	}

	if(error){
		$('#error-order').text(error);
	} else {
		//$('#resultform').submit();		
		submit();
	}
}

function AddName2() {
	/*
	// CONFIG 
	
	// this is id of the search field you want to add this script to. 
	// ������������� ������ �� ��������� ����
	var id = "name2";
	
	// ������� � ���� �� ����� �� ���� ������
	var defaultText2 = "";	
	
	// set to either true or false
	// when set to true it will generate search suggestions list for search field based on content of variable below
	var suggestion2 = true;
	
	// static list of suggestion options, separated by comma
	// � ����� ����� ������� ����������� ��� ��������� ��������
	//var suggestionText = MetalNameArray; 
	var suggestionText2 = MetalNameArray;
	
	// END CONFIG (do not edit below this line, well unless you really, really want to change something :) )
	
	// Peace, 
	// Alen

	var field2 = document.getElementById(id);	
	var classInactive = "sf_inactive";
	var classActive = "sf_active";
	var classText = "sf_text";
	var classSuggestion = "sf_suggestion";
	this.safari = ((parseInt(navigator.productSub)>=20020000)&&(navigator.vendor.indexOf("Apple Computer")!=-1));
	if(field2 && !safari){
		field2.value = defaultText2;
		field2.c = field2.className;		
		field2.className = field2.c + " " + classInactive;
		field2.onfocus = function(){
			this.className = this.c + " "  + classActive;
			this.value = (this.value == "" || this.value == defaultText2) ?  "" : this.value;
		};
		field2.onblur = function(){
			this.className = (this.value != "" && this.value != defaultText2) ? this.c + " " +  classText : this.c + " " +  classInactive;
			this.value = (this.value != "" && this.value != defaultText2) ?  this.value : defaultText2;
			clearList();
		};
		if (suggestion2){
			
			var selectedIndex = 0;
						
			field2.setAttribute("autocomplete", "off");
			var div = document.createElement("div");
			var list = document.createElement("ul");
			list.style.display = "none";
			div.className = classSuggestion;
			list.style.width = field2.offsetWidth + "px";
			div.appendChild(list);
			field2.parentNode.appendChild(div);	

			field2.onkeypress = function(e){
				
				var key = getKeyCode(e);
		
				if(key == 13){ // enter
					selectList();
					selectedIndex = 0;
					return false;
				};	
			};
				
			field2.onkeyup = function(e){
			
				var key = getKeyCode(e);
		
				switch(key){
				case 13:
					return false;
					break;			
				case 27:  // esc
					field2.value = "";
					selectedIndex = 0;
					clearList();
					break;				
				case 38: // up
					navList("up");
					break;
				case 40: // down
					navList("down");		
					break;
				default:
					startList();			
					break;
				};
			};
			
			this.startList = function(){
				var arr2 = getListItems(field2.value);
				if(field2.value.length > 0){
					createList(arr2);
				} else {
					clearList();
				};	
			};
			
			this.getListItems = function(value){
				var arr = new Array();
				var src = suggestionText2;
				var src = src.replace(/, /g, ",");
				var arrSrc = src.split(",");
				for(i=0;i<arrSrc.length;i++){
					if(arrSrc[i].substring(0,value.length).toLowerCase() == value.toLowerCase()){
						arr.push(arrSrc[i]);
					};
				};				
				return arr;
			};
			
			this.createList = function(arr){				
				resetList();			
				if(arr.length > 0) {
					for(i=0;i<arr.length;i++){				
						li = document.createElement("li");
						a = document.createElement("a");
						a.href = "javascript:void(0);";
						a.i = i+1;
						a.innerHTML = arr[i];
						li.i = i+1;
						li.onmouseover = function(){
							navListItem(this.i);
						};
						a.onmousedown = function(){
							selectedIndex = this.i;
							selectList(this.i);		
							return false;
						};					
						li.appendChild(a);
						list.setAttribute("tabindex", "-1");
						list.appendChild(li);	
					};	
					list.style.display = "block";				
				} else {
					clearList();
				};
			};	
			
			this.resetList = function(){
				var li = list.getElementsByTagName("li");
				var len = li.length;
				for(var i=0;i<len;i++){
					list.removeChild(li[0]);
				};
			};
			
			this.navList = function(dir){			
				selectedIndex += (dir == "down") ? 1 : -1;
				li = list.getElementsByTagName("li");
				if (selectedIndex < 1) selectedIndex =  li.length;
				if (selectedIndex > li.length) selectedIndex =  1;
				navListItem(selectedIndex);
			};
			
			this.navListItem = function(index){	
				selectedIndex = index;
				li = list.getElementsByTagName("li");
				for(var i=0;i<li.length;i++){
					li[i].className = (i==(selectedIndex-1)) ? "selected" : "";
				};
			};
			
			this.selectList = function(){
				li = list.getElementsByTagName("li");	
				a = li[selectedIndex-1].getElementsByTagName("a")[0];
				field2.value = a.innerHTML;
				clearList();
			};			
			
		};
	};
	
	this.clearList = function(){
		if(list){
			list.style.display = "none";
			selectedIndex = 0;
		};
	};		
	this.getKeyCode = function(e){
		var code;
		if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		return code;
	};*/
	
};

/*


function ShowBascet2() {

	var divstr = "";
	divstr = "showbascet2";
	myDiv = document.getElementById(divstr);
	if (myDiv.style.display=="none")
		myDiv.style.display="block";
	else
		myDiv.style.display="none";
}
<script type="text/javascript" src="ajax.php" language="JavaScript"></script>
<script type="text/javascript">	
			
function AddBascetTwo (shopcoins, amount, positioncoins)
{
	//vbhjckfd
	process ('addbascet.php?shopcoinsorder=<?echo $shopcoinsorder;?>&shopcoins=' + shopcoins + '&amount=' + amount + '&positioncoins=' + positioncoins +'');	
	//var str = '';
	//str = 'bascetshopcoin' + positioncoins;
	//myDiv = document.getElementById(str);
	//myDiv.innerHTML = '<<img src=<?echo $in?>images/wait.gif>>';
	//alert (shopcoins + " - " + amount);
}

function ShowLot(auction, num, image)
{
	//alert(navigator.userAgent.toLowerCase().search('firefox/7'));
	divstr = "show" + auction;
	myDiv = document.getElementById(divstr);
	myDiv.style.position = "absolute";
	var str = navigator.userAgent.toLowerCase();
	//alert (str);
	if (str.search('firefox/3')<=0 && str.search('firefox/4')<=0 && str.search('firefox/5')<=0 && str.search('firefox/6')<=0 && str.search('firefox/7')<=0 && str.search('firefox/8')<=0) {
	
		myDiv.style.left = 180;
		myDiv.style.top = document.body.scrollTop + event.y + 40;
		//x = myDiv.style.getAttribute("posLeft");
		//y = myDiv.style.getAttribute("posTop");
	}
	else {
		myDiv.style.left = 320;
		myDiv.style.top = (415+num*40);
	}
	
	myDiv.innerHTML = image;
	
	
}

function NotShowLot(auction)
{
	divstr = "show" + auction;
	myDiv = document.getElementById(divstr);
	myDiv.innerHTML = "";
	
}

	function showsinonim(val) {
		myDiv = document.getElementById(val);		
		var str = '';		
		myDiv.innerHTML = <? echo JavaString($RelationText); ?>;

	}
	window.setTimeout("showsinonim('MainBascet');",500);
</script>


<script language="JavaScript">

function CheckingValue ()
{
	myDiv = document.getElementById("Image_Big");
	myDiv.innerHTML = "<img src=<?echo $cfg['site_dir']?>images/wait.gif>";

	str = document.mainform.brightnessvalue.value;
	str = parseInt(str);
	if (str < -100 || str > 100)
	{
		alert ('Значение должно быть >= -100 и <= 100');
	}
	else
	{
		myDiv = document.getElementById("Image_Big");
		myDiv.innerHTML = '<img src=http://www.numizmatik.ru/shopcoins/photoshop7.php?Image=<? echo (trim($imagebig)?$imagebig:$imagest); ?>&brightnessvalue=' + str + ' alt=\"<? echo $namest;?>\" border=1>';;
	}
	window.event.cancelBubble=true;
	window.event.returnValue=false;
}

function ShowLot(auction, num, image)
{
	//alert(navigator.userAgent.toLowerCase().search('firefox/3'));
	divstr = "show" + auction;
	myDiv = document.getElementById(divstr);
	myDiv.style.position = "absolute";
	var str = navigator.userAgent.toLowerCase();
	var str = navigator.userAgent.toLowerCase();
	if (str.search('firefox/3')<=0) {
	
		myDiv.style.left = 180;
		myDiv.style.top = document.body.scrollTop + event.y + 40;
		//x = myDiv.style.getAttribute("posLeft");
		//y = myDiv.style.getAttribute("posTop");
	}
	else {
		myDiv.style.left = 180;
		myDiv.style.top = (215+num*40);
	}
	
	myDiv.innerHTML = image;
	
}

function NotShowLot(auction)
{
	divstr = "show" + auction;
	myDiv = document.getElementById(divstr);
	myDiv.innerHTML = "";
	
}

</script>

<script type="text/javascript" src="ajax.php" language="JavaScript"></script>
<script language="JavaScript">

function ShowBascet2() {

	var divstr = "";
	divstr = "showbascet2";
	myDiv = document.getElementById(divstr);
	if (myDiv.style.display=="none")
		myDiv.style.display="block";
	else
		myDiv.style.display="none";
}

function ShowMarkCoin (xmlRoot) {

	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	if (errorvalue == 'none')
	{
		markusers2 = parseInt(xmlRoot.getElementsByTagName("markusers2").item(0).firstChild.data);
		marksum2 = parseInt(xmlRoot.getElementsByTagName("marksum2").item(0).firstChild.data);
		var str = '<strong>Оценить товар:</strong> (';
		switch (marksum2) {
		
			case 10: str += '<img src=\"../images/star10.gif\" border=0>'; break;
			case 8: str += '<img src=\"../images/star8.gif\" border=0>'; break;
			case 6: str += '<img src=\"../images/star6.gif\" border=0>'; break;
			case 4: str += '<img src=\"../images/star4.gif\" border=0>'; break;
			case 2: str += '<img src=\"../images/star2.gif\" border=0>'; break;
			default: str += '<img src=\"../images/star0.gif\" border=0>'; break;
		}
		
		str += ' - '+markusers2+' оценок(ки))<br>';
		
		myDiv = document.getElementById('MarkCoinsDiv');
		myDiv.innerHTML = str;
	}
	else if (errorvalue == 'error1')
	{
		alert ('Вы не авторизованы. Пройдите авторизацию.');
		
	}
	else if (errorvalue == 'error2')
	{
		
		alert ('Вы не авторизованы. Пройдите авторизацию.');

	}
	else if (errorvalue == 'error3')
	{
		alert ('Неверные параметры запроса');
	}
	else if (errorvalue == 'error4')
	{
		alert ('Неверные параметры запроса.');
	}
}


function AddBascetTwo (shopcoins, amount, positioncoins)
{
	//vbhjckfd
	process ('addbascet.php?shopcoinsorder=<?echo $shopcoinsorder;?>&shopcoins=' + shopcoins + '&amount=' + amount + '&positioncoins=' + positioncoins +'<?=cookiesWork() ? '' : '&'.SID?>');	
	var str = '';
	str = 'bascetshopcoin' + positioncoins;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = '<<img src=<?echo $cfg['site_dir']?>images/wait.gif>>';
	//alert (shopcoins + " - " + amount);
}
</script>
*/

//заказ
function ShowFormPhonePostReceipt(order)
{
	$('#MainBascet').html($('#PhonePostReceipt'+order).html());
	$("#MainBascet").dialog({
        	position: { 
                my: 'top',
                at: 'top',
                of: $("#order"+order)
            },
     		modal:true,
     		width:300,
    		open: function(event, ui){
            }
     }); 	
}
				
function ShowSertificate(paymenttype,orderthis) {

	var str = '<form class=formtxt name=FormCoupon><table width=90% cellpadding=2 cellspacing=1 border=0 align=center><input type=hidden name=orderthis value='+orderthis+'>';
	str += '<tr><td class=txt bgcolor=#EBE4D4 colspan=2> Если у Вас есть подарочные сертификаты и Вы желаете их использовать для оплаты данного заказа, введите номер и код в нижеприведенной форме. В ином случае оставьте поле пустым.</td></tr>';
	str += '<tr><td class=txt width=40% bgcolor=#EBE4D4 align=right><strong>Номер сертификата (полностью):</strong><td class=txt bgcolor=#EBE4D4><input type=text name="numbersert" value="" size=6 maxlength=6 class=formtxt></td></tr>';
	str += '<tr><td class=txt width=40% bgcolor=#EBE4D4 align=right><strong>Код сертификата:</strong><td class=txt bgcolor=#EBE4D4><input type=text name="code1" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code2" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code3" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code4" value="" size=4 maxlength=4 class=formtxt></td></tr>';
	str += '<tr><td class=txt width=40% bgcolor=#EBE4D4> <div id="CouponInfo" name="CouponInfo">&nbsp;</div></td><td class=txt bgcolor=#EBE4D4><input type=button class=formtxt value="Воспользоваться подарочным сертификатом" onClick="javascript:CheckFormCoupon('+paymenttype+');"></td></tr>';
	str += '</table></form>';
	myDiv = document.getElementById("sertificate"+orderthis);
	myDiv.innerHTML = str;
}
				


function ShowSert() {

	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		
		var orderin = xmlRoot.getElementsByTagName("orderin").item(0).firstChild.data;
		
		if (orderin == "none") {
		
			alert ('нет заказа либо он уже отправлен или оплачен');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>нет заказа либо он уже отправлен или оплачен</font>';
		
		}
		else {
		
			var dissum = xmlRoot.getElementsByTagName("dissum").item(0).firstChild.data;
			var deltasum = xmlRoot.getElementsByTagName("deltasum").item(0).firstChild.data;
			var crcode = xmlRoot.getElementsByTagName("crcode").item(0).firstChild.data;
			var outsum = xmlRoot.getElementsByTagName("outsum").item(0).firstChild.data;
			var cardis = xmlRoot.getElementsByTagName("cardis").item(0).firstChild.data;
			
			alert ('Сумма '+dissum+' руб. использована в оплате заказа, остаток  на сертификате ' + (deltasum=="none"? "0.00": deltasum) + ' руб.');
			
			document.FormCoupon.code1.value = '';
			document.FormCoupon.code2.value = '';
			document.FormCoupon.code3.value = '';
			document.FormCoupon.code4.value = '';
			
			myDiv2 = document.getElementById('CouponInfo');
			
			myDiv2.innerHTML = 'Сумма '+dissum+' руб. использована в оплате заказа, остаток  на сертификате ' + (deltasum=="none"? "0.00": deltasum) + ' руб.';
			
			if (cardis != "none") {
			
				if (crcode != "none" && outsum != "none") {
				
					document.getElementById('SignatureValue'+orderin).value = crcode;
					document.getElementById('OutSum'+orderin).value = outsum;
					
					myDiv5 = document.getElementById('info'+orderin);
			
					myDiv5.innerHTML = outsum + ' руб.';
				
				}
				else {
				
					myDiv5 = document.getElementById('info'+orderin);
			
					myDiv5.innerHTML = 'Произошел сбой в системе - оплату картами VISA и MaterCard Вы можете произвести в "Ваши заказы"';
				}
				
			}
		}
	}
	else 
	{
		//alert('3');
		if (errorvalue == "error0") {
		
			alert ('Неверно введен код либо номер сертификата');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен код либо номер сертификата</font>';
		}
		else if (errorvalue == "error1") {
		
			alert ('Неверно введен номер сертификата');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен номер сертификата</font>';
		}
		else if (errorvalue == "error2") {
		
			alert ('неверные символы в коде');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>неверные символы в коде</font>';
		}
		else if (errorvalue == "error3") {
		
			alert ('нет заказа либо он уже отправлен или оплачен');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>нет заказа либо он уже отправлен или оплачен</font>';
		}
		else if (errorvalue == "error4") {
		
			alert ('Неверно введен код');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен код</font>';
		}
		else if (errorvalue == "error5") {
		
			alert ('Сертификат уже был полностью использован.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Сертификат уже был полностью использован.</font>';
		}
		else if (errorvalue == "error6") {
		
			alert ('Время действия купона истекло.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Время действия купона истекло.</font>';
		}
		else if (errorvalue == "error7") {
		
			alert ('Сумма заказа равна 0 руб.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Купон не принят, так как сумма заказа уже равна 0 руб.</font>';
		}
		else if (errorvalue == "error8") {
		
			alert ('Сертификат уже был полностью использован.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Сертификат уже был полностью использован.</font>';
		}
		
	}
}

function ShowMainCoins(coinsmain, image,details)
{
	//alert(navigator.userAgent.toLowerCase().search('firefox/3'));
    if(!image) return;
	var str = image;
	//divstr = ";
	//myDiv2 = $('#image' + coinsmain);
	//var posX = getPosX(myDiv2);
	//var posY = getPosY(myDiv2);
	//myDiv = $("#imageshow");
	//myDiv.style.position = "absolute";
	if (coinsmain == "popular"){
		//myDiv.style.left = posX - 450;
	} else {
		//myDiv.style.left = posX - 150;
	}
	$("#MainBascet").html(str);
	$("#MainBascet").dialog({
        	position: { 
                my: 'top',
                at: 'top',
                of: $('#image' + coinsmain)
            },
     		modal:true,
    		open: function(event, ui){
    		   var $this = $(this); 
    		   setTimeout(function(){$this.dialog('close');}, 2000);
            }
    	});    
	//myDiv.style.top = posY -200;
	//myDiv.html(str);
	
}

function showInvis(atrr){
	 var btn = $('#'+atrr);
	 var is_visible = btn.is(':visible')?true:false;
	 if(!is_visible){
         btn.show();
    } else {
        btn.hide();           
    }
}