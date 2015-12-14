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

function fast_order(id) {
	var telephone = $("#fast_order_"+id).find("input[name='fast_order_telephone']").val();
	
	$("#fast_order_"+id).find(".fast_order_error").fadeOut("fast");
	height_fast_order = $("#fast_order_"+id).find(".fast_order_body").height()+parseInt($("#fast_order_"+id).css("padding-bottom"))+"px";
	if (telephone.length > 5) {
		$.ajax({
			data: "PRODUCT_ID="+id+"&TELEPHONE="+telephone+"&REG_USER=Y&CHANGE_PHONE=Y&ADD_BASKET=Y&ID_USER_FAST=",
			url: "/bitrix/components/pvk/order.click/ajax.php",
			cache: false,
			success: function(html){
				$("#fast_order_"+id).find(".fast_order_body").hide();
				$("#fast_order_"+id).find(".fast_order_success").fadeIn("fast");
				$("#fast_order_"+id).animate({height: $("#fast_order_"+id).find(".fast_order_success").height()+parseInt($("#fast_order_"+id).css("padding-bottom"))+"px"}, 100);
				setTimeout("fast_order_success_close("+id+");", 5000);
			}
		});
	} else {
		$("#fast_order_"+id).find(".fast_order_error").fadeIn("fast");
		$("#fast_order_"+id).animate({height: $(".fast_order_body").height()+parseInt($("#fast_order_"+id).css("padding-bottom"))+"px"}, 100);
	}
}
function fast_order_success_close(id) {
	$("#fast_order_"+id).fadeOut("fast");
	$(document).unbind("click.myEvent");
	setTimeout("fast_order_success_close2("+id+");", 1000);
}
function fast_order_success_close2(id) {
	$("#fast_order_"+id).find(".fast_order_body").show();
	$("#fast_order_"+id).find(".fast_order_success").hide();
	$("#fast_order_"+id).css("height", "100px");
	$(".bg_shadow").fadeOut("fast");
	
}

/*function sendData(name,val){
     return false;
   // jQuery('#'+name).val(val);
     console.log( jQuery('form#search-params input'));
     
     console.log( jQuery('form#search-params :input').serializeArray() );
    console.log(fields); 
    console.log(val); 
    return false;
}*/
function sendData(name,val){   
    if(name){
        jQuery('#'+name).val(val);
    }
    console.log(jQuery('form#search-params'));
    jQuery('#pricestart').val(jQuery('#amount1').val());
    jQuery('#priceend').val(jQuery('#amount2').val());
    
    jQuery('#yearstart').val(jQuery('#amount3').val());
    jQuery('#yearend').val(jQuery('#amount4').val());
    /*  jQuery('input[name="them[]"]:checked').each()

   // data = jQuery('form#search-params :input').serializeArray();
   // filter = jQuery('.filterbox :input').serializeArray();
   
    //console.log(data);
    console.log(filter);*/
    jQuery('form#search-params').submit();
}

<?
/*
function AddAccessory_main(shopcoins,materialtype){
	var str;
	str = document.mainform.amount + shopcoins + value;
	document.mainform.shopcoinsorder.value = shopcoins;
	document.mainform.materialtype.value = materialtype;
	document.mainform.shopcoinsorderamount.value = eval(str);
	if (eval(str) > 0)
	{
		//document.mainform.submit();
		process ('addbascet.php?shopcoinsorder="<?=$shopcoinsorder?>."&shopcoins=' + shopcoins + '&amount=' + eval(str));
	}
	else
		alert ('Введите количество');
}

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

function ShowSmallBascet (xmlRoot)
{
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	var bascetshopcoins = '';	
	bascetshopcoins = xmlRoot.getElementsByTagName("bascetshopcoins").item(0).firstChild.data;	
	
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
		//alert(textbascet2);
		
		myDiv = document.getElementById("inorderamount");
		myDiv.innerHTML = "� ����� ������� <a href=<?=$in?>shopcoins/index.php?page=orderdetails> "+bascetamount+" </a> �������, <a href=<?=$in?>shopcoins/index.php?page=orderdetails><img src=<?=$in?>images/basket.gif border=0></a>";
		
		var str = '';
		str = '<table border=0 cellpadding=3 cellspacing=0 style="border:thin solid 1px #000000" id=tableshopcoinsorder width=180>';
		str += '<tr class=tboard bgcolor=#006699><td><strong><font color=white>�������:</font></strong></td></tr>';
		str += '<tr class=tboard bgcolor=#ffcc66><td class=tboard align=top><strong>����� �</strong> ' + shopcoinsorder + ' ';
		str += '<br><strong>�������:</strong> ' + bascetamount + ' <br><strong>�� �����:</strong> ' + bascetsum + ' �.';
		if (bascetsumclient>0) {
			str += '<br><strong>��� ���������� ��������:</strong> ' + bascetsumclient + ' �.';
		}
		str += '<br><strong>��� ~ </strong> ' + bascetweight + ' ��. <br><strong>����� ��:</strong> ' + bascetreservetime + '<br><center><a href=index.php?page=orderdetails><img src=<?echo $in;?>images/basket.gif border=0></a></center></td></tr>';
		str += '<tr class=tboard bgcolor=#006699><td><strong><font color=white>��������:</font></strong></td></tr>';
		str += '<tr class=tboard bgcolor=#ffcc66><td class=tboard align=top><strong>������:</strong><br><strong>��������� �������:</strong> ���������<br><strong>� ����:</strong> �� 170 �.';
		str += '<br><br><strong>����� ������</strong><br><strong>���� �� ����:</strong> �� ' + bascetpostweightmin + ' �� ' + bascetpostweightmax + ' �.';
		str += '<br><strong>��������� 4%:</strong> ' + bascetinsurance + ' �. <br><strong>��������:</strong> 10 �. �� ������� / ����.</td></tr>';
		str +='<tr class=tboard bgcolor=#ffcc66><td><div style="display:none" id=showbascet2>'+textbascet2+'</div></td></tr>'
		str += '<tr class=tboard bgcolor=#EBE4D4><td align=center><img src=../images/windowsmaximize.gif onclick="ShowBascet2();" alt="���������� ����������"/></td></tr></table>';

		
		myDiv = document.getElementById("MainBascet");
		myDiv.innerHTML = str;
		//dHbcnn=document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight
		dWbcnn=document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth
		myDiv.style.position = "absolute";
		myDiv.style.left = dWbcnn - 220;
		myDiv.style.top = document.body.scrollTop;
		
		var str = '';
		str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz7.gif border=0 alt="��� � �������">';		
		
	}
	else if (errorvalue == 'reserved')
	{
		alert ('����� �������������� ������������ � ������ �������������');

		var str = '';
		str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz6.gif border=0 alt="��� � �������">';
		
	}
	else if (errorvalue == 'notavailable')
	{
		
		alert ('����� ��� ������');
		
		var str = '';
		str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz6.gif border=0 alt="��� � �������">';

	}
	else if (errorvalue == 'stopsummax')
	{
		alert ('������������ ����� ������ <? echo $stopsummax;?> ���. \n���� �� �� ��� ������� � �������, ���������� ��������� �������."');
	}
	else if (errorvalue == 'amount')
	{
		erroramount = xmlRoot.getElementsByTagName("erroramount");
		erroramountvalue = erroramount.item(0).firstChild.data;
		alert ('�� ������ ����� ���� ' + erroramountvalue + ' ����');
	}	
}*/?>
</script>

<?
if($tpl['task']){
     include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php'; 
} else {
    include $cfg['path'] . '/views/' . $tpl['module'] . '/catalog.tpl.php';
}?>
</form>