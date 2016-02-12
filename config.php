<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);	
if(!defined('PATH_LIBS')) define('PATH_LIBS', dirname(__FILE__) .'/php_lib');
if(!defined('DIR_TEMPLATE')) define('DIR_TEMPLATE', dirname(__FILE__) .'/views/');
if(!defined('START_PATH')) define('START_PATH', dirname(__FILE__) );

ini_set('include_path', ini_get('include_path') . 	PATH_SEPARATOR . PATH_LIBS );

$cfg = array(
    'path' => dirname(__FILE__),

    'db' => array(        
            'host'     => 'localhost',
            'username' => 'tester',
            'password' => 'eh3Majyd',
            'dbname'   => 'tet',
      ),
      'site_root' => 'http://'.$_SERVER["HTTP_HOST"]."/new/",
      'site_dir' => 'http://'.$_SERVER["HTTP_HOST"]."/new/",
      'domain'=>".numizmatik1.ru",
      'server_name' =>"http://www.numizmatik1.ru",
      'email_admin'=>"bodka@rt.mipt.ru"     
);

$minpriceoneclick = 500;
$reservetime = 18000;
$mintime = 1800;
$stopsummax = 3000000;

$black_ip_list = array(0=>array('67.159.4.134',"82.142.161.85","217.172.29.30",'195.248.185.209'),
                       1=>array("91.192.131.2","83.167.125.157","94.19.78.255","193.232.110.5"));
                       
$black_user_agent_list = array(0=>array("Web Downloader/6.8","Web Downloader/7.2"),
                       1=>array('Wget/1.8.1'));      
                                       
$black_list = array('fatstube.com', 
					'www.fatstube.com',
					'beeg.com',
					'www.beeg.com',
					'asianfuckthumbs.com',
					'www.asianfuckthumbs.com',
					'xxxtube.com',
					'www.xxxtube.com',
					'm.dojki.com',
					'www.m.dojki.com',
					'dojki.com',
					'www.dojki.com',
					'sexu.com',
					'www.sexu.com',
					'freenung-x.com',
					'www.freenung-x.com');


//Start shopcoins ------------------------------------------------------------------------------------
$blockend = 1446801658;

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