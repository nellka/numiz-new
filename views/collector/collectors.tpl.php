<? include $DOCUMENT_ROOT."/config.php";

$MainFolderMenuX=3; 
$MainFolderMenuY=5;

$title = "Клуб Нумизмат | Портфель пользователя ";
include $in."top.php"; 
table_top ("100%", 0, "Регистрация коллекционера", 1);

include_once "config.php";
include "funct.php";

for ($i=1; $i<=sizeof($interest); $i++)
{
	if (${"interest".$i}=="on")
	{
		$interest[$i][1] = 1;
	}
}

$myclass = new Collectors ($collectors, $user, $fio, $email, $url, $city, $city1, $phone, 
	$text, $adress, $interest);

if ($action=="add" and $submit)
{
	$error_number = $myclass->Collectors_add();
	if ($error_number<0)
	{
		$submit = 0;
	} else {
		echo "<b>Данные успешно добавлены</b>
		<br>Автоматический переход на страницу Вашего профайла осуществится через 3 секунды<br><br>";
		echo "<META HTTP-EQUIV=Refresh CONTENT=3;URL=\"".$in."user/profile.php\">";
	}
}

if ($action=="edit" and $submit)
{
	$error_number = $myclass->Collectors_edit();
	if ($error_number<0)
	{
		$submit = 0;
	} else {
		echo "<b>Данные изменены!</b>
		<br>Автоматический переход на страницу Вашего профайла осуществится через 3 секунды<br><br>";
		echo "<META HTTP-EQUIV=Refresh CONTENT=3;URL=\"".$in."user/profile.php\">";
	}
}

if (($action=="" or $action=="edit" or $action=="add") and !$submit)
{
	if (!$error_number)
	{
		$info = $myclass->User_info();
		$info = $myclass->Collectors_info();
	} else {
		$myclass->error_number = $error_number;
	}
	echo $myclass->Collectors_form();
}

include $in."user/menu.php";

//include $in."bottom.php";
?>
