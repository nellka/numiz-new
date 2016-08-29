<?
if($tpl['catalognew']['error']['no_auth']){?>
    <div class="error">Добавлять и редактировать данные могут только зарегистрированные пользователи! </div>
<?
}?>
<p>Уважаемые пользователи. Настоятельно просим вас не "воровать" изображения монет с других сайтов. Просим вас 
добавлять изображения монет в каталог только из своей личной коллекции. Нам не нужны вопросы, связанные с копирайтом.</p>

<form action=http://www.numizmatik.ru/new/?module=catalognew&task=addcoins&catalog=<?=$catalog?> method=post enctype=multipart/form-data class="wform">
<?
if($catalog&&$data){?>
    <h1>Редактирование элемента каталога <?=$data["name"]?></h1>
<?}
?>
	
<div id=detailsinfo></div>

<div>
<div id=myDivAddImage></div>
<?if($image_big_url){?>
 <div id=Image_Big class="main_img_big  center">
    	<?=contentHelper::showImage($image_big_url,"",array('alt'=>"",'folder'=>'catalognew'))?>	
 </div>	<br>   
<?}?>
</div>

<div><b>Изображение: (<font color=red>*</font>)</b>:
<a name=image></a><input type=file name=image class=formtxt size=40> 


</div>
<input type=hidden name=catalog value='<?=$catalog?>'>	
<input type="hidden" name="submitaftererror" value="<?=$submitaftererror?>">

<?if (sizeof($errors)){?>
	<div class="error"><b><?=implode("<br>",$errors)?></b></div>
<?}
if($tpl['addcoinsExist']){?>
    <table border=0 cellpadding=3 cellspacing=1 align=center width=500>
    <?foreach ($tpl['addcoinsExist'] as $rows){?>
		
		<tr class=tboard valign=top bgcolor=#EBE4D4>
		<td width="200">
		<?=contentHelper::showImage($rows["image_small_url"],"",array('alt'=>$rows["name"],'folder'=>'catalognew'))?>
		</td>
		<td><b><?=$rows["name"]?></b>
		<?=($rows["yearstart"]?"<br><b>Год:</b> ".$rows["yearstart"]:"")?>
		<?=($rows["metal"]?"<br><b>Метал:</b> ".$rows["mname"]:"")?>
		<?=($rows["probe"]?"<br><b>Проба:</b> ".$rows["probe"]:"")?>
		<?=($rows["procent"]?"<br><b>Cоотношение металла:</b> ".$rows["procent"]:"")?>
		<?=($rows["amount"]?"<br><b>Тираж:</b> ".$rows["procent"]:"")?>
		<?=($rows["size"]?"<br><b>Диаметр:</b> ".$rows["size"]:"")?>
		<?=($rows["thick"]?"<br><b>Толщина:</b> ".$rows["thick"]:"")?>
		<?=($rows["weight"]?"<br><b>Вес:</b> ".$rows["weight"]:"")?>
		<?=($rows["condition"]?"<br><b>Состояния(е):</b> ".$rows["condition"]:"")?>
		
		</td></tr>	
		
		
	<?}?>
	<tr><td>
		<p align=center><input type=submit value='Нет, такой в этом списке не существует. Добавить мою монету'></p>
	</td></tr>	
	</table>
<?}?>   			
<div class="addcons_block">
<a onclick="showInvis('myDivElement1');return false;" href="#" class="h"><img src="<?=$cfg['site_dir']?>/images/windowsmaximize.gif" border=0> Общая информация</a>

<div id="myDivElement1" style="display:table">

    <p>
        <label for="group"><b>Страна (<font color=red>*</font>)</b></label>
      
        <select name=group id=group onchange='ShowNominals()'>
            <option value=0>Выберите страну</option>        
            <? 
            foreach ($tpl['groups_parent'] as $rows){?>		
            	<option value='<?=$rows["group"]?>' <?=selected($rows["group"],$group)?>><?=$rows["name"]?></option>
            	<?
            	if (is_array($GroupArray[$rows["group"]])){
            		foreach ($GroupArray[$rows["group"]] as $key=>$value){?>
            			<option value='<?=$key?>' <?=selected($key,$group)?>> |--<?=$value?></option>
            		<?}
            	}	
            }?>
            </select>&nbsp;&nbsp;[<a href=#group onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=2&ajax=1","450");return false;'><font color=red> ? </font></a>]
        
    </p>
    <p>
        <label for="name"><b>Номинал (<font color=red>*</font>)</b></label>
        <select name=nominal_id id=nominal_id>
        		<option value=0>Выберите номинал</option>        	
        		<?foreach ($tpl['nominals'] as $rows_info){?>
        		    <option value='<?=$rows_info['nominal_id']?>' <?=selected($rows_info['nominal_id'],$nominal_id)?>><?=$rows_info['name']?></option>        			
        		<?}?>
                </select>&nbsp;&nbsp;[<a href=#name onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=3&ajax=1","450");return false;'><font color=red> ? </font></a>]
       
    </p>    
    <div>
      <label for="yearstart"><b>Периоды чеканки</b></label>
      <div class="left">
      
      <?for($i=1;$i<=$year_p;$i++){?>
          <div id='year_period<?=$i?>'>
            <input type=text name=yearstart<?=$i?> value='<?=${"yearstart".$i}?>' size=6>&nbsp;&nbsp;по&nbsp;&nbsp;
            <input type=text name=yearend<?=$i?> value='<?=${"yearend".$i}?>' size=6>             
           </div>
      <?}?>
      <span id=myDivYear2>[<a href=#year onclick='AddYear()'>+</a>]</span>&nbsp;&nbsp;[<a href=#image onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=4&ajax=1","450");return false;'><font color=red> ? </font></a>]
      </div>
       <input type="hidden" name="year_p" id="year_p" value="<?=($year_p)?>">
    </div>
   
    
    <p>
      <label for="amount"><b>Тираж (в тысячах штук)</b></label>
      <input type=text name=amount value='<?=$amount?>' size=8> (Например: 0,5 = 500 штук)&nbsp;&nbsp;[<a href=#amount onclick='<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=5&ajax=1'><font color=red> ? </font></a>]
    </p>
     <p>
      <label for="condition"><b><b>Состояния при чеканке</b></b></label>
     
        <select name=condition >
        <option value=0>Выберите</option>>
        
        <?foreach ($ConditionMintArray as $key=>$value){?>
        	<option value="<?=$key?>" <?=selected($key,$condition)?>><?=$value?></option>
        <?}?>
       </select>&nbsp;&nbsp;[<a href=#amount onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=6&ajax=1","450");return false;'><font color=red> ? </font></a>]
      </p>
</div> 
</div>

<div class="addcons_block">
<a class="h" onclick="showInvis('myDivElement2');return false;" href="#"><img src="<?=$cfg['site_dir']?>/images/windowsmaximize.gif" border=0>Описание</a>

<div id="myDivElement2" style="display:none">

    <p>
      <label for="averslegend"><b>Легенда аверса</b></label>
      <textarea name=averslegend cols=45 rows=6><?=$averslegend?></textarea>&nbsp;&nbsp;[<a href=#averslegend onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=7&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>
    
    <p>
      <label for="reverselegend"><b>Легенда реверса</b></label>
      <textarea name=reverselegend cols=45 rows=6><?=$reverselegend?></textarea>&nbsp;&nbsp;[<a href=#reverselegend onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=8&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>
    
    <p>
      <label for="mint"><b>Монетные двора:</b></label>
      <input type=text name=mint value='<?=$mint?>' size=40>&nbsp;&nbsp;[<a href=#averslegend onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=9&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>    
    <p>
      <label for="designer"><b>Дизайнер:</b></label>
     <input type=text name=designer value='<?=$designer?>' size=40> &nbsp;&nbsp;[<a href=#averslegend onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=10&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>   
	<p>
  	<label for="designer"><b>Официальная дата выпуска:</b></label>
     <input type=text name=officialdate value='<?=$officialdate?>' size=20>&nbsp;&nbsp;(<b>Формат день-месяц-год</b>, например 25-03-2002) &nbsp;&nbsp;[<a href=#officialdate onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=11&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>   
	<p>
      <label for="reverselegend"><b>Развернутое описание:</b></label>
      <textarea name=details cols=45 rows=6><?=$details?></textarea>&nbsp;&nbsp;[<a href=#details onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=12&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>
    
    <div>
      <label><b>Тематики</b></label>
      <div class="left" style="width:500px">
      <?foreach ($ThemeArray as $key=>$value){?>
       <div class="left" style="width:33%"><input type=checkbox name=theme<?=$key?> <?=(${"theme".$key}=="on"?"checked":"")?>><?=$value?>&nbsp;&nbsp;</div>
      <?}?>
     </div>
 		[<a href=#image onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=13&ajax=1","450");return false;'><font color=red> ? </font></a>]
      
    </div> 
   </div>
</div>


<div class="addcons_block">
<a class="h"  onclick="showInvis('myDivElement3');return false;" href="#"><img src="<?=$cfg['site_dir']?>/images/windowsmaximize.gif" border=0>Характеристики</a>

<div id="myDivElement3" style="display:none">
 	<p>
        <label for="metal"><b>Металл</b></label>
        <select name=metal id=metal >
        		<option value='0'>Выберите металл</option>        	
        		<?foreach ($tpl['metals'] as $rows_info){?>
        		    <option value='<?=$rows_info['metal']?>' <?=selected($rows_info['metal'],$metal)?>><?=$rows_info['name']?></option>        			
        		<?}?>
         </select>&nbsp;&nbsp;
         [<a href=#name onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=14&ajax=1","450");return false;'><font color=red> ? </font></a>]
       
    </p>    
	<p>
      <label for="probe"><b>Проба:</b></label>
      <input type=text name=probe value='<?=$probe?>' size=10>&nbsp;&nbsp;(Например: 900)&nbsp;&nbsp;
      [<a href=#probe onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=15&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>
    
    <p>
      <label for="procent"><b>Соотношение металла:</b></label>
      <input type=text name=procent value='<?=$procent?>' size=10>&nbsp;&nbsp;(Например: Cu-15%,Ni-85%)&nbsp;&nbsp;
      [<a href=#procent onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=16&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>

    <p>
      <label for="weight"><b>Вес, г.:</b></label>
      <input type=text name=weight value='<?=$weight?>' size=20>&nbsp;&nbsp;
      [<a href=#details onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=17&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>

    <p>
      <label for="size"><b>Диаметр, мм.:</b></label>
      <input type=text name=size value='<?=$size?>' size=20>&nbsp;&nbsp;
      [<a href=#details onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=18&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>
    
    <p>
      <label for="thick"><b>Толщина, мм.:</b></label>
      <input type=text name=thick value='<?=$thick?>' size=20>&nbsp;&nbsp;
      [<a href=#thick onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=19&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>

    <p>
      <label for="herd"><b>Тип гурта описание.:</b></label>
      <input type=text name=herd value='<?=$herd?>' size=20>&nbsp;&nbsp;(Например: Сетчатый, Гуртовая надпись)&nbsp;&nbsp;
      [<a href=#herd onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=19&ajax=1","450");return false;''><font color=red> ? </font></a>]
    </p>

</div>
</div>

<div class="center">
<br><br>
<input type=submit name="submit" value='Записать' class="button25" >
</div>
<?/*

<input type=hidden name=Fgroup value='$group'>
<input type=hidden name=Fname value='$name'>";
$mainform .= "<input type=hidden name=Fyearstart1 value='".$yearstart1."'>";
$mainform .= "<input type=hidden name=Fyearend1 value='".($yearend1?$yearend1:"")."'>";

for ($i=2; $i<=100; $i++)
{
	if (${"yearstart".$i})
	{
		$mainform .= "<input type=hidden name=Fyearstart".$i." value='".${"yearstart".$i}."'>
		<input type=hidden name=Fyearend".$i." value='".(${"yearend".$i}?${"yearend".$i}:"")."'>";
	}
}
$mainform .= "<div id=Fyear></div>";
$mainform .= "<input type=hidden name=Fmetal value='$metal'>
<input type=hidden name=Fprobe value='$probe'>
<input type=hidden name=Fprocent value='$procent'>
<input type=hidden name=Famount value='$amount'>
<input type=hidden name=Fsize value='$size'>
<input type=hidden name=Fthick value='$thick'>
<input type=hidden name=Fweight value='$weight'>";

foreach ($ThemeArray as $key=>$value)
{
	$mainform .= "<input type=hidden name=Ftheme".$key." ".(${"theme".$key}=="on"?"value=1":"").">";
}

$mainform .= "<input type=hidden name=Fcondition value='$condition'>
<input type=hidden name=Fherd value='$herd'>
<input type=hidden name=Faverslegend value='$averslegend'>
<input type=hidden name=Freverselegend value='$reverselegend'>
<input type=hidden name=Fmint value='$mint'>
<input type=hidden name=Fdesigner value='$designer'>
<input type=hidden name=Fofficialdate value='$officialdate'>
<input type=hidden name=Fdetails value='$details'>
<input type=hidden name=Fimage_small_url value='$image_small_url'>
<input type=hidden name=Fimage_big_url value='$image_big_url'>

</form>";*/?>

<script>
function AddYear()
{
    
    var i = parseInt($('#year_p').val());
    var n = i+1;  
    var block = $("<div id='year_period"+n+"'> <input type=text name=yearstart"+n+" value='' size=6>&nbsp;&nbsp;по&nbsp;&nbsp;"+
            "<input type=text name=yearend"+n+" value='' size=6></div>");
            
    block.insertAfter($('#year_period'+i));
    $('#year_p').val(n);
    /*
	var startyear = ''; 
	var endyear = '';
	//alert (eval('StatusYear'+i));
	var str = 'StatusYear' + i + ' == 1';
	if (eval(str))
	{
		
		eval ("var startyear = document.mainform.Fyearstart" + i + ".value;");
		eval ("var endyear = document.mainform.Fyearend" + i + ".value;");
		//alert (startyear+' '+endyear+' '+i);
				
	} else	{
		var str = "<input type=hidden name=Fyearstart" + i + " value='" + startyear + "'>";		
		str += "<input type=hidden name=Fyearend" + i + " value='" + endyear + "'>";		
		mDiv = document.getElementById('Fyear');
		mDiv.innerHTML = mDiv.innerHTML + str;
		eval('StatusYear' + i + ' = 1;');		
	}
	var str = "<br><input type=text name=yearstart" + i + " class=formtxt value='" + startyear + "' size=4> по <input type=text name=yearend" + i + " class=formtxt value='" + endyear + "' size=4> ";
	str += "<div id=myDivYear" + (i + 1) + ">[<a href=# onclick='javascript:AddYear(" + (i+1) + ")'>+</a>]</div>";
	myDiv = document.getElementById('myDivYear'+i);
	myDiv.innerHTML = str;*/
	
}

function ShowNominals(){	
   $.ajax({
	    url: site_dir+'new/?module=detailscoins&task=showNominals', 
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

</script>
<? /*
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

</script>
*/?>
</div>
</div>