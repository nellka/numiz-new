<center><a href="<?=$_SERVER['REQUEST_URI']?>" onclick="$.cookie('fv', 1);" class="full">Полная версия сайта</a></center>
<div class="top-menu">        
  <div id="header" class="wraper">
		<ul class="left cssmenu">
	          <li>
				<a href="#" class="coins" onclick="$('#userMenu').hide();showInvis('shopMenu');return false;"></a>
				
					<ul id="shopMenu" >
						<div  class="shadoweffect shadowblock">
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/moneti' title='Монеты стоимость(цены) весь мир'>Монеты</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/meloch' title='Дешевые монеты стоимость(цены) весь мир' >Мелочь </a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/baraholka' title='Барахолка'>Барахолка</a></li>  
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/cvetnie_moneti' title='Цветные монеты'>Цветные монеты </a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/banknoti' title='Банкноты стоимость(цены) весь мир'>Боны</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/nabori_monet' title='Наборы монет стоимость(цены) весь мир'>Наборы монет</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/podarochnye_nabory' title='Подарочные наборы монет  стоимость(цены) весь мир'>Подарочные наборы</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/aksessuary' title='Аксессуары для коллекционеров цены'>Аксессуары для монет</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/loty_dlya_nachinayushchih' title='Лоты монет для начинающих нумизматов' >Лоты монет для начинающих нумизматов</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/notgeldy' title='Нотгельды стоимость(цены) весь мир'>Нотгельды </a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/knigi' title='Книги о нумизматике бонистике цены'>Книги о монетах</a>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/newcoins' title='Новинки 2013-2015'>Новинки 2015-2015</a></li>
					   <li><a href='<?=$cfg['site_dir']?>shopcoins/revaluation' title='Распродажа монет'>Распродажа монет</a></li> 
						</div>
					</ul>	

			</li>			
		</ul>
		<ul class="cssmenu right" id='rmenu'>
			<li>
				<a href='#' class="searchbtn" onclick="$('#shopMenu').hide();$('#userMenu').hide();showInvis('searchblock');return false;"></a>
			</li>
			<li>
				<a href='<?=$cfg['site_dir']?>/shopcoins/delivery.php' class="car decoratnone">
					<img src="<?=$cfg['site_dir']?>images/mobile/auto.jpg">
				</a>
			</li>
            <li>
				<a href='<?=$cfg['site_dir']?>shopinfo.php' class="decoratnone">
					<img src="<?=$cfg['site_dir']?>images/mobile/geolocation.gif">
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
				<a href="#" class="user decoratnone" onclick="$('#shopMenu').hide();showInvis('userMenu');return false;">
					<img src="<?=$cfg['site_dir']?>images/mobile/profile_mobile.jpg">
				</a>
				
			</li>				
		</ul>
    </div>
</div>  

<div class="search-top-module" id='searchblock'>   
     <form action="<?=$cfg['site_dir']?>shopcoins/index.php" method=get>         
			<input type="hidden"  value="4">    
          	<input type="text" class="search rounded" name=search id=search value="<?=$search?>">
			<input type="submit" id=globalsearch-submit name="submit" value="">             
    </form>    
</div>		
<div id="userMenu" class="blocklogin">
    
	<?if (!$tpl['user']["is_logined"]) {?>
	   <div class="u_l shadoweffect">		
		<li><a class="abold " href="<?=$cfg['site_dir']?>user/login.php">Войти</a> или <a  class="abold " href="<?=$cfg['site_dir']?>user/registration.php">Зарегистрироваться</a></li>			
		</div>    
	<?} else {?>
	   <div class="n_l shadoweffect">	
		<li><a class="abold " href='<?=$cfg['site_dir']?>shopcoins/order.php'>Мои заказы</a></li>
		<li><a class="abold " href="<?=$cfg['site_dir']?>shopcoins/?catalognewstr=1&savesearch=1">Монеты по заявкам (<?=$tpl['user']['catalogamount']?>)</li>
		<li><a href='<?=$cfg['site_dir']?>shopcoins/mycoins.php' title='Монеты из ваших заказов в интернет-магазине монет' class="abold "><span class="error">Монеты из ваших заказов</span></a> </li>
		<li><a class="abold " href="<?=$cfg['site_dir']?>shopcoins/?logout=1">Выйти</a></li>    
		</div>    
    <?}?>									
</div>	
	
<div id=MainBascet></div>
<div class="triger" id='small-logo' style="display:none">
<?php include $cfg['path'] . '/views/common/small-logo.tpl.php';?>
</div>
<div class="wraper" id=top>

<div class="logo" id="logoblock">
	<table width=100%>
		<tr>
			<td>
				 <a class="logo-img" href="http://www.numizmatik.ru"><img src="<?=$cfg['site_dir']?>images/logo_small.jpg" border=0></a>
			</td>
			<td class="fontsize12">
				<div id="contact-top-module">  
					<p><a href="http://www.numizmatik.ru/shopinfo.php" class="black"><b>Москва</b>, ул. Тверская 12 стр. 8</a><br>
				</div>       
				<div id="contact-top-phone" class="contact-top-phone">
					 +7 (800) 333-14-77 <br>
					 +7 (903) 006-00-44 <br>
					 +7 (915) 001-22-23   
				</div> 
			</td>
		</tr>
	</table>
</div>
<div class="abold" >
	<center>
	 <a class="abold" href="http://www.numizmatik.ru/shopcoins">Продажа</a>,
	<a class="abold" href="<?=$cfg['site_dir']?>ocenka-stoimost-monet">Оценка </a> и 
    <a  class="abold" href="http://www.numizmatik.ru/gde-prodat-monety">Скупка монет</a> <br>
   
	<a href="http://www.numizmatik.ru/shopinfo.php" class="black">В Москве и Санкт-Петербурге</a>
	</center>
</div>
   
     
<script>
$(document).ready(function(){   
    $('body').on("click", ".ui-widget-overlay", function() {
          $(".ui-icon.ui-icon-closethick").trigger("click");
    }); 
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
      