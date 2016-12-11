<div class="main_context bordered rating table">
<h1>Распознавание монет</h1>

<div class="bg-8e8 bordered">
<div id=MainTag class="justify"></div>
</div>

<div class="center">При подготовке материала были использованы фотографии с <a href=http://numizmat.net/gallery/ target=_blank>http://numizmat.net/gallery/</a></div>

<div class="bg-8e8 bordered hidden" id=SelectTagDIV>
<div id=SelectTag class="justify"></div>
</div>


<div id=PagesTop class="right"></div>
<br class="clear">
<div id="ShowClassificator" class="sClassificator"></div>
<br class="clear">
<div id="PagesDown" class="right"></div><br>

<script type="text/javascript" src="<?=$cfg['site_dir']?>js/jkl-parsexml.js" language="JavaScript"></script>
<script type="text/javascript">

$(document).ready(function() {
	DoSelect (1);
});

Tag = new Array();
<?foreach ($tpl['classificator']['script_tags'] as $key=>$value ){
	echo "Tag[$key] =\"$value\";\n";
}?>

var SelectTag = '';
var pagenum = 0;

function DeleteTag (TagId)
{
	M = SelectTag.split('*');
	if (M.length = 2)
	{
		SelectTag = SelectTag.replace(TagId + '*', '');
	}
	else
	{
		SelectTag = SelectTag.replace('*' + TagId + '*', '*');
	}
	DoSelect(1);
	ShowSelect ();
}

function ShowSelect ()
{
	var i = 0;
	M = SelectTag.split('*');
	var html = '';
	if(M.length) {
		$('#SelectTagDIV').show();
	} else {
		$('#SelectTagDIV').hide();
	}
	for (i = 0; i < M.length-1; i++){
		html += ' <b>' + Tag[M[i]] + '</b> [<a href=# onclick="DeleteTag(\'' + M[i] + '\');" title="Удалить">X</a>] ';
	}
	$("#SelectTag").html(html);
}

function AddToSelect (TagId)
{
	//на всяк случай проверка, если есть
	M = SelectTag.split('*');
	if (M.length = 2)
	{
		SelectTag = SelectTag.replace(TagId + '*', '');
	}
	else
	{
		SelectTag = SelectTag.replace('*' + TagId + '*', '*');
	}
	
	SelectTag = SelectTag + TagId + '*';
	ShowSelect();
	DoSelect(1);
}

function DoSelect (pagenum)
{
	var url = '<?=$cfg['site_dir']?>new/classificator/select.php?Tag=' + SelectTag + '&pagenum=' + pagenum;

    var xml = new JKL.ParseXML( url );
    var data = xml.parse();
	var html = '';
	
	if (data.response.TagSelectAmount > 0)
	{
		if (data.response.TagSelectAmount > 1)
		{
			for (var i = 0; i < data.response.TagSelectAmount; i++)
			{
				html += ' <a href=# onclick=\"AddToSelect(' + data.response.TagSelect[i] + ');\"><font style="font-size: ' + data.response.FontSelect[i] + '%">' + Tag[data.response.TagSelect[i]] + '</font></a> ';
			}
		}
		else
		{
			if (data.response.TagSelectAmount == 1)
			{
				html += ' <a href=# onclick=\"AddToSelect(' + data.response.TagSelect + ');\"><font style="font-size: ' + data.response.FontSelect + '%">' + Tag[data.response.TagSelect] + '</font></a> ';
			}
			else
			{
				html += ' Отсутствуют ';
			}
		}
	}
	else
	{
		html += ' Отсутствуют ';
	}
	
	myDiv = document.getElementById("MainTag");
	myDiv.innerHTML = html;
	
	if (data.response.pagecount > 0)
	{
		//строим страницы
		var pagenum = parseInt(data.response.pagenum);
		var numpages = parseInt(data.response.numpages);
		var frompage = parseInt(data.response.frompage);
		var topage = parseInt(data.response.topage);
		var pages = parseInt(data.response.pages);
		var page_string = '';
		
		if (pagenum > 2*numpages) 
		{
			//page_string += '<a href=# onclick="DoSelect(1);" class="normal_ref_color">[в начало]</a>';
		}
		
		
		
		if (frompage > numpages) 
		{
			page_string += '<a href=# class="normal_ref_color" onclick="DoSelect(' + (frompage-1) + ');"><</a>';
		}
		
		for (var i = frompage; i <= topage; i++)
		{
			
			if (i == pagenum) 
			{
				page_string += '<span class="active">' + i + '</span>';
			}
			else 
			{
				page_string += '<a href=# class="normal_ref_color"  onclick="DoSelect(' + i + ');">' + i + '</a>';
			}			
			
		}      
		
		//alert (pages + "="+topage);
		
		if (parseInt(pages) > parseInt(topage)) 
		{
			page_string += '<a href=# onclick="DoSelect(' + i + ');">></a>';
		}
		
		page_string = '<p class=paginator>' + page_string + '</p>';
		
		myDiv = document.getElementById("PagesTop");
		myDiv.innerHTML = page_string;
		
		myDiv = document.getElementById("PagesDown");
		myDiv.innerHTML = page_string;
		
		html = '';
		//html = '<table border=0 cellpadding=5 cellspacing=0 align=center width=90%>';
		if (data.response.pagecount > 1)
		{
			shopcoinsgroupamount = parseInt(data.response.shopcoinsgroupamount);
			var ShopcoinsGroupCheck = new Array();
			for (var i = 0; i <shopcoinsgroupamount; i++)
			{
				var k = 0;
				var l = 0;
				
				k = data.response.shopcoinsgroup[i];
				l = data.response.shopcoinsgroupcheck[i];
				ShopcoinsGroupCheck[k] = l;
			}
			
			for (var i = 0; i < parseInt(data.response.pagecount); i++)
			{
				if (i%2 == 0)
				{
					//html += '<tr class=tboard valign=top><td width=50%>';
				}
				else
				{
					//html += '<td width=50%>';
				}
				html += '<div class="left center">';
				html += ' <img class="bordered" src="<?=$cfg['site_dir']?>classificator/images/' + data.response.classificator[i].image + '" > ';
				if (data.response.classificator[i].details != 'empty')
				{
					html += '<br><b>Описание:</b> ' + data.response.classificator[i].details;
				}
				
				//таги
				if (data.response.classificator[i].TagsAmount > 1)
				{
					html += '<br><b>Таги: </b>';
					for (var k = 0; k < data.response.classificator[i].TagsAmount; k++)
					{
						html += ' ' + Tag[data.response.classificator[i].TagId[k]];
					}
				}
				else
				{
					html += '<br><b>Таги: </b>';
					html += ' ' + Tag[data.response.classificator[i].TagId];
				}
				
				//страны
				if (data.response.classificator[i].countryamount > 1)
				{
					html += '<br><b>Страны: </b>';
					for (var k = 0; k < data.response.classificator[i].countryamount; k++)
					{
						html += ' ' + data.response.classificator[i].country.name[k];
						
						l = data.response.classificator[i].country.countryid[k];
						html += '<br>Посмотреть в ';
						if (ShopcoinsGroupCheck[l] == 1)
						{
							html += '<a href=http://www.numizmatik.ru/shopcoins/index.php?group=' + l + ' target=_blank>магазине</a>';
						}
						html += ' <a href=http://www.numizmatik.ru/catalognew/index.php?group=' + l + ' target=_blank>каталоге</a>';
					}
				}
				else
				if (data.response.classificator[i].countryamount == 1)
				{
					html += '<br><b>Страны: </b>';
					html += ' ' + data.response.classificator[i].country.name;
					l = data.response.classificator[i].country.countryid;
					html += '<br>Посмотреть в ';
					if (ShopcoinsGroupCheck[l] == 1)
					{
						html += '<a href=http://www.numizmatik.ru/shopcoins/index.php?group=' + l + ' target=_blank>магазине</a>';
					}
					html += ' <a href=http://www.numizmatik.ru/catalognew/index.php?group=' + l + ' target=_blank>каталоге</a>';
				}
				
				if (i%2 == 0)
				{
					//html += '</td>';
				}
				else
				{
					//;html += '</td></tr>';
				}
				html += '</div>';
			}
			if (i%2 == 1)
			{
				//html += '</tr>';
			}
			
		}	else {
			html += ' <tr class=tboard><td><img src=images/' + data.response.classificator.image + ' border=1> ';
			if (data.response.classificator.details != 'empty')
			{
				html += '<br><b>Описание:</b> ' + data.response.classificator.details;
			}
			
			//таги
			if (parseInt(data.response.classificator.TagsAmount) > 1)
			{
				html += '<br><b>Таги: </b>';
				for (var k = 0; k < parseInt(data.response.classificator.TagsAmount); k++)
				{
					html += ' ' + Tag[data.response.classificator.TagId[k]];
				}
			}
			else
			{
				html += '<br><b>Таги: </b>';
				html += ' ' + Tag[data.response.classificator.TagId];
			}
			
			//страны
			if (parseInt(data.response.classificator.countryamount) > 1)
			{
				html += '<br><b>Страны: </b>';
				for (var k = 0; k < parseInt(data.response.classificator.countryamount); k++)
				{
					html += ' ' + data.response.classificator.country.name[k];
				}
			}
			else
			if (parseInt(data.response.classificator.countryamount) == 1)
			{
				html += '<br><b>Страны: </b>';
				html += ' ' + data.response.classificator.country.name;
			}
			html += '</td></tr>';
		}
		
		html += '</table>';
		myDiv = $("#ShowClassificator").html(html);		
	}	
}
</script>
</div>