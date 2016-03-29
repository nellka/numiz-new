<div class="top-menu">        
  <div id="header" class="wraper">
		<ul class="left cssmenu">
	          <li>
				<a href="#" class="coins" onclick="showInvis('shopMenu');return false;"></a>
				
					<ul id="shopMenu" >
						<div  class="shadoweffect shadowblock">
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=1' title='Монеты стоимость(цены) весь мир'>Монеты</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=8' title='Дешевые монеты стоимость(цены) весь мир' >Мелочь </a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=11' title='Барахолка'>Барахолка</a></li>  
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=6' title='Цветные монеты'>Цветные монеты </a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=2' title='Банкноты стоимость(цены) весь мир'>Боны</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=7' title='Наборы монет стоимость(цены) весь мир'>Наборы монет</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=4' title='Подарочные наборы монет  стоимость(цены) весь мир'>Подарочные наборы</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=3' title='Аксессуары для коллекционеров цены'>Аксессуары для монет</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=9' title='Лоты монет для начинающих нумизматов' >Лоты монет для начинающих нумизматов</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=10' title='Нотгельды стоимость(цены) весь мир'>Нотгельды </a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=5' title='Книги о нумизматике бонистике цены'>Книги о монетах</a>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=newcoins' title='Новинки 2013-2015'>Новинки 2015-2015</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=revaluation' title='Распродажа монет'>Распродажа монет</a></li> 
						</div>
					</ul>	

			</li>	
			<li class="rss_lenta">   
			 <a href='http://www.numizmatik.ru/shopcoins/rss.xml' target='_blank' title='Новые поступления монет,банкнот,аксессуаров - канал RSS' class=topmenu></a>
			</li>
		</ul>
		<ul class="cssmenu right" id='rmenu'>
			<li>
				<a href='#' class="searchbtn" onclick="showInvis('searchblock');return false;"></a>
			</li>
			<li>
				<a href='<?=$cfg['site_dir']?>/shopcoins/delivery.php' class="car decoratnone">
					<img src="<?=$cfg['site_dir']?>images/mobile/auto.jpg">
				</a>
			</li>

			<li>   
				<a href='<?=$cfg['site_dir']?>/shopcoins/index.php?page=orderdetails' class="cart decoratnone">
					<img src="<?=$cfg['site_dir']?>images/mobile/mobile_korz.jpg">
					<span id=inorderamount  class="inorderamount">
						<?=$tpl['user']['product_amount']?>
					</span>
				</a>
    	        <span id=inordersum style="display:none"><?=$tpl['user']['summ']?></span>				
			</li>			
	       	<li>
				<a href="#" class="user decoratnone" onclick="showInvis('userMenu');return false;">
					<img src="<?=$cfg['site_dir']?>images/mobile/profile_mobile.jpg">
				</a>
				
			</li>				
		</ul>
    </div>
</div>  

<div class="search-top-module" id='searchblock'>   
     <form action="<?=$cfg['site_dir']?>shopcoins/index.php" method=get>         
			<input type="hidden"  value="4">    
          	<input type="text" class="search rounded" name=search id=search>
			<input type="submit" id=globalsearch-submit name="submit" value="">             
    </form>    
</div>		
<div id="userMenu" class="blocklogin">
    
	<?if (!$tpl['user']["is_logined"]) {?>
	   <div class="u_l">		
		<a class="abold " href="<?=$cfg['site_dir']?>user/login.php">Войти</a> или <a  class="abold " href="<?=$cfg['site_dir']?>user/registration.php">Зарегистрироваться</a>						
		</div>    
	<?} else {?>
	   <div class="n_l">	
		<a class="abold " href='<?=$cfg['site_dir']?>/shopcoins/order.php'>Мои заказы</a><br>  
		<a class="abold " href="<?=$cfg['site_dir']?>shopcoins/?catalognewstr=1&savesearch=1">Монеты по заявкам (<?=$tpl['user']['catalogamount']?>)<br>  
		<a class="abold " href="<?=$cfg['site_dir']?>shopcoins/?logout=1">Выйти</a><br>     
		</div>    
    <?}?>									
</div>	
	
<div id=MainBascet></div>
<div class="triger" id='small-logo' style="display:none">
<?php include $cfg['path'] . '/views/common/small-logo.tpl.php';?>
</div>
<div class="wraper" id=top>

<div class="logo" id="logoblock">
    <a class="logo-img" href="http://www.numizmatik.ru"><img src="<?=$cfg['site_dir']?>images/logo_small.jpg" border=0></a>
    <div> <a href="<?=$cfg['site_dir']?>ocenka-stoimost-monet">Оценка монет</a>
    <a href="http://www.numizmatik.ru/gde-prodat-monety">Скупка монет</a>
    <a href="http://www.numizmatik.ru/shopcoins">Продажа монет</a>
    </div>
    <div>В Москве и Санкт-Петербурге</div>

    <div id="contact-top-module">  
        <p><b>Москва</b>, ул. Тверская 12 стр. 8<br>
        <b>Санкт-Петербург</b>, ул. Турку 31</p>
       
    </div>       
</div>
<div id="contact-top-phone" class="logo">
     8-800-333-14-77 (по России бесплатно)<br>
     +7-903-006-00-44 (Москва)<br>
     +7-812-925-53-22 (Санкт-Петербург)     
</div> 
     
<script>
$(document).ready(function(){   
 /* $('.dropdown li.top').click(function(event) {
       
        var cur_href =  $('a',this).filter( ':first' );       
        var is_active =  cur_href.hasClass('active');   
        if(!cur_href.hasClass('active_m'))  cur_href.addClass('active_m');         
        var is_visible = btn.is(':visible')?true:false;
        $('div.submenu').hide();
        
        if(!is_visible){
             btn.show();
        } else {
            btn.hide();
            cur_href.removeClass('active_m');
            if(is_active) cur_href.addClass('active');
        }
        return false;
    });  */
});
</script>     
      