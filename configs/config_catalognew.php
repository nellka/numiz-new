<?

$myip = "78.107.251.19";
//выбор тематик
$ThemeArray = Array (
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

$MaterialTypeArray = Array();

$MaterialTypeArray[1] = "Монеты";
$MaterialTypeArray[2] = "Боны";
$MaterialTypeArray[3] = "Аксессуары";
$MaterialTypeArray[4] = "Подарочные наборы <font color=red>NEW</font>";

$ConditionArray = Array();
$ConditionArray[6] = "F";
$ConditionArray[5] = "VF";
$ConditionArray[4] = "XF";
$ConditionArray[1] = "UNC";
$ConditionArray[2] = "BUNC";
$ConditionArray[3] = "Proof";*/

$ConditionMintArray = Array();
$ConditionMintArray[1] = "UNC";
$ConditionMintArray[2] = "ProofLike";
$ConditionMintArray[3] = "Proof";

$ReviewTypeArray = Array();
$ReviewTypeArray[1] = "Куплю";
$ReviewTypeArray[2] = "Продам";
$ReviewTypeArray[3] = "Обменяю";
$ReviewTypeArray[4] = "Другое";

/*
//бандероль=======================================================================================
$WrapperWeight[0] = 500;
$WrapperWeight[1] = 1000;
$WrapperWeight[2] = 1500;
$WrapperWeight[3] = 2000;
$WrapperWeight[4] = 2500;

$WrapperWeightName[0] = "500 грамм";
$WrapperWeightName[1] = "от 500 грамм до 1 кг.";
$WrapperWeightName[2] = "от 1 кг. до 1,5 кг.";
$WrapperWeightName[3] = "от 1,5 кг. до 2 кг.";
$WrapperWeightName[4] = "от 2 кг. до 2,5 кг.";


$WrapperZone[0] = 1;
$WrapperZone[1] = 2;
$WrapperZone[2] = 3;
$WrapperZone[3] = 4;
$WrapperZone[4] = 5;

//[Weight][Zone]
$Wrapper[0][0] = 14.45;
$Wrapper[1][0] = 28.9;
$Wrapper[2][0] = 43.35;
$Wrapper[3][0] = 57.8;
$Wrapper[4][0] = 72.25;

$Wrapper[0][1] = 18.4;
$Wrapper[1][1] = 36.8;
$Wrapper[2][1] = 55.2;
$Wrapper[3][1] = 73.6;
$Wrapper[4][1] = 92;

$Wrapper[0][2] = 20.7;
$Wrapper[1][2] = 41.4;
$Wrapper[2][2] = 62.1;
$Wrapper[3][2] = 82.8;
$Wrapper[4][2] = 103.5;

$Wrapper[0][3] = 24.15;
$Wrapper[1][3] = 48.3;
$Wrapper[2][3] = 72.45;
$Wrapper[3][3] = 96.6;
$Wrapper[4][3] = 120.75;

$Wrapper[0][4] = 26.6;
$Wrapper[1][4] = 53.2;
$Wrapper[2][4] = 79.8;
$Wrapper[3][4] = 106.4;
$Wrapper[4][4] = 133;

//посылка ========================================================================================
$PackageZone[0] = 1;
$PackageZone[1] = 2;
$PackageZone[2] = 3;
$PackageZone[3] = 4;
$PackageZone[4] = 5;

$Package[0][0] = 49;
$Package[0][1] = 54.5;
$Package[0][2] = 71.5;
$Package[0][3] = 95.5;
$Package[0][4] = 107.7;

//за каждые 500 грамм
$PackageAddition[0] = 4;
$PackageAddition[1] = 4.3;
$PackageAddition[2] = 5.4;
$PackageAddition[3] = 6.9;
$PackageAddition[4] = 7.6;

$WeightCoins = 8;

$SumName[1] = "Наложенный платеж - 7%";
$SumName[2] = "Наличные - 0%";
$SumName[3] = "WebMoney - 0,8%";
$SumName[4] = "YandexMoney - 1%";
//$SumName[5] = "Сбербанк - 2,5% (мин. 30 руб.)";
$SumName[5] = "Коммерческий Банк - 1% (мин. 30 руб.)";
$SumName[6] = "Rapida - 1,5% (мин. 7 руб.) - удобно";


$SumProperties[1] = "<b>Наложенный платеж <font color=red>7%</font> <img src=http://".$SERVER_NAME."/shopcoins/payimage/post.gif border=1></b> 
<br>Вы оплачиваете заказ на почте при его получении. Кроме того, почта возьмет с Вас 4-7% от суммы наложенного платежа за услуги по пересылке денег нам.";
$SumProperties[2] = "<b>Наличный расчет <font color=red>(бесплатно)</font></b>
<br>По <b><font color=red>Москве доставка производиться бесплатно</font></b>, но при условии, что время встречи с 18-30 до 20-00 в 
будние дни на кольцевой станции метрополитена. (Господа, не забывайте, что Вас много - я один). 
<br>Также условием является то, что встречи происходят без опозданий с Вашей стороны. 
(10 минут считается опозданием).
<br>Как меня узнать - смотрите <a href=http://www.numizmatik.ru/shopcoins/me.php>фото</a>...";
$SumProperties[3] = "<b>WebMoney <font color=red>0,8%</font> <img src=http://".$SERVER_NAME."/shopcoins/payimage/webmoney.gif border=1></b>
<br>Вся информация доступна по адресу <a href=http://www.webmoney.ru target=_blank>http://www.WebMoney.ru</a>
<br>Номер счета в рублях: R576689304959
<br>Номер счета в USD: Z570568313069
<br>Номер счета в EURO: E320477577247
<br>ФИО: Мандра Богдан Михайлович
<br>WMID: 455446320323
<br>Псевдоним: Numizmatik.Ru
<br>Передача денег должна происходить без протекции.
<br><font color=red><b>Важно!!!</b></font> В описании нужно указать номер заказа и Ваше ФИО под которым Вы делали заказ
<br><b>Внимание: </b> Для ускорения процесса выполнение заказа, после перевода денег, просьба направить письмо на адрес  <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в простой форме.";
$SumProperties[4] = "<b>YandexMoney <font color=red>1%</font> <img src=http://".$SERVER_NAME."/shopcoins/payimage/yandexmoney.gif border=1></b>
<br>Вся информация доступна по адресу <a href=http://money.yandex.ru target=_blank>http://money.yandex.ru</a>
<br>Номер счета: 4100125001255
<br>ФИО: Мандра Богдан Михайлович
<br><b>Внимание: </b> Для ускорения процесса выполнение заказа, после перевода денег, просьба направить письмо на адрес  <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в простой форме.";
/*$SumProperties[5] = "<b>Сбербанк <font color=red>2,5% (минимум 30 руб.)</font> <img src=http://".$SERVER_NAME."/shopcoins/payimage/sbrf.gif border=1></b>
<br>Донское отделение 7813/07813 (операционный сектор)
<br>Счет: 303-018-105-380-006-03811 в Сбербанке России г. Москвы
<br>БИК: 0044525225
<br>Кор/сч.: 301-018-104-000-000-00225
<br>ИНН 7707083893
<br>КПП 774401001
<br>КОД ОКПО: 00032537
<br>НАШ СЧЕТ: 42607.810.8.3811.0000055
<br>ФИО: Мандра Богдан Михайлович
<br><b>Внимание: </b> Для ускорения процесса выполнение заказа, после перевода денег, просьба направить письмо на адрес  <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в простой форме. Если есть возможность - скан квитанции ";*/
/*$SumProperties[5] = "<b>Любой коммерческий банк <font color=red>~1% (минимум 30 руб.)</font> <img src=http://".$SERVER_NAME."/shopcoins/payimage/alfabank.gif border=1></b>
<br>Наименование предприятия: ОАО «Альфа-Банк»
<br>Юридический адрес: ОАО «Альфа-Банк», ул. Каланчевская, д. 27, г. Москва, 107078
<br>Почтовый адрес: ОАО «Альфа-Банк», ул. Маши Порываевой, 9, г. Москва, 107078
<br>ИНН: 7728168971
<br>Кор/сч.: 30101-810-2-0000-0000593 в ОПЕРУ Московского ГТУ ЦБ РФ
<br>БИК: 044525593
<br>Код ОКПО: 09610444
<br>НАШ СЧЕТ: 426-018-105-046-000-00217
<br>ФИО: Мандра Богдан Михайлович
<br><font color=red><b>Важно!!!</b></font> В описании нужно указать номер заказа и Ваше ФИО под которым Вы делали заказ
<br><b>Внимание: </b> Для ускорения процесса выполнение заказа, после перевода денег, просьба направить письмо на адрес  <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в простой форме. Если есть возможность - скан квитанции ";
$SumProperties[6] = "<b>2. Систему Рапида - выгодно и удобно <font color=red>1,5%</font> <img src=http://".$SERVER_NAME."/shopcoins/payimage/rapida.gif border=1></b> 
<br>Для зачисления денег нужно распечатать изображение бланка электронного перевода и 
оплатить его в любом отделении почтовой связи России, подключенном к Единой Системе Почтовых 
Переводов (ЕСПП). Почтовыми отделениями взимается комиссия в размере 1,5% 
(но не менее 7 рублей) от суммы перевода. Приблизительно 90% почтовых отделений подключены к 
Единой Системе Почтовых Переводов. 
<br><a href=http://www.russianpost.ru/resp_engine.asp?Path=RU/Home/Services/rpprojects/cyberdengi/espp target=_blank>Список почтовых отделений, подключенных к ЕСПП</a>
<br>Номер в системе Rapida (UserID): 631528
<br><b>Внимание: </b> Для ускорения процесса выполнение заказа, после перевода денег, просьба направить письмо на адрес  <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в простой форме. Если есть возможность - скан квитанции 
";

//выбор тематик
$ThemeArray = Array (
13	=> 	"Авиация",
4	=> 	"Бракосочетание, коронация",
16	=> 	"Выдающиеся личности",
6	=> 	"География",
23	=> 	"Евросоюз",
18	=> 	"Знаки зодиака",
5	=> 	"История",
9	=> 	"Корабли",
3	=> 	"Королева-мать",
15	=> 	"Космос",
24	=> 	"Миллениум",
10	=> 	"Неправильная форма",
1	=> 	"Обращение",
20	=> 	"Олимпийские игры",
25	=> 	"ООН",
7	=> 	"Памятники архитектуры",
11	=> 	"Позолота",
14	=> 	"Редкости на рынке",
2	=> 	"Сохранение животного мира",
17	=> 	"Транспорт",
8	=> 	"ФАО",
19	=> 	"Флора",
21	=> 	"Футбол",
22	=> 	"Хокей",
12	=> 	"Цветные"
);*/

$yearsArray = Array (
		1 => array('name' => '2001-настоящее время','data'=>array(2001,(integer)date('Y',time()))), 
		2 => array('name' => '1901-2000','data'=>array(1901,2000)),
		3 => array('name' => '1801-1900','data'=>array(1801,1900)),
		4 => array('name' => '1701-1800','data'=>array(1701,1800)),
		5 => array('name' => '1601-1700','data'=>array(1601,1700)),
		6 => array('name' => 'до 1600','data'=>array(0,1600)),
		7 => array('name' => 'Без года','data'=>array(0,0)));

?>