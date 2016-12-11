<?
if($tpl['catalognew']['error']['no_auth']){?>
    <div class="error">Добавлять и редактировать данные могут только зарегистрированные пользователи! </div>
<?
} elseif ($tpl['catalognew']['error']['added'] ){?>        	
    <div class="error">Данные о монете были успешно добавлены. Эта информация доступна для просмотра. Спасибо.
	<br><a href=<?=$cfg['site_dir']?>catalognew/addcoins.php>Добавить монеты</a>	
<?} else {?>
    <p>Уважаемые пользователи. Настоятельно просим вас не "воровать" изображения монет с других сайтов. Просим вас 
    добавлять изображения монет в каталог только из своей личной коллекции. Нам не нужны вопросы, связанные с копирайтом.</p>
    
    <form action="<?=$cfg['site_dir']?>catalognew/addcoins.php?catalog=<?=$catalog?>" method=post enctype=multipart/form-data class="wform">
    <?
    if($catalog&&$data){?>
        <h1>Редактирование элемента каталога <?=$data["name"]?></h1>
    <?}
    ?>
    	
    <div id=detailsinfo></div>
    
    <div>
    <div id=myDivAddImage></div>
    <?if($image_big_url){?>
     <div id=Image_Big class="main_img_big  center">
        	<?=contentHelper::showImage($image_big_url,"",array('alt'=>"",'folder'=>'catalognew'))?>	
     </div>	<br>   
    <?}?>
    </div>
    
    <div><b>Изображение: (<font color=red>*</font>)</b>:
    <a name=image></a><input type=file name=image class=formtxt size=40> 
    
    
    </div>
    <input type=hidden name=catalog value='<?=$catalog?>'>	
    <input type="hidden" name="submitaftererror" value="<?=$submitaftererror?>">
    
    <?if (sizeof($errors)){?>
    	<div class="error"><b><?=implode("<br>",$errors)?></b></div>
    <?}
    if($tpl['addcoinsExist']){?>
        <table border=0 cellpadding=3 cellspacing=1 align=center width="100%">
        <?foreach ($tpl['addcoinsExist'] as $rows){?>
    		
    		<tr class=tboard valign=top bgcolor=#EBE4D4>
    		<td width="200">
    		<?=contentHelper::showImage($rows["image_small_url"],"",array('alt'=>$rows["name"],'folder'=>'catalognew'))?>
    		</td>
    		<td><b><?=$rows["name"]?></b>
    		<?=($rows["yearstart"]?"<br><b>Год:</b> ".$rows["yearstart"]:"")?>
    		<?=($rows["metal"]?"<br><b>Метал:</b> ".$tpl['metalls'][$metal]:"")?>
    		<?=($rows["probe"]?"<br><b>Проба:</b> ".$rows["probe"]:"")?>
    		<?=($rows["procent"]?"<br><b>Cоотношение металла:</b> ".$rows["procent"]:"")?>
    		<?=($rows["amount"]?"<br><b>Тираж:</b> ".$rows["procent"]:"")?>
    		<?=($rows["size"]?"<br><b>Диаметр:</b> ".$rows["size"]:"")?>
    		<?=($rows["thick"]?"<br><b>Толщина:</b> ".$rows["thick"]:"")?>
    		<?=($rows["weight"]?"<br><b>Вес:</b> ".$rows["weight"]:"")?>
    		<?=($rows["condition"]?"<br><b>Состояния(е):</b> ".$rows["condition"]:"")?>
    		
    		</td></tr>	
    		
    		
    	<?}?>
    	<tr><td colspan="2" align="center">
    		<input type=submit class="button25" value='Нет, такой в этом списке не существует. Добавить мою монету'><br><br>
    		
    	</td></tr>	
    	</table>
    <?}?>   			
    <div class="addcons_block">
    <a onclick="showInvis('myDivElement1');return false;" href="#" class="h"><img src="<?=$cfg['site_dir']?>/images/windowsmaximize.gif" border=0> Общая информация</a>
    
    <div id="myDivElement1" style="display:table">
    
        <p>
            <label for="group"><b>Страна (<font color=red>*</font>)</b></label>
          
            <select name=group id=group onchange='ShowNominals()'>
                <option value=0>Выберите страну</option>        
                <? 
                foreach ($tpl['groups_parent'] as $rows){?>		
                	<option value='<?=$rows["group"]?>' <?=selected($rows["group"],$group)?>><?=$rows["name"]?></option>
                	<?
                	if (is_array($GroupArray[$rows["group"]])){
                		foreach ($GroupArray[$rows["group"]] as $key=>$value){?>
                			<option value='<?=$key?>' <?=selected($key,$group)?>> |--<?=$value?></option>
                		<?}
                	}	
                }?>
                </select>&nbsp;&nbsp;[<a href=#group onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=2&ajax=1","450");return false;'><font color=red> ? </font></a>]
            
        </p>
        <p>
            <label for="name"><b>Номинал (<font color=red>*</font>)</b></label>
            <select name=nominal_id id=nominal_id>
            		<option value=0>Выберите номинал</option>        	
            		<?foreach ($tpl['nominals'] as $rows_info){?>
            		    <option value='<?=$rows_info['nominal_id']?>' <?=selected($rows_info['nominal_id'],$nominal_id)?>><?=$rows_info['name']?></option>        			
            		<?}?>
                    </select>&nbsp;&nbsp;[<a href=#name onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=3&ajax=1","450");return false;'><font color=red> ? </font></a>]
           
        </p>    
        <div>
          <label for="yearstart"><b>Периоды чеканки</b></label>
          <div class="left">
          
          <?for($i=1;$i<=$year_p;$i++){?>
              <div id='year_period<?=$i?>'>
                <input type=text name=yearstart<?=$i?> value='<?=${"yearstart".$i}?>' size=6>&nbsp;&nbsp;по&nbsp;&nbsp;
                <input type=text name=yearend<?=$i?> value='<?=${"yearend".$i}?>' size=6>             
               </div>
          <?}?>
          <span id=myDivYear2>[<a href=#year onclick='AddYear()'>+</a>]</span>&nbsp;&nbsp;[<a href=#image onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=4&ajax=1","450");return false;'><font color=red> ? </font></a>]
          </div>
           <input type="hidden" name="year_p" id="year_p" value="<?=($year_p)?>">
        </div>
       
        
        <p>
          <label for="amount"><b>Тираж (в тысячах штук)</b></label>
          <input type=text name=amount value='<?=$amount?>' size=8> (Например: 0,5 = 500 штук)&nbsp;&nbsp;[<a href=#amount onclick='<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=5&ajax=1'><font color=red> ? </font></a>]
        </p>
         <p>
          <label for="condition"><b><b>Состояния при чеканке</b></b></label>
         
            <select name=condition >
            <option value=0>Выберите</option>>
            
            <?foreach ($ConditionMintArray as $key=>$value){?>
            	<option value="<?=$key?>" <?=selected($key,$condition)?>><?=$value?></option>
            <?}?>
           </select>&nbsp;&nbsp;[<a href=#amount onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=6&ajax=1","450");return false;'><font color=red> ? </font></a>]
          </p>
    </div> 
    </div>
    
    <div class="addcons_block">
    <a class="h" onclick="showInvis('myDivElement2');return false;" href="#"><img src="<?=$cfg['site_dir']?>/images/windowsmaximize.gif" border=0>Описание</a>
    
    <div id="myDivElement2" style="display:none">
    
        <p>
          <label for="averslegend"><b>Легенда аверса</b></label>
          <textarea name=averslegend cols=45 rows=6><?=$averslegend?></textarea>&nbsp;&nbsp;[<a href=#averslegend onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=7&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
        
        <p>
          <label for="reverselegend"><b>Легенда реверса</b></label>
          <textarea name=reverselegend cols=45 rows=6><?=$reverselegend?></textarea>&nbsp;&nbsp;[<a href=#reverselegend onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=8&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
        
        <p>
          <label for="mint"><b>Монетные двора:</b></label>
          <input type=text name=mint value='<?=$mint?>' size=40>&nbsp;&nbsp;[<a href=#averslegend onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=9&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>    
        <p>
          <label for="designer"><b>Дизайнер:</b></label>
         <input type=text name=designer value='<?=$designer?>' size=40> &nbsp;&nbsp;[<a href=#averslegend onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=10&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>   
    	<p>
      	<label for="designer"><b>Официальная дата выпуска:</b></label>
         <input type=text name=officialdate value='<?=$officialdate?>' size=20>&nbsp;&nbsp;(<b>Формат день-месяц-год</b>, например 25-03-2002) &nbsp;&nbsp;[<a href=#officialdate onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=11&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>   
    	<p>
          <label for="reverselegend"><b>Развернутое описание:</b></label>
          <textarea name=details cols=45 rows=6><?=$details?></textarea>&nbsp;&nbsp;[<a href=#details onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=12&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
        
        <div>
          <label><b>Тематики</b></label>
          <div class="left" style="width:500px">
          <?foreach ($ThemeArray as $key=>$value){?>
           <div class="left" style="width:33%"><input type=checkbox name=theme<?=$key?> <?=(isset(${"theme".$key})&&${"theme".$key}=="on"?"checked":"")?>><?=$value?>&nbsp;&nbsp;</div>
          <?}?>
         </div>
     		[<a href=#image onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=13&ajax=1","450");return false;'><font color=red> ? </font></a>]
          
        </div> 
       </div>
    </div>
    
    <div class="addcons_block">
    <a class="h"  onclick="showInvis('myDivElement3');return false;" href="#"><img src="<?=$cfg['site_dir']?>/images/windowsmaximize.gif" border=0>Характеристики</a>
    
    <div id="myDivElement3" style="display:none">
     	<p>
            <label for="metal"><b>Металл</b></label>
            <select name=metal id=metal >
            		<option value='0'>Выберите металл</option>        	
            		<?        		
            		foreach ($tpl['metalls'] as $key=>$value){
            			?>
            		    <option value='<?=$key?>' <?=selected($key,$metal)?>><?=$value?></option>        			
            		<?}?>
             </select>&nbsp;&nbsp;
             [<a href=#name onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=14&ajax=1","450");return false;'><font color=red> ? </font></a>]
           
        </p>    
    	<p>
          <label for="probe"><b>Проба:</b></label>
          <input type=text name=probe value='<?=$probe?>' size=10>&nbsp;&nbsp;(Например: 900)&nbsp;&nbsp;
          [<a href=#probe onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=15&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
        
        <p>
          <label for="procent"><b>Соотношение металла:</b></label>
          <input type=text name=procent value='<?=$procent?>' size=10>&nbsp;&nbsp;(Например: Cu-15%,Ni-85%)&nbsp;&nbsp;
          [<a href=#procent onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=16&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
    
        <p>
          <label for="weight"><b>Вес, г.:</b></label>
          <input type=text name=weight value='<?=$weight?>' size=20>&nbsp;&nbsp;
          [<a href=#details onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=17&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
    
        <p>
          <label for="size"><b>Диаметр, мм.:</b></label>
          <input type=text name=size value='<?=$size?>' size=20>&nbsp;&nbsp;
          [<a href=#details onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=18&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
        
        <p>
          <label for="thick"><b>Толщина, мм.:</b></label>
          <input type=text name=thick value='<?=$thick?>' size=20>&nbsp;&nbsp;
          [<a href=#thick onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=19&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
    
        <p>
          <label for="herd"><b>Тип гурта описание.:</b></label>
          <input type=text name=herd value='<?=$herd?>' size=20>&nbsp;&nbsp;(Например: Сетчатый, Гуртовая надпись)&nbsp;&nbsp;
          [<a href=#herd onclick='showWin("<?=$cfg['site_dir']?>/new/?module=catalognew&task=detailsinfo&valueid=19&ajax=1","450");return false;''><font color=red> ? </font></a>]
        </p>
    
    </div>
    </div>
    
    <div class="center">
    <br><br>
    <input type=submit name="submit" value='Записать' class="button25" >
    </div>
    
    <script>
    function AddYear()
    {
        
        var i = parseInt($('#year_p').val());
        var n = i+1;  
        var block = $("<div id='year_period"+n+"'> <input type=text name=yearstart"+n+" value='' size=6>&nbsp;&nbsp;по&nbsp;&nbsp;"+
                "<input type=text name=yearend"+n+" value='' size=6></div>");
                
        block.insertAfter($('#year_period'+i));
        $('#year_p').val(n);   
    	
    }
    </script>
    
    </div>
    </div>
<?}?>