<?php header('Content-Type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href='<?=$cfg['site_dir']?>css/main.css'>
<link rel=stylesheet type=text/css href='<?=$cfg['site_dir']?>js/main.js'>
<link rel="SHORTCUT ICON" href="<?=$cfg['site_dir']?>favicon.ico">
<link href='https://fonts.googleapis.com/css?family=Roboto:400italic,700,700italic,100,400,100italic' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>	
<script> $.noConflict();
jQuery( document ).ready(function( $ ) {});
</script> 
<script src="<?=$cfg['site_dir']?>js/jquery.maskedinput.min.js"></script>	
<title><?=isset($tpl[$tpl['module']]['_Title'])?$tpl[$tpl['module']]['_Title']:$tpl['base']['_Title']?></title>
<meta name="Keywords" content="<?=isset($tpl[$tpl['module']]['_Keywords'])?$tpl[$tpl['module']]['_Keywords']:$tpl['base']['_Keywords']?>" >
<meta name="Description" content="<?=isset( $tpl[$tpl['module']]['_Description'])?$tpl[$tpl['module']]['_Description']:$tpl['base']['_Description']?>">
</head>
<body>  
