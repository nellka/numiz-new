<?
include_once $DOCUMENT_ROOT."/config.php";
include_once $DOCUMENT_ROOT."/wadmin/password.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel=STYLESHEET type=text/css href=<? echo $in;?>bodka.css>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<body>

<?

$brouser = strtolower($_SERVER['HTTP_USER_AGENT']);
//echo $brouser;
if (!substr_count($brouser,"firefox/3") && !substr_count($brouser,"firefox/4") && !substr_count($brouser,"firefox/5") && !substr_count($brouser,"firefox/6"))
	echo "<h3><font color=red>Для корректной работы необходимо перейти на броузер Firefox 3.*</font></h3><br>";


if ($submit) {
	
	//print_r($_FILES); //почемуто максимум можно залить 40 штук
	//echo sizeof($smalluploadimage1)." - ".sizeof($biguploadimage1);

	if (!trim($name))
		$error[] = "Укажите название альбома";
		
	$typecoins = intval($typecoins);
	
	//$smalluploadimage1 = $smalluploadimage1["tmp_name"];
	//$smalluploadimage2 = $smalluploadimage2["tmp_name"];
	
	
	$sql = "select count(*) from shopcoinswrite where album='".trim($name)."' and `check`>0;";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	
	if ($rows[0]>0)
		$error[] = "Такой альбом есть в БД на описании";
		
	if (!$list)
		$error[] = "Укажите количество листов в альбоме";
		
	if (!$collist)
		$error[] = "Укажите ширину листа в ячейках";
		
	if (!$rowslist)
		$error[] = "Укажите высоту листа в ячейках";
		
	if (!sizeof($smalluploadimage1))
		$error[] = "Не выбраны файлы маленьких изображений";
		
	if (!sizeof($biguploadimage1))
		$error[] = "Не выбраны файлы больших изображений";
	
	

	if (sizeof($smalluploadimage1)+sizeof($smalluploadimage2) != sizeof($biguploadimage1)+sizeof($biguploadimage2))
		$error[] = "Не соответствует количество выбранных маленьких изображений с большими.";
		
	//var_dump($list*$collist*$rowslist,$smalluploadimage1,$smalluploadimage2);
	if ($list*$collist*$rowslist != floor((count($smalluploadimage1)+count($smalluploadimage2))/2)) 
		$error[] = "Не соответствует количество выбранных изображений с указанным форматом альбома.";
	
	if ($list*$collist*$rowslist > 99) 
		$error[] = "Одновременная загрузка более 99 позиций запрещена!";
	
	if (!sizeof($error)) {
		
		$sql = "select max(shopcoinswrite) from shopcoinswrite;";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		
		$i=$rows[0]+1;
		$j=$rows[0]+1;
		//echo $smalluploadimage;
		foreach ($smalluploadimage1 as $key=>$value) {
		
			if (filesize($value)>1024) {
			
				copy ($value,"/var/www/htdocs/numizmatik.ru/detailscoins/images/".$i.".jpg");
				$Small_img[$i] = $i.".jpg";
				$i++;
			}
			else {
				$tmpsize = '';
				$Size = '';
				$f = fopen ($value, "r");
				while (!feof($f) && $f) {
					$tmpsize = fgets ($f);
					//echo substr_count(" cm ",$tmpsize)."<br>";
					if (substr_count($tmpsize," cm ")==2)
						$Size = $tmpsize;
				}
				fclose($f);
	
			//$Size = fread($f, 128);
				$Size = str_replace("cm","", $Size);
				$Size = str_replace("  ","x", $Size);
				$Size = trim($Size);
			
				$mytmp = explode("x",$Size);
			
				$Width[$j] = 10*($mytmp[0]+$mytmp[2])/2;
				$Height[$j] = 10*($mytmp[1]+$mytmp[3])/2;
				//echo "<br>".$Width[$file]." - ".$Height[$file];
				if ($Width[$j] >= 15 and $Width[$j] <= 44)
					$Width[$j] = $Width[$j]*(0.97 - (44-$Width[$j])/290);
			
				if ($Height[$j] >= 15 and $Height[$j] <= 44)
					$Height[$j] = $Height[$j]*(0.97 - (44-$Height[$j])/290);
				
				$j++;
			}
		}
		
		foreach ($smalluploadimage2 as $key=>$value) {
		
			if (filesize($value)>1024) {
			
				copy ($value,"/var/www/htdocs/numizmatik.ru/detailscoins/images/".$i.".jpg");
				$Small_img[$i] = $i.".jpg";
				$i++;
			}
			else {
				$tmpsize = '';
				$Size = '';
				$f = fopen ($value, "r");
				while (!feof($f) && $f) {
					$tmpsize = fgets ($f);
					if (substr_count($tmpsize," cm ")==2)
						$Size = $tmpsize;
				}
				fclose($f);
				
	
			//$Size = fread($f, 128);
				$Size = str_replace("cm","", $Size);
				$Size = str_replace("  ","x", $Size);
				$Size = trim($Size);
			
				$mytmp = explode("x",$Size);
			
				$Width[$j] = 10*($mytmp[0]+$mytmp[2])/2;
				$Height[$j] = 10*($mytmp[1]+$mytmp[3])/2;
				//echo "<br>".$Width[$file]." - ".$Height[$file];
				if ($Width[$j] >= 15 and $Width[$j] <= 44)
					$Width[$j] = $Width[$j]*(0.97 - (44-$Width[$j])/290);
			
				if ($Height[$j] >= 15 and $Height[$j] <= 44)
					$Height[$j] = $Height[$j]*(0.97 - (44-$Height[$j])/290);
				
				$j++;
			}
		}
		
		$i=$rows[0]+1;
		$nl = 0;
		$ns = 1;
		$mainnumber = trim($name);
		//echo $mainnumber;
		foreach ($biguploadimage1 as $key=>$value) {
		
			if (filesize($value)>1024) {
			
				copy ($value,"/var/www/htdocs/numizmatik.ru/detailscoins/images/".$i."_b.jpg");
				$Big_img[$i] = $i."_b.jpg";
				
				if ($collist*$rowslist>9 && $ns<10)
					$ns = "0".$ns;
				if ($collist*$rowslist>99 && $ns<100)
					$ns = "0".$ns;
					
				$number = $mainnumber.$nl."-".$ns;
				echo $number."<br>";
				$sql_ins = "insert into shopcoinswrite (`shopcoinswrite`,`image`,`image_big`,`album`,`number`,`width`,`height`,`dateinsert` ,`check`,ourdateprice,materialtype,idadmin,typecoins) 
						values ('".$i."','".$Small_img[$i]."','".$Big_img[$i]."','".$name."','".$number."','".$Width[$i]."','".$Height[$i]."','".time()."','1','$ourdateprice','$materialtype','$PHP_AUTH_USERID',$typecoins);";
				$result = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				
				$i++;
				
				if ($ns == $collist*$rowslist) {
				
					$ns = 0;
					$nl++;
				}
				$ns++;
			}
			else {
				
				continue;
			}
		}
		foreach ($biguploadimage2 as $key=>$value) {
		
			if (filesize($value)>1024) {
			
				copy ($value,"/var/www/htdocs/numizmatik.ru/detailscoins/images/".$i."_b.jpg");
				$Big_img[$i] = $i."_b.jpg";
				
				if ($collist*$rowslist>9 && $ns<10)
					$ns = "0".$ns;
				if ($collist*$rowslist>99 && $ns<100)
					$ns = "0".$ns;
					
				$number = $mainnumber.$nl."-".$ns;
				echo $number."<br>";
				$sql_ins = "insert into shopcoinswrite (`shopcoinswrite`,`image`,`image_big`,`album`,`number`,`width`,`height`,`dateinsert` ,`check`,ourdateprice,materialtype,idadmin,typecoins) 
						values ('".$i."','".$Small_img[$i]."','".$Big_img[$i]."','".$name."','".$number."','".$Width[$i]."','".$Height[$i]."','".time()."','1','$ourdateprice','$materialtype','$PHP_AUTH_USERID',$typecoins);";
				$result = mysql_query($sql_ins);
				//echo $sql_ins."<br>";
				
				$i++;
				
				if ($ns == $collist*$rowslist) {
				
					$ns = 0;
					$nl++;
				}
				$ns++;
			}
			else {
				
				continue;
			}
		}
	}
	
	
}

if($name) {

	$strpos = strlen($name)-1;
	$temp = intval(substr($name,$strpos))+1;
	$name = substr($name,0,$strpos).$temp;
}
//else
//	$ourdateprice = "";

echo '<form action="redaction.php" enctype="multipart/form-data" method="post" name="form1">
<table border=1 cellpadding=2 cellspacing=0 width=800 class=tboard>
'.(count($error)?'<tr><td colspan=4 class=tboard align=left><font color=red><b>&nbsp;'.implode('<br>&nbsp;',$error).'</b></font></td></tr>':'').'
<tr><td class=tboard>Название альбома</td><td><input type="text" value="'.($name?$name:"").'" name="name" class=tboard></td><td colspan=2><select name=typecoins class=tboard><option value=0>Обычные<option value=1>Колеса</select></td></tr>
<tr><td class=tboard>Кол-во листов</td><td><input type="text" value="'.($list?$list:"").'" name="list" class=tboard></td><td colspan=2>&nbsp;</td></tr>
<tr><td class=tboard>ширина листа</td><td><input type="text" value="'.($collist?$collist:"").'" name="collist" class=tboard></td><td colspan=2>&nbsp;</td></tr>
<tr><td class=tboard>высота листа</td><td><input type="text" value="'.($rowslist?$rowslist:"").'" name="rowslist" class=tboard></td><td colspan=2>&nbsp;</td></tr>
<tr><td class=tboard>маленькие изображения1</td><td><input type="file" min=1 max=9999 multiple id=smalluploadimage1 name=smalluploadimage1[] class=tboard></td><td class=tboard>маленькие изображения2</td><td><input type="file" min=1 max=9999 multiple id=smalluploadimage2 name=smalluploadimage2[] class=tboard></td></tr>
<tr><td class=tboard>большие изображения1</td><td><input type="file" min=1 max=9999 multiple id=biguploadimage1 name=biguploadimage1[] class=tboard></td><td class=tboard>большие изображения2</td><td><input type="file" min=1 max=9999 multiple id=biguploadimage2 name=biguploadimage2[] class=tboard></td></tr>
<tr><td align=center class=tboard><select name=materialtype class=txt><option value=1>Монеты<option value=4>Подарочные наборы</option><td align=center class=tboard>Наш прайс дата:<input type="text" value="'.$ourdateprice.'" name="ourdateprice" class=tboard><td colspan=2 align=center class=tboard><input onclick="javascript:if(document.form1.ourdateprice.value){return true;}else{alert(\'Не заполнено поле Наш прайс дата\');return false;};" type="submit" value="Загрузить" name="submit" class=tboard></td></tr>
</table></form>';

?>
</body>
</html>
<?
die();
?>
