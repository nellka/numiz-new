<div class="main_context bordered rating table">
<h1>Рейтинг сайтов</h1>

<script type="text/javascript" language="javascript" src="basic.js"></script>
<?
if($_SERVER['REQUEST_METHOD']=='GET'||$tpl['errors']){?>
	
	<form action="<?=$cfg['site_dir']?>rating/registration.php" method=post name="UserRegist" onSubmit="return checkmail()" class=wform>
	<div class="error"><?=implode("<br>",$tpl['errors'])?></div>
	<div class="table addcons_block">
	<a class="h" href="#" onclick="return false;">Добавление сайта в каталог</a>
	<!--<p>
		<label for="login">
		<b>Логин </b>
		</label>
		<input type=text name=login maxlength=20 value="<?=$login?>" size="40">
	</p>
	<p>
		<label for="password">
		<b>Пароль</b>
		</label>
		<input type=text name=password maxlength=20 value="<?=$password?>"  size="40">
	</p>
	<p>
		<label for="password1">
		<b>Пароль еще раз:</b>
		</label>
		<input type=text name=password1 maxlength=20 value="<?=$password1?>" size="40">
	</p>-->
	
	<p>
		<label for="email">
		<b>Ваш e-mail:</b>
		</label>
		<input type=text name=email maxlength=20 value="<?=$email?>" size="40">
	</p>
	<p>
		<label for="name">
		<b>Название сайта:</b>
		</label>
		<input type=text name=name maxlength=20 value="<?=$name?>" size="40">
	</p>
	<p>
		<label for="url">
		<b>Url сайта:</b>
		</label>
		<input type=text name=url maxlength=20 value="<?=$url?>" size="40">
	</p>

	<p>
        <label for="group"><b>Категория сайта</label>
      
        <Select name=group style="width:270px">
		<option value=0>Выберите категорию</option>
		<? foreach ($tpl['groups'] as $rows){?>
			<option value="<?=$rows['group']?>" <?=selected($rows['group'],$group)?>><?=$rows['name']?></option>		
		<?}?>
		</select>
            		                   
    </p>
	<p>
        <label for="ratingkeywords"><b>Ключевые слова</b></label>      
        <textarea name=ratingkeywords cols=30 rows=5 maxlength=255 class=formtxt><?=$ratingkeywords?></textarea>
            		                   
    </p>
    <p>
        <label for="ratingkeywords"><b>Описание сайта</b></label>      
        <textarea name=text cols=30 rows=5 maxlength=255 class=formtxt><?=$text?></textarea>
            		                   
    </p>
     <p>
        <label for="subscr">Введите цифрами <font style='background:#ffcc66' id='inttostring_text'><?=$tpl['inttostring']?></font>: </label>
        <input type=text name=inttostring value='' id=inttostring class=formtxt size=40 maxlength=3>
        <input type=hidden id=inttostringm name=inttostringm value='<?=$tpl['inttostringm']?>'>
	</p>	
    <div class="center">
	<input type=submit value="Отправить" name=submit class="button25"><br><br>
	</div>
	</div>
	</form>


<?} else {?>
	<p class=txt>Уважаемый пользователь!
	<br>Вы успешно зарегистрировались в рейтинге <b>Клуба Нумизмат</b>.
	<br><br>Спасибо Вам за предоставленные данные. В ближайшее время Ваш сайт будет проверен нашим гидом
	и если он будет соответствовать всем критериям, он будет добавлен в рейтинг.
	<br>Для участия в рейтинге Вам необходимо вставить наш счетчик, который выглядит вот так.
	<br><br><center><img src="<?=$cfg['site_dir']?>rating/images/knop.gif"></center>
	<p class=txt>Для этого необходимо вставить следущие таги в код Вашей странички
	<p class=txt><center><textarea name="code_knopka" cols=40 rows=5 class=formtxt>
	<a href=http://www.numizmatik.ru/rating/index.php?ratinguser=<?=$my_id?>>
	<img src=http://www.numizmatik.ru/rating/rating.php?ratinguser=<?=$my_id?> border=0 width=88 height=31 alt="Клуб Нумизмат | TOP 100"></a></textarea>
	</center><br>
	<p class=txt>Данный текст отправлен Вам на E-mail, указанный при регистрации<br><br>	
<?}?>
</div>