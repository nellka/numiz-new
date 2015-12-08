<?
header('Content-type: text/html; charset=utf-8');
try {
   
    require dirname(__FILE__) . '/config.php';        
    
    //функции хелпера собрали в себе кучу мелких статических функци
    require_once $cfg['path'] . '/helpers/functions_h.php';
    require_once $cfg['path'] . '/helpers/contentHelper.php';    
    require_once $cfg['path'] . '/helpers/Mobile_Detect.php';
    require_once $cfg['path'] . '/models/Model_Base.php';
    require_once $cfg['path'] . '/models/user.php';
    require_once($cfg['path'] . '/models/news.php');
    require_once $cfg['path'] . '/models/shopcoins.php';
    
    //проверяем, что мобильное устройство
    $Mobile_Detect = new Mobile_Detect();
    $tpl['is_mobile'] = $Mobile_Detect->isMobile();         
     	
    //будем на стадии Index проверять залогинивание
    session_start();
    $shopcoins_class = new model_shopcoins($cfg['db']);
    $user_class = new model_user($cfg['db']);
    $news_class = new model_news($cfg['db']);

    //с каким контроллером работаем
    $tpl['module'] = request('module')? request('module') : 'index';    
    //определяем, что дергаем как аякс, то есть не надо подгружать основной темплейт, с шапкой, футером и ид
    $tpl['ajax']  = request('ajax')?request('ajax'):0;
    // какое действие внутри контроллера
	$tpl['task'] = request('task')?request('task'):"";
    // тип данных(например json не подгружает template)
    $tpl['datatype'] = request('datatype')?request('datatype'):"";

    //потом base возможно переедет в index.php
    include $cfg['path'] . '/controllers/base.ctl.php';
   /* $ctl = $cfg['path'] . '/controllers/' . $tpl['module'] . '.ctl.php';
    if ( file_exists($ctl) ) {
        include $ctl;
    } else {
        die('Модуль ' . $tpl['module'] . ' не найден ');
    }*/

} catch ( Exception $e ) {
    die( $e->getMessage() . ' ' .
         $e->getTraceAsString() );
}
die();