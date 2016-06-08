<div id='news' class="news-cls news-one ">
	<?
    if($tpl['news']['errors']){?>
		<div class="error"><?=implode("<br>",$tpl['news']['errors'])?></div>
	<?} else {?>
    	<div>
    		<h1><?=date('d.m.Y',$tpl['news']['data']['date'])?>. &nbsp;&nbsp;<?=$tpl['news']['data']['name']?></h1>
    	</div>
        
        <?=$tpl['news']['data']['text']?>
        
        <?
        if($tpl['news']['data']['source']){
            if ($tpl['news']['data']['typesource']==1){?>
            	<p align=right><noindex>Источник: <a href="<?=$tpl['news']['data']['source']?>" target=_blank><?=$tpl['news']['data']['source']?></a></noindex></p>	
            <?} elseif ($typesource==2) {?>
            	<p align=right><noindex>Источник: книга <?=$tpl['news']['data']['source']?></noindex></p>	
            <?} elseif ($typesource==3) {?>
            	<p align=right><noindex>Источник: журнал <?=$tpl['news']['data']['source']?></noindex></p>	
            <?} else {?>
            	<p align=right><noindex>Источник:<?=$tpl['news']['data']['source']?></noindex></p>		
            <?}
        }?>
        <?
        if ($tpl['news']['data']['author']) echo "<p align=right>Автор: ".$tpl['news']['data']['author'];
        if ($tpl['news']['data']['email']) echo "<p align=right>Email: <a href='mailto:".$tpl['news']['data']['email']."'>".$tpl['news']['data']['email']."</a>";
        
        echo "<br>";
        echo "<a href=".$cfg['site_dir']."forum/forumdisplay.php?f=42 title='Обсудить новость нумизматики - ".htmlspecialchars($tpl['news']['data']["name"])."'>А что Вы думаете об этом</a><br><br>";
        

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
}
//include $in."socialzakladki.php";
?>
</div>