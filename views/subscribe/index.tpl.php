<div class="main_context bordered">

	<div>
		<h2>Подписка</h2>
	</div>
<?
if($tpl['shop']['errors']){?>
	<font color="red">Вы не авторизованы на сайте! <br>Пожалуйста, залогинтесь.</font>
<?} else {
    if($tpl['subscribe']['saved']){?>
        <font color="red">Настройки рассылки сохранены.</font><br><br>
   <?}?>
   <div><b>ФИО:</b> <?=$user_data['fio']?><br><br></div>
   <div><b>E-mail:</b> <?=$user_data['email']?><br></div>
<hr>
	<form action="<?=$cfg['site_dir']?>subscribe/" method=post>
	
	<input type=hidden name=mailkey id='mailkey' value='<?=$tpl['subscribe']['data']['mailkey']?>'>
	
	<div>
    	<input type=checkbox name=shopcoins id=shopcoins <?=checked_box($tpl['subscribe']['data']['shopcoins'])?> >
    	<b>Монетная лавка (магазин)</b>
    	<br>Новые поступления в монетной лавке. Поступления происходят ~2 раза в неделю в зависимости поступления материала. 
    	Все самое интересное...
    	<br><br>
	</div>
	<div>
    	<input type=checkbox name=news <?=checked_box($tpl['subscribe']['data']['news'])?>>
    	<b>Новости</b>
    	<br>Наиболее интересные и яркие новости российских и зарубежных событий, связанных с нумизматикой, бонистикой, историей, археологией и т.п.
    	Для подбора новостей мы используем материалы крупнейших российских и зарубежных новостных агенств. Ряд материалов мы получаем непосредственно от пользователей портала.
    	
    	<br><br>
	</div>
	<div>
    	<input type=checkbox name=tboard <?=checked_box($tpl['subscribe']['data']['tboard'])?>>
    	<b>Конференция</b>
    	<br>Новые темы, обсуждаемые на конференции. Все самое интересное...
    	
    	<br><br>
    </div>
	<div>
    	<input type=checkbox name=blacklist <?=checked_box($tpl['subscribe']['data']['blacklist'])?>>
    	<b>Черный список</b>
    	<br>Новые теме в разделе "Черный список". Предупрежден - значит защищен.
    	
    	<br><br>
	</div>
	<div>
    	<input type=checkbox name=buycoins <?=checked_box($tpl['subscribe']['data']['buycoins'])?>>
    	<b>Нужны монеты</b>
    	<br>Данный раздел был создан по просьбам посетителей сайта. Если Вы имеете потребность в каких-то определенных монетах, тогда этот раздел для Вас.
    	
    	<br><br>
    </div>	
	<div>
    	<input type=checkbox name=biblio <?=checked_box($tpl['subscribe']['data']['bibliot'])?>>
    	<b>Библиотека</b>
    	<br>Новые статьи, которые имеют отношение к нумизматике
    	
    	<br><br>
    </div>	
	<div>
    	<input type=checkbox name=advertise <?=checked_box($tpl['subscribe']['data']['advertise'])?>>
    	<b>Доска объявлений</b>
    	<br>Новые объявления от частных лиц об обмене, продаже или покупке коллекционных материалов.
    	Администрация не несет ответственности
    	за достоверность информации,
    	размещаемой здесь посетителями, и не
    	может подтвердить ее или опровергнуть.
    	Доверять ей или не доверять - личное дело посетителей сайта.
    	<br><br>
    </div>	
    
	<div>
	
    	<b>Все рассылки одним письмом</b>
    	<select name=typemail id=typemail>
    	<option value=1 <?=selected($tpl['subscribe']['data']['typemail'],1)?>>Да</option>
    	<option value=2 <?=selected($tpl['subscribe']['data']['typemail'],2)?>>Нет</option>
    	</select>
	</div>
	<div>
    	<br><br>
    	<input class="button25"  type=submit name=subscribesubmit value='Записать'>
    	<br>
	</div>
	</form>
<?}?>
</div>
