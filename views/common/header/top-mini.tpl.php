<div id='openTop' class="open-top" onclick="setMini(0)"></div>
<div style="float:left">
   <b> 8-800-333-14-77 (по России бесплатно)<br>
    +7-903-006-00-44 (Москва)<br>
</div>
<div class="search-top-module" style="float:left">   
     <form action="<?=$cfg['site_dir']?>shopcoins/index.php" method="get"  >         
			<input type="hidden"  value="4">    
          	<input type="text" class="search rounded" name=search id=search value="<?=$search?>" placeholder='Поиск: Например, Россия 1 рубль 1994 серебро'>
			<input type="submit" id=globalsearch-submit name="submit" value="">             
     </form> 
</div>

<div>
	

	
    <div class="right" style="width: 165px;">
        <? if($tpl['user']['product_amount']){?>        
        <a href="<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails" title="Показать корзину"><font color="Black">Корзина покупок</font><span id='basket-order'><?=($shopcoinsorder&&$tpl['user']['product_amount'])?" №".$shopcoinsorder:''?></span></a>
         <?} else {?>
            <font color="Black">Корзина покупок</font><span id='basket-order'></span>
        <?}?> 
        
        <br>
        <span id=inorderamount>
        <? if($tpl['user']['product_amount']){?>        
            <a href='<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails'>
            <?=$tpl['user']['product_amount']?> товаров
            </a>
        <?} else {?>
            <?=$tpl['user']['product_amount']?> товаров
        <?}?>        
        </span> / <span id=inordersum><?=ceil($tpl['user']['summ'])?></span> рублей
        <?
        if($tpl['user']['user_id']){?>
           <br> <a href="<?=$cfg['site_dir']?>shopcoins/?catalognewstr=1&savesearch=1" class="error">Монеты по заявкам (<?=$tpl['user']['catalogamount']?>)</a>
        <?}?>
    </div>
    
    <?if($tpl['user']['user_id']){?>
    
	<div class="user_menu right">
	<a href="#" onclick="showUserMenu(this);return false" class="user_menu_href" onclick=""></a>
      <ul class="u_submunu" style="display:none" id='u_submunu'>
        <li><a title="Просмотр/редактирование личных данных/настроек" href="http://www.numizmatik.ru/user/profile.php">Ваш профайл</a></li>
        <li>
        	<a title="Ваши заказы монет, банкнот, аксессуаров для коллекционеров в интернет-магазине монет" href="http://www.numizmatik.ru/shopcoins/order.php">Ваши заказы</a>
		</li>
		<li>
			<a title="Монеты из ваших заказов в интернет-магазине монет" href="http://www.numizmatik.ru/shopcoins/mycoins.php">Монеты из ваших заказов</a>
		</li>
		<li>
			<a href="http://www.numizmatik.ru/shopcoins/?logout=1">Выйти</a>
		</li>
      </ul>
      
	       
	</div>

	<?}?>
</div>
<script>

function showUserMenu(obj){
	 var btn = $('#u_submunu');
	 var is_visible = btn.is(':visible')?true:false;
	 if(!is_visible){
	 	$(obj).removeClass('user_menu_href').addClass('user_menu_href_c');
         btn.show();
    } else {
    	$(obj).removeClass('user_menu_href_c').addClass('user_menu_href');
        btn.hide();           
    }
}
</script>
