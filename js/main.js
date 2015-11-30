function initMenu() { // закрывает изначально открытые выпадающие списки

	myObj=eval("document.getElementById('menutop')");
	
	for(i=2;i<=myObj.rows[0].cells.length-1;i++) // Пока i=1, не достигнет количества столбцов
	
	{
	
		mymenu=eval("document.getElementById('menu' + i)"); // получает элимент
		
		mymenu.style.display='none';
	
	}
	
	move3(); // Если хотим его перемещать

}

function hide3(menu) {

	mymenu=eval("document.getElementById(menu)");
	
	mymenu.style.display="none";

} 

function show3(menu) { // Раздельное скрытие.раскрытие работает корректней

	if (navigator.appName == "Netscape")
	
	{
	
		for(i=2;i<=myObj.rows[0].cells.length-1;i++) // Пока i=1, не достигнет количества столбцов
	
		{
		
			mymenu=eval("document.getElementById('menu' + i)"); // получает выпадающие меню
			
			mymenu.style.display="none"; // закрыть все меню (в Нетскейпе могут не корректно закрываться)
		
		}
	
	}
	
	mymenu=eval("document.getElementById(menu)");
	
	mymenu.style.display="block";

}

function move3() // Функция перемещения:

{

	myObj=eval("document.getElementById('menutop')");
	
	myObj.style.top=document.body.scrollTop; // объект настолько удален от верхнего угла документа,
	
	for(i=2;i<=myObj.rows[0].cells.length-1;i++) // Пока i=1, не достигнет количества столбцов
	
	{
	
		mymenu=eval("document.getElementById('menu' + i)"); // получает выпадающие меню
		
		mymenu.style.top=document.body.scrollTop+myObj.rows[0].cells[i-1].offsetHeight; // устанавливает свойства выпадающих списков, с большой точностью
		
		mymenu.style.left=myObj.rows[0].cells[i-1].offsetLeft+myObj.offsetLeft+10; // определяя высоту и отступ с лева
		
		if (navigator.appName == "Netscape")
		
		{
		
			mymenu.style.display="none"; // закрыть все меню (в Нетскейпе могут не корректно закрываться)
		
		}
	
	}

} 

//window.onresize=move3; // Объявляются обработчики событий: изменение размеров

//window.onscroll=move3; // и скролинг вызывают функцию move

if (navigator.appName == "Netscape")

{

//window.onkeyup=move3; // Netscape не генерирует scroll если прокрутка идет с клавиатуры

//window.onclick=move3; // Netscape не генерирует scroll если прокрутка идет колесом мыши

} // частично это компенсируется реакцией на щелчек 
 
function getPosXx(obj) {
	var x=0, y=0;
	while(obj) {
	   x+=obj.offsetLeft;
	   obj=obj.offsetParent;
	}
	return x;
}
	
function getPosYy(obj) {
	var x=0, y=0;
	while(obj) {
	   y+=obj.offsetTop;
	   obj=obj.offsetParent;
	}
	return y;
}

function ShowFormCall() {

	divstr = "showcalllink";
	myDiv2 = document.getElementById(divstr);
	var posX = getPosXx(myDiv2);
	var posY = getPosYy(myDiv2);
	
	myDiv = document.getElementById("showformcall");
	myDiv.style.position = "absolute";
	myDiv.style.left = posX -100;
	myDiv.style.top = posY-170;
	var textone = "<table width=450 bgcolor=#ffcc66 style=\" border:thin solid 1px #000000\" cellpadding=0 cellspacing=0><tr class=tboard bgcolor=#006599 height=22><td colspan=2 align=center><font color=white> Форма заказа обратного звонка</font></td><td width=1% valign=top align=right><a href=# onclick=\"CloseFormCall();\"><font color=white>[x]</font></a></td></tr><tr class=tboard><td colspan=3> Для заказа обратного звонка введите свои имя и номер телефона и с Вами свяжется по телефону наш сотрудник.<br>&nbsp;</td></tr>";
	textone += "<tr class=tboard height=20><td>Ваше имя:</td><td colspan=2><input class=edit id=callfio type=text name=callfio value='' size=40></td></tr>";
	textone += "<tr class=tboard height=20><td>Телефон:</td><td colspan=2><input class=edit id=callphone type=text name=callphone value='' size=15></td></tr>";
	textone += "<tr class=tboard align=center height=20><td align=center colspan=3><input class=edit type=button value='Заказать обратный звонок' onclick=\"AddMakeCall();\"></td></tr>";
	myDiv.innerHTML = textone;
}

function CloseFormCall() {

	myDiv = document.getElementById("showformcall");
	myDiv.innerHTML = '';
}

function AddMakeCall() {

	if (!document.getElementById("callfio").value || document.getElementById("callfio").value.length <3)
		return alert('Вы не указали имя');
	var callfio = document.getElementById("callfio").value;	
	if (document.getElementById("callphone").value) {
	
		var callphone = document.getElementById("callphone").value;
		
		tmponephone = callphone.split("");
		var numphone = 0;
		for (i=0;i<tmponephone.length;i++) {
		
			if (tmponephone[i]=='0' || tmponephone[i]=='1' || tmponephone[i]=='2' || tmponephone[i]=='3' || tmponephone[i]=='4' || tmponephone[i]=='5' || tmponephone[i]=='6' || tmponephone[i]=='7' || tmponephone[i]=='8' || tmponephone[i]=='9')
				numphone++;
		}
		if (numphone<10)
			return alert('Укажите номер телефона в полном формате!');
			
		document.getElementById("showcall").src = '<? echo $in;?>/addcall.php?callfio='+encodeURIComponent(callfio)+'&callphone='+encodeURIComponent(callphone);
		divstr = "showcalllink";
		myDiv2 = document.getElementById(divstr);
		var posX = getPosXx(myDiv2);
		var posY = getPosYy(myDiv2);
		
		myDiv = document.getElementById("showformcall");
		myDiv.style.position = "absolute";
		myDiv.style.left = posX -100;
		myDiv.style.top = posY-170;
		var textone = "<table width=450 bgcolor=#ffcc66 style=\" border:thin solid 1px #000000\" cellpadding=0 cellspacing=0><tr class=tboard bgcolor=#006599 height=22><td colspan=2 align=center><font color=white> Форма заказа обратного звонка</font></td><td width=1% valign=top align=right><a href=# onclick=\"CloseFormCall();\"><font color=white>[x]</font></a></td></tr><tr class=tboard><td colspan=3> Обратный звонок Вами заказан. Вам перезвонит наш сотрудник.<br>&nbsp;</td></tr>";
		textone += "<tr class=tboard align=center height=20><td align=center colspan=3><input class=edit type=button value='Закрыть' onclick=\"CloseFormCall();\"></td></tr>";
		myDiv.innerHTML = textone;
	}
	else
		alert('Вы не указали номер телефона');
}

function CloseShowOcenka() {

	myDiv = document.getElementById("showocenka");
	myDiv.innerHTML = '';
}
var StatusSearchForm = "";

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
	else if (StatusSearchForm == "price")
	{
		SearchByPriceClose();
	}
	else if (StatusSearchForm == "condition")
	{
		SearchByConditionClose();
	}
	
	StatusSearchForm = '';		
}

function SearchByCondition ()
{
	SearchFormClose();
	var ConditionSearchDiv = '';
	var str = '';
	<? echo JavaString($DivConditionStr); ?>
	ConditionSearchDiv = str;
	var str = '';
	
	mytablesearch = document.getElementById("tablesearch");
	searchtdcondition = document.getElementById("SearchTdCondition");
	
	searchbycondition = document.getElementById("SearchByCondition");
	searchbycondition.style.position = "absolute";
	searchbycondition.innerHTML = ConditionSearchDiv;
	searchbycondition.style.left = mytablesearch.offsetLeft + searchtdcondition.offsetLeft + 170;
	searchbycondition.style.top = mytablesearch.offsetTop+searchtdcondition.offsetTop + (searchbycondition.offsetHeight/2) + 5;
	StatusSearchForm = 'condition';
}

function SearchByConditionClose()
{
	searchbycondition = document.getElementById("SearchByCondition");
	searchbycondition.innerHTML = '';
}

function SearchByTheme ()
{
	SearchFormClose();
	
	var ThemeSearchDiv = '';
	var str = '';
	<? echo JavaString($DivThemeStr); ?>
	ThemeSearchDiv = str;
	var str = '';
	
	mytablesearch = document.getElementById("tablesearch");
	searchtdtheme = document.getElementById("SearchTdTheme");
	
	searchbytheme = document.getElementById("SearchByTheme");
	searchbytheme.style.position = "absolute";
	searchbytheme.innerHTML = ThemeSearchDiv;
	searchbytheme.style.left = mytablesearch.offsetLeft + searchtdtheme.offsetLeft + 170;
	searchbytheme.style.top = mytablesearch.offsetTop+searchtdtheme.offsetTop + (searchbytheme.offsetHeight/2) + 5;
	StatusSearchForm = 'theme';
}

function SearchByThemeClose()
{
	searchbytheme = document.getElementById("SearchByTheme");
	searchbytheme.innerHTML = '';
}

function SearchByPrice ()
{
	SearchFormClose();
	
	var PriceSearchDiv = '';
	var str = '';
	<? echo JavaString($DivPriceStr); ?>
	PriceSearchDiv = str;
	var str = '';
	
	mytablesearch = document.getElementById("tablesearch");
	searchtdprice = document.getElementById("SearchTdPrice");
	
	searchbyprice = document.getElementById("SearchByPrice");
	searchbyprice.style.position = "absolute";
	searchbyprice.innerHTML = PriceSearchDiv;
	searchbyprice.style.left = mytablesearch.offsetLeft + searchtdprice.offsetLeft + 170;
	searchbyprice.style.top = mytablesearch.offsetTop+searchtdprice.offsetTop + (searchbyprice.offsetHeight/2) + 5;
	StatusSearchForm = 'price';
}

function SearchByPriceClose()
{
	searchbyprice = document.getElementById("SearchByPrice");
	searchbyprice.innerHTML = '';
}

function SearchByYear ()
{
	SearchFormClose();
	var YearSearchDiv = '';
	var str = '';
	<? echo JavaString($DivYearStr); ?>
	YearSearchDiv = str;
	var str = '';
	
	mytablesearch = document.getElementById("tablesearch");
	searchtdyear = document.getElementById("SearchTdYear");
	
	searchbyyear = document.getElementById("SearchByYear");
	searchbyyear.style.position = "absolute";
	searchbyyear.innerHTML = YearSearchDiv;
	searchbyyear.style.left = mytablesearch.offsetLeft + searchtdyear.offsetLeft + 170;
	searchbyyear.style.top = mytablesearch.offsetTop+searchtdyear.offsetTop + (searchbyyear.offsetHeight/2) + 5;
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
	<? echo JavaString($DivMetalStr); ?>
	MetalSearchDiv = str;
	var str = '';
	
	mytablesearch = document.getElementById("tablesearch");
	searchtdmetal = document.getElementById("SearchTdMetal");
	
	searchbymetal = document.getElementById("SearchByMetal");
	searchbymetal.style.position = "absolute";
	searchbymetal.innerHTML = MetalSearchDiv;
	searchbymetal.style.left = mytablesearch.offsetLeft + searchtdmetal.offsetLeft + 170;
	searchbymetal.style.top = mytablesearch.offsetTop+searchtdmetal.offsetTop + (searchbymetal.offsetHeight/2) + 5;
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
	<? echo JavaString($DivDetailsStr); ?>
	DetailsSearchDiv = str;
	var str = '';
	
	
	mytablesearch = document.getElementById("tablesearch");
	searchtddetails = document.getElementById("SearchTdDetails");
	
	searchbydetails = document.getElementById("SearchByDetails");
	searchbydetails.style.position = "absolute";
	searchbydetails.innerHTML = DetailsSearchDiv;
	searchbydetails.style.left = mytablesearch.offsetLeft + searchtddetails.offsetLeft + 170 + <?=$topleft?>;
	searchbydetails.style.top = mytablesearch.offsetTop+searchtddetails.offsetTop + (searchbydetails.offsetHeight/2) + 5;
	StatusSearchForm = 'details';
}

function SearchByDetailsClose()
{
	searchbytheme = document.getElementById("SearchByDetails");
	searchbydetails.innerHTML = '';
}