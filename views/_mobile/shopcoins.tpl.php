<?
if($tpl['task']){
     include $cfg['path'] . '/views/_mobile/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php'; 
} else {
    include $cfg['path'] . '/views/_mobile/' . $tpl['module'] . '/catalog.tpl.php';
}?>
