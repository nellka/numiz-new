<div class="wraper">
    <ul class="tabs">
        <li class="active">Нумизматик рекомендует</li>
        <li class="passive">Новинки</li>
        <li class="passive">Акции</li>
        <li class="passive">Распродажа</li>
    </ul>
</div>  
 <div class="clearfix"></div>
<div class="triger">
    <div class="wraper" style="height:350px;padding-top:15px;"> 
      <div class="panes"> 
          <div>
          <?foreach ($tpl['coins']['populars'] as $row){?>
              <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/874725.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>
         </div>   
          <div class="les"> 
          <?foreach ($tpl['coins']['new'] as $row){?>
              <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/857893.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>          
          </div> 
          <div class="les">
          <?foreach ($tpl['coins']['actions'] as $row){?>
              <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/865802.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>          
          </div> 
          <div class="les">
          <?foreach ($tpl['coins']['sales'] as $row){?>
               <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/874727.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>          
          </div>  
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="wraper">
	<h3>Разделы монет</h3>   
</div>
<div class="clearfix">
	<div class="wraper">
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/coins.jpg" width="140" style="margin-right:10px;">
			<a href="">Монеты</a><br><br>
			В нашем магазине монет Вы всегда сможете подобрать интересующие Вас монеты, которых возможно не хватает в Вашей коллекции.			
		</div>
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/sets.jpg" width="140" style="margin-right:10px;">
			<a href="">Наборы монет</a><br><br>
			Выполнены на высоком художественном уровне, штемпеля резались лучшими граверами и качество самой чеканки было выше.			
		</div>
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/podaro4nie.jpg" width="140" style="margin-right:10px;">
			<a href="">Подарочные наборы</a><br><br>
			Монеты находятся в красочной упаковке в футляре. Послужит хорошим подарком нумизмату		
		</div>
	</div>
</div>
<br>
<div class="clearfix">
	<div class="wraper">
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/melo4.jpg" width="140" style="margin-right:10px;">
			<a href="">Мелочь</a><br><br>
			Изображение предоставлено для данного типа монет приблизительно одного состояния (+/- 0.5 по шкале F VF XF UNC)		
		</div>
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/notgeld.jpg" width="140" style="margin-right:10px;">
			<a href="">Нотгельды</a><br><br>
			 Специальные деньги, выпущенные в оборот различными органами местной власти, а также неправительственными организациями		
		</div>
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/cvetnie.jpg" width="140" style="margin-right:10px;">
			<a href="">Цветные монеты</a><br><br>
			Каталог красивых монет постоянно пополняется и вы всегда можете купить цветные монеты на любой вкус,		
		</div>
	</div>
</div>
<br>
<div class="clearfix">
	<div class="wraper">
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/banknoti.jpg" width="140" style="margin-right:10px;">
			<a href="">Банкноты</a><br><br>
			 Каталог банкнот постоянно пополняется и вы всегда можете купить банкноты на любой вкус, как самому себе, так и в подарок.		
		</div>
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/knigi.jpg" width="140" style="margin-right:10px;">
			<a href="">Книги о монетах</a><br><br>
			 Прекрасный подарок как начинающим, так и опытным нумизматам. Большой выбор.	
		</div>
		<div class="coin_division">
			<img align="left" src="<?=$cfg['site_dir']?>images/static_images/aksesuari.jpg" width="140" style="margin-right:10px;">
			<a href="">Аксессуары для монет</a><br><br>
			Каталог постоянно пополняется.	Послужит хорошим подарком нумизмату.
		</div>
	</div>
</div>
<br>
              
                
        <script> 
    jQuery(function() {
     jQuery("ul.tabs").tabs("div.panes > div");
      });
    </script>
<div class="wraper clearfix central_banner" >
    <?=$tpl['banners']['main_center_1']?>
    <?=$tpl['banners']['main_center_2']?>
</div>

<div class="wraper" >
   <h3>Популярные в категориях</h3>  
</div>  

 <div class="triger">
    <div class="wraper" style="height:350px;padding-top:15px;"> 
          <?foreach ($tpl['coins']['populars_in_category']   as $row){?>
              <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/880394.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>              
   </div> 
</div>   
<div class="clearfix">
	<div class="wraper" >
	   <h3>Рейтинг клуба <img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border=""></h3>  
	   <div class="club_review">
				UserName <br>
				Rating <br>
				Понравилось, что в клубе “Нумизмат” заключают договор перед продажей. 
				Сразу видно, что здесь всё честно и без скрытых схем наживы. 
				Таким людям и паспорт показать приятно. И деньги платят моментально
				<br>
				<div style="float:right;">
					<a href="">Читать далее>></a>
				</div>
			 </div>
			 <div class="club_review">UserName <br>
				Rating <br>
				Хотела по телефону узнать стоимость нескольких монет. Но 
				когда позвонила, поняла, что такая оценка будет неадекватной. 
				Хорошо, что нашла время на поездку в клуб нумизматов.
				Оценку сделали очень быстро.
				<br>
				<div style="float:right;">
					<a href="">Читать далее>></a>
				</div>
			 </div>
			 <div class="club_review">UserName <br>
				Rating <br>
				Решил продать свою коллекцию, чтобы вложить деньги в старинные
				монеты. В клубе “Нумизмат” на Тверской мне предложили самую высокую цену. 
				Деньги получил сразу. Очень доволен. Здесь всё честно.
				<br>
				<div style="float:right;">
					<a href="">Читать далее>></a>
				</div>
			 </div>
			 <div class="club_review">UserName <br>
				Rating <br>
				Договорились о встрече в клубе. Понравилось, что нумизматы 
				расположились в центре Москвы. Рядом Петровка, 38.
				Это успокаивало меня, что я ничем не рискую. Главное: выгодно продала коллекцию.
				<br>
				<div style="float:right;">
					<a href="">Читать далее>></a>
				</div>
			 </div>
	</div> 
</div>
<div class="clearfix">
	<div class="wraper" >
		<h3 style="padding-top:20px;">Новости клуба</h3> 
		<div class="club_news">
				<a href="">Заголовок новости</a><br><br>
				04.12.2015<br>
				<center><img src="http://www.numizmatik.ru/shopcoins/images/865802.jpg"></center><br>
				Опубликованы итоги конкурса «Монета года 2016»: определено 10 победителейПо итогам конкурса 
				«Монета года 2016», Австрия привезла домой награды в трех категориях. 
				Это сенсационный прорыв для монетного двора этой страны. В конкурсе принимали участие все изделия,
				датированные 2016 годом
				<br>
				<div style="float:right;">
					<a href="">Читать далее>></a>
				</div>
		</div>
		<div class="club_news">
				<a href="">Заголовок новости</a><br><br>
				04.12.2015<br>
				<center><img src="http://www.numizmatik.ru/shopcoins/images/865802.jpg"></center><br>
				Опубликованы итоги конкурса «Монета года 2016»: определено 10 победителейПо итогам конкурса 
				«Монета года 2016», Австрия привезла домой награды в трех категориях. 
				Это сенсационный прорыв для монетного двора этой страны. В конкурсе принимали участие все изделия,
				датированные 2016 годом
				<br>
				<div style="float:right;">
					<a href="">Читать далее>></a>
				</div>
		</div>
		<div class="club_news">
				<a href="">Заголовок новости</a><br><br>
				04.12.2015<br>
				<center><img src="http://www.numizmatik.ru/shopcoins/images/865802.jpg"></center><br>
				Опубликованы итоги конкурса «Монета года 2016»: определено 10 победителейПо итогам конкурса 
				«Монета года 2016», Австрия привезла домой награды в трех категориях. 
				Это сенсационный прорыв для монетного двора этой страны. В конкурсе принимали участие все изделия,
				датированные 2016 годом
				<br>
				<div style="float:right;">
					<a href="">Читать далее>></a>
				</div>
		</div>
		<div class="club_news">
				<a href="">Заголовок новости</a><br><br>
				04.12.2015<br>
				<center><img src="http://www.numizmatik.ru/shopcoins/images/865802.jpg"></center><br>
				Опубликованы итоги конкурса «Монета года 2016»: определено 10 победителейПо итогам конкурса 
				«Монета года 2016», Австрия привезла домой награды в трех категориях. 
				Это сенсационный прорыв для монетного двора этой страны. В конкурсе принимали участие все изделия,
				датированные 2016 годом
				<br>
				<div style="float:right;">
					<a href="">Читать далее>></a>
				</div>
		</div>
	</div> 
</div>



   
    