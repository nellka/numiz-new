
<div class="item_nabor_image" style="display: table;">
    <div class="left">
    <a href='<?=$cfg['site_dir']?>catalognew/<?=$rows['rehref']?>' title='<?=$title?>' class="primage">
    	<?=contentHelper::showImage($rows["image_small_url"],"Каталог - подарочный набор  ".$rows["gname"]." ".$rows["name"],array('alt'=>"Каталог - подарочный набор  ".$rows["gname"]." ".$rows["name"],'folder'=>'catalognew'))?>			
    </a>
    </div>
    <div>    
        <?
         if ($rows['show_in_shop']){?>             
             <br><br><a class="center" href="#" onclick="showWin('http://www.numizmatik.ru/shopcoins/?module=shopcoins&task=showsmall&catalog=<?=$rows["show_in_shop_id"]?>&ajax=1',1100);return false;"><span class=red>В магазине</span></a>
         <?}
         
         echo "<br><br><div id=mysubscribecatalog".$rows["catalog"]." class='mycatalog'>";
        
        if ($rows["in_request"]){
            echo "<b><font color=silver>Заявка принята</font></b>";        
            if ($rows['can_delete_subscribe']){
                echo " [ <a href='#coin".$rows["catalog"]."' onclick=\"deleteSubscribeCatalog(".$rows["catalog"].");return false;\">X</a> ]";
            }   
        } else {
            if (!$rows["show_in_shop"] && $rows['materialtype']!=3) {
                //process('addsubscribecatalog.php?catalog=".$rows["catalog"]."');
                echo "<a href='#coin".$rows["catalog"]."' onclick='addSubscribeCatalog(".$rows["catalog"].");return false;' title='При появлении данного типа монеты ".$rows["gname"]." ".$rows["name"]." в магазине вам будет отправлено уведомление на email...'>Оставить заявку</a>";
            } 
        }
        echo "</div><br><br>";  
        
        
        echo "<div id=mycatalog".$rows["catalog"].">";
    
        if ($rows["in_collection"]) {
            echo "<div class='left'><b><font color=silver>В коллекции  </font></b></div>";
            if ($rows['show_list']) {
                
                echo "<div class='left'>&nbsp;&nbsp;[<a href='#coin".$rows["catalog"]."' onclick=\"deleteMycatalog(".$rows["catalog"].");return false;\" title='Удалить ".$rows["gname"]." ".$rows["name"]." из своей коллекции'> X </a>&nbsp;&nbsp;|&nbsp;&nbsp;</div>";
                echo "<div id=txtcoinchange".$rows["show_list_id"]." class='left'>";
                echo "<a href='#coin".$rows["catalog"]."' onclick=\"ShowForChange(".$rows["show_list_id"].");return false;\" title='Добавить монету ".$rows["gname"]." ".$rows["name"]." в список для обмена'>Обмен&nbsp;&nbsp;</a>]</div> ";?>
                <?
            }
        } else {
            //addmycatalog.php?catalog=".$rows["catalog"]
            echo "<a href='#coin".$rows["catalog"]."' onclick=\"AddMyCatalog(".$rows["catalog"].");return false;\" title='Означает, что у вас есть монета ".$rows["gname"]." ".$rows["name"]." в коллекции'>В коллекцию</a>";
        }
        echo "</div>";
    ?>
    </div>
</div>
<br class="clear">
<div>    
    <a name=coin<?=$rows["shopcoins"]?> title='<?=contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows["gname"])?>'></a>
	<b>Название:</b> <strong><?=$rows['namecoins']?></strong>
	<div id='info'>	
		<?		
		if ($rows["gname"]){
			$groupItemParams = array();	    
			$groupItemParams['materialtype'] = $rows['materialtype'];
			$groupItemParams['group'] = array($rows["group"]=>$rows["gname"]);
			?>		    
		<b>Страна:</b><a href="<?=urlBuild::makePrettyUrl($groupItemParams,"http://www.numizmatik.ru/catalognew")?>" title='Посмотреть все <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?>' alt='Посмотреть все <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?>'>
    	<?=$rows["gname"]?></a>
		<?}

    if(trim($rows["metal"])) echo "<br><strong>Металл: </strong>".$rows["metal"];
    if (trim($rows["probe"])) echo "<br><b>Проба: </b>".$rows["probe"];   
    echo ($rows["year"])?"<br><b>Год:</b>&nbsp;".$rows["year"]:"";
    
    if((1 == $materialtype || 4 == $materialtype)&&trim($rows["condition"])){
          echo "<br><b>Состояние: <font color=blue>".$rows["condition"]."</font></b>";
    }
    if ($rows['theme'])	echo "<br><b>Тематика:</b> ".$rows['theme'];?>

	<?
	
	if ($materialtype == 3) {
        echo ($rows["accessoryProducer"]?"<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
	".($rows["accessoryColors"]?"<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>":"")."
	".($rows["accessorySize"]?"<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>":"");
   }
	if ($rows["details"]){	
    	echo "<br><b>Описание:</b> ".$rows["details"];
    }?>
	</div>		
</div>	
	
