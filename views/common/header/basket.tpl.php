<div class="basket-mini">
    <div class="basket-img">
    	<a href="<?=$cfg['site_dir']?>index.php?page=orderdetails">
            <img src="<?=$cfg['site_dir']?>images/cart.png" border=0 alt="Показать корзину">
        </a>
     </div>	
     <div class="caption">
        <p>
        	<a href="/catalog?page=shop.cart" title="Показать корзину"><font color="Black">Корзина покупок</font></a>
         </p>
    	<div class="basket-label">  <p>	
    	<b>В корзине:</b> <span><?=$tpl['user']['product_amount']?> товаров </span><br>
    	<b>На сумму:</b> <span><?=$tpl['user']['summ']?> рублей </span>  </p>
    	</div>	
    	<p class="basket-info"><b>Выберите несколько товаров и поместите их в корзину.</b> </p>
    </div>
</div>		
