<div class="main_context bordered rating table">
<h1>Библиотека нумизмата</h1>

<br /><p>В этом разделе размещены материалы из различных источников, посвященные вопросам и проблемам нумизматики, бонистики, истории и географии. Часть материалов была прислана посетителями сайта, часть – попала к нам через третьих лиц в измененном виде. Авторы некоторых статей нам неизвестны.</p>
<p>Мы просим откликнуться авторов размещенных на этом портале статей для урегулирования вопросов, связанных с авторскими правами. Вы можете дать согласие на публикацию своей статьи, либо сообщить условия, на которых ваша статья может быть опубликована. В случае если вы возражаете против размещения статьи на нашем портале, она будет немедленно удалена.</p> 
<p>Вы можете размещать свои материалы по теме данного портала, перейдя по <a href="<?=$in?>advice/index.php#02">следующей ссылке</a>.</p>
<p>Также предлагаем вам <a href=<?=$cfg['site_dir']?>subscribe/index.php><font color=red>подписаться на новые статьи</font></a>...</p>
<form action='<?=$cfg['site_dir']?>biblio' method=post>
<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=#ecb34e>
    <tr><td colspan=3 bgcolor=black height=1 width=100%></td></tr>
    <tr><td class=formtxt align=center>Слово:</td>
        <td class=formtxt>Искать в:</td><td>&nbsp;</td>
    </tr>
    <tr>
        <td align=center><input type=text name=s size=20 class=formtxt value='<?=$s?>'></td>
        <td class=formtxt>
            <select name=st class=formtxt>
                <option value=1 <?=selected(1, $st)?>>названии</option>
                <option value= <?=selected(2, $st)?>>ключевом слове</option>
                <option value=3 <?=selected(3, $st)?>>тексте</option>
                <option value=4 <?=selected(4, $st)?>>во всех</option>
            </select>
        </td>
        <td><input type=submit name=submit value='Искать статью' class='button25'></td>
    </tr>
    <tr><td colspan=3 width=615>&nbsp;</td></tr>
    <tr><td colspan=3 bgcolor=black height=1 width=615></td></tr>
</table>
</form>

<br><p align=right><a href='<?=$_SERVER['HTTP_REFERER']?>'><< Вернутся к статьям библиотеки</a></p>

<p><b><?=date('d.m.Y',$tpl['biblio']['data']['date'])?>. &nbsp;&nbsp;</p>

<?php
if ($tpl['biblio']['data']['parent']) {
	echo $tpl['biblio']['data']['name']." (продолжение)</b>";
} else {
	echo $tpl['biblio']['data']['name']."</b>";
}?>
<p><?=$tpl['biblio']['data']['text']?></p>
	
<?	
if (!$tpl['biblio']['data']['parent']){
	//является вершиной дерева
	$i=2;
	foreach ($tpl['pages'] as $rows1){
		if ($i==2) echo "<p class=txt align=center>Страницы: 1 ";
		echo "<a href=\"biblio/".$rows1['correct_link']."\">$i</a>";
		$i++;
	}
} else {
	//является лепестком
	$i=1;
	foreach ($tpl['pages'] as $rows1){
		if ($i==1) echo "<p class=txt align=center>Страницы: ";
		if ($rows1['biblio']!=$biblio){
			echo "<a href=\"biblio/".$rows1['correct_link']."\">$i</a>";
		} else {
			echo $i." ";
		}
		$i++;
	}
	echo "</center>";
}	

if($tpl['biblio']['data']['source']){
	if ($tpl['biblio']['data']['typesource']==1){
		echo "<p align=right>Источник: <a href='".$tpl['biblio']['data']['source']."' target=_blank rel=nofollow>".$tpl['biblio']['data']['source']."</a>";
	} elseif ($tpl['biblio']['data']['typesource']==2)	{
		echo "<p align=right>Источник: книга \"".$tpl['biblio']['data']['source']."\"";
	} elseif ($tpl['biblio']['data']['typesource']==3) {
		echo "<p align=right>Источник: журнал \"".$tpl['biblio']['data']['source']."\"";
	} elseif ($tpl['biblio']['data']['typesource']==4) { 
		echo "<p align=right>Источник: \"".$tpl['biblio']['data']['source']."\"";
	} elseif ($tpl['biblio']['data']['typesource']==5) { 
		echo "<p align=right>Источник: <a href=mailto:".$tpl['biblio']['data']['source'].">".$tpl['biblio']['data']['source']."</a>";
	}
}
if ($tpl['biblio']['data']['author']) echo "<p align=right>Автор: ".$tpl['biblio']['data']['author'];
if ($tpl['biblio']['data']['email']) echo "<p align=right>Email: <a href=mailto:".$tpl['biblio']['data']['email'].">".$tpl['biblio']['data']['email']."</a>";

echo "<br><br>";
	
if ($biblioByKeywords) {
	echo "<p class=txt><b>Статьи по теме:</b></p><ul>";
	foreach ($biblioByKeywords as $rows){
		$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["biblio"].".html";
		echo "<p class=txt><li><a href=\"".$cfg['site_dir']."biblio/$namehref"."\">".$rows["name"]."</a></li>";
	}
	echo "</ul>";
}
echo "<br><br>";

if ($newsbyKeywords){
	echo "<p class=txt><b>Новости по теме:</b></p><ul>";
	$sql = "select news, name from news where $sql1 order by date desc limit 5;";

	foreach ($newsbyKeywords as $rows){
		$namehref = contentHelper::strtolower_ru($rows["name"])."_n".$rows["news"].".html";
		echo "<p class=txt><li><a href=\"".$cfg['site_dir']."news/$namehref"."\">".$rows["name"]."</a></li>";
	}
	echo "</ul>";
}                                                                                                                         