<script>		
function ShowOneClick(id) {
	jQuery('.oneclick_notifications .messages').hide();	
	if (jQuery('#oneshopcoins'+id+' .messages').children().length > 0) {
		jQuery('#oneshopcoins'+id+' .messages').fadeIn("fast");
	}

	jQuery(".bg_shadow").show();
	var yourClick = true;
					
	jQuery(document).mouseup(function (e) {
	var container = jQuery('#oneshopcoins'+id+' .messages');
	if (container.has(e.target).length === 0){
		container.hide();
		jQuery(".bg_shadow").hide();
	}
	});
			
}
function HideOneClick(id) {
	jQuery('#oneshopcoins'+id+' .messages').fadeOut("fast");
	jQuery(".bg_shadow").fadeOut("fast");
	jQuery(document).unbind("click.myEvent");
}

function ValidPhone(phone) {
    var re = /^\d[\d\(\)\ -]{8,14}\d$/;
    var myPhone = phone;
    var valid = re.test(myPhone);
    if (valid) return true;
    return false;
}  

function AddOneClick(id) {
   var onefio = jQuery('#oneshopcoins'+id+' .messages').find("input[name='onefio']").val();
   var onephone = jQuery('#oneshopcoins'+id+' .messages').find("input[name='onephone']").val();
   
   if (!onefio || onefio.length <3){
		jQuery('#oneshopcoins'+id+'-error').text('Вы не указали имя');
		return;
   }
   
   if (!ValidPhone(onephone)){
		jQuery('#oneshopcoins'+id+'-error').text('Укажите номер телефона в полном формате!');
		return;
   }
    
   jQuery.ajax({
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
		jQuery('#oneclick_form'+id).html(str);
	} else if (errorvalue == 'reserved') {
		jQuery('#oneshopcoins'+id+'-error').text('Товар зарезервирован одновременно с другим пользователем');		
	} else if (errorvalue == 'notavailable') {		
		jQuery('#oneshopcoins'+id+'-error').text('Товар уже продан');	
	} else if (errorvalue == 'stopsummax') {
		jQuery('#oneshopcoins'+id+'-error').text('Минимальная сумма заказа <? echo $minpriceoneclick;?> руб.');		
	}	else if (errorvalue == 'amount') {
		erroramountvalue = data.erroramount;		
		jQuery('#oneshopcoins'+id+'-error').text('На складе всего лишь ' + erroramountvalue + ' штук');		
	}
}	
	
function sendData(name,val){   
    if(name){
        jQuery('#'+name).val(val);
    }
    console.log(jQuery('form#search-params'));
    jQuery('#pricestart').val(jQuery('#amount1').val());
    jQuery('#priceend').val(jQuery('#amount2').val());
    
    jQuery('#yearstart').val(jQuery('#amount3').val());
    jQuery('#yearend').val(jQuery('#amount4').val());
    jQuery('form#search-params').submit();
}

function AddAccessory(id,materialtype){	
	var str;
	var amount = jQuery('#amount'+id).val();
	if(amount <=0) amount = 1;
    jQuery.ajax({	
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
	errorvalue = data.error;
	var bascetshopcoins = data.bascetshopcoins;	
	if (!errorvalue){
		var shopcoinsorder = data.shopcoinsorder;
		var bascetamount = data.bascetamount;
		var bascetsum = data.bascetsum;
		var bascetweight = data.bascetsumclient;
		var bascetreservetime = data.bascetreservetime;
		var bascetpostweightmin = data.bascetpostweightmin;
		var bascetpostweightmax = data.bascetpostweightmax;
		var bascetinsurance = data.bascetinsurance;
		var textbascet2 = data.textbascet2;		
		
		jQuery("#inorderamount").html(bascetamount);				
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
		
		jQuery("#MainBascet").html(str);				
		jQuery("#bascetshopcoins" + bascetshopcoins).html('<img src="<?=$cfg['site_dir']?>images/corz7.gif" title="Уже в корзине" alt="Уже в корзине">');		
	} else if (errorvalue == 'reserved'){
		jQuery("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>Товар зарезервирован одновременно с другим пользователем</p>');	
		jQuery("#bascetshopcoins" + bascetshopcoins).html('<img src="<?=$cfg['site_dir']?>images/corz6.gif" title="Уже в корзине" alt="Уже в корзине">');
		
	}	else if (errorvalue == 'notavailable'){	
		jQuery("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>Товар уже продан</p>');	
		jQuery("#bascetshopcoins" + bascetshopcoins).html('<img src="<?=$cfg['site_dir']?>images/corz6.gif" title="Уже в корзине" alt="Уже в корзине">');
	} else if (errorvalue == 'stopsummax') {
		jQuery("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>Максимальная сумма заказа <? echo $stopsummax;?> руб. <br>Если вы не все сложили в корзину, проделайте следующим заказом.</p>');	
	} else if (errorvalue == 'amount') {
		erroramountvalue = data.erroramount;
		jQuery("#MainBascet").html('<h1 class="yell_b">Корзина</h1><p class=center>На складе всего лишь ' + erroramountvalue + ' штук</p>');
	}
	
	$("#MainBascet").dialog({
    	position: { 
            my: 'top',
            at: 'top',
            of: jQuery("#bascetshopcoins"+id)
        },
 		 modal:true,
		 buttons:{
		    Ok: function(){
		      $(this).dialog( "close" );
		    }
		  }
	});
}
	
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

*/?>
</script>

<?
if($tpl['task']){
     include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php'; 
} else {
    include $cfg['path'] . '/views/' . $tpl['module'] . '/catalog.tpl.php';
}?>
</form>