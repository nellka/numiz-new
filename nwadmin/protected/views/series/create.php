<?php

$this->breadcrumbs=array(
	'Серии монет'=>array('index'),
	'Создать',
);
$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Серии монет', 'url'=>array('index')),
);
?>

<h1>Добавить серию</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>