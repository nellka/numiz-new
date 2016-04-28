

<?php 
if (!$tpl['user']["is_logined"]) {
?>
<div class='user_details' id='user_top_block'>
<div class="reg_link link_group"> 
 <a style="text-decoration:underline;" onclick="showWin('<?=$cfg['site_dir']?>user/login.php?ajax=1',500);return false;" href="#" id='login_form'>Войти</a> 
 <a style="text-decoration:underline;" onclick="showWin('<?=$cfg['site_dir']?>user/registration.php?ajax=1',500);return false;" href="#" title='Регистрация' id='reg_form'>Зарегистрироваться</a>
</div>


<?
if(isset($tpl['user']["error_login"])){
  //  echo "<center><b class=txt><font color=red>Несоответствие логина и пароля!</font></center></b>";
}
?>
<?} else {
	?>
	<div class='user_details' id='user_top_block' style='padding:20px 0 0'>
	<p>Здравствуйте, <b><?=$tpl['user']['username']?></b>!</p>
	<p><a href="http://www.numizmatik.ru/user/profile.php"  title="Просмотр/редактирование личных данных/настроек">Ваш профайл</a></p>
	<p>
	<form action="<?=$_SERVER["REQUEST_URI"]?>" method="POST">
	<input type="hidden" value="1" name="logout" id="logout">
	<input type="submit" class="yell_b"  value="Выход">
	</form>

<?}?>
</div>