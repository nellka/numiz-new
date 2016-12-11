<div id='closeTop' class="close-top" onclick="setMini(1)"></div>
<div id="logo">
<?if($_SERVER['REQUEST_URI']!='/'){?>
	<a class="logo-img" href="http://www.numizmatik.ru">
		<img src="<?=$cfg['site_dir']?>images/logo_small.jpg" alt="Нумизматик" title="Нумизматик">
	</a>
<?} else {?>
	<img src="<?=$cfg['site_dir']?>images/logo_small.jpg" alt="Нумизматик" title="Нумизматик">
<?}?>
    <a href="<?=$cfg['site_dir']?>ocenka-stoimost-monet">Оценка монет</a>
    <a href="http://www.numizmatik.ru/gde-prodat-monety">Скупка монет</a>
    <a href="http://www.numizmatik.ru/shopcoins">Продажа монет</a>
    <div>В Москве и Санкт-Петербурге</div>
</div>
<div id="headergrid" itemscope itemtype="http://schema.org/Organization">
    <div id="contact-top-module">       
        <p itemprop="name">магазин <b>Клуб Нумизмат</b></p> 
        <div  itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">   
        <p>
	        <b><span itemprop="addressLocality">Москва</span></b>, <span  itemprop="streetAddress">ул. Тверская 12 стр. 8</span><br>
	        <b><span itemprop="addressLocality">Санкт-Петербург</span></b>,<span  itemprop="streetAddress"> ул. Турку 31</span>
        </p>
        <p style='margin:0'><b>Режим работы магазина</b>:<br>
        Рабочие: 9-00 до 19-30<br>
        Суббота: 10-00 до 18-00<br>
        Воскресенье: 10-00 до 18-00 </p>  
        </div>  
    </div>
     <div id="contact-top-phone">
     <p  class=phonetop>  <span itemprop="telephone">8-800-333-14-77</span> (по России бесплатно)<br>
     <span itemprop="telephone">+7-903-006-00-44</span> (Москва)<br>
     <span itemprop="telephone">+7-915-002-22-23</span>
     </p>
     <a id="showcalllink"  class="button24" onclick="showWin('<?=$cfg['site_dir']?>addcall.php?ajax=1',500);return false;" href="#">Заказать обратный звонок</a>   
    </div>
    <div class="search-top-module">   
         <form action="<?=$cfg['site_dir']?>shopcoins/index.php" method=get id='globalsearch-form'>          
				<input type="hidden"  value="4">    
	          	<input type="text" class="search rounded" placeholder='Поиск: Например, Россия 1 рубль 1994 серебро' name=search id=search value="<?=$search?>">
			<input type="submit" class="globalsearch-submit" name="submit" value="" onclick='ga("send", "event", "page", "search");'>
        </form>    
    </div>
</div>
<div id="basket">

<?if($tpl['user']['user_id']==352480){
	//var_dump(var_dump($tpl['module']));
}
if(in_array($tpl['module'],array('order','shopcoins','index'))){
include 'basket.tpl.php'; 
}?>
<?php include 'login.tpl.php'; ?>
</div>
