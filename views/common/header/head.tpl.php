<?php header('Content-Type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="ru">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href='<?=$cfg['site_dir']?>css/main.css'>

<link rel="SHORTCUT ICON" href="<?=$cfg['site_dir']?>favicon.ico">
<meta name="viewport" content="width=1200" />
<link href='https://fonts.googleapis.com/css?family=Roboto:400italic,700,700italic,100,400,100italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?=$cfg['site_dir']?>css/jquery.fancybox-1.3.3.css" media="screen" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

<script> $.noConflict();
jQuery( document ).ready(function( $ ) {});
</script> 
<script type="text/javascript" src="<?=$cfg['site_dir']?>js/main.js"></script>
<script type="text/javascript" src="<?=$cfg['site_dir']?>js/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?=$cfg['site_dir']?>js/jquery.fancybox-1.3.3.js"></script>
<script src="<?=$cfg['site_dir']?>js/jquery.maskedinput.min.js"></script>


<script type="text/javascript">
jQuery(document).ready(function() {
    
jQuery(".iframe").fancybox({ // выбор всех ссылок с классом iframe
width : 330,
height : 300,
autoScale:false,
arrows:false,
modal:false,
//showCloseButton:false,
'onComplete': function(links, index ) {
    var parent = jQuery(links[index]);
    var id = parent.attr('id');  
    var className = jQuery('#fancybox-wrap').attr('class');
    if(className) jQuery('#fancybox-wrap').removeClass(className);
    if(id) jQuery('#fancybox-wrap').addClass('fancybox-'+id);
  }
});
});
</script>
	
<title><?=isset($tpl[$tpl['module']]['_Title'])?$tpl[$tpl['module']]['_Title']:$tpl['base']['_Title']?></title>
<meta name="Keywords" content="<?=isset($tpl[$tpl['module']]['_Keywords'])?$tpl[$tpl['module']]['_Keywords']:$tpl['base']['_Keywords']?>" >
<meta name="Description" content="<?=isset( $tpl[$tpl['module']]['_Description'])?$tpl[$tpl['module']]['_Description']:$tpl['base']['_Description']?>">
</head>
<!--onLoad="initMenu();"-->
<body>   
