<?php

$this->breadcrumbs=array(
	'Боты'=>array('bots'),
	'Создать',
);

$this->renderPartial('_menuLoad');
?>

<h1>Добавить бота</h1>

<?php echo $this->renderPartial('_formbot', array('model'=>$model)); ?>