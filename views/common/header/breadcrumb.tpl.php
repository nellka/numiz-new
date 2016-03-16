<div id="navigation">
      <ul class="breadcrumb">
        <?php foreach ($tpl['breadcrumbs'] as $breadcrumb) { ?>
        <li><? if($breadcrumb['class']=='current'){?>
            <span><?=$breadcrumb['text']?></span>
       <? } else {?>
            <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?}
        } ?>
      </ul>
</div>