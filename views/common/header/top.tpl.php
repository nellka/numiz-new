<div id='closeTop' class="close-top" onclick="setMini(1)"></div>
<div id="logo">
    <a class="logo-img" href="http://www.numizmatik.ru"><img src="<?=$cfg['site_dir']?>images/logo_small.jpg" border=0></a>
    <a href="<?=$cfg['site_dir']?>ocenka-stoimost-monet">Оценка монет</a>
    <a href="http://www.numizmatik.ru/gde-prodat-monety">Скупка монет</a>
    <div>В Москве и Санкт-Петербурге</div>
</div>
<div id="headergrid">
    <div id="contact-top-module">       
        <p>магазин <b>Клуб Нумизмат</b></p>    
        <p><b>Москва</b>, ул. Тверская 12 стр. 8<br>
        <b>Санкт-Петербург</b>, ул. Турку 31</p>
        <p><b>Режим работы магазина</b>:<br>
        Рабочие: 9-00 до 19-30<br>
        Суббота: 10-00 до 18-00<br>
        Вс: выходной </p>     
    </div>
     <div id="contact-top-phone">
     <p  class=phonetop>  8-800-333-14-77 (по России бесплатно)<br>
     +7-903-006-00-44 (Москва)<br>
     +7-812-925-53-22 (Санкт-Петербург)
     </p>
     <a id="showcalllink" rel='showcalllink' class="iframe button24" href="<?=$cfg['site_dir']?>addcall.php?ajax=1">Заказать обратный звонок</a>   
    </div>
    <div class="search-top-module">   
         <form action="<?=$cfg['site_dir']?>shopcoins/index.php" method=get id='globalsearch-form'>          
				<input type="hidden"  value="4">    
	          	<input type="text" class="search rounded" placeholder='Поиск' name=search id=search>
			<input type="submit" id=globalsearch-submit name="submit" value="">                
        </form>    
    </div>
</div>
<div id="basket">
<?php include 'basket.tpl.php'; ?>
<?php include 'login.tpl.php'; ?>
</div>
