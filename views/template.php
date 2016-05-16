<?php include $cfg['path'] . '/views/common/header/head.tpl.php'; ?>
<div class="bg_shadow"></div>
<div id="page" class="container">
    <?php include $cfg['path'] . '/views/common/header.tpl.php'; ?>   
   
        
    <div class="clearfix" id='content-<?=$tpl['module']?>'>
        <?php     

        if($tpl['module']=='shopcoins'||$tpl['module']=='order'){
           if(in_array($tpl['task'],array('show','catalog_search','viporder'))||$tpl['module']=='order'){ ?>
    			<div class="wraper clearfix">  
			     <?php
                if(file_exists($cfg['path'] . '/views/pagetop/'.$tpl['task'].'.tpl.php')){
			         include $cfg['path'] . '/views/pagetop/'.$tpl['task'].'.tpl.php'; 
                } else include $cfg['path'] . '/views/pagetop/top.tpl.php'; 
                ?>
			    </div> 
        		<? include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php'; ?>    
           <? } else {        ?>
			<div class="subheader">
				<div class="wraper clearfix">
				   <div id='leftmemu'>
			         <?php include $cfg['path'] . '/views/leftmenu/leftmenu_shopcoins.tpl.php'; ?>
			        </div>
			        <div id='subheader-body'>
			            <?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
			            <? include $cfg['path'] . '/views/' . $tpl['module'] . '.tpl.php'; ?>		            
			        </div>
				</div> 
		    </div>         
       <? }
       }  else if($static_page){?>
			 <div class="subheader">
			<div class="wraper clearfix">
			        <div id='leftmemu'>
			         <?php include $cfg['path'] . '/views/leftmenu/leftmenu_index.tpl.php'; ?>
			        </div>
			        <div id='subheader-body'>
			            <?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
			            <? include $cfg['path'] . '/views/static_pages/' . $tpl['module'] . '.tpl.php'; ?>		            
			        </div>
			    </div> 
		    </div>         
       <? } else {   ?>  
        	  <div class="subheader">
		       <div class="wraper clearfix">
		            <div id='leftmemu'>
		             <?php include $cfg['path'] . '/views/leftmenu/leftmenu_'.$tpl['module'].'.tpl.php'; ?>
		            </div>
		            <div id='subheader-body'>
		                <?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
		                <h2> Магазин монет клуба Нумизмат </h2>  
		                <div id='slider'>                
		                    <?php include $cfg['path'] . '/views/common/header/slider.tpl.php'; ?>                          
		                </div>
		            </div>
		        </div>
		    </div>
    <?     
            include $cfg['path'] . '/views/' . $tpl['module'] . '.tpl.php';             
        }        
        ?> 
    </div>       
    
    <?php include $cfg['path'] . '/views/common/bottom.tpl.php'; ?>    
    <a id="toTop" class="toTop scroll" title="Наверх" href="#page" style="display: none;">
    <span class="toTop_inner">
    <span>Наверх</span>
    </span>
    </a>
    
    <a id="toTopLeft" class="toTopLeft scroll" title="Наверх" href="#page" style="display: none;">
    <span class="toTop_inner">
    <span>Наверх</span>
    </span>
    </a>
</div>  


<link rel=stylesheet type=text/css href='<?=$cfg['site_dir']?>css/main.css'>
<link href='https://fonts.googleapis.com/css?family=Roboto:400italic,700,700italic,100,400,100italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?=$cfg['site_dir']?>css/jqueryui.custom.css" media="screen" />

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
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
		if($(document).scrollTop()>10){
			setMini(1,true);
			$('#toTop').show();
			$('#toTopLeft').show();
		} else {			
			setMini(0,true);	
			$('#toTop').hide();	
			$('#toTopLeft').hide();		
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
    };
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
</body>
</html>
   


