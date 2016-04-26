<?if($tpl['mv']){?>
	<center><a href="<?=$_SERVER['REQUEST_URI']?>" onclick="$.cookie('fv', 0);" class="button24" style="margin:10px 0 0;">Мобильная версия сайта</a></center>
<?}?>
<div class="top-menu" <?=($tpl['mv'])?"style='position: relative;'":""?>>  
  	<div class="wraper logo-text" id='shop-logo'>Магазин монет от Клуба Нумизмат</div>            
    <div id="header-menu"  <?=($tpl['mv'])?"style='position: relative;'":""?>>
        <?php  include $cfg['path'] . '/views/common/header/topmenu.tpl.php'; ?>
    </div> 
</div>     

<div id="header" class="wraper header" style="display:<?=$mini?'none':'block'?>;<?=($tpl['mv'])?'padding: 0':""?>">
    <?php include $cfg['path'] . '/views/common/header/top.tpl.php'; ?>
</div> 
<div id="header-mini" class="wraper header-mini" style="display:<?=$mini?'block':'none'?>; <?=($tpl['mv'])?"padding: 0":""?>">
    <?php include $cfg['path'] . '/views/common/header/top-mini.tpl.php'; ?>
</div>  
<div id=MainBascet></div>
<div class="triger" id='small-logo' style="display:none">
<?php include $cfg['path'] . '/views/common/small-logo.tpl.php';?>
</div>
        
      