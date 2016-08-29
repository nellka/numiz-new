<?
if($tpl['is_mobile']){
    
    if($tpl['task']&&file_exists($cfg['path'] . '/views/_mobile/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php')){
          include $cfg['path'] . '/views/' . $tpl['module'] . '/_mobile/'.$tpl['task'].'.tpl.php';
      } elseif ($tpl['task']&&file_exists($cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php')){
          include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php';
      } elseif (file_exists($cfg['path'] . '/views/_mobile/' . $tpl['module'] . '.tpl.php')){
          include $cfg['path'] . '/views/_mobile/' . $tpl['module'] . '.tpl.php';
      } elseif (file_exists($cfg['path'] . '/views/_mobile/' . $tpl['module'] . '/index.tpl.php')){
          include $cfg['path'] . '/views/_mobile/' . $tpl['module'] . '/index.tpl.php';
      } else include $cfg['path'] . '/views/view_construct.tpl.php';
    
    /*if($tpl['is_mobile']&&file_exists($cfg['path'].'/views/_mobile/'.$tpl['module'].'.tpl.php')){?>
	                
    <? require_once $cfg['path'] .  '/views/_mobile/'.$tpl['module'].'.tpl.php';?>			        
<?} else {
    require_once $cfg['path'] .  '/views/'.$tpl['module'].'.tpl.php';
}*/
} else include $cfg['path'] . '/views/view_construct.tpl.php';

/*
 if($tpl['is_mobile']&&file_exists($cfg['path'].'/views/_mobile/'.$tpl['module'].'.tpl.php')){?>
	                
    <? require_once $cfg['path'] .  '/views/_mobile/'.$tpl['module'].'.tpl.php';?>			        
<?} else {
    require_once $cfg['path'] .  '/views/'.$tpl['module'].'.tpl.php';
}
			    
  if($tpl['task']&&file_exists($cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php')){
      include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php';
  } elseif (!$tpl['task']&&file_exists($cfg['path'] . '/views/' . $tpl['module'] . '/index.tpl.php')){
      include $cfg['path'] . '/views/' . $tpl['module'] . '/index.tpl.php';
  } else include $cfg['path'] . '/views/' . $tpl['module'] . '.tpl.php';*/
?>
