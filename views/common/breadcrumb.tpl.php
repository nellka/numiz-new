<div id="navigation">
    <div class="breadcrumb">
            <?php         
            foreach ($tpl['breadcrumbs'] as $breadcrumb) { ?>
           <? if($breadcrumb['base_href']==$tpl['current_page']){?>
                <span><?=$breadcrumb['text']?></span>
           <? } else {?>
                <a href="<?php echo $breadcrumb['href']; ?>"><span><?php echo $breadcrumb['text']; ?></span></a><span>&nbsp;/</span>
            <?}
            } ?>
    </div>
</div>