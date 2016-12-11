<?php //include $cfg['path'] . '/views/_mobile/pagetop/shortmenu.tpl.php'; ?>
<?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 

<div class=filters>
<a href="#" onclick="showInvis('search-params-place');return false;" >Фильтры новостей</a>

<div id='search-params-place'  class="search-params-place">

<?include(DIR_TEMPLATE.'_mobile/leftmenu/filters_news.tpl.php');?>

</div>
</div>
