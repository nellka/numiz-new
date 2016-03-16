<?
$bigsumcoupon = 15000;
$bigsumcoupondis = 5;
$smallsumcoupon = 2000;
$smallsumcoupondis = 3;

//обязательно добавить в config на сервере
$MetroArray = Array (
				1 => "Новослободская", 
				2 => "Белорусская", 
				3 => "Краснопресненская", 
				4 => "Киевская", 
				5 => "Парк Культуры", 
				6 => "Октябрьская", 
				7 => "Добрынинская", 
				8 => "Павелецкая", 
				9 => "Таганская", 
				10 => "Курская", 
				11 => "Комсомольская", 
				12 => "Проспект Мира",
	);
	
	
$metallArray = array(1 => 'Алюминий',
				2 => 'Биллон',
				3 => 'Биметалл',
				4 => 'Железо',
				5 => 'Золото',
				6 => 'Керамика',
				7 => 'Медно-никель',
				8 => 'Медь',
				9 => 'Позолота',
				10 => 'Посеребрение',
				11 => 'Серебро',
				12 => 'Титан',
				13 => 'Фарфор',
				14 => 'Цинк',
				15 => 'Неопределено');
$conditionArray = array(1 => 'VF',
				2 => 'XF',
				3 => 'UNC',
				4 => 'UNC',
				5 => 'Proof-',
				6 => 'Proof');
$yearsArray = Array (
		1 => array('name' => '2001-настоящее время','data'=>array(2001,(integer)date('Y',time()))), 
		2 => array('name' => '1901-2000','data'=>array(1901,2000)),
		3 => array('name' => '1801-1900','data'=>array(1801,1900)),
		4 => array('name' => '1701-1800','data'=>array(1701,1800)),
		5 => array('name' => 'до 1600','data'=>array(0,1600)),
		6 => array('name' => 'Без указания года','data'=>array(0,0)));

$arraynewcoins = Array(1=>date('Y')-2,2=>date('Y')-1,3=>date('Y'));							    
$TimeMetroMeeting = Array (
				64800 => '18-00', 
				65100 => '18-05', 
				65400 => '18-10', 
				65700 => '18-15', 
				66000 => '18-20', 
				66300 => '18-25', 
				66600 => '18-30', 
				66900 => '18-35', 
				67200 => '18-40', 
				67500 => '18-45', 
				67800 => '18-50', 
				68100 => '18-55', 
				68400 => '19-00', 
				68700 => '19-05', 
				69000 => '19-10', 
				69300 => '19-15', 
				69600 => '19-20', 
				69900 => '19-25', 
				70200 => '19-30', 
				70500 => '19-35', 
				70800 => '19-40', 
				71100 => '19-45', 
				71400 => '19-50', 
				71700 => '19-55', 
				72000 => '20-00'
				);
				
$DaysArray = Array(
				0 => "Вс",
				1 => "Пн",
				2 => "Вт",
				3 => "Ср",
				4 => "Чт",
				5 => "Пт",
				6 => "Сб"
				);

$PaymentArray = Array (
				1 => 'Наложенный платеж',
				2 => 'Личная встреча'
				);
//выбор тематик
$ThemeArray = Array (
13	=> 	"Авиация",
4	=> 	"Бракосочетание, коронация",
16	=> 	"Выдающиеся личности",
6	=> 	"География",
23	=> 	"Евросоюз",
2	=> 	"Животные",
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
17	=> 	"Транспорт",
8	=> 	"ФАО",
19	=> 	"Флора",
21	=> 	"Футбол",
22	=> 	"Хоккей",
12	=> 	"Цветные"
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


$MaterialTypeArray = Array();

$MaterialTypeArray[1] = "Монеты"; 											
$MaterialTypeArraySort[1] = 1;			
$MaterialTypeArrayTitle[1] = "Монеты со всего мира стоимость(цены)";

$MaterialTypeArray[5] = "Книги"; 											
$MaterialTypeArraySort[5] = 1;			
$MaterialTypeArrayTitle[5] = "Книги о монетах";

$MaterialTypeArray[6] = "Цветные монеты"; 											
$MaterialTypeArraySort[6] = 6;			
$MaterialTypeArrayTitle[6] = "Цветные монеты";

$MaterialTypeArray[12] = "Монеты СССР";										
$MaterialTypeArraySort[2] = 12;			
$MaterialTypeArrayTitle[12] = "Дешевые монеты СССР (цены и стоимость покупки и продажи)";

$MaterialTypeArray[8] = "Мелочь ";											
$MaterialTypeArraySort[3] = 8;			$MaterialTypeArrayTitle[8] = "Дешевые монеты со всего мира стоимость(цены)";
$MaterialTypeArray[10] = "Нотгельды ";										
$MaterialTypeArraySort[4] = 10;			
$MaterialTypeArrayTitle[10] = "Нотгельды со всего мира стоимость(цены)";
$MaterialTypeArray[7] = "Наборы монет";										
$MaterialTypeArraySort[5] = 7;			
$MaterialTypeArrayTitle[7] = "Наборы монет со всего мира стоимость(цены)";

$MaterialTypeArray[11] = "Барахолка монет <font color=red>NEW</font>";									
$MaterialTypeArraySort[7] = 11;			$MaterialTypeArrayTitle[11] = "Барахолка монет со всего мира стоимость(цены)";
$MaterialTypeArray[2] = "Боны";												
$MaterialTypeArraySort[8] = 2;			

$MaterialTypeArrayTitle[2] = "Банкноты(боны) со всего мира стоимость(цены)";
$MaterialTypeArray[3] = "Аксессуары";										
$MaterialTypeArraySort[9] = 3;			
$MaterialTypeArrayTitle[3] = "Аксессуары для нумизмата стоимость(цены)";

$MaterialTypeArray[4] = "Подарочные наборы";								
$MaterialTypeArraySort[10] = 4;			
$MaterialTypeArrayTitle[4] = "Подарочные наборы монет со всего мира стоимость(цены)";
//[Weight][Zone]
/*
$PostZone[1] = 138.80;
$PostZone[2] = 140.70;
$PostZone[3] = 146.40;
$PostZone[4] = 178.30;
$PostZone[5] = 199.00;*/

//за каждые 500 грамм
/*
$PackageAddition[1] = 12.00;
$PackageAddition[2] = 13.90;
$PackageAddition[3] = 20.30;
$PackageAddition[4] = 29.20;
$PackageAddition[5] = 33.70;*/

//а теперь бандероли и почта идет нах
$PostZone1[1] = 138.80;
$PostZone1[2] = 140.70;
$PostZone1[3] = 146.40;
$PostZone1[4] = 178.30;
$PostZone1[5] = 199.00;
//суток на доставку
$PostZone2[1] = 12;
$PostZone2[2] = 13.90;
$PostZone2[3] = 20.3;
$PostZone2[4] = 29.2;
$PostZone2[5] = 33.7;


$WeightCoins = 5;
$WeightPostLatter = 28;
$WeightPostBox = 100;
$PriceLatter = 16;

$DeliveryName[2] = 'В офисе м.Тверская';
$DeliveryName[1] = 'Встречи на кольцевых';
$DeliveryName[3] = 'Доставка в пределах МКАД';
$DeliveryName[4] = 'Отправка по почте';
//$DeliveryName[5] = 'Экспресс доставка (СПСР)';
$DeliveryName[6] = 'Экспресс доставка (ЕМС)';
//$DeliveryName[7] = 'Метро Новослободская (самовывоз)';

$myip = "83.167.125.158";
$minpriceoneclick = 500;
$reservetime = 18000;
$mintime = 1800;
$stopsummax = 3000000;

//$dateinsert_orderby = "dateinsert_orderby";
$dateinsert_orderby = "dateinsert";

//$myip = "83.167.125.158";
$minpriceoneclick = 500;
$reservetime = 18000;
$mintime = 1800;
$stopsummax = 3000000;

//за каждые 500 грамм
$PackageAddition[1] = 14.50;
$PackageAddition[2] = 17;
$PackageAddition[3] = 24;
$PackageAddition[4] = 35;
$PackageAddition[5] = 30;

//а теперь бандероли и почта идет нах
$PostZone1[1] = 150;
$PostZone1[2] = 168;
$PostZone1[3] = 174;
$PostZone1[4] = 212;
$PostZone1[5] = 237;
//суток на доставку
$PostZone2[1] = 14.5;
$PostZone2[2] = 17;
$PostZone2[3] = 24;
$PostZone2[4] = 35;
$PostZone2[5] = 50;


$WeightCoins = 5;
$WeightPostLatter = 28;
$WeightPostBox = 100;
$PriceLatter = 16;


$robokassapasword1 = 'dfsh76erthkl7kps83md';
$robokassapasword2 = 'kjf893bdhjske3ldor83';
$urlrobokassa = "https://merchant.roboxchange.com";
$krobokassa = 1.04;
$ipmyshop = "212.233.78.26";


$DeliveryProperties[1] = '<ul>
<li>Доставка осуществляется <font color=red><b>бесплатно</b></font></li>
<li>Место встречи: <font color=red><b>кольцевые станции</b></font> метрополитена</li>
<li>Время встречи: <font color=red><b>с 18-00 до 20-00 в будние дни</b></font> 2-3 раза в неделю</li>
<li>Встречи происходят <font color=red><b>без опозданий</b></font> с Вашей стороны (<font color=red><b>4 минуты считается опозданием</b></font>)</li>
<li>Встреча происходит <font color=red><b>только после того</b></font>, как с администратором по телефону обговорены все детали</li>
<li>Обзвон клиентов производится с 12.00 до 15.00 в указанный Вами день при оформлении заказа, если звонка не поступило - просьба связаться с администратором по телефону 8-800-333-14-77 (бесплатный звонок по России) до 16.00</li>
<li>Сумма заказа должна быть не менее <font color=red><b>500 руб.</b></font></li>
<li>Вес заказа не более 5 кг., при весе более 5 кг. доставка расчитывается как в пределах станций кольцевой линии Метрополитена «внутри кольца» за каждые неполные 5 кг.</li>
<li><b>По телефону в день встречи с Вами свяжется наш сотрудник, который опишет себя и место встречи</b></li></ul>';

$DeliveryProperties[3] = '<ul><li>Сумма заказа должна быть <font color=red><b>не менее 1 000 руб.</b></font></li>
<li>Только в пределах МКАД</li>
<li>Доставка в офис/на дом в пределах станций кольцевой линии Метрополитена «внутри кольца» – 200 руб. </li>
<ul><li>+ стоимость проезда от ближайшей станции метро в оба конца, если расстояние от метро до вас более 1 км. </li></ul>
<li>Доставка в офис/на дом по всем остальным районам г. Москвы – 300-500 руб. в зависимости от расстояния, объема заказа, загруженности работников офиса и пр. </li>
<ul><li>+ стоимость проезда от ближайшей станции метро в оба конца, если расстояние от метро до вас более 1 км. </li></ul>
<li><strong>Доставка в офис/на дом в пределах МКАД на сумму более 15000 рублей - бесплатно! </strong></li>
<li><b><font color=red>Внимание! Администрация сайта самостоятельно определяет тарифы на доставку заказа в каждом отдельном случае! </font></b></li>';
$DeliveryProperties[4] = '<ul><li>Доставка почтой осуществляется <font color=red><b>только по территории РФ</b></font> </li>
<li>Сумма заказа должна быть <font color=red><b>не менее 500 руб. и не более 40 000 руб.</b></font> </li>
<li>Заказы отправляються с города Долгопрудного</font> </li>
<li>К стоимости Вашего заказа будет добавлена стоимость почтовых услуг по упаковке, страховке и доставке его Вам, которая зависит от пункта назначения, массы и стоимости товара. Почтовые услуги ~ от 5 до 10 %</li></ul>';
$DeliveryProperties[5] = '<ul><li>Доставка осуществляется <font color=red><b>только по территории РФ</b></font></li>
<li>Сумма заказа должна быть <font color=red><b>не менее 3000 руб. и не более 40 000 руб.</b></font></li>
<li>К стоимости Вашего заказа будет добавлена стоимость услуг по упаковке, страховке и доставке его Вам, через компаню экспресс-доставки СПСР. Идеально подходит для заказов на сумму от 10000 руб.</li>
<li>Стоимость расчета можно узнать на сайте <a href=http://www.cpcr.ru/ target=_blank>http://www.cpcr.ru/</a> в разделе Тарифный калькулятор</li>
</ul>';
$DeliveryProperties[6] = '<ul><li>Доставка осуществляется <font color=red><b>только по территории РФ</b></font></li>
<li>Сумма заказа должна быть <font color=red><b>не менее 3000 руб. и не более 40 000 руб.</b></font></li>
<li>К стоимости Вашего заказа будет добавлена стоимость услуг по упаковке, страховке и доставке его Вам, через службу Почты России ЕМС. Идеально подходит для заказов на сумму от 10000 руб.</li>
<li>Стоимость расчета можно узнать на сайте <a href=http://www.emspost.ru/ target=_blank>http://www.emspost.ru/</a> в разделе Тарифы и сроки</li>
</ul>';

$DeliveryProperties[7] = '<ul><li>Метро Тверская (самовывоз)</li>
<li>Сумма заказа должна быть <font color=red><b>не менее 500 руб. </b></font></li>
<li>Время встречи нужно заранее согласовать по нашим контактным телефонам.</li>
<li>Смотрите <a href=http://www.numizmatik.ru/shopcoins/map.gif target=_blank>схему прохода от м.Тверская</a></li>
</ul>';


$SumName[1] = "Наложенный платеж - 7%";
$SumName[2] = "Наличные - 0%";
$SumName[3] = "WebMoney - 0,8%";
$SumName[4] = "YandexMoney - 1%";
//$SumName[5] = "Сбербанк - 2,5% (мин. 30 руб.)";
$SumName[5] = "Коммерческий Банк - 1% (ПЛАТЕЖИ БОЛЬШЕ НЕ ПРИНИМАЮТСЯ. СЧЕТ ЗАКРЫТ)";
//$SumName[7] = "Платежная система Contact";
//$SumName[6] = "Rapida - 1,5% (мин. 7 руб.) - удобно";
$SumName[6] = "Сбербанк - экономия времени и стоимости";
$SumName[8] = "Кредитные карты VISA,MasterCard - 4%";

$SumProperties[1] = "<b>Наложенный платеж <font color=red>7%</font> <img src=http://".$_SERVER['HTTP_HOST']."/shopcoins/payimage/post.gif border=1></b>
<br><b><font color=red>Заказы отправляются от имени Мандра Б. М. c Центра Магистральных Перевозок г.Москвы </font></b>
<br>Наложенный платеж на <b>банковские реквизиты</b>
<br>Индивидуальный Предприниматель Мандра Богдан Михайлович
<br>ИНН 504908219824
<br>ОГРН: 309504726100042
<br>Расчетный счет 40802810538050005372 в Московский банк ОАО Сбербанк России 3805/01702
<br>БИК 044525225 Номер кор./сч. банка получателя платежа 30101810400000000225
<br>

<br>Вы оплачиваете заказ на почте при его получении. Кроме того, почта возьмет с Вас 4-7% от суммы наложенного платежа за услуги по пересылке денег нам.";
$SumProperties[2] = "<b>Наличный расчет </b>
<br>По <b><font color=red>Москве доставка производится бесплатно</font></b>, но при условии, что время встречи с 18-30 до 20-00 в 
будние дни на кольцевой станции метрополитена.<br> (Господа, не забывайте, что Вас много). <br>
<br>Также условием является то, что встречи происходят без опозданий с Вашей стороны. 
(4 минуты считается опозданием).
<br><b>По телефону в день встречи с Вами свяжется наш сотрудник, который опишет себя и место встречи</b>...";
$SumProperties[3] = "<b>WebMoney <font color=red>0,8%</font> <img src=http://".$_SERVER['HTTP_HOST']."/shopcoins/payimage/webmoney.gif border=1></b>
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
$SumProperties[4] = "<b>YandexMoney <font color=red>1%</font> <img src=http://".$_SERVER['HTTP_HOST']."/shopcoins/payimage/yandexmoney.gif border=1></b>
<br>Вся информация доступна по адресу <a href=http://money.yandex.ru target=_blank>http://money.yandex.ru</a>
<br>Номер счета: 4100163425137
<br>ФИО: Мандра Богдан Михайлович
<br><b>Внимание: </b> Для ускорения процесса выполнение заказа, после перевода денег, просьба направить письмо на адрес  <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> с темой \"Оплата заказа | YandexMoney | Клуб Нумизмат\". В теле сообщения укажите номер заказа и сумму перевода. 
В случае отсутствия письма с информацией о переводе денег, <font color=red><b>возможны задержки с отправкой заказа</b></font>";
$SumProperties[5] = "<b>Коммерческий банк <font color=red>~1% (минимум 30 руб.)</font> <img src=http://".$_SERVER['HTTP_HOST']."/shopcoins/payimage/alfabank.gif border=1></b>
<br><b><font color=red>При переводе могут возникнуть проблемы с переводом на счет нерезидента РФ, сразу говорите это операционисту банка.</font></b>
<br>Наименование предприятия: ОАО «Альфа-Банк»
<br>Юридический адрес: ОАО «Альфа-Банк», ул. Каланчевская, д. 27, г. Москва, 107078
<br>Почтовый адрес: ОАО «Альфа-Банк», ул. Маши Порываевой, 9, г. Москва, 107078
<br>ИНН: 7728168971
<br>Кор/сч.: 30101-810-2-0000-0000593 в ОПЕРУ Московского ГТУ ЦБ РФ
<br>БИК: 044525593
<br>Код ОКПО: 09610444
<br>НАШ СЧЕТ: 426-018-105-046-000-00217
<br>ФИО: Мандра Богдан Михайлович
<br><font color=red><b>Важно!!!</b></font> В описании нужно указать ваше ФИО под которым Вы делали заказ.
<br>Для упрощения перевода денег - в теме перевода указать Дарение в устной форме нерезиденту РФ.
<br>Мы несем полную ответсвенность по доставке материала к вам. В случае пропажи заказа на стадии транспортировки, что бывает очень редко, - мы производим полную компенсацию оплаты по заказам. 
<br><b>Внимание: </b> Для ускорения процесса выполнение заказа, после перевода денег, просьба направить письмо на адрес  <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> с темой \"Оплата заказа | Коммерческий банк | Клуб Нумизмат\". В теле сообщения укажите номер заказа и сумму перевода. 
В случае отсутствия письма с информацией о переводе денег, <font color=red><b>возможны задержки с отправкой заказа</b></font>";

$SumProperties[6] = "!!! <a href=kak_oplatit_kartoi_sberbanka.html target=_blank>Как оплатить заказ картой Сбербанка</a> !!!
<br><b>Любой банк </b> на счет 
Индивидуального Предпринимателя Мандра Богдана Михайловича
<br>ИНН: 504908219824
<br>Расчетный счет 40802810538050005372 в Московский банк ОАО Сбербанк России 3805/01702
<br>БИК 044525225
<br>Номер кор./сч. банка получателя платежа 30101810400000000225
<br>Мы несем полную ответсвенность по доставке материала к вам. В случае пропажи заказа на стадии транспортировки, что бывает очень редко, - мы производим полную компенсацию оплаты по заказам. 
<br><b>Внимание: </b> Для ускорения процесса выполнение заказа, после перевода денег, просьба направить письмо на адрес  <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> с темой \"Оплата заказа | Сбербанк | Клуб Нумизмат\". В теле сообщения укажите номер заказа и сумму перевода. 
<br>В случае отсутствия письма с информацией о переводе денег, <font color=red><b>возможны задержки с отправкой заказа</b></font>
";
/*
$infotext = "
<table cellpadding=3 cellspacing=0 border=0 align=center>
<tr>
<td class=tboard><a href=how.php title='Как купить монеты, банкноты, аксессуры, книги в интернет-магазине монет'>Как заказать</a></td>
<td class=tboard> | </td>
<td class=tboard><a href=# onclick=javascript:window.open('settings.php','_settings','top=200,left=200,status=no,menubar=no,scrollbars=yes,resizable=no,width=300,height=200'); title='Настройки показа монет, банкнот, аксессуаров, книг и их стоимости(цены)'>Настройки</a></td>
<td class=tboard> | </td>
<td class=tboard><a href=delivery.php title='Способы оплаты и доставки монет, банкнот, аксессуаров, книг по нумизматике'>Оплата и доставка</a></td>
<td class=tboard> | </td>
<td class=tboard><a href=excel/ target=_blank title='Прайс-лист стоимости(цен) монет, банкнот, аксессуаров для коллекционеров'>Прайс-лист</a></td>
<td class=tboard>
<a href=excel/ target=_blank title='Прайс-лист стоимости(цен) монет, банкнот, аксессуаров для коллекционеров'><img src=".$cfg['site_dir']."images/excel.gif alt='Прайс-лист стоимости(цен) монет, банкнот, аксессуаров для коллекционеров' border=0></a></td>
<td class=tboard> | </td>
<td class=tboard><a href=order.php target=_blank title='Ваши заказы монет, банкнот, аксессуаров для коллекционеров в интернет-магазине монет'>Личный кабинет и Ваши заказы</a></td>
<td class=tboard> | </td>
<td class=tboard><a href=".$cfg['site_dir']."subscribe/index.php target=_blank title='Подписка на поступления монет, банкнот, аксессуаров, новости нумизматики, форум нумизматов'>Подписка <font color=red>NEW</font></a></td>
</tr>
</table>";

if ($page!="orderdetails" && $page != "submitorder" && $page!="order" && 1!=1)
	$infotext .= "<span class=txt><center><strong>Виды оплаты:</strong> <img src=".$cfg['site_dir']."images/mastercard1.gif border=0> <img src=".$cfg['site_dir']."images/wm1.gif border=0> <img src=".$cfg['site_dir']."images/po4ta1.jpg border=0> <img src=".$cfg['site_dir']."images/yandex-money1.jpg border=0> <img src=".$cfg['site_dir']."images/ccVISA1.gif border=0></center></span>";

    $infotext .= "<table border=0 cellpadding=5 cellspacing=1 style='border:thin solid 1px #FF0000' width=90% align=center>
<tr class=tboard>
<td bgcolor=#EBE4D4>

<b>Доставка в офис или на дом <font color=red>от 200 руб</font>. Подробнее см. раздел <a href=delivery.php title='Способы оплаты и доставки монет, банкнот, аксессуаров, книг по нумизматике'>Оплата и доставка</a></b>
</td></tr></table>";


if (!$materialtype && !$page && !$search) {
	$infotext .= "<h1 style=\"font-size:14px; font-family:arial;margin-bottom: 4px;margin-top: 4px;\">\"Магазин монет от Клуба Нумизмат\"</h1>
	<span class=txt>В нашем <strong>магазине монет</strong> Вы всегда сможете подобрать интересующие Вас монеты, которых возможно не хватает в Вашей коллекции.<br> 
 <strong>Магазин монет</strong> это то что Вам нужно!<br> 
 Гарантия Ваших покупок, конфиденциальность покупки, доставка монет. Возможность выбрать монеты через интернет или приехать к нам в магазин. У нас удобный магазин, находящийся недалеко от метро. У нас в спокойной обстановке, Вы можете рассмотреть подробнее нужные Вам монеты и купить их. <strong>Магазин монет</strong> представляет удобную и приятную атмосферу для истинных ценителей . 
</span>";
	$linkbottomtext = "<br><a href=../metro/metroplace.html>Купить или продать монеты по станциям метро. Москва</a>";
}
if ($materialtype==3 && $group==816 && substr_count($_SERVER['REQUEST_URI'],".html")!=0)
	$infotext .= "<br><h1 style=\"font-size:16px; font-family:arial;\">\"Альбомы для монет\"</h1><p class=txt> <strong>Альбомы для монет</strong>, это нужный атрибут каждому, кому не безразлична тема монет и все что с ними связано.<br>
Вот основные положительные моменты в <strong>альбомах для монет</strong>:<ol>
<li>	Монеты намного лучше и дольше сохраняют красоту и блеск. Монеты не соприкасаются между собой и не царапают друг друга. А это сохранение и даже увеличение их стоимости с возрастом.
<li>	Очень удобный их просмотр и правильное их расположение по годам и другим удобным Вам параметрам. Монеты всегда легко найти, показать, смотреть. Не надо беспокоиться, что их поцарапают.
<li>	<strong>Альбом для монет</strong> это тоже своего рода произведение и искусства и удобства.
</ol>
<p class=txt> Предлагаем Вашему вниманию <strong>альбомы для монет СССР, для 10 (десяти) рублевых монет</strong>.
<br> Альбомы сделаны качественно и надолго сохранят и преумножать ценность Ваших монет.
<br> Условия покупки и доставки читайте на нашем сайте. 
";


if ($materialtype==3 && $group==1604) 
	$infotext .= "<br><p class=txt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=+1>Для заказа данных товаров Вам нужно обратиться по нашим контактным телефонам.</font></p>";

if ($materialtype==1)
	$infotext .= "<br><table border=0 cellpadding=5 cellspacing=1 style=\'border:thin solid 1px #FF0000\' width=90% align=center><tr class=tboard height=0><td bgcolor=#EBE4D4>
	В связи с укреплением рубля, хотим вас проинформировать о <font color=red><b>снижении 
	цен</b></font> на многие  монеты и наборы более чем 
	на 25%. 
	<br>Также, с 7 мая <font color=red><b>восстановлены купоны на последующую скидку</b></font> 
	при сумме заказа от 2000 рублей. 

	<br>И конечно, <font color=red><b>поздравить ветеранов с наступающим праздником</b></font> 
	70-летия Великой Победы. Здоровья, счастья и мирного неба над головами. 

	<br>С уважением, Клуб Нумизмат.</font>
	</td></tr></table>";

if ($materialtype==7)
	$infotext .= "<table border=0 cellpadding=5 cellspacing=1 style='border:thin solid 1px #FF0000' width=90% align=center>
	<tr class=tboard><td bgcolor=#EBE4D4><a href=# onclick=\"javascript:getElementById('setcoins').innerHTML = '<table border=0 cellpadding=5 cellspacing=1 style=\'border:thin solid 1px #FF0000\' width=90% align=center><tr class=tboard height=0><td bgcolor=#EBE4D4>Изображение предоставлено для данного типа монет. Все наборы не из обращения, но могут быть иногда банковские царапины, патина, налет и прочие дефекты хранения. В некоторых случаях может быть несовпадение года.</td></tr></table>';\"><b><font color=red>Предупреждение, прежде чем приобретать наборы.</font></b></a></td></tr></table><div id=setcoins>".($setcoinsshow < $timenow?"<table border=0 cellpadding=5 cellspacing=1 style=\'border:thin solid 1px #FF0000\' width=90% align=center><tr class=tboard height=0><td bgcolor=#EBE4D4>Изображение предоставлено для данного типа монет. Все наборы не из обращения, но могут быть иногда банковские царапины, патина, налет и прочие дефекты хранения. В некоторых случаях может быть несовпадение года.</td></tr></table>":"")."</div>";

if ($materialtype==8)
	$infotext .= "<table border=0 cellpadding=5 cellspacing=1 style='border:thin solid 1px #FF0000' width=90% align=center>
	<tr class=tboard><td bgcolor=#EBE4D4>
	<a href=# onclick=\"javascript:getElementById('smallcoins').innerHTML = '<table border=0 cellpadding=5 cellspacing=1 style=\'border:thin solid 1px #FF0000\' width=90% align=center><tr class=tboard height=0><td bgcolor=#EBE4D4>Изображение предоставлено для данного типа монет приблизительно одного состояния (+/- 0.5 по шкале F VF XF UNC). Могут быть отклонения по состоянию как в большую так и в меньшую сторону. Proof в эту категорию не входит, поскольку, предполагается, что Proof - это идеальное зеркальное состояние без царапин, заляпин и т.п. Предложения типа - выберите мне полуше - будут отрезаться на корню. В некоторых случаях может быть несовпадение года.</td></tr></table>';\"><b><font color=red>Предупреждение, прежде чем приобретать монеты с раздела МЕЛОЧЬ.</font></b></a></td></tr></table><div id=smallcoins>".($smallcoinsshow < $timenow?"<table border=0 cellpadding=5 cellspacing=1 style=\'border:thin solid 1px #FF0000\' width=90% align=center><tr class=tboard height=0><td bgcolor=#EBE4D4>Изображение предоставлено для данного типа монет приблизительно одного состояния (+/- 0.5 по шкале F VF XF UNC). Могут быть отклонения по состоянию как в большую так и в меньшую сторону. Proof в эту категорию не входит, поскольку, предполагается, что Proof - это идеальное зеркальное состояние без царапин, заляпин и т.п. Предложения типа - выберите мне полуше - будут отрезаться на корню. В некоторых случаях может быть несовпадение года.</td></tr></table>":"")."</div>";

$show_cookie = ( isset($_COOKIE['show_ussa']) ) ? $_COOKIE['show_ussa'] : 1;	
if ($materialtype==12)
	$infotext .= "<table border=0 cellpadding=5 cellspacing=1 style='border:thin solid 1px #FF0000' width=90% align=center>
	<tr class=tboard><td bgcolor=#EBE4D4>
	<a href=# onclick=\"javascript:getElementById('smallcoins').innerHTML = '<table border=0 cellpadding=5 cellspacing=1 style=\'border:thin solid 1px #FF0000\' width=90% align=center><tr class=tboard height=0><td bgcolor=#EBE4D4>Состояние монет данного раздела от VF до XF - т.е. представлены мелочовка ходячка монет СССР, которые были в обращении. Это именно те монеты, которые мало чего стоят и основанная цена складывается за счет трудозатрат на них. Будьте внимательны при выборе. Предложения типа - выберите мне полуше - будут отрезаться на корню.</td></tr></table>';\"><b><font color=red>Предупреждение, прежде чем приобретать монеты с раздела Монеты СССР (цена и стоимость).</font></b></a></td></tr></table><div id=smallcoins>".( ($show_cookie < time() ) ?"<table border=0 cellpadding=5 cellspacing=1 style=\'border:thin solid 1px #FF0000\' width=90% align=center><tr class=tboard height=0><td bgcolor=#EBE4D4>Состояние монет данного раздела от VF до XF - т.е. представлены мелочовка ходячка монет СССР, которые были в обращении. Это именно те монеты, которые мало чего стоят и основанная цена складывается за счет трудозатрат на них. Будьте внимательны при выборе. Предложения типа - выберите мне полуше - будут отрезаться на корню.</td></tr></table>":"")."</div>";
	
if($show_cookie < time() and $materialtype == 12)

setcookie("show_ussa", time()+60*60*24*30, time() + 30*24*60*60, "/shopcoins/");


$AdvertiseText = "<br><table border=0 cellpadding=5 cellspacing=1 style='border:thin solid 1px #FF0000' width=90% align=center>
<tr class=tboard>
<td bgcolor=#EBE4D4>
<b>Не нашли то, что искали? <a href=".$cfg['site_dir']."catalognew target=_blank title='Каталог монет, банкнот При появлении монеты в магазине вам будет отправлено уведомление на email...'>Оставьте заявку в каталоге…</a></b>
</td></tr></table><br>";

$sbrfblank = "
<STYLE type=text/css>
H1 {FONT-SIZE: 12pt}
P {MARGIN-TOP: 6px; MARGIN-BOTTOM: 6px}
UL {MARGIN-TOP: 6px; MARGIN-BOTTOM: 6px}
OL {MARGIN-TOP: 6px; MARGIN-BOTTOM: 6px}
H1 {MARGIN-TOP: 6px; MARGIN-BOTTOM: 6px}
TD {FONT-SIZE: 9pt}
SMALL {FONT-SIZE: 7pt}
BODY {FONT-SIZE: 10pt}
</STYLE>

<TABLE style=\"WIDTH: 180mm; HEIGHT: 145mm\" cellSpacing=0 cellPadding=0 
  border=0 bgColor=#ffffff><TBODY>
  <TR vAlign=top>
    <TD style=\"BORDER-RIGHT: medium none; BORDER-TOP: #000000 1pt solid; BORDER-LEFT: #000000 1pt solid; WIDTH: 50mm; BORDER-BOTTOM: medium none; HEIGHT: 70mm\" align=middle><B>Извещение</B>
	<BR><FONT style=\"FONT-SIZE: 53mm\">&nbsp;<BR></FONT><B>Кассир</B> </TD>
    <TD style=\"BORDER-RIGHT: #000000 1pt solid; BORDER-TOP: #000000 1pt solid; BORDER-LEFT: #000000 1pt solid; BORDER-BOTTOM: medium none\" align=middle>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD align=right><SMALL><I>Форма № ПД-4</I></SMALL></TD></TR>
        <TR>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\">Индивидуальный Предприниматель Мандра Богдан Михайлович</TD></TR>
        <TR>
          <TD align=middle><SMALL>(наименование получателя платежа)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD style=\"WIDTH: 37mm; BORDER-BOTTOM: #000000 1pt solid\">504908219824</TD>
          <TD style=\"WIDTH: 9mm\">&nbsp;</TD>
          <TD 
        style=\"BORDER-BOTTOM: #000000 1pt solid\">40802810538050005372</TD></TR>
        <TR>
          <TD align=middle><SMALL>(ИНН получателя платежа)</SMALL></TD>
          <TD><SMALL>&nbsp;</SMALL></TD>
          <TD align=middle><SMALL>(номер счета получателя 
        платежа)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD>в&nbsp;</TD>
          <TD style=\"WIDTH: 73mm; BORDER-BOTTOM: #000000 1pt solid\">Московский банк ОАО Сбербанк России 3805/01702</TD>
          <TD align=right>БИК&nbsp;&nbsp;</TD>
          <TD 
          style=\"WIDTH: 33mm; BORDER-BOTTOM: #000000 1pt solid\">044525225</TD></TR>
        <TR>
          <TD></TD>
          <TD align=middle><SMALL>(наименование банка получателя платежа)</SMALL></TD>
          <TD></TD>
          <TD></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Номер кор./сч. банка получателя 
          платежа&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">30101810400000000225</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD style=\"WIDTH: 60mm; BORDER-BOTTOM: #000000 1pt solid\">Оплата услуг. Заказ № ___NUMBER___ &nbsp;</TD>
          <TD style=\"WIDTH: 2mm\">&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\">&nbsp;</TD></TR>
        <TR>
          <TD align=middle><SMALL>(наименование платежа)</SMALL></TD>
          <TD><SMALL>&nbsp;</SMALL></TD>
          <TD align=middle><SMALL>(номер лицевого счета (код) 
            плательщика)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Ф.И.О. плательщика&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">___FIO___&nbsp;</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Адрес плательщика&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">___ADRESS___</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>Сумма платежа&nbsp;<FONT 
            style=\"TEXT-DECORATION: underline\">&nbsp;___SUM___&nbsp;</FONT>&nbsp;руб.&nbsp;<FONT 
            style=\"TEXT-DECORATION: underline\">&nbsp;___KOP___&nbsp;</FONT>&nbsp;коп.</TD>
          <TD align=right>&nbsp;&nbsp;Сумма платы за 
            услуги&nbsp;&nbsp;_____&nbsp;руб.&nbsp;____&nbsp;коп.</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>Итого&nbsp;&nbsp;_______&nbsp;руб.&nbsp;____&nbsp;коп.</TD>
          <TD align=right>&nbsp;&nbsp;<______>________________ 200____ 
        г.</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD><SMALL>С условиями приема указанной в платежном документе суммы, 
            в т.ч. с суммой взимаемой платы за услуги банка, ознакомлен и 
            согласен.</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD align=right><B>Подпись плательщика 
        _____________________</B></TD></TR></TBODY></TABLE></TD></TR>
  <TR vAlign=top>
    <TD 
    style=\"BORDER-RIGHT: medium none; BORDER-TOP: #000000 1pt solid; BORDER-LEFT: #000000 1pt solid; WIDTH: 50mm; BORDER-BOTTOM: #000000 1pt solid; HEIGHT: 70mm\" 
    align=middle><FONT 
      style=\"FONT-SIZE: 50mm\">&nbsp;<BR></FONT><B>Квитанция</B><BR><FONT 
      style=\"FONT-SIZE: 8pt\">&nbsp;<BR></FONT><B>Кассир</B> </TD>
    <TD 
    style=\"BORDER-RIGHT: #000000 1pt solid; BORDER-TOP: #000000 1pt solid; BORDER-LEFT: #000000 1pt solid; BORDER-BOTTOM: #000000 1pt solid\" 
    align=middle>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD align=right><SMALL>&nbsp;</SMALL></TD></TR>
        <TR>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\">Индивидуальный Предприниматель Мандра Богдан Михайлович</TD></TR>
        <TR>
          <TD align=middle><SMALL>(наименование получателя платежа)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD 
            style=\"WIDTH: 37mm; BORDER-BOTTOM: #000000 1pt solid\">504908219824</TD>
          <TD style=\"WIDTH: 9mm\">&nbsp;</TD>
          <TD 
        style=\"BORDER-BOTTOM: #000000 1pt solid\">40802810538050005372</TD></TR>
        <TR>
          <TD align=middle><SMALL>(ИНН получателя платежа)</SMALL></TD>
          <TD><SMALL>&nbsp;</SMALL></TD>
          <TD align=middle><SMALL>(номер счета получателя 
        платежа)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>в&nbsp;</TD>
          <TD style=\"WIDTH: 73mm; BORDER-BOTTOM: #000000 1pt solid\">Московский банк ОАО Сбербанк России 3805/01702</TD>
          <TD align=right>БИК&nbsp;&nbsp;</TD>
          <TD 
          style=\"WIDTH: 33mm; BORDER-BOTTOM: #000000 1pt solid\">044552362</TD></TR>
        <TR>
          <TD></TD>
          <TD align=middle><SMALL>(наименование банка получателя 
            платежа)</SMALL></TD>
          <TD></TD>
          <TD></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Номер кор./сч. банка получателя 
          платежа&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" 
            width=\"100%\">30101810000000000362</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD style=\"WIDTH: 60mm; BORDER-BOTTOM: #000000 1pt solid\">Оплата услуг. Заказ № ___NUMBER___&nbsp;</TD>
          <TD style=\"WIDTH: 2mm\">&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\">&nbsp;</TD></TR>
        <TR>
          <TD align=middle><SMALL>(наименование платежа)</SMALL></TD>
          <TD><SMALL>&nbsp;</SMALL></TD>
          <TD align=middle><SMALL>(номер лицевого счета (код) 
            плательщика)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Ф.И.О. плательщика&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">___FIO___&nbsp;</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Адрес плательщика&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">___ADRESS___&nbsp;</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>Сумма платежа&nbsp;<FONT 
            style=\"TEXT-DECORATION: underline\">&nbsp;___SUM___&nbsp;</FONT>&nbsp;руб.&nbsp;<FONT 
            style=\"TEXT-DECORATION: underline\">&nbsp;___KOP___&nbsp;</FONT>&nbsp;коп.</TD>
          <TD align=right>&nbsp;&nbsp;Сумма платы за 
            услуги&nbsp;&nbsp;_____&nbsp;руб.&nbsp;____&nbsp;коп.</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>Итого&nbsp;&nbsp;_______&nbsp;руб.&nbsp;____&nbsp;коп.</TD>
          <TD align=right>&nbsp;&nbsp;<______>________________ 200____ 
        г.</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD><SMALL>С условиями приема указанной в платежном документе суммы, 
            в т.ч. с суммой взимаемой платы за услуги банка, ознакомлен и 
            согласен.</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD align=right><B>Подпись плательщика 
        _____________________</B></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H1>Внимание! В стоимость заказа не включена комиссия банка.</H1>
<OL>
  <LI>Распечатайте квитанцию. Если у вас нет принтера, перепишите верхнюю часть 
  квитанции и заполните <br>по этому образцу стандартный бланк квитанции в вашем 
  банке. 
  <LI>Вырежьте по контуру квитанцию. 
  <LI>Оплатите квитанцию в любом отделении банка. 
  <LI>Сохраните квитанцию до получения заказа. </LI></OL>";

$city_array = Array ("Другой", "Москва", "Санкт-Петербург", "Нижний Новгород", "Тольятти", "Калининград", "Владивосток", "Самара", "Новосибирск", "Минск", "Киев");	
?>
<?
/*
if (isset($_SERVER['HTTP_REFERER'])) {
	$ref = $_SERVER['HTTP_REFERER'];
	$refData = parse_url($ref);
	if(in_array($refData['host'], $black_list)) {
		die('bb');
	}
}


//Просчитывает уровень вложенности файла. Нужна для выхода в корневую дирректорию сайта.
//Прикрепляется через php.ini
$sin=(count(explode("/",$_SERVER["PHP_SELF"]))-2);
if($sin!=0){$cfg['site_dir']=str_repeat("../",$sin);} else {$cfg['site_dir']="";}
$sin_m=(count(explode("/",$PHP_SELF))-3);
if($sin_m>0){$cfg['site_dir']_m=str_repeat("../",$sin_m);} else {$cfg['site_dir']_m="";}

if ($_SERVER['HTTP_HOST']=="news.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="biblio.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="tboard.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="blacklist.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="advertise.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="album.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="rating.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="catalog.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="shopcoins.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];
elseif ($_SERVER['HTTP_HOST']=="aukcion.numizmatik.ru")
	$cfg['site_dir'] = "http://www.numizmatik.ru/".$cfg['site_dir'];





setcookie("testingcookies", 1, time()+3600,"/");
if ($cookies=="delete")
{
	setcookie("cookiesfio");
	setcookie("cookiesuserlogin");
	setcookie("cookiesuserpassword");
	setcookie("cookiesuser");
	
	setcookie("cookiesfio", "", time() + 5, "/", $domain);
	setcookie("cookiesfio", "", time() + 5, "/");
	setcookie("cookiesuserlogin", "", time() + 5, "/", $domain);
	setcookie("cookiesuserlogin", "", time() + 5, "/");
	setcookie("cookiesuserpassword", "", time() + 5, "/", $domain);
	setcookie("cookiesuserpassword", "", time() + 5, "/");
	setcookie("cookiesuser", "", time() + 5, "/", $domain);
	setcookie("cookiesuser", "", time() + 5, "/");
	
	setcookie("cookiesuserlogin", "", time() + 5, "/shopcoins/", $domain);
	setcookie("cookiesuserlogin", "", time() + 5, "/shopcoins/");
	setcookie("cookiesuserlogin", "", time() + 5, "/shopcoins/", ".shopcoins.numizmatik.ru");
	
	setcookie("cookiesuserpassword", "", time() + 5, "/shopcoins/", $domain);
	setcookie("cookiesuserpassword", "", time() + 5, "/shopcoins/");
	setcookie("cookiesuserpassword", "", time() + 5, "/shopcoins/", ".shopcoins.numizmatik.ru");
	
	setcookie("cookiesuser", "", time() + 5, "/shopcoins/", $domain);
	setcookie("cookiesuser", "", time() + 5, "/shopcoins/");
	setcookie("cookiesuser", "", time() + 5, "/shopcoins/", ".shopcoins.numizmatik.ru");
	
	unset($_SESSION['cookiesuserlogin']);
	unset($_SESSION['cookiesuserpassword']);
	unset($_SESSION['cookiesuser']);
	
	//header("Location: http://$_SERVER['HTTP_HOST']/index.php?cookies=end");
}

if (trim($_COOKIE['cookiesuserlogin']))
	$cookiesuserlogin = trim($_COOKIE['cookiesuserlogin']);
elseif(trim($_SESSION['cookiesuserlogin']))
	$cookiesuserlogin = trim($_SESSION['cookiesuserlogin']);
else
	unset($cookiesuserlogin);
	

if (trim($_COOKIE['cookiesuserlogin']))
	$cookiesuserpassword = trim($_COOKIE['cookiesuserpassword']);
elseif(trim($_SESSION['cookiesuserpassword']))
	$cookiesuserpassword = trim($_SESSION['cookiesuserpassword']);
else
	unset($cookiesuserpassword);


if (intval($_COOKIE['cookiesuser'])>0)
	$cookiesuser = intval($_COOKIE['cookiesuser']);
elseif(intval($_SESSION['cookiesuser'])>0)
	$cookiesuser = intval($_SESSION['cookiesuser']);
else
	unset($cookiesuser);

if (intval($_COOKIE['shopcoinsorder'])>0)
	$shopcoinsorder = intval($_COOKIE['shopcoinsorder']);
elseif(intval($_SESSION['shopcoinsorder'])>0)
	$shopcoinsorder = intval($_SESSION['shopcoinsorder']);
elseif (intval($_GET['shopcoinsorder'])>0)
	$shopcoinsorder = intval($_GET['shopcoinsorder']);
elseif(intval($_POST['shopcoinsorder'])>0)
	$shopcoinsorder = intval($_POST['shopcoinsorder']);
else
	unset($shopcoinsorder);

if ($shopcoinsorder>0) {

	$sql99 = "select count(*) from `order`,orderdetails where order.order=orderdetails.order and orderdetails.status=0 and order.order=$shopcoinsorder and order.check=0;";
	$result99 = mysql_query($sql99);
	$rows99 = mysql_fetch_array($result99);
	$_SESSION['shopcoinsorderamount'] = $rows99[0];
	
	$shopcoinsorderamount = $rows99[0];
}


$bigsumcoupon = 15000;
$bigsumcoupondis = 5;
$smallsumcoupon = 2000;
$smallsumcoupondis = 3;


$newsimagesfolder = $cfg['site_dir']."news/images/";
$biblioimagesfolder = $cfg['site_dir']."biblio/images/";
$ratingfont = "/var/www/htdocs/www.numizmatik.ru/rating/";
$pricelistfolder = $cfg['site_dir']."inshops/pricelist";
$cfg['site_dir']shopsdetailsimagefolder = $cfg['site_dir']."inshops/images";
$temp_files_folder = $cfg['site_dir']."temp/files/";
$aukcionimagefolder = $cfg['site_dir']."aukcion/images";
$catalogimagefolder = $cfg['site_dir']."catalog/images";
$shopcoinsimagefolder = $cfg['site_dir']."shopcoins/images";


$timenow = time();
$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));
$script = $SCRIPT_NAME;

if ($cookies=="end") 	header("Location: /");

$userlogin = trim(str_replace("'","",$userlogin));
$userpassword = trim(str_replace("'","",$userpassword));

if (($userlogin) and ($userpassword) and ($login))
{
	//$sql = "select fio, userlogin, userpassword, `user` from `user` where userlogin='$userlogin' and userpassword='$userpassword';";
	$sql = "select fio, userlogin, userpassword, `user` from `user` where ( userlogin='$userlogin' OR email='$userlogin') and userpassword='$userpassword';";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	if ($rows[3]>0)
	{
		setcookie("cookiesfio", $rows[0], time() + 86400 * 90, "/", $domain);
		setcookie("cookiesfio", $rows[0], time() + 86400 * 90, "/");
		//setcookie("username", $rows[0], time() + 86400 * 90, "/", $domain);
		//setcookie("username", $rows[0], time() + 86400 * 90, "/");
		setcookie("cookiesuserlogin", $rows[1], time() + 86400 * 90, "/", $domain);
		setcookie("cookiesuserlogin", $rows[1], time() + 86400 * 90, "/");
		setcookie("cookiesuserpassword", $rows[2], time() + 86400 * 90, "/", $domain);
		setcookie("cookiesuserpassword", $rows[2], time() + 86400 * 90, "/");
		//setcookie("password", $rows[2], time() + 86400 * 90, "/", $domain);
		//setcookie("password", $rows[2], time() + 86400 * 90, "/");
		setcookie("cookiesuser", $rows[3], time() + 86400 * 90, "/", $domain);
		setcookie("cookiesuser", $rows[3], time() + 86400 * 90, "/");
		$_SESSION['cookiesuserlogin'] = $rows[1];
		$_SESSION['cookiesuserpassword'] = $rows[2];
		$_SESSION['cookiesuser'] = $rows[3];
		//header("Location: ".$cfg['site_dir']."user/profile.php?cookies=yes");
	} else {
		header("Location: /?cookies=no");
	}
}

if (!isset($testmaterialtypeformenu)) {

	$sql = "select * from shopcoins where `check`=1 and materialtype in(9,5) group by materialtype;";
	$result = mysql_query($sql);
	while ($rows=mysql_fetch_array($result)) {
	
		$testmaterialtypeformenu += pow(2,$rows['materialtype']);
	}
	
	setcookie("testmaterialtypeformenu", $testmaterialtypeformenu, time() + 3600, "/");
	setcookie("testmaterialtypeformenu", $testmaterialtypeformenu, time() + 3600, "/", $domain);
	//echo $testmaterialtypeformenu;
}

$cookiesuser = intval($cookiesuser);

if (!substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"IE")) 
	$browser="nn";
else 
	$browser="ie";

$type_array = Array("coins", "bonni", "orden", "aukcion", "biblio", "rating", "metall", "rusichbank", "journals", "shopcoins", "book","accessory","detectors","notgeld");

include_once $_SERVER['DOCUMENT_ROOT']."/tmpconfig.php";
$serverf = $_SERVER['HTTP_HOST'];

if (!$_COOKIE['showocenkamonet'] &&  substr_count($_SERVER['REQUEST_URI'],"/wadmin/")==0) {

$showbannersmain = "<noindex><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0\" type=application/x-shockwave-flash width=800 height=580 align=center> <param name=allowScriptAccess value=localhost /> <param name=movie value=http://".$serverf."/images/numizmatik.swf /> <param name=quality value=autohigh /> <param name=play value=true /> <param name=loop value=true /> <param name=bgcolor value=#ffffff /> <embed src=http://".$serverf."/images/numizmatik.swf quality=autohigh bgcolor=#ffffff width=800 height=580 name=file align=middle play=true loop=true allowScriptAccess=localhost type=application/x-shockwave-flash pluginspage=http://www.macromedia.com/go/getflashplayer /></object><br><a href=# onclick='CloseShowOcenka();'>Закрыть</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href=".$cfg['site_dir']."ocenka-stoimost-monet >Больше информации по оценке монет</a></noindex>";
	setcookie("showocenkamonet", 1, time() + 86400 * 14, "/", $domain);
	setcookie("showocenkamonet", 1, time() + 86400 * 14, "/");
}*/
?>
