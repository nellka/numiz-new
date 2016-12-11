<?

require_once($cfg['path'] . '/configs/config_shopcoins.php');

require($cfg['path'].'/helpers/Paginator.php');

require_once $cfg['path'] . '/models/stats.php';
$stats_class = new stats($db_class,$tpl['user']['user_id'],session_id());

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

$per_page = 14;

switch ($tpl['task']){    
    case 'coins':{
        $title = "Последние просматриваемые монеты";
        
        $per_page = 4;
        
        if($tpl['user']['user_id']) { 
        	$count = $cache->load("coinscount_user_".$tpl['user']['user_id']);   
        } else   $count = $cache->load("coinscount_ses_".session_id());       
        
       $tpl['catalog']['lastViewsStat'] = array();	
       $lastCoinsIds = $stats_class->getlastCoinsIds($tpl['pagenum']);
        
        if($lastCoinsIds){
        	$d = array();
        	$d_order = array();
        	foreach ($lastCoinsIds as $id){
        		$row = $shopcoins_class->getItem($id,true);
                $row['condition'] = $tpl['conditions'][$row['condition_id']];
        	    $row['metal'] = $tpl['metalls'][$row['metal_id']];
                $d_order[] =  array_merge($row, contentHelper::getRegHref($row));                
            }    
            $tpl['catalog']['lastViewsStat'] = $d_order;
        }
        
        break;
    }
    case 'filter':{
        if($tpl['user']['user_id']) { 
        	$count = $cache->load("filterscount_user_".$tpl['user']['user_id']);   
        } else   $count = $cache->load("filterscount_ses_".session_id());       
        
    	$title = "Последние просматриваемые страны и номиналы";
    	$lastFilters = $stats_class->getlastFilters($tpl['pagenum']);
        break;
    }
    
    case 'search':{
         if($tpl['user']['user_id']) { 
        	$count = $cache->load("searchcount_user_".$tpl['user']['user_id']);   
        } else   $count = $cache->load("searchcount_ses_".session_id());
        $per_page = 28;
        $lastSearchs = $stats_class->getlastSearch($tpl['pagenum'],28);
    	$title = "Последние поисковые запросы";
        break;
    }   
}

$tpl['paginator-coins'] = new Paginator(array(
                'url'        => $cfg['site_dir'].'shopcoins/?module=hstat&task='.$tpl['task'],
                'count'      => $count,
                'per_page'   => $per_page,
                'onclick'    => 'loadTab($(this));return false;',
                'page'       => $tpl['pagenum'],
                'border'     =>2));
?>
<div class='s-header'>
    <h5 class="left"><?=$title?></h5>
    <div class="pages">
        <?php echo $tpl['paginator-coins']->printPager(); ?>
    </div>
</div>

<?	


switch ($tpl['task']){    
    case 'coins':{
        $title = "Последние просматриваемые монеты";?>            
            
        <div class="tab_coins">   
            <?
            foreach ($tpl['catalog']['lastViewsStat'] as $rows_show_relation2){
                $rows_show_relation2['metal'] = $tpl['metalls'][$rows_show_relation2['metal_id']];
                $rows_show_relation2['condition'] = $tpl['conditions'][$rows_show_relation2['condition_id']];
                ?>			
                
                <div class="coin_info-mini left " id='item<?=$rows_show_relation2['shopcoins']?>'>
                    <div id=show<?=$rows_show_relation2['shopcoins']?>></div>
                    <?	
                    $statuses = $shopcoins_class->getBuyStatus($rows_show_relation2["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
                    $rows_show_relation2['buy_status'] = $statuses['buy_status'];
                    $rows_show_relation2['reserved_status'] = $statuses['reserved_status'];	
                    $rows_show_relation2['mark'] = $shopcoins_class->getMarks($rows_show_relation2["shopcoins"]);
                    echo contentHelper::render('shopcoins/item/itemmini-carusel',$rows_show_relation2);
                    ?>				
                </div>
            <?}?>
        </div>
        <?break;
    }
    case 'filter':{?>
    	<div class="tab_filter">   </div>
    	<? foreach ($lastFilters as $row){
    	    $href_array = urlBuild::makePrettyOfferUrl($row,$materialIDsRule,$ThemeArray,$tpl,$shopcoins_class,array('full'=>true));
    	    ?>
           <div> 
           <span><?=date('Y-m-d H:i',$row['datetime'])?></span>
           <a href="<?=$href_array['href']?>"><?=$href_array['title']?></a>
           </div>
        <?}?>            
        <?break;
    }
    
    case 'search':{?>
    	<div class="tab_search">  
            <?
            $i = 0;
            foreach ($lastSearchs as $row){
                if($i==0) echo "<div class='left'>";
                ?>
               <div> 
               <span><?=date('Y-m-d H:i',$row['datetime'])?></span>
               <a href="http://www.numizmatik.ru/shopcoins/index.php?search=<?=$row["search"]?>"><?=$row["search"]?></a>
               </div>

            <?
                if($i==14){
                    echo "</div><div>";
                }
            $i++;
            }
            ?>
            </div>
    	</div>
        <?break;
    }   
}
die();
?>
