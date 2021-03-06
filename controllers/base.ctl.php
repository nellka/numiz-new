<?
//require_once($cfg['path'].'/helpers/mobile_detect.php');
//require $cfg['path'] . '/helpers/cookiesWork.php';

require_once $cfg['path'] . '/models/orderdetails.php';
require_once $cfg['path'] . '/models/stats.php';
require_once ($cfg['path'].'/helpers/urlBuilder.php');

if(request('logout')){

	$user_class->logout();
	unset($_POST['logout']);
	$url = $_SERVER["REQUEST_URI"];
	$url = str_replace('?logout=1','',$url);
	$url = str_replace('&logout=1','',$url);
	//var_dump($_COOKIE);
	  // die();
	header("location: ".$url);
	die();
}
/*if($_COOKIE){
	var_dump($_COOKIE);
}*/
//die();

require $cfg['path'] . '/controllers/start_params.ctl.php';

$orderdetails_class = new model_orderdetails($db_class,$shopcoinsorder);

if($tpl['is_mobile']&&isset($_COOKIES['fullversion'])){
	$tpl['is_mobile'] = false;
}

$nocheck = request('nocheck');
$mailkey = request('mailkey');

$tmp['addcall']['errors'] = array();

$user_remote_address = $_SERVER['REMOTE_ADDR'] ;
$tpl['user']['remote_address'] = $user_remote_address;
//логиним пользователя по ссылке если надо

if($mailkey&&$tpl['module']=='subscribe'){  
   $user_class->loginByMailkey($mailkey);
}

//проверяем залогинивание пользователя
$tpl['user']['is_logined'] = $user_class->is_logged_in();

//получаем информацию о балансе пользователя пользователя
$tpl['user']['summ'] = $bascetsum;
$tpl['user']['product_amount'] = intval($shopcoinsorderamount);

$domain = $_SERVER["HTTP_HOST"];
$domain = '.'.str_replace('www.','', $domain);
       // var_dump($_SESSION);	    

//ЭТА ПРОВЕРКА ЧАСТО ИСПОЛЬЗУЕТСЯ. ЗАЧЕМ НЕ ПОНЯТНО, НО ВЫНОШУ В ОТДЕЛЬНОЕ СВОЙСТВО

if ($user_remote_address!="213.180.194.162" 
&& $user_remote_address!="213.180.194.133" 
&& $user_remote_address!="213.180.194.164" 
&& $user_remote_address!="213.180.210.2" 
&& $user_remote_address!="83.149.237.18"
&& $user_remote_address!="83.237.234.171"
&& !substr_count($_SERVER["HTTP_USER_AGENT"],"ia_archiver")
&& !substr_count($_SERVER["HTTP_USER_AGENT"],"coona")
) $tpl['user']['can_see'] = true;

if ($tpl['user']['is_logined']){
	//данный набор о пользователеле нужен в нескольких кусках хода, поэтому чтобы не дублировать выношу в отдельную функцию
	$user_base_data = $user_class->getUserBaseData();
	
	$tpl['user'] = array_merge($tpl['user'],$user_base_data);
	$shopcoins_class = new model_shopcoins($db_class,$tpl['user']['user_id'],$nocheck);
	$tpl['user']['catalogamount'] = count($shopcoins_class->myCoinsRequest());
	$tpl['user']['my_ip'] = ((getenv("REMOTE_ADDR")=="212.233.78.26"||getenv("REMOTE_ADDR")=="127.0.0.1")?1:0);
	
	$clientdiscount = $orderdetails_class->getClientdiscount($tpl['user']['user_id']);
	    	    
	if(!$shopcoinsorder) {
	    $shopcoinsorder = $user_class->getUserCurrentOrder();
	    $orderdetails_class->setShopcoinsorder($shopcoinsorder);

        if(isset($_SESSION['orderstart'])&&intval($_SESSION['orderstart'])>0){
            $orderstart = intval($_SESSION['orderstart']);
            if(time() > $orderstart + 5*3600){
                $orderstart = 0;
            }
        }
	}
	
	if($shopcoinsorder){ 	        		
		
        $tpl['user']['product_amount'] = $_SESSION["shopcoinsorderamount"] = $orderdetails_class->getShopcoinsorderamount();
        
        $dataBasket = $orderdetails_class->forBasket($clientdiscount);	
        	
    	$tpl['user']['summ'] = $bascetsum = $_SESSION['bascetsum'] = $dataBasket["mysum"];


	}
	
} else {
	$tpl['user']['user_id'] = 0;
	$shopcoins_class = new model_shopcoins($db_class,$tpl['user']['user_id'],$nocheck);
}

$stats_class = new stats($db_class,$tpl['user']['user_id'],session_id());

$tpl['user']['showfullgrouplist'] = false;

$u_statistic = $user_class->getStat();

$tpl['user']['fullcount'] = $u_statistic['fullcount'];
$tpl['user']['coinscount'] = $u_statistic['coinscount'];
$tpl['user']['searchcount'] = $u_statistic['searchcount'];
$tpl['user']['filterscount'] = $u_statistic['filterscount'];

if(!isset($_COOKIE['groups-full-show'])&&$tpl['user']['user_id']) {
	$tpl['user']['showfullgrouplist'] = true;
}

$mini = false;
if(!isset($_COOKIE['mini'])&&$tpl['user']['user_id']) {
	$mini = true;
} else if(isset($_COOKIE['mini'])&&$_COOKIE['mini']>0){
	$mini = true;
}

if($tpl['is_mobile']&&$fv){
	$tpl['is_mobile'] = false;
}

$sh_mt = 0;

if($tpl['is_mobile']){
	$sh_mt = (isset($_COOKIE['sh_mt'])&&$_COOKIE['sh_mt']>0)?true:false;
	if(!$sh_mt){
		setcookie("sh_mt", 1, time() + 3 * 3600, "/");
	}
}

if($tpl['user']['user_id']==352480){  
	//var_dump($_REQUEST);
	//var_dump($_SERVER['REMOTE_ADDR']);
  // $tpl['is_mobile'] = true;
}

//var_dump($tpl);
//если пользователь залогинен и запрещено делать заказы, то проверяем те заказы, которые были
$tpl['user']['orderusernow'] = 0;  
//зачем на главное - под вопросом
if ($blockend < time()&& $tpl['user']['user_id']) {
	$tpl['user']['orderusernow'] = $user_class->setOrderusernow();   
}

include_once($cfg['path'] ."/configs/keywordsAdmin.php");

//include $_SERVER["DOCUMENT_ROOT"]."/keywords.php";
if($tpl['module']=='cron'){
    
} elseif (in_array($_SERVER["HTTP_USER_AGENT"],$black_user_agent_list[0])
|| substr_count($_SERVER["HTTP_USER_AGENT"],"coona")
|| substr_count($_SERVER["HTTP_USER_AGENT"],"Rufus")
|| substr_count($_SERVER["HTTP_USER_AGENT"],"Wget")
|| substr_count($_SERVER["HTTP_USER_AGENT"],"Robot")
||in_array($user_remote_address,$black_ip_list[0]))
{
    $tpl['error_text'] = 'Скачивание информации запрещено!!!Если Вы считаете что это ошибка, свяжитесь с администратором по телефону 8-926-268-41-10.';
    require $cfg['path'] . '/views/error.tpl.php';
	exit;
} 

$timenow = time();

if ($_SERVER['REQUEST_URI'] == "/?materialtype=8&group=590&search=15+%EA%EE%EF%E5%E5%EA") {    
	$textbottompage = "<p class=txt>Продажа монет СССР для истинных ценителей истории. Всегда в продаже монеты СССР любого достоинства. Звоните и заходите в наш магазин в Москве. </p>";
} else	$textbottompage = "";

$tpl['stat']['users'] = $user_class->countAll();
$tpl['stat']['items'] = $shopcoins_class->countAll();
$tpl['stat']['news'] =  $news_class->countAll();

//кладем в кеш металлы и состояния, чтобы работать с ними по id
if(!$tpl['metalls'] = $cache->load("metalls")) {	   
    $_metalls = $shopcoins_class->getMetalList();	 
    $tpl['metalls'][0]='';
    foreach ($_metalls as $row){
        $tpl['metalls'][$row['id']] = $row['name'];
    }
    $cache->save($tpl['metalls'], "metalls");	 
}

if(!$tpl['conditions'] = $cache->load("conditions")) {	   
    $_condition = $shopcoins_class->getConditions(true);	 
    $tpl['conditions'][0]='';
    foreach ($_condition as $row){
        $tpl['conditions'][$row['condition_id']] = $row['name'];
    }
    $cache->save($tpl['conditions'], "conditions");	 
}

require $cfg['path'] . '/controllers/topmenu.ctl.php';

$dir = $cfg['path'] .  '/controllers/'.$tpl['module'].'/';

if( is_dir($dir)){
    if($tpl['task']&&file_exists($dir.$tpl['task'].'.ctl.php')){
         $controller = $dir.$tpl['task'].'.ctl.php';
    } else {
         $controller = $dir.$tpl['module'].'.ctl.php';
    }
} else {
    $controller = $cfg['path'] .  '/controllers/'.$tpl['module'].'.ctl.php';   
}

//var_dump($controller);
$static_page = true;
if(file_exists($controller)){
    $static_page = false;
    //для статических страниц контроллера может не быть
    require  $controller;
}
$tpl["datatype"] = request('datatype');

/*

if($tpl["datatype"]=='json'){
   // if(!$tpl['task']){
       echo json_encode($tpl[$tpl['module']]);
	   die();
   // }    
	//echo json_encode($tpl[$tpl['task']]);
	//die();
}

if($tpl["datatype"]=='text_html'){
	 if($static_page){
    	require_once $cfg['path'] .  '/views/static_pages/'.$tpl['module'].'.tpl.php';
    } else {
    	require_once $cfg['path'] .  '/views/'.$tpl['module'].'.tpl.php';
    }
    die();
}
if($tpl['ajax']){
    //require_once $cfg['path'] . '/views/common/header/head.tpl.php';
   
    require_once $cfg['path'] .  '/views/'.$tpl['module'].'.tpl.php';
  
    die();
}

require_once  $cfg['path'] .  '/controllers/breadcrumbs.ctl.php';
require_once  $cfg['path'] .  '/views/template.php';

die();*/

if($tpl["datatype"]=='text_html'){
	 if($static_page){
	    if($tpl['is_mobile']&&file_exists($cfg['path'] .  '/views/_mobile/static_pages/'.$tpl['module'].'.tpl.php')){
	       require_once $cfg['path'] .  '/views/_mobile/static_pages/'.$tpl['module'].'.tpl.php';
	    } else {	    
    	   require_once $cfg['path'] .  '/views/static_pages/'.$tpl['module'].'.tpl.php';
	    }
    } else {
        if($tpl['is_mobile']&&file_exists($cfg['path'].'/views/_mobile/'.$tpl['module'].'.tpl.php')){
    	   require_once $cfg['path'].'/views/_mobile/'.$tpl['module'].'.tpl.php';
        } else {
            if($tpl['task']&&file_exists($cfg['path'].'/views/'.$tpl['module'].'/'.$tpl['task'].'.tpl.php')){
                require_once $cfg['path'].'/views/'.$tpl['module'].'/'.$tpl['task'].'.tpl.php';
            } else require_once $cfg['path'].'/views/'.$tpl['module'].'.tpl.php';
        }
    }
    die();
} elseif ($tpl["datatype"]=='json'){
   echo json_encode($tpl[$tpl['module']]);
   die();
}

if($tpl['ajax']){   
    if($tpl['is_mobile']&&file_exists($cfg['path'].'/views/_mobile/'.$tpl['module'].'.tpl.php')){
        require_once $cfg['path'] .  '/views/_mobile/'.$tpl['module'].'.tpl.php';
    } else {
        if($tpl['task']&&file_exists($cfg['path'].'/views/'.$tpl['module'].'/'.$tpl['task'].'.tpl.php')){
            require_once $cfg['path'].'/views/'.$tpl['module'].'/'.$tpl['task'].'.tpl.php';
        } else require_once $cfg['path'] .  '/views/'.$tpl['module'].'.tpl.php';
    }  
    die();
}

require_once  $cfg['path'] .  '/controllers/breadcrumbs.ctl.php';

if($tpl['is_mobile']){
    require_once  $cfg['path'] .  '/views/_mobile/template.php';
} else {
    require_once  $cfg['path'] .  '/views/template.php';   
}

die();
?>