<?
if($tpl['task']){
     include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php';
} else {
    include $cfg['path'] . '/views/' . $tpl['module'] . '/news.tpl.php';
}?>
