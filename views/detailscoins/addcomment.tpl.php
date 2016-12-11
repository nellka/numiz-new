<div id="add-comment-block">

<!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css" rel="stylesheet"> -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.1/js/bootstrap.min.js"></script> 
<script src="<?=$cfg['site_dir']?>/js/chosen.jquery.min.js"></script>
<link href="<?=$cfg['site_dir']?>/css/chosen.min.css" rel="stylesheet">

<?
if($tpl['error']){?>
	<div class="error"><?=$tpl['error']?></div>
<?} else  {	
	if ($submit&&$showlinks&&(sizeof($CoinsArray)>0) ){?>
		<form action="#" method=post id='savecatalog'> 
			<p class=txt><img src="<?=$cfg['site_dir']?>detailscoins/images/<?=$rows_main['image_big']?>" border=1 align=left> 
			<br>Для связки монеты выберите аналогичную монету в предложенном ниже списке монет  и нажмите "связать".<br>
			Если в списке отсутствует подобная Вами описанной монете либо Вы сомневаетесь в аналогичности монеты, то нажмите "Пропустить" либо закройте окно. <br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" onclick="saveDataCatalog()" value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Связать&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" onclick="window.location.reload();" value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пропустить&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'><br><br>
			
			<input type=hidden name=pagenum value="<?=$pagenum?>">
			<input type=hidden name=coins value=<?=$coins?>>
			Страна: <?=$group?> <br>
			Номинал: <?=$name?> <br>
			Год: <?=$year?> <br>
			Металл: <?=$metal?> <br>
			Описание: <?=$details?> <br><br>
			
			<!--START SHOWTABLE-->
			
			<table border=1 cellpadding=2 cellspacing=0 width=100%>
			<? 
			$StrResult = "";
			$i = 0;
			$j = 0;
			for ($k=0; $k<sizeof($CoinsArray); $k++){
				
			if ($i%3 == 0 and $i!=0 and ($materialtype_admin==1 or $materialtype_admin==2))
				$StrResult .= "</tr><tr><td height=5 colspan=3></td></tr><tr valign=top>";
			elseif ($i == 0 and ($materialtype_admin==1 or $materialtype_admin==2))
				$StrResult .= "<tr valign=top>";
			elseif ($materialtype_admin!=1 and $materialtype_admin!=2)
				$StrResult .= "<tr valign=top>";
			
			$AddDetails = 0;
			
			if (trim($CoinsArray[$k]["details"]))
			  $AddDetails = 1;
			
						
			$StrResult .= "<td class=tboard ";
			if (($year>=$CoinsArray[$k]["yearstart"] and ($CoinsArray[$k]["yearend"]>0 and $year<=$CoinsArray[$k]["yearend"])) or $year==$CoinsArray[$k]["yearstart"])
			  $StrResult .= " bgcolor=red ";
			
			$StrResult .= "width=180>
			<img id=img".$CoinsArray[$k]["catalog"]." src=".$cfg['site_dir']."/catalognew/".$CoinsArray[$k]["image_small_url"]." alt='".$CoinsArray[$k]["name"]."' border=1 style=\"max-width: 180px\">
			</td>
			<td class=tboard width=".($materialtype_admin==1||$materialtype_admin==2?20:100)."%>
			<input type=radio name=parent value='".$CoinsArray[$k]["catalog"]."'  ";
			
			if ($j==0 and (($year>=$CoinsArray[$k]["yearstart"] and ($CoinsArray[$k]["yearend"]>0 and $year<=$CoinsArray[$k]["yearend"])) or $year==$CoinsArray[$k]["yearstart"]))
			  $StrResult .= " checked>";
			else
			  $StrResult .= ">";
			
			if ($j == 0 and (($year>=$CoinsArray[$k]["yearstart"] and ($CoinsArray[$k]["yearend"]>0 and $year<=$CoinsArray[$k]["yearend"])) or $year==$CoinsArray[$k]["yearstart"]))
			{
			  $j++;
			}
			
			$StrResult .= "<br><span id=name". $CoinsArray[$k]["catalog"] ."><b>".$CoinsArray[$k]["name"]."</b></span>";
			if (trim($CoinsArray[$k]["mname"]))
			  $StrResult .= "<br><span id=metal". $CoinsArray[$k]["catalog"] ."><b>Металл: </b>".$CoinsArray[$k]["mname"].'</span>';
			
			if ($CoinsArray[$k]["yearstart"])
			  $StrResult .= "<br><span id=year". $CoinsArray[$k]["catalog"] ."><b>Год: </b>".$CoinsArray[$k]["yearstart"].'</span>';
			
			
			$display =  ( trim($CoinsArray[$k]["condition"]) ? 'inline' :  'none');
			$StrResult .= "<br style='display: $display'><span style='display: $display' id=status". $CoinsArray[$k]["catalog"] ."><b>Состояние: <font color=blue>".$CoinsArray[$k]["condition"]."</font></b></span>";
			
			$display =  ( trim($CoinsArray[$k]["details"]) ? 'inline' :  'none');
			{
			  $text = substr($CoinsArray[$k]["details"], 0, 250);
			  $text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
			}
			$StrResult .= "<br style='display: $display'><span style='display: $display' id=descr". $CoinsArray[$k]["catalog"] ."><b>Описание: </b>".$text.'</span>';	
			
			$StrResult .= "</td>";
			$i++;
			}
			
			if ($i%3 == 1 and ($materialtype_admin==1 or $materialtype_admin==2))
				$StrResult .= "<td colspan=2></td></tr>";
			elseif ($i%3 == 2 and ($materialtype_admin==1 or $materialtype_admin==2))
				$StrResult .= "<td></td></tr>";
			elseif ($i%2 == 0 and ($materialtype_admin==1 or $materialtype_admin==2))
				$StrResult .= "</tr>";
			elseif ($materialtype_admin!=1 and $materialtype_admin!=2)
				$StrResult .= "</tr>";
				
			echo $StrResult;
			?>
			
			</table>
			<!--END SHOW TABLE-->
			<br>
			<div class=center>
			<input type="button" onclick="saveDataCatalog()" value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Связать&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" onclick="window.location.reload();" value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пропустить&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'>
			
			</div></form>
	<?} 
	if ($submit&&$showlinks){?>
		<br><center><b class=red>Готово!</a></b></center>
					
	<?} else {?>		
			
	<form action="#" id="addcomment">
		<table border=1 cellpadding=2 cellspacing=0 align=center width=100%>
		<input type=hidden id=coins name=coins value="<?=$coins?>">
		<input type=hidden id=copycoins name=copycoins value="<?=$copycoins?>">
		<input type=hidden name=pagenum value="<?=$pagenum?>">
		<tr class=tboard valign=top bgcolor=#EBE4D4>
		<td colspan=2 align=center>&nbsp;<img src="<?=$cfg['site_dir']?>/detailscoins/images/<?=$rows_main['image_big']?>" border=1></td>
		</tr>
		<?if (sizeof($error)){?>
			<tr class=tboard valign=top bgcolor=#EBE4D4>
				<td colspan=4 align="center"><font color=red><?=implode("<br>",$error)?></font></td>
			</tr>
		<?}			
		
		if (!$rows_main['group']) {	?>		
				<tr class=tboard bgcolor=#EBE4D4>
				<td width=20% align=right><b>Страна: </b></td>
				<td>
				<select name="group_id" data-placeholder="Страны" class="theme_slc" id="group" onChange="showSelectors()">
               <option value=""></option>
				<?php foreach ($Countries as $key => $value): ?>
                   <option value="<?php echo $value['group']; ?>"<?php if($value['group']==$group_id) echo "selected";?> ><?=$value['name']?></option>
               <?php endforeach; ?>
           </select>
           <?				
				echo ($rows_main['materialtype']>1?" Указывайте страну из предложенных Вам подсказок":"")."</td></tr>";
			} else {?>
				<tr class=tboard bgcolor=#EBE4D4>
				<td width=20% align=right></a><b>Страна: </b>
				</td>
				<td>&nbsp;<?=$rows_main['group']?></td></tr>
			<?}
			
			if (!$rows_main['name']) {?>
				
				<tr class=tboard bgcolor=#EBE4D4>
				<td width=20% align=right><b>Номинал: </b></td>
				<td> 
				<select name="nominal_id" data-placeholder="Номинал" class="theme_slc" id="nominals"></select>
				&nbsp; &nbsp;&nbsp;&nbsp;</td>
				</tr>
			<?}	else {?>		
				<tr class=tboard bgcolor=#EBE4D4>
				<td width=20% align=right><b>Номинал: </b></td>
				<td> <?=$rows_main['name']?> &nbsp; &nbsp;&nbsp;&nbsp;</td>
				</tr>
			<?}?>
			
			<tr class=tboard bgcolor=#EBE4D4>
			<td width=20% align=right><b>Год: </b></td>
			<td><?=($rows_main['year']?$rows_main['year']:"<input class=formtxt id=\"year\" name=\"year\" size=4 value=\"$year\"/>")?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			</tr>
			
			<tr class=tboard valign=top bgcolor=#EBE4D4>
			<td width=20% align=right><b>Металл: </b></td>
			<td>
			<?if ($rows_main['metal'])
				echo $rows_main['metal'];
			else {?>
				<select name=metal_id id="metal_id" class=formtxt >
				<option value=0>Выберите</option> 
				<?foreach ($MetalArray as $key => $value){?>			
					<option value="<?=$key?>" <?=selected($key,$metal_id)?>><?=$value?></option>
				<?}?>
				</select>
			<?}?>
			
			</td></tr>
			
			<tr class=tboard valign=top bgcolor=#EBE4D4>
			<td width=20% align=right><a name=metal></a><b>Состояние: </b></td>
			<td>
			<?	if ($rows_main['condition']){
				echo $rows_main['condition'];
			} else {?>
				<select name=condition_id id=condition_id class=formtxt >
				<option value=0>Выберите</option>
				<?foreach ($ConditionArray as $key => $value) {?>
					<option value=<?=$key?> <?=selected($key,$condition_id)?>><?=$value?></option>
				<?}?>
				</select>
			<?}?>
			</td></tr>
			
			<?if (!$rows_main['details']) {
				
				echo "<tr class=tboard valign=top bgcolor=#EBE4D4>
				<td width=20% align=right><b>Описание монеты:</b></td>
				<td><textarea name=details id=details class=formtxt cols=60 rows=6>".($details?$details:($rows_main['materialtype']!=7?"":"В наборе Х монет номиналом"))."</textarea> &nbsp;&nbsp;&nbsp;</td>
				</tr>";
			} else {			
				echo "<tr class=tboard valign=top bgcolor=#EBE4D4>
				<td width=20% align=right><a name=details></a><b>Описание монеты:</b></td>
				<td>".($rows_main['details']?$rows_main['details']:"")."&nbsp;&nbsp;&nbsp;
				<input type='hidden'  name=details id=details value='".$rows_main['details']."'>
				</td>
				</tr>";
			}?>
			
			</table>
			<div class="center">
			<!--<input type="hidden" name=submit id=next value="0" name=next>-->
			<input type="button" onclick="CheckSubmitPrice(0);" value='Записать'>
			<input type="button" onclick="CheckSubmitPrice(1);" value='Записать и Сохранить для последующего использования'>
			<?if ($copycoins>0) {?>
				<br>
				<input type=button  value='Использовать сохраненные данные' onclick="UsingCopyCoins(<?=$copycoins?>);">	 
			<?}?>
			<!--<input type="hidden" name=next id=next value="">-->
			<input type="button" value='Не знаю что за монета. Пропустить' onclick="if(confirm('Желаете пропустить описание этой монеты?')){saveData('next');}else{return false;};" class=formtxt>
			
			</div>
			</form>
		<?}?>
<?}?>
</div>
<script>
$(document).ready(function(){ 
	$('#group').chosen({width: '300px;'});
    $('#nominals').chosen({width: '300px;'});
    showSelectors('<?=$nominal_id?>');
    $('#group').trigger('chosen:open');
});

function saveDataCatalog(){
	var datastring = JSON.parse(JSON.stringify($("form#savecatalog").serializeArray()));	    
    datastring.push({ name:"datatype",  value:"text_html"});
	$.ajax({	
	    url: '<?=$cfg['site_dir']?>/detailscoins/addparent.php', 
	    type: "POST",
	    data:datastring,         
	    dataType : "html",                   
	    success: function (data, textStatus) { 
	        $('#add-comment-block').html(data);  
	    }
	});
}    

function saveData(id){

	var datastring = JSON.parse(JSON.stringify($("form#addcomment").serializeArray()));	    
    datastring.push({ name:"datatype",  value:"text_html"});
    if(id=='next') {
    	datastring.push({ name:"next",  value:"1"});
    } else datastring.push({ name:"submit",  value:"1"});
    console.log(datastring);
	$.ajax({	
	    url: '<?=$cfg['site_dir']?>/detailscoins/addcomment.php', 
	    type: "POST",
	    data:datastring,         
	    dataType : "html",                   
	    success: function (data, textStatus) { 
	        $('#add-comment-block').html(data);  
	    }
	});
}    

function showSelectors(nominal_id){
	if($('#group').val()>0){	
	    $.ajax({
	        url: 'addname2.php',
	        type: "POST",
	        data:{group:$('#group').val()},
	        dataType : "json",
	        success: function (data) {
	        	console.log(data.error);
	            if (!data.error) {                
                    $('#addcomment #nominals').empty();
                    var nominals_select = $('#addcomment #nominals');
	                nominals_select.append($("<option>").attr('value','').text(""));
	                for (var i = 0; i < data.data.length; i++) {
	                	console.log(data.data[i].nominal_id+","+data.data[i].name);
	                      nominals_select.append($("<option>").attr('value',data.data[i].nominal_id).text(data.data[i].name));
	                }
	                nominals_select.trigger("chosen:updated");
	                $('#group').trigger('chosen:open');
	                
	            }
	            if(nominal_id){
	            	console.log("set nominal_id"+nominal_id);
	            	 $('#addcomment #nominals').val(nominal_id);
	            	 console.log($('#nominals').val());
	            	 $('#addcomment #nominals').trigger("chosen:updated");
	            }  else {
	            	nominals_select.trigger('chosen:open');
	            }             
	        }
	    }); 
	}     
}
function UsingCopyCoins (copycoins) {
	if (copycoins>0) {	
		 $.ajax({
	        url: 'writesavedatacoins.php',
	        type: "POST",
	        data:{copycoins:copycoins},
	        dataType : "json",
	        success: function (data) {
	            if (!data.error) {  
					if(data.group) {
				    	$("#addcomment #group").val(data.group);
				    	//console.log("set"+data.group);
				    	//console.log($("#addcomment #group").val());
				    	$('#addcomment #group').trigger("chosen:updated");
				    	showSelectors(data.nominal_id);
				    }
				    if(data.metal_id) $("#metal_id").val(data.metal_id);
					if(data.condition_id) $("#condition_id").val(data.condition_id);
				    
					if(data.year) $("#year").val(data.year);
					if(data.nominal_id) {
						$("#nominals").val(data.nominal_id);
						$('#nominals').trigger("chosen:updated");					
					}
					if(data.details) $("#details").val(data.details);	
									
	            }  else alert('not data');             
	        }
	    }); 
	} else	alert('not coins');
}

function CheckSubmitPrice (num) {
	if (confirm('Вы не сможете внести изменения после записи. Записать?')) { 		
		if (num == 1) {		
			$("#copycoins").val($("#coins").val());
		}
		saveData();
	} 
	else return false;
}

/*
var test3 = 0;


function CopyDetails(id) {

	myDiv = document.getElementById("details"+id);
	document.mainform.details.value=myDiv.value;
	
}

var StatusForm = 1;
var NameStatus = 'text';
NameArray = new Array();
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
				alert("Can't connect to server:\n" + e.toString());
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
	eval (scripteval.item(0).firstChild.data + '(xmlRoot)');
};
var TextGroup = '<? echo $groupselect_v2; ?>';
//this.group = AutoInput('group2',);
//this.name = AutoInput('name2',MetalNameArray);
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
		obj['e'+type+fn] = fn;
		obj[type+fn] = function(){obj['e'+type+fn](window.event );}
		obj.attachEvent('on'+type, obj[type+fn]);
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
		var arrayresult = '';	
		arrayresult = xmlRoot.getElementsByTagName("arrayresult").item(0).firstChild.data;
		//alert(arrayresult);
		MetalNameArray = arrayresult;
		AddName2();
	}
	else
		//alert('0');
		return "";
}*/

</script>
