<? include('shortmenu.tpl.php')?>

<div style='margin-left:30px;'>
<?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
</div>	
<br style="clear: both;">
<div>
  <h5 style="margin: 10px 0;">Поиск - <?=$search?></h5>
  <b>Критерии поиска:</b>
  <div class="content">
    <p>
    <form method="get" action="http://numizmatik1.ru/new/shopcoins/index.php">
    Поиск: <input type="text" name="search" value="<?=$search?>" />
            <select name="materialtype" id=materialtype>
                <option value="0">Все категории</option>
                <option value="1" <?=selected($materialtype,1)?>>Монеты</option>
                <option value="8" <?=selected($materialtype,8)?>>Мелочь</option>
                <option value="6" <?=selected($materialtype,6)?>>Цветные монеты</option>
                <option value="10" <?=selected($materialtype,10)?>>Нотгельды</option>
                <option value="7" <?=selected($materialtype,7)?>>Наборы монет</option>
                <option value="9" <?=selected($materialtype,9)?>>Лоты монет для начинающих нумизматов</option>
                <option value="2" <?=selected($materialtype,2)?>>Боны<</option>
                <option value="3" <?=selected($materialtype,3)?>>Аксессуары для монет</option>
                <option value="4" <?=selected($materialtype,4)?>>Подарочные наборы</option>
                <option value="5" <?=selected($materialtype,5)?>>Книги о монетах</option>               
                <!--
                <option value="5" <?=selected($materialtype,5)?>>Книги о монетах</option>
                <option value="5" <?=selected($materialtype,5)?>>Книги о монетах</option>
-->  
            </select>  
            <input type="submit" value="Поиск" id="button-search" class="button" />  
      </form> 
    </p>
  </div>
</div>
<br style="clear: both;">