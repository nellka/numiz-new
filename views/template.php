<?php include $cfg['path'] . '/views/common/header/head.tpl.php'; ?>

<div id="page" class="container">
    <?php include $cfg['path'] . '/views/common/header.tpl.php'; ?>   
   
        
    <div class="clearfix" id='content-<?=$tpl['module']?>'>
        <?php 
        
        
        if($static_page){?>
			<div class="wraper clearfix">
		        <div id='leftmemu'>
		         <?php include $cfg['path'] . '/views/leftmenu/leftmenu_index.tpl.php'; ?>
		        </div>
		        <div id='subheader-body'>
		            <?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
		            <? include $cfg['path'] . '/views/static_pages/' . $tpl['module'] . '.tpl.php'; ?>		            
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
</div>     
</body>
</html>
   


