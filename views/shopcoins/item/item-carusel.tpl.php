<div style="width:600px;display: inline-block;vertical-align:top;margin-top:10px;">
            <?include($cfg['path'].'/views/shopcoins/item/imageBig.tpl.php');?>
    </div>
    <div style="width:200px;display: inline-block;padding-left:50px;">
        <h1><?=$rows_main["name"]?></h1>       
  
    <div id='review-block'>
	  <?php	if ($tpl['user']['user_id']) {   ?> 
			<a name=addreview href="#" onclick="showReviewForm();return false;">Написать отзыв</a> 
    			<div id='reviewcoin' style="display:none">
    			    <form name='reviewcoin'>        			
        			<h1 class="yell_b">Написать отзыв</h1>
        			<div id='error-review' class="error"></div>
        			<div><b>Пользователь: <br><input type=text name=fio value='<?=$tpl['user']['username']?>' size=40 maxlength=150 disabled></b></div>
        			<div><b>Отзыв:</b><br>
        			<textarea name=reviewcointext id=reviewcointext cols=40 rows=10 ></textarea></div>
        			<div class="web-form"><input type=button class="yell_b" value='Оставить отзыв' onclick="AddReview('<?=$catalog?>');"></div>
        			</form>
    			</div>
			
		<?} else {?>
		   
		    <div id='reviewcoin' style="display:none">
		     <h1 class="yell_b">Написать отзыв</h1>
        	<div id='error-review' class="error">Отзывы могут оставлять только зарегистрированные пользователи!</div>
        	</div>
		<?}?>
		</div>
     <?
	if ($rows_main["gname"]){?>
	<?=in_array($rows_main["materialtype"],array(9,3,5))?"Группа":"Страна"?>: 
	<a href=<?=$cfg['site_dir']?>/shopcoins?group=<?=$rows_main['group']?>&materialtype=<?=$rows_main["materialtype"]?> title='Посмотреть <?=contentHelper::setWordThat($rows_main["materialtype"])?> <?=$rows_main["gname"]?>'>
	<strong><font color=blue><?=$rows_main["gname"]?></font></strong>
	</a><br>
	<?}?>
	<?= ($rows_main["year"]?"Год: <strong>".$rows_main["year"]."</strong><br>":"")?>
	<?= (trim($rows_main["metal"])?"Металл: <strong>".$rows_main["metal"]."</strong><br>":"")?>
	<?=(trim($rows_main["condition"])?"Состояние: <strong><font color=blue>".$rows_main["condition"]."</font></strong>":"")?>
        

    
    <div id=subinfo>
Название: <strong><?=$rows_main["name"]?></strong><br>
Номер: <strong><?=$rows_main["number"]?></strong><br>
<?
echo ($rows_main["width"]&&$rows_main["height"]?"<br>Приблизительный размер: <strong>".$rows_main["width"]."*".$rows_main["height"]." мм.</strong>":"")."
".($rows_main["weight"]>0?"<br>Вес: <strong>".$rows_main["weight"]." гр.</strong>":"")."
".($rows_main["series"]&&$group?"<br>Серия монет: <a href=$script?series=".$rows_main["series"]."&group=".$group."&materialtype=".$materialtype.">".$series_name[$rows_main["series"]]."</a>":"");
//($rows_main["materialtype"]==8&&$materialtype==1&&!$mycoins?"<br><font color=red>МОНЕТА С РАЗДЕЛА МЕЛОЧЬ, см. условия покупки в разделе</font>":"")."
if($materialtype==5){
	if (trim($rows_main["accessoryProducer"])) echo "<br><b>ISBN: </b>".$rows_main["accessoryProducer"];
	if ($rows_main["accessoryColors"])  echo "<br><b>Год выпуска: </b>".$rows_main["accessoryColors"];
	if ($rows_main["accessorySize"])  echo "<br><b>Количество страниц: <font color=blue>".$rows_main["accessorySize"]."</font></b>";
} else {
	if($rows_main["accessoryProducer"]) echo "<br>Производитель:<strong> ".$rows_main["accessoryProducer"]."</strong>";
	if($rows_main["accessoryColors"]) echo "<br>Цвета:<strong> ".$rows_main["accessoryColors"]."</strong>";
	if($rows_main["accessorySize"]) echo "<br>Размеры:<strong> ".$rows_main["accessorySize"]."</strong>";
}
		
if (sizeof($tpl['show']['shopcoinstheme']))	$details .= "<br>Тематика: <strong>".implode(", ", $tpl['show']['shopcoinstheme'])."</strong>";

if (trim($rows_main["details"]))
{
	$text = substr($rows_main["details"], 0, 250);
	$text = strip_tags($text);
	$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
	$text = str_replace($rows_main['name'],"<strong>".$rows_main['name']."</strong>",$text);
	$text = str_replace($rows_main['gname'],"<strong>".$rows_main['gname']."</strong>",$text);
	$text = str_replace(" монет ","<strong> монет </strong>",$text);
	$text = str_replace(" монета ","<strong> монета </strong>",$text);
	$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
	$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
	echo "<br>Описание: ".str_replace("\n","<br>",$text)."";
}

if ($rows_main["dateinsert"]>time()-86400*180){
	echo "<br>Добавлено: <strong>".($rows_main["dateinsert"]>time()-86400*14?"<font color=red>NEW</font> ".date("Y-m-d", $rows_main["dateinsert"]):date("Y-m-d", $rows_main["dateinsert"]))."</strong>";
}?>
</div>	

<?        

if ($materialtype==1||$materialtype==12||$materialtype==10 || $materialtype==11 ||$materialtype==2|| $materialtype==4 || $materialtype==8 || $materialtype==6 || $materialtype==9) {		   
    if($shopcoins_class->is_already_described($catalog)){			
        echo '<div>Монета была описана <span style="color:red;">пользователем</span> сайта.<br>Клуб Нумизмат 
    несет ответственность за изображение предмета</div>';
    }
}

echo contentHelper::render('shopcoins/price/buy_button',$rows_main);
echo contentHelper::render('shopcoins/price/prices',$rows_main);
if(($rows_main['buy_status']==7||$rows_main['buy_status']==6)&&($minpriceoneclick<=$rows_main['price'])) {
	echo contentHelper::render('shopcoins/price/oneclick',$rows_main);
}?>

</div>
