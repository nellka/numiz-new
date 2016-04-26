 <div class="triger clearfix" id='subsription'>
	<div class="error" id="subsription-error"></div>	
    <div class="center bacgroundgray" id='subsription-form'>    
		<div class="custom_socials abold">
			<center>
				Всегда актуальная информация! <br>
		        <a href="#" class="custom_social decoratnone"> <img border="0" alt="" src="<?=$cfg['site_dir']?>images/social/instagram.gif"> </a> 
		        <a href="#" class="custom_social decoratnone"> <img border="0" alt="" src="<?=$cfg['site_dir']?>images/social/youtube.gif"> </a>
		        <a href="#" class="custom_social decoratnone"> <img border="0" alt="" src="<?=$cfg['site_dir']?>images/social/vk.gif"> </a>         
		        <a href="#" class="custom_social decoratnone"> <img border="0" alt="" src="<?=$cfg['site_dir']?>images/social/facebook.gif"> </a> 	
			</center>
		</div>
	</div>
</div>

<div id='footer'>
	<br style="clear:both;">
	<div class="description">
		<table class="width100">
			<tr>
				<td><img src="<?=$cfg['site_dir']?>images/mobile/visa.jpg"></td>
				<td><img src="<?=$cfg['site_dir']?>images/mobile/mastercard.jpg"></td>
				<td><img src="<?=$cfg['site_dir']?>images/mobile/sberbank.jpg"></td>
				<td><img src="<?=$cfg['site_dir']?>images/mobile/russianpost.jpg"></td>
			</tr>
			<tr>
				
				<td><img src="<?=$cfg['site_dir']?>images/mobile/qiwi.jpg"></td>
				<td><img src="<?=$cfg['site_dir']?>images/mobile/vtb.jpg"></td>
					<td><img src="<?=$cfg['site_dir']?>images/mobile/paypal.jpg"></td>
				<td><img src="<?=$cfg['site_dir']?>images/mobile/icon.jpg"></td>
			</tr>			
		</table>
		<div class="fontsize12">
			При полном или частичном использовании материалов <br> ссылка на Клуб Нумизмат обязательна.<br>
			2001 - <?=date('Y',time())?> &copy; Клуб Нумизмат
			<br><a href="mailto:administrator@numizmatik.ru">administrator@numizmatik.ru</a><br>
			<br>Крупнейший портал для коллекционеров. Монеты.<br> Покупка и продажа. Боны, книги, антиквариат. <br>
			Разнообразные аксессуары для монет, альбомы<br> для монет. Оценка стоимости монет. Москва.		 <br> <br>	
		</div>
		
		 <div class="center bacgroundgray abold">
			<br>		 
			<center><a href="<?=$_SERVER['REQUEST_URI']?>" onclick="$.cookie('fv', 1);">Полная версия сайта</a></center>
			<br>
		 </div>
		
	</div>
	 <?php include $cfg['path'] . '/views/_mobile/common/metrica.tpl.php'; ?>
</div>
