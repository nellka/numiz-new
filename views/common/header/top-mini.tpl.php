<div id='openTop' class="open-top" onclick="setMini(0)"></div>
<div style="float:left">
   <b> 8-800-333-14-77 (по России бесплатно)</b><br>
   <b> +7-903-006-00-44 (Москва)</b><br>
</div>
<div class="search-top-module" style="float:left">   
     <form action="<?=$cfg['site_dir']?>shopcoins/index.php" method="get"  >         
			<input type="hidden"  value="4">    
          	<input type="text" class="search rounded" name=search id=search value="<?=$search?>" placeholder='Поиск: Например, Россия 1 рубль 1994 серебро'>
			<input type="submit" class="globalsearch-submit" name="submit" value="" onclick='ga("send", "event", "page", "search");'>
     </form> 
</div>

<div>
	
<?if(in_array($tpl['module'],array('order','shopcoins','index'))){?>
	
    <div class="right" style="width: 165px;">
        <? if($tpl['user']['product_amount']){?>        
        <a href="<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails" title="Показать корзину"><span class="black">Корзина</span><span id='basket-order'><?=($shopcoinsorder&&$tpl['user']['product_amount'])?" №".$shopcoinsorder:''?></span>
            <?php
            if($orderstart){?>
                <span class="red">до <?=date('H:i',($orderstart+5*3600))?></span>
            <?}?>
        </a>
         <?} else {?>
            <span class="black">Корзина покупок</span><span id='basket-order'></span>
        <?}?> 
        
        <br>
        <div class="short_top_user">
        <span id=inorderamount>
        <? if($tpl['user']['product_amount']){?>        
            <a href='<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails'>
            <?=$tpl['user']['product_amount']?> товаров
            </a>
        <?} else {?>
            <?=$tpl['user']['product_amount']?> товаров
        <?}?>        
        </span> / <span id=inordersum><?=ceil($tpl['user']['summ'])?></span> р.
        <?if($tpl['user']['balance']){?>
    	    <img src='<?=$cfg['site_dir']?>images/balance.gif'><?=$tpl['user']['balance']?> р.
    	<?}?>
    	</div>
        <?
        if($tpl['user']['user_id']){?>
          <a href="<?=$cfg['site_dir']?>shopcoins/?catalognewstr=1&savesearch=1" class="error">Монеты по заявкам (<?=$tpl['user']['catalogamount']?>)</a>
        <?}?>
    </div>
 <?}?>   
    <?if($tpl['user']['user_id']){?>
    
	<div class="user_menu right">
	<a href="#" onclick="showUserMenu(this);return false" class="user_menu_href" onclick=""></a>
      <ul class="u_submunu" style="display:none" id='u_submunu'>
        <li>Здравствуйте, <b><?=$tpl['user']['username']?></b>!</li>
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

	<?} else {?>
		<div class="mini-login-block right" style="width: 165px;"> 
 <a style="text-decoration:underline;" onclick="showWin('<?=$cfg['site_dir']?>user/login.php?ajax=1',500);return false;" href="#" id='login_form'>Войти</a> <br>
 <a style="text-decoration:underline;" onclick="showWin('<?=$cfg['site_dir']?>user/registration.php?ajax=1',500);return false;" href="#" title='Регистрация' id='reg_form'>Зарегистрироваться</a>
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
