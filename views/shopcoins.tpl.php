<?

if($tpl['task']){
     include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php'; 
} else {
    include $cfg['path'] . '/views/' . $tpl['module'] . '/catalog.tpl.php';
}?>

<script language="JavaScript">

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
}
function AddAccessory(shopcoins,materialtype)
{
	var str;
	str = document.mainform.amount + shopcoins + value;
	document.mainform.shopcoinsorder.value = shopcoins;
	document.mainform.materialtype.value = materialtype;
	document.mainform.shopcoinsorderamount.value = eval(str);
	//alert (eval(str) + shopcoins);
	if (eval(str) > 0)
	{
		//document.mainform.submit();
		process ('addbascet.php?shopcoinsorder=".$shopcoinsorder."&shopcoins=' + shopcoins + '&amount=' + eval(str));
	}
	else
		alert ('������� ����������');
}
</script>