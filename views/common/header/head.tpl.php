<?php header('Content-Type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="ru">
<head>
<title><?=isset($tpl[$tpl['module']]['_Title'])?$tpl[$tpl['module']]['_Title']:$tpl['base']['_Title']?></title>
<meta name="Keywords" content="<?=isset($tpl[$tpl['module']]['_Keywords'])?$tpl[$tpl['module']]['_Keywords']:$tpl['base']['_Keywords']?>" >
<meta name="Description" content="<?=isset( $tpl[$tpl['module']]['_Description'])?$tpl[$tpl['module']]['_Description']:$tpl['base']['_Description']?>">
<meta name="viewport" content="width=1200" />
<link rel="SHORTCUT ICON" href="<?=$cfg['site_dir']?>favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body>  
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type= "text/javascript"></script> 
