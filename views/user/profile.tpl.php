<div class="main_context bordered">
<h2>Профайл пользователя:</h2>
<div class="error"><?if($tpl['user']['errors']) echo implode("<br>",$tpl['user']['errors'])?></div>
<?
if ($action=="message_see" && $tpl['user']['message']){ ?>
	<table border=0 cellpadding=3 cellspacing=1 width=100% align=center>
		<tr bgcolor=#EBE4D4 valign=top><td colspan=2 align=center class=tboard><b>Отображение сообщения</b></td></tr>
		<tr bgcolor=#fff8e8 valign=top><td class=tboard>Дата:</td><td class=tboard><?=$tpl['user']['message']["date1"]?></td></tr>
		<tr bgcolor=#fff8e8 valign=top><td class=tboard>От:</td><td class=tboard><?=$tpl['user']['message']["fio"]?></td></tr>
		<tr bgcolor=#fff8e8 valign=top><td class=tboard>Тема:</td><td class=tboard><?=$tpl['user']['message']["topic"]?></td></tr>
		<tr bgcolor=#fff8e8 valign=top><td class=tboard>Сообщение:</td><td class=tboard><?=str_replace("\n", "<br>", $tpl['user']['message']["details"])?></td></tr>
		<tr bgcolor=#fff8e8 valign=top><td colspan=2 class=tboard align=right>
		<a href="<?=$r_url?>"><img src="<?=$cfg['site_dir']?>images/refresh.gif" alt='Обновить и в начало' border=0></a>&nbsp;&nbsp;
		<a href="#" onclick="showWin('<?=$cfg['site_dir']?>collector/message.php?collectors_message=<?=$tpl['user']['message']["collectors_message"]?>&ajax=1',500);return false;">
		<img src="<?=$cfg['site_dir']?>/images/reply.gif" border=0 alt='Ответить'></a>
		</td></tr>
		</table>		
<?}
				
if($tpl['user']['messages']){?>
	<br><b>Ваши сообщения:</b></p>
		<table border=0 cellpadding=3 cellspacing=1 align=center width=100%>
		<tr bgcolor=#EBE4D4>
			<td class=tboard><b>Дата</b></td>
			<td class=tboard><b>От:</b></td>
			<td class=tboard><b>Тема:</b></td>
			<td class=tboard>&nbsp;</td>
		</tr>
		<? foreach ($tpl['user']['messages'] as $rows){?>
			<tr bgcolor=#fff8e8>
				<td class=tboard><?=$rows["date1"]?></td>
				<td class=tboard><a href="<?=$r_url?>?action=message_see&collectors_message=<?=$rows["collectors_message"]?>" class="<?=($rows["view"]==0)?"":"star"?>"><?=$rows["fio"]?></a></td>
				<td class=tboard><a href="<?=$r_url?>?action=message_see&collectors_message=<?=$rows["collectors_message"]?>" class="<?=($rows["view"]==0)?"":"star"?>"><?=$rows["topic"]?></a></td>
				<td class=tboard>
				<a href="<?=$r_url?>?action=message_delete&collectors_message=<?=$rows["collectors_message"]?>">
				<img src="<?=$cfg['site_dir']?>images/delete.gif" alt='Удалить' border=0></a></td>
			</tr>
		<?}?>
		</table>
<?}?>
<p><a href="#" onclick="showInvis('pamyatka');">Памятка пользователя Клуб Нумизмат</a></p>
<div  style="display:none" id='pamyatka'>
    <p>Почти все действия на сайте Numizmatik.ru требуют авторизации, т.е. выполнить их может только зарегистрированный пользователь. Каждому пользователю присваивается определенный статус доверия к предоставленной им информации:<br></p>
    <p><b>Схема рейтингования:</b>
    <div>
        <div class="left" style="margin:0 20px 0 0">
        <table cellspacing="0" cellpadding="3" border="0"><tbody><tr><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star1.gif"> - 1</td><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star2.gif"> - 2</td></tr><tr><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star3.gif"> - 3</td><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star4.gif"> - 4</td></tr><tr><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star5.gif"> - 5</td><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star6.gif"> - 6</td></tr><tr><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star7.gif"> - 7</td><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star8.gif"> - 8</td></tr><tr><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star9.gif"> - 9</td><td class="tboard"><img alt="Рейтинг пользователя" src="../images/star10.gif"> - 10</td></tr></tbody></table>
        </div>
        <div>
        <p><b>1-2</b> - пользователь не зарегистрирован на портале</p>
        <p><b>3</b> - пользователь прошел систему регистрации на сайте, информацию о нем можно найти в разделе <b>Пользователи</b></p>
        <p><b>4-5</b> - пользователь относиться к активным участникам портала</p>
        <p><b>5-8</b> - активный участник, появляются права подсветки текста на форуме, интернет-магазинах, отзывах (т.е. релевантность таких сообщений выше, чем у простых).</p>
        <p><b>9-более</b> - администраторы разделов, более активные пользователи, имеют расширенные права</p>
        </div>
   </div>
</div>
<p><a href="#" onclick="showInvis('reg_form');return false">Изменить информацию о себе</a></p>
<div class="<?=$pageinfo=='edit'?"":"hidden"?> form-block bordered" id="reg_form">
	
	<h5>Профайл пользователя</h5>
	
	<?if($tpl['user']['errors_mail']){?>
		<div class="error" id="addcall-error">Неверный формат email или такой email уже используется!</div>
	<?} else if($tpl['user']['send_status']){?>
	    <div class="error" id="addcall-error">Ваши данные успешно изменены!</div>	
	<?}?>
	
	<form action="<?=$r_url?>?pageinfo=edit" method="post" class=formtxt>
	
	<p>
	<label for="fio"><b>ФИО:</b> </label>
	<input type=text name=fio id=fio size=50 maxlength=100  value='<?=$tpl['user']['data']['fio']?>'>
	</p>
	<p>
	<label for="email"><b>Ваш email:</b> </label>
	<input type=text name=email id=email size=50 maxlength=100  value='<?=$tpl['user']['data']['email']?>'>
	</p>
	<p>
	<label for="url"><b>Домашняя страница:</b> </label>
	<input type=text name=url id=url size=50 maxlength=100  value='<?=$tpl['user']['data']['url']?>'>
	</p>
	<p>
		<label for="sex"><b>Пол:</b> </label>
		<select name=sex >
		<option value=0 <?=selected($tpl['user']['data']['sex'],0)?>>мужской</option>
		<option value=1 <?=selected($tpl['user']['data']['sex'],1)?>>женский</option>
		</select>
	</p>
	<p>
		<label for="city"><b>Город:</b> </label>
		<select name=city >
		<?for ($i=0; $i<count($city_array); $i++){?>
			<option value="<?=$i?>" <?=selected($tpl['user']['data']['city'],$i)?>><?=$city_array[$i]?></option>
		<?}?>	
		</select>
	</p>
	<?
	if (!preg_match ('/^\-{0,1}[0-9]{1,}/', $tpl['user']['data']['city'])) {?>
	<p>
	<label for="email"><b>Город (если нет в списке):</b> </label>
	<input type=text name=city1 id=city1 size=50 maxlength=100  value='<?=$tpl['user']['data']['city']?>'>
	</p>
	<?}?>
	<p>
	<label for="daybith"><b>Дата рождения:</b> </label>
	<select name=daybith class=formtxt>
	<?
		for ($i=1; $i<32; $i++){?>
			<option value=<?=$i?> <?=selected((int)date('d',$tpl['user']['data']['datebithday']),$i)?>><?=$i?></option>		
		<?}?>
	</select>
	<select name=monthbith class=formtxt>
	<?
	for ($i=1; $i<13; $i++){?>
		<option value=<?=$i?> <?=selected((int)date('m',$tpl['user']['data']['datebithday']),$i)?>><?=$month2[$i]?></option>	
	<?}?>
	</select>
	<select name=yearbith class=formtxt>
		<?for ($i=1900; $i<2001; $i++){?>
			<option value=<?=$i?> <?=selected((int)date('Y',$tpl['user']['data']['datebithday']),$i)?>><?=$i?></option>		
		<?}?>
	</select>
	</p>
	<p>
	<label for="phone"><b>Телефон:</b> </label>
	<input type=text name=phone id=phone size=50 maxlength=100  value='<?=$tpl['user']['data']['phone']?>'>
	</p>
	<p>
		<label for="phone"><b>Адрес:</b></label>
	    <textarea name=adress id=adress cols=40 rows=5><?=$tpl['user']['data']['adress']?></textarea>
	</p>
	<p>
		<label for="phone"><b>Информация о Вас:</b></label>
	    <textarea name=text id=text cols=40 rows=5><?=$tpl['user']['data']['text']?></textarea>
	</p>
	<p>
		<label for="emailcheck"><b>Подписка на новости:</b> </label>
		<select name=emailcheck >
		<option value=0 <?=selected($tpl['user']['data']['emailcheck'],0)?>>Нет</option>
		<option value=1 <?=selected($tpl['user']['data']['emailcheck'],1)?>>Да</option>
		</select>
	</p>		
	<p>
		<label for="sms"><b>Получать SMS-уведомления:</b> </label>
		<select name=sms >
		<option value=0 <?=selected($tpl['user']['data']['sms'],0)?>>Нет</option>
		<option value=1 <?=selected($tpl['user']['data']['sms'],1)?>>Да</option>
		</select>
	</p>			
	<div class=center><input class="button25" name="submit" value="Отправить"  type="submit"></div>
	</form>	
</div>
<p><a href="#" onclick="showInvis('pwd_form');return false">Изменить пароль</a></p>

<div class="<?=$pageinfo=='password'?"":"hidden"?> form-block bordered" id="pwd_form">
	
	<h5>Изменение пароля</h5>	
	<?if($tpl['user']['error_pwd']){?>
		<div class="error" id="addcall-error"><?=$tpl['user']['error_pwd']?>!</div>
	<?} else if($tpl['user']['send_status']){?>
	    <div class="error" id="addcall-error">Ваши данные успешно изменены!</div>	
	<?}?>

	<form action="<?=$r_url?>?pageinfo=password" method="post" class=formtxt>	
	<p>
	<label for="userpassword1"><b>Старый пароль:</b> </label>
	<input type="password" name=userpassword1 id=userpassword1 size=50 maxlength=100  value='<?=$userpassword1?>'>
	</p>
	<p>
	<label for="userpassword2"><b>Новый пароль:</b> </label>
	<input type="password" name=userpassword2 id=userpassword2 size=50 maxlength=100  value='<?=$userpassword2?>'>
	</p>
	<p>
	<label for="userpassword3"><b>Подтверждение нового пароля:</b> </label>
	<input type="password" name=userpassword3 id=userpassword3 size=50 maxlength=100  value='<?=$userpassword3?>'>
	</p>		
	<div class=center><input class="button25" name="submit" value="Сохранить"  type="submit"></div>
	</form>	
</div>
<p><a href="#" onclick="showInvis('collector_form');return false">Профайл коллекционера</a></p>
<div class="<?=$pageinfo=='collectors'?"":"hidden"?> form-block bordered" id="collector_form">
	
	<h5>Профиль коллекционера</h5>	
	<form action="<?=$r_url?>?pageinfo=collectors" method="post" class=formtxt enctype="multipart/form-data">
		<input type="hidden" name="MAX_FILE_SIZE" value="200000" />				
		<input type=hidden name=collector value='<?=$tpl['user']["collectors"]['id']?>'>
		<?if($tpl['user']['error_coll']){?>
			<div class="error" id="addcall-error"><?=$tpl['user']['error_coll']?>!</div>
		<?} else if($tpl['user']['send_status']){?>
		    <div class="error" id="addcall-error">Ваши данные успешно изменены!</div>	
		<?}?>

		
		<? if($tpl['user']['data']['photo']){?>
		<p>
		<label for="phone"><b>Фотография</b></label>
		<img src="<?=$cfg['site_dir']?>collector/images/<?=$tpl['user']['data']['photo']?>?v=<?=rand(90)?>" width="200" />	    
		</p>
		<?}?>
		<p>
		<label for="phone"><b>Загрузить фотографию(JPG, GIF не более 200 кб)</b></label>

		<input type="file" name="file"  />    
		</p>
		<p>
		<div class="left" style="height: 210px"><label for="interest"><b>Коллекционирую:(<SPAN class=red>*</span>)</b></label></div>
		<div><?for ($i=1; $i<=sizeof($interests); $i++){?>
			<input type="Checkbox" name=interest<?=$i?> <?=in_array($i,$tpl['user']["collectors"]['interests'])?"checked":""?>><?=$interests[$i][0]?><br>
		<?}?></div>
	<div class=center><input class="button25" name="submit" value="Сохранить"  type="submit"></div>
	</form>	
</div>		
<p><a href="<?=$cfg['site_dir']?>advice/index.php">Увеличить свой рейтинг</a></p>
<p><a href="<?=$cfg['site_dir']?>subscribe/index.php">Подписка <font color="red">NEW</font></a></p>
<p><a href="<?=$cfg['site_dir']?>shopcoins/order.php">Ваши заказы в магазине</a></p>
<p><a href="<?=$cfg['site_dir']?>auction/index.php?page=mylot">Ваши лоты на аукционе</a></p>
</div>