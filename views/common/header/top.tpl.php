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
    <div id="search-top-module">   
         <form action="<?=$cfg['site_dir']?>search.php" method=get>   
         <!--<select name=globalsearch_type class=topform >
         <option value=0>Все категории
            <option value=4>Магазин
            <option value=5>Кат. монет
            <option value=6>Кат. бон
            <option value=1>Новости
            <option value=2>Библиотека
            <option value=3>Конференция
            </select>  --> 
	<table>
		<tr>
			<td>  
				<input type="hidden"    value="4">    
	          	  	<input type="text" class="search rounded" name=globalsearch id=globalsearch>
			</td>
			<td>
				<input type="submit" name=globalsearch-submit id=globalsearch-submit value="">
			</td>
		</tr>
	</table> 
          
           
        </form>    
    </div>
</div>
<div id="basket">
<?php include 'basket.tpl.php'; ?>
<?php include 'login.tpl.php'; ?>
</div>
