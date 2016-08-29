<?
include(START_PATH."/config.php");
//новинки

$title = contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows['gname']).' - подробная информация';?>
	<div class="center">
	<a href='<?=$cfg['site_dir']?>catalognew/<?=$rows['rehref']?>' title='<?=$title?>' class="borderimage primage">
		<?=contentHelper::showImage($rows["image_small_url"],"Каталог монет - монета  ".$rows["gname"]." ".$rows["name"],array('alt'=>"Каталог монет - монета  ".$rows["gname"]." ".$rows["name"],'folder'=>'catalognew'))?>			
	</a>
	
	</div>		
	
	<div class="coinname">    
	  <a href='<?=$rows['rehref']?>' title='Подробнее о монете <?=$rows["gname"]?> <?=$rows["name"]?>'><h2><?=$rows['namecoins']?></h2></a>
	  <?if ($rows["agreement"]==0){?>
	   - <b><span class="red">Эта монета Непроверена</span>
	  <?}?>
   </div>



<div>	
	
	<?if($rows['group']){
    	echo in_array($rows["materialtype"],array(9,3,5))?"Группа":"Страна"?>:<a class="group_href" href="<?=$cfg['site_dir']?>catalognew/?group=<?=$rows['group']?>&materialtype=<?=$rows['materialtype']?>" title='Посмотреть все <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?>' alt='Посмотреть все <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?>'>
    	<?=$rows["gname"]?>
	</a>
	<?}?>
	<?
	if(1 == $materialtype || 4 == $materialtype) {
      if(trim($rows["metal"])) echo "<br><strong>Металл: </strong>".$rows["mname"];
      if (trim($rows["probe"])) echo "<br><b>Проба: </b>".$rows["probe"];
    }
    if(1 == $materialtype&&($rows["size"]>0)) {
        echo "<br><b>Диаметр: </b>&asymp; ".$rows["size"]." мм.";
    }
    echo ($rows["year"])?"<br><b>Год:</b>&nbsp;".$rows["year"]:"";
    //var_dump($rows);
    if((1 == $materialtype || 4 == $materialtype)&&trim($rows["condition"])){
          echo "<br><b>Состояние: <font color=blue>".$rows["condition"]."</font></b>";
    }
   if ($materialtype == 3) {
        echo ($rows["accessoryProducer"]?"<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
	".($rows["accessoryColors"]?"<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>":"")."
	".($rows["accessorySize"]?"<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>":"");
   }

	if ($rows['theme'])	echo "<br><b>Тематика:</b> ".$rows['theme'];?>
</div>

<?
 if ($rows['show_in_shop']){?>
     
     <a class="qwk center" href="#" onclick="showWin('http://www.numizmatik.ru/shopcoins/?module=shopcoins&task=showsmall&catalog=<?=$rows["show_in_shop_id"]?>&ajax=1',1100);return false;"><span class=red>В магазине</span></a>
     <?//showshopcoins.php?catalog=".$rows["catalog"]."
    //echo "<a href=#coin".$rows["catalog"]." onclick=\"ShowShopcoinsStart('".$rows["catalog"]."');\" title='Посмотреть(купить) ".$rows["gname"]." ".$rows["name"]." в магазине монет'></a>";
 }
 
 echo "<div id=mysubscribecatalog".$rows["catalog"]." class='mycatalog'>";

if ($rows["in_request"]){
    echo "<b><font color=silver>Заявка принята</font></b>";

    if ($rows['materialtype'] == 3) {
        ?>
        <?=date('d-m-Y',$rows["request_date"])?>
        <div class="amount">
            <a href='#coin<?=$rows["catalog"]?>' onclick="addSubscribeCatalog(<?=$rows["catalog"]?>);" title='При появлении данного типа монеты <?=$rows["gname"]?> <?=$rows["name"]?> в магазине вам будет отправлено уведомление на email...'>Изменить заявку</a>
        	<span class="down">-</span>
            <input type=text name=amountscribecatalog<?=$rows["catalog"]?> id=amountscribecatalog<?=$rows["catalog"]?> size=1 value='<?=$rows["shopcoins_subbscribe"]?>'> 
        	<span class="up">+</span>
        	
        	<?if ($rows['can_delete_subscribe']){
                //deletesubscribecatalog.php?catalog=".$rows["catalog"]."')
                echo " [ <a href='#coin".$rows["catalog"]."' onclick=\"deleteSubscribeCatalog(".$rows["catalog"].");\">X</a> ]";
            }?>
        </div>
      <?  
    } elseif ($rows['can_delete_subscribe']){
                //deletesubscribecatalog.php?catalog=".$rows["catalog"]."')
        echo " [ <a href='#coin".$rows["catalog"]."' onclick=\"deleteSubscribeCatalog(".$rows["catalog"].");\">X</a> ]";
    }   
} else {
    if (!$rows["show_in_shop"] && $rows['materialtype']!=3) {
        //process('addsubscribecatalog.php?catalog=".$rows["catalog"]."');
        echo "<a href='#coin".$rows["catalog"]."' onclick='addSubscribeCatalog(".$rows["catalog"].");' title='При появлении данного типа монеты ".$rows["gname"]." ".$rows["name"]." в магазине вам будет отправлено уведомление на email...'>Оставить заявку</a>";
    } elseif ($rows['materialtype'] == 3) { ?>       
        <div class="amount">
            <a href='#coin<?=$rows["catalog"]?>' onclick="addSubscribeCatalog(<?=$rows["catalog"]?>);" title='При появлении данного типа монеты <?=$rows["gname"]?> <?=$rows["name"]?> в магазине вам будет отправлено уведомление на email...'>Оставить заявку</a>
        	<span class="down">-</span>
            <input type=text name=amountscribecatalog<?=$rows["catalog"]?> id=amountscribecatalog<?=$rows["catalog"]?> size=1 value='0'> 
        	<span class="up">+</span>
        </div>
	<!-- <input class=formtxt type=text name=amountscribecatalog".$rows["catalog"]." id=amountscribecatalog".$rows["catalog"]." size=3 value=1>";-->
   <? }    
}
echo "</div>";

if ($rows['materialtype'] != 3) {

    echo "<div id=mycatalog".$rows["catalog"].">";

    if ($rows["in_collection"]) {
        echo "<div class='left'><b><font color=silver>В коллекции  </font></b></div>";
        if ($rows['show_list']) {
            
            echo "<div class='left'>&nbsp;&nbsp;[<a href='#coin".$rows["catalog"]."' onclick=\"deleteMycatalog(".$rows["catalog"].");\" title='Удалить ".$rows["gname"]." ".$rows["name"]." из своей коллекции'> X </a>&nbsp;&nbsp;|&nbsp;&nbsp;</div>";
            echo "<div id=txtcoinchange".$rows["show_list_id"]." class='left'>";
            echo "<a href='#coin".$rows["catalog"]."' onclick=\"ShowForChange(".$rows["show_list_id"].");\" title='Добавить монету ".$rows["gname"]." ".$rows["name"]." в список для обмена'>Обмен&nbsp;&nbsp;</a>]</div> ";?>
            <?
        }
    } else {
        //addmycatalog.php?catalog=".$rows["catalog"]
        echo "<a href='#coin".$rows["catalog"]."' onclick=\"AddMyCatalog(".$rows["catalog"].");\" title='Означает, что у вас есть монета ".$rows["gname"]." ".$rows["name"]." в коллекции'>В коллекцию</a>";
    }
    echo "</div>";
}
if ($rows["details"]){	
	echo "<br><b>Описание:</b> ".$rows["details"];
}
?>