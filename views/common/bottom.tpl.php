 <div class="clearfix">
       <?php include $cfg['path'] . '/views/common/small-logo.tpl.php';?>
 </div>
 <div class="triger clearfix" id='subsription'>
    <div class="wraper center" id='subsription-form' style="background-color:#eeeeee">    
        <div class="error" id="subsription-error"></div>
        Хотите быть в курсе событий? Всегда актульная информация!<br> 
	<center>
			  <form action="<?=$cfg['site_dir']?>user/subsription.php?ajax=1" method="post" class=formtxt>
		        Подпишитесь <input type="text" placeholder="Введите e-mail" value="" id='subsription-mail'>  
		            <input type="button" value="Подписаться" onclick="subsript();">
		        </form> 
		        
		        <script>
		        function subsript(){
		        	var subsriptionmail = jQuery('#subsription-mail').val().trim();
		        	if(!subsriptionmail){
		        		jQuery('#subsription-error').text('Введите E-mail');
		        	} else {
			            jQuery.ajax({
						    url: '<?=$cfg['site_dir']?>user/subsription.php?datatype=json', 
						    type: "POST",
						    data:{'subsription-mail':subsriptionmail}  ,         
						    dataType : "json",                   
						    success: function (data, textStatus) { 
						       jQuery('#subsription-error').text(data.errors);
						    }
						});
		        	} 
		        }
		        </script>
		 </center>  
		 <div class="custom_socials">
		        <a href="#" class="custom_social"> <img alt="" src="<?=$cfg['site_dir']?>images/social/instagram.gif"> </a> 
		        <a href="#" class="custom_social"> <img alt="" src="<?=$cfg['site_dir']?>images/social/youtube.gif"> </a>
		        <a href="#" class="custom_social"> <img alt="" src="<?=$cfg['site_dir']?>images/social/vk.gif"> </a>         
		        <a href="#" class="custom_social"> <img alt="" src="<?=$cfg['site_dir']?>images/social/facebook.gif"> </a> 		   
		    </div>
</div>
</div>

<div id='footer' class="wraper">
<div id='footer-menu'>
    <div class="col-5">
        <h5>Магазин монет</h5>
        <a title="Монеты стоимость(цены) весь мир" href="http://www.numizmatik.ru/shopcoins/">Монеты</a><br>
        <a title="Распродажа монет" href="http://www.numizmatik.ru/shopcoins/revaluation">Распродажа монет</a><br>
        <a title="Способы оплаты и доставки монет, банкнот, аксессуаров, книг по нумизматике" href="http://www.numizmatik.ru/shopcoins/delivery.php">Оплата и доставка</a><br>
        <a title="Покупка-скупка монет, коллекций монет. " href="http://www.numizmatik.ru/gde-prodat-monety">Покупка/скупка монет</a><br>
        <a title="Оценка стоимости(цены) монет(ы)" href="<?=$cfg['site_dir']?>ocenka-stoimost-monet">Оценка стоимости монет</a><br>
        <a title="" href="http://www.numizmatik.ru/">Контакты</a>
    </div>
    <div class="col-5">
        <h5>Каталог монет</h5>
        <a title="Каталог монет России, Германии, США и других стран" href="http://www.numizmatik.ru/catalognew/">Каталог монет</a><br>
        <a title="Каталог наборов монет весь мир" href="http://www.numizmatik.ru/catalognew/index.php?materialtype=7">Наборы монет</a><br>
        <a title="Каталог банкнот весь мир" href="http://www.numizmatik.ru/catalognew/index.php?materialtype=2">Банкноты</a><br>
        <a title="Каталог подарочных наборов монет весь мир" href="http://www.numizmatik.ru/catalognew/index.php?materialtype=4">Подарочные наборы</a><br>
        <a title="Каталог аксессуаров для коллекционеров" href="http://www.numizmatik.ru/catalognew/index.php?materialtype=3">Аксессуары</a><br>
        <a title="Скачать программное обеспечение для нумизмата совершенно бесплатно" href="http://www.numizmatik.ru/program/">Программа нумизмата</a><br>
        <a title="Скачать нумизматическое приложение для смартфона (планшета) бесплатно" href="http://www.numizmatik.ru/programandroid/">Приложения для смартфона</a><br>
     </div>
    <div class="col-5">
        <h5>Нумизмату</h5>
        <a title="Последние новости нумизматики" href="http://www.numizmatik.ru/http://news.numizmatik.ru/">Новости нумизматики</a><br>
        <a title="Клуб Нумизмат Найдите коллекционеров в своем городе, узнайте кто еще интересуется монетами на вашу тематику" href="http://www.numizmatik.ru/collector/">Клуб Нумизмат</a><br>
        <a title="Нумизматический форум, место встречи коллекционеров всей страны" href="http://www.numizmatik.ru/forum/">Форум нумизматов</a><br>
        <a title="Клуб Нумизмат рекомендует быть осторожным Опасайтесь мошенников" href="http://www.numizmatik.ru/blacklist/">Черный список</a><br>
        <a title="Динамический ценник на монеты СССР и России" href="http://www.numizmatik.ru/pricecoins/">Ценник на монеты</a><br>
        <a title="Цены на монеты" href="http://www.numizmatik.ru/price/">Стоимость монет</a>
    </div>
    <div class="col-5">
        <h5>Обратная связь</h5>
        <a title="Вопрос недели опросы" href="http://www.numizmatik.ru/weekquestion/">Вопрос недели</a><br>
        <a title="Гостевая книга" href="http://www.numizmatik.ru/guestbook/">Гостевая книга</a><br>
        <a title="Ваши советы" href="http://www.numizmatik.ru/advice/">Ваши советы</a><br>
        <a title="Контакты" href="http://www.numizmatik.ru/contacts/">Обратная связь</a><br>
        <a title="Для прессы" href="http://www.numizmatik.ru/press/">Для прессы</a><br><a title="" href="http://www.numizmatik.ru/">Контакты</a>
       </div>
      <div class="col-5 send-error">
       <h5>Нашли ошибку? </h5>
       Выделите мышкой и нажмите <br>CTRL+ENTER      
      </div> 
</div><br style="clear:both;">
<div class="description">
<div>
При полном или частичном использовании материалов ссылка на Клуб Нумизмат обязательна.<br>
2001 - <?=date('Y',time())?> &copy; Клуб Нумизмат
<br><a href="http://www.numizmatik.ru/mailto:administrator@numizmatik.ru">administrator@numizmatik.ru</a>
<br>Крупнейший портал для коллекционеров. Монеты. Покупка и продажа. Боны, книги, антиквариат. <br>
Разнообразные аксессуары для монет, альбомы для монет. Оценка стоимости монет. Москва.

<div class="cards">
  <img alt="" src="<?=$cfg['site_dir']?>images/social/visa.gif"> 
  <img alt="" src="<?=$cfg['site_dir']?>images/social/mastercard.gif">
  <img alt="" src="<?=$cfg['site_dir']?>images/social/russianpost.gif"> 
    <img alt="" src="<?=$cfg['site_dir']?>images/social/qiwi.gif">
  <img alt="" src="<?=$cfg['site_dir']?>images/social/sberbank.gif">    
    <img alt="" src="<?=$cfg['site_dir']?>images/social/webmoney.gif">  
</div>
</div>
</div>
 <?php include $cfg['path'] . '/views/common/metrica.tpl.php'; ?>
</div>
