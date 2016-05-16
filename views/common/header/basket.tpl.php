<div class="basket-mini">
    <div class="basket-img">
    	<a href="<?=$cfg['site_dir']?>index.php?page=orderdetails">
            <img src="<?=$cfg['site_dir']?>images/cart.png" border=0 alt="Показать корзину">
        </a>
     </div>	
     <div class="caption">
        <p> <? if($tpl['user']['product_amount']){?>     
        	<a href="<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails" title="Показать корзину"><font color="Black">Корзина покупок</font>
        	   <span id='basket-order'><?=($shopcoinsorder&&$tpl['user']['product_amount'])?" № ".$shopcoinsorder:''?></span>
        	</a>
        	<?} else {?>
        	    <font color="Black"><b>Корзина покупок</b></font><span id='basket-order'></span>
        	<?}?>

         </p>
    	<div class="basket-label" id='basket-info-ne' style="display:<?=$tpl['user']['product_amount']?'block':'none'?>">  
    	<p>	
    	<b>В корзине:</b> 
    	<span id=inorderamount>
    	 <? if($tpl['user']['product_amount']){?>        
            <a href='<?=$cfg['site_dir']?>shopcoins/index.php?page=orderdetails'>
            <?=$tpl['user']['product_amount']?> товаров
            </a>
        <?} else {?>
            <?=$tpl['user']['product_amount']?> товаров
        <?}?>        
    	</span><br>
    	<b>На сумму:</b> <span id=inordersum><?=ceil($tpl['user']['summ'])?></span> рублей   </p>
    	</div>	
        <p class="basket-label" id='basket-info' style="display:<?=$tpl['user']['product_amount']?'none':'block'?>"><b>Выберите несколько товаров и поместите их в корзину.</b> </p>
    	<?/*($cookiesuser?($catalogamount>0?"<a href=$script?catalognewstr=1&savesearch=1><font color=red>Ответы на оставленные заявки ($catalogamount)</font></a>":"<b>Ответы на оставленные заявки (0)</b>"):"<b>Ответы на оставленные заявки</b> (нужна авторизация)")*/
        if($tpl['user']['user_id']){?>
            <a href="<?=$cfg['site_dir']?>shopcoins/?catalognewstr=1&savesearch=1" class="error">Монеты по заявкам (<?=$tpl['user']['catalogamount']?>)</a>
        <?}?>   	
    </div>
</div>		
<div id=MainBascet></div>