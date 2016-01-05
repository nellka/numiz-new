<?php header('Content-Type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="ru">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href='<?=$cfg['site_dir']?>css/main.css'>

<link rel="SHORTCUT ICON" href="<?=$cfg['site_dir']?>favicon.ico">
<meta name="viewport" content="width=1200" />
<link href='https://fonts.googleapis.com/css?family=Roboto:400italic,700,700italic,100,400,100italic' rel='stylesheet' type='text/css'>
<!--<link rel="stylesheet" type="text/css" href="<?=$cfg['site_dir']?>css/jquery.fancybox-1.3.3.css" media="screen" />-->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />-->
<link rel="stylesheet" type="text/css" href="<?=$cfg['site_dir']?>css/jqueryui.custom.css" media="screen" />
<script> /*$.noConflict();
jQuery( document ).ready(function( $ ) {});*/
</script> 
<!--<script type="text/javascript" src="<?=$cfg['site_dir']?>js/main.js"></script>
<script type="text/javascript" src="<?=$cfg['site_dir']?>js/jquery.mousewheel-3.0.4.pack.js"></script>
<!--<script type="text/javascript" src="<?=$cfg['site_dir']?>js/jquery.fancybox-1.3.3.js"></script>-->

<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type= "text/javascript"></script>
<script src="<?=$cfg['site_dir']?>js/jquery.cookie.js"></script>
<script src= "<?=$cfg['site_dir']?>js/jquery.mousewheel.min.js" type= "text/javascript"></script> 
<link href="<?=$cfg['site_dir']?>css/jquery.mCustomScrollbar.css" rel="stylesheet" type= "text/css"/>
<script src="<?=$cfg['site_dir']?>js/jquery.easing.1.3.js" type= "text/javascript"></script>
<script src="<?=$cfg['site_dir']?>js/jquery.mCustomScrollbar.js" type="text/javascript"></script>
<script src="<?=$cfg['site_dir']?>js/jquery.maskedinput.min.js"></script>
<script src="<?=$cfg['site_dir']?>js/shopcoins.js"></script>
<script type="text/javascript">

$(document).ready(function() {
    site_dir = '<?=$cfg['site_dir']?>';
    $('.iframe').click(function () {        
        href = this.href;
        showOn(href);
        return false; ////cancel eventbubbeling
    });
    
    $('.search-top-module #search').autocomplete({
      source: 'shopcoins/index.php?search=1',
      minLength:3,
      select: function (event, ui) {
            window.location = ui.item.href;
            return ui.item.label;
        }
    });

    $('.down').click(function () {
		var $input = $(this).parent().find('input[type=text]');
		var $amountall = $(this).parent().find('input[type=hidden]');
		var count = parseInt($input.val()) - 1;
		count = count < 1 ? 1 : count;
		$input.val(count);
		$input.change();
		return false;
	});
	$('.up').click(function () {
		var $input = $(this).parent().find('input[type=text]');
		var $amountall = $(this).parent().find('input[type=hidden]');
		count = parseInt($input.val()) + 1;
		count = count > $amountall.val() ? $amountall.val() : count;
		$input.val(count);
		$input.change();
		return false;
	});
});


function showOn(href){
     $('#MainBascet').dialog({
        modal: true,
        open: function (){
            $(this).load(href);
             $('.ui-dialog-titlebar-close').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only');
$('.ui-dialog-titlebar-close').append('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">close</span>');
        },
        height: 330, 
        width: 400
    });
     return false;   
} 
</script>
	
<title><?=isset($tpl[$tpl['module']]['_Title'])?$tpl[$tpl['module']]['_Title']:$tpl['base']['_Title']?></title>
<meta name="Keywords" content="<?=isset($tpl[$tpl['module']]['_Keywords'])?$tpl[$tpl['module']]['_Keywords']:$tpl['base']['_Keywords']?>" >
<meta name="Description" content="<?=isset( $tpl[$tpl['module']]['_Description'])?$tpl[$tpl['module']]['_Description']:$tpl['base']['_Description']?>">
</head>
<!--onLoad="initMenu();"-->
<body>   
