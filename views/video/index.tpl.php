<div class="main_context bordered">
<h1>Видео для нумизматов</h1>

<p class=txt>Приветствуем Вас уважаемые посетители.<br><br>
В данном разделе сайта мы собираем интересные и поучительные видеоматериалы. Которые помогают начинающим, закрепляют у бывалых и вызывает улыбку у профессионалов.<br>
Данный раздел будет постепенно наполняться и расширяться. Как за счет наших, так и возможно ваших материалов, которые Вы можете прислать на наш электронный адрес.<br><br>
 
Нумизматика не только увлечение, хобби и источник вложений, это целая жизнь. И мы стараемся сделать эту жизнь увлекательнее и живее.<br><br>
<?
if ($tpl['user']['user_id']){?>
Вы можете предложить нам ссылку на интересные видеоматериалы, а также оставить пожелания по поводу данного раздела. <br>
При отправке нам ссылки и пожелания Ваш рейтинг на сайте будет увеличен на 10 пунктов.  <br>
<a href=#label2>Отправить ссылку( пожелания)</a><br><br>
<?} ?>
С Уважением, Клуб Нумизмат
	
<br>
<br>
<table border=0 align=center class="table_cl">
	<tr><td class='tboard <?=(!$theme?"table_active_block":"")?>'>
		<a href=video.php title='Все видео'>Все видео</a>
	</td>
	<?
	foreach ($arraythemevideo as $key=>$value) {
		echo "<td class='tboard ".($theme==$key?"table_active_block":'')."'><a href=".$cfg['site_dir']."video.php?theme=$key title='".$value."'>$value</a></td>";
	}?>
	</tr>
</table>

<p>
<script>
function ShowVideo(numbervideo,typeplayer) {

	var myDiv = document.getElementById('thisplayvideo');
	var str = '<center>';
	if (typeplayer==1) {
		str += '<object style="height: 390px; width: 640px">';
		str += '<param name="movie" value="http://www.youtube.com/v/'+numbervideo+'?version=3&autoplay=1&feature=player_embedded">';
		str += '<param name="allowFullScreen" value="true">';
		str += '<param name="allowScriptAccess" value="always">';
		str += '<embed src="http://www.youtube.com/v/'+numbervideo+'?autoplay=1&feature=player_embedded" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="415"></object>';
	}
	else if (typeplayer==2) {
	
		//str += '<iframe title="" width="640" height="415" src="http://video.meta.ua/vpla/video/Player.swf?version=1.3.4b&fileID='+numbervideo+'&globalURL=http://meta.ua&search=true&target=new&autoplay=0"></iframe>';
		str += '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="640" height="415" id="Player" align="top"><param name="allowScriptAccess" value="always" /><param name="FlashVars" value="version=1.3.4b&fileID='+numbervideo+'&target=new&globalURL=http://meta.ua&search=true&autoplay=0&color=1" /><param name="allowFullScreen" value="true" /><param name="movie" value="http://video.meta.ua/vpla/video/Player.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#A6A6A6" /><param name="wmode" value="opaque" /><param name="border" value="0" /><embed border="0" src="http://video.meta.ua/vpla/video/Player.swf" wmode="opaque" quality="high" bgcolor="#A6A6A6" width="640" height="415" name="Player" align="top" allowScriptAccess="always" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" FlashVars="version=1.3.4b&fileID='+numbervideo+'&globalURL=http://meta.ua&search=true&target=new&autoplay=0&color=1" /></object>';
	}
	str += '<br><a href=#label1 onclick="javascript:CloseVideo();">Закрыть ролик</a></center>';
	myDiv.innerHTML = str;
}
function CloseVideo() {

	var myDiv = document.getElementById('thisplayvideo');
	var str = '';
	myDiv.innerHTML = str;
}
</script>
<p align=center><a name=label1></a
><div id=thisplayvideo></div>
</p>

<div>
<?

foreach ($tpl['video'] as $rows ){
	if($rows){
	?>
 	<div class="left video-block">
 	<?
	echo str_replace("Видео - ","",$rows['title']).'<br><a href=#label1 title="Смотреть '.$rows['title'].'" onclick="ShowVideo(\''.$rows['numberlink'].'\',\''.$rows['type'].'\');"><img src='.$cfg['site_dir'].'imagesvideo/video'.$rows['video'].'.jpg border=1 style="border-color:#000000" /></a>';
	if ($rows['whosend'] && $rows['whosend'] != "n")
		echo "<br>Видео от пользователя <strong>".$rows['whosend']."</strong>.";
		
	echo "<br><a href=".$cfg['site_dir']."tboard/read.php?tboard_title=".urlencode($rows['title'])." title='".$rows['title']."'>Обсудить ".$rows['title']."</a>";
	?>
	</div>
 <?
	
	}
}?>
 

</div>
<?

if ($tpl['user']['user_id']){?>
<form action='<?=$cfg['site_dir']?>/video.php' method=post class=wform>
	<div class="addcons_block">
	<p class=txt><a name=label2></a>
	<a class="h" href="#" onclick="return false;">Заявка на новое видео на сайте</a>
	    <div class="error"><?=implode("<br>",$tpl['video']['error'])?></div> 
		<input type=hidden name=inttostringm value='<?=md5("Numizmatik".$inttostring)?>'>
		<div id="myLink" class="table videolink">
			<p>
				<label for="inttostring"><b>Введите цифрами <b><font style='background:#ffcc66'><?=$tpl['user']['inttostring']?></font></b></label>
				<input type=text name=inttostring value='' size=8 maxlength=3>
			</p>
			<p>
				<label for="videolink"><b>Ссылка на видео:</b> </label>
				<input type=text name=videolink value='<?=$videolink?>' maxlength=200 size=50>	
			</p>
			<p>
				<label for="videolink">Что бы Вы желали увидеть еще в данном разделе:</label>
			
				<textarea name=text class=formtxt cols=50 rows=10><?=$text?></textarea>
			</p>
			
			<div class="center">
				<br>
				<br>
				<input type=submit name=submitbutton value='Отправить' class=button25>
			</div>		
		</div>
	</div>
</form>	
<?}?>

</div>