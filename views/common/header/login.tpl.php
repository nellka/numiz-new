<div class='user_details' id='user_top_block'>

<?php 
if (!$tpl['user']["is_logined"]) {
?>
<div class="reg_link link_group"> 
 <a class="iframe" style="text-decoration:underline;" href="<?=$cfg['site_dir']?>user/login.php?ajax=1" id='login_form'>Войти</a> 
 <a class="iframe" style="text-decoration:underline;" href="<?=$cfg['site_dir']?>user/registration.php?ajax=1" title='Регистрация' id='reg_form'>Зарегистрироваться</a>
</div>


<?
if(isset($tpl['user']["error_login"])){
  //  echo "<center><b class=txt><font color=red>Несоответствие логина и пароля!</font></center></b>";
}
?>
<?} else {
	?>
	<p>Здравствуйте, <b><?=$tpl['user']['username']?></b>!</p>
	<p><a href="http://numizmatik.ru/user/profile.php"  title="Просмотр/редактирование личных данных/настроек">Ваш профайл</a></p>
	<p>
	<form action="<?=$_SERVER["REQUEST_URI"]?>" method="POST">
	<input type="hidden" value="1" name="logout" id="logout">
	<input type="submit" class="yell_b"  value="Выход">
	</form>

<?}?>
</div>