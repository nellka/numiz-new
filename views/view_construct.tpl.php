<?
  if($tpl['task']&&file_exists($cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php')){
      include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php';
  } elseif (!$tpl['task']&&file_exists($cfg['path'] . '/views/' . $tpl['module'] . '/index.tpl.php')){
      include $cfg['path'] . '/views/' . $tpl['module'] . '/index.tpl.php';
  } else include $cfg['path'] . '/views/' . $tpl['module'] . '.tpl.php';
?>
