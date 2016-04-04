<?php include $cfg['path'] . '/views/common/header/head.tpl.php'; ?>
<div class="bg_shadow"></div>
<div id="page" class="container">
    <?php include $cfg['path'] . '/views/common/header.tpl.php'; ?>   
   
        
    <div class="clearfix" id='content-<?=$tpl['module']?>'>
        <?php      

        if($tpl['module']=='shopcoins'||$tpl['module']=='order'){
            if(in_array($tpl['task'],array('show','catalog_search','viporder'))||$tpl['module']=='order'){?>
    			<div class="wraper clearfix">  
			     <?php
                if(file_exists($cfg['path'] . '/views/pagetop/'.$tpl['task'].'.tpl.php')){
			         include $cfg['path'] . '/views/pagetop/'.$tpl['task'].'.tpl.php'; 
                } else include $cfg['path'] . '/views/pagetop/top.tpl.php'; 
                ?>
			    </div> 
        		<? include $cfg['path'] . '/views/' . $tpl['module'] . '/'.$tpl['task'].'.tpl.php'; ?>    
           <? /*} else   if($tpl['task']=='orderdetails'){?>
    			<div class="wraper clearfix">  
			     <?php include $cfg['path'] . '/views/shopcoins/orderdetails.tpl.php'; ?>
			    </div> 
        		<? include $cfg['path'] . '/views/' . $tpl['module'] . '.tpl.php'; ?>    
           <? } elseif($tpl['task']=='catalog_search'){?>
    			<div class="wraper clearfix">  
			     <?php include $cfg['path'] . '/views/shopcoins/topsearch.tpl.php'; ?>
			    </div> 
        		<? include $cfg['path'] . '/views/' . $tpl['module'] . '.tpl.php'; ?>    
           <?*/ 
            } else {
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
</body>
</html>
   


