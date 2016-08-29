<div id='news' class="news-cls news-one ">
	<?
    if($tpl['news']['errors']){?>
		<div class="error"><?=implode("<br>",$tpl['news']['errors'])?></div>
	<?} else {?>
    	<div>
    		<h1><?=date('d.m.Y',$tpl['news']['data']['date'])?>. &nbsp;&nbsp;<?=$tpl['news']['data']['name']?></h1>
    	</div>
        
        <?=$tpl['news']['data']['text']?>
        <div style="display: table;    width: 100%;">
            <p class="left"><a href=<?=$cfg['site_dir']?>forum/forumdisplay.php?f=42 title='Обсудить новость нумизматики - <?=htmlspecialchars($tpl['news']['data']["name"])?>'>А что Вы думаете об этом</a>
            </p>            
            <?
            if($tpl['news']['data']['source']){
                if ($tpl['news']['data']['typesource']==1){?>
                	<p align="right" class="right"><noindex>Источник: <a href="<?=$tpl['news']['data']['source']?>" target=_blank><?=$tpl['news']['data']['source']?></a></noindex></p>	
                <?} elseif ($typesource==2) {?>
                	<p align=right class="right"><noindex>Источник: книга <?=$tpl['news']['data']['source']?></noindex></p>	
                <?} elseif ($typesource==3) {?>
                	<p align=right class="right"><noindex>Источник: журнал <?=$tpl['news']['data']['source']?></noindex></p>	
                <?} else {?>
                	<p align=right class="right"><noindex>Источник:<?=$tpl['news']['data']['source']?></noindex></p>		
                <?}
            }?>
       </div>
        <?
        if ($tpl['news']['data']['author']) echo "<p align=right>Автор: ".$tpl['news']['data']['author'];
        if ($tpl['news']['data']['email']) echo "<p align=right>Email: <a href='mailto:".$tpl['news']['data']['email']."'>".$tpl['news']['data']['email']."</a>";
                

}?>
</div>
<?if( $tpl['news']['show_relation']) {	?>

	<h5>Подобные позиции в магазине:</h5>

	<div class="triger-carusel">	
		  <div class="d-carousel">
          <ul class="carousel">
			<?
			foreach ($tpl['news']['show_relation'] as $rows_show_relation2){
				$rows_show_relation2['metal'] = $tpl['metalls'][$rows_show_relation2['metal_id']];
				$rows_show_relation2['condition'] = $tpl['conditions'][$rows_show_relation2['condition_id']];
				?>			
				<li>
    			 <div class="coin_info" id='item<?=$rows_show_relation2['shopcoins']?>'>
					<div id=show<?=$rows_show_relation2['shopcoins']?>></div>
				<?	
				$rows_show_relation2 = array_merge($rows_show_relation2, contentHelper::getRegHref($rows_show_relation2));
				
				 $statuses = $shopcoins_class->getBuyStatus($rows_show_relation2["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);                 $rows_show_relation2['buy_status'] = $statuses['buy_status'];
				 //if($tpl['user']['user_id']==352480){
				    $rows_show_relation2['reserved_status'] = $statuses['reserved_status'];	
				// }		 
				  //$rows_show_relation2['mark'] = $shopcoins_class->getMarks($rows_show_relation2["shopcoins"]);
				
				
				echo contentHelper::render('shopcoins/item/itemmini-carusel',$rows_show_relation2);
				?>	
			 </div>
			</li>
		<?}?>
		</ul>
	</div>
</div>	

<script>
 $(document).ready(function() {    
     $('.d-carousel .carousel').jcarousel({
        scroll: 1,
        itemFallbackDimension: 75
     }); 
  }); 
</script>

<?}

//include $in."socialzakladki.php";
?>

<div id='news' class="news-cls news-one ">
	<?
    if(!$tpl['news']['errors']){     

        if ($tpl['news']['byTheme']) {
        	echo "<p class=txt><b>Новости по теме:</b></p><ul>";       	
        	
        	foreach ($tpl['news']['byTheme'] as $rows ){
        		$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["news"].".html";
        		echo "<p class=txt><li><a href='$namehref' title='Читать новость нумизматики - ".htmlspecialchars($rows["name"])."'>".$rows["name"]."</a></li>";
        	}
        	echo "</ul>";
        }
        echo "<br><br>";
        
        $tpl['news']['byBiblio'] = $news_class->getBiblioByKeywords($keywords,$id);        
      
        if ($tpl['news']['byBiblio']) {
            
        	echo "<p class=txt><b>Статьи по теме:</b></p><ul>";
        	
        	foreach ($tpl['news']['byBiblio'] as $rows) {
        		$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["biblio"].".html";
        		echo "<p class=txt><li><a href='".$cfg['site_dir']."biblio/$namehref' title='Читать статью о нумизматике - ".$rows['name']."'>".$rows['name']."</a></li>";
        	}
        	echo "</ul>";
        }
	}?>
	
</div>
