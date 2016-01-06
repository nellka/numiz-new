<?php 
header('Content-Type: application/json');
	
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/helpshopcoinsorder.php';

$data_result = array();
$data_result['error'] = null;
if(!$tpl['user']['user_id']) $data_result['error'] = 'Проблема с авторизацией';

$coin_id = (integer)request('coin');	
$group = request('group2'); 		
$name = strip_tags(request('name2')); //
$year = (integer)request('year');
$metal = (integer)request('metal');
$condition = (integer)request('condition');
$details = strip_tags(request('details')); //
	
if(isset($_REQUEST['modal']) AND isset($_GET['coin']) AND !empty($_GET['coin'])){
	die('modal');
}
	

if($coin_id &&$group&&$name&&$year&&!$data_result['error']){
	if($year > 9999) $year = 0;
	$group_id = 0;
	$coin = $shopcoins_class->getItem($coin_id);
	
	if(!$user_class->is_user_has_premissons()){
		$data_result['error'] = 'У вас недостаточно прав для данного действия';
	} else if(!$coin||$coin['materialtype']!=11) {
		$data_result['error'] = 'Монета не находится в категории барахолка';
	} elseif ($shopcoins_class->is_already_described($coin_id)){
		$data_result['error'] = 'Монета уже описана'; // maybe check is_locked = 1?
	} elseif(!$group_id = $shopcoins_class->group_id_from_name($group)){
		$data_result['error'] = 'Мы не принимаем монеты из этой страны';
	} else {
		if(!$data_result['error']){
			$data = array('price' => 'price'+1, 
						  'group' => $group_id, 
						  'year' => $year, 
						  'metal'=> isset($metallArray[$metal])?$metallArray[$metal]:$metallArray[15], 
						  'condition' => isset($conditionArray[$condition])?$conditionArray[$condition]:"", 
						  'details' => $details,  
						  'name' => $name);
	        $shopcoins_class->setCoinDescription($data,$coin_id);			
			$user_class->add_user_describe_log($coin_id);
			$user_class->addUserBalance();
		}
		$data_result['success'] = 1;
	}		
} else{
	$data_result['error'] = 'Заполните обязательные поля - Страна, номинал, год';
}

echo json_encode($data_result);
die();

function get_image_from_id($coin_id){
	$sql = "SELECT image_big FROM shopcoins WHERE shopcoins = ".intval($coin_id);
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0)
	{   
			$tmp = mysql_fetch_array($result);
			return $tmp['image_big'];
	}
	else{
		return FALSE;
	}
}


if(isset($_GET['modal']) AND isset($_GET['coin']) AND !empty($_GET['coin'])){
		header('Content-Type: text/html;');

			if(
				is_user_has_premissons(intval($_COOKIE['cookiesuser'])) AND 
				is_logged_in(intval($_COOKIE['cookiesuser'])) != FALSE AND 
				!is_already_described($_GET['coin']))
			{ // if user has 5 orders && !is_locked && item not have description

			$sql = "select * from 'group' where type='shopcoins' and 'group' not in (667,937,983,997,1014,1015,1062,1063,1097,1106) group by name;";
			$result = mysql_query($sql);
			$i=0;
			while ($rows = mysql_fetch_array($result)) {
				$groupselect_v .= ($i!=0?",\"":"\"").str_replace('"','',$rows["name"])."\"";
				$groupselect_v2 .= ($i!=0?",":"").str_replace('"','',$rows["name"])."";
				$i++;
			}
			
$details33 .= '<html><head><title>Описать</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<link rel=stylesheet type=text/css href='.$in.'bodka.css>
<center>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script></head>
<form action=/detailcoins/addcomment.php name=mainform id="send_descr" method=post>
		<table border=1 cellpadding=2 cellspacing=0 align=center width=100%>
		<tbody>
<tr class="txt" bgcolor="#99CCFF"><td class="txt" colspan="2"><b>Опишите монету и получите 1 рубль на бонус-счет</b></td></tr>
		<input type=hidden id=coins name=coin value="'.$_GET['coin'].'">		
		<img src="/shopcoins/images/'.get_image_from_id($_GET['coin']).'"/ border=1>
		<tr class=tboard bgcolor=#EBE4D4>
			<td width=20% align=right><b>Страна: </b></td>
			<td><input type=text class=formtxt id="group2" name="group" required size=40 onfocus="javascript:AddGroup2();"/><br>
		</td></tr><tr class=tboard bgcolor=#EBE4D4>
			<td width=20% align=right><b>Номинал: </b></td>
			<td> <input class=formtxt id="name2" name="name" type=text required size=40 onfocus="process(\'/detailscoins/addname.php?group=\'+ encodeURI(document.getElementById(\'group2\').value));">  &nbsp; &nbsp;&nbsp;&nbsp;</td>
			</tr><tr class=tboard bgcolor=#EBE4D4>
		<td width=20% align=right><a name=year></a><b>Год: </b></td>
		<td> 
		<input class=formtxt id="year2" name="year" required size=4/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		</tr>
		<tr class=tboard valign=top bgcolor=#EBE4D4>
		<td width=20% align=right><a name=metal></a><b>Металл: </b></td>
		<td><select name=metal id="metal2" class=formtxt ><option value=0>Выберите<option value=1>Алюминий<option value=2>Биллон<option value=3>Биметалл<option value=4>Железо<option value=5>Золото<option value=6>Керамика<option value=7>Медно-никель<option value=8>Медь<option value=9>Позолота<option value=10>Посеребрение<option value=11>Серебро<option value=12>Титан<option value=13>Фарфор<option value=14>Цинк<option value=15>Неопределено</select>
		</td></tr>
		<tr class=tboard valign=top bgcolor=#EBE4D4>
		<td width=20% align=right><a name=metal></a><b>Состояние: </b></td>
		<td><select name=condition id=condition2 class=formtxt >
		<option value=0>Выберите<option value=1>VF<option value=2>XF<option value=3>UNC-<option value=4>UNC<option value=5>Proof-<option value=6>Proof</select>
		</td></tr><tr class=tboard valign=top bgcolor=#EBE4D4>
			<td width=20% align=right><a name=details></a><b>Описание монеты:</b></td>
			<td><textarea name=details id=details2 class=formtxt cols=60 rows=6></textarea> &nbsp;&nbsp;&nbsp;</td>
			</tr>
		
		</table>
		<table border=0 cellpadding=3 cellspacing=1 width=100%>
		<tr class=tboard valign=top bgcolor=#EBE4D4>
		<td align=center width=35%>
		<input type=submit name=submit onclick="javascript:return CheckSubmitPrice(0);window.close();window.opener.location.reload();" value="Сохранить описание" class=formtxt >
		</td>
		</tr>
		</tbody>
</table>

<script>

$( document ).ready(function() {
  console.log(11);
});

var test3 = 0;
function UsingCopyCoins (copycoins) {

	if (copycoins>0) {
	
		process("writesavedatacoins.php?copycoins="+copycoins);
	}
	else
		alert("not coins");
}

function CheckSubmitPrice (num) {

	if (confirm("Все правильно заполнили ?")) { 
		
		var datastring = $("#send_descr").serialize();
		
		$.ajax({
            type: "GET",
            url: "/shopcoins/change_coin.php",
            data: datastring,
            dataType: "json",
            success: function(data) {
               	
               if(data.success){
               	alert("Спасибо, на ваш бонус-счет зачислен 1 рубль.");
				window.close();
				window.opener.location.reload(); 
               }
               if(data.error){
               	alert(data.error);
               }


            },
            error: function(){
                  alert("");
            }
        }, "json");

		return false;
		
	} 
	else {
		return false;
	}

}

function CopyDetails(id) {

	myDiv = document.getElementById("details"+id);
	document.mainform.details.value=myDiv.value;
	
}

function ShowSaveCoins(xmlRoot) {
	
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	if (errorvalue == "none") {
		var metal = xmlRoot.getElementsByTagName("metal").item(0).firstChild.data;
		document.getElementById("metal2").value = metal;
		var condition = xmlRoot.getElementsByTagName("condition").item(0).firstChild.data;
		document.getElementById("condition2").value = condition;
		var group = xmlRoot.getElementsByTagName("group").item(0).firstChild.data;
		if (group != "none")
			document.getElementById("group2").value = group;
		var year = xmlRoot.getElementsByTagName("year").item(0).firstChild.data;
		if (year != "none")
			document.getElementById("year2").value = year;
		var name = xmlRoot.getElementsByTagName("name").item(0).firstChild.data;
		if (name != "none")
			document.getElementById("name2").value = name;
		var details = xmlRoot.getElementsByTagName("details").item(0).firstChild.data;
		if (details != "none")
			document.getElementById("details2").value = details;
	}
	else
		alert("not data");
		return;
}

var StatusForm = 1;
var NameStatus = "text";
NameArray = new Array();
GroupNameArray = new Array('.$groupselect_v.');
var MetalNameArray = "";

var xmlHttp = createXmlHttpRequestObject();
var in_office = 0;

function createXmlHttpRequestObject() 
{
	var xmlHttp;
	try
	{
		xmlHttp = new XMLHttpRequest();
	}
	catch(e)
	{
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
										"MSXML2.XMLHTTP.5.0",
										"MSXML2.XMLHTTP.4.0",
										"MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP",
										"Microsoft.XMLHTTP");
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) 
		{
			try 
			{ 
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			} 
			catch (e) {}
		}
	}
	

	if (!xmlHttp)
		alert("Не возможно создать объект XMLHttpRequest");
	else 
		return xmlHttp;
}

function process(myFile)
{
	//alert (myFile);
	if (myFile)
	{
		if (xmlHttp)
		{
			try
			{
				xmlHttp.open("GET", myFile, true);
				xmlHttp.onreadystatechange = handleRequestStateChange;
				xmlHttp.send(null);
			}
			catch (e)
			{
				alert("Cant connect to server:\n" + e.toString());
			}
		}
	}
	else
		return;
}

function handleRequestStateChange() 
{
	if (xmlHttp.readyState == 4) 
	{
		if (xmlHttp.status == 200) 
		{
			try
			{
				handleServerResponse();
			}
			catch(e)
			{
				alert("Error reading the response: " + e.toString());
			}
		} 
		else
		{
			alert("There was a problem retrieving the data:\n" + 
			xmlHttp.statusText);
		}
	}
}

function handleServerResponse()
{
	var xmlResponse = xmlHttp.responseXML;
	if (!xmlResponse || !xmlResponse.documentElement)
		throw("Invalid XML structure:\n" + xmlHttp.responseText);

	var rootNodeName = xmlResponse.documentElement.nodeName;
	if (rootNodeName == "parsererror") 
		throw("Invalid XML structure:\n" + xmlHttp.responseText);
	
	xmlRoot = xmlResponse.documentElement;
	if (rootNodeName != "response" || !xmlRoot.firstChild)
		throw("Invalid XML structure:\n" + xmlHttp.responseText);
	
	
	scripteval = xmlRoot.getElementsByTagName("scripteval");
	eval (scripteval.item(0).firstChild.data + "(xmlRoot)");
};
var TextGroup = "'.$groupselect_v2.'";
//this.group = AutoInput("group2",);
//this.name = AutoInput("name2",MetalNameArray);
function AddGroup2() {
	
	// CONFIG 
	
	// this is id of the search field you want to add this script to. 
	// идентификатор котрый мы присвоили полю
	var id = "group2";
	
	// Надпись в поле до клика на него мышкой
	var defaultText = "";	
	
	// set to either true or false
	// when set to true it will generate search suggestions list for search field based on content of variable below
	var suggestion = true;
	
	// static list of suggestion options, separated by comma
	// А здесь через запятую перечислены все возможные варианты
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
	};
	
};

function AddName2() {
	
	// CONFIG 
	
	// this is id of the search field you want to add this script to. 
	// идентификатор котрый мы присвоили полю
	var id = "name2";
	
	// Надпись в поле до клика на него мышкой
	var defaultText2 = "";	
	
	// set to either true or false
	// when set to true it will generate search suggestions list for search field based on content of variable below
	var suggestion2 = true;
	
	// static list of suggestion options, separated by comma
	// А здесь через запятую перечислены все возможные варианты
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
	};
	
};

// script initiates on page load. 

this.addEvent = function(obj,type,fn){
	if(obj.attachEvent){
		obj["e"+type+fn] = fn;
		obj[type+fn] = function(){obj["e"+type+fn](window.event );}
		obj.attachEvent("on"+type, obj[type+fn]);
	} else {
		obj.addEventListener(type,fn,false);
	};
};
//addEvent(window,"load",group2);
//addEvent(window,"load",group2);

function ShowNameCoins(xmlRoot) {
	
	//alert("1111");
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	if (errorvalue == "none") {
		var arrayresult = "";	
		arrayresult = xmlRoot.getElementsByTagName("arrayresult").item(0).firstChild.data;
		//alert(arrayresult);
		MetalNameArray = arrayresult;
		AddName2();
	}
	else
		//alert("0");
		return "";
}
</script>
</form></center></html>';

	}
				
	
	echo $details33;
	die();
	}

?>