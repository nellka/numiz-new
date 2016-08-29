<?php
if($tpl['module']=='seo'){
    include $cfg['path'] . '/views/seo/index.tpl.php';
    die();
}


header('Content-Type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="ru">
<head>
<title><?=isset($tpl[$tpl['module']]['_Title'])?$tpl[$tpl['module']]['_Title']:$tpl['base']['_Title']?></title>
<meta name="Keywords" content="<?=isset($tpl[$tpl['module']]['_Keywords'])?$tpl[$tpl['module']]['_Keywords']:$tpl['base']['_Keywords']?>" >
<meta name="Description" content="<?=isset( $tpl[$tpl['module']]['_Description'])?$tpl[$tpl['module']]['_Description']:$tpl['base']['_Description']?>">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href='<?=$cfg['site_dir']?>css/mobile.css'>

<link rel="SHORTCUT ICON" href="<?=$cfg['site_dir']?>favicon.ico">
<meta content="width=415" name="viewport">
<link href='https://fonts.googleapis.com/css?family=Roboto:400italic,700,700italic,100,400,100italic' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" ></script>
<script src="<?=$cfg['site_dir']?>js/shopcoins.js"></script>
<link rel="stylesheet" type="text/css" href="<?=$cfg['site_dir']?>css/jqueryui.custom.css" media="screen" />

<?
if(!in_array($tpl["task"],array('login_order','registration','login','remind'))){
?>
<script src="<?=$cfg['site_dir']?>js/jquery.cookie.js"></script>
<script src= "<?=$cfg['site_dir']?>js/jquery.mousewheel.min.js" type= "text/javascript"></script> 
<link href="<?=$cfg['site_dir']?>css/jquery.mCustomScrollbar.css" rel="stylesheet" type= "text/css"/>
<script src="<?=$cfg['site_dir']?>js/jquery.easing.1.3.js" type= "text/javascript"></script>
<script src="<?=$cfg['site_dir']?>js/jquery.mCustomScrollbar.js" type="text/javascript"></script>
<script src="<?=$cfg['site_dir']?>js/jquery.maskedinput.min.js"></script>

<script type="text/javascript" src="<?=$cfg['site_dir']?>js/jquery.jcarousel.js"></script>
<?}?>
   <script src="<?=$cfg['site_dir']?>js/jquery.ui.touch-punch.min.js"></script> 

<script type="text/javascript">

$(document).ready(function() {
    site_dir = '<?=$cfg['site_dir']?>';
    $('#searchblock #search').autocomplete({
      source: 'shopcoins/index.php?search=1',
      minLength:3,
      select: function (event, ui) {
            window.location = ui.item.href;
            return ui.item.label;
        }
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li class='search-ayax'></li>" )
            .data( "item.autocomplete", item )
            .append( "<a href='"+item.href+"'>" + item.image+ " <span> " + item.label+ "</span></a>" )
            .appendTo( ul );
    };
    
    $(window).on('scroll', function(){
	//animations will be smoother
	//window.requestAnimationFrame(animateIntro);
		if($(document).scrollTop()>10){
			$('#logoblock').hide();
		} else {
			$('#logoblock').show();		
		}		
	});
	
    /*
    $('body').on("click", ".ui-widget-overlay", function() {
          $(".ui-icon.ui-icon-closethick").trigger("click");
    }); 
    
	
    
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
     
  */
    
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
	
	$("#fb-groups #group_name").keyup(function(){
        var vl = $(this).val();
        if (vl.length >= 3) {
            fgroup();                 
        } else {            
            clear_filter('group_name',1);
        }
    });
});
</script>


</head>
<body>   

<div class="bg_shadow"></div>
<div class="container"> 
<?php include $cfg['path'] . '/views/_mobile/common/header.tpl.php';  ?>    
    <div class="clearfix content" id='content-<?=$tpl['module']?>'>
        <?php      

        if($tpl['module']=='shopcoins'||$tpl['module']=='order'){
           if(in_array($tpl['task'],array('show','catalog_search'))||$tpl['module']=='order'){?>
    			<div class="clearfix">  
			     <?php
	            if(file_exists($cfg['path'] . '/views/_mobile/pagetop/'.$tpl['task'].'.tpl.php')){
			         include $cfg['path'] . '/views/_mobile/pagetop/'.$tpl['task'].'.tpl.php'; 
                } else include $cfg['path'] . '/views/_mobile/pagetop/top.tpl.php'; 
                ?>
			    </div> 
        		<? include $cfg['path'] . '/views/_mobile/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php'; ?>    
           <?} else {?>
			  
		    <div class="subheader">
			<div class="clearfix">
			
			        <div id='leftmemu'>
			         <?php include $cfg['path'] . '/views/_mobile/pagetop/shortmenu.tpl.php'; ?>
			        </div>
			        <div id='subheader-body'>
			            <?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
			            <? include $cfg['path'] . '/views/_mobile/' . $tpl['module'] . '.tpl.php'; ?>		            
			        </div>
			    </div>
			 
		    </div>               
       <? }
       } else if($static_page){?>
			<div class="subheader">
			<div class="clearfix">			        
			        <div id='subheader-body'>
			            <?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
			            <? include $cfg['path'] . '/views/static_pages/' . $tpl['module'] . '.tpl.php'; ?>		            
			        </div>
			    </div> 
		    </div>         
       <? } else { ?>  
	       <div class="clearfix">		            
	           
	            <? 
	            if(file_exists($cfg['path'] . '/views/_mobile/pagetop/'.$tpl['module'].'.tpl.php')){
			         include $cfg['path'] . '/views/_mobile/pagetop/'.$tpl['module'].'.tpl.php'; 
                } else {?>
                	 <div id='subheader-body'>
		                <?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
		            </div>
                <?}
                include $cfg['path'] . '/views/_mobile/view_construct.tpl.php'; 
                /*
	            if($tpl['is_mobile']&&file_exists($cfg['path'].'/views/_mobile/'.$tpl['module'].'.tpl.php')){?>
	                
			        <? require_once $cfg['path'] .  '/views/_mobile/'.$tpl['module'].'.tpl.php';?>			        
			    <?} else {
			        require_once $cfg['path'] .  '/views/'.$tpl['module'].'.tpl.php';
			    }*/?>		            
	        </div>            
       <? }        
        ?> 
    </div>       
    
    <?php include $cfg['path'] . '/views/_mobile/common/bottom.tpl.php'; ?>    
</div>     
</body>
</html>
   


