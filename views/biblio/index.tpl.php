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
<ul class="sub-top-menu">
 <?php   
    foreach($tpl['groups'] as $rows){?>	
    <li class="<?=($group==$rows['group'])?"active":""?>">		
    <? if ($group==$rows['group']) {?>
            <?=$rows['name']?>
    <?} else {?>
            <a href="<?=$cfg['site_dir']?>biblio/?group=<?=$rows['group']?>"><?=$rows['name']?></a>
    <?}?>
    </li>
<?php
    }?>
</ul>
    <?PHP	
foreach ($tpl['result'] as $rows){?>
    <p class=txt><b><?=$rows['t']?>&nbsp;&nbsp;<?=$rows['name']?></b>
    <p class=txt><?=$rows['text']?></p>
    <p class=txt><a href="<?=$cfg['site_dir']?>biblio/<?=$rows['namehref']?>">Подробнее >></a></p>	
<?php
} ?>
    <div class="right">
        <?=$tpl['paginator']->printPager();?>
    </div>
</div>