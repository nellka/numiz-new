site_dir = 'http://www.numizmatik1.ru/new/';

function sendDataCatalog(name,val,y0,y1){  
   
    if(name){
        $('#'+name).val(val);
    }   

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
             $('#products').html(data);                            	
             $(".bg_shadow").hide();          
        }
    });		
}

function AddMyCatalog(id){
	$("#mycatalog"+id).html('<img src="'+site_dir+'images/wait.gif">');
	$.ajax({
	    url: site_dir+'catalognew/addmycatalog.php', 
	    type: "POST",
	    data:{'catalog':id},         
	    dataType : "json",                   
	    success: function (data, textStatus) {    
	        if (!data.error){             
                if (data.valueid){
                  $("#mycatalog" + data.valueid).html('<b><font color=silver>В коллекции</font></b>');
                }
           } else if (data.error == 'auth') {
                $("#mycatalog" + data.valueid).html('<b><font color=silver>Вы не авторизованы</font></b>');
            }  else {
                $("#mycatalog" + data.valueid).html('<font color=red><b>'+data.error+ '</b></font>');
            }
	    }
	});
}

function deleteSubscribeCatalog(id){
	$("#mysubscribecatalog"+id).html('<img src="'+site_dir+'images/wait.gif">');
	$.ajax({
	    url: site_dir+'catalognew/deletesubscribecatalog.php', 
	    type: "POST",
	    data:{'catalog':id},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 
	        if (!data.error){            
                if (data.valueid){
                  $("#mysubscribecatalog" + data.valueid).html('<b><font color=red>Заявка удалена</font></b>');
                }
            } else if (data.error == 'auth') {
                $("#mysubscribecatalog" + data.valueid).html('<b><font color=silver>Вы не авторизованы</font></b>');
            }  else {
                $("#mysubscribecatalog" + data.valueid).html('<font color=red><b>'+data.error+ '</b></font>');
            }
	    }
	});
}

function deleteMycatalog(id){
    $("#mycatalog"+id).html('<img src="'+site_dir+'images/wait.gif">');
	$.ajax({
	    url: site_dir+'catalognew/deletemycatalog.php', 
	    type: "POST",
	    data:{'catalog':id},         
	    dataType : "json",                   
	    success: function (data, textStatus) {    
	        if (!data.error){             
                if (data.valueid){
                  $("#mycatalog" + data.valueid).html('<b><font color=silver>Удалена из коллекции</font></b>');
                }
           } else if (data.error == 'auth') {
                $("#mycatalog" + data.valueid).html('<b><font color=silver>Вы не авторизованы</font></b>');
            }  else {
                $("#mycatalog" + data.valueid).html('<font color=red><b>'+data.error+ '</b></font>');
            }
	    }
	});
}

function ShowForChange(catalognewmycatalog) {
    $('#catalognewmycatalog').val(catalognewmycatalog);
    $('#MainBascet').html($('#coinchange').html());
	$("#MainBascet").dialog({
    	position: { 
            my: 'top',
            at: 'top',
            of: $("#txtcoinchange" + catalognewmycatalog)
        },
 		modal:true,
 		width:300,
		open: function(event, ui){
        }
     }); 
}

function ShowFriendMail(){
	//$('#catalognewmycatalog').val(catalognewmycatalog);
    $('#MainBascet').html($('#friendsLetter').html());
	$("#MainBascet").dialog({
    	position: { 
            my: 'top',
            at: 'top',
            of: $("#txtfriendsLetter")
        },
 		modal:true,
 		width:350,
		open: function(event, ui){
        }
     }); 	
}


function AddForChange (){

    var typechange = $('#MainBascet #typechange').val();
    console.log(typechange);
    $.ajax({
	    url: site_dir+'catalognew/changemycatalog.php', 
	    type: "POST",
	    data:{catalognewmycatalog:$('#catalognewmycatalog').val(),detailschange:$('#MainBascet #detailschange').val(),typechange:typechange},         
	    dataType : "json",                   
	    success: function (data, textStatus) {  
                console.log(data);  

                if (!data.error){             
                    if (data.valueid){	
                        if (data.typechange){
                            $('#MainBascet').html($('#coinchange').html('<b><font color=red>Добавлена для обмена </font></b>'));
                        } else $('#MainBascet').html($('#coinchange').html('<b><font color=red>В коллекции </font></b>'));
                        $('#MainBascet #coinchange').show();
                    }
                } else if (data.error == 'auth') {
                    $("#mycatalog" + data.valueid).html('<b><font color=silver>Вы не авторизованы</font></b>');
                }  else {
                    $("#mycatalog" + data.valueid).html('<font color=red><b>'+data.error+ '</b></font>');
                }
            }
	});
}

function AddMailFriend() {
	var fio = $.trim($('#MainBascet #fio').val());
	var email = $.trim($('#MainBascet #email').val());
	var link = $.trim($('#MainBascet #link').val());
	var emailfriend = $.trim($('#MainBascet #emailfriend').val());
	var messageform = $.trim($('#MainBascet #messageform').val());
	if(!fio||!email||!link||!emailfriend||!messageform){
		$('#MainBascet #errorFriendsLetter').text('Заполните все поля!');
		return;
	}
	$.ajax({
		url: site_dir+'catalognew/mailFriends.php',
		type: "POST",
		data:{'fio': fio,'email': email,'link': link, 'emailfriend':emailfriend,'messageform':messageform},
		dataType : "json",
		success: function (data, textStatus) {
			if (!data.error){
				$('#MainBascet form').html('<span class="error center">Сообщение успешно отправлено</span>');
			} else  {
				$("#MainBascet #errorFriendsLetter").text(data.error);
			}
		}
	});
}

function addSubscribeCatalog(id){
	
	var amountacsessory = 1;
    
    if($('#amountscribecatalog'+id)){
        var amountacsessory = $('#amountscribecatalog'+id).val();
    } 

    if(!amountacsessory) amountacsessory = 1;
    $("#mysubscribecatalog"+id).html('<img src="'+site_dir+'images/wait.gif">');
	$.ajax({
	    url: site_dir+'catalognew/addsubscribecatalog.php', 
	    type: "POST",
	    data:{'catalog':id,amountacsessory:amountacsessory},         
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

function ShowNominals(){	
   $.ajax({
	    url: site_dir+'?module=detailscoins&task=showNominals', 
	    type: "POST",
	    data:{'id':$('#group').val()},         
	    dataType : "json",                   
	    success: function (data, textStatus) { 
	        
            var nameSelect = $('#nominal_id');			
			nameSelect.empty();	
			nameSelect.append($("<option>").attr('value',0).text("--||--"));				
			for (var i = 0; i < data.names.length; i++) {			    
				nameSelect.append($("<option>").attr('value',data.names[i].val).text(data.names[i].text));
			}           
       }
	});
}

        
function SearchFormClose()
{
    if (StatusSearchForm == "theme")
    {
        SearchByThemeClose();
    }
    else if (StatusSearchForm == "year")
    {
        SearchByYearClose();
    }
    else if (StatusSearchForm == "metal")
    {
        SearchByMetalClose();
    }
    else if (StatusSearchForm == "details")
    {
        SearchByDetailsClose();
    }

    StatusSearchForm = '';
}

/*
        function SearchByTheme ()
        {
            SearchFormClose();

            var ThemeSearchDiv = '';
            var str = '';
            <? //echo JavaString($DivThemeStr); ?>
            ThemeSearchDiv = str;
            var str = '';

            mytablesearch = document.getElementById("tablesearch");
            searchtdtheme = document.getElementById("SearchTdTheme");

            searchbytheme = document.getElementById("SearchByTheme");
            searchbytheme.style.position = "absolute";
            searchbytheme.innerHTML = ThemeSearchDiv;
            searchbytheme.style.left = mytablesearch.offsetLeft + searchtdtheme.offsetLeft + 170;
            searchbytheme.style.top = mytablesearch.offsetTop+searchtdtheme.offsetTop + (searchbytheme.offsetHeight/2) + 150;
            StatusSearchForm = 'theme';
        }

        function SearchByThemeClose()
        {
            searchbytheme = document.getElementById("SearchByTheme");
            searchbytheme.innerHTML = '';
        }

        function SearchByYear ()
        {
            SearchFormClose();
            var YearSearchDiv = '';
            var str = '';
            <? //echo JavaString($DivYearStr); ?>
            YearSearchDiv = str;
            var str = '';

            mytablesearch = document.getElementById("tablesearch");
            searchtdyear = document.getElementById("SearchTdYear");

            searchbyyear = document.getElementById("SearchByYear");
            searchbyyear.style.position = "absolute";
            searchbyyear.innerHTML = YearSearchDiv;
            searchbyyear.style.left = mytablesearch.offsetLeft + searchtdyear.offsetLeft + 170;
            searchbyyear.style.top = mytablesearch.offsetTop+searchtdyear.offsetTop + (searchbyyear.offsetHeight/2) + 150;
            StatusSearchForm = 'year';
        }

        function SearchByYearClose()
        {
            searchbyyear = document.getElementById("SearchByYear");
            searchbyyear.innerHTML = '';
        }

        function SearchByMetal ()
        {
            SearchFormClose();
            var MetalSearchDiv = '';
            var str = '';
            <? //echo JavaString($DivMetalStr); ?>
            MetalSearchDiv = str;
            var str = '';

            mytablesearch = document.getElementById("tablesearch");
            searchtdmetal = document.getElementById("SearchTdMetal");

            searchbymetal = document.getElementById("SearchByMetal");
            searchbymetal.style.position = "absolute";
            searchbymetal.innerHTML = MetalSearchDiv;
            searchbymetal.style.left = mytablesearch.offsetLeft + searchtdmetal.offsetLeft + 170;
            searchbymetal.style.top = mytablesearch.offsetTop+searchtdmetal.offsetTop + (searchbymetal.offsetHeight/2) + 150;
            StatusSearchForm = 'metal';
        }

        function SearchByMetalClose()
        {
            searchbymetal = document.getElementById("SearchByMetal");
            searchbymetal.innerHTML = '';
        }


        function SearchByDetails ()
        {
            SearchFormClose();
            var DetailsSearchDiv = '';
            var str = '';
            <? //echo JavaString($DivDetailsStr); ?>
            DetailsSearchDiv = str;
            var str = '';


            mytablesearch = document.getElementById("tablesearch");
            searchtddetails = document.getElementById("SearchTdDetails");

            searchbydetails = document.getElementById("SearchByDetails");
            searchbydetails.style.position = "absolute";
            searchbydetails.innerHTML = DetailsSearchDiv;
            searchbydetails.style.left = mytablesearch.offsetLeft + searchtddetails.offsetLeft + 170;
            searchbydetails.style.top = mytablesearch.offsetTop+searchtddetails.offsetTop + (searchbydetails.offsetHeight/2) + 150;
            StatusSearchForm = 'details';
        }

        function SearchByDetailsClose()
        {
            searchbytheme = document.getElementById("SearchByDetails");
            searchbydetails.innerHTML = '';
        }

        function WaitMyCatalog(catalog)
        {

            var str = "mycatalog"+catalog;
            myDiv = document.getElementById(str);
            myDiv.innerHTML = "<img src=<?echo $in?>images/wait.gif>";
        }    
        
        
var i = 0;

<?
	for ($i=2; $i<=100; $i++)
	{
		if (${"yearstart".$i})
			echo "var StatusYear".$i." = 1;";
		else
			echo "var StatusYear".$i." = 0;";
	}
?>
var StatusForm = 1;
var NameStatus = 'text';
NameArray = new Array();
GroupNameArray = new Array();
GroupValueArray = new Array();
MetalNameArray = new Array();
MetalValueArray = new Array();

function AddYear (i)
{
	var startyear = ''; 
	var endyear = '';
	//alert (eval('StatusYear'+i));
	var str = 'StatusYear' + i + ' == 1';
	if (eval(str))
	{
		
		eval ("var startyear = document.mainform.Fyearstart" + i + ".value;");
		eval ("var endyear = document.mainform.Fyearend" + i + ".value;");
		//alert (startyear+' '+endyear+' '+i);
				
	}
	else
	{
		//alert ('444');
		var str = "<input type=hidden name=Fyearstart" + i + " value='" + startyear + "'>";
		
		str += "<input type=hidden name=Fyearend" + i + " value='" + endyear + "'>";
		
		mDiv = document.getElementById('Fyear');
		mDiv.innerHTML = mDiv.innerHTML + str;
		//alert (myDiv.innerHTML);
		
		eval('StatusYear' + i + ' = 1;');
		
	}
	//alert (555);
	var str = "<br><input type=text name=yearstart" + i + " class=formtxt value='" + startyear + "' size=4> по <input type=text name=yearend" + i + " class=formtxt value='" + endyear + "' size=4> ";
	//alert (str);
	//str += "<input type=hidden name=Fyearstart"+i+" value='"+startyear+"'>";
	//str += "<input type=hidden name=Fyearend"+i+" value='"+endyear+"'>";
	str += "<div id=myDivYear" + (i + 1) + ">[<a href=# onclick='javascript:AddYear(" + (i+1) + ")'>+</a>]</div>";
	//alert (str);
	myDiv = document.getElementById('myDivYear'+i);
	myDiv.innerHTML = str;

	
}

function groupselect_f()
{
	var str = '';
	<? echo JavaString($groupselect_f);?>
	return str;
}

function groupselect_v()
{
	var str = '';
	<? echo JavaString($groupselect_v);?>
	return str;
}

function Form1_turn ()
{
	SaveData ();
	var str = '';
	<? echo JavaString($div1a);?>
	
	myDiv = document.getElementById("myDivElement1");
	myDiv.innerHTML = str;
	StatusForm = 0;
}

function Form2_turn ()
{
	SaveData ();
	var str = '';
	<? echo JavaString($div2a);?>
	
	myDiv = document.getElementById("myDivElement2");
	myDiv.innerHTML = str;
	StatusForm = 0;
}

function Form3_turn ()
{
	SaveData ();
	var str = '';
	<? echo JavaString($div3a);?>
	
	myDiv = document.getElementById("myDivElement3");
	myDiv.innerHTML = str;
	StatusForm = 0;
}


function Form1(a)
{
	if (a != 1)
	{
		SaveData ();
	}
	
	StatusForm = 1;
	
	var str = '';
	
	<? echo JavaString($div1);?>
	<? echo JavaString($form1);?>
	str += groupselect_f();
	str += groupselect_v();
	<? echo JavaString($form1_a);?>

	myDiv = document.getElementById("myDivElement1");
	myDiv.innerHTML = str;
	
	if (a!=1)
	{
		GetData ();
	}
	else
	{
	<?
	for ($i=2; $i<=100; $i++)
	{
		if (${"yearstart".$i})
			echo "AddYear (".$i.");
			StatusYear".$i." = 1;";
	}
	?>
	}
}

function Form2()
{
	SaveData ();
	StatusForm = 2;
	var str = '';
	<? echo JavaString($div2);?>
	<? echo JavaString($form2);?>

	myDiv = document.getElementById("myDivElement2");
	myDiv.innerHTML = str;
	GetData ();
}

function Form3()
{
	SaveData ();
	StatusForm = 3;
	var str = '';
	myDiv = document.getElementById("myDivElement3");
	<? echo JavaString($div3);?>
	<? echo JavaString($form3);?>

	myDiv.innerHTML = str;
	GetData ();
}

function SaveData ()
{
	CloseShowDetailsInfo();
	if (StatusForm == 1)
	{
		var str, str1, str2;
		document.mainform.Fgroup.value = 		document.mainform.group.value;
		document.mainform.Fname.value = 		document.mainform.name.value;
		document.mainform.Fyearstart1.value = 	document.mainform.yearstart1.value;
		document.mainform.Fyearend1.value = 	document.mainform.yearend1.value;
		
		
		for (i=2; i<=100; i++)
		{
			str = "StatusYear"+i;
			
			if (eval(str) == 1)
			{
				str1 = "document.mainform.Fyearstart" + i + ".value = document.mainform.yearstart" + i + ".value;";
				eval (str1);
				
				str1 = "document.mainform.Fyearend" + i + ".value = document.mainform.yearend" + i + ".value;";
				eval (str1);
			}
		}
			
		document.mainform.Famount.value = 		document.mainform.amount.value;
		document.mainform.Fcondition.value = 	document.mainform.condition.value;
	}
	else if (StatusForm == 2)
	{
		document.mainform.Faverslegend.value = 				document.mainform.averslegend.value;
		document.mainform.Freverselegend.value = 			document.mainform.reverselegend.value;
		document.mainform.Fmint.value = 					document.mainform.mint.value;
		document.mainform.Fdesigner.value = 				document.mainform.designer.value;
		document.mainform.Fofficialdate.value = 			document.mainform.officialdate.value;
		document.mainform.Fdetails.value = 					document.mainform.details.value;
		//Ftheme
		
		for (i=1; i<=25; i++)
		{
			//alert('document.mainform.theme' + i + '.checked =' + eval ('document.mainform.theme' + i + '.checked'));
			if (eval ('document.mainform.theme' + i + '.checked'))
			{
				eval ("document.mainform.Ftheme" + i + ".value = 1;")
			}
			else
			{
				eval ("document.mainform.Ftheme" + i + ".value = 0;")
			}
		}
	}
	else if (StatusForm == 3)
	{
		document.mainform.Fmetal.value = 	document.mainform.metal.value;
		document.mainform.Fprobe.value = 	document.mainform.probe.value;
		document.mainform.Fprocent.value = 	document.mainform.procent.value;
		document.mainform.Fsize.value = 	document.mainform.size.value;
		document.mainform.Fthick.value = 	document.mainform.thick.value;
		document.mainform.Fweight.value = 	document.mainform.weight.value;
		document.mainform.Fherd.value = 	document.mainform.herd.value;
	}
}

function GetData ()
{
	if (StatusForm == 1)
	{
		if (GroupNameArray.length > 0)
		{
			for (i=0; i<GroupNameArray.length; i++)
			{
				var el = document.createElement("OPTION");
				el.value = GroupValueArray[i];
				el.text = GroupNameArray[i];
				document.mainform.group.options.add(el);
			}
		}
		
		document.mainform.group.value = 		document.mainform.Fgroup.value;
		
		if (NameStatus == 'selecttext')
		{
			var myFile = 'shownamebygroup.php?group='+document.mainform.group.value;
			process(myFile);
			
			if (NameArray.length > 0)
			{
				for (i=0; i<NameArray.length; i++)
				{
					var el = document.createElement("OPTION");
					el.value = NameArray[i];
					el.text = NameArray[i];
					document.mainform.name.options.add(el);
				}
			}
		}
		
		document.mainform.name.value = 			document.mainform.Fname.value;
		document.mainform.yearstart1.value = 	document.mainform.Fyearstart1.value;
		document.mainform.yearend1.value = 		document.mainform.Fyearend1.value;
		
		var str, str1, str2;
		for (i=2; i<=100; i++)
		{
			str = "StatusYear"+i;
			if (eval(str) == 1)
			{
				AddYear (i);
				str1 = "document.mainform.yearstart" + i + ".value = document.mainform.Fyearstart" + i + ".value;";
				eval(str1);
				
				str1 = "document.mainform.yearend" + i + ".value = document.mainform.Fyearend" + i + ".value;";
				eval (str1);
			}
		}
		
		document.mainform.amount.value = 		document.mainform.Famount.value;
		document.mainform.condition.value = 	document.mainform.Fcondition.value;

	}
	else if (StatusForm == 2)
	{
		document.mainform.averslegend.value = 				document.mainform.Faverslegend.value;
		document.mainform.reverselegend.value = 			document.mainform.Freverselegend.value;
		document.mainform.mint.value = 						document.mainform.Fmint.value;
		document.mainform.designer.value = 					document.mainform.Fdesigner.value;
		document.mainform.officialdate.value = 				document.mainform.Fofficialdate.value;
		document.mainform.details.value = 					document.mainform.Fdetails.value;
		//theme
		
		for (i=1; i<=25; i++)
		{
			//alert ("document.mainform.Ftheme" + i + ".value = "+eval ("document.mainform.Ftheme" + i + ".value"));
			if (eval ("document.mainform.Ftheme" + i + ".value") == 1)
			{
				eval ("document.mainform.theme" + i + ".checked = true;")
			}
			else
			{
				eval ("document.mainform.theme" + i + ".checked = false;")
			}
		}
	}
	else if (StatusForm == 3)
	{
		if (MetalNameArray.length > 0)
		{
			for (i=0; i<MetalNameArray.length; i++)
			{
				var el = document.createElement("OPTION");
				el.value = MetalValueArray[i];
				el.text = MetalNameArray[i];
				document.mainform.metal.options.add(el);
			}
		}
		
		document.mainform.metal.value = 	document.mainform.Fmetal.value;
		document.mainform.probe.value = 	document.mainform.Fprobe.value;
		document.mainform.procent.value = 	document.mainform.Fprocent.value;
		document.mainform.size.value = 		document.mainform.Fsize.value;
		document.mainform.thick.value = 	document.mainform.Fthick.value;
		document.mainform.weight.value = 	document.mainform.Fweight.value;
		document.mainform.herd.value = 		document.mainform.Fherd.value;
	}
}

function CheckedGroup ()
{
	str = document.mainform.group.value;
	if (str == 'add')
	{
		// Добавляет новый вариант. 
		myDivAddGroup();
	}
	else if (str != 0)
	{
		//скрипт для показа номиналов группы
		//загружаем select или input
		var myFile = 'shownamebygroup.php?group=' + document.mainform.group.value;
		process(myFile);
		myDiv = document.getElementById("myDivAddGroup");
		myDiv.innerHTML = '';
	}
}

function CheckedName ()
{
	str = document.mainform.name.value;
	if (str == 'add')
	{
		// Добавляет новый вариант. 
		myDivAddName();
	}
	else if (str != 0)
	{
		myDiv = document.getElementById("myDivAddName");
		myDiv.innerHTML = '';
		
		//var myFile = 'showcoinsbygroupandname.php?group=' + document.mainform.group.value + '&name=' + document.mainform.name.value;
		//alert (myFile);
		//process(myFile);
	}
}

function CheckedMetal ()
{
	str = document.mainform.metal.value;
	if (str == 'add')
	{
		// Добавляет новый вариант. 
		myDivAddMetal();
	}
	else if (str != 0)
	{
		myDiv = document.getElementById("myDivAddMetal");
		myDiv.innerHTML = '';
	}
}

function myDivAddMetal()
{
	var str = '';
	<? echo JavaString($myDivAddMetal);?>
	myDiv = document.getElementById("myDivAddMetal");
	myDiv.innerHTML = str;
}

function myDivAddName()
{
	var str = '';
	<? echo JavaString($myDivAddName);?>
	myDiv = document.getElementById("myDivAddName");
	myDiv.innerHTML = str;
}


function myDivAddGroup()
{
	var str = '';
	<? echo JavaString($myDivAddGroup);?>
	str += groupselect_v();
	<? echo JavaString($myDivAddGroup1);?>
	myDiv = document.getElementById("myDivAddGroup");
	myDiv.innerHTML = str;
}

var StatusShowDetailsInfo = "";

function StartShowDetailsInfo (valueid)
{
	CloseShowDetailsInfo();
	myDiv = document.getElementById("detailsinfo");
	myDiv.style.position = "absolute";
	myDiv.style.left = document.body.scrollLeft + event.x + 5;
	myDiv.style.top = document.body.scrollTop + event.y + 5;
	myDiv.innerHTML = "<img src=../images/wait.gif>";
	
	process('detailsinfo.php?valueid='+valueid);
	StatusUserInfo = 1;
}

function CloseShowDetailsInfo()
{
	if (StatusUserInfo != "")
	{
		myDiv = document.getElementById("detailsinfo");
		myDiv.innerHTML = "";
	}
}

function ShowDetailsInfo ()
{
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		var str = '';
		var valueid = '';
		var value = '';
		
		valueid = xmlRoot.getElementsByTagName("valueid").item(0).firstChild.data;
		value = xmlRoot.getElementsByTagName("value").item(0).firstChild.data;
		
		if (value != "empty")
		{
			myDiv = document.getElementById("detailsinfo");
			myDiv.innerHTML = "<table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #000000' id=tableshowshopcoins width=300><tr class=tboard bgcolor=#ffcc66><td align=right><a href=# onclick=\"javascript:CloseShowDetailsInfo();\"><img src=../images/windowsclose.gif border=0></a></td></tr><tr class=tboard bgcolor=#EBE4D4><td class=tboard valign=top colspan=2>" + value + "</td></tr></table>";
		}
	}
}

function CheckImage()
{
	str = document.mainform.image.value;
	//alert (str);
	process('checkimage.php?image='+str);
}

   function ChangeDivCoinInfo()
	{
		CloseUserInfo();
		var str = '';
		<? echo JavaString($details);?>
		myDiv = document.getElementById("myDivElement");
		myDiv.innerHTML = str;
	}

	function ChangeDivAgree()
	{
		CloseUserInfo();
		var str = '';
		<? echo JavaString($agreeText);?>
		myDiv = document.getElementById("myDivElement");
		myDiv.innerHTML = str;
	}
	
	function ChangeDivReview()
	{
		CloseUserInfo();
		var str = '';
		<? echo JavaString($reviewText);?>
		myDiv = document.getElementById("myDivElement");
		myDiv.innerHTML = str;
	}
	
	function ChangeDivPrice()
	{
		CloseUserInfo();
		var str = '';
		<? echo JavaString($priceText);?>
		myDiv = document.getElementById("myDivElement");
		myDiv.innerHTML = str;
	}	
	
        
        */      

