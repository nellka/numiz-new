<?php include $cfg['path'] . '/views/common/header/head.tpl.php'; ?>
<div class="bg_shadow"></div>
<div id="page" class="container">
    <?php include $cfg['path'] . '/views/common/header.tpl.php'; ?>   
   
        
    <div class="clearfix" id='content-<?=$tpl['module']?>'>
        <?php        
        if($tpl['module']=='shopcoins'){
            if($tpl['task']=='show'){?>
                <div class="subheader">
        			<div class="wraper clearfix">  
        			     <?php include $cfg['path'] . '/views/shopcoins/itemtop.tpl.php'; ?>
        			      <? include $cfg['path'] . '/views/' . $tpl['module'] . '.tpl.php'; ?>	
        			
        			    </div> 
        		    </div>       
           <? } else {
            ?>
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
       } else if($static_page){?>
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
</div>     
</body>
</html>
   


