<?php
$this->breadcrumbs=array(
 'seo'=>array('post/index'),
	'Создать Seo-text'
	
);
$this->menu=array(
	array('label'=>'Список', 'url'=>array('index'))	
);
?>
<h1>Создать Seo-text</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>