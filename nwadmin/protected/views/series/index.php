<?php 
$this->breadcrumbs=array(
	'Серии монет',
);
$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Серии монет', 'url'=>array('index')),
);

echo "<h3>Серии монет</h3>";

echo CHtml::link("Создать",array('create'));
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ip-bot-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
   // 'filter'=>null,
	'columns'=>array(
		 array(
            'name'=>'image',
           'type' => 'raw',
            'value'=>'(!empty($data->image))?CHtml::image(Series::$http_path_to_file."/$data->image","",array("style"=>"max-width:100px;max-height:100px;")):"no image"',
 
        ),
        array(
            'name'=>'name',
            'value'=>'$data->name',
            'type'=>'raw',
        ),
        array(
            'name' => 'countrygroup',
            'value'=>'$data->group->name',
        ),
        'whereselect',
		'details',
		array(
            'name' => 'status',
            'value'=>'Shopcoinsseotext::$statuses[$data->status]',
            /*'filter'=>false,*/
        ),

		array(
			'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
             /*'buttons'=>array(
		        'update' => array(		           
		            'url'=>'Yii::app()->createUrl("cp/tools/updatebot", array("id"=>$data->id))',
		        ),
		        'delete' => array(
		            'url'=>'Yii::app()->createUrl("cp/tools/deletebot", array("id"=>$data->id))',
		        ),
			),*/
		),
	),
)); ?>
