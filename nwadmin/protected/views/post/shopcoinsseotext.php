<?php
$this->breadcrumbs=array(
	'Seo тексты',
);
$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);
?>
<h1>Seo тексты </h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->title), $data->url)'
		),
		array(
			'name'=>'materialtype',
			'value'=>'Shopcoins::$sections[$data->materialtype]',
			//'filter'=>Lookup::items('PostStatus'),
		),
		array(
			'name'=>'group_id',
			'type'=>'raw',
			'value'=>'($data->group_id)?$data->group->name:""'
		),
		array(
			'name'=>'nominal_id',
			'type'=>'raw',
			'value'=>'($data->nominal_id)?$data->nominal->name:""'
		),
		array(
			'name'=>'active',
			'value'=>'Shopcoinsseotext::$statuses[$data->active]',
			//'filter'=>Lookup::items('PostStatus'),
		),
		array(
			'name'=>'create_time',
			'type'=>'datetime',
			'value'=>'date("d.m.Y H:i",$data->dateinsert)',
			'filter'=>false,
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); 

?>
