<?
header('Content-type: text/html; charset=utf-8');
try {
    session_start();
    require dirname(__FILE__) . '/config.php';        
    $time_script_start = microtime(true);
    //функции хелпера собрали в себе кучу мелких статических функци
    require_once $cfg['path'] . '/helpers/functions_h.php';
    require_once $cfg['path'] . '/helpers/contentHelper.php';    
    require_once $cfg['path'] . '/helpers/Mobile_Detect.php';
    require_once $cfg['path'] . '/models/Model_Base.php';
    require_once $cfg['path'] . '/models/user.php';
    require_once($cfg['path'] . '/models/news.php');
    require_once $cfg['path'] . '/models/shopcoins.php';
     require_once 'Zend/Cache.php';
     
    $group = request('group');
	$search = request('search');
	
	if ($group==790 or $search == "Гитлер" or $search=="гитлер" or $search=="адольф" or $search=="Адольф" or $search == "рейх" or $search=="Рейх"){
		DIE();
	}
    //проверяем, что мобильное устройство
    $Mobile_Detect = new Mobile_Detect();
    $tpl['is_mobile'] = $tpl['mv'] = $Mobile_Detect->isMobile();         
     //подключаем кеш
    $frontendOptions = array('lifetime' => 24*3600, // время жизни кэша - 2 часа   
       'automatic_serialization' => true);
     
    $backendOptions = array(
        'cache_dir' => './tmp/' // директория, в которой размещаются файлы кэша
    );
     
    // получение объекта Zend_Cache_Core
    $cache = Zend_Cache::factory('Core',  'File',   $frontendOptions,  $backendOptions);
    Zend_Registry::set("cache",$cache);	
    /* $frontendOptions = array (
                      'automatic_serialization' => true,
                      'cache_id_prefix' => 'Num_',
                      'lifetime' => 1800, // infinity must be ...
                      'caching' => true,
                      'gzip' => true,
                    );
                    
      $backendOptions = array (
                          'servers' =>
                              array (
                                0 =>
                                    array (
                                      'host' => 'localhost',
                                      'port' => 11211,
                                    ),
                              ),
                          'compression' => false,
                        );
    $cache = Zend_Cache::factory('Core',  'Memcached',   $frontendOptions,  $backendOptions);
    
    Zend_Registry::set("Memcached",$cache);*/
                        
    //будем на стадии Index проверять залогинивание
    ob_start();
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
	
/*
	if (!file_exists($logfile))
	{
	  $fp = fopen($logfile, 'a');
	  fclose($fp);
	}*/
	require_once 'Zend/Log/Writer/Stream.php';
	require_once 'Zend/Log.php';
	
	$writer = new Zend_Log_Writer_Stream($logfile);
	$logger    = new Zend_Log($writer);
    $logger->log($_SERVER['REQUEST_URI'],Zend_Log::INFO); 
	$logger->log($e->getMessage(),Zend_Log::INFO);
	$logger->log(var_export($e->getTrace(),true),Zend_Log::INFO);
	
    //if($tpl['user']['user_id']==352480){
        var_dump($e->getMessage());
    //}
	 die('Извините, произошла ошибка!');
   // die( $e->getMessage() . ' ' .
       //  $e->getTraceAsString() );
}
die();
