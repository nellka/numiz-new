<div id='news' class="news-cls news-one ">

	<?
    if($tpl['news']['errors']){?>
		<div class="error"><?=implode("<br>",$tpl['news']['errors'])?></div>
	<?} else {?>
	<div itemscope itemtype="http://schema.org/Article">
	<meta itemprop="datePublished" content="<?=date('Y-m-d',$rows["date"])?>">
	<meta itemprop="dateModified" content="<?=date('Y-m-d',$rows["date"])?>">
	<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?=$rows['namehref']?>"/>
	<?
	if($tpl['news']['data']["group_title"]){?>
		<meta itemprop="articleSection"content="<?=$tpl['news']['data']["group_title"]?>">	
		<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
		     <meta itemprop="name" content="Нумизматик Нумизмат Нумизматович ">  
		     <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
			     <img itemprop="url image" src="http://numizmatik.ru/images/numizmatik.gif" class="hidden"/>
		         <meta itemprop="width" content="200">
		         <meta itemprop="height" content="44">
	    	 </div>           
        </div>
	<?}?>
    	<div>
    		<h1><?=date('d.m.Y',$tpl['news']['data']['date'])?>. &nbsp;&nbsp;<span itemprop="headline"><?=$tpl['news']['data']['name']?></span></h1>
    	</div>
        <div  itemprop="articleBody">
        <?=$tpl['news']['data']['text']?>
        </div>
        <div style="display: table;    width: 100%;">
            <p class="left"><a href=<?=$cfg['site_dir']?>forum/forumdisplay.php?f=42 title='Обсудить новость нумизматики - <?=htmlspecialchars($tpl['news']['data']["name"])?>'>А что Вы думаете об этом</a>
            </p>            
            <?
            if($tpl['news']['data']['source']){
                if ($tpl['news']['data']['typesource']==1){?>
                	<p align="right" class="right" itemprop="author">Источник: <noindex><a href="<?=$tpl['news']['data']['source']?>" target="_blank" rel="nofollow"><?=$tpl['news']['data']['source']?></a></noindex></p>	
                <?} elseif ($typesource==2) {?>
                	<p align=right class="right" itemprop="author">Источник: книга <noindex><?=$tpl['news']['data']['source']?></noindex></p>	
                <?} elseif ($typesource==3) {?>
                	<p align=right class="right" itemprop="author">Источник: журнал <noindex><?=$tpl['news']['data']['source']?></noindex></p>	
                <?} else {?>
                	<p align=right class="right" itemprop="author">Источник:<noindex><?=$tpl['news']['data']['source']?></noindex></p>		
                <?}
            }?>
       </div>
        <?
        if ($tpl['news']['data']['author']) echo "<p align=right>Автор: ".$tpl['news']['data']['author'];
        if ($tpl['news']['data']['email']) echo "<p align=right>Email: <a href='mailto:".$tpl['news']['data']['email']."'>".$tpl['news']['data']['email']."</a>";?>
       </div>     

<?}?>

	<?

    if(!$tpl['news']['errors']){     
        if ($tpl['news']['linked']) {
        	echo "<p class=txt><b>Новости по теме:</b></p><ul>";    
        	   	
        	foreach ($tpl['news']['linked'] as $rows ){
        		$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["news"].".html";
        		echo "<p class=txt><li><a href=\"".$cfg['site_dir']."news/$namehref"."\"' title='Читать новость нумизматики - ".htmlspecialchars($rows["name"])."'>".$rows["name"]."</a></li>";
        	}        	
        	echo "</ul>";
        }
        echo "<br><br>";
        
       /* $tpl['news']['byBiblio'] = $news_class->getBiblioByKeywords($keywords,$id);        
      
        if ($tpl['news']['byBiblio']) {
            
        	echo "<p class=txt><b>Статьи по теме:</b></p><ul>";
        	
        	foreach ($tpl['news']['byBiblio'] as $rows) {
        		$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["biblio"].".html";
        		echo "<p class=txt><li><a href='".$cfg['site_dir']."biblio/$namehref' title='Читать статью о нумизматике - ".$rows['name']."'>".$rows['name']."</a></li>";
        	}
        	echo "</ul>";
        }*/
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
    			 <div class="coin_info" id='item<?=$rows_show_relation2['shopcoins']?>' itemscope itemtype="http://schema.org/Product">
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


