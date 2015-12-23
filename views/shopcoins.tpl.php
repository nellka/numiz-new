<script>	
//рейтинг товара
function initRaiting(id,total_reiting){
    var star_widht = total_reiting*17 ;
    $('#raiting_star'+id+' #raiting_votes').width(star_widht);

    $('#raiting_star'+id+' #raiting').hover(function() {
            $('#raiting_votes, #raiting_hover').toggle();
        },
        function() {
        $('#raiting_votes,#raiting_hover').toggle();
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
                	console.log(data.marksum2);   
                	console.log((data.marksum2)*17/2 );   
                	console.log(data);         	
	                 $('#raiting_info'+id).text(data.markusers2+' оценка(ок)');  
                	/*$('#raiting_info h5, #raiting_info img').toggle();
                	//$.cookies.set('article'+id_arc, 123, {hoursToLive: 1}); // создаем куку 	*/
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
	
function ShowOneClick(id) {
	$('.oneclick_notifications .messages').hide();	
	if ($('#oneshopcoins'+id+' .messages').children().length > 0) {
		$('#oneshopcoins'+id+' .messages').fadeIn("fast");
	}

	$(".bg_shadow").show();
	var yourClick = true;
					
	$(document).mouseup(function (e) {
	var container = $('#oneshopcoins'+id+' .messages');
	if (container.has(e.target).length === 0){
		container.hide();
		$(".bg_shadow").hide();
	}
	});
			
}
function HideOneClick(id) {
	$('#oneshopcoins'+id+' .messages').fadeOut("fast");
	$(".bg_shadow").fadeOut("fast");
	$(document).unbind("click.myEvent");
}

function ValidPhone(phone) {
    var re = /^\d[\d\(\)\ -]{8,14}\d$/;
    var myPhone = phone;
    var valid = re.test(myPhone);
    if (valid) return true;
    return false;
}  

function AddOneClick(id) {
   var onefio = $('#oneshopcoins'+id+' .messages').find("input[name='onefio']").val();
   var onephone = $('#oneshopcoins'+id+' .messages').find("input[name='onephone']").val();
   
   if (!onefio || onefio.length <3){
		$('#oneshopcoins'+id+'-error').text('Вы не указали имя');
		return;
   }
   
   if (!ValidPhone(onephone)){
		$('#oneshopcoins'+id+'-error').text('Укажите номер телефона в полном формате!');
		return;
   }
    
   $.ajax({
	    url: '<?=$cfg['site_dir']?>shopcoins/addoneklick.php', 
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
	
	if (!errorvalue){
		var shopcoinsorder = data.shopcoinsorder;	
		var bascetsum = data.bascetsum;			
		var str = '';
		str += '<h4>Ваш заказ №</strong> ' + shopcoinsorder + ' </h4>';
		str += '<p><strong>Товаров:</strong> 1 <br><strong>На сумму:</strong> ' + bascetsum + ' р.';
		str += '<br>Заказ сделан, Для уточнения деталей платежа и доставки с Вами свяжется наш менеджер.<br><br></p>';
		$('#oneclick_form'+id).html(str);
	} else if (errorvalue == 'reserved') {
		$('#oneshopcoins'+id+'-error').text('Товар зарезервирован одновременно с другим пользователем');		
	} else if (errorvalue == 'notavailable') {		
		$('#oneshopcoins'+id+'-error').text('Товар уже продан');	
	} else if (errorvalue == 'stopsummax') {
		$('#oneshopcoins'+id+'-error').text('Минимальная сумма заказа <? echo $minpriceoneclick;?> руб.');		
	}	else if (errorvalue == 'amount') {
		erroramountvalue = data.erroramount;		
		$('#oneshopcoins'+id+'-error').text('На складе всего лишь ' + erroramountvalue + ' штук');		
	}
}	
	
function sendData(name,val){   
    if(name){
        $('#'+name).val(val);
    }
    console.log($('form#search-params'));
    $('#pricestart').val($('#amount1').val());
    $('#priceend').val($('#amount2').val());
    
    $('#yearstart').val($('#amount3').val());
    $('#yearend').val($('#amount4').val());
    $('form#search-params').submit();
}

function AddAccessory(id,materialtype){	
	var str;
	var amount = $('#amount'+id).val();
	if(amount <=0) amount = 1;
    $.ajax({	
	    url: '<?=$cfg['site_dir']?>shopcoins/addbascet.php?', 
	    type: "POST",
	    data:{'shopcoinsorder':"<?=$shopcoinsorder?>",'shopcoins':id,'amount':amount,'materialtype':materialtype,'datatype':'json'},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 	    	
	        ShowSmallBascet(id,data);
	    }
	});
	
	return false;		
}
function ShowSmallBascet (id,data)
{
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
		
		$("#inorderamount").html(bascetamount);				
    		var str = '';
    		str = '<h1 class="yell_b">Корзина</h1>';
    		str += '<p><b>Заказ №</b> ' + shopcoinsorder + '</p>';
    		str += '<p><strong>Товаров:</strong> ' + bascetamount + ' <br><b>На сумму:</b> ' + bascetsum + ' р.';
    		str += '<br><b>Вес ~ </b> ' + bascetweight + ' гр. <br><b>Бронь на:</b> ' + bascetreservetime + '</p>'
    		str += '<p><center><a href=?page=orderdetails><img src=<?=$cfg['site_dir']?>images/basket.gif border=0></a></center></p>';
    		str += '<h1 class="yell_b">Доставка:</font></h1>';
    		str += '<p><b>Москва:</b><br><b>Кольцевые станции:</b> бесплатно<br><b>В офис:</b> от 150 р.';
    		str += '<br><br><b>Почта России</b><br><b>Сбор по весу:</b> от ' + bascetpostweightmin + ' до ' + bascetpostweightmax + ' р.';
    		str += '<br><b>Страховка 4%:</b> ' + bascetinsurance + ' р. <br><b>Упаковка:</b> 10 р. за конверт / ящик.</<p>';
    		
    		$("#MainBascet").html(str);				
    		$("#bascetshopcoins" + bascetshopcoins).html('<img src="<?=$cfg['site_dir']?>images/corz7.gif" title="Уже в корзине" alt="Уже в корзине">');		
    	} else if (data.error == 'reserved'){
    		$("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>Товар зарезервирован одновременно с другим пользователем</p>');	
    		$("#bascetshopcoins" + bascetshopcoins).html('<img src="<?=$cfg['site_dir']?>images/corz6.gif" title="Уже в корзине" alt="Уже в корзине">');
    		
    	}	else if (data.error == 'notavailable'){	
    		$("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>Товар уже продан</p>');	
    		$("#bascetshopcoins" + bascetshopcoins).html('<img src="<?=$cfg['site_dir']?>images/corz6.gif" title="Уже в корзине" alt="Уже в корзине">');
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
    		 buttons:{
    		    Ok: function(){
    		      $(this).dialog( "close" );
    		    }
    		  }
    	});
    }
    

function AddReview(id) {
	if(!id)	return;

	var review = $('#reviewcointext').val();
	console.log($('#reviewcointext'))
	if (!review) {	
	    $("#error-review").text('Заполните отзыв.');
		return;
	}	
	$.ajax({
	    url: '<?=$cfg['site_dir']?>shopcoins/addreview.php', 
	    type: "POST",
	    data:{'shopcoins':id,'review':review},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 
	        if (!data.error){
        		$('#emptyreview').hide();
        		var old_review = $("#reviewsdiv").html();        		
        		$("#reviewsdiv").html("<div id='review"+id+"'><b>"+data.dateinsert+" "+data.fio+"</b>"+ data.review+"</div>"+old_review);
        		$("#error-review").text('Спасибо за Ваш отзыв!');     
	        } else if (data.error == 'error1') {
        		$("#error-review").text('Вы не авторизованы. Пройдите авторизацию.');        		
        	} else if (data.error == 'error3') {
        		$("#error-review").text('Неверные параметры запроса');
        	} else if (data.error == 'error4'){
        		$("#error-review").text('Вы уже оставляли отзыв на данную позицию');
        	}        	       
       }
	});	
}

function AddNominal(){
    $.ajax({
	    url: '<?=$cfg['site_dir']?><?=$cfg['site_dir']?>shopcoins/detailscoins/addname.php', 
	    type: "POST",
	    data:{'group':$('#group2').val()},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 
	      console.log(data);  
	      ShowNameCoins();     
       }
	});

}	
function  CheckSubmitPrice(){
    console.log(10);
}
//var TextGroup = "'.$groupselect_v2.'";
//this.group = AutoInput("group2",);
//this.name = AutoInput("name2",MetalNameArray);
function AddGroup2() {
    console.log('jjj');
	/*
	// CONFIG 
	
	// this is id of the search field you want to add this script to. 
	// ������������� ������ �� ��������� ����
	var id = "group2";
	
	// ������� � ���� �� ����� �� ���� ������
	var defaultText = "";	
	
	// set to either true or false
	// when set to true it will generate search suggestions list for search field based on content of variable below
	var suggestion = true;
	
	// static list of suggestion options, separated by comma
	// � ����� ����� ������� ����������� ��� ��������� ��������
	var suggestionText =  TextGroup;
	
	// END CONFIG (do not edit below this line, well unless you really, really want to change something :) )
	
	// Peace, 
	// Alen

	var field = document.getElementById(id);	
	var classInactive = "sf_inactive";
	var classActive = "sf_active";
	var classText = "sf_text";
	var classSuggestion = "sf_suggestion";
	this.safari = ((parseInt(navigator.productSub)>=20020000)&&(navigator.vendor.indexOf("Apple Computer")!=-1));
	if(field && !safari){
		field.value = defaultText;
		field.c = field.className;		
		field.className = field.c + " " + classInactive;
		field.onfocus = function(){
			this.className = this.c + " "  + classActive;
			this.value = (this.value == "" || this.value == defaultText) ?  "" : this.value;
		};
		field.onblur = function(){
			this.className = (this.value != "" && this.value != defaultText) ? this.c + " " +  classText : this.c + " " +  classInactive;
			this.value = (this.value != "" && this.value != defaultText) ?  this.value : defaultText;
			clearList();
		};
		if (suggestion){
			
			var selectedIndex = 0;
						
			field.setAttribute("autocomplete", "off");
			var div = document.createElement("div");
			var list = document.createElement("ul");
			list.style.display = "none";
			div.className = classSuggestion;
			list.style.width = field.offsetWidth + "px";
			div.appendChild(list);
			field.parentNode.appendChild(div);	

			field.onkeypress = function(e){
				
				var key = getKeyCode(e);
		
				if(key == 13){ // enter
					selectList();
					selectedIndex = 0;
					return false;
				};	
			};
				
			field.onkeyup = function(e){
			
				var key = getKeyCode(e);
		
				switch(key){
				case 13:
					return false;
					break;			
				case 27:  // esc
					field.value = "";
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
				var arr = getListItems(field.value);
				if(field.value.length > 0){
					createList(arr);
				} else {
					clearList();
				};	
			};
			
			this.getListItems = function(value){
				var arr = new Array();
				var src = suggestionText;
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
				field.value = a.innerHTML;
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
<?
/*


function AddAccessory_3(shopcoins){
	var str;
	str = document.mainform.amount + shopcoins + value;
	document.mainform.shopcoinsorder.value = shopcoins;
	document.mainform.shopcoinsorderamount.value = eval(str);
	if (eval(str) > 0)
	{
		//document.mainform.submit();
		process ('addbascet.php?shopcoinsorder=".$shopcoinsorder."&shopcoins=' + shopcoins + '&amount=' + eval(str));
	}
	else
		alert ('Введите количество');
}

function WaitSubscribeCatalog(catalog)
{
	
	var str = "mysubscribecatalog"+catalog;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = "<img src=<?echo $in?>images/wait.gif>";
}

function AddSubscribeCatalog ()
{
  error = xmlRoot.getElementsByTagName("error");
  errorvalue = error.item(0).firstChild.data;

  if (errorvalue == 'none')
  {
    var valueid = '';
    var value = '';
    valueid = xmlRoot.getElementsByTagName("valueid").item(0).firstChild.data;
    value = xmlRoot.getElementsByTagName("value").item(0).firstChild.data;

    if (valueid != "")
    {
      myDiv = document.getElementById("mysubscribecatalog" + valueid);
      myDiv.innerHTML = '<b><font color=silver>������ �������</font></b>';
    }
  }
  else if (errorvalue == 'auth')
  {
    myDiv = document.getElementById("mysubscribecatalog" + valueid);
    myDiv.innerHTML = '<b><font color=silver>�� �� ������������</font></b>';
  }
  else
  {
    var valueid = '';
    valueid = xmlRoot.getElementsByTagName("valueid").item(0).firstChild.data;

    myDiv = document.getElementById("mysubscribecatalog" + valueid);
    myDiv.innerHTML = '<font color=red><b>' + errorvalue + '</b></font>';
  }
}

function AddBascet (shopcoins, amount)
{
	//vbhjckfd
	process ('addbascet.php?shopcoinsorder=<?echo $shopcoinsorder;?>&shopcoins=' + shopcoins + '&amount=' + amount + '<?= cookiesWork() ? '' : '&'.SID ?>');
	var str = '';
	str = 'bascetshopcoins' + shopcoins;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = '<img src=<?echo $in;?>images/wait.gif>';
	//alert (shopcoins + " - " + amount);
}

function AddNext (shopcoins, amount)
{
	//vbhjckfd
	//window.open ('addinorder.php?shopcoins=' + shopcoins + '&amount=' + amount + '<?= cookiesWork() ? '' : '&'.SID ?>');
	process ('addnext.php?shopcoins=' + shopcoins + '&amount=' + amount + '<?= cookiesWork() ? '' : '&'.SID ?>');
	var str = '';
	str = 'bascetshop' + shopcoins;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = '<img src=<?echo $in;?>images/wait.gif>';
	//alert (shopcoins + " - " + amount);
}


function ShowNext (xmlRoot)
{

	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	var bascetshopcoins = '';	
	bascetshopcoins = xmlRoot.getElementsByTagName("bascetshopcoins").item(0).firstChild.data;
	datereserve = xmlRoot.getElementsByTagName("datereserve").item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		alert ('�� � ������� �� ������� ������, � ������ ������ �� ������� ����������� ���������� ������ ����� ������������� �� ���� � ������� 5 �����. ���� ����� ����� ������������� �� '+datereserve);
		
		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz77.gif border=0 alt="�� � ������� �� ������">';
	}
	else if (errorvalue == 'reserved')
	{
		alert ('����� �������������� ������������ � ������ �������������');

		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz6.gif border=0 alt="��� � �������">';
		
	}
	else if (errorvalue == 'notavailable')
	{
		
		alert ('����� ��� ������');
		
		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz6.gif border=0 alt="��� � �������">';

	}
	else if (errorvalue == 'stopsummax')
	{
		alert ('������������ ����� ������ <? echo $stopsummax;?> ���. \n���� �� �� ��� ������� � �������, ���������� ��������� �������."');
	}
	
}

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

	function AddAccessory(shopcoins)
			{
				var str='';
				str = document.getElementById('amount' + shopcoins).value;
				//document.mainform.shopcoinsorder.value = shopcoins;
				//document.mainform.shopcoinsorderamount.value = eval(str);
				//alert (eval(str) + shopcoins);
				if (eval(str) > 0)
				{
					//document.mainform.submit();
					process ('addbascet.php?shopcoinsorder="+<? echo $shopcoinsorder; ?> + "&shopcoins=' + shopcoins + '&amount=' + eval(str));
				}
				else
					alert ('������� ����������');
			}
			
			

function AddBascet (shopcoins, amount)
{
	//vbhjckfd
	process ('addbascet.php?shopcoinsorder=<? echo $shopcoinsorder;?>&shopcoins=' + shopcoins + '&amount=' + amount + '');	
	//window.setTimeout("reload(GET=true);",500);
	//alert (shopcoins + " - " + amount);
}

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

function AddBrightness ()
{
	myDiv = document.getElementById("Image_Big");
	myDiv.innerHTML = "<img src=<?echo $cfg['site_dir']?>images/wait.gif>";
	var digit = 10;
	var str = 0;
	str = document.mainform.brightnessvalue.value;
	var returnvalue = digit + parseInt(str);
	if (returnvalue > 100)
	{
		alert ('Значение должно быть <= -100');
		window.event.cancelBubble=true;
		window.event.returnValue=false;
	} else {
		document.mainform.brightnessvalue.value = returnvalue;
		myDiv = document.getElementById("Image_Big");
		myDiv.innerHTML = '<img src=http://www.numizmatik.ru/shopcoins/photoshop7.php?Image=<? echo (trim($imagebig)?$imagebig:$imagest); ?>&brightnessvalue=' + returnvalue + ' alt=\"<? echo $namest;?>\" border=1>';;
	}
}

function ReduceBrightness ()
{
	myDiv = document.getElementById("Image_Big");
	myDiv.innerHTML = "<img src=<?echo $cfg['site_dir']?>images/wait.gif>";

	var digit = 10;
	var str = 0;
	str = document.mainform.brightnessvalue.value;
	var returnvalue = parseInt(str) - digit ;
	if (returnvalue < -100)
	{
		alert ('Значение должно быть >= -100');
		window.event.cancelBubble=true;
		window.event.returnValue=false;
	} else {
		document.mainform.brightnessvalue.value = returnvalue;
		myDiv = document.getElementById("Image_Big");
		myDiv.innerHTML = '<img src=http://www.numizmatik.ru/shopcoins/photoshop7.php?Image=<? echo (trim($imagebig)?$imagebig:$imagest); ?>&brightnessvalue=' + returnvalue + ' alt=\"<? echo $namest;?>\" border=1>';;
	}
}

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


function AddAccessory(shopcoins)
{
	var str;
	str = document.getElementById('amount' + shopcoins).value;
	//document.mainform.shopcoinsorder.value = shopcoins;
	//document.mainform.shopcoinsorderamount.value = eval(str);
	//alert (eval(str) + shopcoins);
	if (eval(str) > 0)
	{
		//document.mainform.submit();
		process ('addbascet.php?shopcoinsorder="+<? echo $shopcoinsorder; ?> + "&shopcoins=' + shopcoins + '&amount=' + eval(str));
	}
	else
		alert ('Введите количество');
}

function getRadioGroupValue(radioGroupObj)
{
  for (var i=0; i < radioGroupObj.length; i++)
    if (radioGroupObj[i].checked) return radioGroupObj[i].value;

  return null;
}
			
function AddMark(user,shopcoins) {

	if(!shopcoins)
		return;
	//alert(user);
	var marks = getRadioGroupValue(document.markcoin.marks);
	//alert(marks);
	if (!marks) {
		
		alert('Выберите оценку.');
		return;
	}
	
	if (!user) {
		
		alert('Вы не авторизованны.');
		return;
	}
	//alert('addmark.php?mark='+marks+'&user='+user+'&shopcoins='+shopcoins);
	process('addmark.php?mark='+marks+'&user='+user+'&shopcoins='+shopcoins);
	
}

function AddReview(user,shopcoins) {

	if(!shopcoins)
		return;
	//alert(user);
	var review = document.reviewcoin.reviewcointext.value;
	//alert(marks);
	if (!review) {
		
		alert('Заполните отзыв.');
		return;
	}
	
	if (!user) {
		
		alert('Вы не авторизованны.');
		return;
	}
	//alert (review);
	//alert('addreview.php?review='+encodeURI(review)+'&user='+user+'&shopcoins='+shopcoins);
	process('addreview.php?review='+encodeURI(review)+'&user='+user+'&shopcoins='+shopcoins);
}

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

			

function AddBascet (shopcoins, amount)
{
	//vbhjckfd
	process ('addbascet.php?shopcoinsorder=<?echo $shopcoinsorder;?>&shopcoins=' + shopcoins + '&amount=' + amount + '<?=cookiesWork() ? '' : '&'.SID?>');	
	var str = '';
	str = 'bascetshopcoins' + shopcoins;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = '<img src=<?echo $cfg['site_dir']?>images/wait.gif>';
	//alert (shopcoins + " - " + amount);
}

function AddNext (shopcoins, amount)
{
	//vbhjckfd
	//window.open ('addinorder.php?shopcoins=' + shopcoins + '&amount=' + amount + '<?= cookiesWork() ? '' : '&'.SID ?>');
	process ('addnext.php?shopcoins=' + shopcoins + '&amount=' + amount + '<?= cookiesWork() ? '' : '&'.SID ?>');
	var str = '';
	str = 'bascetshop' + shopcoins;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = '<img src=<?echo $cfg['site_dir'];?>images/wait.gif>';
	//alert (shopcoins + " - " + amount);
}

function ShowNext (xmlRoot)
{

	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	var bascetshopcoins = '';	
	bascetshopcoins = xmlRoot.getElementsByTagName("bascetshopcoins").item(0).firstChild.data;
	datereserve = xmlRoot.getElementsByTagName("datereserve").item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		alert ('Вы в очереди на покупку монеты, в случае отказа от покупки предыдущего покупателя монета будет забронирована за вами в течении 5 часов. Ваша бронь будет действительна до '+datereserve);
		
		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $cfg['site_dir']; ?>images/corz77.gif border=0 alt="Вы в очереди на монету">';
	}
	else if (errorvalue == 'reserved')
	{
		alert ('Товар зарезервирован одновременно с другим пользователем');

		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $cfg['site_dir']; ?>images/corz6.gif border=0 alt="Уже в корзине">';
		
	}
	else if (errorvalue == 'notavailable')
	{
		
		alert ('Товар уже продан');
		
		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $cfg['site_dir']; ?>images/corz6.gif border=0 alt="Уже в корзине">';

	}
	else if (errorvalue == 'stopsummax')
	{
		alert ('Максимальная сумма заказа <? echo $stopsummax;?> руб. \nЕсли вы не все сложили в корзину, проделайте следующим заказом."');
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

function ShowSmallBascet (xmlRoot)
{
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;

	var bascetshopcoins = '';
	bascetshopcoins = xmlRoot.getElementsByTagName("bascetshopcoins").item(0).firstChild.data;
	positioncoins = xmlRoot.getElementsByTagName("positioncoins").item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		var shopcoinsorder = '';
		var bascetamount = '';
		var bascetsum = '';
		var bascetweight = '';
		var bascetreservetime = '';
		var bascetpostweightmin = '';
		var bascetpostweightmax = '';
		var bascetinsurance = '';
		var textbascet2 = '';
		
		shopcoinsorder = xmlRoot.getElementsByTagName("shopcoinsorder").item(0).firstChild.data;
		bascetamount = xmlRoot.getElementsByTagName("bascetamount").item(0).firstChild.data;
		bascetsum = xmlRoot.getElementsByTagName("bascetsum").item(0).firstChild.data;
		bascetsumclient = xmlRoot.getElementsByTagName("bascetsumclient").item(0).firstChild.data;
		bascetweight = xmlRoot.getElementsByTagName("bascetweight").item(0).firstChild.data;
		bascetreservetime = xmlRoot.getElementsByTagName("bascetreservetime").item(0).firstChild.data;
		bascetpostweightmin = xmlRoot.getElementsByTagName("bascetpostweightmin").item(0).firstChild.data;
		bascetpostweightmax = xmlRoot.getElementsByTagName("bascetpostweightmax").item(0).firstChild.data;
		bascetinsurance = xmlRoot.getElementsByTagName("bascetinsurance").item(0).firstChild.data;
		textbascet2 = xmlRoot.getElementsByTagName("textbascet2").item(0).firstChild.data;
		
		myDiv = document.getElementById("inorderamount");
		myDiv.innerHTML = "В вашей корзине <a href=<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails> "+bascetamount+" </a> товаров, <a href=<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails><img src=<?=$cfg['site_dir']?>images/basket.gif border=0></a>";
		
		var str = '';
		str = '<table border=0 cellpadding=3 cellspacing=0 style="border:thin solid 1px #000000" id=tableshopcoinsorder width=180>';
		str += '<tr class=tboard bgcolor=#006699><td><strong><font color=white>Корзина:</font></strong></td></tr>';
		str += '<tr class=tboard bgcolor=#ffcc66><td class=tboard align=top><strong>Заказ №</strong> ' + shopcoinsorder + ' ';
		str += '<br><strong>Товаров:</strong> ' + bascetamount + ' <br><strong>На сумму:</strong> ' + bascetsum + ' р.';
		if (bascetsumclient>0) {
			str += '<br><strong>Для постоянных клиентов:</strong> ' + bascetsumclient + ' р.';
		}
		str += '<br><strong>Вес ~ </strong> ' + bascetweight + ' гр. <br><strong>Бронь на:</strong> ' + bascetreservetime + '<br><center><a href=index.php?page=orderdetails><img src=<?echo $cfg['site_dir'];?>images/basket.gif border=0></a></center></td></tr>';
		str += '<tr class=tboard bgcolor=#006699><td><strong><font color=white>Доставка:</font></strong></td></tr>';
		str += '<tr class=tboard bgcolor=#ffcc66><td class=tboard align=top><strong>Москва:</strong><br><strong>Кольцевые станции:</strong> бесплатно<br><strong>В офис:</strong> от 170 р.';
		str += '<br><br><strong>Почта России</strong><br><strong>Сбор по весу:</strong> от ' + bascetpostweightmin + ' до ' + bascetpostweightmax + ' р.';
		str += '<br><strong>Страховка 4%:</strong> ' + bascetinsurance + ' р. <br><strong>Упаковка:</strong> 10 р. за конверт / ящик.</td></tr>';
		str +='<tr class=tboard bgcolor=#ffcc66><td><div style="display:none" id=showbascet2>'+textbascet2+'</div></td></tr>'
		str += '<tr class=tboard bgcolor=#EBE4D4><td align=center><img src=../images/windowsmaximize.gif onclick="ShowBascet2();" alt="Посмотреть содержимое"/></td></tr></table>';
		
		myDiv = document.getElementById("MainBascet");
		myDiv.innerHTML = str;
		
		myDiv.style.position = "absolute";
		myDiv.style.left = 797;
		myDiv.style.top = document.body.scrollTop;

		
		var str = '';
		if (positioncoins>0) str = 'bascetshopcoin' + positioncoins;
		else str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $cfg['site_dir']; ?>images/corz7.gif border=0 alt="Уже в корзине">';

	}
	else if (errorvalue == 'reserved')
	{
		alert ('Товар зарезервирован одновременно с другим пользователем');

		var str = '';
		if (positioncoins>0) str = 'bascetshopcoin' + positioncoins;
		else str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $cfg['site_dir']; ?>images/corz6.gif border=0 alt="Уже в корзине">';
	}
	else if (errorvalue == 'notavailable')
	{
		alert ('Товар уже продан');
		
		var str = '';
		if (positioncoins>0) str = 'bascetshopcoin' + positioncoins;
		else str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		alert (str);
		myDiv.innerHTML = '<img src=<? echo $cfg['site_dir']; ?>images/corz6.gif border=0 alt="Уже в корзине">';
	}
}

</script>
*/?>
</script>

<?
if($tpl['task']){
     include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php'; 
} else {
    include $cfg['path'] . '/views/' . $tpl['module'] . '/catalog.tpl.php';
}?>
</form>