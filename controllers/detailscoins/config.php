<?php

$ArrayAuctionPaymentSend = array ( 1 => "Покупатель", 2 => "Продавец");
$ArrayUsers = array(811,4853, 90126, 227250,266539,272362,269041,285589,284640,329697,316481,316573, 310477, 342907, 301896, 344450, 324519, 18477);
$ArrayTypeWork = array (

1=>"Описание монет",
2=>"Связки монет",
3=>"Ответ на форуме",
4=>"Новости сайта",
5=>"Редактирование каталога",
6=>"Новые добавленные",
7=>"Были изменения",
8=>"Подарочные наборы/Наборы"
);

$ArrayPriceWork = array (

1=>2,
2=>0.5,
3=>5,
4=>15,
5=>0.5,
6=>0.5,
7=>0.5,
8=>10
);

$ThemeArray = array (
13	=> 	"Авиация",
4	=> 	"Бракосочетание, коронация, портреты",
16	=> 	"Выдающиеся личности",
6	=> 	"География",
23	=> 	"Евросоюз",
18	=> 	"Знаки зодиака",
5	=> 	"История",
9	=> 	"Корабли, лодки",
3	=> 	"Королева-мать",
15	=> 	"Космос",
24	=> 	"Миллениум",
10	=> 	"Неправильная форма",
1	=> 	"Обращение",
20	=> 	"Олимпийские игры, спорт",
25	=> 	"ООН",
7	=> 	"Памятники архитектуры",
11	=> 	"Позолота",
14	=> 	"Редкости на рынке",
2	=> 	"Сохранение животного мира",
17	=> 	"Транспорт",
8	=> 	"ФАО",
19	=> 	"Флора",
21	=> 	"Футбол",
22	=> 	"Хоккей",
12	=> 	"Цветные"
); 
/*
$MetalArray = array (

	1=>"Алюминий",
    2=>"Биллон", 
    3=>"Биметалл", 
    4=>"Железо", 
    5=>"Золото",
    6=>"Керамика", 
    7=>"Медно-никель", 
    8=>"Медь",
    9=>"Позолота", 
    10=>"Посеребрение", 
    11=>"Серебро",
    12=>"Титан",
    13=>"Фарфор",
    14=>"Цинк",
	16 =>"Латунь"
);*/

$MetalArray = array (

	7=>"Алюминий",
    101=>"Биллон", 
    2=>"Биметалл", 
    12=>"Железо", 
    11=>"Золото",
    16=>"Керамика", 
    10 =>"Латунь",
    3=>"Медно-никель", 
    5=>"Медь",
    18=>"Позолота", 
    6=>"Посеребрение", 
    1=>"Серебро",
    13=>"Титан",
    9=>"Фарфор",
    8=>"Цинк",

);

$ConditionArray = array (

	1=>"VF",
    2=>"XF", 
    3=>"UNC-", 
    4=>"UNC", 
    5=>"Proof-",
    6=>"Proof"
);

$ArrayBonus = array (

	1 => 1500,
	2 => 1000,
	3 => 500

);

$ArrayForCode = array (

	0=>"0",
	1=>"1",
	2=>"2",
	3=>"3",
	4=>"4",
	5=>"5",
	6=>"6",
	7=>"7",
	8=>"8",
	9=>"9",
	10=>"a",
	11=>"b",
	12=>"c",
	13=>"d",
	14=>"e",
	15=>"f",
	16=>"g",
	17=>"h",
	18=>"j",
	19=>"k",
	20=>"l",
	21=>"m",
	22=>"n",
	23=>"o",
	24=>"p",
	25=>"q",
	26=>"r",
	27=>"v",
	28=>"u",
	29=>"w",
	30=>"i",
	31=>"x",
	32=>"y",
	33=>"z"

);

$reserve = 15*60;
$maxpage = 150;
$datestartcomment = mktime(0,0,1,6,28,2010)+floor( (time()-mktime(0,0,1,6,28,2010)) /(7*24*60*60) )*7*24*60*60;

?>