<div id='openTop' class="open-top" onclick="setMini(0)"></div>
<div style="float:left">
   <b> 8-800-333-14-77 (по России бесплатно)<br>
    +7-903-006-00-44 (Москва)<br>
</div>
<div class="search-top-module" style="float:left">   
     <form action="<?=$cfg['site_dir']?>shopcoins/index.php" method=get>         
			<input type="hidden"  value="4">    
          	<input type="text" class="search rounded" name=search id=search value="<?=$search?>">
			<input type="submit" id=globalsearch-submit name="submit" value="">             
     </form> 
</div>

<div>
    <div style="float:right">
        <a href="<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails" title="Показать корзину"><font color="Black">Корзина покупок</font></a>:<br>
        <span><a href='<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails'>
        <span id=inorderamount><?=$tpl['user']['product_amount']?></span> товаров</a></span> / <span id=inordersum><?=$tpl['user']['summ']?></span> рублей
        <?/*($cookiesuser?($catalogamount>0?"<a href=$script?catalognewstr=1&savesearch=1><font color=red>Ответы на оставленные заявки ($catalogamount)</font></a>":"<b>Ответы на оставленные заявки (0)</b>"):"<b>Ответы на оставленные заявки</b> (нужна авторизация)")*/
        if($tpl['user']['user_id']){?>
           <br> <a href="<?=$cfg['site_dir']?>shopcoins/?catalognewstr=1&savesearch=1" class="error">Монеты по заявкам (<?=$tpl['user']['catalogamount']?>)</a>
        <?}?>
    </div>
</div>
