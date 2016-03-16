<?php header('Content-Type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="ru">
<head>
<title><?=isset($tpl[$tpl['module']]['_Title'])?$tpl[$tpl['module']]['_Title']:$tpl['base']['_Title']?></title>
<meta name="Keywords" content="<?=isset($tpl[$tpl['module']]['_Keywords'])?$tpl[$tpl['module']]['_Keywords']:$tpl['base']['_Keywords']?>" >
<meta name="Description" content="<?=isset( $tpl[$tpl['module']]['_Description'])?$tpl[$tpl['module']]['_Description']:$tpl['base']['_Description']?>">

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


<?
if(!in_array($tpl["task"],array('login_order','registration','login','remind'))){
?>
<script src="<?=$cfg['site_dir']?>js/jquery.cookie.js"></script>
<script src= "<?=$cfg['site_dir']?>js/jquery.mousewheel.min.js" type= "text/javascript"></script> 
<link href="<?=$cfg['site_dir']?>css/jquery.mCustomScrollbar.css" rel="stylesheet" type= "text/css"/>
<script src="<?=$cfg['site_dir']?>js/jquery.easing.1.3.js" type= "text/javascript"></script>
<script src="<?=$cfg['site_dir']?>js/jquery.mCustomScrollbar.js" type="text/javascript"></script>
<script src="<?=$cfg['site_dir']?>js/jquery.maskedinput.min.js"></script>
<script src="<?=$cfg['site_dir']?>js/shopcoins.js"></script>
<script type="text/javascript" src="<?=$cfg['site_dir']?>js/jquery.jcarousel.js"></script>
<?}
if( $tpl['is_mobile']){?>
   <script src="<?=$cfg['site_dir']?>js/jquery.ui.touch-punch.min.js"></script> 
<?}?>
<script type="text/javascript">

$(document).ready(function() {
    
    $('body').on("click", ".ui-widget-overlay", function() {
          $(".ui-icon.ui-icon-closethick").trigger("click");
    }); 
    
	$(window).on('scroll', function(){
	//animations will be smoother
	//window.requestAnimationFrame(animateIntro);
		if($(document).scrollTop()>10){
			setMini(1);
		} else {
			console.log($(document).scrollTop());
			setMini(0);			
		}
		
	});
    site_dir = '<?=$cfg['site_dir']?>';
    $('.iframe').click(function () {    
        showOn(this.href,this.id);
        return false; ////cancel eventbubbeling
    });
    $('.image_block').mouseover(function() {
       $(this).parent().find('.imageBig').show();
    });
    $('.image_block').mouseout(function(){
         $(this).parent().find('.imageBig').hide();    
    });    
     /*   
    $('#header-mini #search').autocomplete({
      source: 'shopcoins/index.php?search=1',
      minLength:3,
      select: function (event, ui) {
          console.log(ui.item);
            window.location = ui.item.href;
            return ui.item.label;
        }
    }).data( "autocomplete" )._renderItem = function( ul, item ) {

        return $( "<li class='search-ayax'></li>" )
            .data( "item.autocomplete", item )
            .append( "<a href='"+item.href+"'>" + item.image+ " <span> " + item.label+ "</span></a>" )
            .appendTo( ul );
    };*/
    $('#header #search').autocomplete({
      source: 'shopcoins/index.php?search=1',
      minLength:3,
      select: function (event, ui) {
          console.log(ui.item);
            window.location = ui.item.href;
            return ui.item.label;
        }
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
         console.log(item);
        return $( "<li class='search-ayax'></li>" )
            .data( "item.autocomplete", item )
            .append( "<a href='"+item.href+"'>" + item.image+ " <span> " + item.label+ "</span></a>" )
            .appendTo( ul );
    };
    
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
</script>


</head>
<!--onLoad="initMenu();"-->
<body>   
