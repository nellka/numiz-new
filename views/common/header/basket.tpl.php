<div class="basket-mini">
    <div class="basket-img">
    	<a href="<?=$cfg['site_dir']?>index.php?page=orderdetails">
            <img src="<?=$cfg['site_dir']?>images/cart.png" border=0 alt="Показать корзину">
        </a>
     </div>	
     <div class="caption">
        <p>
        	<a href="<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails" title="Показать корзину"><font color="Black">Корзина покупок</font></a>
         </p>
    	<div class="basket-label">  <p>	
    	<b>В корзине:</b> <span><a href='<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails'>
    	<span id=inorderamount><?=$tpl['user']['product_amount']?></span> товаров</a></span><br>
    	<b>На сумму:</b> <span id=inordersum><?=$tpl['user']['summ']?></span> рублей   </p>
    	 <?/*($cookiesuser?($catalogamount>0?"<a href=$script?catalognewstr=1&savesearch=1><font color=red>Ответы на оставленные заявки ($catalogamount)</font></a>":"<b>Ответы на оставленные заявки (0)</b>"):"<b>Ответы на оставленные заявки</b> (нужна авторизация)")*/
        if($tpl['user']['user_id']){?>
            <a href="<?=$cfg['site_dir']?>shopcoins/?catalognewstr=1&savesearch=1" class="error">Монеты по заявкам (<?=$tpl['user']['catalogamount']?>)</a>
        <?}?>
    	</div>	
    	<p class="basket-info"><b>Выберите несколько товаров и поместите их в корзину.</b> </p>
    	
    </div>
</div>		
<div id=MainBascet></div>